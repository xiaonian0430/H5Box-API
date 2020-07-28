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
use App\HttpController\Base;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Config\RedisConfig;


class URL extends Base
{
    public function postUrl()
    {
        $request = $this->request();
        $url = $request->getRequestParam('url');
        $key = $request->getRequestParam('key');
        if(empty($key) || empty($url)) {
            $this->writeJson(Status::CODE_BAD_REQUEST, null, '失败');
        }else{
            $instance = Config::getInstance();
            $redisOne=$instance->getConf('REDIS_CONFIG.one');
            $config = new RedisConfig([
                'host' => $redisOne['host'],
                'port' => intval($redisOne['port']),
                'db' => 5,
                'auth' => ''
            ]);
            $redis = new Redis($config);
            $redis->set('test:'.$key, $url);
            $data = [
                'url'=>$url,
                'key'=>$key
            ];
            $this->writeJson(Status::CODE_OK, $data, '成功');
        }
    }

    public function getUrl()
    {
        $request = $this->request();
        $key = $request->getRequestParam('key');
        if(empty($key)) {
            $this->writeJson(Status::CODE_BAD_REQUEST, null, '失败');
        }else{
            $instance = Config::getInstance();
            $redisOne=$instance->getConf('REDIS_CONFIG.one');
            $config = new RedisConfig([
                'host' => $redisOne['host'],
                'port' => intval($redisOne['port']),
                'db' => 5,
                'auth' => ''
            ]);
            $redis = new Redis($config);
            $url = $redis->get('test:'.$key);
            $data = [
                'url'=>$url,
                'key'=>$key
            ];
            $this->writeJson(Status::CODE_OK, $data, '成功');
        }
    }
}
