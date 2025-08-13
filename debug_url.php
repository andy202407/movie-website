<?php
echo "<!DOCTYPE html><html><head><meta charset='utf-8'></head><body>";
echo "<h1>URL调试信息</h1>";

echo "<h2>GET参数:</h2>";
echo "<pre>";
print_r($_GET);
echo "</pre>";

echo "<h2>REQUEST_URI:</h2>";
echo "<p>" . $_SERVER['REQUEST_URI'] . "</p>";

echo "<h2>QUERY_STRING:</h2>";
echo "<p>" . ($_SERVER['QUERY_STRING'] ?? 'empty') . "</p>";

if (isset($_GET['category'])) {
    echo "<h2>分类处理:</h2>";
    $categoryId = $_GET['category'];
    echo "<p>原始值: " . var_export($categoryId, true) . "</p>";
    echo "<p>类型: " . gettype($categoryId) . "</p>";
    echo "<p>转整数: " . intval($categoryId) . "</p>";
    
    require_once 'VideoModel.php';
    $videoModel = new VideoModel();
    $category = $videoModel->getCategoryById($categoryId);
    
    echo "<h3>数据库查询结果:</h3>";
    echo "<pre>";
    print_r($category);
    echo "</pre>";
}

echo "</body></html>";
?>








