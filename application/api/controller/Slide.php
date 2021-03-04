<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\Db;


class Slide extends Base
{
	public function getSlide()
	{
		$slide_urls = Db::name('slide')->field('slide_url')->where('is_show',1)->order('slide_order')->select();

		return json($slide_urls);
	}
}