<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\common\model\Article;
use app\common\model\ArticleCategory;
use think\facade\Request;

class Index extends Base
{
    /**
     * 首页渲染
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        //全局查询条件
        $map = [];
        $map[] = ['status', '=', 1];
        $keywords = Request::param('keywords');
        if (!empty($keywords)) {
            $this->assign('keywords', $keywords);
            $map[] = ['title', 'like', '%'.$keywords.'%'];
        }
        $cate_id = Request::param('cate_id');
        if (isset($cate_id)) {
            $map[] = ['cate_id', '=', $cate_id];
            $res = ArticleCategory::get($cate_id);
            $cateName = $res->name;
            $artList = Article::where($map)
                ->order('create_time', 'desc')->paginate(3);
        }else {
            $cateName = '全部文章';
            $artList = Article::where($map)->order('create_time', 'desc')->paginate(3);
        }
        $this->assign('cateName', $cateName);
        $this->assign('artList', $artList);
        return $this->fetch();
    }

}
