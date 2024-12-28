<?php
/**
 * config.php
 *
 * 設定ファイル。テーマ内で使用する共通の定数や変数を記述する
 */

// 管理画面のアップデート通知 (true: 表示, false: 非表示)
define('CWP_SHOW_UPDATE_NOTICE', false);

// 管理バーを表示するかどうか (true: 表示, false: 非表示)
define('CWP_SHOW_ADMIN_BAR', false);

// 絵文字を使用するかどうか (true: 使用, false: 未使用)
define('CWP_USE_EMOJI', true);

// デフォルトの "投稿" を使用するかどうか (true: 使用, false: 未使用)
define('CWP_USE_POST_TYPE_POST', false);

// デフォルトの "固定ページ" を使用するかどうか (true: 使用, false: 未使用)
define('CWP_USE_POST_TYPE_PAGE', false);

// 投稿IDでの自動スラッグ指定を使用するかどうか (true: 使用, false: 未使用)
define('CWP_USE_POST_ID_SLUG', true);

// 投稿がないページで表示するテキスト
define('CWP_NO_POSTS_TEXT', '<p>現在ページ作成中です。</p>');

// 検索結果がないページで表示するテキスト
define('CWP_NO_SEARCH_TEXT', '<p>検索結果に当てはまるものが見つかりません。</p>');


// サイトパーツ (サイトパーツIDは定数化して呼び出す)
define('CWP_SITE_CONFIG', 129);
define('CWP_TOP_CONFIG', 395);
define('CWP_HEADER', 220);
define('CWP_MAINIMAGE', 109);
define('CWP_MAIN', 114);
define('CWP_SIDE', 112);
define('CWP_FOOTER', 222);
define('CWP_SPHONE_CONFIG', 103);
define('CWP_PUPPIES', 2168);

/**
 * テーマ内で使用する変数はglobal変数として宣言する
 */
global $cwp_production_sapis, $cwp_vars, $cwp_cpt_definitions;

/**
 * 本番環境用SAPI設定
 * 下記SAPIでの実行時は、管理画面のアップデート通知を非表示にする
 */
$cwp_production_sapis = array('apache2handler', 'cgi-fcgi');

// テーマフォルダ内のcss/jsファイルを読み込む場合
add_action( 'wp_enqueue_scripts', function(){
	wp_enqueue_style( 'my-style', get_template_directory_uri() . '/assets/css/common/reset.css' );
	wp_enqueue_style( 'my-style2', get_template_directory_uri() . '/assets/css/common/base.css' );
	wp_enqueue_style( 'my-style5', get_template_directory_uri() . '/assets/css/default/theme.css' );
	// wp_enqueue_style( 'my-style1', get_template_directory_uri() . '/assets/css/common/editor-style.css' );

	// wp_enqueue_style( 'my-style3', get_template_directory_uri() . '/assets/css/common/vendor.css' );
	// wp_enqueue_style( 'my-style4', get_template_directory_uri() . '/assets/css/default/extra.css' );

	// wp_enqueue_style( 'my-script1', get_template_directory_uri() . '/assets/js/common/common.js' );
	// wp_enqueue_style( 'my-script2', get_template_directory_uri() . '/assets/js/common/jquery.datetimepicker.full.min.js' );
	// wp_enqueue_style( 'my-script3', get_template_directory_uri() . '/assets/js/common/jquery.easing.1.3.js' );
	// wp_enqueue_style( 'my-script4', get_template_directory_uri() . '/assets/js/default/jquery.matchHeight.js' );
	// wp_enqueue_style( 'my-script5', get_template_directory_uri() . '/assets/js/default/jquery.simple.accordion.js' );
	// wp_enqueue_style( 'my-script6', get_template_directory_uri() . '/assets/js/default/jquery.customScroll.js' );

	// wp_enqueue_style( 'my-script8', get_template_directory_uri() . '/assets/js/default/jquery.rollOver.js' );
	// wp_enqueue_style( 'my-script9', get_template_directory_uri() . '/assets/js/default/jquery.droppy.js' );
	// wp_enqueue_style( 'my-script10', get_template_directory_uri() . '/assets/js/default/jquery.mousewheel.js' );
	// wp_enqueue_style( 'my-script11', get_template_directory_uri() . '/assets/js/default/jquery.customScroll.js' );
	wp_enqueue_style( 'my-script12', get_template_directory_uri() . '/assets/js/default/theme.js' );
   
} );

/**
 * javascript内で使用するフィールドを定義する配列
 *
 * cwp_vars で取得できます (e.g. cwp_vars.speed)
 */
// $cwp_vars = array(
// 	// 'google_map_tag' => get_field('google_map_tag', CWP_SITE_CONFIG, false),
// 	'infinite_loop' => get_field('infinite_loop', CWP_MAINIMAGE),
// 	'speed' => get_field('speed', CWP_MAINIMAGE),
// 	'auto' => get_field('auto', CWP_MAINIMAGE),
// 	'pause' => get_field('pause', CWP_MAINIMAGE),
// );

/**
 * カスタム投稿タイプ定義用配列
 */
$cwp_cpt_definitions = array(
	// サイトパーツ
	array(
		'default' => array(
			'name' => 'サイトパーツ',
			'slug' => 'siteparts',
			'template_type' => array(
				'archive' => false,
				'taxonomy' => false,
				'single' => false,
			),
			'capability_type' => 'post',
		),
		'cpt' => array(
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'supports' => array('title', 'revisions'),
			'menu_icon' => 'dashicons-admin-tools',
		),
	),
	// コンテンツ
	array(
		'default' => array(
			'name' => 'コンテンツ',
			'slug' => 'contents',
			'template_type' => array(
				'archive' => false,
				'taxonomy' => 'contents',
				'single' => 'contents',
			),
			'capability_type' => 'post',
			'posts_per_page' => -1,
		),
	),
	// スタッフブログ
	array(
		'default' => array(
			'name' => ' ブログ＆コラム',
			'slug' => 'blog',
			'template_type' => array(
				'archive' => 'blog',
				'taxonomy' => 'blog',
				'single' => 'blog',
			),
			'capability_type' => 'post',
		),
	),
	// インフォメーション
	array(
		'default' => array(
			'name' => '新着情報',
			'slug' => 'news',
			'template_type' => array(
				'archive' => 'blog',
				'taxonomy' => 'blog',
				'single' => 'blog',
			),
			'capability_type' => 'post',
		),
	),
	// ギャラリー
	array(
		'default' => array(
			'name' => ' トイプードル仔犬一覧',
			'slug' => 'puppies',
			'template_type' => array(
				'archive' => 'gallery',
				'taxonomy' => 'gallery',
				'single' => 'gallery',
			),
			'capability_type' => 'post',
			'posts_per_page' => 12,
		),
	),
	// 追加用
	// array(
	// 	// カスタム投稿タイプ: 通常オプション (必須)
	// 	'default' => array(
	// 		'name'            => 'カスタム投稿タイプ名', // スタッフブログなど
	// 		'slug'            => 'slug',              // blog など
	// 		'template_type'   => array(               // 使用するテンプレート種別。blog, galleryなど。配列でpageごとに指定する
	// 			'archive'  => '', // 投稿一覧ページ
	// 			'taxonomy' => '', // タクソノミーページ
	// 			'category' => '', // カテゴリページ（投稿 postのみ）
	// 			'tag'      => '', // タグページ（投稿 postのみ）
	// 			'single'   => '', // 投稿個別ページ
	// 		),
	//		'capability_type' => 'post',              // post: ブログ, page: コンテンツ
	// 		'posts_per_page'  => 10,                  // 1ページの表示記事数。-1は全件表示 (省略した場合、投稿設定の値が適用されます。)
	// 	),
	// 	// 通常、ここから下は指定不要
	// 	// カスタム投稿タイプ 詳細オプション
	// 	// 指定可能なオプションは http://wpdocs.m.osdn.jp/%E9%96%A2%E6%95%B0%E3%83%AA%E3%83%95%E3%82%A1%E3%83%AC%E3%83%B3%E3%82%B9/register_post_type#.E5.BC.95.E6.95.B0 を参照。
	// 	'cpt' => array(
	// 	),
	// 	// タクソノミー: 詳細オプション
	// 	// 指定可能なオプションは https://wpdocs.osdn.jp/%E9%96%A2%E6%95%B0%E3%83%AA%E3%83%95%E3%82%A1%E3%83%AC%E3%83%B3%E3%82%B9/register_taxonomy#.E5.BC.95.E6.95.B0 を参照。
	// 	'tax' => array(
	// 	),
	// ),
);
