<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>提交表单</title>
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
        
        input[type="submit"] {
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
        
        input[type="submit"]:hover {
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
</head>
<body>
    <div class="container">
        <h2>福职课表刷新 - <?=$classyear?><?=$classname?></h2>
        <form action="" method="post">
            <input type="hidden" name="class" value="<?php echo $class; ?>">
            <div class="form-group">
                <div>为避免请求过快更新一次需要至少20秒</div>
                <div>本接口用法比较繁琐，不了解需要提交的参数时请勿随意提交</div>
                <label for="data">WebVPN Cookie：</label>
                <input type="text" id="data" name="cookie" required>
            </div>
            <input type="submit" value="提交">
        </form>
    </div>
</body>
</html>