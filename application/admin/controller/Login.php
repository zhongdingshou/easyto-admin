<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Admin;
use think\Request;
use think\Session;

class Login extends Base
{
	// 登陆
	public function login()
	{
		$this -> alreadyLogin();
		$this -> assign('title','易起来 - 登录界面');
		$this -> assign('keywords','易起来登录界面，易起来小程序');
		$this -> assign('description','易起来小程序后台管理');
		return $this -> view -> fetch();
	}

	// 验证
	public function checkLogin(Request $request)
	{
		// 初始化
		$status = 0;
		$result = '';
		$data = $request -> param();

		// 验证规则
		$rule = [
			'username|用户名' => 'require',
			'password|密码' => 'require',
			'verify|验证码' => 'require|captcha'
		];

		// 提示信息
		$msg = [
			'username' => ['require' => '用户名不能为空，请检查！'],
			'password' => ['require' => '密码不能为空，请检查！'],
			'verify' => [
				'require' => '验证码不能为空，请检查！',
				'captcha' => '验证码错误！'
			]
		];

		// 验证通过返回true，反之返回自定义消息
		$result = $this -> Validate($data,$rule,$msg);

		if ($result === true) {
			$conditions = [
				'username' => $data['username'],
				'password' => md5($data['password']),
				'status' => 1
			];
			//查找该管理员
			$admin = Admin::get($conditions);
			if ($admin == null) {
				$result = '未找到该管理员，请检查用户名和密码，或者联系超级管理员！';
			} else {
				$status = 1;
				$result = '验证通过，3秒后进入管理界面';
				Session::set('admin_id', $admin -> id);
				Session::set('admin_info', $admin -> getData());

				//更新用户登录次数:自增1
                $admin -> setInc('login_count');
			}
		}

		//将信息返回给客户端
		return [
			'status' => $status,
			'message' => $result,
			'data' => $data
		];

	}
	
	//注销
	public function logout()
	{
		//退出前先更新登录时间字段,下次登录时就知道上次登录时间了
        Admin::update(['login_time'=>time()],['id'=> Session::get('admin_id')]);
		Session::delete('admin_id');
		Session::delete('admin_info');
		$this -> success('注销登陆，正在返回！', url('/admin/login/login'));
	}
	
}