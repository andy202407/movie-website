<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no,viewport-fit=cover">
    <meta name="theme-color" content="#1a1a1a" />
    <title><?php if ($keyword): ?>搜索: <?= $this->escape($keyword) ?><?php else: ?>搜索影片<?php endif; ?> - 鱼鱼影院</title>
    <meta name="keywords" content="<?= $this->escape($keyword) ?>,搜索,免费观看,高清在线" />
    <meta name="description" content="在鱼鱼影院搜索'<?= $this->escape($keyword) ?>'的相关影片" />
    
    <link href="/template/yuyuyy/asset/css/common.css" rel="stylesheet" type="text/css" />
    <script src="/template/yuyuyy/asset/js/jquery.js"></script>
    <script src="/template/yuyuyy/asset/js/assembly.js"></script>
    <script src="/template/yuyuyy/asset/js/swiper.min.js"></script>
    <script>var maccms={"vod_mask":"mask-1","path2":"/","day":"2","jx":"0","so_off":"0","bt-style":"","login-login":"/","path":"","mid":"","aid":"1","url":"m.ql82.com ","wapurl":"m.ql82.com ","mob_status":"0"};</script>
    <script src="/template/yuyuyy/asset/js/ecscript.js"></script>
    <link rel="shortcut icon" href="/template/yuyuyy/asset/img/favicon.png" type="image/x-icon" />
</head>
<body class="theme2">

<!-- 头部导航 -->
<?php include 'components/header.php'; ?>

<!-- 搜索标题和搜索框 -->
<div class="container">
    <div class="search-header">
        <h1 class="search-title">
            <?php if ($keyword): ?>
                搜索结果: "<?= $this->escape($keyword) ?>"
            <?php else: ?>
                搜索影片
            <?php endif; ?>
        </h1>
        
        <div class="search-box">
            <form method="GET" action="/search">
                <div class="search-input-group">
                    <input type="text" name="wd" value="<?= $this->escape($keyword ?? '') ?>" 
                           placeholder="输入影片名称或关键词..." class="search-input-large">
                    <button type="submit" class="search-btn-large">
                        <i class="fa ds-sousuo"></i> 搜索
                    </button>
                </div>
            </form>
        </div>
        
        <?php if ($keyword && count($videos ?? []) > 0): ?>
        <div class="search-count">共找到 <?= count($videos) ?> 部相关影片</div>
        <?php endif; ?>
    </div>
</div>

<!-- 搜索结果 -->
<div class="container">
    <?php if ($keyword && empty($videos)): ?>
    <div class="empty-result">
        <div class="empty-icon">🔍</div>
        <h3>没有找到相关影片</h3>
        <p>请尝试使用其他关键词，或者检查拼写是否正确</p>
        <div class="search-suggestions">
            <p>建议搜索：</p>
            <div class="suggestion-tags">
                <a href="/search?wd=复仇者" class="suggestion-tag">复仇者</a>
                <a href="/search?wd=泰坦尼克" class="suggestion-tag">泰坦尼克</a>
                <a href="/search?wd=星际" class="suggestion-tag">星际</a>
                <a href="/search?wd=寄生虫" class="suggestion-tag">寄生虫</a>
            </div>
        </div>
    </div>
    <?php elseif (!empty($videos)): ?>
    <div class="search-results">
        <div class="video-grid">
            <?php foreach ($videos as $video): ?>
            <div class="video-item">
                <div class="video-poster">
                    <a href="/?page=play&id=<?= $video['id'] ?>" title="<?= $this->escape($video['title']) ?>">
                        <img src="<?= $this->escape($video['poster']) ?>" alt="<?= $this->escape($video['title']) ?>" />
                        <div class="video-overlay">
                            <div class="play-btn">
                                <i class="fa">&#xe593;</i>
                            </div>
                        </div>
                        <div class="video-rating">⭐ <?= $this->escape($video['rating']) ?></div>
                        <div class="video-year"><?= $this->escape($video['year']) ?>年</div>
                    </a>
                </div>
                <div class="video-info">
                    <h3 class="video-title">
                        <a href="?page=play&id=<?= $video['id'] ?>" title="<?= $this->escape($video['title']) ?>">
                            <?= $this->escape($video['title']) ?>
                        </a>
                    </h3>
                    <div class="video-meta">
                        <span class="video-category"><?= $this->escape($categories[$video['category_id']]['name'] ?? '影片') ?></span>
                        <span class="video-duration"><?= $this->escape($video['duration']) ?></span>
                    </div>
                    <div class="video-desc">
                        <?= $this->escape(mb_substr($video['description'], 0, 80, 'UTF-8')) ?>...
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
    <!-- 无搜索关键词时显示热门搜索 -->
    <div class="hot-search">
        <h2 class="hot-title">🔥 热门搜索</h2>
        <div class="hot-tags">
            <a href="/search?wd=复仇者联盟" class="hot-tag">复仇者联盟</a>
            <a href="/search?wd=泰坦尼克号" class="hot-tag">泰坦尼克号</a>
            <a href="/search?wd=星际穿越" class="hot-tag">星际穿越</a>
            <a href="/search?wd=寄生虫" class="hot-tag">寄生虫</a>
            <a href="/search?wd=闪灵" class="hot-tag">闪灵</a>
        </div>
        
        <h2 class="category-title">📂 分类浏览</h2>
        <div class="category-links">
            <?php foreach ($categories ?? [] as $category): ?>
            <a href="?page=list&category=<?= $category['id'] ?>" class="category-link">
                <?= $this->escape($category['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.crumb {
    padding: 1.5rem 0;
    color: #ccc;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.crumb a {
    color: #667eea;
    text-decoration: none;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
}

.crumb a:hover {
    color: #fff;
    transform: translateY(-1px);
}

.crumb .fa {
    font-size: 12px;
    color: #666;
    margin: 0 0.2rem;
}

.crumb span:last-child {
    color: #fff;
    font-weight: 500;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.search-header {
    text-align: center;
    margin: 2rem 0;
}

.search-title {
    font-size: 2.5rem;
    color: #fff;
    margin-bottom: 2rem;
}

.search-box {
    margin-bottom: 2rem;
}

.search-input-group {
    display: flex;
    max-width: 600px;
    margin: 0 auto;
    gap: 1rem;
}

.search-input-large {
    flex: 1;
    padding: 1rem 1.5rem;
    font-size: 1.1rem;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 30px;
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    outline: none;
    transition: all 0.3s;
}

.search-input-large::placeholder {
    color: #ccc;
}

.search-input-large:focus {
    border-color: #667eea;
    background: rgba(255, 255, 255, 0.15);
}

.search-btn-large {
    padding: 1rem 2rem;
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 30px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-btn-large:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.search-count {
    color: #667eea;
    font-size: 1.2rem;
    font-weight: 500;
    text-align: center;
    padding: 1rem;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 15px;
    margin: 1rem 0;
    border: 1px solid rgba(102, 126, 234, 0.2);
}

.search-results {
    padding: 2rem 1rem;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.02) 0%, rgba(102, 126, 234, 0.05) 100%);
    border-radius: 25px;
    margin: 2rem 0;
}

.video-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2.5rem;
    margin: 2rem 0 3rem 0;
    padding: 0 1rem;
    justify-items: center;
}

.video-item {
    background: rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    max-width: 350px;
    width: 100%;
}

.video-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.video-poster {
    position: relative;
    height: 350px;
    overflow: hidden;
}

.video-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.video-item:hover .video-poster img {
    transform: scale(1.05);
}

.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.video-item:hover .video-overlay {
    opacity: 1;
}

.play-btn {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #333;
    transition: transform 0.3s;
}

.play-btn:hover {
    transform: scale(1.1);
}

.video-rating {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(243, 156, 18, 0.9);
    color: white;
    padding: 0.3rem 0.6rem;
    border-radius: 15px;
    font-size: 0.9rem;
    font-weight: bold;
}

.video-year {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 0.3rem 0.6rem;
    border-radius: 15px;
    font-size: 0.9rem;
}

.video-info {
    background: rgba(0, 0, 0, 0.15);
    padding: 20px 10px;
}

.video-title {
    margin-bottom: 1.2rem;
}

.video-title a {
    color: #fff;
    text-decoration: none;
    font-size: 1.1rem;
    font-weight: 600;
    transition: color 0.3s;
}

.video-title a:hover {
    color: #667eea;
}

.video-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.2rem;
    font-size: 0.9rem;
    color: #ccc;
}

.video-category {
    background: rgba(102, 126, 234, 0.2);
    color: #667eea;
    padding: 0.2rem 0.6rem;
    border-radius: 10px;
    font-size: 0.8rem;
}

.video-desc {
    color: #ccc;
    line-height: 1.6;
    font-size: 0.85rem;
    opacity: 0.9;
}

.empty-result {
    text-align: center;
    color: #fff;
    margin: 5rem 0;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.empty-result h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.empty-result p {
    margin-bottom: 2rem;
    color: #ccc;
}

.search-suggestions {
    margin-top: 2rem;
}

.suggestion-tags, .hot-tags, .category-links {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 1rem;
}

.suggestion-tag, .hot-tag, .category-link {
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    text-decoration: none;
    border-radius: 20px;
    transition: all 0.3s;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.suggestion-tag:hover, .hot-tag:hover, .category-link:hover {
    background: rgba(102, 126, 234, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.hot-search {
    text-align: center;
    margin: 3rem 0;
}

.hot-title, .category-title {
    color: #fff;
    font-size: 1.5rem;
    margin: 2rem 0 1rem;
}

@media (max-width: 768px) {
    .search-input-group {
        flex-direction: column;
        gap: 1rem;
    }
    
    .video-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    .search-title {
        font-size: 2rem;
    }
    
    .suggestion-tags, .hot-tags, .category-links {
        flex-direction: column;
        align-items: center;
    }
    
    .video-item {
        max-width: 100%;
    }
}

/* 单个搜索结果时的特殊样式 */
.video-grid:has(.video-item:only-child) {
    justify-content: center;
}

.video-grid:has(.video-item:only-child) .video-item {
    max-width: 400px;
    margin: 0 auto;
}


</style>

<?php include 'components/footer.php'; ?>
</body>
</html>