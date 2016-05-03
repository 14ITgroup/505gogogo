<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <title>购物车</title>
    <link rel="stylesheet" type="text/css" href="/505/Public/css/style.css" /> 
    <!--[if lt IE 9]>
    <script type="text/javascript" src="js/html5shiv.min.js"></script>
    <![endif]-->
</head>

<body>
    <section class="chart-head">
        <h3>购物车</h3>
    </section>
    <section class="chart-list">
        <ul class="shopping-list">
            <?php if(is_array($carts)): $i = 0; $__LIST__ = $carts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cart): $mod = ($i % 2 );++$i;?><li>
                    <img src="<?php echo ($cart["image"]); ?>" alt="things">
                    <div>
                        <p>商品名称/颜色/规格<br /><?php echo ($cart["name"]); ?>--<?php echo ($cart["color"]); ?>--<?php echo ($cart["size"]); ?>
                        </p>
                        <p>产品数量</p>
                        <b>X<!-- <?php echo ($cart["goodsnum"]); ?> --></b>
                        <strong>￥<span><?php echo ($cart["price"]); ?></span></strong>
                    </div>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>    
        </ul>
    </section>
    <section class="hideContainer">
        <?php if(is_array($carts)): $i = 0; $__LIST__ = $carts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cart): $mod = ($i % 2 );++$i;?><section class="hideEdit">
                <a href="chart.html?delete=<?php echo ($cart["id"]); ?>" onclick="<!-- jump(<?php echo ($cart["id"]); ?>); -->alert('哈哈哈');">删除</a>
            </section><?php endforeach; endif; else: echo "" ;endif; ?> 
    </section>
    <section class="pay-confirm">
        <a href="pay.html">支付</a>
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
    <script type="text/javascript" src="/505/Public/js/jquery-v1.10.2.min.js"></script>
    <script type="text/javascript" src="/505/Public/js/jquery.touchSwipe.min.js"></script>
    <script type="text/javascript" src="/505/Public/js/index.js"></script>
    <script type="text/javascript">
        // function jump(deleteid){
        //     window.location.href="chart.html?delete=deleteid";
        // }
       
    </script>
</body>

</html>