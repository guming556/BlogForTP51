<?php
namespace app\common\validate;


use think\Validate;

class ArtCate extends Validate
{
    protected $rule = [
        'name|用户名'     => 'require|length:4,10|chsAlphaNum|unique:User',
        'password|密码'   => 'require|length:6,20|alphaNum|confirm',
        'email|邮箱'      => 'require|email|unique:User',
        'mobile|手机号'   => 'require|mobile|number|unique:User',
    ];
}