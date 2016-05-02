<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>用户注册</title>
</head>
<body>
	<form action="" method="post" onsubmit="return validate();">
		<input type="text" placeholder="请输入您的昵称" name="name" id="name" required="required"/>
		<input type="text" placeholder="请输入您的账号" name="account" id="account" required="required"/>
		<input type="password" placeholder="请输入您的密码" name="password1" id="password1" required="required"/>
		<input type="password" placeholder="请再次输入密码" name="password2" id="password2"required="required"/>
		<input type="text" placeholder="请输入您的邮箱" name="email" id="email" required="required"/>
		<input type="text" placeholder="请输入您的电话" name="phone" id="phone" required="required"/>
		<input type="text" placeholder="请输入您的地址" name="address" id="address" required="required"/>
		<input type="text" placeholder="请输入验证码" name="verify" id="verify" required="required" />
		<!-- 生成验证码，src部署时需要更改，点击验证码图片会局部刷新 -->
		<img src="<?php echo U('index.php/home/Index/verify');?>" alt="验证码" onclick="this.src=this.src+'?'+Math.random()">
		<!--onclick="this.src='<?php echo U(index.php/home/Index/verify);?>'+Math.random()"-->
		<input type="submit" value="注册" />
 	</form>
</body>
<script type="text/javascript">
	function validate(){
		var name=document.getElementById('name').value.trim();
		var account=document.getElementById('account').value.trim();
		var password1=document.getElementById('password1').value.trim();
		var password2=document.getElementById('password2').value.trim();
		var email=document.getElementById('email').value.trim();
		var phone=document.getElementById('phone').value.trim();
		if(name.length>15){
			alert("昵称不能超过15位");
			return false;
		}
		if(account.length<6||account.length>15){
			alert("账号长度为6-15位");
			return false;
		}
		if(!(password2==password1)){
			alert("两次输入的密码不一致");
			return false;
		}
		var regEmail=/^(\w)+(\.\w+)*@(\w)+((\.\w{2,3}){1,3})$/;
		if(!regEmail.test(email)){
			alert("邮箱格式不正确");
			return false;
		}
		var regPhone=/1[0-9]{10}/;
		if(!regPhone.test(phone)){
			alert("手机格式不正确");
			return false;
		}
	}
</script>
</html>