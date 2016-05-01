<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
    	redirect(U('home/index/login'), 5, '页面跳转中。。。');
        //$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }

    //http://localhost/505/index.php/home/Index/login.html
    public function login(){
    	$this->display();
    	if(IS_POST){
    		$user = M('users');
    		$useraccount=$_POST['useraccount'];
    		$pwd=$_POST['password'];
    		//这里使用md5加密
    		$password=md5($pwd);
    		if($useraccount==""||$password==""){
    			echo "<script>alert('请输入用户名和密码！');history.go(-1);</script>";
    		}else{
    			$result=$user->where('account="%s" and password="%s"',$useraccount,$password)->select();
    			if($result){
    				//将用户账号存入session
    				$_SESSION['useraccount'] = $useraccount;
    				$this->success('登录成功', U("index.php/home/Index/homepage"));
    			}else{
    				$this->error('登录失败');
    			}
    		}
    	}
    }

    public function _after_login() {
		echo '<br/><a href="' . U("index.php/home/Index/register") . '">注册</a>';
	}

	public function register(){
		$this->display();
		if(IS_POST){
			//由于新版的thinkphp验证码生成后不是简单的md5后存入session，所以不能用如下的方式比较了
			//$_SESSION['verify_code']!=md5($_POST['verify'])
			//封装了函数后不方便调用，所以直接使用框架带的$verify->check($code,$id)函数验证
			$verify=new \Think\Verify();
			if(!$verify->check(strtolower($_POST['verify']),$id)){
				$this->error('验证码输入有误');
			}
			$user=M('users');
			$user->name=$_POST['name'];
			$user->account=$_POST['account'];
			//md5后存入数据库
			$pwd=$_POST['password1'];
			$user->password=md5($pwd);
			$user->email=$_POST['email'];
			$user->phonenumber=$_POST['phone'];
			$user->address=$_POST['address'];
			if($user->where('account="%s"',$user->account)->select()){
				$this->error('您输入的账号已存在');
			}
			$result=$user->add();
			if ($result) {
				$this->success('注册成功', U("index.php/home/Index/login"));
			} else {
				$this->error('注册失败');
			}
		}
	}

	public function verify(){
		$verify=new \Think\Verify();
		//设置验证码的长度为4
		$verify->length=4;
		//设置验证码不生成噪点，不然过于凌乱
		$verify->useNoise=false;
		$verify->entry();
	}

	// public function check_verify($code,$id=''){
	// 	$verify=new \Think\Verify();
	// 	return $verify->check($code,$id);
	// }

	public function homepage(){
		$da=M("goods");
		//滚动部分
		$rolllist=$da->limit(3)->select();
		$this->assign("rolllist", $rolllist);
		if(isset($_GET['classify'])){//判断用户是否点击分类导航
			$classifyname = I('request.classify');
			$temp=M("goodsclassify");
			$classifyid=$temp->where('classifyname="%s"',$classifyname)->getField('id');

			$all=$da->where('classifyid="%d"',$classifyid)->select();
			// $all=$da->where("classifyid=1")->select();
			$this->assign("list", $all);
			$this->display();
		}
		else if(isset($_GET['search'])){//判断用户是否搜索相关商品
			$keyword=I('request.search');
			$all=$da->query("select * from goods where name like '%".$keyword."%'");
			$this->assign("list", $all);
			$this->display();
		}
		else{//如果是直接加载首页的话
			$all=$da->select();
			$this->assign("list", $all);

			$this->display();
		}
	}

	public function people(){
		if(isset($_SESSION['useraccount'])){
			$this->display();
		}else{
			$this->error('请登录');
		}	
	}

	public function chart(){
		$this->display();
	}

	public function pay(){
		$this->display();
	}
}