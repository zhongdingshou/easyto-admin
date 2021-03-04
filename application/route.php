<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//API
//用户登陆注册
Route::post('getSess','/easyto/public/index.php/api/User/getSess');
//获取幻灯片
Route::get('getSlide','/easyto/public/index.php/api/Slide/getSlide');
//获取所有商品信息
Route::post('getGoods','/easyto/public/index.php/api/Goods/getGoods');
//获取单个商品信息
Route::post('goodsDetail','/easyto/public/index.php/api/Goods/goodsDetail');
//查找商品
Route::post('goodsSearch','/easyto/public/index.php/api/Goods/goodsSearch');
//评论商品
Route::post('issueTalk','/easyto/public/index.php/api/Goods/issueTalk');
//收藏商品
Route::post('goodsCollect','/easyto/public/index.php/api/Goods/goodsCollect');
//我的发布
Route::post('myRelease','/easyto/public/index.php/api/Goods/myRelease');
//我的收藏
Route::post('myCollect','/easyto/public/index.php/api/Goods/myCollect');
//我的认证
Route::post('approve','/easyto/public/index.php/api/User/approve');
//我的认证
Route::post('getApprove','/easyto/public/index.php/api/User/getApprove');
//编辑信息
Route::post('editUserInfo','/easyto/public/index.php/api/User/editUserInfo');
//发布商品
Route::post('issueGoods','/easyto/public/index.php/api/Goods/issueGoods');
//获取用户信息
Route::post('getUser','/easyto/public/index.php/api/User/getUser');
//获取某类商品
Route::post('goodsCategory','/easyto/public/index.php/api/Goods/goodsCategory');
//商品图片上传
Route::post('goodsImagesUplaod','/easyto/public/index.php/api/Goods/goodsImagesUplaod');
//删除商品
Route::post('goodsDelete','/easyto/public/index.php/api/Goods/goodsDelete');
//商品已经被购买
Route::post('goodsBuy','/easyto/public/index.php/api/Goods/goodsBuy');
//获取所有说说
Route::post('getSay','/easyto/public/index.php/api/Say/getSay');
//获取所有说说
Route::post('getMySay','/easyto/public/index.php/api/Say/getMySay');
//点赞说说
Route::post('setPraise','/easyto/public/index.php/api/Say/setPraise');
//点踩说说
Route::post('setTread','/easyto/public/index.php/api/Say/setTread');
//发表说说
Route::post('satSay','/easyto/public/index.php/api/Say/satSay');
//删除我的说说
Route::post('deleteSay','/easyto/public/index.php/api/Say/deleteSay');
//获取小程序信息
Route::get('getAbout','/easyto/public/index.php/api/User/getAbout');
//发送反馈信息
Route::post('satAdvice','/easyto/public/index.php/api/User/satAdvice');
