<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17
 * Time: 17:12
 */

namespace App\HttpController\Api;


use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Trigger;
use EasySwoole\Http\Message\UploadFile;
use Gumlet\ImageResize;
use Exception;

class UploadController extends BaseController
{
    public function image(){
        $request=  $this->request();

        /** @var UploadFile $imgFile */
        $imgFile = $request->getUploadedFile('file');//获取一个上传文件,返回的是一个\EasySwoole\Http\Message\UploadFile的对象
        if(!$imgFile) {
            $this->error('请上传文件');
        }
        $mediaType = ['image/jpeg', 'image/png', 'image/gif'];
        if(!in_array($imgFile->getClientMediaType(), $mediaType)) {
            $this->error('请上传图片');
        }
        if($imgFile->getSize() > 10000000) {//10M内图片
            $this->error('图片太大啦');
        }
        $rootPath = EASYSWOOLE_ROOT.'/Static/upload/';
        $subPath = 'image/'.date('Ymd');

        if(!file_exists($rootPath.$subPath)) {
            mkdir($rootPath.$subPath, 0777, true);
        }

        $fileName = md5_file($imgFile->getTempName()).'.jpg';
        $imgFile->moveTo($rootPath.$subPath.'/'.$fileName);
        $data['src'] = Config::getInstance()->getConf('HTTP_HOST').'/upload/'.$subPath.'/'.$fileName;
        $data['name'] = 'image';

        try {
            $image = new ImageResize($rootPath . $subPath . '/' . $fileName);
            $image->resizeToShortSide(1024);
            $image->save($rootPath . $subPath . '/' . $fileName);
        }catch (Exception $e) {
            Trigger::getInstance()->error($e->getMessage());
        }
        $this->success($data);
    }
}