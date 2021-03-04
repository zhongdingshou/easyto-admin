<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
use think\Db;

class Slide extends Base 
{
	//幻灯片列表
	public function slide_list()
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 幻灯片列表');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');

		$slides = Db::name('slide')->paginate(3);
	    $this->assign('slides',$slides);
		return $this -> view -> fetch('slide_list');
	}

	//幻灯片添加界面
	public function slide_add()
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 幻灯片添加');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');
		return $this -> view -> fetch('slide_add');
	}
	
	//幻灯片添加操作
	public function addSlide(Request $request)
	{
		// 初始化
		$data = $request -> param();
		$status = 1;
		$message = '添加成功';

        $rule = [
            'slide_title|幻灯片标题' => "require",
            'slide_order|幻灯片顺序' => "require",
            'slide_url|幻灯片链接' => 'require',
        ];
        $result = $this -> validate($data, $rule);

        if ($result === true) {

        	$slide = Db::name('slide')->insert($data);
        	if ($slide==null) {
        		$status = 0;
				$message = '添加失败！';
        	}
        	return [
        		'message' => $message,
        		'status'  => $status
        	];
        }
	}

	//幻灯片上传操作
	public function uploads(Request $request)
	{
		$file = $request->file('file');
    	if($file){
    		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/slide/');
    		if($info){
    			$path = '/easyto/public/uploads/slide/'.$info->getSaveName();
    			return json(['slide_url'=>$path]);
    		}
    	}
	}

	//幻灯片编辑界面
	public function slide_edit(Request $request)
	{
		$this -> isLogin();
		$this -> assign('title','易起来 - 幻灯片编辑');
		$this -> assign('keywords','易起来后台管理系统，易起来小程序');
		$this -> assign('description','易起来后台管理系统');

		$id = $request -> param('id');
		$slide = Db::name('slide')->where('id',$id)->find();
	    $this->assign('slide',$slide);
		return $this -> view -> fetch('slide_edit');
	}

	//幻灯片编辑操作
	public function editSlide(Request $request)
	{
		$status=0;
		$message = '编辑失败！';

		$id = $request -> param('id');
		$data = $request -> param();
		
		$result = Db::name('slide')->where('id',$id)->update($data);
		if ($result == true) {
			$message = '编辑成功！';
			$status = 1;
		} 
		return [
        	'message' => $message,
        	'status'  => $status
        ];
	}

	//幻灯片删除操作
	public function deleteSlide(Request $request)
	{
		$id = $request -> param('id');
		$result = Db::name('slide')->where('id',$id)->delete();
	}
}