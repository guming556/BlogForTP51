<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\common\model\Article;
use app\common\model\ArticleCategory;
use think\facade\Request;
use app\common\model\User as UserModel;
use think\facade\Session;

class User extends Base
{
    /**
     * 用户注册
     */
    public function register() {
        //用户登录状态下不允许进入注册页面
        $this->logined();
        $this->assign('title', '用户注册');
        return $this->fetch();
    }

    /**
     * 处理用户提交的注册信息
     */
    public function registered() {
        if (Request::isAjax()) {
            //获取用户提交的数据
            $data = Request::post();
            $rule = 'User';
            $res = $this->validate($data, $rule);
            if (true !== $res) {
                return ['status' => -1, 'msg' => $res];
            }else {
                $data = Request::except('confirm', 'post');
                if (UserModel::create($data)) {
                    return ['status' => 1, 'msg' => '注册成功'];
                }else {
                    return ['status' => 0, 'msg' => '注册失败'];
                }
            }
        }else {
            return $this->error('请求类型错误', 'register');
        }
    }

    /**
     * 用户登录
     */
    public function login() {
        //防止用户重复登录
        $this->logined();
        $this->assign('title', '用户登录');
        return $this->fetch();
    }

    /**
     * 用户登录校验
     * @return array|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function loginCheck() {
        if (Request::isAjax()) {
//            //获取用户提交的数据
            $data = Request::post();
            $rule = [
                'name' => 'require|length:4,10|chsAlphaNum',
                'password' => 'require|alphaNum'
            ];
            $res = $this->validate($data, $rule);
            if (true !== $res) {
                return ['status' => -1, 'msg' => $res];
            }else {
                $user = UserModel::where([
                    'name' => $data['name'],
                    'password' => doubleMd5($data['password'])
                ])->find();
                if (!$user) {
                    return ['status' => 0, 'msg' => '用户名或密码错误，请重新输入'];
                }else {
                    Session::set('zh_user_id', $user->id);
                    Session::set('zh_user_name', $user->name);
                    return ['status' => 1, 'msg' => '登录成功'];
                }
            }
        }else {
            return $this->error('请求类型错误', 'login');
        }
    }

    /**
     * 用户退出登录
     */
    public function logout() {
        Session::delete('zh_user_id');
        Session::delete('zh_user_name');
        $this->success('退出登录成功', 'index/index');
    }

    /**
     * 添加文章界面
     */
    public function add() {
        //1.登录后才允许发布文章
        $this->isLogin();
        //2.设置页面标题
        $this->view->assign('title', '发布文章');
        //3.获取栏目信息
        $cateList = ArticleCategory::all();
        if (count($cateList) > 0) {
            //将查询到的栏目信息传递到模板中
            $this->assign('cateList', $cateList);
        }else {
            $this->error('请先添加栏目信息','index/index');
        }
        //4.发布页面渲染
        return $this->fetch();
    }

    /**
     * 文章发布
     */
    public function save() {
        //判断提交类型
        if (Request::isPost()) {
            $data = Request::post();
            $res = $this->validate($data, 'Article');
            if ($res !== true) {
                echo "<script>alert('$res');window.history.back();</script>";
            }else {
                $file = Request::file('title_img');
                $info = $file->validate(['size' => 1024*1024*5, 'ext' => 'jpg,jpeg,png,gif' ])->move('uploads/');
                if ($info) {
                    $data['title_img'] = $info->getSaveName();
                }else {
                    $this->error($file->getError());
                }
                //将数据写入数据表
                if (Article::create($data)) {
                    $this->success('文章发布成功', 'index/index');
                }
            }
        }else {
            $this->error('请求类型错误');
        }
    }

    /**
     * 文章详情
     */
    public function detail() {
        $artId = Request::param('art_id');
        if (!empty($artId)) {
            $art = Article::get($artId);
            $this->assign('art', $art);
        }
        $this->assign('title', $art->title);
        return $this->fetch();
    }
}