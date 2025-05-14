<?php
function list_process($list) {
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
    
    echo "<div style='display: flex; flex-wrap: wrap; gap: 20px; padding: 20px;'>";
    foreach ($subfolders as $folder) {
        $class = basename($folder);
        $dataFile = "{$folder}/data.json";
        
        if (!file_exists($dataFile)) continue;
        
        $data = json_decode(file_get_contents($dataFile), true);
        $classyear = $data['classyear'];
        $classname = $data['classname'];
        $tableinfo = $data['tableinfo'];
        
        echo "<div style='border: 1px solid #ddd; border-radius: 8px; padding: 15px; width: 250px;'>";
        echo "<h3>{$classyear}级 {$classname}</h3>";
        echo "<p>{$tableinfo}({$class})</p>";
        echo "<div style='display: flex; gap: 10px; margin-top: 10px;'>";
        echo "<a href='?class={$class}' style='padding: 5px 10px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px;'>查看课表</a>";
        echo "<a href='?class={$class}&updata=2' style='padding: 5px 10px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px;'>更新课表</a>";
        echo "</div></div>";
    }
    echo "</div>";
}
?>