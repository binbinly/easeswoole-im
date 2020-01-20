<?php


namespace App\RpcService;


use App\Common\GRpc\GRpcCmd;
use App\Common\GRpc\GRPCTool;

class MemberService extends BaseService
{
    public function gold(){
        $ret = GRPCTool::getInstance()->setCmd(GrpcCmd::EDIT_GOLD)->send($this->params);
        if($ret !== false) {
            $this->response()->setStatus(0);
        }else{
            $this->response()->setStatus(-1);
            $this->response()->setMsg(GRPCTool::getInstance()->getMsg());
        }
    }
}