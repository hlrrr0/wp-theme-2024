<?php
/*
 * functions.php
 *
 * テーマの機能追加を行うファイル
 * 追加を行う場合は、extra.php に記述するか、/inc/内にファイルを作成して requireする。
 *
 */

require_once dirname(__FILE__) . '/inc/functions/config.php';
require_once dirname(__FILE__) . '/inc/functions/init.php';
require_once dirname(__FILE__) . '/inc/functions/admin.php';
require_once dirname(__FILE__) . '/inc/functions/template-tags.php';
require_once dirname(__FILE__) . '/inc/functions/breadcrumbs.php';
require_once dirname(__FILE__) . '/inc/functions/calendar.php';
require_once dirname(__FILE__) . '/inc/functions/resize-image.php';
require_once dirname(__FILE__) . '/inc/functions/extra.php';
