<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\Db;
use think\Request;

class Say extends Base
{
	public function getSay(Request $request)
	{
		$num = $request -> param('num');
		$allSay = Db::name('say')->alias('s')->join('user u','u.id=s.user_id')->field(['u.avatar_url','u.nick_name','s.say','s.tread','s.praise','s.id','s.create_time'])->where('s.is_delete',1)->order('s.create_time','desc')->limit($num)->select();
		$Say = Db::name('say')->where('is_delete',1)->select();
		$count = count($Say)+10;
		if ($num>=$count) {
			$allSay=null;
		}
		return json($allSay);
	}

	public function getMySay(Request $request)
	{
		$thr_session = $request->param('thr_session');
		$user = Db::name('user')->where('thr_session',$thr_session)->find();
		if($user['id']) {
			$mysay = Db::name('say')->alias('s')->join('user u','u.id=s.user_id')->field(['u.avatar_url','u.nick_name','s.say','s.tread','s.praise','s.id','s.create_time'])->where('s.is_delete',1)->order('s.create_time','desc')->where('s.user_id',$user['id'])->select();
			return json($mysay);
		}
	}

	public function setPraise(Request $request)
	{
		$thr_session = $request->param('thr_session');
		$say_id = $request->param('say_id');
		$user_id = Db::name('user')->where('thr_session',$thr_session)->find();
		if ($user_id) {
			$thePraise = Db::name('praiseandtread')->where('user_id',$user_id['id'])->where('say_id',$say_id)->find();
			if(!$thePraise){
				$data = ['user_id' => $user_id['id'], 'say_id' => $say_id,'is_praise'=>1];
				$Praise = Db::name('praiseandtread')->insert($data);
				$praisecount = Db::name('praiseandtread')->where('say_id',$say_id)->where('is_praise',1)->count();
				$updatepraise = Db::name('say')->where('id',$say_id)->update(['praise'=>$praisecount]);
				return 1;
			} else {
				if ($thePraise['is_praise']==0) {
					$Praise = Db::name('praiseandtread')->where('user_id',$user_id['id'])->where('say_id',$say_id)->update(['is_praise'=>1]);
					$praisecount = Db::name('praiseandtread')->where('say_id',$say_id)->where('is_praise',1)->count();
					$updatepraise = Db::name('say')->where('id',$say_id)->update(['praise'=>$praisecount]);
					return 1;
				} else {
					$collect = Db::name('praiseandtread')->where('user_id',$user_id['id'])->where('say_id',$say_id)->update(['is_praise'=>0]);
					$praisecount = Db::name('praiseandtread')->where('say_id',$say_id)->where('is_praise',1)->count();
					$updatepraise = Db::name('say')->where('id',$say_id)->update(['praise'=>$praisecount]);
					return -1;
				}
			}
			return 0;
		} else {
			return 2;
		}
	}

	public function setTread(Request $request)
	{
		$thr_session = $request->param('thr_session');
		$say_id = $request->param('say_id');
		$user_id = Db::name('user')->where('thr_session',$thr_session)->find();
		if ($user_id) {
			$thePraise = Db::name('praiseandtread')->where('user_id',$user_id['id'])->where('say_id',$say_id)->find();
			if(!$thePraise){
				$data = ['user_id' => $user_id['id'], 'say_id' => $say_id,'is_tread'=>1];
				$Praise = Db::name('praiseandtread')->insert($data);
				$praisecount = Db::name('praiseandtread')->where('say_id',$say_id)->where('is_tread',1)->count();
				$updatepraise = Db::name('say')->where('id',$say_id)->update(['tread'=>$praisecount]);
				return 1;
			} else {
				if ($thePraise['is_tread']==0) {
					$Praise = Db::name('praiseandtread')->where('user_id',$user_id['id'])->where('say_id',$say_id)->update(['is_tread'=>1]);
					$praisecount = Db::name('praiseandtread')->where('say_id',$say_id)->where('is_tread',1)->count();
					$updatepraise = Db::name('say')->where('id',$say_id)->update(['tread'=>$praisecount]);
					return 1;
				} else {
					$collect = Db::name('praiseandtread')->where('user_id',$user_id['id'])->where('say_id',$say_id)->update(['is_tread'=>0]);
					$praisecount = Db::name('praiseandtread')->where('say_id',$say_id)->where('is_tread',1)->count();
					$updatepraise = Db::name('say')->where('id',$say_id)->update(['tread'=>$praisecount]);
					return -1;
				}
			}
			return 0;
		} else {
			return 2;
		}
	} 

	public function satSay(Request $request)
	{
		$thr_session = $request->param('thr_session');
		$say = $request->param('say');
		$user_id = Db::name('user')->where('thr_session',$thr_session)->find();
		if ($user_id&&$user_id['is_approve']==1) {
			$create_time = time();
			$data = ['say'=>$say,'user_id'=>$user_id['id'],'create_time'=>$create_time];
			$say=Db::name('say')->insert($data);
			return 1;
		} else if ($user_id) {
			return 0;
		}
		return -1;
	}

	public function deleteSay(Request $request)
	{
		$id = $request->param('say_id');
		$thr_session = $request->param('thr_session');
		$say = Db::name('say')->where('id',$id)->find();
		$user_id = Db::name('user')->where('thr_session',$thr_session)->find();
		if ($say&&$user_id) {
			if ($user_id['id']==$say['user_id']) {
				$say=Db::name('say')->where('id',$id)->update(['is_delete'=>0]);
				return 1;
			}
			return 0;
		} 
		return 0;
	}
}