<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller {
	public function index() {
		redirect(U('home/index/login'), 5, '页面跳转中。。。');
		//$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
	}

	//http://localhost/505/index.php/home/Index/login.html
	public function login() {
		$this->display();
		if (IS_POST) {
			$user = M('users');
			$useraccount = $_POST['useraccount'];
			$pwd = $_POST['password'];
			//这里使用md5加密
			$password = md5($pwd);
			if ($useraccount == "" || $password == "") {
				echo "<script>alert('请输入用户名和密码！');history.go(-1);</script>";
			} else {
				$result = $user->where('account="%s" and password="%s"', $useraccount, $password)->select();
				if ($result) {
					//将用户账号存入session
					$_SESSION['useraccount'] = $useraccount;
					$this->success('登录成功', U("index.php/home/Index/homepage"));
				} else {
					$this->error('登录失败');
				}
			}
		}
	}

	public function _after_login() {
		echo '<br/><a href="' . U("index.php/home/Index/register") . '">注册</a>';
	}

	public function register() {
		$this->display();
		if (IS_POST) {
			//由于新版的thinkphp验证码生成后不是简单的md5后存入session，所以不能用如下的方式比较了
			//$_SESSION['verify_code']!=md5($_POST['verify'])
			//封装了函数后不方便调用，所以直接使用框架带的$verify->check($code,$id)函数验证
			$verify = new \Think\Verify();
			if (!$verify->check(strtolower($_POST['verify']), $id)) {
				$this->error('验证码输入有误');
			}
			$user = M('users');
			$user->name = $_POST['name'];
			$user->account = $_POST['account'];
			//md5后存入数据库
			$pwd = $_POST['password1'];
			$user->password = md5($pwd);
			$user->email = $_POST['email'];
			$user->phonenumber = $_POST['phone'];
			$user->address = $_POST['address'];
			if ($user->where('account="%s"', $user->account)->select()) {
				$this->error('您输入的账号已存在');
			}
			$result = $user->add();
			if ($result) {
				$this->success('注册成功', U("index.php/home/Index/login"));
			} else {
				$this->error('注册失败');
			}
		}
	}

	public function verify() {
		$verify = new \Think\Verify();
		//设置验证码的长度为4
		$verify->length = 4;
		//设置验证码不生成噪点，不然过于凌乱
		$verify->useNoise = false;
		$verify->entry();
	}

	// public function check_verify($code,$id=''){
	// 	$verify=new \Think\Verify();
	// 	return $verify->check($code,$id);
	// }

	public function homepage() {
		$da = M("goods");
		//滚动部分
		$rolllist = $da->limit(3)->select();
		$this->assign("rolllist", $rolllist);
		if (isset($_GET['classify'])) {
//判断用户是否点击分类导航
			$classifyname = I('request.classify');
			$temp = M("goodsclassify");
			$classifyid = $temp->where('classifyname="%s"', $classifyname)->getField('id');

			$all = $da->where('classifyid="%d"', $classifyid)->select();
			// $all=$da->where("classifyid=1")->select();
			$this->assign("list", $all);
			$this->display();
		} else if (isset($_GET['search'])) {
//判断用户是否搜索相关商品
			$keyword = I('request.search');
			$all = $da->query("select * from goods where name like '%" . $keyword . "%'");
			$this->assign("list", $all);
			$this->display();
		} else {
//如果是直接加载首页的话
			$all = $da->select();
			$this->assign("list", $all);

			$this->display();
		}
	}

	public function people() {
		if (isset($_SESSION['useraccount'])) {
			//获取当前用户账号，来找出此用户的id
			//getField()方法只返回该字段第一行的值
			$users = M('users');
			$userid = $users->where("account=%s", $_SESSION['useraccount'])->getField('id');
			//使用订单模型
<<<<<<< HEAD
			$da = D('OrdersView');
			if (isset($_GET['state'])) {
				$state = I('request.state');
				$all = $da->where("userid=%d and state=%d", $userid, $state)->select();
			} else {
				$all = $da->where("userid=%d", $userid)->select();
			}
			$this->assign("list", $all);
=======
			$da=D('OrdersView');
			if(isset($_GET['state'])){
				$state=I('request.state');
				$this->assign("state",$state);
				$all=$da->where("userid=%d and state=%d",$userid,$state)->select();
			}else{
				$all=$da->where("userid=%d",$userid)->select();
			}

			$this->assign("list",$all);
>>>>>>> origin/master
			$this->display();
		} else {
			$this->error('请登录');
		}
	}

	public function details() {
		if (isset($_GET['goodsid'])) {
			$goodsid = I('request.goodsid');
			$da = D('GoodsView');
			$da2 = M('goods');
			$good = $da2->where("goods.id=%d", $goodsid)->select();

			$color = $da->seecolor($goodsid);
			//设置第一项为默认选中
			$color[0]["checked"] = "checked";
			$size = $da->seesize($goodsid);
			$size[0]["checked"] = "checked";
			$this->assign("colors", $color);
			$this->assign("sizes", $size);
			$this->assign("good", $good);
			$this->display();
		} else if (isset($_GET['add'])) {
			$goodsid = I('request.add');

<<<<<<< HEAD
		} else {
			$this->error('请稍后', 'homepage', 0);
		}
	}

	public function chart() {
		$this->display();
	}

	public function pay() {
		if (isset($_SESSION['useraccount'])) {
			$user = M('users');
			$single = $user->where("account=%s", $_SESSION['useraccount'])->select();
			$this->assign("single", $single);
			if (isset($_GET['fromdetail'])) {
				//支付详情页中的商品
			} else {
=======

		}else{
			$this->error('请稍后','homepage',0);
		}
	}

	public function chart(){
		if(isset($_SESSION['useraccount'])){
			if(isset($_GET['delete'])){
				$carts=M('cart');
				$delete=$carts->where("id=%d",$_GET['delete'])->delete();
			}
				$user=M('users');
				$userid=$user->where("account=%s",$_SESSION['useraccount'])->getField('id');

				$carts=D('CartsView');
				$goods=$carts->where("userid=%d",$userid)->select();
				$this->assign("carts",$goods);

				//计算出总钱数
				$singleprice=$carts->where("userid=%d",$userid)->field('price')->select();
				$allmoney=0;
				foreach ($singleprice as $value) {
					$allmoney=$allmoney+$value['price']+0;
				}
				$this->assign("allprice",$allmoney);
		}else{
			$this->error('请登录','login',3);
		}
		$this->display();
	}

	public function pay(){
		if(isset($_SESSION['useraccount'])){
			$user=M('users');
			$single=$user->where("account=%s",$_SESSION['useraccount'])->select();
			$userid=$user->where("account=%s",$_SESSION['useraccount'])->getField('id');
			$this->assign("single",$single);
			if(isset($_GET['fromdetail'])){
				$goodsid=$_GET['fromdetail'];
				$good=M('goods');
				$order=$good->where("id=%d",$goodsid)->select();
				$allmoney=$good->where("id=%d",$goodsid)->getField('price');
				$this->assign("carts",$order);
				$this->assign("allprice",$allmoney);
			}else{
>>>>>>> origin/master
				//支付购物车中全部内容
				$carts=D('CartsView');
				$goods=$carts->where("userid=%d",$userid)->select();
				$this->assign("carts",$goods);

				//计算出总钱数
				$singleprice=$carts->where("userid=%d",$userid)->field('price')->select();
				//dump($singleprice);
				$allmoney=0;
				foreach ($singleprice as $value) {
					$allmoney=$allmoney+$value['price']+0;
				}
				//$allmoney=array_sum($singleprice);
				$this->assign("allprice",$allmoney);
			}
		} else {
			$this->error('请登录', 'login', 3);
		}
		$this->display();
	}

	public function goodsajax() {
		$name = I('post.name');
		$color = I('post.color');
		$size = I('post.size');
		if($color!=""){
			if($size!=""){
				$go = D('GoodsView')->where('name="' . $name . '" and color="' . $color . '" and size="' . $size . '"')->find();
			}
			else{
				$go = D('GoodsView')->where('name="' . $name . '" and color="' . $color . '"')->find();
			}
		}
		else{
			if($size!=""){
				$go = D('GoodsView')->where('name="' . $name . '" and size="' . $size . '"')->find();
			}
			else{
				$go = D('GoodsView')->where('name="' . $name . '"')->find();
			}
		}
		$data = $go["goodsleft"];
		$this->ajaxReturn($data);//{"data":"$go["goodsleft"]"}
	}

	public function goodsajax2(){
		$name = I('post.name');
		$color = I('post.color');
		$size = I('post.size');
		if($color!=""){
			if($size!=""){
				$go = D('GoodsView')->where('name="' . $name . '" and color="' . $color . '" and size="' . $size . '"')->find();
			}
			else{
				$go = D('GoodsView')->where('name="' . $name . '" and color="' . $color . '"')->find();
			}
		}
		else{
			if($size!=""){
				$go = D('GoodsView')->where('name="' . $name . '" and size="' . $size . '"')->find();
			}
			else{
				$go = D('GoodsView')->where('name="' . $name . '"')->find();
			}
		}
		if(isset($_SESSION['useraccount'])){
			$user=M('users');
			$userid=$user->where("account=%s",$_SESSION['useraccount'])->getField('id');
			$base=M('cart');
			$base->userid=$userid;
			$base->goodstypeid=$go['type_id'];
			$base->goodsnum=1;
			$info=$base->add();
			if($info){
				$data="成功将商品名：".$name." 颜色：".$color." 尺寸：".$size."  1件加入到购物车";
				$this->ajaxReturn($data,'json');
			}
			else{
				$data="加入失败，请稍后再试";
				$this->ajaxReturn($data,'json');
			}

		}else {
			$data="请先登录再加入购物车";
			$this->ajaxReturn($data,'json');
		}

	}
}