# 福州职业技术学院教务系统课程表获取与显示
请注意，无论是新旧版本的submit.php(yaklovpn.php)，都无法直接使用，需要可以直连校园网的环境，或者跳板，未来会加以备注，现在先摆烂了。

submit_old.php(webvpn.php)所对应updata的php页面需要提交的参数为：
登录到教务系统后所获取的cookie，类似于这样的
```
user_device_id=123; user_device_id_timestamp=123; _webvpn_key=123; webvpn_username=123; PHPSESSID=123
```