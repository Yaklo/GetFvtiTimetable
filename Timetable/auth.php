<?php
function auth_check($class) {
    $tabledata_path = __DIR__ . '/tabledata/' . $class;
    return is_dir($tabledata_path);
}
?>