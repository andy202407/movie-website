<?php
require_once 'VideoModel.php';
require_once 'TemplateEngine.php';

// 直接模拟Router的list方法
$categoryId = 2;
$page = 1;
$perPage = 12;
$sort = 'latest';

$videoModel = new VideoModel();
$templateEngine = new TemplateEngine('frontend-template');

$category = $videoModel->getCategoryById($categoryId);
$categories = $videoModel->getAllCategories();

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>测试</title></head><body>";
echo "<h1>调试信息</h1>";
echo "<p>categoryId: $categoryId</p>";
echo "<p>category data: " . print_r($category, true) . "</p>";
echo "<p>category name: " . $category['name'] . "</p>";

if (!$category) {
    echo "<p>ERROR: 分类不存在</p>";
    exit;
}

// 获取分类下的所有视频
$allVideos = $videoModel->getVideosByCategory($categoryId);

$total = count($allVideos);
$totalPages = max(1, ceil($total / $perPage));
$page = min($page, $totalPages);

// 分页处理
$offset = ($page - 1) * $perPage;
$videos = array_slice($allVideos, $offset, $perPage);

// 创建分类索引映射
$categoryMap = [];
foreach ($categories as $cat) {
    $categoryMap[$cat['id']] = $cat;
}

$templateEngine->assignArray([
    'title' => $category['name'] . ' - 第' . $page . '页 - 星河影院',
    'category' => $category,
    'categories' => $categories,
    'categoryMap' => $categoryMap,
    'videos' => $videos,
    'categoryId' => $categoryId,
    'currentPage' => $page,
    'totalPages' => $totalPages,
    'total' => $total,
    'sort' => $sort,
    'current_page' => 'list'
]);

echo "<h2>模板变量</h2>";
echo "<p>category['name']: " . $category['name'] . "</p>";
echo "<p>categoryId: $categoryId</p>";
echo "<p>total: $total</p>";

echo "<h2>渲染结果</h2>";
try {
    $content = $templateEngine->render('list');
    echo "<div style='border:1px solid #ccc; padding:10px;'>";
    // 只显示标题部分
    if (preg_match('/<h1[^>]*>(.*?)<\/h1>/s', $content, $matches)) {
        echo "标题: " . $matches[1];
    }
    if (preg_match('/<!-- DEBUG: categoryId=([^,]+), category_name=([^>]+) -->/', $content, $matches)) {
        echo "<br>调试信息: categoryId=" . $matches[1] . ", category_name=" . $matches[2];
    }
    echo "</div>";
} catch (Exception $e) {
    echo "<p>ERROR: " . $e->getMessage() . "</p>";
}

echo "</body></html>";
?>



