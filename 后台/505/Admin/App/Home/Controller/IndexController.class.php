<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
	// 自动运行方法,判断是否登录
	public function _initialize() {
		//当前为登录页时不执行该操作
		if (ACTION_NAME != "login") {
			//判断session['adminaccount']是否为空，是的话跳转到登陆界面
			if (!isset($_SESSION['adminaccount'])) {
				echo "<script>alert('用户未登录或登陆超时');</script>";
				$this->redirect("/Home/index/login");
			} else {
				//显示登录的管理员帐号
				$adminaccount = $_SESSION['adminaccount'];
				$admin = M('admins')->where("account=" . $adminaccount)->select();
				$name = $admin[0]['name'];
				$this->assign("name", $name);
			}
		}
	}
	//后台首页
	public function index() {
		//读取用户数据
		$vo = M('users')->order('id desc')->select();
		$this->assign("list", $vo);
		//使用OrdersView模型读取订单有关数据
		$order = D('OrdersView')->where('orders.state=0')->select();
		$this->assign("order", $order);
		$this->display();
	}
	public function notice() {
		//读取公告数据
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
			//判断是否有表单提交
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
	//管理员列表
	public function admins() {
		$admin = M('admins');
		$vo = $admin->select();
		$this->assign("list", $vo);
		$this->display();
	}
	//管理员信息
	public function admin() {
		$adminaccount = $_SESSION['adminaccount'];
		$admin = M('admins')->where("account=" . $adminaccount)->select();
		$power = $admin[0]['power'];
		//判断该管理员是否为最高管理员
		if ($power) {
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
				$result = $admin->save();
				if ($result) {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('修改成功');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
				} else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("修改失败")</script>';
				}
			}
		} else {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('你没有权限执行此操作');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
		}
	}
	//添加管理员
	public function addadmin() {
		$this->display();
		$adminaccount = $_SESSION['adminaccount'];
		$admin = M('admins')->where("account=" . $adminaccount)->select();
		$power = $admin[0]['power'];
		//判断该管理员是否为最高管理员
		if ($power) {
			if (IS_POST) {
				$admin = M('admins');
				$admin->name = $_POST['name'];
				$admin->account = $_POST['account'];
				$password = $_POST['password'];
				//采用md5加密
				$admin->password = md5($password);
				//默认权限都为0，仅有唯一最高管理员
				$admin->power = "0";
				$Admins = D("Admins");
				//判断用户名和账号是否重复
				$is = $Admins->IsExist($admin->name, $admin->account);
				//不重复则新增管理员
				if ($is == "1") {
					$result = $admin->add();
					if ($result) {
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script type="text/javascript">alert("新增成功")</script>';
					} else {
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script type="text/javascript">alert("新增失败")</script>';
					}
				}
				//输出重复信息
				else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script type="text/javascript">alert("' . $is . '")</script>';
				}
			}
		} else {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('你没有权限执行此操作');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
		}
	}
	//订单列表
	public function order() {
		$order = D('OrdersView')->order('order_state')->select();
		$this->assign("order", $order);
		$this->display();
	}
	//订单的受理
	public function handleorder() {
		$id = I('request.id');
		$order = M("orders")->where('id=' . $id)->select();
		$state = $order[0]['state'];
		//判断订单是否受理完毕
		if ($state == 1) {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('该订单以执行');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
		} else {
			$goodstypeid = $order[0]['goodstypeid'];
			$goodsnum = $order[0]['goodsnum'];
			$goodstype = M('goodstype')->where('id=' . $goodstypeid)->select();
			$goodsleft = $goodstype[0]['goodsleft'];
			//判断余货是否充足
			if ($goodsleft >= $goodsnum) {
				$goodsleft -= $goodsnum;
				$goodstypes = M('goodstype');
				$goodstypes->id = $goodstypeid;
				$goodstypes->goodsleft = $goodsleft;
				$result = $goodstypes->save();
				//受理订单
				$orders = M('orders');
				$orders->id = $id;
				$orders->state = 1;
				$result2 = $orders->save();

				if ($result && $result2) {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('发货成功');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
				} else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script>alert('发货失败');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
				}
			} else {
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo "<script>alert('余货不足');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
			}
		}
	}
	//用户检索页，跳转到users页显示
	public function userselect() {
		$this->display();
		if (IS_POST) {
			$name = $_POST['name'];
			$account = $_POST['account'];
			if ($name && $account) {
				$this->redirect("Home/index/users?name=" . $name . "&account=" . $account);
			} else if ($name) {
				$this->redirect("Home/index/users?name=" . $name);
			} else if ($account) {
				$this->redirect("Home/index/users?account=" . $account);
			}
		}
	}

	//登录页
	public function login() {
		//不加载模板页
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

	//删除管理员
	public function delete() {
		$adminaccount = $_SESSION['adminaccount'];
		$admin = M('admins')->where("account=" . $adminaccount)->select();
		$power = $admin[0]['power'];
		//判断该管理员是否为最高管理员
		if ($power) {
			$id = I('request.id');
			$result = M('admins')->delete($id);
			if ($result) {
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo "<script>alert('删除成功');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
			} else {
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo "<script>alert('删除失败');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
			}
		} else {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('你没有权限执行此操作');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
		}
	}
	//删除用户
	public function deleteuser() {
		$id = I('request.id');
		$user = M('users');
		$result = $user->delete($id);
		if ($result) {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('删除成功');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
		} else {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('删除失败');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
		}
	}
	//删除公告
	public function deletenotice() {
		$id = I('request.id');
		$notice = M('notice');
		$result = $notice->delete($id);
		if ($result) {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('删除成功');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
		} else {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('删除失败');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
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
			$data["image"] = I("post.pp");
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

	//商品添加
	public function goodsadd() {
		$mode = D('GoodsView');
		$mode2 = M('goodsclassify')->limit(8)->select();
		$this->assign('classify', $mode2);
		$this->display();
		if (IS_POST) {
			$data['name'] = I("post.goodsname");
			$data["price"] = I("post.goodsprice");
			$data["classifyid"] = I("post.goodsclassify");
			$data["image"] = I("post.pp");
			$data["detail"] = I("post.detail");
			$result = $mode->addgood($data);
			if ($result) {
				$this->success("保存成功", U("home/index/goodslist"));
			} else {
				$this->error("保存失败");

			}
		}
	}

	//商品页需要的ajax接口
	public function goodsajax() {
		$name = I('post.name');
		$color = I('post.color');
		$size = I('post.size');
		if ($color != "") {
			if ($size != "") {
				$go = D('GoodsView')->where('name="' . $name . '" and color="' . $color . '" and size="' . $size . '"')->find();
			} else {
				$go = D('GoodsView')->where('name="' . $name . '" and color="' . $color . '"')->find();
			}
		} else {
			if ($size != "") {
				$go = D('GoodsView')->where('name="' . $name . '" and size="' . $size . '"')->find();
			} else {
				$go = D('GoodsView')->where('name="' . $name . '"')->find();
			}
		}
		$data = $go["goodsleft"];
		$this->ajaxReturn($data); //{"data":"$go["goodsleft"]"}
	}

	public function goodsajax2() {
		$name = I('post.name');
		$color = I('post.color');
		$size = I('post.size');
		$left = I('post.left');
		$left = $left + 0;
		if ($color != "") {
			if ($size != "") {
				$go = D('GoodsView')->where('name="' . $name . '" and color="' . $color . '" and size="' . $size . '"')->find();
			} else {
				$go = D('GoodsView')->where('name="' . $name . '" and color="' . $color . '"')->find();
			}
		} else {
			if ($size != "") {
				$go = D('GoodsView')->where('name="' . $name . '" and size="' . $size . '"')->find();
			} else {
				$go = D('GoodsView')->where('name="' . $name . '"')->find();
			}
		}
		$data = $go["type_id"] + 0;
		$result = M("goodstype");
		$result = $result->where('id=%d', $data)->setField('goodsleft', $left);
		if ($result) {
			$data = "修改成功";
		} else {
			$data = "修改失败";
		}

		$this->ajaxReturn($data); //{"data":"$go["goodsleft"]"}
	}

}
