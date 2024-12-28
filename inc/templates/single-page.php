<div id="main" class="single-page">
	<div id="main-inner">

		<h1 class="page-title"><?php the_title(); ?></h1>
		<?php if (have_posts()): ?>
			<?php while (have_posts()): the_post(); ?>
				<?php 
				$post_content = get_the_content();
				$args = [
					'post_type' => $post->post_type,
					'post_parent' => $post->ID,
					'posts_per_page' => -1,
				];
				$children = get_posts($args);
				?>
				<?php if ($post_content): ?>
					<article id="post-<?php the_ID(); ?>" class="entry clearfix">
						<div class="entry-content clearfix">
							<?php the_content(); ?>
						</div>
					</article>
				<?php elseif ($children): ?>
					<?php foreach ($children as $post): setup_postdata($post); ?>
						<article id="post-<?php the_ID(); ?>" class="entry clearfix">
							<div class="entry-content">
								<h2><?php the_title(); ?></h2>
								<div class="row">
									<?php if (has_post_thumbnail()): ?>
										<div class="col-sm-4 mb5">
											<a href="<?php the_permalink(); ?>"><img src="<?php cwp_resize_image(get_the_post_thumbnail_url(), 600); ?>" alt="<?php the_title(); ?>" /></a>
										</div>
									<?php endif; ?>
									<div class="<?php if (has_post_thumbnail()): ?>col-sm-8<?php else: ?>col-sm-12<?php endif; ?>">
										<?php the_field('description'); ?>
										<div class="text-right mt15"><a href="<?php the_permalink(); ?>"><i class="fa fa-angle-double-right"></i>詳細はこちら</a></div>
									</div>
								</div>
							</div>
						</article>
					<?php endforeach; wp_reset_postdata(); ?>
				<?php else: ?>
					<?php echo CWP_NO_POSTS_TEXT; ?>
				<?php endif; ?>
			<?php endwhile; ?>
		<?php endif; ?>

	</div>
</div>