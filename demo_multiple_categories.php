<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>多个分类功能演示</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .video-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .video-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .video-categories {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }
        .category-tag {
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .video-meta {
            color: #666;
            font-size: 14px;
        }
        .category-section {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .category-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎬 多个分类功能演示</h1>
        <p>展示视频可以同时属于多个分类的功能</p>
    </div>

    <div class="success-message">
        ✅ <strong>功能说明：</strong> 现在每个视频可以同时属于多个分类，比如一个视频可以既是"科幻片"又是"喜剧片"。
    </div>

    <?php
    require_once 'VideoModel.php';
    
    $videoModel = new VideoModel();
    $videos = $videoModel->getAllVideos();
    $categories = $videoModel->getAllCategories();
    ?>

    <div class="category-section">
        <h2 class="category-title">📺 所有视频及其分类</h2>
        <div class="video-grid">
            <?php foreach ($videos as $video): ?>
            <div class="video-card">
                <div class="video-title"><?= htmlspecialchars($video['title']) ?></div>
                <div class="video-categories">
                    <?php foreach ($video['category_names'] as $categoryName): ?>
                    <span class="category-tag"><?= htmlspecialchars($categoryName) ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="video-meta">
                    <div>年份: <?= htmlspecialchars($video['year']) ?></div>
                    <div>时长: <?= htmlspecialchars($video['duration']) ?></div>
                    <div>导演: <?= htmlspecialchars($video['director']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="category-section">
        <h2 class="category-title">🔍 按分类查看视频</h2>
        <?php foreach ($categories as $category): ?>
        <?php $categoryVideos = $videoModel->getVideosByCategory($category['id']); ?>
        <?php if (!empty($categoryVideos)): ?>
        <div style="margin-bottom: 25px;">
            <h3 style="color: #667eea; margin-bottom: 15px;">
                <?= htmlspecialchars($category['name']) ?> 
                <span style="color: #666; font-size: 14px;">(找到 <?= count($categoryVideos) ?> 个视频)</span>
            </h3>
            <div class="video-grid" style="grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
                <?php foreach ($categoryVideos as $video): ?>
                <div class="video-card" style="padding: 15px;">
                    <div class="video-title" style="font-size: 16px;"><?= htmlspecialchars($video['title']) ?></div>
                    <div class="video-categories">
                        <?php foreach ($video['category_names'] as $catName): ?>
                        <span class="category-tag" style="background: <?= $catName === $category['name'] ? '#28a745' : '#667eea' ?>;">
                            <?= htmlspecialchars($catName) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="category-section">
        <h2 class="category-title">💡 技术特点</h2>
        <ul style="line-height: 1.8; color: #555;">
            <li><strong>数据库设计：</strong> 使用JSON字段存储多个分类ID</li>
            <li><strong>向后兼容：</strong> 支持旧的单个分类ID字段</li>
            <li><strong>灵活查询：</strong> 可以按任意分类查找视频</li>
            <li><strong>前端展示：</strong> 分类以标签形式显示，用斜杠分隔</li>
            <li><strong>性能优化：</strong> 使用LIKE查询替代复杂的JSON函数</li>
        </ul>
    </div>
</body>
</html> 