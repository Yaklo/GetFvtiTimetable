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
        
        .message {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: var(--text-color);
            line-height: 1.6;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>福职课表刷新 - <?=$classyear?><?=$classname?></h2>
        <div class="message">这个班级5分钟内已刷新过，休息一下~</div>
    </div>
</body>
</html>