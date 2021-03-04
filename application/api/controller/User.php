<?php 
namespace app\api\controller;
use app\api\controller\Base;
use think\Request;
use think\Db;

class User extends Base
{
	private function decryUser($iv,$sess_key,$encData,$appid){
		import('wxBizDataCrypt', EXTEND_PATH);	
		$pc = new \WXBizDataCrypt($appid, $sess_key);
		$errCode = $pc->decryptData($encData, $iv, $data );	
		return $data;
	}

 	public function getSess(Request $request)
 	{
 		$data = $request -> param();
 		$code    = $data['code'];
		$encData = $data['encryptedData'];
		$iv      = $data['iv'];
		$appid   = config('easyto.APPID');
		$secret  = config('easyto.SECRET');

		//解密数据
		$url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
		$res = file_get_contents($url);
		$res = json_decode($res,true);

		$ret = $this->decryUser($iv,$res['session_key'],$encData,$appid);
		$ret = json_decode($ret,true);

		$thr_session = md5($ret['openId'].$res['session_key']);
		
		$data = [
			'nick_name'=>$ret['nickName'],
			'avatar_url'=>$ret['avatarUrl'],
			'open_id'  =>$ret['openId'],
			'session_key'=>$res['session_key'],
			'thr_session' => $thr_session
		];

		$id = Db::name('user')->where(['open_id'=>$ret['openId']])->value('id');
		
		if($id){
			$ins = Db::name('user')->where(['open_id'=>$ret['openId']])->update($data);
		}else{

			$ins  = Db::name('user')->insert($data);		
		}
		if($ins){
			return $thr_session;
		}
			
	}

	public function editUserInfo(Request $request)
	{
		$data = $request->param();
		$user_id = Db::name('user')->where('thr_session',$data['thr_session'])->find();
		if ($user_id) {
			$data = ['linkman' => $data['linkman'], 'wechat_number' => $data['wechat_number'], 'phone_number' => $data['phone_number']];
			$editUser = Db::name('user')->where('id',$user_id['id'])->update($data);
			if ($editUser) {
				return 1;
			} 
			return 0;
		}
	}

	public function getUser(Request $request)
	{
		$data = $request->param();
		$user_id = Db::name('user')->where('thr_session',$data['thr_session'])->find();
		if ($user_id) {
			$theUser = Db::name('user')->where('id',$user_id['id'])->field(['linkman','wechat_number','student_id','phone_number','is_approve'])->find();
			return json($theUser);
		} else {
			return 0;
		}
	}
	
	//认证
	public function approve(Request $request)
	{
		$compellation = $request -> param('compellation');
		$thr_session = $request -> param('thr_session');
		$student_id = $request -> param('student_id');

		$thr_session = Db::name('user')->where('thr_session',$thr_session)->find();
		$student = Db::name('user')->where('student_id',$student_id)->find();

		if ($thr_session) {
			if (!$thr_session['student_id']) {
				if (!$student) {
					$data=['compellation'=>$compellation,'student_id'=>$student_id,'is_approve'=>1];
					$approve = Db::name('user')->where('id',$thr_session['id'])->update($data);
					if ($approve) {
						return 1;
					}
					return 0;
				} else {
					if ($student['compellation']===$compellation) {
						$del1 = Db::name('user')->where('id',$thr_session['id'])->delete();
						$del2 = Db::name('lookandtalk')->where('user_id',$thr_session['id'])->delete();
						$del3 = Db::name('praiseandtread')->where('user_id',$thr_session['id'])->delete();
						$del4 = Db::name('say')->where('user_id',$thr_session['id'])->update(['user_id'=> $student['id']]);
						$del5 = Db::name('goods')->where('seller_id',$thr_session['id'])->update(['seller_id'=> $student['id']]);
						$del6 = Db::name('collect')->where('user_id',$thr_session['id'])->update(['user_id'=> $student['id']]);
						$del7 = Db::name('advice')->where('user_id',$thr_session['id'])->update(['user_id'=> $student['id']]);
						return json($student['thr_session']);
					} else {
						return -1;
					}
				}
			} else {
				if (!$student) {
					$data=['compellation'=>$compellation,'student_id'=>$student_id];
					$approve = Db::name('user')->where('id',$thr_session['id'])->update($data);
					return 2;
				} else {
					if($thr_session['student_id']==$student['student_id']) {
						$data=['compellation'=>$compellation];
						$approve = Db::name('user')->where('id',$thr_session['id'])->update($data);
						return 2;
					} else {
						if ($student['compellation']===$compellation) {
							$del1 = Db::name('user')->where('id',$thr_session['id'])->delete();
							$del2 = Db::name('lookandtalk')->where('user_id',$thr_session['id'])->delete();
							$del3 = Db::name('praiseandtread')->where('user_id',$thr_session['id'])->delete();
							$del4 = Db::name('say')->where('user_id',$thr_session['id'])->update(['user_id'=> $student['id']]);
							$del5 = Db::name('goods')->where('seller_id',$thr_session['id'])->update(['seller_id'=> $student['id']]);
							$del6 = Db::name('collect')->where('user_id',$thr_session['id'])->update(['user_id'=> $student['id']]);
							$del7 = Db::name('advice')->where('user_id',$thr_session['id'])->update(['user_id'=> $student['id']]);
							return json($student['thr_session']);
						} else {
							return -1;
						}
					}
				}
			}
		} else {
			return 0;
		}
	}

	public function getApprove(Request $request)
	{
		$thr_session = $request -> param('thr_session');
		$student_id = $request -> param('student_id');
		$thr_session = Db::name('user')->where('thr_session',$thr_session)->find();
		$student_id = Db::name('user')->where('student_id',$student_id)->find();
		if ($thr_session['id']==$student_id['id']) {
			$approveInfo = Db::name('user')->where('id',$student_id['id'])->field(['student_id','compellation'])->find();
			return json($approveInfo);
		} 
		return 0;
	}

	//获取产品信息
	public function getAbout()
	{
		$about = Db::name('about')->order('create_time','desc')->select();
		return json($about);
	}

	//发送反馈信息
	public function satAdvice(Request $request)
	{
		$thr_session = $request -> param('thr_session');
		$advice = $request -> param('advice');
		$user = Db::name('user')->where('thr_session',$thr_session)->find();
		if($user) {
			$create_time = time();
			$theadvice = Db::name('advice')->insert(['user_id'=>$user['id'],'advice'=>$advice,'create_time'=>$create_time]);
			return 1;
		}
		return 0;
	}
}