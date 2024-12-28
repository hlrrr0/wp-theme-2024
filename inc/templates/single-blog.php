<div id="single-blog" class="bg-sub">
	<div class="container">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php if (have_posts()): ?>
			<?php while (have_posts()): the_post(); ?>
				<article id="post-<?php the_ID(); ?>" class="entry">
					<div class="entry-content">
						<?php the_content(); ?>
					</div>

					<div class="entry-footer flex">
						<div class="bookmarks flex">
							<?php cwp_bookmarks(); ?>
						</div>

						<div class="entry-meta flex">
							<?php cwp_posted_on(); ?>
						</div>
					</div>

					<div class="entry-post-links flex">
						<?php cwp_adjacent_post_links(); ?>
					</div>
				</article>
			<?php endwhile; ?>
		<?php endif; ?>

	</div>
</div>
