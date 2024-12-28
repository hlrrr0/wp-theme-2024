<?php get_header(); ?>

<div id="main" class="search bg-sub">
	<div class="container">
		<h1 class="page-title"><?php wp_title(''); ?></h1>
		<!-- <?php if (is_tax()): ?>
			<?php 
			// カテゴリ説明文
			$obj_term = get_term_by('slug', $term, $taxonomy);
			$category_description = get_field('category_description', $obj_term->taxonomy . '_' . $obj_term->term_id); 
			?>
			<?php if ($category_description): ?>
				<article id="taxonomy-<?php echo $obj_term->term_id; ?>" class="category-description entry clearfix">
					<div class="entry-content">
						<?php echo $category_description; ?>
					</div>
				</article>
			<?php endif; ?>
		<?php endif; ?> -->

		<?php get_search_form(); ?>
		<ul class="main-puppies-list">
			<?php
				$args = array(
					'post_type'=> 'puppies',
					'posts_per_page' => 12,
					'meta_query' => array(
						array(
							'key' => 'status',
							'value' => $status,
							'compare' => 'IN' // 配列内のいずれかに一致するかをチェック
						)
					)
				);
				$search_query = new WP_Query( $args );
			?>
			<?php if ($search_query->have_posts()): ?>
				<?php while ( $search_query->have_posts() ): $search_query->the_post(); ?>
					<?php $images = get_field('gallery'); ?>
					<?php
						$price = get_field('price');
						$price02 = get_field('price02');
						$color = get_field('color');
						$size = get_field('size');
						$sex = get_field('sex');
						$status = get_field('status');
						$birthday = get_field('birthday');
					?>
					<li class="main-puppies-list-item <?php if ($status == 'sold'): ?>main-puppies-list-item-<?php echo $status;?><?php endif;?>">
						<a href="<?php the_permalink(); ?>">
							<div class="main-puppies-list-img">
								<img src="<?php cwp_resize_image($images[0]['url'], 205,180,true); ?>" alt="<?php the_title(); ?>" title="<?php echo esc_html($images[0]['caption']); ?>" />
							</div>
							<ul class="main-puppies-list-field">
								<li><span>誕生日：</span><?php echo $birthday;?>生まれ</li>
								<li><span>毛　色：</span><?php if ($color == 'red'):?>レッド<?php elseif ($color == 'apricot'):?>アプリコット<?php elseif ($color == 'brown'):?>ブラウン<?php elseif ($color == 'white'):?>ホワイト<?php elseif ($color == 'silver'):?>シルバー<?php elseif ($color == 'black'):?>ブラック<?php elseif ($color == 'cream'):?>クリーム<?php endif;?></li>
								<li><span>性　別：</span><?php if ($sex == 'male'):?>男の子<?php elseif ($sex == 'female'):?>女の子<?php endif;?></li>
								<li><span>種　類：</span><?php if ($size == 'tiny'):?>タイニー<?php elseif ($size == 'teacup'):?>ティーカップ<?php endif;?></li>
								<li><span>状　況：</span><?php if ($status == 'onsale'):?>家族募集中<?php elseif ($status == 'sold'):?>家族が見つかりました<?php endif;?></li>
								<li><span>価　格：</span><?php if ($status == 'onsale'): ?><?php echo $price;?>円<?php else: ?>家族が見つかりました<?php endif; ?></li>
							</ul>
							<div class="in-detail-btn">
								<p>この仔犬を見る</p>
								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/top/arrow.png" alt="この仔犬を見る" />
							</div>
						</a>
					</li>
				<?php endwhile; ?>
				<?php if (isset($wp_query)) cwp_pagination($wp_query->max_num_pages); ?>
			<?php else: ?>
				<?php echo CWP_NO_SEARCH_TEXT; ?>
			<?php endif; ?>
		</ul>
		<!-- .gallery-list -->
	</div>
</div>


<?php get_footer(); ?>