<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>提交表单</title>
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
        input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #2980b9;
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