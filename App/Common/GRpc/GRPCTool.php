<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/26
 * Time: 14:12
 */

namespace App\Common\GRpc;


use App\Utility\JsonHelper;
use EasySwoole\Component\Singleton;
use Pb\Easymicro\EMReq;
use Pb\Easymicro\EMRsp;

class GRPCTool
{
    use Singleton;

    //路由
    protected $cmd = '';

    //请求数据
    protected $data = [];

    protected $msg = '';

    public function getMsg(){
        return $this->msg;
    }

    public function send(array $params = [])
    {
        $data = JsonHelper::encode($params);

        $request = new EMReq();
        $request->setCmd($this->cmd);
        $request->setReqData($data);
        $request->setSeq(0);
        $request->setTraceID(uniqid());
        /** @var EMRsp $reply */
        list($reply, $status) = RpcClient::getInstance()->getClient()->UnaryCall($request)->wait();

        if($status->code != 0) {//code=0成功
            $this->msg = $status->details;
            return false;
        }
        $rsp = $reply->getRspCode();

        if($rsp == 200){
            if($reply->getRspData()) {
                return JsonHelper::decode($reply->getRspData());
            }
            return true;
        }
        $this->msg = $reply->getRspMsg();
        return false;
    }

    /**
     * @param string $cmd
     * @return $this
     */
    public function setCmd(string $cmd)
    {
        $this->cmd = $cmd;
        return $this;
    }

    /**
     * 签名
     * @param string $data
     * @param $key
     * @return string
     */
    protected function sign(string $data, $key){
        return md5(md5($data) . $key);
    }
}