<?php
// 接收参数
$class = $_GET['class'] ?? null;
$style = $_GET['style'] ?? "1";
$updata = $_GET['updata'] ?? null;
$list = $_GET['list'] ?? null;
$week = $_GET['week'] ?? null;

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
    updata_process($class, $updata);
} else {
    require_once 'style.php';
    style_process($class, $style, $week);
}
?>