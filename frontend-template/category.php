<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no,viewport-fit=cover">
    <meta name="theme-color" content="#1a1a1a" />
    <title><?= $this->escape($category['name']) ?> - 星海影院</title>
    <meta name="keywords" content="<?= $this->escape($category['name']) ?>,免费观看,高清在线" />
    <meta name="description" content="星海影院<?= $this->escape($category['name']) ?>频道，提供最新最全的<?= $this->escape($category['name']) ?>资源" />
    
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
<div class="head flex between no-null header_nav0">
    <div class="left flex">
        <div class="logo">
            <a class="logo-brand" href="/">
                <img class="logo1 none" src="/template/yuyuyy/asset/img/logo-1.png" alt="星海影院">
                <img class="logo2 none" src="/template/yuyuyy/asset/img/logo-2.png" alt="星海影院">
            </a>
        </div>
        <div class="head-nav ft4 roll bold0 pc-show1 wap-show1">
            <ul class="swiper-wrapper">
                <li class="swiper-slide"><a target="_self" href="/" class=""><em class="fa ds-zhuye"></em><em class="fa none ds-zhuye2"></em>首页</a></li>
                <?php foreach ($categories ?? [] as $cat): ?>
                <li class="swiper-slide"><a target="_self" href="?page=category&id=<?= $cat['id'] ?>" class="<?= $cat['id'] == $category['id'] ? 'current cor6' : '' ?>"><em class="fa ds-dianying"></em><em class="fa none ds-dianying2"></em><?= $this->escape($cat['name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="right flex">
        <div class="this-search">
            <form id="search2" name="search" class="flex-public" method="get" action="/">
                <input type="hidden" name="page" value="search">
                <input type="text" name="q" class="this-input flex-auto cor4" value="<?= $this->escape($_GET['q'] ?? '') ?>" placeholder="搜索影片..." autocomplete="off">
                <button type="submit" class="fa ds-sousuo ol2"></button>
            </form>
        </div>
    </div>
</div>

<!-- 面包屑导航 -->
<div class="container">
    <div class="crumb clearfix">
        <a href="/">首页</a>
        <span class="fa">&#xe622;</span>
        <span><?= $this->escape($category['name']) ?></span>
    </div>
</div>

<!-- 分类标题 -->
<div class="container">
    <div class="category-header">
        <h1 class="category-title"><?= $this->escape($category['name']) ?></h1>
        <div class="category-count">共找到 <?= count($videos ?? []) ?> 部影片</div>
    </div>
</div>

<!-- 影片列表 -->
<div class="container">
    <?php if (empty($videos)): ?>
    <div class="empty-result">
        <div class="empty-icon">📺</div>
        <h3>暂无该分类的影片</h3>
        <p>请尝试其他分类或稍后再来</p>
        <a href="/" class="btn-back">返回首页</a>
    </div>
    <?php else: ?>
    <div class="video-grid">
        <?php foreach ($videos as $video): ?>
        <div class="video-item">
            <div class="video-poster">
                <a href="?page=play&id=<?= $video['id'] ?>" title="<?= $this->escape($video['title']) ?>">
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
                    <span class="video-duration"><?= $this->escape($video['duration']) ?></span>
                    <span class="video-director"><?= $this->escape($video['director']) ?></span>
                </div>
                <div class="video-desc">
                    <?= $this->escape(mb_substr($video['description'], 0, 80, 'UTF-8')) ?>...
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- 脚部 -->
<div class="foot">
    <div class="foot-min">
        <div class="foot-main-a">
            <div class="foot-main-a1">
                <h3>免责声明</h3>
                <div>
                    <p>本站所有视频和图片均来自网络，版权归原创者所有，本网站只提供web页面服务，并不提供资源存储，也不参与录制、上传。</p>
                    <p>若本站收录的内容侵犯了您的合法权益，请联系我们，我们会及时删除。</p>
                </div>
            </div>
        </div>
        <div class="foot-bottom">
            <p>&copy; 2024 星海影院. 基于原生PHP模板引擎构建</p>
        </div>
    </div>
</div>

<style>
.crumb {
    padding: 1rem 0;
    color: #ccc;
}

.crumb a {
    color: #667eea;
    text-decoration: none;
}

.crumb a:hover {
    color: #fff;
}

.crumb .fa {
    margin: 0 0.5rem;
    color: #666;
}

.category-header {
    text-align: center;
    margin: 2rem 0;
}

.category-title {
    font-size: 2.5rem;
    color: #fff;
    margin-bottom: 0.5rem;
}

.category-count {
    color: #ccc;
    font-size: 1.1rem;
}

.video-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.video-item {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s;
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
    padding: 1.5rem;
}

.video-title {
    margin-bottom: 0.5rem;
}

.video-title a {
    color: #fff;
    text-decoration: none;
    font-size: 1.2rem;
    font-weight: bold;
    transition: color 0.3s;
}

.video-title a:hover {
    color: #667eea;
}

.video-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: #ccc;
}

.video-desc {
    color: #ddd;
    line-height: 1.5;
    font-size: 0.9rem;
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

.btn-back {
    display: inline-block;
    padding: 0.8rem 2rem;
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-weight: bold;
    transition: transform 0.3s;
}

.btn-back:hover {
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .video-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1rem;
    }
    
    .category-title {
        font-size: 2rem;
    }
}
</style>

</body>
</html>