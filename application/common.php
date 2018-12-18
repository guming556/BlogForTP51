<?php
// 应用公共文件
use app\common\model\User;
use app\common\model\ArticleCategory;

/**
 * 密码加密
 * @param $password
 * @return string
 */
function doubleMd5($password){
    return md5(md5($password).'');
}
/**
 * 根据用户ID获取用户名
 */
if (!function_exists('getUserNameByID')) {
    function getUserNameByID($user_id) {
        return User::where('id', $user_id)->value('name');
    }
}

/**
 * 截取文章的摘要
 */
function artAbstract($content) {
    return mb_substr(strip_tags($content), 0, 30).'...';
}

/**
 * 根据栏目的id获取栏目名称
 */
if (!function_exists('getCateNameByID')) {
    function getCateNameByID($cate_id) {
        return ArticleCategory::where('id', $cate_id)->value('name');
    }
}

/**
 * 解析html标签
 */
function htmlDecode($content) {
    return html_entity_decode($content, ENT_QUOTES, 'UTF-8');
}