<div id="main" class="single-ba">
	<div id="main-inner">

		<h1 class="page-title"><?php the_title(); ?></h1>
		<?php if (have_posts()): ?>
			<?php while (have_posts()): the_post(); ?>
				<?php $images = get_field('gallery'); ?>

				<article id="post-<?php the_ID(); ?>" class="entry clearfix">
					<?php if (!empty($images)): ?>
						<div class="ba-detail lightbox-group">
							<div class="ba-detail-main">
								<?php if (!empty($images[0])): ?>
									<div class="ba-detail-before"><a href="<?php echo $images[0]['url']; ?>" class="lightbox-group-item"><img src="<?php echo $images[0]['sizes']['medium']; ?>" alt="<?php the_title(); ?>" title="<?php echo esc_html($images[0]['caption']); ?>" /></a></div>
								<?php endif; ?>
								<div class="ba-detail-arrow"><i class="fa fa-chevron-right"></i></div>
								<?php if (!empty($images[1])): ?>
									<div class="ba-detail-after"><a href="<?php echo $images[1]['url']; ?>" class="lightbox-group-item"><img src="<?php echo $images[1]['sizes']['medium'] ?>" alt="<?php the_title(); ?>" title="<?php echo esc_html($images[1]['caption']); ?>" /></a></div>
								<?php endif; ?>
							</div>
							
							<?php if (!empty($images[2])): unset($images[0], $images[1]); ?>
								<div class="ba-detail-sub">
									<ul class="row">
										<?php foreach ($images as $image): ?>
											<?php if (!empty($image)): ?>
												<li class="col-sm-3 col-6" data-mh="ba-detail-sub"><a href="<?php echo $image['url']; ?>" class="lightbox-group-item"><img src="<?php echo $image['sizes']['medium']; ?>" alt="<?php the_title(); ?>" title="<?php echo esc_html($image['caption']); ?>" /></a></li>
											<?php endif; ?>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="entry-content clearfix">
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>
		<?php endif; ?>

	</div>
</div>
