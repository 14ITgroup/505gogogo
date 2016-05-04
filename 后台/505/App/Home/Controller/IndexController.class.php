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

 //    public function _after_login() {
	// 	echo '<br/><a href="' . U("index.php/home/Index/register") . '">注册</a>';
	// }

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
				echo '<script>alert("注册成功")</script>';
				$this->success('成功', U("index.php/home/Index/login"),0);
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
			//获取当前用户账号，来找出此用户的id
			//getField()方法只返回该字段第一行的值
			$users=M('users');
			$userid=$users->where("account=%s",$_SESSION['useraccount'])->getField('id');
			//使用订单模型
			$da=D('OrdersView');
			if(isset($_GET['state'])){
				$state=I('request.state');
				$this->assign("state",$state);
				$all=$da->where("userid=%d and state=%d",$userid,$state)->select();
			}else{
				$all=$da->where("userid=%d",$userid)->select();
			}

			$this->assign("list",$all);
			$this->display();
		}else{
			$this->error('请登录');
		}	
	}

	public function details(){
		if(isset($_GET['goodsid'])){
			$goodsid=I('request.goodsid');
			$da=D('GoodsView');
			$da2=M('goods');
			$good=$da2->where("goods.id=%d",$goodsid)->select();
			
			$color=$da->seecolor($goodsid);
			//设置第一项为默认选中
			$color[0]["checked"]="checked";
			$size=$da->seesize($goodsid);
			$size[0]["checked"]="checked";
			$this->assign("colors",$color);
			$this->assign("sizes",$size);
			$this->assign("good",$good);
			$this->display();
		}else if(isset($_GET['add'])){
			$goodsid=I('request.add');


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
				$userid=$user->where("account='%s'",$_SESSION['useraccount'])->getField('id');

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
			if(isset($_GET['name'])){//&&isset($_GET['color'])&&isset($_GET['size'])
				$goodsname=$_GET['name'];
				$goodscolor=$_GET['color'];
				$goodssize=$_GET['size'];
				$good=D('GoodsView');

				if($goodscolor!=""){
					if($goodssize!=""){
						$order=$good->where("name='%s' and color='%s' and size='%s'",$goodsname,$goodscolor,$goodssize)->select();
						$allmoney=$good->where("name='%s' and color='%s' and size='%s'",$goodsname,$goodscolor,$goodssize)->getField('price');
					}else{
						$order=$good->where("name='%s' and color='%s'",$goodsname,$goodscolor)->select();
						$allmoney=$good->where("name='%s' and color='%s'",$goodsname,$goodscolor)->getField('price');
					}
				}
				else{
					if($goodssize!=""){
						$order=$good->where("name='%s' and size='%s'",$goodsname,$goodssize)->select();
						$allmoney=$good->where("name='%s' and size='%s'",$goodsname,$goodssize)->getField('price');
					}else{
						$order=$good->where("name='%s'",$goodsname)->select();
						$allmoney=$good->where("name='%s'",$goodsname)->getField('price');
					}
				}
				$this->assign("carts",$order);
				$this->assign("allprice",$allmoney);
			}else{
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
		}else{
			$this->error('请登录','login',2);
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

	public function buildorderajax(){
		//这个数组检测无误
		$typeids = I('post.typeids');
		//这个bug找了好久
		//原来是array_count_value()和array_unique()函数获得的数组下标并不是有序渐进的
		//而是类似Array ( [1] => 2 [31] => 1 ) Array ( [0] => 1 [2] => 31 ) 
		//所以我们要调整为从0开始递增1的数组

		//对数组中的所有值进行计数
		$uniquecount=array_count_values($typeids);
		//去除数组中的重复元素
		$unique=array_unique($typeids);

		$realuniquecount=array();
		for($i=0,$j=0;$i<count($uniquecount);){
			if($uniquecount[$j]!=0){
				$realuniquecount[$i]=$uniquecount[$j];
				$i++;
			}
			$j++;
		}

		$realunique=array();
		for($i=0,$j=0;$i<count($unique);){
			if($unique[$j]!=0){
				$realunique[$i]=$unique[$j];
				$i++;
			}
			$j++;
		}
		//修改之后的意思才是$unique[i]对应的值出现的次数是$uniquecount[i]
		if(isset($_SESSION['useraccount'])){//判断用户登录
			$user=M('users');
			$userid=$user->where("account=%s",$_SESSION['useraccount'])->getField('id');

			$good=D('GoodsView');
			$type=M('goodstype');
			$order=M('orders');
			$cart=M('cart');

			$length=sizeof($realunique);
			// echo '<script type="text/javascript"> alert("文件上传失败！\n\n错误原因：'.$realuniquecount[1].'")</script>';
			// $inf="提示信息:\n";
			for($i=0;$i<$length;$i++){
				//判断库存是否满足用户需求
				$goodsleft=$good->where("goodstype.id=%d",$realunique[$i])->getField('goodsleft');
				//获取单价
				$singleprice=$good->where("goodstype.id=%d",$realunique[$i])->getField('price');
				if($goodsleft-$uniquecount[$i]>0){//如果库存足够，执行库存减去goodsnum的操作和生成订单的操作
				
					//库存减少，现有量减去用户购买量
					$data['goodsleft'] = $goodsleft-$realuniquecount[$i];
					$result1=$type->where("id=%d",$realunique[$i])->save($data);

					//生成订单
					$create['userid']=$userid;
					$create['goodstypeid']=$realunique[$i];
					$create['goodsnum']=$realuniquecount[$i];
					$create['totalprice']=$singleprice*$realuniquecount[$i];
					$create['addtime']=date('y-m-d H:i:s',time());
					$result2=$order->add($create);
					$result=$result1*$result2;

					$name=$good->where("goodstype.id=%d",$realunique[$i])->getField('name');
					$color=$good->where("goodstype.id=%d",$realunique[$i])->getField('color');
					$size=$good->where("goodstype.id=%d",$realunique[$i])->getField('size');
					if($result){
						echo '<script type="text/javascript"> alert("成功支付商品:'.$name.' 颜色:'.$color.' 尺寸:'.$size.'")</script>';
						//去除购物车中的该项
						$cart->where("goodstypeid=%d",$realunique[$i])->delete();
					}
				}else{//如果库存不足
					$name=$good->where("goodstype.id=%d",$realunique[$i])->getField('name');
					$color=$good->where("goodstype.id=%d",$realunique[$i])->getField('color');
					$size=$good->where("goodstype.id=%d",$realunique[$i])->getField('size');
					echo '<script type="text/javascript"> alert("非常抱歉,商品:'.$name.' 颜色:'.$color.' 尺寸:'.$size.' 库存不足")</script>';
				}
			}
			//$this->ajaxReturn($inf,'json');
		}else{
			$this->error('请登录','login',1);
		}
	}
}

?>