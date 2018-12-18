<?php
namespace app\common\model;


use think\Model;

class Article extends Model
{
    protected $autoWriteTimestamp = true;

    public function getTitleImgAttr($value) {
        return config('base.base_url').'/uploads/'.$value;
    }

}