<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/14
 * Time: 15:12
 */

namespace App\HttpController\Api;

use EasySwoole\Validate\Validate;

class BaseController extends \App\HttpController\Common\BaseController
{
    public function onRequest(?string $action): ?bool
    {
        if (parent::onRequest($action)) {
            return true;
        }
        return false;
    }

    protected function getValidateRule(?string $action): ?Validate
    {
        return null;
        // TODO: Implement getValidateRule() method.
    }

    protected function afterAction(?string $actionName): void
    {
        $this->response()->withHeader('Content-type','application/json;charset=utf-8');
        $this->response()->withHeader('Access-Control-Allow-Origin','*');
    }
}