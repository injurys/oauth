# QQ、新浪微博、Github网页授权登陆
### 安装
``` php
composer require injurys/oauth
``` 
### Github 授权登陆
github API地址：https://developer.github.com/
登陆流程：http://www.hug-code.cn/article/1070.shtml
``` php
// 定义配置文件
$config = [
    'client_id'=>'xxxxxxxxxxx',
    'client_secret'=>'xxxxxxxxxxxxxxxxxxxxxxx'
];

// 引入包
use injurys\third\login\GithubLogin;
use injurys\third\Exception\MessageException;

// 调用登陆方法
try{
    $oauth = new GithubLogin($config);
    $user_info = $oauth->login();
}catch (MessageException $e){
    var_dump($e->getErrorMessage());
}
```

### 新浪微博 授权登陆
新浪微博开放平台: https://open.weibo.com/
登陆流程：http://www.hug-code.cn/article/1081.shtml
``` php
// 定义配置文件
$config = [
    'app_key' => 'xxxxxxxxxxx',
    'app_secret' => 'xxxxxxxxxxxxxxxxxxx',
    'redirect_uri' => '新浪后台配置的回调地址'
];

// 引入包
use injurys\third\login\SinaLogin;
use injurys\third\Exception\MessageException;

//调用登陆方法
try{
    $oauth = new SinaLogin($config);
    $user_info = $oauth->login();
}catch (MessageException $e){
    var_dump($e->getErrorMessage());
}
```

### QQ 授权登陆
QQ 互联地址：https://connect.qq.com/index.html
登陆流程：http://www.hug-code.cn/article/1076.shtml
``` php
// 定义配置文件
$config = [
        'app_id' => 'xxxxxxxx',
        'app_key' => 'xxxxxxxxxxxxxxxxxxxxx',
        'redirect_uri' => 'QQ互联上配置的回调地址'
];


// 引入包
use injurys\third\login\QqLogin;
use injurys\third\Exception\MessageException;

// 调用登陆方法
try{
    $oauth = new QqLogin($config);
    $user_info = $oauth->login();
}catch (MessageException $e){
    var_dump($e->getErrorMessage());
}
```
