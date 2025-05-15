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
        :root {
            --primary-color: #3498db;
            --primary-hover: #2980b9;
            --error-color: #e74c3c;
            --bg-color: #f8f9fa;
            --text-color: #333;
            --border-color: #dee2e6;
            --container-bg: #fff;
            --shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            background: var(--container-bg);
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        
        h2 {
            margin-bottom: 1.5rem;
            color: var(--primary-color);
        }
        
        #error-msg {
            color: var(--error-color);
            margin-bottom: 1.5rem;
            font-size: 1rem;
            padding: 0.5rem;
            border-radius: 4px;
            background-color: rgba(231, 76, 60, 0.1);
        }
        
        #captcha-img {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            margin-bottom: 1.5rem;
            max-width: 100%;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--text-color);
        }
        
        input[type="text"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        button, input[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
        }
        
        button:hover, input[type="submit"]:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }
        
        .refresh-btn {
            margin-left: 1rem;
            width: auto;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }
        
        .message {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: var(--text-color);
            line-height: 1.6;
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
        <?php if(empty($captchaData['error'])): ?>
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
        <?php else: ?>
            <div class="form-group">
                <input type="text" id="captcha-input" name="yzm" disabled>
                <input type="submit" id="submit-btn" value="提交" disabled>
            </div>
            <a href="?updata=1&class=<?php echo $class; ?>" class="btn">检测到校园网节点离线，点我使用备用接口</a>
        <?php endif; ?>
    </div>
</body>
</html>