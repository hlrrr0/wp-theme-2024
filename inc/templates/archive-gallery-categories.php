<div id="main">
	<div id="main-inner">

		<h1 class="page-title"><?php wp_title(''); ?></h1>
		<?php
		$args = array(
			'taxonomy' => cwp_get_post_type() . '_category',
		);
		$terms = get_categories($args);
		?>
		<?php if (!empty($terms)): ?>
			<?php foreach ($terms as $term): ?>
				<article id="post-<?php echo $term->term_id; ?>" class="entry clearfix">
					<h2 class="entry-title"><?php echo esc_html($term->name); ?></h2>
					<div class="gallery-list">
						<div class="gallery-list-row row">
							<?php
							$args = array(
								'post_type' => cwp_get_post_type(),
								'posts_per_page' => 4,
								'tax_query' => array(
									array(
										'taxonomy' => cwp_get_post_type() . '_category',
										'field' => 'id',
										'terms' => array($term->term_id),
									),
								),
							);
							$posts = get_posts($args);
							?>
							<?php if (!empty($posts)): ?>
								<?php foreach ($posts as $post): setup_postdata($post); ?>
									<?php $images = get_field('gallery'); ?>
									<div class="gallery-list-col col-sm-3 col-6">
										<div class="gallery-list-item" data-mh="gallery-list-item">
											<div class="gallery-list-img"><a href="<?php the_permalink(); ?>"><img src="<?php cwp_resize_image($images[0]['url'], 600); ?>" alt="<?php the_title(); ?>" title="<?php echo esc_html($images[0]['caption']); ?>" /></a></div>
											<h2 class="gallery-list-title"><?php the_title(); ?></h2>
										</div>
									</div>
								<?php endforeach; wp_reset_postdata(); ?>
							<?php endif; ?>
						</div>
						<div class="text-right">
							<a href="<?php echo esc_url(get_term_link($term)); ?>"><i class="fa fa-angle-double-right"></i>詳細はこちら</a>
						</div>
					</div>
					<!-- .gallery-list -->
				</article>
			<?php endforeach; ?>
		<?php else: ?>
			<?php echo CWP_NO_POSTS_TEXT; ?>
		<?php endif ?>

	</div>
</div>
