<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller
{
	// 自动运行方法,判断是否登录
	public function _initialize(){
    	//当前为登录页时不执行该操作
        if(ACTION_NAME!="login"){
        	//判断session['adminaccount']是否为空，是的话跳转到登陆界面
			if(!isset($_SESSION['adminaccount'])){
				echo "<script>alert('用户未登录或登陆超时');</script>";
				$this->redirect("/Home/index/login");
	        }
	        else{
	        	//显示登录的管理员帐号
	        	$adminaccount=$_SESSION['adminaccount'];
				$admin= M('admins')->where("account=".$adminaccount)->select();
				$name=$admin[0]['name'];
	        	$this->assign("name",$name);
	        }
		}
    }
    //后台首页
    public function index(){
    	//读取用户数据
		$vo = M('users')->order('id desc')->select();
		$this->assign("list", $vo);
		//使用OrdersView模型读取订单有关数据
		$order=D('OrdersView')->where('orders.state=0')->select();
		$this->assign("order",$order);
 		$this->display();
    }	
    public function notice(){
    	//读取公告数据
		$notice = M('notice');
		$vo = $notice->select();
		$this->assign("list", $vo);
		$this->display();
    }
    //公告编辑页
 	public function noticeedit(){
		$id = I('request.id');
		//判断是否有id传值，有则作为编辑页，否则作为添加页
		if($id){
			$notice = M('notice');
			$vo = $notice->where('id='.$id)->select();
			$this->assign("list", $vo);
			$this->assign("id", $id);
			$this->display();
			//判断是否有表单提交
			if (IS_POST) {
				$notice = M('notice');
				$notice->id=$id;
				$notice->title = $_POST['title'];
				$notice->content = $_POST['content'];
				$result = $notice->save();
				if ($result) {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('修改成功');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
				} else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("修改失败")</script>';
				}
			}
		}
		//作为添加页
		else
		{
			if (IS_POST) {
				$notice = M('notice');
				$notice->title = $_POST['title'];
				$notice->content = $_POST['content'];
				$notice->addtime = date('Y-m-d H:i');
				$result = $notice->add();
				if ($result) {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("新增成功")</script>';		
				} 
				else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("新增失败")</script>';
				}
			}
		}
		$this->display();
    }
    //用户列表&&用户检索显示页
 	public function users(){
    	$name = I('request.name');
    	$account=I('request.account');
    	$users = M('users');
    	//判断是否有name和account传值，有则作为用户检索显示页
    	if($name&&$account){
			$vo = $users->where('name='.$name.'&account='.$account)->select();
    	}
    	else if($name){
			$vo = $users->where('name='.$name)->select();
    	}
    	else if($account){
			$vo = $users->where('account='.$account)->select();
    	}
    	//显示所有用户
    	else{
			$vo = $users->select();
		}
		$this->assign("list", $vo);
		$this->display();
    }
    //用户信息页 	
    public function user(){
    	$id = I('request.id');
	    $users = M('users');
		$vo = $users->where('id='.$id)->select();
		$this->assign("list", $vo);
		$this->display();
		if (IS_POST) {
			//判断哪个button提交的表单，此为名为save的button
			if(isset($_POST['save']))
			{
				$users = M('users');
				$users->id=$id;
				$users->name = $_POST['name'];
				$users->account = $_POST['account'];
				$users->password = $_POST['password'];
				$users->email = $_POST['email'];
				$users->address = $_POST['address'];
				$result = $users->save();
				if ($result) {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('修改成功');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
				} 
				else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("修改失败")</script>';
				}
			}
			//此为名为freeze的button
			else if(isset($_POST['freeze']))
			{
				//冻结用户
				$users = M('users');
				$users->id=$id;
				$users->state=0;
				$result = $users->save();
				if ($result) {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('冻结成功');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
				} 
				else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("冻结失败")</script>';
				}
			}
		}
    }
    //管理员列表
    public function admins(){
 		$admin = M('admins');
		$vo = $admin->select();
		$this->assign("list", $vo);
		$this->display();
    } 	
    //管理员信息
    public function admin(){
    	$this->display();
    	$adminaccount=$_SESSION['adminaccount'];
		$admin= M('admins')->where("account=".$adminaccount)->select();
		$power=$admin[0]['power'];
		//判断该管理员是否为最高管理员
		if($power)
		{
	    	$id = I('request.id');
			$admin = M('admins');
			$vo = $admin->where('id='.$id)->select();
			$this->assign("list", $vo);
			$this->assign("id", $id);
			if (IS_POST) {
				$admin = M('admins');
				$admin->id=$id;
				$admin->name = $_POST['name'];
				$admin->account = $_POST['account'];
				$admin->password = $_POST['password'];
				$admin->power = $_POST['power'] + 0;
				$result = $admin->save();
				if ($result) {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('修改成功');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
				} else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("修改失败")</script>';
				}
			}
		}
		else{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('你没有权限执行此操作');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
		}
    }
    //添加管理员
    public function addadmin(){
    	$this->display();
    	$adminaccount=$_SESSION['adminaccount'];
		$admin= M('admins')->where("account=".$adminaccount)->select();
		$power=$admin[0]['power'];
		//判断该管理员是否为最高管理员
		if($power)
		{
			if (IS_POST) {
				$admin = M('admins');
				$admin->name = $_POST['name'];
				$admin->account = $_POST['account'];
				$password = $_POST['password'];
				//采用md5加密
				$admin->password=md5($password);
				//默认权限都为0，仅有唯一最高管理员
				$admin->power =  "0";
				$Admins=D("Admins");
				//判断用户名和账号是否重复
				$is=$Admins->IsExist($admin->name,$admin->account);
				//不重复则新增管理员
				if($is=="1"){					
					$result = $admin->add();
					if ($result) {
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script type="text/javascript">alert("新增成功")</script>';
					} 
					else {
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script type="text/javascript">alert("新增失败")</script>';
					}
				}
				//输出重复项目
				else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("'.$is.'")</script>';
				}
			}
		}
		else{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('你没有权限执行此操作');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
		}
	}
	//订单列表
	public function order(){
		$order=D('OrdersView')->order('order_state')->select();
		$this->assign("order",$order);
		$this->display();
	}
	//订单的受理
	public function handleorder(){
		$id = I('request.id');
		$order=M("orders")->where('id='.$id)->select();
		$state=$order[0]['state'];
		//判断订单是否受理完毕
		if($state==1){
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('该订单以执行');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
		}
		else{
			$goodstypeid=$order[0]['goodstypeid'];
			$goodsnum=$order[0]['goodsnum'];
			$goodstype=M('goodstype')->where('id='.$goodstypeid)->select();
			$goodsleft=$goodstype[0]['goodsleft'];
			//判断余货是否充足
		    if($goodsleft>=$goodsnum){
			 	$goodsleft-=$goodsnum;
				$goodstypes=M('goodstype');
				$goodstypes->id=$goodstypeid;
			 	$goodstypes->goodsleft=$goodsleft;	
			 	$result = $goodstypes->save();
			 	//受理订单
			 	$orders=M('orders');
			 	$orders->id=$id;    
				$orders->state=1;
				$result2 = $orders->save();

				if($result&&$result2){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('发货成功');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
				}
				else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('发货失败');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
				}
			}
			else{
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo "<script>alert('余货不足');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
			}
		}
	}
	//用户检索页，跳转到users页显示
	public function userselect(){
		$this->display();
    	if(IS_POST)
    	{	
    		$name=$_POST['name'];
    		$account=$_POST['account'];
			if($name&&$account)
				$this->redirect("Home/index/users?name=".$name."&account=".$account);
			else if($name)
				$this->redirect("Home/index/users?name=".$name);
			else if($account){
				$this->redirect("Home/index/users?account=".$account);
			}
    	}
    }

    //登录页
    public function login(){
    	//不加载模板页
    	C('LAYOUT_ON', FALSE);
    	$this->display();
    	if(IS_POST){
    		$admin = M('admins');
    		$adminaccount=$_POST['adminaccount'];
    		$password=$_POST['password'];
    		//这里使用md5加密
    		$password=md5($password);
    		if($adminaccount==""||$password==""){
    			echo "<script>alert('请输入用户名和密码！');history.go(-1);</script>";
    		}else{
    			$result=$admin->where('account="%s" and password="%s"',$adminaccount,$password)->select();
    			if($result){
    				//将用户账号存入session
    				$_SESSION['adminaccount'] = $adminaccount;
    				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('登陆成功');</script>";
					$this->redirect("/Home/index"); 
    			}else{
    				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('登录失败');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
    			}
    		}
    	}
    }

	//删除管理员
	public function delete() {
		$adminaccount=$_SESSION['adminaccount'];
		$admin= M('admins')->where("account=".$adminaccount)->select();
		$power=$admin[0]['power'];
		//判断该管理员是否为最高管理员
		if($power)
		{
			$id = I('request.id');
			$result = M('admins')->delete($id);
			if ($result) {
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo "<script>alert('删除成功');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
			} else {
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo "<script>alert('删除失败');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
			}
		}
		else{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('你没有权限执行此操作');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
		}
	}
	//删除用户
	public function deleteuser() {
		$id = I('request.id');
		$user = M('users');
		$result = $user->delete($id);
		if ($result) {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('删除成功');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
		} else {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('删除失败');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
		}
	}
	//删除公告 
	public function deletenotice() {
		$id = I('request.id');
		$notice = M('notice');
		$result = $notice->delete($id);
		if ($result) {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('删除成功');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
		} else {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('删除失败');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
		}
	}
	

}
