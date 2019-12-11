<?php
/**
 * 邮箱配置pop  smtp
 *  smtp.qq.com
 *  smtp.163.com
 *  smtp.gmail.com
 *  smtp.exmail.qq.com 腾讯企业邮箱
 * */
namespace tools;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
class Email{

    /**
     * 实例化配置
     * Email constructor.
     * @param $config
     */
    public function __construct()
    {


    }

    /**
     * 发送邮件
     * @param $config
     * @return bool|string
     */
    public static function sendMsg($config)
    {
        try{
            $validate = self::validateData($config);
            if (is_array($validate)){
                return json_encode($validate);
            }else{
                $transport = (new Swift_SmtpTransport($config['host'],$config['port']))
                    ->setUsername($config['sendUse'])
                    ->setPassword($config['sendPwd']);

                $mailer = (new Swift_Mailer($transport));

                // 设置邮件内容,可以省略content-type
                $message = (new Swift_Message($config['subject']))
                    ->setFrom($config['fromUser'])
                    ->setTo($config['toUser'])
                    ->setBody($config['msgBody']);
                // Send the message
                $result = $mailer->send($message);
                if($result){
                    return $result;
                }else{
                    return false;
                }
            }

        }catch (\Exception $error){
            return $error->getMessage();
        }
    }


    /**
     * @param $config
     * @return array
     */
    public function validateData($config)
    {
        if(!isset($config['host']) || empty($config['host'])) {
            return ['code' => 400 ,'msg' => '邮件主机协议为空'];
        }
        if(!isset($config['port']) || empty($config['port'])) {
            return ['code' => 400 ,'msg' => '邮件服务器端口号为空'];
        }
        if(!isset($config['sendUse']) || empty($config['sendUse'])) {
            return ['code' => 400 ,'msg' => '发送账户为空'];
        }
        if(!isset($config['sendPwd']) || empty($config['sendPwd'])) {
            return ['code' => 400 ,'msg' => '邮件服务器密码为空'];
        }
        if(!isset($config['fromUser']) || empty($config['fromUser'])) {
            return ['code' => 400 ,'msg' => '发送方账号为空'];
        }
        if(!isset($config['toUser']) || empty($config['toUser'])) {
            return ['code' => 400 ,'msg' => '接收方账号为空'];
        }
        if(!isset($config['subject']) || empty($config['subject'])) {
            return ['code' => 400 ,'msg' => '邮件主题为空'];
        }
        if(!isset($config['msgBody']) || empty($config['msgBody'])) {
            return ['code' => 400 ,'msg' => '邮件内容为空'];
        }

    }

}