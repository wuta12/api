<?php
/**
 * 皮皮虾短视频无水印解析
 * 作者：iqiqiya (77sec.cn)
 * 日期：2019/9/2
 */
error_reporting(0);
function curl_pipiXia($id)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5000);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4',
    ));
    curl_setopt($ch, CURLOPT_URL, "https://h5.pipix.com/bds/webapi/item/detail/?item_id=" . $id);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS, -1);
    $contents = curl_exec($ch);
    curl_close($ch);
    return $contents;
}
function getPiPiXiaId($VideoUrl)
{
    $temp = $VideoUrl . "/";
    preg_match("~item/(.*?)/?app_id~", $temp, $matches);
    if (count($matches) == 0) {
        echo '无法解析此视频，请换个链接试一下。';
        exit;
    }
    // var_dump($matches);
    // die;
    // $video_id = $matches[1];
    //echo $video_id;
    $video_id = 7339481675040037157;

    $contents = curl_pipiXia($video_id);
    // var_dump($contents);
    // die;
    return $contents;

    //支持两种链接形式
    //https://h5.hulushequ.com/item/6562792949205358861
    //https://h5.pipix.com/item/6562792949205358861
}
$VideoUrl = "https://h5.pipix.com/item/7298352559368968485?app_id=1319&region=&app=super&carrier_region=&language=&user_id=835282344872573&timestamp=1710212374";
//$VideoUrl = $_GET['url'];
$data = getPiPiXiaId($VideoUrl);
// dd($data);
$data2 = json_decode($data, true);
$url = $data2['data']['item']['comments'][0]['item']['video']['video_high']['url_list'][0]['url'];



//调试打印
function dd($var, $output = 1)
{
    static $infos = array();

    $backtrace = debug_backtrace();
    $file = $backtrace[0]['file'];
    $line = $backtrace[0]['line'];
    $type = gettype($var);
    unset($backtrace);

    ob_start();
    if (is_bool($var)) {
        var_dump($var);
        $content = ob_get_contents();
    } elseif (is_null($var)) {
        var_dump(NULL);
        $content = ob_get_contents();
    } else {
        $content = print_r($var, true);
    }
    ob_end_clean();

    $infos[] = array(
        'file' => $file,
        'line' => $line,
        'type' => $type,
        'content' => $content,
    );
    if ($output === 1 || $output === 2 || $output === 3 || $output === 4) {
        $str = '';
        foreach ($infos as $info) {
            if ($output === 1) {
                $str = '<pre style="padding:10px;border-radius:5px;background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;">';
                $str .= "\r\n";
                $str .= '<strong>FILE</strong>: ' . $info['file'] . " <br />";
                $str .= '<strong>LINE</strong>: ' . $info['line'] . " <br />";
                $str .= '<strong>TYPE</strong>: ' . $info['type'] . " <br />";
                $str .= '<strong>CONTENT</strong>: ' . trim($info['content'], "\r\n");
                $str .= "\r\n";
                $str .= "</pre>";
            }
            echo $str;
        }
        if ($output === 1 || $output === 3) {
            exit(0);
        }
    }
}