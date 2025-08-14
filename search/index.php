<?php
// 搜索页面入口文件
// 确保GET参数正确传递
if (!isset($_GET['wd'])) {
    $_GET['wd'] = '';
}

// 引入必要的文件
require_once dirname(__DIR__) . '/VideoModel.php';
require_once dirname(__DIR__) . '/TemplateEngine.php';

// 初始化 - 使用绝对路径
$templateDir = dirname(__DIR__) . '/frontend-template';
$videoModel = new VideoModel();
$templateEngine = new TemplateEngine($templateDir);

// 获取搜索关键词
$keyword = $_GET['wd'] ?? '';
$videos = [];

if ($keyword) {
    $videos = $videoModel->searchVideos($keyword);
}

$categories = $videoModel->getAllCategories();

$title = $keyword ? '搜索: ' . $keyword . ' - 星河影院' : '搜索影片 - 星河影院';

// 分配变量到模板
$templateEngine->assignArray([
    'title' => $title,
    'keyword' => $keyword,
    'videos' => $videos,
    'categories' => $categories,
    'current_page' => 'search'
]);

// 显示搜索页面
$templateEngine->display('search');
?>
