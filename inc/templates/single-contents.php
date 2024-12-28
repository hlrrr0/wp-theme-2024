<div id="single-contents" class="bg-sub">
	<div class="container">
		<!-- <div class="page-title"><?php cwp_cpt_name(); ?></div> -->
		<?php if (have_posts()): ?>
			<?php while (have_posts()): the_post(); ?>
					<article id="post-<?php the_ID(); ?>" class="entry">
						<div class="entry-content">
							<h1 class="entry-title"><?php the_title(); ?></h1>
							<?php the_content(); ?>
						</div>
					</article>
			<?php endwhile; ?>
		<?php endif; ?>

	</div>
</div>