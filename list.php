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
    
    echo "<div style='display: flex; flex-wrap: wrap; gap: 25px; padding: 25px; max-width: 1400px; margin: 0 auto;'>";
    foreach ($subfolders as $folder) {
        $class = basename($folder);
        $dataFile = "{$folder}/data.json";
        
        if (!file_exists($dataFile)) continue;
        
        $data = json_decode(file_get_contents($dataFile), true);
        $classyear = $data['classyear'];
        $classname = $data['classname'];
        $tableinfo = $data['tableinfo'];

        
        $updateData = json_decode(file_get_contents("{$folder}/update.json"), true);
        
        echo "<div style='border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; width: 280px; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease;'>";
        echo "<h3 style='margin: 0 0 10px 0; color: #333; font-size: 1.2em;'>{$classyear}级 {$classname}</h3>";
        echo "<p style='margin: 0 0 10px 0; color: #555;'>{$tableinfo}({$class})</p>";
        echo "<p style='font-size: 0.8em; color: #777; margin: 0 0 15px 0;'>更新时间: {$updateData['update']}</p>";
        echo "<div style='display: flex; gap: 12px; margin-top: 15px;'>";
        echo "<a href='?class={$class}' style='padding: 8px 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 6px; transition: all 0.2s ease;'>查看课表</a>";
        echo "<a href='?class={$class}&updata=2' style='padding: 8px 15px; background: #2196F3; color: white; text-decoration: none; border-radius: 6px; transition: all 0.2s ease;'>更新课表</a>";
        echo "</div></div>";
    }
    
    
    echo "<div style='border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; width: 280px; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease;'>";
    echo "<h3>Api支持</h3>";
    echo "<p>参数：(必填)class(选填)week(必要)api=json</p>";
    echo "</div>";
    
    echo "<div style='border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; width: 280px; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease;'>";
    echo "<h3>Github</h3>";
    echo "<p>本项目已开源</p>";
    echo "<div style='display: flex; gap: 12px; margin-top: 15px;'>";
    echo "<a href='https://github.com/Yaklo/GetFvtiTimetable' style='padding: 5px 10px; background: #9C27B0; color: white; text-decoration: none; border-radius: 4px;' target='_blank'>GOGOGO</a>";
    echo "</div></div>";
    
    echo "</div>";
    echo "</body>\n</html>";
}
?>