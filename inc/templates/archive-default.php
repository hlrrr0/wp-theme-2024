<div id="main" class="archive-default">
	<div id="main-inner">

		<h1 class="page-title"><?php wp_title(''); ?></h1>
		<?php if (have_posts()): ?>
			<?php while (have_posts()): the_post(); ?>
				<article id="post-<?php the_ID(); ?>" class="entry clearfix">
					<div class="entry-content">
						<h2 class="entry-title"><?php the_title(); ?></h2>
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>
		<?php else: ?>	
			<?php echo CWP_NO_POSTS_TEXT; ?>
		<?php endif; ?>

	</div>
</div>
