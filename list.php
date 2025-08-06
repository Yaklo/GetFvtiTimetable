<?php
function list_process($list) {
    echo "<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"UTF-8\">\n    <title>福职课表列表</title>\n</head>\n<body>";
    $tabledata_path = __DIR__ . '/tabledata';
    
    if (!is_dir($tabledata_path)) {
        echo "tabledata目录不存在";
        return;
    }
    
    $subfolders = array_filter(glob($tabledata_path . '/*'), 'is_dir');
    
    if (empty($subfolders)) {
        echo "tabledata目录下没有子文件夹";
        return;
    }
    
    // 初始化当前学期和往期课表数组
    $currentCourses = [];
    $pastCourses = [];
    $currentDate = new DateTime();
    
    foreach ($subfolders as $folder) {
        $class = basename($folder);
        $dataFile = "{$folder}/data.json";
        
        if (!file_exists($dataFile)) continue;
        
        $data = json_decode(file_get_contents($dataFile), true);
        $classyear = $data['classyear'];
        $classname = $data['classname'];
        $tableinfo = $data['tableinfo'];
        $week1time = isset($data['week1time']) ? $data['week1time'] : '';

        $updateData = json_decode(file_get_contents("{$folder}/update.json"), true);
        
        // 检查是否超过5个月
        $isPast = false;
        if (!empty($week1time)) {
            $week1Date = new DateTime($week1time);
            $interval = $currentDate->diff($week1Date);
            // 计算总月数差
            $monthsDiff = ($interval->y * 12) + $interval->m;
            if ($monthsDiff >= 5) {
                $isPast = true;
            }
        }
        
        // 构建课程信息
        $courseInfo = [
            'class' => $class,
            'classyear' => $classyear,
            'classname' => $classname,
            'tableinfo' => $tableinfo,
            'update' => $updateData['update'],
            'isPast' => $isPast
        ];
        
        // 分类存放
        if ($isPast) {
            $pastCourses[] = $courseInfo;
        } else {
            $currentCourses[] = $courseInfo;
        }
    }
    
    // 输出当前学期课表
    echo "<div style='max-width: 1400px; margin: 0 auto; padding: 25px;'>";
    echo "<h2 style='color: #333; margin-bottom: 20px;'>当前学期课表</h2>";
    if (empty($currentCourses)) {
        echo "<p>暂无当前学期课表</p>";
    } else {
        echo "<div style='display: flex; flex-wrap: wrap; gap: 25px;'>";
        foreach ($currentCourses as $course) {
            echo "<div style='border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; width: 280px; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease;'>";
            echo "<h3 style='margin: 0 0 10px 0; color: #333; font-size: 1.2em;'>{$course['classyear']}级 {$course['classname']}</h3>";
            echo "<p style='margin: 0 0 10px 0; color: #555;'>{$course['tableinfo']}({$course['class']})</p>";
            echo "<p style='font-size: 0.8em; color: #777; margin: 0 0 15px 0;'>更新时间: {$course['update']}</p>";
            echo "<div style='display: flex; gap: 12px; margin-top: 15px;'>";
            echo "<a href='?class={$course['class']}' style='padding: 8px 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 6px; transition: all 0.2s ease;'>查看课表</a>";
            echo "<a href='?class={$course['class']}&updata=2' style='padding: 8px 15px; background: #2196F3; color: white; text-decoration: none; border-radius: 6px; transition: all 0.2s ease;'>更新课表</a>";
            echo "</div></div>";
        }
        echo "</div>";
    }
    
    // 输出往期课表
    echo "<div style='margin-top: 40px;'>";
    echo "<h2 style='color: #333; margin-bottom: 20px;'>往期课表</h2>";
    if (empty($pastCourses)) {
        echo "<p>暂无往期课表</p>";
    } else {
        echo "<div style='display: flex; flex-wrap: wrap; gap: 25px;'>";
        foreach ($pastCourses as $course) {
            echo "<div style='border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; width: 280px; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease;'>";
            echo "<h3 style='margin: 0 0 10px 0; color: #333; font-size: 1.2em;'>{$course['classyear']}级 {$course['classname']}</h3>";
            echo "<p style='margin: 0 0 10px 0; color: #555;'>{$course['tableinfo']}({$course['class']})</p>";
            echo "<p style='font-size: 0.8em; color: #777; margin: 0 0 15px 0;'>更新时间: {$course['update']}</p>";
            echo "<div style='display: flex; gap: 12px; margin-top: 15px;'>";
            echo "<a href='?class={$course['class']}' style='padding: 8px 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 6px; transition: all 0.2s ease;'>查看课表</a>";
            echo "<span style='padding: 8px 15px; background: #cccccc; color: #666; text-decoration: none; border-radius: 6px; cursor: not-allowed;'>更新课表</span>";
            echo "</div></div>";
        }
        echo "</div>";
    }
    echo "</div>";
    
    // 添加分隔线
    echo "<hr style='max-width: 1400px; margin: 40px auto; border: 1px solid #e0e0e0;'>";
    
    // 底栏 - 输出Api支持、Github信息和作者信息
    echo "<div style='background: #f5f5f5; padding: 20px 0;'>";
    echo "<div style='max-width: 1400px; margin: 0 auto; padding: 0 25px;'>";
    
    // 使用flex布局实现自适应显示
    echo "<div style='display: flex; flex-wrap: wrap; gap: 20px;'>";
    
    // Api支持部分
    echo "<div style='flex: 1; min-width: 250px;'>";
    echo "<h3 style='margin-right: 10px; margin-top: 0; margin-bottom: 0;'>Api支持</h3>";
    echo "<p style='margin: 0;'>参数：(必填)class(选填)week(必要)api=json</p>";
    echo "</div>";

    // Github部分
    echo "<div style='flex: 1; min-width: 250px;'>";
    echo "<h3 style='margin-right: 10px; margin-top: 0; margin-bottom: 0;'>Github</h3>";
    echo "<p style='margin: 0;'>本项目已开源<a href='https://github.com/Yaklo/GetFvtiTimetable' style='padding: 5px 10px; background: #9C27B0; color: white; text-decoration: none; border-radius: 4px;' target='_blank'>GOGOGO</a></p>";
    echo "</div>";

    // 作者部分
    echo "<div style='flex: 1; min-width: 250px;'>";
    echo "<h3 style='margin-right: 10px; margin-top: 0; margin-bottom: 0;'>开发</h3>";
    echo "<p style='margin: 0;'>虚之亚克洛Yaklo</p>";
    echo "</div>";

    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</body>\n</html>";
}
?>