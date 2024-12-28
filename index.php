<?php get_header(); ?>

<div id="main">
	<section id="award">
		<div class="main-no1"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/top/award.png" alt="3冠受賞" /></div>
	</section>
	<section id="about" class="bg-sub">
		<div class="main-message container">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/top/fv5.jpg" alt="toy angelからのメッセージ" />
			<div class="main-message-inner">
				<h2><span class="sub-heading">東京都内・神奈川・千葉からもアクセス抜群</span>
					<br>トイプードル専門ブリーダー TOY ANGELがあなたの家族をご紹介します。
				</h2>
				<div>
					<p>TOY ANGEL(トイ・エンジェル)は、ティーカッププードルからタイニープードルの小さめサイズのトイプードル専門ブリーダーです。お顔とサイズに徹底的にこだわりぬいた本物の子達をご紹介。</p>
					<p>ブリーダー直販だからできる、細やかなサービス、アフターフォローを心がけ、健康な仔犬たちを素敵なお客さまのもとへ送り届けることができるよう一生懸命育てています。 </p>
					<p>東京都内・神奈川・千葉からもアクセス抜群で、吉川駅からの無料送迎もございます。気になる仔犬がございましたら、見学やお問い合わせなどお気軽にご連絡ください。</p>
				</div>
			</div>
		</div>
	</section>
	<section id="puppies">
		<div class="main-puppies container">
			<h2 class="main-puppies-title"><span class="sub-heading">Puppies information</span><br>トイプードル仔犬情報</h2>
			<div class="main-puppies-inner">
				<ul class="main-puppies-list">
					<?php
					$args = array(
						'post_type'=> 'puppies',
						'posts_per_page' => 6,
					);
					$posts = get_posts($args);
					?>
					<?php foreach ($posts as $post): setup_postdata($post); ?>
                    	<?php $images = get_field('gallery'); ?>
                        <?php
                        	$price = get_field('price');
                        	$color = get_field('color');
                        	$size = get_field('size');
                        	$sex = get_field('sex');
                        	$status = get_field('status');
                        	$birthday = get_field('birthday');
						?>
						<li class="main-puppies-list-item <?php if ($status == 'sold'): ?>main-puppies-list-item-<?php echo $status;?><?php endif;?>">
							<a href="<?php the_permalink(); ?>">
								<div class="main-puppies-list-img">
									<img src="<?php cwp_resize_image($images[0]['url'], 560,480,true); ?>" alt="<?php the_title(); ?>" title="<?php echo esc_html($images[0]['caption']); ?>" />
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
					<?php endforeach; wp_reset_postdata(); ?>
				</ul>
                <div class="btn-inner">
					<a class="accent-btn" href="/puppies">すべての仔犬を見る</a>
				</div>
			</div>
		</div>
	</section>
	<section id="news" class="bg-sub">
		<div class="main-news container">
			<h2><span class="sub-heading">news</span>
				<br>TOY ANGELからのお知らせ
			</h2>
			<div class="main-news-inner">
				<ul class="main-news-list">
					<?php
					$args = array(
					'post_type'=> 'news',
					'posts_per_page' => 3,
					);
					$posts = get_posts($args);
					?>
					<?php foreach ($posts as $post): setup_postdata($post); ?>
						<a href="<?php the_permalink(); ?>">
							<li class="main-news-list-item">
								<span class="main-news-list-date"><?php the_time('Y.m.d'); ?></span>
								<h3 class="main-news-list-title"><?php cwp_trim_to(get_the_title(), 120, '...'); ?></h3>
							</li>
						</a>
					<?php endforeach; wp_reset_postdata(); ?>
				</ul>
			</div>
			<div class="btn-inner"><a class="accent-btn" href="/news">すべてのお知らせを見る</a></div>
		</div>
	</section>

	<section id="important">
		<div class="main-important container">
			<h2>大切な家族だから、<br>迎える前にご覧ください。</h2>
			<div class="main-important-inner">
				<p class="main-important-text">トイプードルの中でも、
					<br>マイクロサイズ、ティーカップサイズ、タイニーサイズ、トイサイズという名称でサイズが分類されています。
					<br>トイプードルの分類によって、サイズだけでなく飼育するために必要な環境も異なります。
					<br>トイプードルはサイズだけでなく家族としてお世話ができる状況も考慮して選ぶことが大切ですので、
					<br>ぜひ家族として迎える前にご覧ください。
				</p>
			</div>
			<div class="btn-inner"><a class="btn" href="/contents/category/guidance/">購入のご案内</a></div>
		</div>
	</section>
	<section id="blog" class="bg-sub">
		<div class="main-blog container">
			<h2><span class="sub-heading">BLOG&COLUMN</span>
				<br>トイプードル専門店スタッフのブログ&コラム
			</h2>
			<div class="main-blog-inner">
				<ul class="main-blog-list">
					<?php
					$args = array(
						'post_type' => 'blog',
						'posts_per_page' => 3,
					);
					$posts = get_posts($args);
					?>
					<?php foreach ($posts as $post): setup_postdata($post); ?>
						<li class="main-blog-item">
							<a href="<?php the_permalink(); ?>">
								<div class="main-blog-item-img">
									<div class="main-blog-item-img-inner">                                    
                                        <?php if (has_post_thumbnail()): ?>
                                            <img src="<?php cwp_resize_image(get_the_post_thumbnail_url(), 326, 300, true); ?>" alt="<?php the_title(); ?>" class="main-blog-item-img"/>
                                        <?php else: ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/common/noimage.png" alt="<?php the_title(); ?>" class="main-blog-item-img" />
                                        <?php endif; ?>
                                	</div>
								<div class="main-blog-item-inner">
									<div class="main-blog-item-cat-inner">
										<?php $terms = get_the_terms($post->ID, $post->post_type . '_category');?>
										<span class="main-blog-item-cat">
											<?php if($terms): ?>
												<?php echo esc_html($terms[0]->name); ?>
											<?php else: ?>その他
											<?php endif; ?>
										</span>
										<?php the_modified_date(); ?>
									</div>
									<h3 class="main-blog-item-title" data-mh="main-blog-item-title"><?php cwp_trim_to(get_the_title(), 90, '...'); ?></h3>
									<p class="main-blog-item-text"><?php cwp_trim_to(get_the_content(), 200, '...'); ?></p>
									<div class="in-detail-btn">
										<p>すべて読む</p>
										<img src="<?php echo get_template_directory_uri(); ?>/assets/images/top/arrow.png" alt="すべてを読む" />
									</div>
								</div>
							</a>
						</li>
					<?php endforeach; wp_reset_postdata(); ?>
				</ul>
				<div class="btn-inner"><a class="accent-btn" href="/blog">ブログ＆コラム一覧を見る</a></div>
			</div>
		</div>
	</section>
	<section id="service" class="bg-white">
		<div class="main-service container">
			<h2><span class="sub-heading">Service</span>
				<br>仔犬選びからアフターサポートまで
			</h2>
			<p class="main-service-message">TOY ANGELでは、大切な家族になるトイプードルの仔犬をご提供するだけでなく、
				<br>迎え入れるために必要なアイテムから、迎え入れた後のトリミングや葉のクリーニングなどもご用意しております。
				<br>トイプードルを迎えてくださるお客様に可能な限り寄り添うために、
				<br>トリミングのご予約時間は柔軟に対応し、歯のクリーニングや検診は歯科衛生士の国家資格を持ったスタッフが対応いたします。
			</p>
			<div class="main-service-inner">
				<ul class="main-service-list">
					<li class="main-service-item bg-sub">
						<a href="/contents/service/">
							<img src="<?php echo get_template_directory_uri(); ?>/assets/images/top/service1.png" alt="" class="main-service-item-img"  />
							<div class="main-service-item-text">
								<h3 class="main-service-item-title">
									<span class="sub-heading">Purim by TOY ANGEL</span>
									<br>トリミングサロン
								</h3>			
								<p>ブリーダー監修だからできる、
									<br>ワンちゃんの気持ちがわかる
									<br>ハイクオリティなトリミングサロン。
								</p>
							</div>
						</a>
					</li>
					<!-- <li class="main-service-item bg-sub">
						<a href="/contents/category/service/">
							<img src="<?php echo get_template_directory_uri(); ?>/assets/images/top/service2.png" alt="" class="main-service-item-img" />
							<div class="main-service-item-text">
								<h3 class="main-service-item-title">
									<span class="sub-heading">Teeth Cleaning</span>
									<br>歯のクリーニング
								</h3>
								<p>歯科衛生士の国家資格を持ったスタッフが対応。
									<br>人間と同様、 
									<br>安心して愛犬の歯のクリーニングや検診を。
								</p>
							</div>
						</a>
					</li> -->
					<li class="main-service-item bg-sub">
						<a href="/contents/service/">
							<img src="<?php echo get_template_directory_uri(); ?>/assets/images/top/service3.png" alt="" class="main-service-item-img" />
							<div class="main-service-item-text">
								<h3 class="main-service-item-title">
									<span class="sub-heading">TOY ANGEL ONLINE SHOP</span>
									<br>オンラインストア
								</h3>
								<p>トイレからベット・ケージ・給水機・服まで、
									<br>ブリーダーがセレクトした
									<br>愛犬に必要なアイテムが揃います。
								</p>
							</div>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</section>        
	<section id="voice" class="bg-sub">
		<div class="container">
			<h2><span class="sub-heading">Voice</span><br>お客さまの声</h2>
			<div class="main-voice-inner">
				<ul class="main-voice-list">
					<li class="main-voice-item">
						<div>
							<img src="<?php echo get_template_directory_uri(); ?>/assets/images/top/dog.jpg" class="main-voice-img">
							<h3>購入後の相談に<br>親身にのっていただけて安心</h3>
							<p>A・Mさん</p>
							<p class="text-left">躾の相談に親身にのっていただけたり、急に食欲が落ちてきたりと心配事はメールや電話でご相談しております。親身にご対応いただけるので、購入後でも非常に心強いです。</p>
						</div>
					</li>
					<li class="main-voice-item">
						<div>
							<img src="<?php echo get_template_directory_uri(); ?>/assets/images/top/dog3.jpg" class="main-voice-img">
							<h3>遠方なので悩んでいたが、<br>徹底されたこだわりに感動</h3>
							<p>S・Uさん</p>
							<p class="text-left">遠方でしたので犬舎見学に伺うことを一旦悩みましたが、戸田ブリーダー様とお電話でお話した際、その仔犬への徹底的なこだわりに感動し、思いきって犬舎見学させていただき、こちらから仔犬をお迎えすることになりました。</p>
						</div>
					</li>
					<li class="main-voice-item">
						<div>
							<img src="<?php echo get_template_directory_uri(); ?>/assets/images/top/dog5.jpg" class="main-voice-img">
							<h3>何軒か比較しましたが、<br>お顔が抜群に可愛らしく即決</h3>
							<p>R・Zさん</p>
							<p class="text-left">スタッフさんたちの育て方もあり、とてもお利口な子を迎えることができました。広々ドッグランもあり、犬舎は清潔でおしゃれで、一頭一頭に愛情を注いで接していることが伝わり感動し、ここから迎え入れたいと強く思うようになりました。</p>
						</div>
					</li>


				</ul>
			</div>
		</div>
	</section>
</div>

<?php get_footer(); ?>
