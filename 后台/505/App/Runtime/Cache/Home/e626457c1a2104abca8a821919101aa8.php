<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <title>个人页面</title>
    <link rel="stylesheet" type="text/css" href="/505/Public/css/style.css" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="js/html5shiv.min.js"></script>
    <![endif]-->
</head>

<body>
    <section class="mine-info">
        <a href="#"><img src="/505/Public/images/setting.png" alt="setting"></a>
        <img src="/505/Public/images/me.jpg" alt="me">
        <p><?php echo ($_SESSION['useraccount']); ?></p>
    </section>
    <section class="tab-choice">
        <input type="hidden" id='state' value="<?php echo ($state); ?>" />
        <script type="text/javascript" src="/505/Public/js/jquery-v1.10.2.min.js"></script>

        <ul>
            <li>
                <a href="people.html">
                    <img src="/505/Public/images/dingdan.png" alt="订单">
                    <p class="reds">订单</p>
                </a>
            </li>
            <li>
                <a href="people.html?state=1">
                    <img src="/505/Public/images/car.png" alt="待发货">
                    <p class="red">待发货</p>
                </a>
            </li>
            <li>
                <a href="people.html?state=2">
                    <img src="/505/Public/images/take.png" alt="运送中">
                    <p class="red">运送中</p>
                </a>
            </li>
            <li>
                <a href="people.html?state=3">
                    <img src="/505/Public/images/comment.png" alt="已完成">
                    <p class="red">已完成</p>
                </a>
            </li>
        </ul>
    </section>
    <section class="shopping-list-container">
        <ul class="shopping-list">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$order): $mod = ($i % 2 );++$i;?><li>
                    <img src="<?php echo ($order["image"]); ?>" alt="things">
                    <div>
                        <p>商品名称/颜色/规格<br /><?php echo ($order["name"]); ?>--<?php echo ($order["color"]); ?>--<?php echo ($order["size"]); ?>
                        </p>
                        <p>产品数量</p>
                        <b>X<?php echo ($order["goodsnum"]); ?></b>
                        <strong>￥<span><?php echo ($order["totalprice"]); ?></span></strong>
                    </div>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
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
            <script type="text/javascript">
        function red1(){
            var st= $("#state").attr("value");
            var redp=$("p.red ");
            if(st!=""){
                for(var i = 0; i < $(redp).length; i++){
                    console.log($(redp)[i]);
                    var temp1 = i+1;
                    var temp2 =Number(st);
                    if(temp1==temp2){
                        
                        $($(redp)[i]).css("color","#ce4845");
                        //console.log($(redp)[i]);
                    }console.log($(redp)[i]);
                }console.log("hello"+st);
            }
            else
            $("p.reds").css("color","#ce4845");
        }
        red1();
        </script>
</body>

</html>