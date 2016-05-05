<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <title>首页</title>
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="/505/Public/css/style.css" /> -->
    <link rel="stylesheet" type="text/css" href="/505/Public/css/style.css" /> 
    <!-- <link rel="stylesheet" href="css/flickerplate.css"> -->
    <link rel="stylesheet" type="text/css" href="/505/Public/css/flickerplate.css" /> 
    <!--[if lt IE 9]>
    <script type="text/javascript" src="js/html5shiv.min.js"></script>
    <![endif]-->
</head>

<body>
    <header>
        <nav class="top-nav">
            <a href="#" class="add"><img src="/505/Public/images/add.png" alt="添加"></a>
            <input id="content" type="text" placeholder="初夏好物...">
            <a href="#" onclick="jump();"><img src="/505/Public/images/search.png" alt="搜索"></a>
        </nav>
    </header>
    <section class="focus-wrap">
        <div class="flicker-example" data-block-text="false">
            <ul>
                <?php if(is_array($rolllist)): $i = 0; $__LIST__ = $rolllist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$good): $mod = ($i % 2 );++$i;?><li data-background="<?php echo ($good["image"]); ?>">
                        <div class="flick-title"><?php echo ($good["name"]); ?></div>
                        <div class="flick-sub-text">￥<?php echo ($good["price"]); ?></div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </section>
    <section class="categories">
        <ul>
            <li>
                <a href="homepage.html?classify=服装">
                    <img src="/505/Public/images/clothes.png" alt="服装">
                    <p>服装</p>
                </a>
            </li>
            <li>
                <a href="homepage.html?classify=鞋包">
                    <img src="/505/Public/images/shoes.png" alt="鞋包">
                    <p>鞋包</p>
                </a>
            </li>
            <li>
                <a href="homepage.html?classify=配饰">
                    <img src="/505/Public/images/peishi.png" alt="配饰">
                    <p>配饰</p>
                </a>
            </li>
            <li>
                <a href="homepage.html?classify=数码">
                    <img src="/505/Public/images/com.png" alt="数码">
                    <p>数码</p>
                </a>
            </li>
        </ul>
        <ul>
            <li>
                <a href="homepage.html?classify=读物">
                    <img src="/505/Public/images/read.png" alt="读物">
                    <p>读物</p>
                </a>
            </li>
            <li>
                <a href="homepage.html?classify=食品">
                    <img src="/505/Public/images/eat.png" alt="食品">
                    <p>食品</p>
                </a>
            </li>
            <li>
                <a href="homepage.html?classify=超市">
                    <img src="/505/Public/images/market.png" alt="超市">
                    <p>超市</p>
                </a>
            </li>
            <li>
                <a href="homepage.html?classify=家电">
                    <img src="/505/Public/images/count.png" alt="家电">
                    <p>家电</p>
                </a>
            </li>
        </ul>
    </section>
    <section class="recommend">
        <h3><img src="/505/Public/images/heart.png" alt="">好物推荐</h2>
        
        <ul class="reco-list">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$good): $mod = ($i % 2 );++$i;?><li>
                    <a href="details.html?goodsid=<?php echo ($good["id"]); ?>"><img src="<?php echo ($good["image"]); ?>" alt=""></a>
                    <a href="#"><span>HOT</span><?php echo ($good["name"]); ?></a>
                    <!-- 这里要不要绑good.detail -->
                    <!-- <p>销量1w 包邮</p> -->
                    <!-- <p><?php echo ($good["detail"]); ?></p> -->
                    <strong>￥<span><?php echo ($good["price"]); ?></span></strong>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </section>
    <footer>
        <nav>
            <ul>
                <li><a href="homepage.html">
                        <img src="/505/Public/images/home-pressed.png" alt="主页">
                        <p style="color: #b63c39;">主页</p>
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
    <script type="text/javascript" src="/505/Public/js/jquery.finger.min.js"></script>
    <script type="text/javascript" src="/505/Public/js/modernizr-custom-v2.7.1.min.js"></script>
    <script type="text/javascript" src="/505/Public/js/flickerplate.min.js"></script> 
    <script type="text/javascript">
    $(document).ready(function() {
        $('.flicker-example').flicker();
    });
    </script>
    <script type="text/javascript">
        function jump(){
            var content=document.getElementById('content').value.trim();
             window.location.href="homepage?search="+content;
        }
    </script>
</body>

</html>