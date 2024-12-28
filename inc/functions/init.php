<?php
/*
 * init.php
 *
 * 全体の初期化処理
 *
 */

/**
 * テーマが使用する機能
 */
add_theme_support('menus');
add_theme_support('post-thumbnails');



/**
 * アップデート通知を非表示にする
 */
if (!CWP_SHOW_UPDATE_NOTICE && in_array(php_sapi_name(), array('apache2handler'))) {
	// WordPress本体のアップデート通知
	remove_action('wp_version_check', 'wp_version_check');
	remove_action('admin_init', '_maybe_update_core');
	add_filter('pre_site_transient_update_core', '__return_zero');

	// プラグインのアップデート通知
	add_filter('site_option__site_transient_update_plugins', '__return_zero');

	// テーマのアップデート通知
	remove_action('load-update-core.php', 'wp_update_themes');
	add_filter('pre_site_transient_update_themes', '__return_zero');
}



/**
 * デバッグ時以外はadminbarを非表示
 */
if (!CWP_SHOW_ADMIN_BAR) {
	add_filter('show_admin_bar', '__return_false');
}



/**
 * pタグ自動付加を除去する
 */
function cwp_remove_autop() {
	remove_filter('the_excerpt', 'wpautop');
	remove_filter('the_content', 'wpautop');
	remove_filter('acf_the_content', 'wpautop');
}
add_action('init', 'cwp_remove_autop');



/**
 * カスタム投稿タイプを生成する
 */
function cwp_generate_cpt() {
	global $cwp_cpt_definitions;
	$order = 6;

	foreach ($cwp_cpt_definitions as $definition) {
		// 投稿と固定ページは処理しない
		if (in_array($definition['default']['slug'], ['post', 'page'])) {
			continue;
		}

		$cpt_args = array(
			'labels' => array(
				'name'               => _x($definition['default']['name'], 'post type general name'),
				'singular_name'      => _x($definition['default']['name'], 'post type singular name'),
				'add_new'            => _x('新規追加', $definition['default']['slug']),
				'add_new_item'       => __($definition['default']['name'] . 'の記事を追加'),
				'edit_item'          => __($definition['default']['name'] . 'の記事を編集'),
				'new_item'           => __('新しい記事'),
				'view_item'          => __('記事を見る'),
				'search_items'       => __('記事を探す'),
				'not_found'          => __('記事はありません'),
				'not_found_in_trash' => __('ゴミ箱に記事はありません'),
				'parent_item_colon'  => ''
			),
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'show_ui'             => true,
			'query_var'           => true,
			'rewrite'             => true,
			'capability_type'     => $definition['default']['capability_type'],
			'hierarchical'        => false,
			'menu_position'       => $order,
			'supports'            => array('title', 'editor', 'thumbnail', 'revisions'),
			'has_archive'         => true,
		);

		// 固定ページ型のカスタム投稿タイプは、必要なオプションを上書き
		if ($definition['default']['capability_type'] === 'page') {
			$cpt_args['hierarchical'] = true;
			$cpt_args['supports'] = array('title', 'editor', 'thumbnail', 'revisions', 'page-attributes', 'excerpt');
		}

		// サイトパーツは管理者以外「追加」,「削除」を出来ないようにする
		if ($definition['default']['slug'] === 'siteparts' && !current_user_can('administrator')) {
			// カスタム投稿タイプの権限を設定
			$cpt_args['capabilities'] = array(
				'create_posts' => 'do_not_allow',
				'delete_post' => 'do_not_allow',
				'delete_private_posts' => 'do_not_allow',
				'delete_published_posts' => 'do_not_allow',
				'delete_others_posts' => 'do_not_allow',
			);

			// trueで、edit_xx, delete_xxの権限を設定可能にする
			$cpt_args['map_meta_cap'] = true;
		}

		// 詳細オプションがあればマージする
		if (isset($definition['cpt'])) {
			$cpt_args = wp_parse_args($definition['cpt'], $cpt_args);
		}

		// 登録
		register_post_type($definition['default']['slug'], $cpt_args);

		// 公開時、投稿IDをスラッグにセット
		if (CWP_USE_POST_ID_SLUG) {
			add_action('publish_' . $definition['default']['slug'], 'cwp_add_slug_for_posts');
		}

		// カスタムタクソノミーを作成
		if (!empty($definition['default']['template_type']['taxonomy'])) {
			// カテゴリータイプ
			$tax_args = array(
				'label'        => 'カテゴリー',
				'public'       => true,
				'show_ui'      => true,
				'hierarchical' => true,
				'rewrite'      => array('slug' => $definition['default']['slug'] . '/category')
			);

			// 詳細オプションがあればマージする
			if (isset($definition['tax'])) {
				$tax_args = wp_parse_args($definition['tax'], $tax_args);
			}

			// 登録
			register_taxonomy($definition['default']['slug'] . '_category', $definition['default']['slug'], $tax_args);

			// rewrite ruleを登録
			add_rewrite_rule($definition['default']['slug'] . '/category/([^/]+)/?$', 'index.php?' . $definition['default']['slug'] . '_category=$matches[1]', 'top');

			// カスタムタクソノミー > ターム一覧 の変更を適用
			add_filter('manage_edit-' . $definition['default']['slug'] . '_category_columns', 'cwp_edit_taxonomy_columns');
		}

		$order++;
	}
}
add_action('init', 'cwp_generate_cpt');



/**
 * 画像アップロード時に、自動で設定値にリサイズする
 *
 * @param  array $uploaded アップロードされたファイル
 * @return array
 */
function cwp_resize_image_on_upload($uploaded) {
	// リサイズの使用フラグを確認
	if (!get_field('use_resize_image_on_upload', CWP_SITE_CONFIG)) {
		return $uploaded;
	}

	// WP_Image_Editor
	$wp_image_editor = wp_get_image_editor($uploaded['file']);

	if (!is_wp_error($wp_image_editor)) {
		$sizes = $wp_image_editor->get_size();
		$max_width = get_field('upload_max_width', CWP_SITE_CONFIG);
		$max_height = get_field('upload_max_height', CWP_SITE_CONFIG);

		// 設定最大値より大きい画像はリサイズして上書きする
		if ($sizes['width'] > $max_width || $sizes['height'] > $max_height) {
			$wp_image_editor->resize($max_width, $max_height);
			$wp_image_editor->save($uploaded['file']);
		}
	}

	return $uploaded;
}
add_action('wp_handle_upload', 'cwp_resize_image_on_upload');



/**
 * wp_titleでタクソノミーのラベルを出力しないようにする
 */
function cwp_wp_title($title, $sep, $seplocation){
	global $wp_query;

	if (is_tax()) {
		$queried_obj = $wp_query->get_queried_object();
		$term_name = $queried_obj->name;
		$title = $term_name;
		$t_sep = '%WP_TITILE_SEP%';

		$prefix = '';
		if (!empty($title)) {
			$prefix = " $sep ";
		}
		if ('right' == $seplocation) {
			$title_array = explode($t_sep, $title);
			$title_array = array_reverse($title_array);
			$title = implode(" $sep ", $title_array) . $prefix;
		} 
		else {
			$title_array = explode($t_sep, $title);
			$title = $prefix . implode(" $sep ", $title_array);
		}
	}

	// 先頭の空白を除去
	return trim($title);
}
add_filter('wp_title', 'cwp_wp_title', 10, 3);



/**
 * wp_title()の日付アーカイブのタイトルを変更する
 */
function cwp_adjust_date_title($title, $sep, $seplocation) {
	$m        = get_query_var('m');
	$year     = get_query_var('year');
	$monthnum = get_query_var('monthnum');
	$day      = get_query_var('day');
	$date_title = '';

	// mパラメータがある場合 (パーマリンク設定がデフォルトの場合の日付アーカイブ)
	if (is_archive() && !empty($m)) {
		$my_year  = substr($m, 0, 4);
		$my_month = substr($m, 4, 2);
		$my_day   = substr($m, 6, 2);
		$date_title    = $my_year . '年' . ($my_month ? $my_month . '月' : '') . ($my_day ? $my_day . '日' : '');
	}
	// yearパラメータがある場合 (パーマリンク設定がデフォルト以外の日付アーカイブ)
	if (is_archive() && !empty($year)) {
		$date_title = $year . '年';
		if (!empty($monthnum)) {
			$date_title .= zeroise($monthnum, 2) . '月';
		}
		if (!empty($day)) {
			$date_title .= zeroise($day, 2) . '日';
		}
	}
	// 日付調整を行ったタイトルがあれば区切り文字を追加(左か右)
	if ('' != $date_title) {
		if ('right' == $seplocation) {
			$title = $date_title . " $sep ";
		} else {
			$title = " $sep " . $date_title;
		}
	}
	
	return $title;
}
add_filter('wp_title', 'cwp_adjust_date_title', 11, 3);



/**
 * the_title をデフォルトでエスケープする
 * 
 * @param string $title
 * @return string $ret
 * 
 */
function cwp_the_title($title) {
	return esc_html($title);
}
add_filter('the_title', 'cwp_the_title');



/**
 * 特定ページにベーシック認証をかける
 *
 * @return void
 */
// function cwp_basic_auth() {
// 	$use_auth = get_field('use_auth', CWP_SITE_CONFIG);
// 	$auth_user = get_field('auth_user', CWP_SITE_CONFIG);
// 	$auth_pw = get_field('auth_pw', CWP_SITE_CONFIG);
// 	$auth_directory = get_field('auth_directory', CWP_SITE_CONFIG);

// 	// URLと設定ディレクトリを比較する
// 	$is_match = ($auth_directory === substr($_SERVER['REQUEST_URI'], 0, strlen($auth_directory)));
// 	// URLが管理画面がどうか
// 	$is_cwp = ('/cwp/' === substr($_SERVER['REQUEST_URI'], 0, strlen('/cwp/')));
	
// 	if ($use_auth && $is_match && !$is_cwp) {
// 		switch (true) {
// 			case !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']):
// 			case $_SERVER['PHP_AUTH_USER'] != $auth_user:
// 			case $_SERVER['PHP_AUTH_PW']   != $auth_pw:
// 				header('WWW-Authenticate: Basic realm="Enter username and password."');
// 				header('Content-Type: text/plain; charset=utf-8');
// 				exit('このページを見るにはログインが必要です');
// 		}
// 	}
// }
// add_action('wp_loaded', 'cwp_basic_auth');



/**
 * authorページでリダイレクトする
 * 
 * /?author=1 で管理ユーザ名が取得できてしまうため、/author/ページを使用できないようにする
 * 
 */
function cwp_redirec_on_author() {
	if (!empty($_GET['author'])) {
		wp_redirect(home_url());
		exit;
	}
}
add_action('init', 'cwp_redirec_on_author');



/**
 * 使用しないページでリダイレクトする
 */
function cwp_redirect_on_unused_page() {
	// 表画面かつ、メインクエリのみフィルタする
	if (!is_admin() && is_main_query()) {
		// template typeで使用しないページ
		if (cwp_get_template_type() === false) {
			wp_redirect(home_url());
			exit;
		}

		// /dummy/ カテゴリは削除できないのでリダイレクトする
		if (is_category('dummy')) {
			wp_redirect(home_url());
			exit;
		}
	}
}
add_action('template_redirect', 'cwp_redirect_on_unused_page');



/**
 * 投稿取得時のフィルター
 * 
 * @param object $wp_query WP_Query
 * 
 */
function cwp_pre_get_posts($wp_query){
	// 表画面かつ、メインクエリのみフィルタする
	if (!is_admin() && $wp_query->is_main_query()) {
		global $cwp_cpt_definitions;
		
		// カスタム投稿タイプ
		if ($wp_query->is_post_type_archive()) {
			$cpt_slug = get_query_var('post_type');

			foreach ($cwp_cpt_definitions as $definition) {
				// 表示件数を指定する
				if ($cpt_slug === $definition['default']['slug'] && isset($definition['default']['posts_per_page'])) {
					$wp_query->set('posts_per_page', $definition['default']['posts_per_page']);
				}
			}
		}

		// タクソノミー
		if ($wp_query->is_tax()) {
			// タクソノミーのスラッグを取得
			$tax_queries = $wp_query->tax_query->queries;
			$tax_slug = $tax_queries[0]['taxonomy'];

			// タームのスラッグを取得
			$arr_query = $wp_query->query;
			$term_slug = $arr_query[$tax_slug];

			foreach ($cwp_cpt_definitions as $definition) {
				// 表示件数を指定する
				if ($tax_slug === $definition['default']['slug'] . '_category' && isset($definition['default']['posts_per_page'])) {
					$wp_query->set('posts_per_page', $definition['default']['posts_per_page']);
				}				
			}

			// 子タームの投稿は取得しない
			$wp_query->set('tax_query', array(
				array(
					'taxonomy' => $tax_slug,
					'terms' => array($term_slug),
					'include_children' => false,
					'field' => 'slug',
				),
			));
		}
	}
}
add_action('pre_get_posts', 'cwp_pre_get_posts');



/**
 * スマホサイトでは、スマホ用本文を優先して表示する。
 * 
 * @param string $the_content post本文
 * 
 */
function cwp_the_content($the_content) {
	global $post;

	if (is_multi_device('smart')) {
		$sphone_content = get_field('sphone_content', $post->ID);
		if ($sphone_content) {
			return $sphone_content;
		}
	}
	return $the_content;
}
add_filter('the_content', 'cwp_the_content');



/**
 * excerpt_more の 文字列を変更する
 *
 * @param string $more
 */
function cwp_excerpt_more($more) {
	return '...<div class="text-right"><a class="read-more" href="'. get_permalink(get_the_ID()) . '"><i class="fa fa-angle-double-right"></i>続きを読む</a></div>';
}
add_filter('excerpt_more', 'cwp_excerpt_more');



/**
 * body_classの追加処理
 * 
 * @param array $classes
 */
function cwp_body_class($classes) {
	if (!is_home()) {
		$template_type = cwp_get_template_type();
		if ($template_type) {
			$classes[] = 'template-type-' . $template_type;
		}
	}

	// bootstrapとconflictするのでtagを削除
	if (is_tag()) {
		$classes = array_diff($classes, ['tag']);
	}

	if (is_multi_device('smart')) {
		$classes[] = 'sphone';
	}
	else {
		$classes[] = 'default';
	}

	// EC CUBE
	if (cwp_is_shop()) {
		$classes[] = 'shop';
	}

	return $classes;
}
add_filter('body_class', 'cwp_body_class');



/**
 * contact form7: fromのメールアドレスの確認用入力欄チェック
 */
function cwp_wpcf7_text_validation_filter_extend($result, $tag) {
	$type = $tag['type'];
	$name = $tag['name'];
	$_POST[$name] = trim(strtr((string) $_POST[$name], "\n", " "));
	if ('email' == $type || 'email*' == $type) {
		if (preg_match('/(.*)_confirm$/', $name, $matches)){
			$target_name = $matches[1];
			if ($_POST[$name] != $_POST[$target_name]) {
				if (method_exists($result, 'invalidate')) {
					$result->invalidate($tag,"確認用のメールアドレスが一致していません");
				}
				else {
					$result['valid'] = false;
					$result['reason'][$name] = '確認用のメールアドレスが一致していません';
				}
			}
		}
	}
	return $result;
}
add_filter('wpcf7_validate_email', 'cwp_wpcf7_text_validation_filter_extend', 11, 2);
add_filter('wpcf7_validate_email*', 'cwp_wpcf7_text_validation_filter_extend', 11, 2);



/**
 * 絵文字を使用できないようにする
 */
function cwp_disable_emoji() {
	if (!CWP_USE_EMOJI) {
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_action('admin_print_styles', 'print_emoji_styles');    
		remove_filter('the_content_feed', 'wp_staticize_emoji');
		remove_filter('comment_text_rss', 'wp_staticize_emoji');    
		remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	}
}
add_action('init', 'cwp_disable_emoji');



/**
 * wp_head内の不要な項目を非表示
 */
function cwp_clean_wp_head() {
	remove_action('wp_head', 'wp_generator');                    // バージョン情報
	remove_action('wp_head', 'rsd_link');                        // 外部投稿ツール用リンク
	remove_action('wp_head', 'wlwmanifest_link');                // Windows Live Writer用URL
	remove_action('wp_head', 'wp_shortlink_wp_head');            // パーマリンク
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head'); // 前後記事のURL
	remove_action('wp_head', 'feed_links_extra', 3);             // RSSフィード

	// oEmbed関連
	remove_action('wp_head','rest_output_link_wp_head');
	remove_action('wp_head','wp_oembed_add_discovery_links');
	remove_action('wp_head','wp_oembed_add_host_js');
}
add_action('init', 'cwp_clean_wp_head');



/**
 * wp_head内の追加タグ
 */
function cwp_add_wp_head(){
	// contents テンプレートの場合は 重複コンテンツ回避のためnoindex指定を行う
	if (is_single() && cwp_get_template_type() === 'contents') {
		echo '<meta name="robots" content="noindex,nofollow" />' . "\n";
	}

	// Google Analytics Tag
	if (get_option('cwp_ga_tag')) {
		echo get_option('cwp_ga_tag') . "\n";
	}
	
	// Google Map API key
	if (get_option('cwp_gmap_browser_api_key')) {
		$gmap_browser_api_key = esc_attr(get_option('cwp_gmap_browser_api_key'));
		echo <<<EOF
<script type="text/javascript" charset="utf-8" src="//maps.google.com/maps/api/js?key={$gmap_browser_api_key}"></script>\n
EOF;
	}
}
add_action('wp_head', 'cwp_add_wp_head');



/**
 * wp_footer内の追加タグ
 */
function cwp_add_wp_footer(){
	// 追加footerタグ
	if (get_option('cwp_add_footer_tag')) {
		echo get_option('cwp_add_footer_tag');
	}

	// カスタムフィールド
	cwp_print_vars();

	// sns sdk
	cwp_sns_sdk();
}
add_action('wp_footer', 'cwp_add_wp_footer');

/**
 * contact form7: Return-PathをFromにあわせる
 */
function cwp_phpmailer_set_retun_path($phpmailer){
    $phpmailer->Sender = $phpmailer->From;
}
add_action('phpmailer_init','cwp_phpmailer_set_retun_path');

/**
 * smptでメール送信
 */
function cwp_send_smtp_email($phpmailer){
    if(DESIGN_SMTP_USED===true){
        $phpmailer->isSMTP();
        $phpmailer->Host = DESIGN_SMTP_HOST;
        $phpmailer->SMTPAuth = DESIGN_SMTP_AUTH;
        $phpmailer->Port = DESIGN_SMTP_PORT;
        $phpmailer->Username = DESIGN_SMTP_USER;
        $phpmailer->Password = DESIGN_SMTP_PASS;
    }
}
add_action('phpmailer_init','cwp_send_smtp_email');


function filter_search_by_status($query) {
    if ($query->is_search && !is_admin() && $query->is_main_query()) {
        $status = get_query_var('status');
        
        if ($status) {
            $meta_query = array(
                array(
                    'key' => 'status',
                    'value' => $status,
                    'compare' => 'IN' // ステータスが配列に含まれるかどうかをチェック
                )
            );
            $query->set('meta_query', $meta_query);
        }

        $query->set('post_type', 'puppies');
    }
}
add_action('pre_get_posts', 'filter_search_by_status');
