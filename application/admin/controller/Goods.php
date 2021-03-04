<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
use think\Db;

class Goods extends Base
{
	public function new_product()
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 最新商品列表');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');

		$goods = Db::name('goods')->alias('g')->join('user u','g.seller_id=u.id')->field(['g.goods_abstract','g.goods_img','g.id','g.goods_title','g.goods_prom_price','g.goods_category','g.create_time','g.is_buy','u.nick_name'])->where('g.is_audit',1)->order('g.create_time desc')->paginate(5);
		$count = count($goods);
		$this -> view -> assign('goods', $goods);
		$this -> view -> assign('count', $count);
		return $this -> view -> fetch('new_product');
	}


	public function commodity_audit() 
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 审核商品列表');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');

		$goods = Db::name('goods')->alias('g')->join('user u','g.seller_id=u.id')->field(['g.goods_abstract','g.goods_img','g.id','g.goods_title','g.goods_prom_price','g.goods_category','g.create_time','g.is_audit','u.nick_name'])->order('g.id desc')->paginate(5);
		$count = count($goods);
		$this -> view -> assign('goods', $goods);
		$this -> view -> assign('count', $count);
		return $this -> view -> fetch('commodity_audit');
	}

	public function deleteGoods(Request $request) 
	{
		$id = $request->param('id');
		Db::name('goods')->where('id',$id)->delete();
	}

	public function is_audit(Request $request) 
	{
		$id = $request->param('id');
		$is_audit = $request->param('is_audit');
		Db::name('goods')->where('id',$id)->update(['is_audit'=>$is_audit]);
	}

	public function goods_life() 
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 分类商品列表');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');
		
		$goods = Db::name('goods')->alias('g')->join('user u','g.seller_id=u.id')->field(['g.goods_abstract','g.goods_img','g.id','g.goods_title','g.goods_prom_price','g.goods_category','g.create_time','g.is_buy','g.look','u.nick_name','u.avatar_url'])->where('g.goods_category',4)->where('g.is_audit',1)->order('g.id desc')->paginate(5);
		$count = count($goods);
		$this -> view -> assign('goods', $goods);
		$this -> view -> assign('count', $count);
		return $this -> view -> fetch('goods_life');
	}

	public function goods_sports() 
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 分类商品列表');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');
		
		$goods = Db::name('goods')->alias('g')->join('user u','g.seller_id=u.id')->field(['g.goods_abstract','g.goods_img','g.id','g.goods_title','g.goods_prom_price','g.goods_category','g.create_time','g.is_buy','g.look','u.nick_name','u.avatar_url'])->where('g.goods_category',3)->where('g.is_audit',1)->order('g.id desc')->paginate(5);
		$count = count($goods);
		$this -> view -> assign('goods', $goods);
		$this -> view -> assign('count', $count);
		return $this -> view -> fetch('goods_sports');
	}

	public function goods_books() 
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 分类商品列表');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');
		
		$goods = Db::name('goods')->alias('g')->join('user u','g.seller_id=u.id')->field(['g.goods_abstract','g.goods_img','g.id','g.goods_title','g.goods_prom_price','g.goods_category','g.create_time','g.is_buy','g.look','u.nick_name','u.avatar_url'])->where('g.goods_category',2)->where('g.is_audit',1)->order('g.id desc')->paginate(5);
		$count = count($goods);
		$this -> view -> assign('goods', $goods);
		$this -> view -> assign('count', $count);
		return $this -> view -> fetch('goods_books');
	}

	public function goods_electronic() 
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 分类商品列表');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');
		
		$goods = Db::name('goods')->alias('g')->join('user u','g.seller_id=u.id')->field(['g.goods_abstract','g.goods_img','g.id','g.goods_title','g.goods_prom_price','g.goods_category','g.create_time','g.is_buy','g.look','u.nick_name','u.avatar_url'])->where('g.goods_category',1)->where('g.is_audit',1)->order('g.id desc')->paginate(5);
		$count = count($goods);
		$this -> view -> assign('goods', $goods);
		$this -> view -> assign('count', $count);
		return $this -> view -> fetch('goods_electronic');
	}

	public function goods_other() 
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 分类商品列表');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');
		
		$goods = Db::name('goods')->alias('g')->join('user u','g.seller_id=u.id')->field(['g.goods_abstract','g.goods_img','g.id','g.goods_title','g.goods_prom_price','g.goods_category','g.create_time','g.is_buy','g.look','u.nick_name','u.avatar_url'])->where('g.goods_category',0)->where('g.is_audit',1)->order('g.id desc')->paginate(5);
		$count = count($goods);
		$this -> view -> assign('goods', $goods);
		$this -> view -> assign('count', $count);
		return $this -> view -> fetch('goods_other');
	}

}