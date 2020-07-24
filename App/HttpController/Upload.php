<?php
/**
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com

 * @version: 1.0
 * @license: Apache Licence
 * @file: Upload.php
 * @time: 2019-12-01 14:00
 */
namespace App\HttpController;

use EasySwoole\Http\Message\Status;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Utility\SnowFlake;
use App\HttpController\Base;


class Upload extends Base
{
    /**
    * 上传音乐文件
    */
    public function file()
    {
        $request = $this->request();
        $file = $request->getUploadedFile('file');

        $instance = Config::getInstance();
        $fileHost="http://112.74.58.15:9501";
        $uid = 'file_'.SnowFlake::make(1,1);
        $data = [
            'file' => ''
        ];
        if (!empty($file)) {
            $arr=explode('.', $file->getClientFilename());
            $type=$arr[count($arr)-1];
            if (!in_array($type, ['wav','mp3','mp4','m4a'])) {
                $this->writeJson(Status::CODE_BAD_REQUEST, null, '文件格式错误');
            } else {
                $date = date('Ymd', time());
                $path = 'Static/'.$date;
                $resPath = $fileHost.'/Static/'.$date;

                //创建目录
                if (!is_dir($path)) {
                    if (!mkdir($path, 777, true)) {
                        $this->writeJson(Status::CODE_BAD_REQUEST, null, '失败');
                    } else {
                        $path .= '/'.$uid.'.'.$type;
                        $resPath .= '/'.$uid.'.'.$type;
                        $file->moveTo($path); // 移动文件（file_put_contents 实行）
                        $data['file'] = $resPath;
                        $this->writeJson(Status::CODE_OK, $data, '成功');
                    }
                } else {
                    $path .= '/'.$uid.'.'.$type;
                    $resPath .= '/'.$uid.'.'.$type;
                    $file->moveTo($path); // 移动文件（file_put_contents 实行）
                    //$file->getSize();               // 获取文件大小
                    //$file->getError();              // 获取错误序号
                    $data['file'] = $resPath;
                    $this->writeJson(Status::CODE_OK, $data, '成功');
                }
            }
        } else {
            $this->writeJson(Status::CODE_BAD_REQUEST, null, '失败');
        }
    }
}
