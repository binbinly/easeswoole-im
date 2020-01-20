<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/21
 * Time: 17:37
 */

namespace App\RpcService;


use EasySwoole\EasySwoole\Config;
use EasySwoole\Rpc\AbstractService;

class BaseService extends AbstractService
{
    protected $params = [];

    public function serviceName(): string
    {
        return basename(str_replace('\\', '/', get_class($this)));
    }

    protected function onRequest(?string $action): ?bool
    {
        if (parent::onRequest($action)) {
            $requestData = $this->request()->toArray();

            if(!isset($requestData['arg']['sign'])) {
                $this->error('sign not found');
            }

            $sign = $requestData['arg']['sign'];
            unset($requestData['arg']['sign']);
            asort($requestData['arg']);
            $signStr = http_build_query($requestData['arg']);
            $sign2 = md5(md5($signStr). Config::getInstance()->getConf('SIGN_KEY'));
            if($sign == $sign2) {
                $this->params = $requestData['arg'];
                return true;
            }
            $this->error('sign error');
            return false;
        }
        $this->error('request error');
        return false;
    }

    /**
     * 成功返回
     * @param array $data
     * @return string
     */
    protected function success(array $data = []){
        $this->response()->setStatus(0);
        $this->response()->setResult($data);
        $this->response()->setMsg('success');
    }

    /**
     * 失败返回
     * @param string $msg
     * @return string
     */
    protected function error(string $msg) {
        $this->response()->setStatus(-1);
        $this->response()->setMsg($msg);
    }
}