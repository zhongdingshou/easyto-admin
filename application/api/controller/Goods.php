<?php 
namespace app\api\controller;
use app\api\controller\Base;
use think\Db;
use think\Request;

class Goods extends Base
{
	//获取所有商品
	public function getGoods(Request $request)
	{
		$num = $request -> param('num');
		$allGoods = Db::name('goods')->alias('g')->join('user u','g.seller_id=u.id')->field(['u.avatar_url','u.nick_name','u.is_approve','g.goods_title','g.goods_img','g.goods_img2','g.goods_img3','g.id','g.look','g.goods_category','g.create_time'])->where('g.is_audit',1)->where('g.is_buy',0)->order('g.create_time','desc')->limit($num)->select();
		$Goods = Db::name('goods')->where('is_audit',1)->where('is_buy',0)->select();
		$count = count($Goods)+10;
		if ($num>=$count) {
			$allGoods=null;
		}
		return json($allGoods);
	}

	//获取单个商品
	public function goodsDetail(Request $request)
	{
		$goods_id = $request -> param('goods_id');
		$thr_session = $request -> param('thr_session');
		$user_id = Db::name('user')->where('thr_session',$thr_session)->find();
		if ($user_id) {
			$theUser = Db::name('lookandtalk')->where('user_id',$user_id['id'])->where('goods_id',$goods_id)->find();
			if (!$theUser) {
				$data = ['user_id' => $user_id['id'], 'goods_id' => $goods_id];
				$setUser = Db::name('lookandtalk')->insert($data);
				$count = Db::name('lookandtalk')->where('goods_id',$goods_id)->count();
				$updatelook = Db::name('goods')->where('id',$goods_id)->update(['look'=>$count]);
			}
		} else {
			return 0;
		}
		$theGoods = Db::name('goods')->alias('g')->join('user u','g.seller_id=u.id')->field(['g.id','g.goods_title','g.goods_abstract','g.goods_img','g.goods_img2','g.goods_img3','g.goods_prom_price','g.goods_category','g.is_buy','g.create_time','g.look','u.linkman','u.wechat_number','u.phone_number','u.nick_name'])->where('g.id',$goods_id)->find();
		if ($user_id['is_approve']==0) {
			$theGoods['wechat_number']='we*******110';
			$theGoods['phone_number']='1*********6';
		}
		$is_collect =Db::name('user')->alias('u')->join('collect c','u.id=c.user_id')->where('c.goods_id',$goods_id)->field(['c.is_collect'])->where('user_id',$user_id['id'])->find();
		$theTalks = Db::name('lookandtalk')->alias('l')->join('user u','l.user_id=u.id')->field(['u.nick_name','u.avatar_url','l.create_time','l.talk'])->where('l.goods_id',$goods_id)->select();
		$GoodsInfo = ['theGoods'=>$theGoods,'is_collect'=>$is_collect,'theTalks'=>$theTalks];
		return json($GoodsInfo);
	}

	//搜索商品
	public function goodsSearch(Request $request)
	{
		$title = $request->param('title');
		$theGoods = Db::name('goods')->alias('g')->join('user u','g.seller_id=u.id')->field(['u.avatar_url','u.nick_name','u.is_approve','g.goods_title','g.goods_img','g.goods_img2','g.goods_img3','g.id','g.look','g.goods_category','g.create_time'])->where('g.is_audit',1)->where('g.is_buy',0)->order('g.create_time','desc')->where('g.goods_title','like','%'.$title.'%')->select();
		return json($theGoods);
	}

	//发表评论
	public function issueTalk(Request $request)
	{	
		$data = $request->param();
		$user_id = Db::name('user')->where('thr_session',$data['thr_session'])->find();
		if ($user_id) {
			$theTalk = Db::name('lookandtalk')->where('user_id',$user_id['id'])->where('goods_id',$data['goods_id'])->find();
			$create_time = time();
			if (!$theTalk['talk']) {
				$setTalk = Db::name('lookandtalk')->where('user_id',$user_id['id'])->where('goods_id',$data['goods_id'])->update(['talk'=>$data['talk'],'create_time'=>$create_time]);
				return 1;
			} else {
				$data = [
					'user_id' => $user_id['id'], 
					'goods_id' => $data['goods_id'], 
					'talk' => $data['talk'],
					'create_time'=>$create_time
				];
				$setTalk = Db::name('lookandtalk')->insert($data);
				return 1;
			}
		}
		return 0;
		
	}

	//收藏商品
	public function goodsCollect(Request $request)
	{
		$data = $request->param();
		$user_id = Db::name('user')->where('thr_session',$data['thr_session'])->find();
		if ($user_id) {
			$theGoods = Db::name('collect')->where('user_id',$user_id['id'])->where('goods_id',$data['goods_id'])->find();
			if(!$theGoods){
				$data = ['user_id' => $user_id['id'], 'goods_id' => $data['goods_id'],'is_collect'=>1];
				$theGoods = Db::name('collect')->insert($data);
				return 1;
			} else {
				if ($theGoods['is_collect']==0) {
					$collect = Db::name('collect')->where('user_id',$user_id['id'])->where('goods_id',$data['goods_id'])->update(['is_collect'=>1]);
					return 1;
				} else {
					$collect = Db::name('collect')->where('user_id',$user_id['id'])->where('goods_id',$data['goods_id'])->update(['is_collect'=>0]);
					return 1;
				}
			}
			return 0;
		} else {
			return -1;
		}
		
	}

	//我的发布
	public function myRelease(Request $request)
	{
		$thr_session = $request->param('thr_session');
		$user = Db::name('user')->where('thr_session',$thr_session)->find();
		if($user['id']) {
			$releaseGoods = Db::name('user')->alias('u')->join('goods g','u.id=g.seller_id')->field(['u.avatar_url','u.nick_name','g.goods_title','g.goods_img','g.goods_img2','g.goods_img3','g.id','g.look','g.goods_category','g.create_time','g.is_audit','g.is_buy'])->order('g.create_time','desc')->where('g.seller_id',$user['id'])->select();
			return json($releaseGoods);
		}
	}

	//我的收藏
	public function myCollect(Request $request)
	{
		$thr_session = $request->param('thr_session');
		$user = Db::name('user')->where('thr_session',$thr_session)->find();
		if($user['id']) {
			$collectGoods = Db::name('collect')->alias('c')->join('user u','u.id=c.user_id')->join('goods g','g.id=c.goods_id')->join('user h','h.id=g.seller_id')->field(['h.avatar_url','h.nick_name','h.is_approve','g.goods_title','g.goods_img','g.goods_img2','g.goods_img3','g.id','g.look','g.goods_category','g.create_time','g.is_buy'])->where('c.user_id',$user['id'])->order('c.id','desc')->where('c.is_collect',1)->select();
			return json($collectGoods);
		}
	}


	//发布商品
	public function issueGoods(Request $request)
	{
		$data = $request->param();
		$user_id = Db::name('user')->where('thr_session',$data['thr_session'])->find();
		if ($user_id) {
			if (!$user_id['linkman']||!$user_id['wechat_number']||!$user_id['phone_number']) {
				return -1;
			}
			$num = $data['num'];
			$goods_img2=null;
			$goods_img3=null;
			$pictures = $data['pictures'];
			if ($num==1) {
				$goods_img = $pictures[0];
			} else if($num==2) {
				$goods_img = $pictures[0];
				$goods_img2 = $pictures[1];
			} else {
				$goods_img = $pictures[0];
				$goods_img2 = $pictures[1];
				$goods_img3 = $pictures[2];
			}
			$create_time = time();
			$goods = [
				'goods_title' =>  $data['goods_title'],
				'seller_id' => $user_id['id'],
				'goods_prom_price' => $data['goods_prom_price'],
				'goods_abstract' => $data['goods_abstract'],
				'goods_img' => $goods_img,
				'goods_img2' => $goods_img2,
				'goods_img3' => $goods_img3,
				'goods_category' => $data['goods_category'],
				'create_time' => $create_time
			];
			$setGoods = Db::name('goods')->insert($goods);
			if ($setGoods) {
				return 1;
			} else {
				return 0;
			}
		}

	}

	//从列表页选择某类商品
	public function goodsCategory(Request $request)
	{
		$num = $request ->param('num');
		$category = $request ->param('category');
		if($category<0){
			$goods = Db::name('goods')->alias('g')->join('user u','g.seller_id=u.id')->field(['u.avatar_url','u.nick_name','u.is_approve','g.goods_title','g.goods_img','g.goods_img2','g.goods_img3','g.id','g.look','g.goods_category','g.create_time'])->where('g.is_audit',1)->where('g.is_buy',0)->limit($num)->order('g.create_time','desc')->select();
			$count = count(Db::name('goods')->where('is_audit',1)->where('is_buy',0)->select())+10;
			if ($num>=$count) {
				$goods=null;
			}
		} else {
			$goods = Db::name('goods')->alias('g')->join('user u','g.seller_id=u.id')->field(['u.avatar_url','u.nick_name','u.is_approve','g.goods_title','g.goods_img','g.goods_img2','g.goods_img3','g.id','g.look','g.goods_category','g.create_time'])->where('g.is_audit',1)->where('g.is_buy',0)->where('goods_category',$category)->limit($num)->order('g.create_time','desc')->select();
			$count = count(Db::name('goods')->where('is_audit',1)->where('is_buy',0)->where('goods_category',$category)->select())+10;
			if ($num>=$count) {
				$goods=null;
			}
		}
		return json($goods);
	}

	//商品图片上传
	public function goodsImagesUplaod(Request $request)
	{
		// 获取表单上传文件 例如上传了001.jpg
	    $file = request()->file('image');
	    $status = 0;
	    $addr=[];
	    // 移动到框架应用根目录/public/uploads/ 目录下
	    if($file){
	        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/goods_img/');
	        if($info){
	            // 成功上传后 获取上传信息
	            // 输出 jpg
	            // echo $info->getExtension();
	            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
	            $addr = '/easyto/public/uploads/goods_img/'.$info->getSaveName();
	            $status = 1;
	            // 输出 42a79759f284b767dfcb2a0197904287.jpg
	            // echo $info->getFilename(); 
	        }else{
	            // 上传失败获取错误信息
	            return $file->getError();
	        }
	    }
	    $result = [
	            	'status' => $status,
	            	'addr' => $addr
	            ];
	    return json($result);

	}

	//删除商品
	public function goodsDelete(Request $request)
	{
		$thr_session = $request->param('thr_session');
		$goods_id = $request->param('goods_id');
		$user = Db::name('user')->where('thr_session',$thr_session)->find();
		$goods = Db::name('goods')->where('id',$goods_id)->find();
		if($user['id'] == $goods['seller_id']) {
			$delete = Db::name('goods')->where('id',$goods['id'])->delete();
			return 1;
		} 
		return 0;
	}

	//被购买商品
	public function goodsBuy(Request $request)
	{
		$thr_session = $request->param('thr_session');
		$goods_id = $request->param('goods_id');
		$user = Db::name('user')->where('thr_session',$thr_session)->find();
		$goods = Db::name('goods')->where('id',$goods_id)->find();
		if($user['id'] == $goods['seller_id']) {
			if($goods['is_audit']!=1){
				return 2;
			}
			$delete = Db::name('goods')->where('id',$goods['id'])->update(['is_buy'=>1]);
			return 1;
		}
		return 0;
	}
}