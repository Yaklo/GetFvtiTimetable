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
    
    $rows = $xpath->query('//table//tr[position()>1]');
    
    foreach ($rows as $row) {
        $cells = $xpath->query('.//td[position()>1]', $row);
        $timeCell = $xpath->query('.//td[1]', $row)->item(0);
        
        if (!$timeCell) continue;
        
        $timeText = trim($timeCell->textContent);
        preg_match('/第(\d+)节/', $timeText, $matches);
        $timeSlot = $matches[1] ?? null;
        
        if (!$timeSlot) continue;
        
        $day = 0;
        foreach ($cells as $cell) {
            $day++;
            $div = $xpath->query('.//div', $cell)->item(0);
            if (!$div || !$div->hasAttribute('title')) continue;
            
            $title = $div->getAttribute('title');
            $lines = explode("\n", trim($title));
            
            if (count($lines) < 2) continue;
            
            $course = trim($lines[0]);
            $teacher = count($lines) > 1 ? trim($lines[1]) : '';
            $location = count($lines) > 2 ? trim($lines[2]) : '';
            
            $rowspan = $cell->getAttribute('rowspan') ?: 1;
            $timeRange = [];
            
            for ($i = 0; $i < $rowspan; $i++) {
                $timeRange[] = $timeSlot + $i;
            }
            
            if (!isset($result[$day])) {
                $result[$day] = [];
            }
            
            $result[$day][] = [
                $timeRange,
                $course,
                $teacher,
                $location
            ];
            
            // 添加更新时间
            date_default_timezone_set('Asia/Shanghai');
            $result['update'] = date('Y-m-d H:i:s');
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