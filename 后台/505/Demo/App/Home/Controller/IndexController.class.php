<?php
namespace Home\Controller;
//尝试eventutil.preventdefault(event);
use Think\Controller;

class IndexController extends Controller {
	public function index() {
		$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>', 'utf-8');
		echo '<br/><a href ="' . U("index.php/home/Index/hello") . '" title="点我跳转">点我跳转</a>';
	}

	///显示demo///
	public function hello() {

		$admin = M('admins');
		$vo = $admin->select();
		$this->assign("list", $vo);
		$this->display();

	}
	public function _after_hello() {
		echo 'Hello World<br/><a href="' . U("index.php/home/Index/insert") . '">点我添加数据</a>';
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
				$this->success('新增成功', U("index.php/home/Index/hello"));
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
			$this->success('删除成功', U("index.php/home/Index/hello"));
		} else {
			$this->error('删除失败');
		}
	}

	public function datatest() {
		//实例化出来一个商品类(视图)
		$this->display();
		if (IS_POST) {
			if (isset($_POST['add'])) {
				$color = $_POST['colorname'];
				$add = D("GoodsView")->addcolor(2, $color);
				if ($add) {
					$this->success('操作完成', U('index.php/home/index/datatest'), 5);
				} else {
					$this->error('删除失败');
				}
			}
			if (isset($_POST['del'])) {
				$color = $_POST['colorname'];
				$add = D("GoodsView")->delcolor(2, $color);
				if ($add) {
					$this->success('操作完成', U('index.php/home/index/datatest'), 5);
				} else {
					$this->error('删除失败');
				}
			}
		}
		$da = D("goodsView");
		//
		$demo = $da->select(); //->where('goods.id=1')->select();
		dump($demo);
		//测试两个方法seecolor，seesize；分别用于查看每个商品的颜色分类和尺寸分类，同样的分类只显示1次，传入的id
		$goodid = 1;
		$color = $da->seecolor($goodid);
		dump($color);
		$goodid = 2;
		$size = $da->seesize($goodid);
		dump($size);
	}

//当有没有记录时跳转；
	public function datatest2() {
		$d1 = "红";
		$da = D("GoodsView")->where('goodstype.color="%s"', $d1)->count();
		dump($da);
		if ($da == '0') {
			$this->success('操作完成', U('index.php/home/index/datatest'), 5);
		}
	}
}