<?php

namespace app\admin\controller;

use app\common\controller\Base;
use app\common\model\ArticleCategory;
use think\facade\Request;

class Category extends Base
{
    /**
     * 渲染栏目列表页
     */
    public function cateList() {
        //判断是否登录
        $this->adminIsLogin();
        $cateList = ArticleCategory::all();
        if ($cateList) {
            $this->assign('cateList', $cateList);
        }else {
            return $this->assign('empty', '没有栏目');
        }
        $this->assign('title', '栏目列表');
        return $this->view->fetch();
    }

    /**
     * 栏目编辑
     * @return string|void
     * @throws \Exception
     * @throws \think\Exception\DbException
     */
    public function cateEdit() {
        $id = Request::param('id');
        if ($id) {
            $res = ArticleCategory::get($id);
            if ($res) {
                $this->assign('cateInfo', $res);
            }else {
                return $this->error('参数错误');
            }
        }else {
            return $this->error('缺少参数栏目id');
        }
        $this->assign('title', '编辑栏目');
        return $this->view->fetch();
    }

    /**
     * 提交栏目修改
     */
    public function doEdit() {
        $id = Request::param('id');
        $data = Request::post();
        if ($data) {
            if (ArticleCategory::where('id', $id)->update($data)) {
                return $this->success('栏目信息修改成功', 'cateList');
            }else {
                return $this->error('栏目信息修改失败');
            }
        }else {
            return $this->error('缺少参数');
        }

    }

    /**
     * 删除栏目
     */
    public function cateDel() {
        $id = Request::param('id');
        if ($id) {
            if (ArticleCategory::where('id', $id)->delete()) {
                return $this->success('栏目删除成功', 'cateList');
            }else {
                return $this->error('栏目删除失败');
            }
        }else {
            return $this->error('缺少参数');
        }
    }

    /**
     * 渲染添加栏目界面
     * @return string
     * @throws \Exception
     */
    public function cateAdd() {
        $this->assign('title', '添加栏目');
        return $this->view->fetch();
    }

    public function doAdd() {
        $data = Request::post();
        $rule = [
            'name|栏目名' => 'require|unique:ArticleCategory',
        ];
        $res = $this->validate($data, $rule);
        if ($res === true) {
            if (ArticleCategory::create($data)) {
                return $this->success('添加栏目成功', 'cateList');
            }else {
                return $this->error('添加栏目失败');
            }
        }else {
            return $this->error($res);
        }
    }
}