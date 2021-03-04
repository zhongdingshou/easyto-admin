<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
use think\Db;


class Say extends Base 
{
	//说说列表
	public function say_list()
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 说说列表');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');

		$says = Db::name('say')->alias('s')->join('user u','s.user_id=u.id')->field(['u.avatar_url','u.nick_name','s.praise','s.is_delete','s.id','s.user_id','s.tread','s.say','s.create_time'])->order('s.create_time','desc')->paginate(9);
	    $count = count($says);
		$this -> view -> assign('says', $says);
		$this -> view -> assign('count', $count);
		return $this -> view -> fetch('say_list');
	}
	public function deleteSay(Request $request)
	{
		$id = $request -> param('id');
		$say = Db::name('say')->where('id',$id)->delete();
	}
}