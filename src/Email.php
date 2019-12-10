<?php
/**
 * 邮箱配置pop  smtp
 *  smtp.qq.com
 *  smtp.163.com
 *  smtp.gmail.com
 * */
namespace tools;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
class Email{

    protected static $transport;

    protected static $host;

    protected static $username;

    protected static $password;

    /**
     * 实例化配置
     * Email constructor.
     * @param $config
     */
    public function __construct($config)
    {
        if(isset($config['host']) && !empty($config['host']))
            self::$host = $config['host'];
        else
            return ['code' => 400 ,'msg' => 'host为空'];

        if(isset($config['username']) && !empty($config['username']))
            self::$username = $config['username'];
        else
            return ['code' => 400 ,'msg' => 'username为空'];

        if(isset($config['password']) && !empty($config['password']))
            self::$password = $config['password'];
        else
            return ['code' => 400 ,'msg' => 'password为空'];

    }

    /**
     * 发送消息
     * @param string    $msgBody
     * @param array     $source
     * @param array     $target
     * @param string    $subject
     * @return bool|string
     */
    public static function sendMsg($msgBody,$source,$target,$subject)
    {
        try{

            $transport = (new Swift_SmtpTransport('smtp.exmail.qq.com',25))
                ->setUsername('report@skinrun.me')
                ->setPassword('rEmail_123');

            $mailer = (new Swift_Mailer($transport));

            // 设置邮件内容,可以省略content-type
            $message = (new Swift_Message($subject))
                ->setFrom(['report@skinrun.me' => 'skinrun'])
                ->setTo(['18838952961@163.com'])
                ->setBody($msgBody);
            // Send the message
            $result = $mailer->send($message);
            if($result){
                return true;
            }else{
                return false;
            }
        }catch (\Exception $error){
            return $error->getMessage();
        }
    }

}