<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>数据展示</title>

</head>

<body>
<form id="add"   method="post" action="<?php echo U("index.php/home/index/ajaxtest");?>" >
	colorname：
	<input type="text" name="name" />
	<input type="text" name="color" />
	<input type="text" name="size" />
	<input type="submit" id="id" name='add' value="提交"  />
</form>
</body>
</html>