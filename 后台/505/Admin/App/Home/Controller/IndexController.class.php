<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
 		$this->display();
    }	
    public function notice()
    {
		$notice = M('notice');
		$vo = $notice->select();
		$this->assign("list", $vo);
		$this->display();

    }
 	public function noticeedit()
    {
		$id = I('request.id');
		if($id){
			$notice = M('notice');
			$vo = $notice->where('id='.$id)->select();
			$this->assign("list", $vo);
			$this->assign("id", $id);
			$this->display();
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


 	public function users()
    {
 		$users = M('users');
		$vo = $users->select();
		$this->assign("list", $vo);
		$this->display();
    } 	
    public function user()
    {
    	$id = I('request.id');
	    $users = M('users');
		$vo = $users->where('id='.$id)->select();
		$this->assign("list", $vo);
		$this->display();
		if (IS_POST) {
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
			else if(isset($_POST['freeze']))
			{
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
    public function admins()
    {
 		$admin = M('admins');
		$vo = $admin->select();
		$this->assign("list", $vo);
		$this->display();

    } 	
    public function admin()
    {
    	$id = I('request.id');
		$admin = M('admins');
		$vo = $admin->where('id='.$id)->select();
		$this->assign("list", $vo);
		$this->assign("id", $id);
		$this->display();
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
     public function addadmin()
    {
    	$this->display();
			if (IS_POST) {
				$admin = M('admins');
				$admin->name = $_POST['name'];
				$admin->account = $_POST['account'];
				$admin->password = $_POST['password'];
				$admin->power =  "0";
				$Admins=D("Admins");
				$is=$Admins->IsExist($admin->name,$admin->account);
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
				else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("'.$is.'")</script>';
				}
			}
	}
	public function order(){
		$order = M('order')->order('id desc')->select();
		$users =M('users')->select();
		$goodstype=M('goodstype')->select();
		$goods=M('goods')->select();
		$this->assign("order", $order);
		$this->assign("users", $users);
		$this->assign("goodstype", $goodstype);
		$this->assign("goods", $goods);
		$this->display();
	}


	///删除demo///
	public function delete() {
		$id = I('request.id');
		$admin = M('admins');
		$result = $admin->delete($id);
		if ($result) {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script type="text/javascript">alert("删除成功")</script>';
			$this->success();
		} else {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script type="text/javascript">alert("删除失败")</script>';
		}
	}
}
