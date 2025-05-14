<?php
date_default_timezone_set('Asia/Shanghai');
$data = json_decode(file_get_contents('data.json'), true);
$updateTime = json_decode(file_get_contents('updatatime.json'), true)['update'];

$week1Monday = new DateTime($data['week1time']);
$today = new DateTime('now');
$diff = $week1Monday->diff($today);
$days = $diff->invert ? -$diff->days : $diff->days;
$selectedWeek = isset($_GET['week']) ? min(max(intval($_GET['week']), 1), 20) : 
    max(1, floor(($days) / 7) + 1);

$weekData = json_decode(file_get_contents("classdata/{$selectedWeek}.json"), true);

$weekDates = [];
$currentMonday = clone $week1Monday;
$currentMonday->modify('+' . ($selectedWeek - 1) . ' weeks');
for ($i = 0; $i < 7; $i++) {
    $date = clone $currentMonday;
    $date->modify("+{$i} days");
    $weekDates[$i + 1] = $date->format('Y-m-d');
}

$periods = [];
for ($i = 1; $i <= 12; $i++) {
    $periods[] = [
        '节数' => $i,
        '时间' => $data['classtime'][$i]
    ];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>课程表 - 第<?=$selectedWeek?>周</title>
</head>
<body>
    <h1>23软件1班 第<?=$selectedWeek?>周 课程表（2024-2025第2学期）</h1>
    <p>最后更新时间：<?=$updateTime?></p>
    <p>浏览器输入 otoo.top/fzkcb 访问课表</p>

    <div>
        <?php for ($i = 1; $i <= 20; $i++): ?>
        <a href="?week=<?=$i?>">第<?=$i?>周</a>
        <?php endfor; ?>
    </div>

    <table border="1">
        <tr>
            <th>时间</th>
            <?php foreach ($weekDates as $day => $date): ?>
            <th>星期<?=['一','二','三','四','五','六','日'][$day-1]?><br><?=$date?></th>
            <?php endforeach; ?>
        </tr>
        
        <?php 
        $rowSpans = array_fill(1, 7, 0);
        foreach ($periods as $period): 
        ?>
        <tr>
            <td>第<?=$period['节数']?>节<br><?=implode('-', $period['时间'])?></td>
            
            <?php for ($day = 1; $day <= 7; $day++): ?>
            <?php if ($rowSpans[$day] > 0): 
                $rowSpans[$day]--;
                continue;
            endif; ?>

            <?php 
                $course = null;
                $span = 1;
                foreach ($weekData[$day] ?? [] as $c) {
                    $periodsList = $c[0];
                    $startPeriod = is_array($periodsList) ? $periodsList[0] : $periodsList;
                    if ($startPeriod == $period['节数']) {
                        $endPeriod = is_array($periodsList) ? end($periodsList) : $periodsList;
                        $span = $endPeriod - $startPeriod + 1;
                        $course = $c;
                        break;
                    }
                }
                if ($course): ?>
                    <td rowspan="<?=$span?>">
                        <?=$course[1]?><br>
                        <?=$course[2]?><br>
                        <?=$course[3]?>
                    </td>
                    <?php $rowSpans[$day] = $span - 1; ?>
                <?php else: ?>
                    <td>&nbsp;</td>
                <?php endif; ?>
            <?php endfor; ?>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>