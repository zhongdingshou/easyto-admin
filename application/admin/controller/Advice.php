<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
use think\Db;


class Advice extends Base 
{
	//小程序信息列表
	public function advice_list()
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 意见箱');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');

		$advice = Db::name('advice')->alias('a')->join('user u','a.user_id=u.id')->field(['a.id','a.advice','a.create_time','u.nick_name','u.avatar_url',])->order('a.create_time desc')->paginate(9);
	    $count = count($advice);
		$this -> view -> assign('advice', $advice);
		$this -> view -> assign('count', $count);
		return $this -> view -> fetch('advice_list');
	}

	public function deleteAdvice(Request $request)
	{
		$id = $request -> param('id');
		$advice = Db::name('advice')->where('id',$id)->delete();
	}
}
