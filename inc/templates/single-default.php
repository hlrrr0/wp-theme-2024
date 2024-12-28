<div id="main" class="single-cdefault">
	<div id="main-inner" class="container">

		<h1 class="page-title"><?php the_title(); ?></h1>
		<?php if (have_posts()): ?>
			<?php while (have_posts()): the_post(); ?>
				<article id="post-<?php the_ID(); ?>" class="entry clearfix">
					<div class="entry-content clearfix">
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>
		<?php endif; ?>

	</div>
</div>