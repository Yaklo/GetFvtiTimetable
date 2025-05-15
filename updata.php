<?php
function updata_process($class, $updata, $upmode, $yzm, $phpsessid, $cookie) {
    
    $data = json_decode(file_get_contents("tabledata/{$class}/data.json"), true);
    $classyear = $data['classyear'];
    $classname = $data['classname'];
    $tableinfo = $data['tableinfo'];
    $updataurl_1 = $data['updataurl_1'];
    $updataurl_2 = $data['updataurl_2'];
    
    $selectedWeek = 1;
    if ($upmode === "1") {
        // 计算当前周数（修正版）
        $week1Monday = new DateTime($data['week1time']);
        $today = new DateTime('now');
        $diff = $week1Monday->diff($today);
        $days = $diff->invert ? -$diff->days : $diff->days;
        $nowWeek = floor(($days) / 7) + 1;
        $onWeek = min(max(1, $nowWeek), 20);
        $selectedWeek = isset($week) ? min(max(intval($_GET['week']), 1), 20) : $onWeek;
    }

    $stopinfo = "哎呀出问题了，怎么没有阻止你更新的提示信息~";
    
    // 检查update.json中的时间是否在5分钟内
    $updateData = json_decode(file_get_contents("tabledata/{$class}/update.json"), true);
    if ($nowWeek > 20) {
        $updata = -1;
        $stopinfo = "这个学期已经结束了呢，假期快乐~";
    } elseif (isset($updateData['update'])) {
        $lastUpdate = strtotime($updateData['update']);
        if (time() - $lastUpdate < 300) { // 5分钟=300秒
            $updata = -1;
            $stopinfo = "这个班级5分钟内已刷新过，休息一下~";
        }
    } 

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
        'cookie' => $cookie,
        'selectedWeek' => $selectedWeek,
        'stopinfo' => $stopinfo
    ];

    // 根据请求方法和更新方式调用对应的模板文件
    $method = $_SERVER['REQUEST_METHOD'];
    $baseDir = ($method === 'POST') ? 'submit' : 'updata';
    
    switch ($updata) {
        case -1:
            $templateFile = __DIR__. '/updata/stop.php';
            break;
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