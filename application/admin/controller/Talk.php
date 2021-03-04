<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
use think\Db;


class Talk extends Base 
{
	//评论列表
	public function comment_list()
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 评论列表');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');

		$goods = Db::name('lookandtalk')->alias('l')->join('goods g','l.goods_id=g.id')->join('user u','g.seller_id=u.id')->field(['g.id','g.goods_title','u.nick_name','u.avatar_url','g.goods_img','g.goods_abstract','g.goods_prom_price','g.goods_category','g.look'])->where('l.create_time','>',0)->order('g.id','desc')->distinct('g.id')->paginate(9);
	    $count = count($goods);
		$this -> view -> assign('goods', $goods);
		$this -> view -> assign('count', $count);
		return $this -> view -> fetch('comment_list');
	}
	public function deleteTalk(Request $request)
	{
		$id = $request -> param('id');
		$say = Db::name('lookandtalk')->where('goods_id',$id)->delete();
	}

	public function deleteOne(Request $request)
	{
		$id = $request -> param('id');
		$say = Db::name('lookandtalk')->where('id',$id)->delete();
	}

	//查看商品评论
	public function lookTalk(Request $request)
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 商品评论');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');

		$id = $request -> param('id');
		$talks = Db::name('lookandtalk')->alias('l')->join('user u','l.user_id=u.id')->field(['l.id','u.nick_name','u.avatar_url','l.create_time','l.talk'])->where('l.goods_id',$id)->order('l.id')->paginate(9);
	    $count = count($talks);
		$this -> view -> assign('talks', $talks);
		$this -> view -> assign('count', $count);
		return $this -> view -> fetch('show_talk');
	}
}