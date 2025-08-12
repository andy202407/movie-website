<?php
require_once 'VideoModel.php';

echo "=== 测试分类获取 ===\n";

$videoModel = new VideoModel();

// 测试获取所有分类
echo "\n1. 获取所有分类：\n";
$categories = $videoModel->getAllCategories();
foreach ($categories as $index => $category) {
    echo "索引 {$index}: ID={$category['id']}, 名称={$category['name']}\n";
}

// 测试获取特定分类
echo "\n2. 获取分类ID=2的信息：\n";
$category2 = $videoModel->getCategoryById(2);
if ($category2) {
    echo "ID={$category2['id']}, 名称={$category2['name']}\n";
} else {
    echo "未找到分类ID=2\n";
}

// 测试获取分类下的视频
echo "\n3. 获取分类ID=2下的视频：\n";
$videos = $videoModel->getVideosByCategory(2);
foreach ($videos as $video) {
    echo "视频: {$video['title']}, 分类: {$video['category_name']}\n";
}

echo "\n=== 测试完成 ===\n";
?>
