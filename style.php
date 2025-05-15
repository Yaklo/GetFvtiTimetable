<?php
date_default_timezone_set('Asia/Shanghai');

function load_timetable_data($class, $week) {
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

    // 加载课程数据
    $weekData = json_decode(file_get_contents("tabledata/{$class}/{$selectedWeek}.json"), true);

    // 从每周数据文件中获取updateTime
    $updateTime = $weekData['update'];

    $classyear = $data['classyear'];
    $classname = $data['classname'];
    $tableinfo = $data['tableinfo'];
    $otoourl = $data['otoourl'];

    // 生成周日期数据
    $weekDates = [];
    $currentMonday = clone $week1Monday;
    $currentMonday->modify('+' . ($selectedWeek - 1) . ' weeks');
    for ($i = 0; $i < 7; $i++) {
        $date = clone $currentMonday;
        $date->modify("+{$i} days");
        $weekDates[$i + 1] = $date->format('Y-m-d');
    }

    // 课程时间段配置
    $periods = [];
    for ($i = 1; $i <= 12; $i++) {
        $periods[] = [
            '节数' => $i,
            '时间' => $data['classtime'][$i]
        ];
    }

    // 获取当前是星期几
    $currentDayOfWeek = $today->format('N'); // 1-7 (星期一-星期日)
    $isCurrentWeek = ($selectedWeek == $onWeek);

    return [
        'data' => $data,
        'updateTime' => $updateTime,
        'today' => $today,
        'selectedWeek' => $selectedWeek,
        'onWeek' => $onWeek,
        'weekData' => $weekData,
        'weekDates' => $weekDates,
        'periods' => $periods,
        'currentDayOfWeek' => $currentDayOfWeek,
        'isCurrentWeek' => $isCurrentWeek,
        'classyear' => $classyear,
        'classname' => $classname,
        'tableinfo' => $tableinfo,
        'otoourl' => $otoourl,
    ];
}

function style_process($class, $style, $week = null) {
    $timetableData = load_timetable_data($class, $week);
    
    // 根据样式参数调用对应的模板文件
    $styleFile = __DIR__ . "/style/{$style}.php";
    if (file_exists($styleFile)) {
        extract($timetableData);
        include $styleFile;
    } else {
        echo "样式文件不存在: {$styleFile}";
    }
}
?>