<div id="" class="archive-blog bg-sub">
	<div class="container">
		<h1 class="page-title"><?php wp_title(''); ?></h1>
		<?php if (have_posts()): ?>
			<?php while (have_posts()): the_post(); ?>
				<a href="<?php the_permalink(); ?>" style="display: block;">
					<article id="post-<?php the_ID(); ?>" class="flex">
						<?php if (has_post_thumbnail()): ?>
							<img src="<?php cwp_resize_image(get_the_post_thumbnail_url(), 200); ?>" alt="<?php the_title(); ?>" />
						<?php endif; ?>
						<div class="blog-text-inner<?php if (has_post_thumbnail()): ?><?php else: ?><?php endif; ?>">
							<h2 class="blog-title"><?php the_title(); ?></h2>
							<div class="blog-text"><?php the_excerpt(); ?></div>
							<div class="entry-meta"><?php cwp_posted_on(); ?></div>
						</div>
					</article>
				</a>
			<?php endwhile; ?>
			<?php if (isset($wp_query)) cwp_pagination($wp_query->max_num_pages); ?>
		<?php else: ?>
			<?php echo CWP_NO_POSTS_TEXT; ?>
		<?php endif; ?>

	</div>
</div>
