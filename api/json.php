<?php
function json_process($class, $week) {
    if(empty($week)) {
        // 读取基础数据
        $data = json_decode(file_get_contents("tabledata/{$class}/data.json"), true);

        // 计算当前周数（修正版）
        $week1Monday = new DateTime($data['week1time']);
        $today = new DateTime('now');
        $diff = $week1Monday->diff($today);
        $days = $diff->invert ? -$diff->days : $diff->days;
        $nowWeek = floor(($days) / 7) + 1;
        $onWeek = min(max(1, $nowWeek), 20);
        $selectedWeek = isset($week) ? min(max(intval($_GET['week']), 1), 20) : $onWeek;
    } else {
        $selectedWeek = $week;
    }

    // 加载课程数据
    $json_data = file_get_contents("tabledata/{$class}/{$selectedWeek}.json");

    header('Content-Type: application/json');
    echo $json_data;
}
?>