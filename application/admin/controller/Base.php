<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;


class Base extends Controller
{
	protected function _initialize()
   {
      parent::_initialize();

      define('ADMIN_ID', Session::has('admin_id') ? Session::get('admin_id'):null);
   }

   protected function isLogin()
   {
       if(empty(ADMIN_ID)){
           $this ->error('用户未登录，无权访问',url('/admin/login/login'));
       }
   }
   
   protected function alreadyLogin()
   {
       if(!empty(ADMIN_ID)){
           $this ->error('用户已登录，正在进入',url('/admin/index/index'));
       }
   }
}