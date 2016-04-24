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
    		$username=$_POST['username'];
    		$pwd=$_POST['password'];
    		//这里使用md5加密
    		$password=md5($pwd);
    		if($username==""||$password==""){
    			echo "<script>alert('请输入用户名和密码！');history.go(-1);</script>";
    		}else{
    			$result=$user->where('account="%s" and password="%s"',$username,$password)->select();
    			if($result){
    				$this->success('登陆成功', U("index.php/home/Index/homepage"));
    			}else{
    				$this->error('登陆失败');
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

	public function homepage(){
		$this->display();
	}
}