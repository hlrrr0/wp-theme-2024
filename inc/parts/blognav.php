<div class="blog-nav-area bg-sub">
	<div class="blog-nav">
			<?php cwp_get_cpt_calendar(cwp_get_post_type()); ?>
	</div>
	<nav class="common-nav blog-nav">
		<h2>最新の記事</h2>
		<ul>
			<?php
			$args = array(
				'numberposts' => 5,
				'post_type' => cwp_get_post_type()
			);
			$posts = get_posts($args);
			?>
			<?php if ($posts): ?>
				<?php foreach ($posts as $post) : setup_postdata($post); ?>
					<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
				<?php endforeach; wp_reset_postdata(); ?>
			<?php endif; ?>
		</ul>
	</nav>
	<nav class="common-nav blog-nav">
		<h2>ブログカテゴリ</h2>
		<ul>
			<?php
			$args = array(
				'title_li' => '',
				'taxonomy' => cwp_get_post_type() . '_category',
				'show_count' => true,
			);
			wp_list_categories($args);
			?>
		</ul>
	</nav>
	<nav class="common-nav blog-nav">
		<h2>月別アーカイブ</h2>
		<div class="scroll-area">
            <div class="cs-bar"><div class="cs-bar-inner"><div class="cs-drag"></div></div></div>
            <div class="cs-content">
                <ul>
                    <?php
                    $args = array(
                        'type' => 'monthly',
                        'post_type' => cwp_get_post_type(),
                        'show_post_count' => true,
                    );
                    wp_get_archives($args);
                    ?>
                </ul>
			</div>
		</div>
		<!-- /.scroll-area -->
	</nav>
</div>