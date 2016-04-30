<?php
//当前页面的完整URL地址,用于操作返回上一页
 defined('CURRENT_URL') or define('CURRENT_URL',base64_encode($_SERVER["REQUEST_URI"]));