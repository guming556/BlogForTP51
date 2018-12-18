<?php

namespace app\common\model;

use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp = true;

    public function getIsAdminAttr($value) {
        $status = ['1' => '管理员', '0' => '普通会员'];
        return $status[$value];
    }

    public function getStatusAttr($value) {
        $status = ['1' => '启用', '0' => '禁用'];
        return $status[$value];
    }

    public function setPasswordAttr($value) {
        return doubleMd5($value);
    }
}