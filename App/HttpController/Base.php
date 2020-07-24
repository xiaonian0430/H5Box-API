<?php
/**
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com

 * @version: 1.0
 * @license: Apache Licence
 * @file: Base.php
 * @time: 2019-12-01 14:00
 */
namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;

class Base extends Controller
{
    public function index()
    {
        return 'index';
    }
}
