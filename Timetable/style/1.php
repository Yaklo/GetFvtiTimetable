<?php
// 数据已由style.php统一处理并传递
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>课程表 - 第<?=$selectedWeek?>周</title>
    <style>
        /* 定义全局变量，用于控制主题颜色和文本颜色 */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #007bff;
            --text-color: #495057;
            --hover-bg: #e9f3ff;
        }

        /* 设置body的基本样式，包括字体、背景色、文本颜色等 */
        body { 
            font-family: 'Segoe UI', system-ui, sans-serif; 
            background: #f8f9fa; 
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
        }
        
        /* 定义内容容器的样式，确保内容居中且具有良好的视觉效果 */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 25px;
            /*background: white;*/
            /*border-radius: 12px;*/
            /*box-shadow: 0 4px 6px rgba(0,0,0,0.05);*/
        }

        /* 设置页面标题的样式，使其突出显示 */
        .page-title {
            text-align: center;
            margin: 0 0 15px;
            font-size: 2.2em;
            color: var(--primary-color);
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* 设置更新时间的样式，用于显示页面的最后更新时间 */
        .update-time {
            text-align: center;
            color: #868e96;
            margin: -10px 0 25px;
            font-size: 0.9em;
        }

        /* 为周次按钮设置样式，使其灵活布局并具有一定的交互性 */
        .week-buttons { 
            display: flex; 
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 25px;
            justify-content: center;
        }

        /* 定义单个周次按钮的样式，包括悬浮和激活状态的样式 */
        .week-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            background: #e9ecef;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.95em;
            color: var(--text-color);
            text-decoration: none;
            display: inline-block;
        }

        /* 周次按钮悬浮状态的样式 */
        .week-btn:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,123,255,0.2);
        }

        /* 周次按钮激活状态的样式 */
        .week-btn.active {
            background: var(--primary-color);
            color: white;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(44,62,80,0.2);
        }

        /* 周次按钮属于当周状态的样式 */
        .week-btn#on {
            box-shadow: 0 0px 0px 5px rgb(102,204,255,0.5);
        }

        /* 设置表格的基本样式，使其具有现代感和易读性 */
        table {
            border-collapse: collapse;
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            table-layout: fixed;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        /* 设置表头和单元格的基本样式 */
        th, td {
            padding: 15px;
            text-align: center;
            border: 2px solid #c6cdd4;
            width: 12.5%; /* 8列等宽 */
            vertical-align: middle;
        }

        /* 设置表头的特定样式，包括背景色、字体颜色和粘滞效果 */
        th {
            background: var(--primary-color);
            color: white;
            font-weight: 500;
            font-size: 0.95em;
            letter-spacing: 0.5px;
            position: sticky;
            /*top: 0;*/
            z-index: 2;
        }

        /* 设置时间列的样式，使其具有粘滞效果并突出显示 */
        .time-col {
            background: #f1f3f5;
            font-weight: 500;
            position: sticky;
            left: 0;
            z-index: 1;
            line-height: 1.25;
        }
        /* 设置星期行的样式 */
        .time-week {
            line-height: 1.25;
        }
        
        /* 设置时间列标题的样式，例如：星期一、第1节 */
        .time-title {
            letter-spacing: 3px;
            font-size: 1em;
            font-weight: 900;
        }
        
        /* 设置时间列信息的样式，例如：2025-03-24、08:30-09:15 */
        .time-info {
            font-size: 0.9em;
            font-weight: 500;
        }

        /* 设置课程单元格的样式，包括背景色和交互效果 */
        .course-cell {
            background: #f8f9fa;
            transition: background 0.2s;
            vertical-align: top;
            position: relative;
            height: 48px;
        }

        /* 课程单元格中的内容样式，垂直和水平居中 */
        .course-cell > div {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
        }

        /* 设置有rowspan属性的课程单元格的样式 */
        .course-cell[rowspan] {
            background: #f0f6ff;
        }

        /* 课程单元格悬浮状态的样式 */
        .course-cell:hover {
            background: var(--hover-bg);
        }

        /* 设置课程标题的样式，使其突出显示 */
        .course-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 5px;
            font-size: 0.95em;
            line-height: 1.3;
        }

        /* 设置教师名称的样式 */
        .teacher {
            color: #6c757d;
            font-size: 0.85em;
            margin-bottom: 2px;
        }

        /* 设置房间名称的样式 */
        .room {
            color: var(--secondary-color);
            font-size: 0.85em;
            line-height: 1.2;
        }

        /* 响应式设计，当屏幕宽度小于768px时应用以下样式 */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
                margin: 10px;
            }
            
            .page-title {
                font-size: 1.8em;
            }
            
            th, td {
                padding: 12px;
                font-size: 0.85em;
            }
            
            .week-btn {
                padding: 8px 12px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="page-title">
            <?=$classyear?>级 <?=$classname?> 第<?=$selectedWeek?>周 课程表
            <div style="font-size:0.6em; margin-top:8px; color:#868e96">
                （<?=$tableinfo?>）
            </div>
        </h1>
        
        <div class="update-time">
            最后更新时间：<?=$updateTime?>
            <?php if(!empty($otoourl)): ?>
            <br>
            浏览器输入 otoo.top/<?=$otoourl?> 访问课表
            <?php endif; ?>
            <br>
            <a href="?style=2<?=!empty($_GET["class"])? "&class=".$_GET["class"] : "" ?><?=!empty($_GET["week"])? "&week=".$_GET["week"] : "" ?>" class="week-btn" style="padding:3px 40px">切换样式</a>
            <a href="?style=0<?=!empty($_GET["class"])? "&class=".$_GET["class"] : "" ?><?=!empty($_GET["week"])? "&week=".$_GET["week"] : "" ?>" class="week-btn" style="padding:3px 15px">去除按钮</a>
        </div>

        <div class="week-buttons">
            <?php for ($i = 1; $i <= 20; $i++): ?>
            <a href="?<?=!empty($_GET["style"])? "style=".$_GET["style"]."&" : "" ?><?=!empty($_GET["class"])? "class=".$_GET["class"]."&" : "" ?>week=<?=$i?>" class="week-btn <?=$i==$selectedWeek?'active':''?>" <?=$i==$onWeek?'id="on"':''?>>
                第<?=$i?>周
            </a>
            <?php endfor; ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="time-col" onclick="window.location.href='?style=2'"></th>
                    <?php foreach ($weekDates as $day => $date): ?>
                    <th class="time-week"><span class="time-title">星期<?=['一','二','三','四','五','六','日'][$day-1]?></span><br><span class="time-info"><?=$date?></span></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rowSpans = array_fill(1, 7, 0);
                foreach ($periods as $period): 
                ?>
                <tr>
                    <td class="time-col">
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
                            <td class="course-cell" rowspan="<?=$span?>">
                                <div>
                                    <div class="course-title"><?=$course[1]?></div>
                                    <div class="teacher"><?=$course[2]?></div>
                                    <div class="room"><?=$course[3]?></div>
                                </div>
                            </td>
                            <?php $rowSpans[$day] = $span - 1; ?>
                        <?php else: ?>
                            <td class="course-cell">&nbsp;</td>
                        <?php endif; ?>
                    <?php endfor; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>