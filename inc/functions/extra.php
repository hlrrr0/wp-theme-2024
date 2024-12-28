<?php
/*
 * extra.php
 *
 * サイトごとに関数を追加する必要がある場合は、ここに書く。
 * 
 */
// メールフォームの textarea にひらがなが無ければ送信できない（contact form7）
add_filter('wpcf7_validate_textarea', 'wpcf7_validation_textarea_hiragana', 10, 2);
add_filter('wpcf7_validate_textarea*', 'wpcf7_validation_textarea_hiragana', 10, 2);
function wpcf7_validation_textarea_hiragana($result, $tag)
{
    $name = $tag['name'];
    $value = (isset($_POST[$name])) ? (string) $_POST[$name] : ”;
    if ($value !== ” && !preg_match('/[ぁ-ん]/u', $value)) {
        $result['valid'] = false;
        $result['reason'] = array($name => 'エラー / この内容は送信できません。');
    }
    return $result;
}
// フリガナ(name-kana)内にカタカナ入力でない時にエラー（contact form7）
add_filter('wpcf7_validate_text',  'wpcf7_validate_post_text', 11, 2);
add_filter('wpcf7_validate_text*', 'wpcf7_validate_post_text', 11, 2);
function wpcf7_validate_post_text($result,$tag){
    $tag = new WPCF7_Shortcode($tag);
    $name = $tag->name;
    $value = isset($_POST[$name]) ? trim(wp_unslash(strtr((string) $_POST[$name], "\n", " "))) : "";
 
    // 入力エリアの id を限定
    if ($name === "name-kana") {
        //"your-lastname-kana"を適用したいフォーム項目の名前に書き換える
        if(!preg_match("/^[ア-ヶー 　]+$/u", $value)) {
            if (method_exists($result, 'invalidate')) {
                $result->invalidate( $tag,"全角カタカナで入力してください");
            } else {
                $result['valid'] = false;
                $result['reason'][$name] = '全角カタカナで入力してください';
            }
        }
    }
 
    return $result;
}