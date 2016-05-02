<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
	public function _initialize() {
		// 自动运行方法,判断是否登录
		if (ACTION_NAME != "login") {
			if (!isset($_SESSION['adminaccount'])) {
				echo "<script>alert('用户未登录或登陆超时');</script>";
				$this->redirect("/Home/index/login");
			}
		}

	}

	public function index() {
		$this->display();
	}
	public function notice() {
		$notice = M('notice');
		$vo = $notice->select();
		$this->assign("list", $vo);
		$this->display();
	}
	public function noticeedit() {
		$id = I('request.id');
		if ($id) {
			$notice = M('notice');
			$vo = $notice->where('id=' . $id)->select();
			$this->assign("list", $vo);
			$this->assign("id", $id);
			$this->display();
			if (IS_POST) {
				$notice = M('notice');
				$notice->id = $id;
				$notice->title = $_POST['title'];
				$notice->content = $_POST['content'];
				$result = $notice->save();
				if ($result) {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('修改成功');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
				} else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("修改失败")</script>';
				}
			}
		} else {
			if (IS_POST) {
				$notice = M('notice');
				$notice->title = $_POST['title'];
				$notice->content = $_POST['content'];
				$notice->addtime = date('Y-m-d H:i');
				$result = $notice->add();
				if ($result) {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("新增成功")</script>';

				} else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("新增失败")</script>';
				}
			}
		}
		$this->display();
	}
	public function users() {
		$users = M('users');
		$vo = $users->select();
		$this->assign("list", $vo);
		$this->display();
	}
	public function user() {
		$id = I('request.id');
		$users = M('users');
		$vo = $users->where('id=' . $id)->select();
		$this->assign("list", $vo);
		$this->display();
		if (IS_POST) {
			if (isset($_POST['save'])) {
				$users = M('users');
				$users->id = $id;
				$users->name = $_POST['name'];
				$users->account = $_POST['account'];
				$users->password = $_POST['password'];
				$users->email = $_POST['email'];
				$users->address = $_POST['address'];
				$result = $users->save();
				if ($result) {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('修改成功');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
				} else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("修改失败")</script>';
				}
			} else if (isset($_POST['freeze'])) {
				$users = M('users');
				$users->id = $id;
				$users->state = 0;
				$result = $users->save();
				if ($result) {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('冻结成功');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
				} else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("冻结失败")</script>';
				}
			}
		}
	}
	public function admins() {
		$admin = M('admins');
		$vo = $admin->select();
		$this->assign("list", $vo);
		$this->display();
	}
	public function admin() {
		$id = I('request.id');
		$admin = M('admins');
		$vo = $admin->where('id=' . $id)->select();
		$this->assign("list", $vo);
		$this->assign("id", $id);
		$this->display();
		if (IS_POST) {
			$admin = M('admins');
			$admin->id = $id;
			$admin->name = $_POST['name'];
			$admin->account = $_POST['account'];
			$admin->password = $_POST['password'];
			$admin->power = $_POST['power'] + 0;
			$result = $admin->save();
			if ($result) {
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo "<script>alert('修改成功');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
			} else {
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo '<script type="text/javascript">alert("修改失败")</script>';
			}
		}
	}
	public function addadmin() {
		$this->display();
		if (IS_POST) {
			$admin = M('admins');
			$admin->name = $_POST['name'];
			$admin->account = $_POST['account'];
			$password = $_POST['password'];
			$admin->password = md5($password);
			$admin->power = "0";
			$Admins = D("Admins");
			$is = $Admins->IsExist($admin->name, $admin->account);
			if ($is == "1") {
				$result = $admin->add();
				if ($result) {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("新增成功")</script>';
				} else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("新增失败")</script>';
				}
			} else {
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo '<script type="text/javascript">alert("' . $is . '")</script>';
			}
		}
	}
	public function order() {
		$order = M('order')->order('id desc')->select();
		$users = M('users')->select();
		$goodstype = M('goodstype')->select();
		$goods = M('goods')->select();
		$this->assign("order", $order);
		$this->assign("users", $users);
		$this->assign("goodstype", $goodstype);
		$this->assign("goods", $goods);
		$this->display();
	}
	public function userselect() {
		$this->display();
		if (IS_POST) {
			echo "saddas";
			$name = $_POST["name"];
			$account = $_POST["account"];
			if ($name && $account) {
				$users = M('users')->where("name=" . $name . "and account=" . $account);
			} else if ($name) {
				$users = M('users')->where("name=" . $name);
			} else if ($account) {
				$users = M('users')->where("name=" . $name);
			} else {
				echo "用户不存在";
			}

			$this->assign("list", $users);
		}
	}
	public function login() {
		C('LAYOUT_ON', FALSE);
		$this->display();
		if (IS_POST) {
			$admin = M('admins');
			$adminaccount = $_POST['adminaccount'];
			$password = $_POST['password'];
			//这里使用md5加密
			$password = md5($password);
			if ($adminaccount == "" || $password == "") {
				echo "<script>alert('请输入用户名和密码！');history.go(-1);</script>";
			} else {
				$result = $admin->where('account="%s" and password="%s"', $adminaccount, $password)->select();
				if ($result) {
					//将用户账号存入session
					$_SESSION['adminaccount'] = $adminaccount;
					// $this->success('登录成功', U('/Home/index/'));
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('登陆成功');</script>";
					$this->redirect("/Home/index");
				} else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('登录失败');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
				}
			}
		}
	}

	///删除admin///
	public function delete() {
		$id = I('request.id');
		$admin = M('admins');
		$result = $admin->delete($id);
		if ($result) {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('删除成功');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
		} else {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script type="text/javascript">alert("删除失败")</script>';
		}
	}
	public function deleteuser() {
		$id = I('request.id');
		$user = M('users');
		$result = $user->delete($id);
		if ($result) {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('删除成功');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
		} else {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script type="text/javascript">alert("删除失败")</script>';
		}
	}
	public function deletenotice() {
		$id = I('request.id');
		$notice = M('notice');
		$result = $notice->delete($id);
		if ($result) {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('删除成功');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
		} else {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script type="text/javascript">alert("删除失败")</script>';
		}
	}

	/// 商品页///

	//商品列表
	public function goodslist() {
		$goodsitem = M('goods')->select();
		$this->assign("list", $goodsitem);
		$this->display();
	}
	//商品编辑
	public function goodseditor() {
		$goodsid = I('get.id'); //获取商品的主键

		$mode = D('GoodsView'); //建立模型

		$vo1 = $mode->seecolor($goodsid); //获取颜色分类
		$vo1[0]['ch'] = 'selected="true"';
		$vo2 = $mode->seesize($goodsid); //获取尺寸分类
		$vo2[0]['ch'] = 'selected="true"';
		$data = $mode->where('goods.id=%d', $goodsid)->select(); //获得商品类型
		$mode2 = M('goodsclassify')->limit(8)->select();
		foreach ($mode2 as &$value) {
			if ($value["id"] == $data[0]["classifyid"]) {
				$value["se"] = 'selected="true"';
			}
		}
		//绑定数据并显示
		$this->assign('classify', $mode2);
		$this->assign('name', $data[0]["name"]);
		$this->assign('price', $data[0]["price"]);
		$this->assign('detail', $data[0]["detail"]);
		$this->assign('name', $data[0]["name"]);
		$this->assign('goodsid', $goodsid);
		$this->assign('colorlist', $vo1);
		$this->assign('colorl', $vo1);
		$this->assign('sizelist', $vo2);
		$this->assign('sizel', $vo2);
		$this->display();
		//添加分类的post
		if (isset($_POST['addcolor'])) {
			$colorname = I("post.txtcolor");
			if ($colorname == "") {
				$this->error("添加失败");
			}
			$result = $mode->addcolor($goodsid, $colorname);
			if ($result) {
				$this->success("添加成功");
			} else {
				$this->error("添加失败");

			}
		}
		if (isset($_POST['addsize'])) {
			$sizename = I("post.txtsize");
			if ($sizename == "") {
				$this->error("添加失败");
			}
			$result = $mode->addsize($goodsid, $sizename);
			if ($result) {
				$this->success("添加成功");
			} else {
				$this->error("添加失败");

			}
		}
		//删除分类的post
		if (isset($_POST['decolor'])) {
			$colorname = I("post.goodscolor");
			$result = $mode->delcolor($goodsid, $colorname);
			if ($result) {
				$this->success("删除成功");
			} else {
				$this->error("删除失败");

			}
		}
		if (isset($_POST['desize'])) {
			$sizename = I("post.goodssize");
			$result = $mode->delsize($goodsid, $sizename);
			if ($result) {
				$this->success("删除成功");
			} else {
				$this->error("删除失败");

			}
		}
		//save按钮
		if (isset($_POST['save'])) {
			$data["name"] = I("post.goodsname");
			$data["price"] = I("post.goodsprice");
			$data["classifyid"] = I("post.goodsclassify");
			$data["image"] = I("post.photo");
			$data["detail"] = I("post.detail");
			$result = $mode->updategood($goodsid, $data);
			if ($result) {
				$this->success("保存成功", U("home/index/goodslist"));
			} else {
				$this->error("保存失败");

			}
		}
		if (isset($_POST['delete'])) {
			$result = $mode->delgood($goodsid);
			if ($result) {
				$this->success("删除成功", U("home/index/goodslist"));
			} else {
				$this->error("删除失败");

			}
		}

	}

}
