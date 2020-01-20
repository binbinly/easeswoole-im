<?php


namespace App\Common\Ssl;


class AES
{
    const TYPE_16 = 1;
    const TYPE_32 = 2;

    protected $method = '';  //加密算法

    protected $secret_key = '';  //密钥

    public function __construct($type = self::TYPE_16)
    {
        $this->init($type);
    }

    protected function init($type){
        if($type == self::TYPE_32) {
            $this->method = 'AES-256-CBC';
            $this->secret_key = 'i4XnFIRHT05qZBOYgxmVeuQPKEj1ls8v';
        }else{
            $this->method = 'AES-128-CBC';
            $this->secret_key = '9871267812345mn8';
        }
    }

    /**
     * AES加密算法
     * @param string $content 加密内容
     * @return string
     */
    public function encrypt($content)
    {
        $result = openssl_encrypt($content, $this->method, $this->secret_key);
        $str = base64_encode($result);
        return $str;
    }

    /**
     * AES解密算法
     * @param string $content 密文
     * @return string
     */
    public function decrypt($content)
    {
        $content = base64_decode($content);
        return openssl_decrypt($content, $this->method, $this->secret_key, OPENSSL_RAW_DATA, $this->secret_key);
    }
}
