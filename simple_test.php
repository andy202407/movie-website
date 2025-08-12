<?php
// 直接访问这个文件进行测试
require_once 'Router.php';

// 设置GET参数
$_GET['page'] = 'list';
$_GET['category'] = '2';

$router = new Router();
$router->route();
?>
