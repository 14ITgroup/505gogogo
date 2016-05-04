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
            <a href="#"><img src="/505/Public/images/arrow-left.png" alt="返回"></a>
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
                            <input type="hidden" class="gi" id="goodsid" name="goodsid" value="<?php echo ($cart["type_id"]); ?>">
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
    	<a href="#" onclick="paying();">支付</a>
    	<p>总计: <span>￥<?php echo ($allprice); ?></span></p>
    	<div style="clear: both;"></div>
    </section>
    <footer>
        <nav>
            <ul>
                <li><a href="homepage.html">
                        <img src="/505/Public/images/home-pressed.png" alt="主页">
                        <p>主页</p>
                    </a></li>
                <li><a href="chart.html">
                        <img src="/505/Public/images/shop-car.png" alt="购物车">
                        <p>购物车</p>
                    </a></li>
                <li><a href="people.html">
                        <img src="/505/Public/images/mine.png" alt="个人">
                        <p>个人</p>
                    </a></li>
            </ul>
        </nav>
    </footer>
</body>
    <script type="text/javascript" src="/505/Public/js/jquery-v1.10.2.min.js"></script>
<script type="text/javascript">
    function paying(){
        var name = $(".shopping-list li div #name");
        var color = $(".shopping-list li div #color");
        var size = $(".shopping-list li div #size");
        var goodsnum = $(".shopping-list li div #goodsnum");
        var price = $(".shopping-list li div #price");
        //var type_id=$(".gi");
        var length=name.length+0;

        var names = new Array(length);
        var colors = new Array(length);
        var sizes = new Array(length);
        var goodsnums = new Array(length);
        var prices = new Array(length);
        for(i=0;i<length;i++){
            names[i]=name[i].value;
            colors[i]=color[i].value;
            sizes[i]=size[i].value;
            goodsnums[i]=goodsnum[i].value;
            prices[i]=price[i].value;
        }
        //var ids=new Array();
        //for(var i =0;i<$(type_id).length;i++){
        //    ids[i]=$($(type_id)[i]).value;
        //}
        //alert(ids);
        //alert("111");
        //var data={
        //    id:ids,
        //}
        var data = {
            names: names,
            sizes: sizes,
            colors: colors,
           goodsnums: goodsnums,
            prices: prices
        };
        $.ajax({
            url: '<?php echo U("index.php/home/index/buildorderajax");?>',
            type: 'POST',
            data: data
        })
        .done(function(dataget) {
            console.log("success");
            console.log(dataget);
            //alert(dataget);
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