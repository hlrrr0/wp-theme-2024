<div id="main" class="archive-contents bg-sub">
	<div class="container">
		<h1 class="page-title"><?php wp_title(''); ?></h1>
		<?php if (have_posts()): ?>
			<?php while (have_posts()): the_post(); ?>
				<article id="post-<?php the_ID(); ?>" class="entry clearfix">
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>
		<?php else: ?>
			<?php 
			$obj_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$args = array(
				'parent'   => $obj_term->term_id,
				'taxonomy' => get_query_var('taxonomy'),
			);
			$terms = get_categories($args);
			?>
			<?php if (!empty($terms)): ?>
				<?php foreach ($terms as $term): ?>
					<article id="post-<?php echo $term->term_id; ?>" class="entry clearfix">
						<?php $category_image = get_field('category_image', $term->taxonomy . '_' . $term->term_id); ?>						
						<div class="entry-content">
							<h2 class="entry-title"><?php echo esc_html($term->name); ?></h2>
							<div class="row">
								<?php if ($category_image): ?>
									<div class="col-sm-4 mb5">
										<a href="<?php echo esc_url(get_term_link($term)); ?>"><img src="<?php cwp_resize_image($category_image['url'], 600); ?>"></a>
									</div>
								<?php endif; ?>
								<div class="<?php if ($category_image): ?>col-sm-8<?php else : ?>col-sm-12<?php endif; ?>">
									<?php echo get_field('category_description', $term->taxonomy . '_' . $term->term_id); ?>
									<div class="text-right">
										<a href="<?php echo esc_url(get_term_link($term)); ?>"><i class="fa fa-angle-double-right"></i>詳細はこちら</a>
									</div>
								</div>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			<?php else: ?>
				<?php echo CWP_NO_POSTS_TEXT; ?>
			<?php endif ?>
		<?php endif; ?>

	</div>
</div>
