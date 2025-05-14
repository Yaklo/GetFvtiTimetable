<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>提交表单</title>
</head>
<body>
    <form action="submit_old.php" method="post">
        <div>
            <div>为避免请求过快更新一次需要至少20秒</div>
            <label for="data">输入内容：</label>
            <input type="text" id="data" name="cookie" required>
        </div>
        <div>
            <input type="submit" value="提交">
        </div>
    </form>
</body>
</html>