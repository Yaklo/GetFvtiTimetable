<?php
// 只接收POST请求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('只允许POST请求');
}

// 创建存储目录
$saveDir = "tabledata/{$class}";
if (!is_dir($saveDir) && !mkdir($saveDir, 0755, true)) {
    exit("无法创建目录 {$saveDir}");
}

// 校验参数是否为空
if (empty($cookie)) {
    exit('缺少cookie参数');
}

// 特殊的计数器，用于判断是否已写入特殊的更新时间
$num = 0;

// 循环获取1-20周数据
for ($page = $selectedWeek; $page <= 20; $page++) {
    // 构建请求URL
    $url = "https://jw.webvpn.fvti.cn/studentportal.php".$updataurl_1."{$page}".$updataurl_2;
    
    // 发送请求
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["cookie: " . $cookie],
        CURLOPT_SSL_VERIFYPEER => false,
        // CURLOPT_TIMEOUT => 10
    ]);
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        echo "第{$page}周请求失败，状态码：{$httpCode}\n";
        continue;
    }

    // 解析HTML，数据处理，JSON保存等逻辑
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    
    $result = [];
    // 添加更新时间
    date_default_timezone_set('Asia/Shanghai');
    $result["update"] = date('Y-m-d H:i:s');
    
    // 使用XPath查询获取表格中所有行(跳过第一行表头)
    $rows = $xpath->query('//table//tr[position()>1]');
    
    // 遍历每一行
    foreach ($rows as $row) {
        // 获取当前行中除第一列外的所有单元格
        $cells = $xpath->query('.//td[position()>1]', $row);
        // 获取第一列单元格(时间信息)
        $timeCell = $xpath->query('.//td[1]', $row)->item(0);
        
        // 如果没有时间单元格则跳过此行
        if (!$timeCell) continue;
        
        // 获取时间文本并去除前后空格
        $timeText = trim($timeCell->textContent);
        // 使用正则表达式匹配节数(如"第1节")
        preg_match('/第(\d+)节/', $timeText, $matches);
        // 获取匹配到的节数，若无则设为null
        $timeSlot = $matches[1] ?? null;
        
        // 如果没有获取到节数则跳过此行
        if (!$timeSlot) continue;
        
        // 初始化星期计数器(1-7表示周一到周日)
        $day = 0;
        // 遍历当前行的所有单元格(课程信息)
        foreach ($cells as $cell) {
            $day++;  // 星期数递增
            // 获取单元格中的div元素
            $div = $xpath->query('.//div', $cell)->item(0);
            // 如果没有div或div没有title属性则跳过
            if (!$div || !$div->hasAttribute('title')) continue;
            
            // 获取title属性值并去除前后空格
            $title = $div->getAttribute('title');
            // 按换行符分割title内容
            $lines = explode("\n", trim($title));
            
            // 如果分割后少于2行则跳过(至少需要课程名和教师)
            if (count($lines) < 2) continue;
            
            // 第一行为课程名称
            $course = trim($lines[0]);
            // 第二行为教师(如果有)
            $teacher = count($lines) > 1 ? trim($lines[1]) : '';
            // 第三行为地点(如果有)
            $location = count($lines) > 2 ? trim($lines[2]) : '';
            
            // 获取单元格的rowspan属性(合并行数)，默认为1
            $rowspan = $cell->getAttribute('rowspan') ?: 1;
            $timeRange = [];
            
            // 根据rowspan生成时间范围数组
            for ($i = 0; $i < $rowspan; $i++) {
                $timeRange[] = $timeSlot + $i;
            }
            
            // 如果该星期还没有初始化数组则初始化
            if (!isset($result[$day])) {
                $result[$day] = [];
            }
            
            // 将课程信息添加到结果数组中
            $result[$day][] = [
                $timeRange,    // 时间范围数组
                $course,      // 课程名称
                $teacher,     // 教师姓名
                $location     // 上课地点
            ];
        }
    }
    
    // 保存周数据
    $filename = sprintf('%s/%d.json', $saveDir, $page);
    file_put_contents($filename, json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    echo "第{$page}周数据已保存\n";

    // 检查 $num 是否为 0
    if ($num === 0) {
        // 创建update.json文件
        $updateFile = "{$saveDir}/update.json";
        file_put_contents($updateFile, json_encode(['update' => date('Y-m-d H:i:s')], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        // $num 加 1
        $num++;
    }

    // 等待1喵
    sleep(1);
}

echo '全部周次数据抓取完成';

echo "<div style='text-align:center; margin-top:20px;'><a href='?class={$class}' style='padding:10px 20px; background:#4CAF50; color:white; text-decoration:none; border-radius:5px;'>返回你的课程表</a></div>";
?>