<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <title>支付</title>
    <link rel="stylesheet" type="text/css" href="/505/Public/css/style.css" /> 
    <!--[if lt IE 9]>
    <script type="text/javascript" src="js/html5shiv.min.js"></script>
    <![endif]-->
</head>

<body>
    <section class="pay-head">
        <div class="pay-head-h">
            <a href="homepage.html"><img src="/505/Public/images/arrow-left.png" alt="返回"></a>
            <h3>支付</h3>
        </div>
        <div class="pay-info">
            <img src="/505/Public/images/location.png" alt="location">
            <?php if(is_array($single)): $i = 0; $__LIST__ = $single;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$user): $mod = ($i % 2 );++$i;?><div>
                    <p>收货人: <span><?php echo ($user["name"]); ?></span></p>
                    <p>电话: <span><?php echo ($user["phonenumber"]); ?></span></p>
                    <p>地址: <span><?php echo ($user["address"]); ?></span></p>
                    <p></p>
                </div><?php endforeach; endif; else: echo "" ;endif; ?> 
        </div>
        <img src="/505/Public/images/bolang.png" alt="bolang">
    </section>
    <section class="pay-things">
        <section class="shop">
            <div class="shop-info">
                <img src="/505/Public/images/logo-shop.png" alt="logo-shop">
                <h3>505店铺</h3>
            </div>
            <ul class="shopping-list">
                <?php if(is_array($carts)): $i = 0; $__LIST__ = $carts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cart): $mod = ($i % 2 );++$i;?><li>
                        <img src="<?php echo ($cart["image"]); ?>" alt="things">
                        <div>
                            <input type="hidden" id="typeid" name="typeid" value="<?php echo ($cart["type_id"]); ?>">
                            <input type="hidden" id="name" name="name" value="<?php echo ($cart["name"]); ?>" />
                            <input type="hidden" id="color" name="color" value="<?php echo ($cart["color"]); ?>" />
                            <input type="hidden" id="size" name="size" value="<?php echo ($cart["size"]); ?>" />
                            <input type="hidden" id="goodsnum" name="goodsnum" value="<?php echo ($cart["goodsnum"]); ?>" />
                            <input type="hidden" id="price" name="price" value="<?php echo ($cart["price"]); ?>" />
                            <p>商品名称/颜色/规格<br /><?php echo ($cart["name"]); ?>--<?php echo ($cart["color"]); ?>--<?php echo ($cart["size"]); ?>
                            </p>
                            <p>产品数量</p>
                            <b>X1<!-- <?php echo ($cart["goodsnum"]); ?> --></b>
                            <strong>￥<span><?php echo ($cart["price"]); ?></span></strong>
                        </div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?> 
            </ul>
        </section>
    </section>
    <section class="pay-confirm">
    	<a href='/505/index.php/home/index/peopleinfo.html' onclick="paying();">支付</a>
    	<p>总计: <span>￥<?php echo ($allprice); ?></span></p>
    	<div style="clear: both;"></div>
    </section>
</body>
    <script type="text/javascript" src="/505/Public/js/jquery-v1.10.2.min.js"></script>
<script type="text/javascript">
    function paying(){
        //获取typeid序列
        var typeid=$(".shopping-list li div #typeid");
        var length=typeid.length+0;
        var typeids=new Array(length);

        for(i=0;i<length;i++){
            typeids[i]=typeid[i].value;
            // alert(typeids[i]);
        }
        var data = {
           typeids:typeids
        };
        $.ajax({
            url: '<?php echo U("index.php/Home/Index/buildorderajax");?>',
            type: 'POST',
            //false和true对应两种传值方式，默认为false
            //traditional :false,
            data: data
        })
        .done(function(dataget) {
            console.log("success");
            //alert(dataget);
            console.log(dataget);
            $.each(dataget, function(){     
                alert(this);
                console.log(this);
                self.location='<?php echo U("index.php/Home/Index/peopleinfo");?>'; 
            }); 

        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    }
</script>
</html>