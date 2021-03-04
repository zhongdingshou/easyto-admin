<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
use think\Db;

class User extends Base 
{
	public function user_list()
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 用户列表');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');

		$users = Db::name('user')->order('id desc')->paginate(5);
		$count = count($users);
		$this -> view -> assign('users', $users);
		$this -> view -> assign('count', $count);
		return $this -> view -> fetch('user_list');
	}
	
	public function deleteUser(Request $request)
	{
		$id = $request -> param('id');
		$user = Db::name('user')->where('id',$id)->delete();
	}

	public function user_edit(Request $request)
	{
		$this -> isLogin();
		$id = $request -> param('id');
		$user = Db::name('user')->where('id',$id)->find();
		$this -> assign('user', $user);
		return $this -> view -> fetch('user_edit');
	}

	public function editUser(Request $request)
	{
		$status=0;
		$message = '编辑失败！';

		$id = $request -> param('id');
		$data = $request -> param();
		
		$result = Db::name('user')->where('id',$id)->update($data);
		if ($result == true) {
			$message = '编辑成功！';
			$status = 1;
		}
		return [
        	'message' => $message,
        	'status'  => $status
        ];
	}

	//头像上传操作
	public function head_portrait(Request $request)
	{
		$file = $request->file('file');
    	if($file){
    		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/head_portrait/');
    		if($info){
    			$path = '/easyto/public/uploads/head_portrait/'.$info->getSaveName();
    			return json(['avatar_url'=>$path]);
    		}
    	}
	}
}