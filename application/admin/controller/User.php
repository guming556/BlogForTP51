<?php
namespace app\admin\controller;


use app\common\controller\Base;
use think\facade\Request;
use app\common\model\User as UserModel;
use think\facade\Session;

class User extends Base
{
    /**
     * 渲染后台登录界面
     * @return string
     * @throws \Exception
     */
    public function login() {
        $this->adminLogined();
        $this->assign('title', '管理员登录');
        return $this->view->fetch();
    }

    /**
     * 验证后台登录
     */
    public function loginCheck() {
        $data = Request::param();
        //查询条件
        $map[] = ['name', '=', $data['name']];
        $map[] = ['password', '=', doubleMd5($data['password'])];

        $result = UserModel::where($map)->find();
        if ($result) {
            Session::set('admin_id', $result->id);
            Session::set('admin_name', $result->name);
            Session::set('userLevel', $result->is_admin);
            $this->success('登录成功', 'index/index');
        }else {
            $this->error('用户名或密码错误，请检查后重试', 'login');
        }
    }

    /**
     * 退出登录
     */
    public function logout() {
        Session::delete('admin_id');
        Session::delete('admin_name');
        Session::delete('userLevel');
        $this->success('退出登录成功', 'user/login');
    }


    /**
     * 用户列表
     * @return string
     * @throws \Exception
     * @throws \think\Exception\DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function userList() {
        $admin_id = Session::get('admin_id');
        $userLevel = Session::get('userLevel');
        if ($userLevel == '管理员') {
            $data = UserModel::all();
        }else {
            $data = UserModel::get($admin_id);
        }
        if ($data) {
            $this->assign('userList', $data);
        }else {
            $this->assign('empty', '<span style="color: red">没有用户</span>');
        }
        $this->assign('title', '用户列表');
        return $this->view->fetch();
    }

    /**
     * 用户编辑页面
     * @return string|void
     * @throws \Exception
     * @throws \think\Exception\DbException
     */
    public function userEdit() {
        $id = Request::param('id');
        if ($id) {
            $res = UserModel::get($id);
            if ($res) {
                $this->assign('userInfo', $res);
            }else {
                return $this->error('参数错误');
            }
        }else {
            return $this->error('缺少参数用户id');
        }
        $this->assign('title', '编辑用户');
        return $this->view->fetch();
    }

    /**
     * 提交用户信息修改
     */
    public function doEdit() {
        $id = Request::param('id');
        $data = Request::post();
        if ($data) {
            if (UserModel::where('id', $id)->update($data)) {
                return $this->success('用户信息修改成功', 'userList');
            }else {
                return $this->error('用户信息修改失败');
            }
        }else {
            return $this->error('缺少参数');
        }
    }

    public function userDel() {
        $id = Request::param('id');
        if ($id) {
            if (UserModel::where('id', $id)->delete()) {
                return $this->success('用户删除成功', 'userList');
            }else {
                return $this->error('用户删除失败');
            }
        }else {
            return $this->error('缺少参数');
        }
    }
}