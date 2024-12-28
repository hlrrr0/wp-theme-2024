<?php
/*
 * template-tags.php
 *
 * templateで使用する関数
 *
 */


/**
 * ページネーションを表示する
 *
 * @param int $pages 1ページに表示するエントリの件数
 * @param int $range 表示するページ番号の数
 */
function cwp_pagination($pages = '', $range = 4) {
	$showitems = ($range * 2)+1;

	global $paged;
	if (empty($paged)) $paged = 1;

	if ($pages == '') {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if (!$pages) {
			$pages = 1;
		}
	}

	if (1 != $pages) {
		echo "<nav class=\"pagination-wrap\">";
		echo "<ul class=\"pagination\">";
		if ($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li class=\"page-item\"><a href='" . get_pagenum_link(1) . "' class=\"page-link\">&laquo; 最初</a></li>";
		if ($paged > 1 && $showitems < $pages) echo "<li class=\"page-item\"><a href='" . get_pagenum_link($paged - 1) . "' class=\"page-link\">&lsaquo; 前へ</a></li>";

		for ($i = 1; $i <= $pages; $i++) {
			if (1 != $pages &&(!($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems)) {
				echo ($paged == $i) ? "<li class=\"page-item active\"><a href='" . get_pagenum_link($i) . "' class=\"page-link\">" . $i . "</a></li>" : "<li class=\"page-item\"><a href='" . get_pagenum_link($i) . "' class=\"page-link\">" . $i . "</a></li>";
			}
		}

		if ($paged < $pages && $showitems < $pages) echo "<li class=\"page-item\"><a href=\"".get_pagenum_link($paged + 1)."\" class=\"page-link\">次へ &rsaquo;</a></li>";
		if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li class=\"page-item\"><a href='".get_pagenum_link($pages)."' class=\"page-link\">最後 &raquo;</a></li>";
		echo "</ul>\n";
		echo "</nav>\n";
	}
}



/**
 * facebook JavaScript SDKタグ
 */
function cwp_fb_sdk() {
	$cwp_fb_sdk = <<<EOF
		<div id="fb-root"></div>
		<script>
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.5&appId=592969907455373";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		</script>
EOF;
	echo $cwp_fb_sdk;
}



/**
 * javascriptの値としてカスタムフィールドを出力する
 */
function cwp_print_vars() {
	global $cwp_vars;
	$json = json_encode($cwp_vars);

	echo <<<EOF
<script type="text/javascript">
/* <![CDATA[ */
var cwp_vars = {$json};
/* ]]> */
</script>

EOF;
}



/**
 * SNSボタン SDKタグ
 */
function cwp_sns_sdk() {
	$sns_sdk_tag = <<<EOF
		<script type="text/javascript" charset="utf-8" src="//b.st-hatena.com/js/bookmark_button.js"></script>
		<!-- <script type="text/javascript">window.___gcfg = {lang: 'ja'}; (function() { var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true; po.src = 'https://apis.google.com/js/plusone.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s); })();</script> -->
		<script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>		
EOF;
	echo $sns_sdk_tag;
}



/**
 * snsボタンのテンプレート
 *
 * @param string $permalink URL
 * @param string $title タイトル
 */
function cwp_bookmarks($permalink = false, $title = false) {
	$permalink = esc_url($permalink ? $permalink : get_the_permalink());
	$title     = esc_attr($title ? $title : get_the_title());

	$bookmarks_template = <<<EOF
		<div class="bookmarks-btn hatena"><a href="http://b.hatena.ne.jp/entry/{$permalink}" class="hatena-bookmark-button" data-hatena-bookmark-title="{$title}" data-hatena-bookmark-layout="simple" title="このエントリーをはてなブックマークに追加"><img src="//b.st-hatena.com/images/entry-button/button-only.gif" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a></div><!-- .hatena -->		
		<!-- <div class="bookmarks-btn google"><div class="g-plusone" data-size="medium" data-annotation="none" data-href="{$permalink}"></div></div> --><!-- .google -->
		<div class="bookmarks-btn twitter"><a href="https://twitter.com/share" class="twitter-share-button" data-url="{$permalink}" data-lang="ja" data-count="none">ツイート</a></div><!-- .twitter -->
		<div class="bookmarks-btn facebook"><div class="fb-like" data-href="{$permalink}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div></div><!-- .facebook -->
EOF;
	echo $bookmarks_template;
}



/**
 * 投稿者、日時等、投稿時のメタ情報
 */
function cwp_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	$time_string = sprintf($time_string,
		esc_attr(get_the_date('c')),
		esc_html(get_the_date('Y.m.d G:i'))
	);
	printf(__('<span class="posted-on">Posted on %1$s</span> | <span class="byline"> by %2$s</span>', 'cusotm'),
		sprintf('%1$s',	$time_string),
		sprintf('<span class="author vcard">%1$s</span>',
			esc_html(get_the_author())
		)
	);
}



/**
 * 前の記事、次の記事へのリンクタグ
 */
function cwp_adjacent_post_links() {
	$previous_post = get_previous_post();
	$next_post = get_next_post();
	if (!empty($previous_post)) {
		$html .= sprintf('<a class="btn btn-secondary m-1" href="%1$s"><i class="fa fa-angle-double-left"></i> 前の記事へ</a>', get_permalink($previous_post->ID));
	}
	if (!empty($next_post)) {
		$html .= sprintf('<a class="btn btn-secondary m-1" href="%1$s">次の記事へ <i class="fa fa-angle-double-right"></i></a>', get_permalink($next_post->ID));
	}

	echo $html;
}


/**
 * ページごとのmeta情報を取得する
 *
 * @param string $key 取得するタイプ
 * @param boolean $echo echoするかどうか。falseを指定するとデータが返る
 * @return string $echoがfalseの場合はmeta情報の文字列を返す
 */
function cwp_the_meta($key, $echo = true) {
	global $cwp_meta;

	// 取得済みなら処理しない
	if (isset($cwp_meta)) {
		if ($echo) {
			echo esc_attr($cwp_meta[$key]);
			return;
		}
		else {
			return $cwp_meta[$key];
		}
	}

	$cwp_meta = array();

	// og:image
	$cwp_meta['og_image'] = cwp_get_og_image();

	// title suffix (pager)
	$paged = get_query_var('paged');
	if (!empty($paged)) {
		$title_suffix = ' - ' . $paged . 'ページ目';
	} else {
		$title_suffix = '';
	}

	// title suffix (サイトタイトル)
	if (is_home()) {
		$title_suffix .= get_field('title', CWP_SITE_CONFIG);
	}
	else {
		$title_suffix .= get_field('title', CWP_SITE_CONFIG) ? ' | ' . get_field('title', CWP_SITE_CONFIG) : '';
	}

	// デフォルト値
	$cwp_meta['title']            = wp_title('', false) . $title_suffix;
	$cwp_meta['site_description'] = get_field('site_description', CWP_SITE_CONFIG);
	$cwp_meta['meta_author']      = get_field('meta_author', CWP_SITE_CONFIG);
	$cwp_meta['meta_keywords']    = get_field('meta_keywords', CWP_SITE_CONFIG);
	$cwp_meta['meta_description'] = get_field('meta_description', CWP_SITE_CONFIG);

	// TOP
	if (is_home()) {
		$cwp_meta['title']            = get_field('title', CWP_TOP_CONFIG) ? get_field('title', CWP_TOP_CONFIG) : $cwp_meta['title'];
		$cwp_meta['site_description'] = get_field('site_description', CWP_TOP_CONFIG) ? get_field('site_description', CWP_TOP_CONFIG) : $cwp_meta['site_description'];
		$cwp_meta['meta_author']      = get_field('meta_author', CWP_TOP_CONFIG) ? get_field('meta_author', CWP_TOP_CONFIG) : $cwp_meta['meta_author'];
		$cwp_meta['meta_keywords']    = get_field('meta_keywords', CWP_TOP_CONFIG) ? get_field('meta_keywords', CWP_TOP_CONFIG) : $cwp_meta['meta_keywords'];
		$cwp_meta['meta_description'] = get_field('meta_description', CWP_TOP_CONFIG) ? get_field('meta_description', CWP_TOP_CONFIG) : $cwp_meta['meta_description'];
	}
	// 個別投稿ページ or タクソノミー
	elseif (is_single() || is_tax()) {
		$queried_object = get_queried_object();
		$cwp_meta['title']            = get_field('title', $queried_object) ? get_field('title', $queried_object) . $title_suffix : $cwp_meta['title'];
		$cwp_meta['site_description'] = get_field('site_description', $queried_object) ? get_field('site_description', $queried_object) : $cwp_meta['site_description'];
		$cwp_meta['meta_author']      = get_field('meta_author', $queried_object) ? get_field('meta_author', $queried_object) : $cwp_meta['meta_author'];
		$cwp_meta['meta_keywords']    = get_field('meta_keywords', $queried_object) ? get_field('meta_keywords', $queried_object) : $cwp_meta['meta_keywords'];
		$cwp_meta['meta_description'] = get_field('meta_description', $queried_object) ? get_field('meta_description', $queried_object) : $cwp_meta['meta_description'];
	}

	// ECCUBE関連コード
	if (cwp_is_shop()) {
		global $ecVar;

		if ($ecVar['arrMeta']['title']) {
			$cwp_meta['title'] = $ecVar['arrMeta']['title'];
		}
		elseif ($ecVar['tpl_subtitle']) {
			 $cwp_meta['title'] = $ecVar['tpl_subtitle'] . $cwp_meta['title'];
		}
		elseif ($ecVar['tpl_title']) {
			$cwp_meta['title'] = $ecVar['tpl_title'] . $cwp_meta['title'];
		}

		if ($ecVar['arrMeta']['keywords']) {
			$cwp_meta['meta_keywords'] = $ecVar['arrMeta']['keywords'];
		}
		if ($ecVar['arrMeta']['description']) {
			$cwp_meta['meta_description'] = $ecVar['arrMeta']['description'];
		}
	}

	// return
	if ($echo) {
		echo esc_attr($cwp_meta[$key]);
	}
	else {
		return $cwp_meta[$key];
	}

	return '';
}



/**
 * ページごとのog:imageを取得する
 *
 * @return string
 */
function cwp_get_og_image() {
	// 投稿にサムネイルがあればreturn
	if (is_single()) {
		if (has_post_thumbnail()){
			$image_id = get_post_thumbnail_id();
			$image = wp_get_attachment_image_src($image_id, 'full');
			return $image[0];
		}

		$gallery_images = get_field('gallery');
		if ($gallery_images) {
			return $gallery_images[0]['url'];
		}
	}

	// デフォルトlogoがあればreturn
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/images/common/site-logo.png')) {
		$protocol =	isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https://' : 'http://';
		return $protocol . $_SERVER['HTTP_HOST'] . '/images/common/site-logo.png';
	}

	return '';
}



/**
 * タブやスペースなどの空白文字,ショートコードhtmlタグを削除して、指定した文字数に詰める
 *
 * @param string $str 読み込むテキスト
 * @param int $width 丸める文字サイズ。半角で換算した文字数を指定
 * @param string $trimmarker 最後に追加される文字列
 * @param boolean $echo echoするかどうか
 *
 * @return string $echoがfalseの場合は文字列を返す
 */
function cwp_trim_to($str, $width, $trimmarker = null, $echo = true) {
	$str = strip_shortcodes($str);
	$str = strip_tags($str);
	$str = html_entity_decode($str);
	$str = trim($str);
	$str = preg_replace('/\s{2,}/', '', $str);
	$str = mb_strimwidth($str, 0, $width, $trimmarker);

	if ($echo) {
		echo esc_html($str);
	}
	else {
		return $str;
	}
}



/**
 * 投稿タイプ名を表示する
 *
 * @param string $slug カスタム投稿タイプのslug
 * @param boolean $echo echoするかどうか
 * @return string $echoがfalseの場合は文字列を返す
 */
function cwp_cpt_name($slug = null, $echo = true) {
	$slug = $slug ? $slug : cwp_get_post_type();
	$post_type_obj = get_post_type_object($slug);
	$cpt_name = $post_type_obj->label;

	if ($echo) {
		echo esc_html($cpt_name);
	}
	else {
		return $cpt_name;
	}
}



/**
 * ページに応じた投稿タイプを取得する
 *
 * - FIRST_DIR と同様の使い方を想定。
 * - pre_get_posts以前では、get_query_var()の値が返らないので、
 *   posts_selection以降や、htmlテンプレート内で使用する。
 *
 * @return string
 */
function cwp_get_post_type() {
	// TOP
	if (is_home()) {
		return 'home';
	}
	// 年月日
	else if (is_date()) {
		return get_post_type();
	}
	// カスタム投稿タイプアーカイブ
	else if (is_post_type_archive()) {
		return get_query_var('post_type');
	}
	// タクソノミー
	else if (is_tax()) {
		$taxonomy = get_query_var('taxonomy');
		$post_type = get_taxonomy($taxonomy)->object_type[0];
		return $post_type;
	}
	// カテゴリ
	else if (is_category()) {
		return 'post';
	}
	// タグ
	else if (is_tag()) {
		return 'post';
	}
	// 個別投稿ページ
	else if (is_single()) {
		return get_post_type();
	}

	return '';
}



/**
 * テンプレートタイプを取得する
 *
 * @param string $slug カスタム投稿タイプのslug
 * @param string $context ページの種別
 *
 * @return string
 */
function cwp_get_template_type($slug = null, $context = null) {
	global $cwp_cpt_definitions;

	$slug = $slug ? $slug : cwp_get_post_type();

	foreach ($cwp_cpt_definitions as $definition) {
		if ($slug === $definition['default']['slug']) {
			$template_type = $definition['default']['template_type'];

			// 配列ならcontextに応じてreturnする
			if (is_array($template_type)) {
				if (!$context) {
					// 年月日
					if (is_date()) {
						$context = 'date';
					}
					// カスタム投稿タイプアーカイブ
					if (is_post_type_archive()) {
						$context = 'archive';
					}
					// タクソノミー
					else if (is_tax()) {
						$context = 'taxonomy';
					}
					// カテゴリ
					else if (is_category()) {
						$context = 'category';
					}
					// タグ
					else if (is_tag()) {
						$context = 'tag';
					}
					// 個別投稿ページ
					else if (is_single()) {
						$context = 'single';
					}
				}

				return $template_type[$context];
			}

			// 配列以外はそのままreturn
			return $template_type;
		}
	}

	return '';
}



/**
 * 年別/月別アーカイブを表示する
 *
 * @param null $post_type
 */
function cwp_get_yearly_monthly_archives($post_type = null) {
	global $wpdb;
	$post_type = $post_type ? $post_type : cwp_get_post_type();
	$year_prev = null;
	$output = '';

	$query = <<<EOF
        SELECT 
            DISTINCT MONTH(post_date) AS month,
            YEAR(post_date) AS year,
            COUNT(id) AS post_count 
        FROM 
            $wpdb->posts
        WHERE 
            post_status = 'publish' AND 
            post_date <= NOW() AND
            post_type = %s
        GROUP BY 
            month, year
        ORDER BY
            post_date DESC
EOF;
	$months = $wpdb->get_results($wpdb->prepare($query, $post_type));
	$month_length = count($months);

	if ($month_length > 0){
		$i = 0;
		foreach($months as $month) {
			$i++;
			$year_current = $month->year;

			// 年表示
			if ($year_current != $year_prev) {
				// 2回目以降
				if ($year_prev != null) {
					$output .= '</ul>';
					$output .= '</li>';
				}

				$output .= '<li>';
				$output .= '<a href="' . home_url() . '/' . $month->year . '/?post_type=' . $post_type . '">' . $month->year . '年</a>';
				$output .= '<ul>';
			}

			// 月表示
			$output .= '<li><a href="' . home_url() . '/' . $month->year . '/' . sprintf('%02d', $month->month) . '/?post_type=' . $post_type . '">';
			$output .= $month->year . '年' . sprintf('%02d', $month->month) . '月 (' . $month->post_count . ')';
			$output .= '</a></li>';

			$year_prev = $year_current;

			// 最後なら閉じる
			if ($i == $month_length) {
				$output .= '</ul>';
				$output .= '</li>';
			}
		}
	}

	echo $output;
}



/**
 * ECCUBE関連コード shopディレクトリが存在するかどうか
 *
 * @return bool
 */
function cwp_exists_shop() {
	return is_dir($_SERVER['DOCUMENT_ROOT'] . '/shop');
}



/**
 * ECCUBE関連コード 現在shopディレクトリ内かどうか
 *
 * @return bool
 */
function cwp_is_shop() {
	return substr($_SERVER['SCRIPT_NAME'], 0, 6) == '/shop/';
}
