<?php
// 影视站入口文件 - 基于原生PHP模板引擎

// 蜘蛛检测和阻止
function isSpider() {
    $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $spiderPatterns = [
        'bot', 'crawler', 'spider', 'scraper', 'scraper', 'crawler',
        'googlebot', 'bingbot', 'baiduspider', 'sogou', '360spider',
        'yisouspider', 'yandexbot', 'facebookexternalhit', 'twitterbot',
        'linkedinbot', 'whatsapp', 'telegrambot', 'slackbot', 'discordbot',
        'curl', 'wget', 'python', 'java', 'perl', 'ruby', 'php'
    ];
    
    $userAgent = strtolower($userAgent);
    foreach ($spiderPatterns as $pattern) {
        if (strpos($userAgent, $pattern) !== false) {
            return true;
        }
    }
    return false;
}

// 阻止蜘蛛访问
if (isSpider()) {
    http_response_code(403);
    echo "Access Denied";
    exit;
}

// 引入核心类
require_once 'TemplateEngine.php';
require_once 'Database.php';
require_once 'VideoModel.php';
require_once 'Router.php';
require_once 'visitor_helper.php';

// 错误处理
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 设置时区
date_default_timezone_set('Asia/Shanghai');

try {
    // 记录访客访问
    autoRecordVisit();
    
    // 检查是否是API请求
    $requestUri = $_SERVER['REQUEST_URI'];
    $path = parse_url($requestUri, PHP_URL_PATH);
    if (strpos($path, '/api/') === 0) {
        // 处理用户API请求
        if (strpos($path, '/api/user') === 0) {
            require_once 'api/user.php';
            exit;
        }
        // 其他API请求
        if (file_exists('api.php')) {
            require_once 'api.php';
            exit;
        }
    }
    
    // 初始化模板引擎
    $templateEngine = new TemplateEngine('frontend-template');
    
    // 初始化数据模型
    $videoModel = new VideoModel();
    
    // 初始化路由器
    $router = new Router($templateEngine, $videoModel);
    
    // 执行路由
    $router->route();
    
} catch (Exception $e) {
    // 错误处理
    http_response_code(500);
    echo "<h1>系统错误</h1>";
    echo "<p>错误信息: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><a href='?'>返回首页</a></p>";
}