<?php

namespace api\tools;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Email客户端
 * Demo: (new Email)->setSubject('标题')->addAddress($mail, $name)->setBody('<h2>内容</h2>')->send();
 * @author decezz@qq.com
 */
class Email
{
    /**
     * PHPMailer 实例
     * @var object|null
     */
    private $mail = null;

    /**
     * 错误消息
     * @var string
     */
    public $errorMsg = '';

    /**
     * 初始化
     * @author decezz@qq.com
     */
    public function __construct()
    {
        $config = Config::get('email');
        $mail   = new PHPMailer();
        // 服务器设置
        $mail->SMTPDebug  = $config['SMTPDebug'];                   // Enable verbose debug output
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->SMTPAuth   = $config['SMTPAuth'];                    // Enable SMTP authentication
        $mail->Host       = $config['Host'];                        // Specify main and backup SMTP servers
        $mail->Username   = $config['Username'];                    // SMTP username
        $mail->Password   = $config['Password'];                    // SMTP password
        $mail->SMTPSecure = $config['SMTPSecure'];                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = $config['Port'];                        // TCP port to connect to
        $mail->setFrom($config['setFrom'], $config['setName']);
        $this->mail = $mail;
    }

    /**
     * 设置标题
     * @param string $Subject
     * @return this
     */
    public function setSubject(string $Subject)
    {
        $this->mail->Subject = $Subject;
        return $this;
    }

    /**
     * 添加收件人
     * @param string $email
     * @param string $name
     * @return this
     */
    public function addAddress($email, $name = '')
    {
        $this->mail->addAddress($email, $name);
        return $this;
    }

    /**
     * 批量添加收件人
     * @param array $list (包含 email & name 的数组)
     * @return this
     */
    public function addAddressBatch($list)
    {
        foreach ($list as $value) {
            $this->mail->addAddress($value['email'], $value['name']);
        }
        return $this;
    }

    /**
     * 抄送
     * @param string $email
     * @param string $name
     * @return this
     */
    public function addReplyTo($email, $name = '')
    {
        $this->mail->addReplyTo($email, $name);
        return $this;
    }

    /**
     * 添加附件
     * @param string $attachment
     * @param string $name
     * @return this
     */
    public function addAttachment($attachment, $name = '')
    {
        $this->mail->addAttachment($attachment, $name);
        return $this;
    }

    /**
     * 设置消息体
     * @param string $body
     * @return this
     */
    public function setBody($body)
    {
        $this->mail->Body = $body;
        return $this;
    }

    /**
     * 设置html消息体
     * @param string $html
     * @return this
     */
    public function setHtml($html)
    {
        $this->mail->isHTML(true);
        $this->mail->Body = $html;
        return $this;
    }

    /**
     * 设置非HTML邮件客户端的纯文本正文
     * @param string $AltBody
     * @return this
     */
    public function setAltBody($AltBody)
    {
        $this->mail->AltBody = $AltBody;
        return $this;
    }

    /**
     * 发送消息
     * @return mixed
     */
    public function send()
    {
        try {
            $result = $this->mail->send();
            if (!$result) {
                $this->errorMsg = $this->mail->ErrorInfo;
            }
        } catch (\Exception $e) {
            $this->errorMsg = $this->mail->ErrorInfo;
            $result = false;
        }
        var_dump($this->errorMsg);
        return $result;
    }
}
