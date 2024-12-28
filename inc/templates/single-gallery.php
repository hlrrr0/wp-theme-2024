<div id="single-puppies" class="bg-sub single-gallery">
    <div class="container">    
        <?php if (have_posts()): ?>
            <?php while (have_posts()): the_post(); ?>
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
                <article id="post-<?php the_ID(); ?>" class="entry">
                    <?php if ($images): ?>
                        <div class="gallery-field">
                            <div class="puppies-gallery">
                                <ul class="">
                                    <?php foreach ($images as $image) : ?>
                                        <li><a href="<?php echo $image['url']; ?>" class="">
                                            <img src="<?php cwp_resize_image($image['url'], 700, 450, true); ?>" class="puppies-gallery-image" alt="<?php the_title(); ?>のサムネイル" title="<?php echo esc_html($image['caption']); ?>" /></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="puppies-details">
                                <h1 class="page-title"><?php the_title(); ?></h1>
                                <!-- <h2 class=""><?php if ($status == 'onsale'): ?>【家族募集中】<?php the_title(); ?>&nbsp;<?php echo $price; ?>円<?php else: ?>SOLD OUT<?php endif; ?></h2> -->
                            
                                <table>
                                    <tr><th class="bg-main">犬種：サイズ</th></tr>
                                    <tr><td class="bg-sub">トイプードル：<?php if ($size == 'tiny'):?>タイニー<?php elseif ($size == 'teacup'):?>ティーカップ<?php endif;?></td></tr>
                                    <tr><th class="bg-main">毛　色</th></tr>
                                    <tr><td class="bg-sub"><?php if ($color == 'red'):?>レッド<?php elseif ($color == 'apricot'):?>アプリコット<?php elseif ($color == 'brown'):?>ブラウン<?php elseif ($color == 'white'):?>ホワイト<?php elseif ($color == 'silver'):?>シルバー<?php elseif ($color == 'black'):?>ブラック<?php elseif ($color == 'cream'):?>クリーム<?php endif;?></td></tr>
                                    <tr><th class="bg-main">性　別</th></tr>
                                    <tr><td class="bg-sub"><?php if ($sex == 'male'):?>男の子<?php elseif ($sex == 'female'):?>女の子<?php endif;?></td></tr>
                                    <tr><th class="bg-main">誕生日</th></tr>
                                    <tr><td class="bg-sub"><?php echo $birthday;?>生まれ</td></tr>
                                    <tr><th class="bg-main">状　況</th></tr>
                                    <tr><td class="bg-sub"><?php if ($status == 'onsale'):?>家族募集中<?php elseif ($status == 'sold'):?>家族が見つかりました<?php endif;?></td></tr>
                                    <tr><th class="bg-main">生体価格</th></tr>
                                    <tr><td class="bg-sub"><?php if ($status == 'onsale'): ?><?php echo $price;?>円<?php else: ?>SOLD OUT<?php endif; ?></td></tr>
                                    <tr><th class="bg-main">税込価格</th></tr>
                                    <tr><td class="bg-sub"><?php if ($status == 'onsale'): ?><?php echo $price02;?>円<?php else: ?>SOLD OUT<?php endif; ?></td></tr>
                                </table>
                                <div class="entry-content">
                                    <?php the_content(); ?>
                                </div>
                                <div class="puppies-notice">
                                    <?php $items = get_field('notice', CWP_PUPPIES); ?>
                                    <?php foreach ($items as $item): ?>
                                        <div class="puppies-notice-once">
                                            <?php $cols = $item['col']; ?>
                                            <?php foreach ($cols as $col): ?>
                                                <h3><?php echo $col['title']; ?></h3>
                                                <ol class="puppies-notice-inner">
                                                    <?php $texts = $col['item']; ?>
                                                    <?php foreach ($texts as $text): ?>
                                                        <li class="puppies-notice-list"><?php echo $text['text']; ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
        
        

        <?php if (cwp_get_template_type() === 'gallery') require_once TEMPLATEPATH . '/inc/parts/questions.php'; ?>
        <div class="puppies-banner">
            <ul class="puppies-banner-list">
                <li class="puppies-banner-btn contact">
                    <a href="/contents/category/contact/" class="puppies-banner-item">
                        <div class="puppies-banner-item-txt">
                            <p>気になった子を実際に見たい！</p>
                            <h3>TOY ANGELの<br>見学予約はこちら</h3>
                        </div>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/top/arrow.png" alt="見学予約" />
                    </a>
                </li>
                <li class="puppies-banner-btn access">
                    <a href="/contents/category/access/" class="puppies-banner-item">
                        <div class="puppies-banner-item-txt">
                            <p>都心からのアクセス抜群！</p>
                            <h3>TOY ANGELへの<br>アクセス方法はこちら</h3>
                        </div>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/top/arrow.png" alt="アクセス" />
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>