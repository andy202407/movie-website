<?php
require_once 'VideoModel.php';

$videoModel = new VideoModel();

echo "测试图片路径处理\n";
echo "================\n\n";

// 测试不同的路径格式
$testPaths = [
    '/public/uploads/covers/20250813/104cd4f88e48e6c3671fd71c789070e7.webp',
    'public/uploads/covers/20250813/104cd4f88e48e6c3671fd71c789070e7.webp',
    '/uploads/covers/20250813/104cd4f88e48e6c3671fd71c789070e7.webp',
    'uploads/covers/20250813/104cd4f88e48e6c3671fd71c789070e7.webp'
];

foreach ($testPaths as $path) {
    echo "原始路径: {$path}\n";
    
    // 模拟formatVideo中的处理逻辑
    $poster = str_replace('/public/', '/', $path);
    
    // 如果路径仍然以/public开头，再次处理
    if (strpos($poster, '/public/') === 0) {
        $poster = str_replace('/public/', '/', $poster);
    }
    
    // 确保路径以/开头
    if (strpos($poster, '/') !== 0) {
        $poster = '/' . $poster;
    }
    
    echo "处理后: {$poster}\n";
    echo "---\n";
}

echo "\n测试实际文件访问:\n";
$testFile = '/uploads/covers/20250813/104cd4f88e48e6c3671fd71c789070e7.webp';
echo "测试文件: {$testFile}\n";

// 检查文件是否存在
$fullPath = __DIR__ . '/public' . $testFile;
echo "完整路径: {$fullPath}\n";
echo "文件存在: " . (file_exists($fullPath) ? '是' : '否') . "\n";

// 检查web可访问路径
$webPath = 'http://' . $_SERVER['HTTP_HOST'] . $testFile;
echo "Web路径: {$webPath}\n";
?> 