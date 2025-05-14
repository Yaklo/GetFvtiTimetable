<?php
// 获取验证码和PHPSESSID
function getCaptchaData() {
    try {
        $ch = curl_init('https://jw.vf.yaklo.cn/studentportal.php/Public/verify/');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new Exception('验证码获取失败: ' . curl_error($ch));
        }
        
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $header_size);
        $image = substr($response, $header_size);
        
        // 获取PHPSESSID
        preg_match('/PHPSESSID=([^;]+)/', $headers, $matches);
        $phpSessId = $matches[1] ?? '';
        
        return [
            'image' => base64_encode($image),
            'phpsessid' => $phpSessId,
            'error' => ''
        ];
    } catch (Exception $e) {
        return [
            'image' => '',
            'phpsessid' => '',
            'error' => '无法连接到登录接口：' . $e->getMessage()
        ];
    } finally {
        if (isset($ch)) curl_close($ch);
    }
}

$captchaData = getCaptchaData();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>福职课表刷新 - 3班</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 450px;
            text-align: center;
        }
        #error-msg {
            color: #e74c3c;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        #captcha-img {
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button, input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        button:hover, input[type="submit"]:hover {
            background-color: #2980b9;
        }
        .refresh-btn {
            margin-left: 1rem;
        }
    </style>
    <script>
        function refreshCaptcha() {
            window.location.reload();
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>福职课表刷新 - <?=$classyear?><?=$classname?></h2>
        <div id="error-msg"><?php echo $captchaData['error']; ?></div>
        <form action="" method="post">
                <input type="hidden" name="class" value="<?php echo $class; ?>">
            <div class="form-group">
                <img id="captcha-img" src="data:image/jpeg;base64,<?php echo $captchaData['image']; ?>" alt="验证码">
                <button type="button" class="refresh-btn" onclick="refreshCaptcha()">刷新验证码</button>
            </div>
            <div class="form-group">
                <label for="captcha-input">验证码：</label>
                <input type="text" id="captcha-input" name="yzm" required>
                <input type="hidden" id="phpsessid" name="phpsessid" value="<?php echo $captchaData['phpsessid']; ?>">
            </div>
            <input type="submit" id="submit-btn" value="提交">
        </form>
    </div>
</body>
</html>