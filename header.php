<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta name="author" content="<?php cwp_the_meta('meta_author'); ?>" />
	<meta name="keywords" content="<?php cwp_the_meta('meta_keywords'); ?>" />
	<?php if (cwp_the_meta('meta_description', false)): ?><meta name="description" content="<?php cwp_the_meta('meta_description'); ?>" /><?php endif; echo "\n"; ?>
	<?php if (cwp_the_meta('og_image', false)): ?><meta property="og:image" content="<?php cwp_the_meta('og_image'); ?>" /><?php endif; echo "\n"; ?>
	<title><?php cwp_the_meta('title'); ?></title>
	<link rel="alternate" type="application/atom+xml" title="Recent Entries" href="<?php bloginfo('atom_url'); ?>" />

	<?php wp_head(); ?>

	<!-- Font  -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@200..900&display=swap" rel="stylesheet">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<!-- swiper -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

	<!-- header js -->
	<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
	<script>
		$(function() {
			const hum = $('#hamburger, .close')
			const nav = $('.sp-nav')
			hum.on('click', function(){
				nav.toggleClass('toggle');
			});
		});
		$(function(){
			const mySwiper = new Swiper('.swiper', {
				// Optional parameters
				autoplay: true,
				loop: true,
			
				// If we need pagination
				pagination: {
				el: '.swiper-pagination',
				},
			
				// Navigation arrows
				navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
				}
			});
		});
	</script>	
</head>
<body <?php body_class(); ?>>

<?php cwp_fb_sdk(); ?>

<header id="header">
	<nav class="header-inner container pc-nav">
		<a href="<?php echo home_url(); ?>"><h1><img class="header-logo" src="<?php echo get_template_directory_uri(); ?>/assets/images/common/site-logo.png" alt="<?php the_field('title', CWP_SITE_CONFIG); ?>" /></h1></a>
		<ul class="gnav-list">
			<li><a href="<?php echo get_permalink( "31461" ); ?>">犬舎紹介</a></li>
			<li><a href="<?php echo home_url(); ?>/puppies/">トイプードル仔犬一覧</a></li>
			<li><a href="<?php echo get_permalink( "31464" ); ?>">各種サービス</a></li>
			<li><a href="<?php echo get_permalink( "31459" ); ?>">ご購入のご案内</a></li>
			<li><a href="<?php echo get_permalink( "31454" ); ?>">アクセス</a></li>
			<li><a href="<?php echo home_url(); ?>/news/">お知らせ</a></li>
		</ul>
		<a class="header-contact" href="<?php echo get_permalink( "247" ); ?>">お問い合わせ</a>
	</nav>
	<a class="" href="/"><h1><img class="header-logo-sp" src="<?php echo get_template_directory_uri(); ?>/assets/images/common/site-logo.png" alt="<?php the_field('title', CWP_SITE_CONFIG); ?>" /></h1></a>
	<nav class="sp-nav">
		<ul>
			<li><a href="<?php echo get_permalink( "31461" ); ?>">犬舎紹介</a></li>
			<li><a href="<?php echo home_url(); ?>/puppies/">トイプードル仔犬一覧</a></li>
			<li><a href="<?php echo get_permalink( "31464" ); ?>">各種サービス</a></li>
			<li><a href="<?php echo get_permalink( "31459" ); ?>">ご購入のご案内</a></li>
			<li><a href="<?php echo get_permalink( "31454" ); ?>">アクセス</a></li>
			<li><a href="<?php echo home_url(); ?>/news/">お知らせ</a></li>
			<li><a href="<?php echo get_permalink( "247" ); ?>">お問い合わせ</a></li>
			<li class="close"><span>閉じる</span></li>
		</ul>
	</nav>
	<div id="hamburger">
      <span></span>
   	</div>
</header>

<?php if (is_home()): ?>
	<?php $mainimages = get_field('mainimages', CWP_MAINIMAGE); ?>

	<div class="mainimage">
		<div class="mainimage-inner">
			<!-- Slider main container -->
			<div class="swiper">
				<div class="swiper-wrapper">
					<!-- Slides -->
					<div class="swiper-slide">
						<div class="slide-img fv-image01" style="" alt=""></div>
						<h2 class="message" style="" alt="">
							トイプードル専門のブリーダー<br>
							<span class="sub">最高のトイプードルを<br>
							あなたのもとへ</span>
						</h2>
					</div>
					<div class="swiper-slide">
						<div class="slide-img fv-image02" style="" alt=""></div>
						<div class="message" style="" alt="">
							大切な家族を迎える前に<br>
							一度犬舎へ見学にきてください！<br>
							<span class="sub">親犬の顔を見てから、どんな環境で育ったのかを確認することができます。</span>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="slide-img fv-image03" style="" alt=""></div>
						<div class="message" style="" alt="">
							いつでも遊びに戻って来れる<br>
							故郷で家族を迎えませんか？<br>
							<span class="sub">TOY ANGELではトリミングやドッグランなどで気軽に遊びに戻って来れる場所を目指しております。</span>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="slide-img fv-image04" style="" alt=""></div>
						<div class="message" style="" alt="">
							お悩みやご相談に<br>
							いつでもサポートいたします。<br>
							<span class="sub">ご購入後のお困りごとなどは、気軽にご相談ください。</span>
						</div>
					</div>
					<!-- <div class="swiper-slide">
						<div class="slide-img fv-image05" style="" alt=""></div>
						<div class="message" style="" alt="">
							一匹一匹愛情をもって
							<br>
							育てさせていただいております。
						</div>

					</div> -->
				</div>

				<!-- If we need navigation buttons -->
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			</div>
			<div class="swiper-pagination"></div>
		</div>
	</div>
<?php else: ?>
<?php endif; ?>

<div id="main">
	<?php if (!is_home()) cwp_breadcrumbs(); ?>
