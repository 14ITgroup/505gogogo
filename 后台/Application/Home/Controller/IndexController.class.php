<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller {
	public function index() {
		$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>', 'utf-8');
		echo '<br/><a href ="http://localhost/index.php/home/index/hello" title="点我跳转">点我跳转</a>';
	}

	///显示demo///
	public function hello() {

		$admin = M('admins');
		$vo = $admin->select();
		$this->assign("list", $vo);
		$this->display();

	}
	public function _after_hello() {
		echo 'Hello World<br/><a href="http://localhost/index.php/home/index/insert">点我添加数据</a>';
	}
	///插入demo///

	public function insert() {
		$this->display();
		if (IS_POST) {
			$admin = M('admins');
			$admin->name = $_POST['name'];
			$admin->account = $_POST['account'];
			$admin->password = $_POST['password'];
			$admin->power = $_POST['power'] + 0;
			$result = $admin->add();
			if ($result) {
				$this->success('新增成功', '/index.php/home/index/hello');
			} else {
				$this->error('新增失败');
			}

		}
	}
	///删除demo///
	public function delete() {
		$id = I('request.id');
		$admin = M('admins');
		$result = $admin->delete($id);
		if ($result) {
			$this->success('删除成功', '/index.php/home/index/hello');
		} else {
			$this->error('删除失败');
		}
	}
}