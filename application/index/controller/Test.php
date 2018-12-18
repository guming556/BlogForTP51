<?php
/**
 * 测试专用类
 */
namespace app\index\controller;


use app\common\controller\Base;
use think\facade\Session;

class Test extends Base
{
    public function index()
    {
        print_r(config('base.base_url'));
    }
}