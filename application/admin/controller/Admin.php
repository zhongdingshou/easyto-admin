<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Admin as AdminModel;
use think\Session;
use think\Request;

class Admin extends Base 
{
	//管理员列表
	public function admin_list()
	{
        $this -> isLogin();
		$this -> assign('title','易起来 - 管理员列表');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');

		$this -> view -> count = AdminModel::count();

        //判断当前是不是admin用户
        //先通过session获取到用户登陆名
        $userName = Session::get('admin_info.username');
        $permissions = Session::get('admin_info.permissions');
        if ($permissions == '0') {
            $list = AdminModel::all();  //admin用户可以查看所有记录,数据要经过模型获取器处理
        } else {
            //为了共用列表模板,使用了all(),其实这里用get()符合逻辑,但有时也要变通
            //非admin只能看自己信息,数据要经过模型获取器处理
            $list = AdminModel::all(['username'=>$userName]);
        }


        $this -> view -> assign('list', $list);
        //渲染管理员列表模板
		return $this -> view -> fetch('admin_list');
	}


	//管理员状态变更
    public function setStatus(Request $request)
    {
        $admin_id = $request -> param('id');
        $result = AdminModel::get($admin_id);
        if($result->getData('status') == 1) {
            AdminModel::update(['status'=>0],['id'=>$admin_id]);
        } else {
            AdminModel::update(['status'=>1],['id'=>$admin_id]);
        }
    }

    //管理员添加界面
	public function admin_add()
	{
        $this -> isLogin();
        if (Session::get('admin_info.permissions')!=0) {
            $this ->error('你的权限不足，正在返回欢迎页',url('/admin/index/index'));
        }
		$this -> assign('title','易起来 - 管理员添加');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');
		return $this -> view -> fetch('admin_add');
	}

	//管理员添加操作
    public function addAdmin(Request $request)
    {
        $data = $request -> param();
        $status = 0;
        $message = '添加失败，用户名密码长度不符合';

        $rule = [
            'username|用户名' => "require|min:3|max:10",
            'password|密码' => "require|min:3|max:16",
            'email|邮箱' => 'require|email'
        ];
        $result = $this -> validate($data, $rule);

        if ($result === true) {
            $admin= AdminModel::create($request->param());
            if ($admin !== null) {
                $status = 1;
                $message = '添加成功';
            }
        }
        return ['status'=>$status, 'message'=>$message];
    }

    //渲染编辑管理员界面
    public function admin_edit(Request $request)
    {
        $this -> isLogin();
		$this -> assign('title','易起来 - 管理员编辑');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');

		$admin_id = $request -> param('id');
        $result = AdminModel::get($admin_id);
        $this -> view -> assign('admin_info',$result->getData());
        return $this -> view -> fetch('admin_edit');
    }

    //更新数据操作
    public function editAdmin(Request $request)
    {
        //获取表单返回的数据
		$data = $request -> param();
        //$param = $request -> param();

        //去掉表单中为空的数据,即没有修改的内容
        // foreach ($param as $key => $value ){
        //     if (!empty($value)){
        //         $data[$key] = $value;
        //     }
        // }
        // 
        if (Session::get('admin_info.username') != 'admin') {
            if ($data['id']==1) {
                return ['status'=>0, 'message'=>'admin超级管理员信息你无权编辑！','abc' => $data];
            }
        }
        if (empty($data['password'])) {
            $data = $request->except('password');
        }
        $result = AdminModel::update($data, ['id' => $data['id']]);

        //如果是admin用户,更新当前session中用户信息admin_info中的角色permissions,供页面调用
        // if (Session::get('admin_info.name') == 'admin') {
        //     Session::set('admin_info.permissions', $data['permissions']);
        // }


        if ($result == true ) {
            return ['status'=>1, 'message'=>'更新成功！','abc' => $data];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查！','abc' => $data];
        }
    }

    //删除操作
    public function deleteAdmin(Request $request)
    {
        $admin_id = $request -> param('id');
        AdminModel::update(['is_delete'=>1],['id'=> $admin_id]);
        AdminModel::destroy($admin_id);
    }

    //恢复删除操作
    public function unDelete()
    {
        AdminModel::update(['delete_time'=>NULL],['is_delete'=>1]);
    }

    //检测用户名是否可用
    public function checkAdminName(Request $request)
    {
        $userName = trim($request -> param('username'));
        $status = 1;
        $message = '用户名可用！';
        if (AdminModel::get(['username'=> $userName])) {
            //如果在表中查询到该用户名
            $status = 0;
            $message = '用户名重复,请重新输入！';
        }
        return ['status'=>$status, 'message'=>$message];
    }

    //检测用户邮箱是否可用
    public function checkAdminEmail(Request $request)
    {
        $userEmail = trim($request -> param('email'));
        $status = 1;
        $message = '邮箱可用！';
        if (AdminModel::get(['email'=> $userEmail])) {
            //查询表中找到了该邮箱,修改返回值
            $status = 0;
            $message = '邮箱重复,请重新输入！';
        }
        return ['status'=>$status, 'message'=>$message];
    }
}