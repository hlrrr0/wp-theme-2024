<?php
/**
 * 画像トリミング関数
 *
 * @param string $url    (required) 画像のURL。"http"|"https"もしくは"/"（ルート相対パス）で始まるurlを指定
 * @param int    $width  (required)
 * @param int    $height (required)
 * @param bool   $crop   (optional) トリミングするかどうか。 true: cropする false: cropしない
 * @param bool   $echo   (optional) 返り値を出力するかどうか。true: 文字列を出力false: 値を返す
 *
 * @return string 画像のurl $echo = falseの場合
 *
 */

function cwp_resize_image($url, $width, $height = null, $crop = false, $echo = true, $quality = 90) {
	$document_root = $_SERVER['DOCUMENT_ROOT'];
	$thumbnail_dir = $document_root . '/images/thumb';
	$protocol      = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on')) ? 'https://' : 'http://';
	$site_url      = $protocol . $_SERVER['HTTP_HOST'];

	// 元画像
	$from                  = array();
	$from['relative_path'] = str_replace($site_url, '', $url);
	$from['path']          = $document_root . $from['relative_path'];
	$from['url']           = $url;
	list($from['width'], $from['height']) = getimagesize($from['path']);
	$from['pathinfo']      = pathinfo($from['path']);

	// リサイズ後画像
	$to                   = array();
	$to['suffix']         = '-' . $width . 'x' . $height;
	$to['suffix']        .= $crop ? '-c' : '';
	$to['relative_path']  = str_replace($from['pathinfo']['basename'], $from['pathinfo']['filename'] . $to['suffix'] . '.' . $from['pathinfo']['extension'], $from['relative_path']);
	$to['path']           = $thumbnail_dir . $to['relative_path'];
	$to['url']            = $site_url . '/images/thumb' . $to['relative_path'];
	$to['width']          = $width;
	$to['height']         = $height;
	$to['pathinfo']       = pathinfo($to['path']);

	// 元画像が存在しない or ディレクトリの場合は処理しない
	if (!file_exists($from['path']) || is_dir($from['path'])) {
		if ($echo) {
			echo $from['url'];
			return;
		}
		else {
			return $from['url'];
		}
	}

	// リサイズ後画像が存在する && デバッグモードがfalseなら処理しない
	if (file_exists($to['path'])) {
		if ($echo) {
			echo $to['url'];
			return;
		}
		else {
			return $to['url'];
		}
	}

	// ディレクトリが存在しなければ作成
	if (!file_exists($to['pathinfo']['dirname'])) {
		mkdir($to['pathinfo']['dirname'], 0755, true);
	}

	$image = wp_get_image_editor($from['path']);
	$image->set_quality($quality);
	

	// リサイズ処理開始(注意：$image->resizeメソッドは小さい画像の拡大をしてくれないのでcropだけで処理する)
	
	if ($crop) {// トリミングする場合

		//リサイズ後のwidthとheightは両方指定されてないといけない
		if(!$to['width']||!$to['height']) return false;
		$width_ratio = $from['width'] / $to['width'];
		$height_ratio = $from['height'] / $to['height'];

		// 元画像とリサイズ後画像の各辺を比較し、小さい辺に合わせてリサイズする
		if ($width_ratio < $height_ratio){
			$new_height = round($from['height'] / $width_ratio);
			$src_x = 0;
			$src_w = $from['width'];
			$src_y = round((($new_height - $to['height']) / 2) * $width_ratio);
			$src_h = round($to['height'] * $width_ratio);			
		}
		else{
			$new_width = round($from['width'] / $height_ratio);
			$src_x = round((($new_width-$to['width']) / 2) * $height_ratio);
			$src_w = round($to['width'] * $height_ratio);
			$src_y = 0;
			$src_h = $from['height'];
		}
		$image->crop($src_x,$src_y,$src_w,$src_h,$to['width'],$to['height']);
		$image->save($to['path']);
	}
	else {// トリミングしない場合

		//リサイズ後のwidthとheightは、少なくともひとつは指定されてないといけない。
		if(!$to['width']&&!$to['height']) return false;
		if($to['width']) $width_ratio = $from['width'] / $to['width'];
		else $width_ratio = 0;
		if($to['height']) $height_ratio = $from['height'] / $to['height'];
		else $height_ratio = 0;

		// 元画像とリサイズ後画像の各辺を比較し、大きい辺に合わせてリサイズする
		if ($width_ratio < $height_ratio){
			$new_width = round($from['width'] / $height_ratio);
			$new_height = $to['height'];			
		}
		else{
			$new_width = $to['width'];
			$new_height = round($from['height'] / $width_ratio);
		}
		$image->crop(0,0,$from['width'],$from['height'],$new_width,$new_height);
		$image->save($to['path']);
	}

	if ($echo) {
		echo $to['url'];
		return;
	}
	else {
		return $to['url'];
	}
}
