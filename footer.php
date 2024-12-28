	<?php if (cwp_get_template_type() === 'blog') require_once TEMPLATEPATH . '/inc/parts/blognav.php'; ?>
</div>
<!-- /#content -->

<footer id="footer">
	<div class="footer-inner">
		<!-- <div class="pagetop-btn-wrap"><a href="#container" class="pagetop-btn scroll"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/default/pagetop-btn.png" alt="ページ上部へ" /></a></div> -->
		<div class="footer-logo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/default/footer-logo.png" alt="<?php the_field('title', CWP_SITE_CONFIG); ?>" /></div>
		<div class="footer-description"><?php cwp_the_meta('site_description'); ?></div>
		<div class="footer-about"><?php the_field('about', CWP_FOOTER); ?></div>
		<div class="footer-tel">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/default/footer-tel.png" alt="電話番号: <?php the_field('tel', CWP_SITE_CONFIG); ?>" />
			<p class="footer-hours">（電話受付<?php the_field('hours', CWP_HEADER); ?>）</p>
		</div>
		

		<div class="footer-btn">
			<ul class="footer-btn-list">
				<li class="footer-btn-list-item">
					<a href="/contents/category/access/" class="footer-btn-list-item-btn">
						<p>アクセス</p>
						<img src="<?php echo get_template_directory_uri(); ?>/assets/images/top/icon-pin.png" alt="アクセス方法を見る" />
					</a>
				</li>

				<li class="footer-btn-list-item">
					<a href="/contents/category/contact/" class="footer-btn-list-item-btn">
						<p>お問い合わせ</p>
						<img src="<?php echo get_template_directory_uri(); ?>/assets/images/top/icon-message.png" alt="お問い合わせ" />
					</a>
				</li>

			</ul>
		</div>
		
		<div class="footer-nav-inner">					
			<ul class="footer-nav">
				<li><a href="/">HOME</a></li>
				<li><a href="/">犬舎紹介</a></li>
				<li><a href="/">各種サービス</a></li>
				<li><a href="/puppies/">トイプードル仔犬情報</a></li>
				<li><a href="/">ご購入のご案内</a></li>
				<li><a href="/news">お知らせ</a></li>
				<li><a href="/blog">ブログ&コラム</a></li>
				<li><a href="/">プライバシーポリシー</a></li>
			</ul>
			<ul class="footer-sns">
				<?php $items = get_field('sns', CWP_FOOTER); ?>
				<?php foreach ($items as $item): ?>
					<li class="footer-sns-list-item"><a href="<?php echo $item['link']; ?>"><img src="<?php echo $item['image']['url']; ?>" alt="<?php echo $item['image']['title']; ?>" /></a></li>
				<?php endforeach; ?>
			</ul>
		</div>		
		<div class="footer-copyright">&copy; <?php echo date('Y'); ?> <?php cwp_the_meta('meta_author'); ?> All Rights Reserved.</div>
	</div>
</footer>
<?php wp_footer(); ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>
</html>
