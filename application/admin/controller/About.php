<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
use think\Db;


class About extends Base 
{
	//小程序信息列表
	public function about_list()
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 小程序信息列表');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');

		$about = Db::name('about')->order('create_time','desc')->paginate(9);
	    $count = count($about);
		$this -> view -> assign('about', $about);
		$this -> view -> assign('count', $count);
		return $this -> view -> fetch('about_list');
	}

	public function about_add()
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 小程序版本信息添加页');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');

		return $this -> view -> fetch('about_add');
	}

	public function deleteAbout(Request $request)
	{
		$id = $request -> param('id');
		$about = Db::name('about')->where('id',$id)->delete();
	}

	public function addAbout(Request $request)
	{
		$status = 0;
        $message = '添加失败';
		$title = $request -> param('title');
		$content = $request -> param('content');
		$create_time = time();
		$data = ['title'=>$title,'content'=>$content,'create_time'=>$create_time];
		$theabout = Db::name('about')->insert($data);
		if ($theabout) {
			$status = 1;
            $message = '添加成功';
		}
		return ['status'=>$status, 'message'=>$message];
	}

	public function about_edit(Request $request)
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 产品信息编辑');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');

		$id = $request -> param('id');
		$about = Db::name('about')->where('id',$id)->find();
	    $this->assign('about',$about);
		return $this -> view -> fetch('about_edit');
	}

	public function editAbout(Request $request)
	{
		$status=0;
		$message = '编辑失败！';

		$id = $request -> param('id');
		$data = $request -> param();
		
		$result = Db::name('about')->where('id',$id)->update($data);
		if ($result == true) {
			$message = '编辑成功！';
			$status = 1;
		} 
		return [
        	'message' => $message,
        	'status'  => $status
        ];
	}
}