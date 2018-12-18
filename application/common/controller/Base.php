<?php
/**
 * 基础控制器
 */
namespace app\common\controller;

use app\common\model\ArticleCategory;
use think\Controller;
use think\facade\Session;

class Base extends Controller
{
    /**
     * 初始化方法
     * 创建常量，公共方法
     * 在所有的方法之前被调用
     * @throws \think\Exception\DbException
     */
    protected function initialize()
    {
        $this->showNav();

    }

    /**
     * 检查是否已登录，防止重复登录
     * 前台
     */
    protected function logined() {
        if (Session::has('zh_user_id')){
            $this->error('哥们，你已经登录过了', 'index/index');
        }
    }

    //后台
    protected function adminLogined() {
        if (Session::has('admin_id')){
            $this->error('哥们，你已经登录过了', 'admin/index');
        }
    }
    /**
     * 检查是否未登录，放在需要登录后才能操作的方法的最前面，如发布文章
     * 前台
     */
    protected function isLogin() {
        if (!Session::has('zh_user_id')){
            $this->error('哥们，悠着点，你还没有登录呢', 'index/user/login');
        }
    }

    //后台
    protected function adminIsLogin() {
        if (!Session::has('admin_id')){
            $this->error('哥们，悠着点，你还没有登录呢', 'admin/user/login');
        }
    }

    /**
     * 加载栏目导航数据
     * @throws \think\Exception\DbException
     */
    protected function showNav() {
        $cateList = ArticleCategory::where('status', 1)->order('sort','asc')->all();
        if (!$cateList) {
            $this->error('找不到栏目');
        }else {
            $this->assign('cateList', $cateList);
        }
    }
}