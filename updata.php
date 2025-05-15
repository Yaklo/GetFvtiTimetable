<?php
function updata_process($class, $updata, $yzm, $phpsessid, $cookie) {
    
    $data = json_decode(file_get_contents("tabledata/{$class}/data.json"), true);
    $classyear = $data['classyear'];
    $classname = $data['classname'];
    $tableinfo = $data['tableinfo'];
    $updataurl_1 = $data['updataurl_1'];
    $updataurl_2 = $data['updataurl_2'];
    
    // 准备传递给模板的数据
    $templateData = [
        'class' => $class,
        'data' => $data,
        'classyear' => $classyear,
        'classname' => $classname,
        'tableinfo' => $tableinfo,
        'updataurl_1' => $updataurl_1,
        'updataurl_2' => $updataurl_2,
        'yzm' => $yzm,
        'phpsessid' => $phpsessid,
        'cookie' => $cookie
    ];
    
    // 检查update.json中的时间是否在5分钟内
    $updateFile = "tabledata/{$class}/update.json";
    if (file_exists($updateFile)) {
        $updateData = json_decode(file_get_contents($updateFile), true);
        if (isset($updateData['update'])) {
            $lastUpdate = strtotime($updateData['update']);
            if (time() - $lastUpdate < 300) { // 5分钟=300秒
                $templateFile = __DIR__ . '/updata/stop.php';
                if (file_exists($templateFile)) {
                    extract($templateData);
                    include $templateFile;
                    return;
                }
            }
        }
    }

    // 根据请求方法和更新方式调用对应的模板文件
    $method = $_SERVER['REQUEST_METHOD'];
    $baseDir = ($method === 'POST') ? 'submit' : 'updata';
    
    switch ($updata) {
        case 1:
            $templateFile = __DIR__ . '/'.$baseDir.'/webvpn.php';
            break;
        case 2:
            $templateFile = __DIR__ . '/'.$baseDir.'/yaklovpn.php';
            break;
        default:
            echo "没有这样的更新方式";
            return;
    }
    
    if (file_exists($templateFile)) {
        extract($templateData);
        include $templateFile;
    } else {
        echo "模板文件不存在: {$templateFile}";
    }
}
?>