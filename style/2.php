<?php
// 数据已由style.php统一处理并传递
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>课程表 - 第<?=$selectedWeek?>周</title>
    <!--<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@300;400;500;700&display=swap" rel="stylesheet">-->
    <link href="https://sodayo.yaklo.cn/project/fvti/css/css2-Fvti-Timetable-style2.css" rel="stylesheet">
    <style>
        :root {
            /*--primary-color: #4361ee;*/
            --primary-color: #30ACEB;
            --pprimary-color: #4361ee;
            --secondary-color: #3a0ca3;
            /*--accent-color: #f72585;*/
            --accent-color: #1385E8;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --text-color: #495057;
            --border-color: #dee2e6;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --hover-bg: #e9ecef;
            --current-day-bg: #fff8e1;
            --current-period-bg: #e3f2fd;
            --course-colors: #e9c46a, #2a9d8f, #e76f51, #264653, #f4a261, #457b9d, #1d3557;
        }

        body {
            font-family: 'Noto Sans SC', system-ui, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 25px;
        }

        .header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 8px;
            height: 100%;
            /*background: var(--primary-color);*/
            background: var(--pprimary-color);
        }

        .page-title {
            margin: 0 0 15px;
            font-size: 2.2em;
            /*color: var(--primary-color);*/
            color: var(--pprimary-color);
            font-weight: 700;
            position: relative;
            display: inline-block;
        }
        
        .page-title::after {
            content: "";
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 50%;
            height: 3px;
            /*background: var(--accent-color);*/
            background: var(--pprimary-color);
            border-radius: 3px;
        }

        .subtitle {
            font-size: 0.6em;
            margin-top: 8px;
            color: #6c757d;
            font-weight: 400;
        }

        .update-time {
            color: #868e96;
            margin: -10px 0 25px;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .update-time::before {
            content: "⏱️";
            font-size: 1.2em;
        }

        .week-buttons { 
            display: flex; 
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 25px;
        }

        .week-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 50px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95em;
            color: var(--text-color);
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            font-weight: 500;
        }

        .week-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .week-btn.active {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.4);
        }

        .week-btn#on {
            animation: pulse 2s infinite;
            background: var(--accent-color);
            color: white;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(248, 37, 133, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(248, 37, 133, 0); }
            100% { box-shadow: 0 0 0 0 rgba(248, 37, 133, 0); }
        }

        .schedule-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            position: relative;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border: 1px solid var(--border-color);
            vertical-align: middle;
        }

        th {
            background: var(--primary-color);
            color: white;
            font-weight: 500;
            font-size: 0.95em;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .time-col {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            position: sticky;
            left: 0;
            z-index: 5;
            width: 100px;
        }
        
        .time-week {
            transition: all 0.3s ease;
        }
        
        .time-week:hover {
            background: var(--secondary-color);
        }
        
        .time-title {
            font-weight: 600;
            font-size: 1em;
        }
        
        .time-info {
            font-size: 0.8em;
            opacity: 0.9;
        }

        .course-cell {
            transition: all 0.3s ease;
            vertical-align: top;
            position: relative;
            height: 80px;
            background: white;
        }
        
        .course-cell > div {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
        }

        .course-cell[rowspan] {
            background: #f8f9fa;
        }
        
        .course-cell:hover {
            transform: scale(1.02);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            z-index: 2;
        }

        .course-title {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 5px;
            font-size: 0.95em;
            line-height: 1.3;
        }

        .teacher {
            color: #6c757d;
            font-size: 0.85em;
            margin-bottom: 2px;
        }

        .room {
            color: var(--primary-color);
            font-size: 0.85em;
            font-weight: 500;
            background: rgba(67, 97, 238, 0.1);
            padding: 2px 5px;
            border-radius: 3px;
            margin-top: 3px;
        }
        
        /* 当前星期高亮 */
        .current-day {
            color: var(--primary-color);
            background-color: var(--current-period-bg);
            /*background-color: var(--current-day-bg);*/
        }
        
        /* 当前时间段高亮 */
        .current-period {
            color: var(--primary-color);
            background-color: var(--current-period-bg);
        }
        
        /* 为不同课程添加不同颜色 */
        <?php 
        $colorIndex = 0;
        $courseColors = [];
        foreach ($weekData as $dayCourses) {
            foreach ($dayCourses as $course) {
                if (!empty($course[1]) && !isset($courseColors[$course[1]])) {
                    $courseColors[$course[1]] = $colorIndex % 7;
                    $colorIndex++;
                }
            }
        }
        
        foreach ($courseColors as $courseName => $index) {
            $hash = md5($courseName);
            $r = hexdec(substr($hash, 0, 2)) % 200 + 55;
            $g = hexdec(substr($hash, 2, 2)) % 200 + 55;
            $b = hexdec(substr($hash, 4, 2)) % 200 + 55;
        ?>
        .course-<?= $hash ?> {
            background-color: rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.1);
            border-left: 3px solid rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.7);
        }
        <?php } ?>
        
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
            font-size: 0.9em;
        }
        
        .quick-nav {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .nav-btn {
            padding: 10px 20px;
            background: white;
            border-radius: 50px;
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }
        
        .nav-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .today-btn {
            background: var(--accent-color);
            color: white;
        }
        
        .today-btn:hover {
            background: #d91a6d;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .page-title {
                font-size: 1.8em;
            }
            
            th, td {
                padding: 10px;
                font-size: 0.85em;
            }
            
            .week-btn {
                padding: 8px 15px;
            }
            
            .time-col {
                width: 80px;
            }
            
            .course-cell {
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="page-title">
                <?=$classyear?>级 <?=$classname?> 第<?=$selectedWeek?>周 课程表
                <div class="subtitle"><?=$tableinfo?></div>
            </h1>
            
            <div class="update-time">
                最后更新时间：<?=$updateTime?> <?php if(!empty($otoourl)): ?>｜ 访问地址：otoo.top/<?=$otoourl?> <?php endif; ?>｜ <a href="?" class="week-btn" style="padding:3px 15px">返回主页</a>
                    <a href="?style=1<?=!empty($_GET["class"])? "&class=".$_GET["class"] : "" ?><?=!empty($_GET["week"])? "&week=".$_GET["week"] : "" ?>" class="week-btn" style="padding:3px 40px">切换样式</a>
                    <a href="?class=<?=$class?>&updata=2" class="week-btn" style="padding:3px 15px">更新课表</a>
            </div>
            
            <div class="quick-nav">
                <a href="?week=<?=max(1, $selectedWeek-1)?>" class="nav-btn">
                    <span>←</span> 上一周
                </a>
                
                <a href="?week=<?=$onWeek?>" class="nav-btn today-btn">
                    <span>📅</span> 本周课表
                </a>
                
                <a href="?week=<?=min(20, $selectedWeek+1)?>" class="nav-btn">
                    下一周 <span>→</span>
                </a>
            </div>
            
            <div class="week-buttons">
                <?php for ($i = 1; $i <= 20; $i++): ?>
                <a href="?<?=!empty($_GET["style"])? "style=".$_GET["style"]."&" : "" ?><?=!empty($_GET["class"])? "class=".$_GET["class"]."&" : "" ?>week=<?=$i?>" class="week-btn <?=$i==$selectedWeek?'active':''?>" <?=$i==$onWeek?'id="on"':''?>>
                    第<?=$i?>周
                </a>
                <?php endfor; ?>
            </div>
        </div>

        <div class="schedule-container">
            <table>
                <thead>
                    <tr>
                        <th class="time-col">时间/星期</th>
                        <?php foreach ($weekDates as $day => $date): ?>
                        <th class="time-week <?=($isCurrentWeek && $day == $currentDayOfWeek)?'current-day':''?>">
                            <span class="time-title">星期<?=['一','二','三','四','五','六','日'][$day-1]?></span><br>
                            <span class="time-info"><?=$date?></span>
                        </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rowSpans = array_fill(1, 7, 0);
                    foreach ($periods as $period): 
                        $isCurrentPeriod = false;
                        if ($isCurrentWeek && $selectedWeek == $onWeek) {
                            $currentHour = $today->format('G');
                            $currentMinute = $today->format('i');
                            $periodTime = $period['时间'];
                            $startTime = explode(':', $periodTime[0]);
                            $endTime = explode(':', $periodTime[1]);
                            
                            if (($currentHour > $startTime[0] || ($currentHour == $startTime[0] && $currentMinute >= $startTime[1])) &&
                                ($currentHour < $endTime[0] || ($currentHour == $endTime[0] && $currentMinute <= $endTime[1]))) {
                                $isCurrentPeriod = true;
                            }
                        }
                    ?>
                    <tr>
                        <td class="time-col <?=$isCurrentPeriod?'current-period':''?>">
                            <span class="time-title">第<?=$period['节数']?>节</span><br>
                            <span class="time-info"><?=implode('-', $period['时间'])?></span>
                        </td>
                        
                        <?php for ($day = 1; $day <= 7; $day++): ?>
                        <?php if ($rowSpans[$day] > 0): 
                            $rowSpans[$day]--;
                            continue;
                        endif; ?>

                        <?php 
                            $course = null;
                            $span = 1;
                            // 处理连续课程
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
                                <td class="course-cell course-<?= md5($course[1]) ?> <?=($isCurrentWeek && $day == $currentDayOfWeek)?'current-day':''?> <?=($isCurrentPeriod && $day == $currentDayOfWeek)?'current-period':''?>" 
                                    rowspan="<?=$span?>">
                                    <div>
                                        <div class="course-title"><?=$course[1]?></div>
                                        <div class="teacher"><?=$course[2]?></div>
                                        <div class="room"><?=$course[3]?></div>
                                    </div>
                                </td>
                                <?php $rowSpans[$day] = $span - 1; ?>
                            <?php else: ?>
                                <td class="course-cell <?=($isCurrentWeek && $day == $currentDayOfWeek)?'current-day':''?> <?=($isCurrentPeriod && $day == $currentDayOfWeek)?'current-period':''?>">
                                    &nbsp;
                                </td>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="footer">
            🚀 数据手动更新 | 如有问题请联系管理员 | © 2025 YakloProject
        </div>
    </div>
    
    <script>
        // 添加简单的动画效果
        document.addEventListener('DOMContentLoaded', function() {
            // 表格行入场动画
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                setTimeout(() => {
                    row.style.opacity = '1';
                }, index * 50);
            });
            
            // 课程单元格点击效果
            const courseCells = document.querySelectorAll('.course-cell');
            courseCells.forEach(cell => {
                cell.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 200);
                });
            });
        });
    </script>
</body>
</html>