# php-redis-v2
升级前版本：<a href='https://github.com/liuzhongsheng/php-redis-v1'>https://github.com/liuzhongsheng/php-redis-v1</a>
<h3>使用说明：</h3>
<p>本类采用php单例模式</p>
<p>使用示例：</p>
<pre>加入队列示例
    $result = require APP_PATH.'/plugin/RedisEmail/RedisEmail.class.php';
    if ($result) {
        $obj = \redisEmail::getInstance();
        $obj ->joinQueue($param);
     }   
</pre>

<pre>
参数示例:
[
    'key' => 'reg_email', //redis里的key
    'value' => [
        '996674366@qq.com', //收件人
        'liuzhongsheng@xxx.cn'//收件人
    ]
]
</pre>
<pre>
查询队列里指定key的总数
$obj -> getCount('reg_email');
</pre>
<pre>发送邮件示例：
    $result = require APP_PATH.'/plugin/RedisEmail/RedisEmail.class.php';
    if ($result) {
        $obj = \redisEmail::getInstance();
        $obj -> debug = true; //开启debug模式：true 是 false 否
        $obj -> title = '这是邮件标题';
        $obj -> subject = '这是邮件主题';
        $obj -> content = '这是邮件内容';
        $obj -> block = true; //是否开启阻塞模式：默认true 开启 false 关闭，如果需要开启可以注释该行
        $obj -> timeOut = 30; //超时时间（秒） 如果为阻塞模式时该参数生效
        $obj -> attachment = [
            '附件1',
            '附件2',
            .....
        ];
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

服务端运行(linux)
<pre>
    nohup  /root/shell/xxx.sh >> /root/shell/xxx.sh.log  2>&1 &
注意事项：
    1.shell 需要给予权限 chmod 755 ./xxx.sh
    2.执行时带上路径
    3.需要将shell里的prefix='/root/shell' 替换为你自己的路径
    4.默认时间为一秒，可以通过second修改
</pre>
更新日志
<br>
<pre>
    2017-12-06 新增debug,附件模式,执行shell，查询队列总数 代码结构优化
</pre>
<p>以上为本程序使用方式欢迎大家提提建议或者加入QQ群：456605791 交流，如果觉得代码写得还行请赞一个谢谢,欢迎提出更好的解决办法<p>
<b>url:<a href='https://www.php63.cc'>https://www.php63.cc</a></b>