<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>数据展示</title>
	<script type="text/javascript" src="/jquery-v1.10.2.min.js"></script>
	</script>
	<script type="text/javascript">
		function checked(){
			return false;
		}
	</script>
</head>

<body>
<form id="add" action="./delete"  method="post" >
	序号 -- 名字 -- 账号 -- 密码 -- 权限<br>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; echo ($i); ?> --
		<?php echo ($vo["name"]); ?> --
		<?php echo ($vo["account"]); ?> --
		<?php echo ($vo["password"]); ?> --
		<?php echo ($vo["power"]); ?>
		<a href="<?php echo U("index.php/home/Index/delete?id=$vo[id]");?>" >删除</a>
	<br>
	<br><?php endforeach; endif; else: echo "" ;endif; ?>
	<input type="hidden" id="id" name='ID'  />
</form>
</body>
</html>