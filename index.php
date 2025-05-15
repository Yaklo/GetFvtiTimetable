<!--不好意思，正在维护中~-->
<?php
// 根据请求参数分发到不同的处理文件

// 检查单个 GET 参数
// if (isset($_GET['style']) && !empty($_GET['style'])) {
//     // 参数存在且不为空
//     require 'style'.$_GET['style'].'.php';
// } else {
//     // 参数不存在或为空
//     require 'style1.php';
// }

// 定义允许的样式列表，防止任意文件包含
$allowedStyles = ['0','1', '2']; // 根据需要添加更多允许的样式

// 安全获取并验证style参数
$style = isset($_GET['style']) ? trim($_GET['style']) : 'style1';

// 验证style是否在允许列表中，防止目录遍历攻击
if (!in_array($style, $allowedStyles)) {
    $style = '1'; // 默认回退样式
    // echo("样式不存在，已返回默认样式");
}

// 构造安全的文件路径
$styleFile = 'style' . $style . '.php';

// 确保文件存在再包含
if (file_exists($styleFile)) {
    require $styleFile;
} else {
    require 'style1.php'; // 默认样式
}

// 结束脚本执行，避免后续代码意外运行
exit();
?>