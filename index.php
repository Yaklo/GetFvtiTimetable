<?php
// 接收并验证参数
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// 获取GET参数
$class = isset($_GET['class']) ? sanitize_input($_GET['class']) : null;
$style = isset($_GET['style']) ? sanitize_input($_GET['style']) : "1";
$updata = isset($_GET['updata']) ? sanitize_input($_GET['updata']) : null;
$list = isset($_GET['list']) ? sanitize_input($_GET['list']) : null;
$week = isset($_GET['week']) ? sanitize_input($_GET['week']) : null;

// 获取并验证POST参数
$yzm = isset($_POST['yzm']) ? sanitize_input($_POST['yzm']) : null;
$phpsessid = isset($_POST['phpsessid']) ? sanitize_input($_POST['phpsessid']) : null;
$cookie = isset($_POST['cookie']) ? sanitize_input($_POST['cookie']) : null;


if(!empty($list) || is_null($class)) {
    require_once 'list.php';
    list_process($list ?? '1');
    exit();
}

// 包含auth.php进行验证
require_once 'auth.php';
$is_valid = auth_check($class);

if(!$is_valid) {
    die('你访问的课表不存在');
}

// 根据参数路由
if(!empty($updata)) {
    require_once 'updata.php';
    updata_process($class, $updata, $yzm, $phpsessid, $cookie);
} else {
    require_once 'style.php';
    style_process($class, $style, $week);
}
?>