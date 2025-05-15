<?php
// 只接收POST请求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('只允许POST请求');
}

// 创建存储目录
$saveDir = 'classdata';
if (!is_dir($saveDir) && !mkdir($saveDir, 0755, true)) {
    exit("无法创建目录 {$saveDir}");
}

// 从POST获取验证码和PHPSESSID
$yzm = $_POST["yzm"] ?? '';
$phpsessid = $_POST["phpsessid"] ?? '';
if (empty($yzm) || empty($phpsessid)) {
    exit('缺少验证码或会话ID');
}

// 登录请求
$loginUrl = 'https://jw.vf.yaklo.cn/studentportal.php/Index/checkLogin';
$loginData = [
    'logintype' => 'xsxh',
    'xsxh' => '账号',
    'dlmm' => '密码',
    'yzm' => $yzm
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $loginUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($loginData),
    CURLOPT_HTTPHEADER => ["cookie: PHPSESSID={$phpsessid}"],
    CURLOPT_SSL_VERIFYPEER => false
]);
$loginResult = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    exit('登录请求失败');
}

$loginData = json_decode($loginResult, true);
if ($loginData['status'] !== 1) {
    exit('登录失败: ' . $loginData['info']);
}

// 使用PHPSESSID作为cookie
$cookie = "PHPSESSID={$phpsessid}";

// 循环获取1-20周数据
for ($page = 1; $page <= 20; $page++) {
    // 构建请求URL
    $url = "https://jw.vf.yaklo.cn/studentportal.php/Jxxx/xskbxx/optype/2/xn/2024-2025/xq/2/dqz/{$page}/sybmdmstr/9625,9605,9789,9583,9569,10100,10202,10189,10185,10212,11257,2023120463031/bjmc/23%E8%BD%AF%E4%BB%B61%E7%8F%AD";
    
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
            
            if (count($lines) < 3) continue;
            
            $course = trim($lines[0]);
            $teacher = trim($lines[1]);
            $location = trim($lines[2]);
            
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
        }
    }
    
    // 保存周数据
    $filename = sprintf('%s/%d.json', $saveDir, $page);
    file_put_contents($filename, json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    echo "第{$page}周数据已保存\n";
    
    // 等待1喵
    // sleep(1);
}

echo '全部周次数据抓取完成';

// 登出
$logoutUrl = 'https://jw.vf.yaklo.cn/studentportal.php/Main/logout';
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $logoutUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["cookie: PHPSESSID={$phpsessid}"],
    CURLOPT_SSL_VERIFYPEER => false
]);
curl_exec($ch);
curl_close($ch);

// 设置时区（根据实际情况调整）
date_default_timezone_set('Asia/Shanghai');

// 获取当前时间
$currentTime = date('Y-m-d H:i:s');

// 创建数据数组
$data = [
    'update' => $currentTime
];

// 转换为JSON格式
$jsonData = json_encode($data, JSON_UNESCAPED_SLASHES);

// 保存到文件
if (file_put_contents('updatatime.json', $jsonData) !== false) {
    echo "时间数据已成功保存到 data.json";
} else {
    echo "时间数据保存文件时发生错误";
}
?>