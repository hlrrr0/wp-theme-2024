<?php
/*
 * admin.php
 *
 * 管理画面カスタマイズ関連の関数
 * 
 */

/**
 * ログイン画面 > ロゴを変更する
 */
function cwp_login_logo_image() {
	$logo_url = '/images/common/cwp-logo.png';
	echo <<<EOF
<style type="text/css">
#login h1 a{
	width: 285px !important;
	height: 44px !important;
	-webkit-background-size: auto !important;
	background-size: auto !important;
	background-image: url({$logo_url}) !important;
}
</style>
EOF;
}
add_action('login_head', 'cwp_login_logo_image');



/**
 * ログイン画面 > ロゴのurlを変更
 */
function cwp_login_logo_url() {
	return get_bloginfo('url');
}
add_filter('login_headerurl', 'cwp_login_logo_url');



/**
 * ログイン画面 > ロゴのtitleを変更
 */
function cwp_login_logo_title(){
	return get_bloginfo('name');
}
add_filter('login_headertitle', 'cwp_login_logo_title');


/**
 * 管理画面にfaviconを設定する
 */
function cwp_admin_favicon() {
	echo '<link rel="shortcut icon" type="image/x-icon" href="/images/common/admin-favicon.ico" />';
}
add_filter('admin_head', 'cwp_admin_favicon');



/**
 * 管理バーをカスタマイズする 
 */
function cwp_adminbar_menu($wp_admin_bar) {
	// 不要な項目を非表示にする
	$wp_admin_bar->remove_node('wp-logo');  // ロゴ
	$wp_admin_bar->remove_node('comments'); // コメント

	// 新規 > 投稿
	if (!CWP_USE_POST_TYPE_POST) {
		$wp_admin_bar->remove_node('new-post');
	}

	// 新規 > 固定ページ
	if (!CWP_USE_POST_TYPE_PAGE) {
		$wp_admin_bar->remove_node('new-page');
	}

	// EC CUBEリンク
	if (cwp_exists_shop()) {
		$wp_admin_bar->add_menu(array(
			'id' => 'eccube',
			'title' => 'DESIGN SHOP',
			'parent' => 'site-name',
			'href' => '/shop/admin/'
		));
	}

	// アーカイブを使用しない場合、リンクを削除
	global $typenow;
	if (!empty($typenow) && cwp_get_template_type($typenow, 'archive') === false) {
		$wp_admin_bar->remove_node('archive');
	}

	// 個別投稿ページを使用しない場合、リンクを削除
	global $current_screen;
	if (!empty($current_screen) && cwp_get_template_type($current_screen->post_type, 'single') === false) {
		$wp_admin_bar->remove_node('view');
	}
}
add_action('admin_bar_menu', 'cwp_adminbar_menu', 100);



/**
 * サイドメニューをカスタマイズ
 */
function cwp_admin_menu() {
	// メニュー, サブメニュー
	global $menu, $submenu;

	// 投稿
	if (!CWP_USE_POST_TYPE_POST) {
		unset($menu[5]);
	}

	// 固定ページ
	if (!CWP_USE_POST_TYPE_PAGE) {
		unset($menu[20]);
	}

	// コメント
	unset($menu[25]);

	// メディアを移動
	$menu[21] = $menu[10];
	unset($menu[10]);

	// Flamingo
	$menu[26][0] = '問い合わせ履歴';
	$menu[28] = $menu[26];
	unset($menu[26]);

	// クライアント権限
	if (!current_user_can('administrator')) {
		unset($menu[27]); // お問い合わせ
		unset($menu[75]); // ツール
	}
}
add_action('admin_menu', 'cwp_admin_menu');



/**
 * フッターの文言を非表示にする
 */
add_filter('admin_footer_text', '__return_false');



/**
 * ダッシュボード > ウィジェットを非表示にする
 */
function cwp_remove_dashboard_widgets() {
	remove_action('welcome_panel', 'wp_welcome_panel'); // WordPress へようこそ !

	global $wp_meta_boxes;
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // クイックドラフト 下書き
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // 最近の下書き

	// クライアント権限で非表示
	if (!current_user_can('administrator')) {
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); // 概要
		//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']); // アクティビティ
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // 最近のコメント
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // 被リンク
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // プラグイン
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // WordPress ニュース
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // WordPressフォーラム
	}
}
add_action('wp_dashboard_setup', 'cwp_remove_dashboard_widgets');



/**
 * ダッシュボード > 概要 にカスタム投稿の情報を表示する
 */
function cwp_dashboard_glance_items($elements) {
	// すべてのカスタム投稿タイプを取得
	$args = array(
		'public' => true,
		'_builtin' => false
	);
	$post_types = get_post_types($args, 'object', 'and');

	foreach ($post_types as $post_type) {
		$num_posts = wp_count_posts($post_type->name);
		$num = number_format_i18n($num_posts->publish);
		$text = _n($post_type->labels->singular_name, $post_type->labels->name, intval($num_posts->publish));
		if (current_user_can('edit_posts')) {
			$output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . '&nbsp;' . $text . '</a>';
		}
		echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
	}
}
add_filter('dashboard_glance_items', 'cwp_dashboard_glance_items');



/**
 * ダッシュボード > アクティビティ にカスタム投稿の情報を表示する
 */
function cwp_dashboard_recent_posts_query_args($query_args) {
	// すべてのカスタム投稿タイプを取得
	$args = array(
		'public' => true,
		'_builtin' => false
	);
	$post_types = get_post_types($args, 'object', 'and');

	foreach ($post_types as $post_type) {
		$custom_post_type_slugs[] = $post_type->name;		
	}

	$query_args['post_type'] = $custom_post_type_slugs;

	if ($query_args['post_status'] == "publish") {
		$query_args['posts_per_page'] = 10;
	}

	return $query_args;
}
add_filter('dashboard_recent_posts_query_args', 'cwp_dashboard_recent_posts_query_args', 10, 1);



/**
 * 管理画面全体 に独自styleを適用
 */
function cwp_admin_head_style() {
	echo <<<EOF
		<style type="text/css">
		/* 右上ヘルプを非表示にする */
		#contextual-help-link-wrap{
			display: none !important;
		}
		/* デフォルトのカテゴリ説明文を非表示にする */
		.term-description-wrap{
			display: none !important;
		}
		</style>
EOF;
}
add_action('admin_head', 'cwp_admin_head_style');



/**
 * 管理画面全体 に独自scriptを適用
 */
function cwp_admin_head_script() {
	echo <<<EOF
		<script>
		(function($) {
			$(function(){
				// 管理バー > メニュー > サイトを表示を新規ウインドウで開く
				$('#wp-admin-bar-site-name a').attr('target', '_blank');

				// 管理バー > アーカイブの表示を新規ウインドウで開く
				$('#wp-admin-bar-archive a').attr('target', '_blank');

				// 管理バー > 記事の表示を新規ウインドウで開く
				$('#wp-admin-bar-view a').attr('target', '_blank');

				// アクション行 > 表示をを新規ウインドウで開く
				$('.row-actions .view a').attr('target', '_blank');

				// 編集画面 > パーマリンクを新規ウインドウで開く
				$('#sample-permalink a').attr('target', '_blank');

				// 画像のトリミング時に横と縦のプレースホルダを設定する
				$('input[id^="imgedit-open-btn-"]').on('click', function(event) {
					var count = 0;
					var intervalID = setInterval(function() {
						// inputが見つかればplaceholdを設定する
						if ($('input[id^="imgedit-crop-width-"]').length && $('input[id^="imgedit-crop-height-"]').length) {
							$('input[id^="imgedit-crop-width-"]').attr('placeholder', '横');
							$('input[id^="imgedit-crop-height-"]').attr('placeholder', '縦');
							clearInterval(intervalID);
						}

						// ローディングを加味して、10回までリトライする
						if (count < 10) {
							count++;
						} else {
							clearInterval(intervalID);
						}
					}, 1000);
				});

			});
		})(jQuery);
		</script>
EOF;
}
add_action('admin_head', 'cwp_admin_head_script');



/**
 * ダッシュボード に独自styleを適用
 */
function cwp_admin_dashboard_style() {
	// 投稿
	if (!CWP_USE_POST_TYPE_POST) {
		echo <<<EOF
			<style type="text/css">
			/* 概要の投稿を非表示 */
			#dashboard_right_now .main ul li.post-count:first-child{
				display: none;
			}
			</style>
EOF;
	}

	// 固定ページ
	if (!CWP_USE_POST_TYPE_PAGE) {
		echo <<<EOF
			<style type="text/css">
			/* 概要の固定ページを非表示 */
			#dashboard_right_now .main ul li.page-count{
				display: none;
			}
			</style>
EOF;
	}

}
add_action('admin_print_styles-index.php', 'cwp_admin_dashboard_style');



/**
 * クライアント権限でアップデート通知を非表示にする
 */
function cwp_hide_update_notice_not_admin() {
	if (!current_user_can('update_core')) {
		remove_action('admin_notices', 'update_nag', 3);
	}
}
add_action('admin_notices', 'cwp_hide_update_notice_not_admin', 1);



/**
 * カスタムタクソノミー > ターム一覧 のカラムの表示を変更
 *
 * - filter設定は cwp_generate_cpt() 内で行う
 *
 */
function cwp_edit_taxonomy_columns($columns) {
	// 説明文を削除
	unset($columns['description']);

	return $columns;
}



/**
 * カスタム投稿タイプ > 記事一覧 にカテゴリの絞り込み検索を追加する
 */
function cwp_restrict_manage_posts() {
	global $typenow;

	// すべてのカスタム投稿タイプを取得
	$args = array(
		'public' => true,
		'_builtin' => false
	);
	$post_types = get_post_types($args);

	if (in_array($typenow, $post_types)) {
		$filters = get_object_taxonomies($typenow);
		foreach ($filters as $tax_slug) {
			$tax_obj  = get_taxonomy($tax_slug);
			$selected = !empty($_GET[$tax_obj->query_var]) ? $_GET[$tax_obj->query_var] : '';

			wp_dropdown_categories(array(
				'show_option_all' => __($tax_obj->label),
				'taxonomy'        => $tax_slug,
				'name'            => $tax_obj->name,
				'orderby'         => 'term_order',
				'selected'        => $selected,
				'hierarchical'    => $tax_obj->hierarchical,
				'show_count'      => false,
				'hide_empty'      => true,
				'value_field'     => 'slug'
			));
		}
	}
}
add_action('restrict_manage_posts', 'cwp_restrict_manage_posts');



/**
 * カスタム投稿タイプ > 記事一覧 にカラムを追加する
 */
function cwp_add_posttype_column($columns) {
	global $cwp_cpt_definitions;
	global $post_type; // 現在の投稿タイプslug

	// author は値を指定するだけで使用可能
 	$columns['author'] = '作成者';

	// 独自定義のカスタム投稿タイプ && タクソノミーを使用する場合、カテゴリーのカラムを追加する
	foreach ($cwp_cpt_definitions as $key => $definition) {
		if ($definition['default']['slug'] === $post_type && $cwp_cpt_definitions[$key]['default']['template_type']['taxonomy']) {
			$columns['add_category'] = 'カテゴリー';		
		}
	}

	return $columns;
}
add_filter('manage_posts_columns', 'cwp_add_posttype_column');



/**
 * カスタム投稿タイプ > 記事一覧 > 所属カテゴリのカラム内にフィルタリンクを挿入
 */
function cwp_add_posttype_column_id($column_name, $id) {
	if ($column_name === 'add_category') {
		// すべてのカスタム投稿タイプを取得
		$args = array(
			'public' => true,
			'_builtin' => false
		);
		$post_types = get_post_types($args, 'object', 'and');

		// カスタム投稿タイプのスラッグ配列
		foreach ($post_types as $post_type) {
			$custom_post_type_slugs[] = $post_type->name;
		}

		// 現在のカスタム投稿タイプ
		$post_type = get_post_type($id);

		if (in_array($post_type, $custom_post_type_slugs)) {
			$post_terms = wp_get_object_terms($id, $post_type . '_category');
			
			for ($i = 0; $i < count($post_terms); $i++) { 
				// 記事フィルタ用リンク
				$html = '<a href="' . get_admin_url() . 'edit.php?post_type=' . $post_type . '&' .  $post_type . '_category' . '=' . esc_html($post_terms[$i]->slug) . '">' . esc_html($post_terms[$i]->name) . '</a>';
				// ターム表画面確認用リンク
				$html .= ' ' . '<a class="dashicons dashicons-external" style="font-size:17px;" target="_blank" href="' . get_term_link($post_terms[$i]) . '"></a>';
				if ($i != count($post_terms) - 1) $html .= ', ';
				echo $html;
			}
		}
	}
}
add_action('manage_posts_custom_column', 'cwp_add_posttype_column_id', 10, 2);



/**
 * 投稿画面 カテゴリの階層構造を保持する
 */
function cwp_wp_category_terms_checklist_no_top($args, $post_id = null) {
	$args['checked_ontop'] = false;
	return $args;
}
add_action('wp_terms_checklist_args', 'cwp_wp_category_terms_checklist_no_top');



/**
 * 投稿画面 カスタム投稿タイプの公開時に自動で投稿IDをスラッグにセットする
 */
function cwp_add_slug_for_posts($post_id) {
	global $wpdb;

	$posts_data = get_post($post_id, ARRAY_A);
	$slug = $posts_data['post_name'];
	$dec = urldecode($slug);

	if ($post_id != $slug && strlen($dec) != mb_strlen($dec)){
		$my_post = array();
		$my_post['ID'] = $post_id;
		$my_post['post_name'] = $post_id;
		wp_update_post($my_post);
	}
}
if (CWP_USE_POST_ID_SLUG) {	
	add_action('publish_page', 'cwp_add_slug_for_posts'); // 固定ページ
}



/**
 * サイトパーツ > 投稿画面 でパーマリンク欄を非表示にする
 */
function cwp_remove_slugbox() {
	global $current_screen;

	switch ($current_screen->post_type) {
		case 'siteparts':
			add_filter('get_sample_permalink_html' , '__return_false');
			add_filter('get_shortlink' , '__return_false');
			break;
		default:
			break;
	}
}
add_action('admin_head', 'cwp_remove_slugbox');



/**
 * tinymceをカスタマイズする
 */
function cwp_mce_options($settings){
	$settings['wpautop'] = false;

	$settings['block_formats'] = "段落=p; 段落 (div)=div; 見出し2=h2; 見出し3=h3; 見出し4=h4; 見出し5=h5; 見出し6=h6;"; // h1を入力できないように

	$style_formats = array(
		array(
			'title' => 'ブロック',
			'items' => array(
				array(
					'title' => '1文字目を大きく',
					'block' => 'p',
					'classes' => 'firstlarge',
				),
				array(
					'title' => '引用',
					'block' => 'blockquote',
					'classes' => 'blockquote',
				),
			)
		),
		array(
			'title' => 'インライン',
			'items' => array(
				array(
					'title' => '小さくする',
					'inline' => 'small',
				),
				array(
					'title' => '太字',
					'inline' => 'strong',
				),
				array(
					'title' => 'イタリック',
					'inline' => 'em',
				),
				array(
					'title' => '下線',
					'inline' => 'u',
				),
				array(
					'title' => '打ち消し線',
					'inline' => 'del',
				),
				array(
					'title' => 'ハイライト',
					'inline' => 'mark',
				),
			)
		),
		array(
			'title' => '書体',
			'items' => array(
				array(
					'title' => '明朝体',
					'inline' => 'span',
					'classes' => 'serif',
				),
				array(
					'title' => '記号用',
					'inline' => 'span',
					'classes' => 'alphanumeric',
				),
			)
		),
		array(
			'title' => 'マーカー',
			'items' => array(
				array(
					'title' => 'マーカー 青',
					'inline' => 'span',
					'classes' => 'marker-blue',
				),
				array(
					'title' => 'マーカー 緑',
					'inline' => 'span',
					'classes' => 'marker-green',
				),
				array(
					'title' => 'マーカー 黃',
					'inline' => 'span',
					'classes' => 'marker-yellow',
				),
				array(
					'title' => 'マーカー 赤',
					'inline' => 'span',
					'classes' => 'marker-red',
				),
				array(
					'title' => 'マーカー ピンク',
					'inline' => 'span',
					'classes' => 'marker-pink',
				),
			)
		),
	);
	$settings['style_formats_merge'] = false;// merge set to true, overwrite set to false
	$settings['style_formats'] = json_encode($style_formats);

	$settings['fontsize_formats'] = "10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 24px 28px 32px 36px 40px 48px 60px";

	return $settings;
}
add_filter('tiny_mce_before_init', 'cwp_mce_options', 10, 3);

// tiny mce用css
add_editor_style(home_url() . '/css/common/vendor.css');
add_editor_style(home_url() . '/css/common/editor-style.css');



/**
 * 設定 > 一般 > Google Map ブラウザ APIキー 入力欄のテンプレート
 */
function cwp_gmap_browser_api_key_template() {
	$value = esc_attr(get_option('cwp_gmap_browser_api_key'));
	echo <<<EOF
		<input type="text" name="cwp_gmap_browser_api_key" id="cwp_gmap_browser_api_key" class="regular-text code" value="{$value}" />
		<p>
		APIキーを入力すると、<head>内でGoogle Map Javascript APIが読み込まれます。
		</p>
EOF;
}



/**
 * 設定 > 一般 > Google Analyticsタグ 入力欄のテンプレート
 */
function cwp_ga_tag_template() {
	$value = esc_attr(get_option('cwp_ga_tag'));
	echo <<<EOF
		<textarea cols="50" rows="10" name="cwp_ga_tag" id="cwp_ga_tag">{$value}</textarea>
EOF;
}



/**
 * 設定 > 一般 > フッター追加タグの入力欄
 */
function cwp_add_footer_tag_template() {
	$add_footer_tag = esc_attr(get_option('cwp_add_footer_tag'));
	echo <<<EOF
		<textarea cols="50" rows="10" name="cwp_add_footer_tag" id="cwp_add_footer_tag">{$add_footer_tag}</textarea>
		<p>
		ここにタグを入力すると、すべてのページのfooterでタグが表示されます。<br>
		AI計測タグなどはここに入力します。
		</p>
EOF;
}



/**
 * 設定 > 一般 に項目追加
 */
function cwp_add_general_custom_sections() {
	add_settings_field('cwp_gmap_browser_api_key', 'Google Map ブラウザ APIキー', 'cwp_gmap_browser_api_key_template', 'general');
	add_settings_field('cwp_ga_tag', 'Google Analyticsタグ', 'cwp_ga_tag_template', 'general');
	add_settings_field('cwp_add_footer_tag', 'フッターへの追加タグ', 'cwp_add_footer_tag_template', 'general');

	register_setting('general', 'cwp_gmap_browser_api_key');
	register_setting('general', 'cwp_ga_tag');
	register_setting('general', 'cwp_add_footer_tag');
}
add_action('admin_init', 'cwp_add_general_custom_sections');



/**
 * クライアント権限でパスワードを変更できないようにする
 *
 * @param bool $show_password_fields
 */
function cwp_show_password_fields($show_password_fields) {
	// クライアント権限で非表示
	if (current_user_can('administrator')) {
		return $show_password_fields;
	}
}
add_action('show_password_fields', 'cwp_show_password_fields');



/**
 * パスワード変更時の通知が送られないようにする
 */
add_filter('send_password_change_email', '__return_false');



/**
 * Google Map API を ACF で利用可能にする
 */
function cwp_acf_google_map_api($api) {
	if (!empty(get_option('cwp_gmap_browser_api_key'))) {
		$api['key'] = esc_attr(get_option('cwp_gmap_browser_api_key'));
	}
	return $api;
}
add_filter('acf/fields/google_map/api', 'cwp_acf_google_map_api');
