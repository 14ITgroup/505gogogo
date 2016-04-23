<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<titl>数据展示</titl>
</head>
<body>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><?php echo ($i); ?></li>
<li><?php echo ($vo["name"]); ?></li>
<li><?php echo ($vo["account"]); ?></li>
<li><?php echo ($vo["password"]); ?></li>
<li><?php echo ($vo["power"]); ?></li>
<br><?php endforeach; endif; else: echo "" ;endif; ?>

</body>
</html>