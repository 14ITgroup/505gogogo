<?php

namespace Home\Model;
use Think\Model\ViewModel;
class AdminsModel extends ViewModel {
	public function IsExist($name,$account) {
		$isName = M('admins')->where('name='.$name)->select();
		$isAccount = M('admins')->where('account='.$account)->select();
		if($isName&&$isAccount)
			return "用户名和账户已存在";
		else if($isName)
			return "用户名已存在";
		else if($isAccount)
			return "账户已存在";
		else
			return "1";
	}
	public function IsRepeat($id,$name,$account) {
		$nameMap["id"]=array("neq",$id);
		$namaMap["name"]=array("eq",$name);
		$AccountMap["id"]=array("neq",$id);
		$AccountMap["account"]=array("eq",$account);
		$isName = M('admins')->where($nameMap)->select();
		$isAccount = M('admins')->where($accountMap)->select();
		if($isName&&$isAccount)
			return "用户名和账户已存在";
		else if($isName)
			return "用户名已存在";
		else if($isAccount)
			return "账户已存在";
		else
			return "1";
	}
}
?>