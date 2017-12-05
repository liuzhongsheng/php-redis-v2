<?php
// +----------------------------------------------------------------------
// | redis邮件发送类v2.0
// +----------------------------------------------------------------------
// | Copyright (c) www.php63.cc All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 吹泡泡的鱼 <996674366@qq.com>
// +---------

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$filePath = dirname(__FILE__);
require $filePath.'/PHPMailer/Exception.php';
require $filePath.'/PHPMailer/PHPMailer.php';
require $filePath.'/PHPMailer/SMTP.php';

class RedisEmail
{

    /**
     * 设置发送邮件标题
     * @var string 邮件标题
     */
    public $title = '未命名邮件';

    /**
     * 设置发送邮件主题
     * @var string 邮件主题
     */
    public $subject = '未命名邮件主题';

    /**
     * 要发送的邮件内容
     * 通常根据情况传递不同内容
     * @var string 邮件内容
     */
    public $content = '';

    /**
     * 是否启用阻塞模式，如果使用cli执行，建议开启阻塞模式
     * @var bool 是否使用阻塞模式，true是 false 否
     */
    public $block = true;

    /**
     * 当启用阻塞模式时该方法生效
     * @var int 超时时间，单位秒
     */
    public $timeOut = 100;

    /**
     * 为了避免类被重复实例化，第一次实例化后将会把实例化后的结果存入该方法
     * @var
     */
    private static $instance;


    /**
     * @var 配置项
     */
    private $config;
    private $redis;

    //初始化化类，防止被实例化
    private function __construct()
    {
        $this->redis = $this->connect();
    }

    //防止类被克隆
    private function __clone()
    {
        trigger_error('Clone is not allow!', E_USER_ERROR);
    }

    /**
     * 防止类重复实例化
     * 检测当前类是否已经实例化过，如果实例化过直接返回
     * @return redisEmail 返回实例化过后的对象
     */
    public static function getInstance()
    {
        //检测当前类是否实例化过
        if (!(self::$instance instanceof self)) {
            //如果当前类没有实例化过则实例化当前类
            self::$instance = new self;
        }
        return self::$instance;
    }

    //连接redis
    private function connect()
    {
        try {
            //引入配置文件
            $this->config = include 'config.php';
            $redis = new \Redis();
            $redis->pconnect($this->config['host'], $this->config['port']);
            return $redis;
        } catch (RedisException $e) {
            echo 'phpRedis扩展没有安装：' . $e->getMessage();
            exit;
        }
    }

    /**
     * 加入队列
     * 参数以数组方式传递，key为键名，value为要写入的值，value，如果需要写入多个则以数组方式传递
     * @param array 要加入队列的格式 ['key'=>'键名','value'=>[值]]
     * @return int 成功返回 1失败 返回0
     */
    public function joinQueue($param = [])
    {
        //如果value不存在或者不是一个数组则写入一次
        if ((array)$param['value'] !== $param['value']) {
            return $this->redis->lpush($param['key'], $param['value']);
        }
        //如果是一个数组则循环写入
        foreach ($param['value'] as $value) {
            $this->redis->lpush($param['key'], $value);
        }
    }

    /**
     * @param $key
     * @return array|string
     */
    public function popQueue($key)
    {
        if ($this->block) {
            return $this->redis->brpop($key, $this->timeOut);
        } else {
            return $this->redis->rpop($key);
        }
    }

    /**
     * 邮件发送方法
     * 传入要处理的数组，包含内容如下：
     * @email 邮件发送地址
     * @return bool
     */
    public function sendEmail($email)
    {
        $mail = new PHPMailer();
        try {
            $mail->isSMTP();                                      // 设置邮件发送模式
            $mail->Host     = $this->config['email_host'];       // 服务地址
            $mail->SMTPAuth = $this->config['email_smtp_auth'];  // Enable SMTP authentication
            $mail->Username = $this->config['email_user_name'];  // SMTP 账号名称
            $mail->Password = $this->config['email_passwrod'];   // SMTP 密码
            $mail->CharSet  = $this->config['email_charset']; //设置编码

            //收件人设置
            $mail->setFrom($this->config['email_from'], $this->config['email_from_name']); //发件人
            $mail->addAddress($email, $this->title);     // 收件人

            //邮件内容设置
            $mail->isHTML($this->config['email_is_html']); // 是否HTML格式邮件                                 // Set email format to HTML
            $mail->Subject = $this->subject; //邮件主题
            $mail->Body    = $this->content; //带html标签的邮件内容
            $mail->AltBody = $this->content; //不带html标签的内容
            return $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo '发送失败: ' . $mail->ErrorInfo;
        }
    }
}