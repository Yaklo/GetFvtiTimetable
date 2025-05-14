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
    
    echo "<h2>tabledata子文件夹列表:</h2><ul>";
    foreach ($subfolders as $folder) {
        $folder_name = basename($folder);
        echo "<li>{$folder_name}</li>";
    }
    echo "</ul>";
}
?>