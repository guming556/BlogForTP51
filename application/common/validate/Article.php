<?php
namespace app\common\validate;


use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'title|文章标题'     => 'require',
//        'title_img|标题图片'   => 'require',
        'content|文章内容'      => 'require',
        'cate_id|分类'   => 'require|number',
    ];
}