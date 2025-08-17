<?php
// 简单的缓存管理页面
require_once 'CacheManager.php';

// 启动会话
session_start();

// 检查是否是管理员（简单判断）
$isAdmin = isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;

if (!$isAdmin) {
    echo "<h1>访问被拒绝</h1>";
    echo "<p>只有管理员才能访问此页面</p>";
    exit;
}

$cacheManager = new CacheManager();
$message = '';
$messageType = '';

// 处理清除缓存操作
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'clear_all') {
        try {
            // 清除整个cache目录
            $cacheDir = __DIR__ . '/cache/';
            if (is_dir($cacheDir)) {
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($cacheDir, RecursiveDirectoryIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::CHILD_FIRST
                );
                
                $deletedCount = 0;
                foreach ($files as $file) {
                    if ($file->isDir()) {
                        rmdir($file->getPathname());
                    } else {
                        unlink($file->getPathname());
                        $deletedCount++;
                    }
                }
                
                // 重新创建cache目录结构
                mkdir($cacheDir . 'pages', 0755, true);
                
                $message = "缓存清除成功！已删除 {$deletedCount} 个文件";
                $messageType = 'success';
            } else {
                $message = "缓存目录不存在";
                $messageType = 'error';
            }
        } catch (Exception $e) {
            $message = "清除缓存失败: " . $e->getMessage();
            $messageType = 'error';
        }
    }
}

$stats = $cacheManager->getStats();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>缓存状态 - 星河影院</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #667eea; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat { background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #667eea; }
        .stat-number { font-size: 1.5em; font-weight: bold; color: #667eea; }
        .stat-label { color: #666; margin-top: 5px; }
        .info { background: #e7f3ff; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .btn { background: #667eea; color: white; padding: 10px 20px; border: none; border-radius: 5px; text-decoration: none; display: inline-block; }
        .btn:hover { background: #5a6fd8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>缓存状态</h1>
            <p>星河影院 - 24小时自动过期缓存</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="message <?= $messageType ?>" style="padding: 15px; margin-bottom: 20px; border-radius: 5px; <?= $messageType === 'success' ? 'background: #d4edda; color: #155724; border: 1px solid #c3e6cb;' : 'background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="info">
            <strong>缓存说明：</strong>
            <ul>
                <li>页面缓存自动过期时间：24小时</li>
                <li>过期缓存会在访问时自动删除</li>
                <li>无需手动清理，系统自动管理</li>
                <li>用户登录后的页面不会被缓存</li>
            </ul>
        </div>

        <div class="stats">
            <div class="stat">
                <div class="stat-number"><?= $stats['total_files'] ?></div>
                <div class="stat-label">缓存文件数</div>
            </div>
            <div class="stat">
                <div class="stat-number"><?= $stats['total_size'] ?></div>
                <div class="stat-label">缓存总大小</div>
            </div>
            <div class="stat">
                <div class="stat-number"><?= $stats['expired_files'] ?></div>
                <div class="stat-label">过期文件数</div>
            </div>
            <div class="stat">
                <div class="stat-number"><?= $stats['cache_ttl_hours'] ?>小时</div>
                <div class="stat-label">过期时间</div>
            </div>
        </div>
        
        <div class="info" style="margin-top: 20px;">
            <strong>数据库设置：</strong>
            <ul>
                <li>缓存状态: <span style="color: <?= $stats['cache_enabled'] ? '#28a745' : '#dc3545' ?>"><?= $stats['cache_enabled'] ? '启用' : '关闭' ?></span></li>
                <li>缓存时间: <?= $stats['cache_ttl_hours'] ?> 小时 (<?= $stats['cache_ttl'] ?> 秒)</li>
                <li>设置来源: cache_settings 数据表</li>
            </ul>
        </div>

        <div style="text-align: center; margin-bottom: 30px;">
            <form method="post" style="display: inline;" onsubmit="return confirm('确定要清除所有缓存吗？这将删除cache目录下的所有文件！')">
                <input type="hidden" name="action" value="clear_all">
                <button type="submit" class="btn" style="background: #dc3545;">清除所有缓存</button>
            </form>
        </div>

        <div style="text-align: center;">
            <a href="/" class="btn">返回首页</a>
        </div>
    </div>
</body>
</html>
