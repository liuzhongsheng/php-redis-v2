# php-redis-v2
升级前版本：<a href='https://github.com/liuzhongsheng/php-redis-v1'>https://github.com/liuzhongsheng/php-redis-v1</a>
<h3>使用说明：</h3>
<p>本类采用php单例模式</p>
<p>使用示例：</p>
<pre>加入队列示例
    $result = require APP_PATH.'/plugin/RedisEmail/RedisEmail.class.php';
    if ($result) {
        $obj = \redisEmail::getInstance();
     }   
</pre>
<pre>发送邮件示例：
    $result = require APP_PATH.'/plugin/RedisEmail/RedisEmail.class.php';
    if ($result) {
        $obj = \redisEmail::getInstance();
        $obj -> title = '这是邮件标题';
        $obj -> subject = '这是邮件主题';
        $obj -> content = '<h1>这是邮件内容</h1>';
        $obj -> block = true; //是否开启阻塞模式：默认true 开启 false 关闭，如果需要开启可以注释该行
        $obj -> timeOut = 30; //超时时间（秒） 如果为阻塞模式时该参数生效
        $emailInfo = $obj -> popQueue('reg_email'); //从队列中获取待处理的任务
        $obj->sendEmail($emailInfo[1]);
        //后续逻辑处理
    }
</pre>

<h3>配置说明<span>(参考config.php)</span>：</h3>

<p>config.php<p>
<pre>

return [
    'start_using'       =>  'off',  //on 开 off关闭
    'host'              =>  '127.0.0.1',    //服务地址
    'port'              =>  6379,   //服务端口号
    'email_host'        =>  'smtp.163.com',//smtp服务器的名称
    'email_smtp_auth'   =>  true, //启用smtp认证
    'email_user_name'   =>  '',//发件人
    'email_from'        =>  '',//发件人地址
    'email_from_name'   =>  '',//发件人姓名
    'email_passwrod'    =>  '',//邮箱密码：此密码未客户端授权密码
    'email_charset'     =>  'utf-8',//设置邮件编码
    'email_is_html'     =>  true, // 是否HTML格式邮件
];
</pre>

<p>以上为本程序使用方式欢迎大家提提建议或者加入QQ群：456605791 交流，如果觉得代码写得还行请赞一个谢谢,欢迎提出更好的解决办法<p>
<b>url:<a href='https://www.php63.cc'>https://www.php63.cc</a></b>