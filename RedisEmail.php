<?php
$result = require  dirname(__FILE__).'/RedisEmail.class.php';
if ($result) {
    $obj = \redisEmail::getInstance();
//    $param = [
//        'key' => 'reg_email', //redis里的key
//        'value' => [
//            '996674366@qq.com', //收件人
//            '1055489612@qq.com',
//            '1332980565@qq.com',
//        ]
//    ];
//    $obj ->joinQueue($param);
//    echo $obj -> getCount('reg_email');

//    $obj -> debug = true; //开启debug模式：true 是 false 否
    $obj -> title = '这是邮件标题';
    $obj -> subject = '这是邮件主题';
    $obj -> content = '这是邮件内容';
//    $obj -> block = true; //是否开启阻塞模式：默认true 开启 false 关闭，如果需要开启可以注释该行
    $obj -> timeOut = 30; //超时时间（秒） 如果为阻塞模式时该参数生效
    $emailInfo = $obj -> popQueue('reg_email'); //从队列中获取待处理的任务
    if ($emailInfo){
        $res = $obj->sendEmail($emailInfo[1]);
        if (!$res) {
            //后续逻辑处理
            file_put_contents('send_log.txt',$emailInfo[1].':发送失败'.PHP_EOL,FILE_APPEND);
        }
    }else{
        file_put_contents('send_log_s.txt','无邮件'.PHP_EOL,FILE_APPEND);
    }

}