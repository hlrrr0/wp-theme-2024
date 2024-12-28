<div id="sidebar">
	<div id="sidebar-inner">
		<?php if (cwp_get_template_type() === 'blog') require_once TEMPLATEPATH . '/inc/parts/blognav.php'; ?>

		<div class="side-search">
			<form method="get" action="<?php bloginfo('url'); ?>">
				<div class="input-group">
					<input class="form-control" name="s" id="s" type="text" />
					<span class="input-group-append"><input class="btn btn-secondary" id="submit" type="submit" value="検索" /></span>
				</div>
			</form>
		</div>

		<nav class="side-nav">
			<h2><img src="/images/default/side-nav-contents-title.png" alt="CONTENTS" /></h2>
			<div class="side-nav-inner">
				<ul>
					<?php
					$args = array(
						'title_li' => '',
						'show_option_none' => '',
						'taxonomy'         => 'contents_category',
						// 'depth'            => 1, // 表示する階層
						// 'child_of'         => カテゴリID, // 指定したIDの子カテゴリのみ表示
						// 'exclude'          => array(カテゴリID), // 指定したIDのカテゴリを除外
					);
					wp_list_categories($args);
					?>
				</ul>
			</div>
		</nav>

		<nav class="side-nav">
			<h2><img src="/images/default/side-nav-gallery-title.png" alt="GALLERY" /></h2>
			<div class="side-nav-inner">
				<ul>
					<?php
					$args = array(
						'title_li'         => '',
						'show_option_none' => '',
						'taxonomy'         => 'gallery_category',
						// 'depth'            => 1, // 表示する階層
						// 'child_of'         => カテゴリID, // 指定したIDの子カテゴリのみ表示
						// 'exclude'          => array(カテゴリID), // 指定したIDのカテゴリを除外
					);
					wp_list_categories($args);
					?>
				</ul>
			</div>
		</nav>

		<?php
		// shopディレクトリが存在する場合に表示
		if (cwp_exists_shop()) {
			require_once($_SERVER['DOCUMENT_ROOT'] . '/shop/frontparts/bloc/calendar.php');
			require_once($_SERVER['DOCUMENT_ROOT'] . '/shop/frontparts/bloc/cart.php');
			require_once($_SERVER['DOCUMENT_ROOT'] . '/shop/frontparts/bloc/category.php');
			require_once($_SERVER['DOCUMENT_ROOT'] . '/shop/frontparts/bloc/login.php');
			require_once($_SERVER['DOCUMENT_ROOT'] . '/shop/frontparts/bloc/search_products.php');
		}

		// shopディレクトリ内の場合に表示
		if (cwp_is_shop()) {
		}
		?>

		<div class="side-about">
			<div class="side-about-inner">
				<?php the_field('about', CWP_SIDE); ?>
			</div>
		</div>

		<div class="side-banner">
			<div class="side-banner-inner">
				<?php the_field('banner', CWP_SIDE); ?>
			</div>
		</div>
	</div>
</div>
