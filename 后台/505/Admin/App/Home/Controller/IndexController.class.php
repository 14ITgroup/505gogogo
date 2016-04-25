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
		$admin = M('admins');
		$vo = $admin->select();
		$this->assign("list", $vo);
		$this->display();

    }
 	public function noticeedit()
    {
		// $id = I('request.id');
		// $admin = M('admins');
		// $vo = $admin->select();
		// $this->assign("list", $vo);
		$this->display();

    }


 	public function users()
    {
 		$admin = M('admins');
		 $vo = $admin->select();
		 $this->assign("list", $vo);
		$this->display();

    } 	
    public function user()
    {
  //   	$id = I('request.id');
		// $admin = M('admins');
		// $vo = $admin->select();
		// $this->assign("list", $vo);
		$this->display();

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
				echo '<script type="text/javascript">alert("修改成功")</script>';
				$this->success();
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
				$admin->power = $_POST['power'] + 0;
				$result = $admin->add();
				if ($result) {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("新增成功")</script>';
					$this->success();
				} 
				else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("新增失败")</script>';
				}

			}
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
