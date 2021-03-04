<?php
namespace app\admin\controller;
use app\admin\controller\Base;

class Index extends Base 
{
	public function index()
	{
		$this -> isLogin();
		$this -> assign('title','易起来后台管理系统');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');
		return $this -> view -> fetch();
	}
	
	//我的信息
	public function my_info()
	{
		$this -> isLogin();
		$this -> assign('title','易起来后台管理系统');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');
		return $this -> view -> fetch('my_info');
	}
}