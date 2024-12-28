<?php
/*
 * breadcrumbs.php
 *
 * パンくずを生成するための関数
 * 
 */

/**
 * パンくずリスト
 */
function cwp_breadcrumbs($args = array()) {
	global $post;
	$str = '';
	$defaults = array(
		'class' => 'breadcrumb-wrap',
		'home' => 'ホーム',
		'tag' => 'タグ',
		'author' => '投稿者',
		'notfound' => '404 Not found',
		'separator' => '',   //デフォルトは「&nbsp;>&nbsp;」
	);
	$args = wp_parse_args($args, $defaults);
	extract($args, EXTR_SKIP);

	// セパレータが設定されてない場合は表示なし（空白）
	if ($separator == '') {
		$separator_html = $separator;
	} 
	else {
		$separator_html = '<li>' . $separator . '</li>';
	}

	if (is_home()) {
		echo '<div class="' . $class. '"><ul class="breadcrumb"><li class="home breadcrumb-item"><span>' . $home . '</span></li></ul></div>';
	}

	// 管理ページ以外
	if (!is_home() && !is_admin()) {
		$str .= '<div class="' . $class . '">';
		$str .= '<ul class="breadcrumb">';
		$str .= '<li class="home breadcrumb-item"><a href="' . esc_url(home_url()) . '/"><span>' . $home . '</span></a></li>';
		$str .= $separator_html;
		$tax_slug = get_query_var('taxonomy');  // [taxonomy] の値（タクソノミーのスラッグ）
		$cpt_slug = get_query_var('post_type');   // [post_type] の値（投稿タイプ名）

		// タクソノミーのページ
		if ($tax_slug && is_tax($tax_slug)) {
			$my_tax = get_queried_object(); 
			$post_types = get_taxonomy($tax_slug)->object_type;
			$cpt_slug = $post_types[0];  // カスタム分類名からカスタム投稿名を取得。

			// template typeがfalse以外は、アーカイブ一覧へのリンクを出力
			if (cwp_get_template_type($cpt_slug, 'archive') !== false) {
				$str .= '<li class="breadcrumb-item"><a href="' .  esc_url(get_post_type_archive_link($cpt_slug)) . '"><span>' . esc_html(get_post_type_object($cpt_slug)->label) . '</span></a></li>';
				$str .= $separator_html;				
			}

			if ($my_tax->parent !== 0) {  // 親があればそれらを取得して表示
				$ancestors = array_reverse(get_ancestors($my_tax->term_id, $my_tax->taxonomy));
				foreach($ancestors as $ancestor) {
					$str .= '<li class="breadcrumb-item"><a href="' . esc_url(get_term_link($ancestor, $my_tax->taxonomy)) . '"><span>' . esc_html(get_term($ancestor, $my_tax->taxonomy)->name) . '</span></a></li>';
					$str .= $separator_html;
				}
			}
			$str .= '<li class="breadcrumb-item"><span>' . esc_html($my_tax->name) . '</span></li>';
		}
		// カテゴリーのアーカイブページ
		elseif (is_category()) {
			$cat = get_queried_object();
			if ($cat->parent !== 0) {
				$ancestors = array_reverse(get_ancestors($cat->cat_ID, 'category'));
				foreach($ancestors as $ancestor) {
					$str .= '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($ancestor)) . '"><span>' . esc_html(get_cat_name($ancestor)) . '</span></a></li>';
					$str .= $separator_html;
				}
			}
			$str .= '<li class="breadcrumb-item"><span>' . esc_html($cat->name) . '</span></li>';
		} 
		// カスタム投稿タイプのアーカイブページ
		elseif (is_post_type_archive()) {
			$cpt_slug = get_query_var('post_type');
			// 年月日別アーカイブだった場合
			if (is_date()) {
				$str .= '<li class="breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link($cpt_slug)) . '"><span>' . esc_html(get_post_type_object($cpt_slug)->label) . '</span></a></li>';
				// 日別アーカイブ
				if (get_query_var('day') != 0) { 
					$post_type_link = '/' . get_query_var('year') . '/' . sprintf("%02d", get_query_var('monthnum')) . '/?post_type=' . get_post_type_object($cpt_slug)->name;
					$str .= $separator_html;
					$str .= '<li class="breadcrumb-item"><a href="' . $post_type_link . '">' . get_query_var('year') . '年' . get_query_var('monthnum'). '月</span></a></li>';
					$str .= $separator_html;
					$str .= '<li class="breadcrumb-item"><span>' . get_query_var('day'). '日</span></li>';
				} 
				// 月別アーカイブ
				elseif (get_query_var('monthnum') != 0) { 
					$str .= $separator_html;
					$str .= '<li class="breadcrumb-item">' . get_query_var('year') . '年' . get_query_var('monthnum'). '月</span></li>';
				} 
				// 年別アーカイブ
				else { 
					$str .= '<li class="breadcrumb-item"><span>' . get_query_var('year') . '年</span></li>';
				}
			} 
			else {
				$str .= '<li class="breadcrumb-item"><span>' . esc_html(get_post_type_object($cpt_slug)->label) . '</span></li>';
			}
		} 
		// カスタム投稿タイプの個別投稿ページ
		elseif ($cpt_slug && is_singular($cpt_slug)) {
			// タクソノミーを取得
			$taxonomies = get_object_taxonomies($cpt_slug);
			$taxonomy = $taxonomies[0];
			$terms = get_the_terms($post->ID, $taxonomy);
			$term = $terms ? cwp_get_youngest_term($terms, $taxonomy) : null;

			// template typeがfalseなら、アーカイブへのリンクを出力しない
			if (cwp_get_template_type($cpt_slug, 'archive') !== false) {
				$str .= '<li class="breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link($cpt_slug)) . '"><span>' . esc_html(get_post_type_object($cpt_slug)->label) . '</span></a></li>';
				$str .= $separator_html;
			}

			// タクソノミーありの場合
			if (!empty($term)) {
				// 親タームがある場合
				if ($term->parent !== 0) {
					$ancestors = array_reverse(get_ancestors($term->term_id, $taxonomy));
					foreach($ancestors as $ancestor) {
						$str .= '<li class="breadcrumb-item"><a href="' . esc_url(get_term_link($ancestor, $taxonomy)) . '"><span>' . esc_html(get_term($ancestor, $taxonomy)->name) . '</span></a></li>';
						$str .= $separator_html;
					}
				}
				$str .= '<li class="breadcrumb-item"><a href="' . esc_url(get_term_link($term, $taxonomy)) . '"><span>' . esc_html($term->name) . '</span></a></li>';
				$str .= $separator_html;
			}
			// タクソノミーなしの場合
			else {
				// 親ページがある場合
				if ($post->post_parent !== 0) {
					$ancestors = array_reverse(get_ancestors($post->ID, $cpt_slug));
					foreach ($ancestors as $ancestor) {
						$str .= '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink($ancestor)) . '"><span>' . esc_html(get_the_title($ancestor)) . '</span></a></li>';
						$str .= $separator_html;
					}
				}
			}

			// 投稿タイトル
			$str .= '<li class="breadcrumb-item"><span>' . esc_html($post->post_title) . '</span></li>';
		} 
		// 個別投稿ページ
		elseif (is_single()) {
			$categories = get_the_category($post->ID);
			$cat = cwp_get_youngest_cat($categories);
			if ($cat->parent !== 0) {
				$ancestors = array_reverse(get_ancestors($cat->cat_ID, 'category'));
				foreach ($ancestors as $ancestor) {
					$str .= '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($ancestor)) . '"><span>' . esc_html(get_cat_name($ancestor)) . '</span></a></li>';
					$str .= $separator_html;
				}
			}
			$str .= '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($cat->term_id)) . '"><span>' . esc_html($cat->cat_name) . '</span></a></li>';
			$str .= $separator_html;
			$str .= '<li class="breadcrumb-item"><span>' . esc_html($post->post_title) . '</span></li>';
		} 
		// 固定ページ
		elseif (is_page()) {
			if ($post->post_parent !== 0) {
				$ancestors = array_reverse(get_post_ancestors($post->ID));
				foreach ($ancestors as $ancestor) {
					$str .= '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink($ancestor)) . '"><span>' . esc_html(get_the_title($ancestor)) . '</span></a></li>';
					$str .= $separator_html;
				}
			}
			$str .= '<li class="breadcrumb-item"><span>' . esc_html($post->post_title) . '</span></li>';
		} 
		// 日付ベースのアーカイブページ
		elseif (is_date()) {
			// 年別アーカイブ
			if (get_query_var('day') != 0) { 
				$str .= '<li class="breadcrumb-item"><a href="' . esc_url(get_year_link(get_query_var('year'))) . '"><span>' . get_query_var('year'). '年</span></a></li>';
				$str .= $separator_html;
				$str .= '<li class="breadcrumb-item"><a href="' . esc_url(get_month_link(get_query_var('year'), get_query_var('monthnum'))) . '"><span>' . get_query_var('monthnum') . '月</span></a></li>';
				$str .= $separator_html;
				$str .= '<li class="breadcrumb-item"><span>' . get_query_var('day'). '日</span></li>';
			} 
			// 月別アーカイブ
			elseif (get_query_var('monthnum') != 0) {
				$str .= '<li class="breadcrumb-item"><a href="' . esc_url(get_year_link(get_query_var('year'))) . '"><span>' . get_query_var('year') . '年</span></a></li>';
				$str .= $separator_html;
				$str .= '<li class="breadcrumb-item"><span>' . get_query_var('monthnum') . '月</span></li>';
			} 
			// 年別アーカイブ
			else {
				$str .= '<li class="breadcrumb-item"><span>' . get_query_var('year') . '年</span></li>';
			}
		} 
		// 検索結果表示ページ
		elseif (is_search()) {
			$str .= '<li class="breadcrumb-item"><span>"' . esc_html(get_search_query()) . '" の検索結果</span></li>';
		} 
		// 投稿者のアーカイブページ
		elseif (is_author()) {
			$str  .= '<li class="breadcrumb-item"><span>' . $author . ' : ' . get_the_author_meta('display_name', get_query_var('author')) . '</span></li>';
		} 
		// タグのアーカイブページ
		elseif (is_tag()) {
			$str .= '<li class="breadcrumb-item"><span>' . $tag . ' : ' . single_tag_title('' , false) . '</span></li>';
		} 
		// 添付ファイルページ
		elseif (is_attachment()) {
			$str .= '<li class="breadcrumb-item"><span>' . esc_html($post->post_title) . '</span></li>';
		} 
		// 404 Not Found ページ
		elseif (is_404()) {
			$str .= '<li class="breadcrumb-item"><span>' . esc_html($notfound) . '</span></li>';
		} 
		// ECCUBE関連
		elseif (cwp_is_shop()) {
			global $ecVar;

			if ($_SERVER['SCRIPT_NAME'] == '/shop/index.php' || $_SERVER['REQUEST_URI'] == '/shop/products/list.php') {
				$str .= '<li class="breadcrumb-item"><span>オンラインショップ</span></li>';
			}
			else {
				$str .= '<li class="breadcrumb-item"><a href="' . esc_url(ROOT_URLPATH) . '"><span>オンラインショップ</span></a></li>' . $separator_html;
				if (!empty($ecVar['arrBreadCrumb'])) {
					foreach ($ecVar['arrBreadCrumb'] as $value) {
						if (empty($value['link'])) $str .= '<li class="breadcrumb-item"><span>' . $value['text'] . '</span></li>';
						else {
							$str .= '<li class="breadcrumb-item"><a href="' . esc_url($value['link']) . '"><span>' . $value['text'] . '</span></a></li>' . $separator_html;
						}
					}
				}
				else {
					$str .= '<li class="breadcrumb-item"><span>' . $ecVar['tpl_title'] . '</span></li>';
				}
			}
		}
		// その他
		else {
			$str .= '<li class="breadcrumb-item"><span>' . wp_title('', true) . '</span></li>';
		}
		$str .= '</ul>';
		$str .= '</div>';
	}
	echo $str;
}



/**
 * 一番下の階層のカテゴリーを返す関数
 */
function cwp_get_youngest_cat($categories) {
	global $post;
	if (count($categories) === 1) {
		$youngest = $categories[0];
	}
	else {
		$count = 0;
		// それぞれのカテゴリーについて調査
		foreach($categories as $category) {  
			$children = get_term_children($category->term_id, 'category');  // 子カテゴリーの ID を取得

			// 子カテゴリーの数が多いほど、そのカテゴリーは階層が下なのでそれを元に調査するかを判定
			if ($children && $count < count($children)) {
				$count = count($children);
				foreach ($children as $child) {  // それぞれの「子カテゴリー」について調査 $childは子カテゴリーのID
					if (in_category($child, $post->ID)) {  // 現在の投稿が「子カテゴリー」のカテゴリーに属するか
						$youngest = get_category($child);  // 属していればその「子カテゴリー」が一番若い（一番下の階層）
					}
				}
			}
			// 子カテゴリーが存在しない場合
			else {
				$youngest = $category;  // そのカテゴリーが一番若い（一番下の階層）
			}
		}
	}
	return $youngest;
}



/**
 * 一番下の階層のタームを返す関数
 */
function cwp_get_youngest_term($terms, $taxonomy) {
	global $post;
	if (count($terms) === 1) {
		$youngest = $terms[key($terms)];
	}
	else {
		$count = 0;
		foreach($terms as $term) {
			$children = get_term_children($term->term_id, $taxonomy);  // 子タームの ID を取得

			// 子タームの数が多いほど、そのタームは階層が下なのでそれを元に調査するかを判定
			if ($children && $count < count($children)) {
				$count = count($children);
				foreach ($children as $child) {  // それぞれの「子ターム」について調査 $childは子タームのID
					if (is_object_in_term($post->ID, $taxonomy, $child)) {  // 現在の投稿が「子ターム」のタームに属するか
						$youngest = get_term($child, $taxonomy);  // 属していればその「子ターム」が一番若い（一番下の階層）
					}
				}
			}
			// 子タームが存在しない場合
			else {
				$youngest = $term;  // そのタームが一番若い（一番下の階層）
			}
		}
	}
	return $youngest;
}
