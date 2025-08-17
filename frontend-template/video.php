<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no,viewport-fit=cover">
    <meta name="theme-color" content="#1a1a1a" />
    <title><?= $this->escape($video['title']) ?> - 星海影院</title>
    <meta name="keywords" content="<?= $this->escape($video['title']) ?>,免费观看,高清在线" />
    <meta name="description" content="<?= $this->escape($video['description']) ?>" />
    
    <link href="/template/yuyuyy/asset/css/common.css" rel="stylesheet" type="text/css" />
    <script src="/template/yuyuyy/asset/js/jquery.js"></script>
    <script src="/template/yuyuyy/asset/js/assembly.js"></script>
    <script src="/template/yuyuyy/asset/js/swiper.min.js"></script>
    <script>var maccms={"vod_mask":"mask-1","path2":"/","day":"2","jx":"0","so_off":"0","bt-style":"","login-login":"/","path":"","mid":"","aid":"1","url":"m.ql83.com ","wapurl":"m.ql83.com ","mob_status":"0"};</script>
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

<!-- 影片详情 -->
<div class="container">
    <div class="video-detail-wrap">
        <div class="video-info-box">
            <div class="video-poster">
                <img src="<?= $this->escape($video['poster']) ?>" alt="<?= $this->escape($video['title']) ?>" />
                <div class="video-rating">⭐ <?= $this->escape($video['rating']) ?></div>
            </div>
            <div class="video-info">
                <h1><?= $this->escape($video['title']) ?></h1>
                <div class="video-meta">
                    <div class="meta-row">
                        <span class="meta-item">
                            <i class="fa ds-dianying"></i>
                            <?= $this->escape($category['name']) ?>
                        </span>
                        <span class="meta-item">
                            <i class="fa ds-shijian"></i>
                            <?= $this->escape($video['duration']) ?>
                        </span>
                        <span class="meta-item">
                            <i class="fa ds-rili"></i>
                            <?= $this->escape($video['year']) ?>年
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-item">
                            <i class="fa ds-daoyan"></i>
                            导演：<?= $this->escape($video['director']) ?>
                        </span>
                    </div>
                </div>
                <div class="video-desc">
                    <h3>剧情简介</h3>
                    <p><?= $this->escape($video['description']) ?></p>
                </div>
                <div class="video-actions">
                    <a href="?page=play&id=<?= $video['id'] ?>" class="btn-play">
                        <i class="fa ds-bofang1"></i>立即播放
                    </a>
                    <a href="?page=list&category=<?= $video['category_ids'][0] ?? '' ?>" class="btn-more">
                        <i class="fa ds-liebiao"></i>更多<?= $this->escape($video['category_names'][0] ?? '影片') ?>
                    </a>
                    <a href="javascript:void(0)" class="btn-collect" onclick="alert('收藏功能')">
                        <i class="fa ds-shoucang"></i>收藏
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.video-detail-wrap {
    margin: 2rem 0;
}

.video-info-box {
    display: flex;
    gap: 2rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    padding: 2rem;
}

.video-poster {
    position: relative;
    flex-shrink: 0;
    width: 300px;
}

.video-poster img {
    width: 100%;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.video-rating {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(243, 156, 18, 0.9);
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-weight: bold;
    backdrop-filter: blur(5px);
}

.video-info {
    flex: 1;
}

.video-info h1 {
    font-size: 2rem;
    color: #fff;
    margin-bottom: 1.5rem;
}

.video-meta {
    margin-bottom: 2rem;
}

.meta-row {
    display: flex;
    gap: 2rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.meta-item {
    color: #ccc;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.meta-item i {
    color: #667eea;
    font-size: 1.1rem;
}

.video-desc {
    color: #ddd;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.video-desc h3 {
    color: #fff;
    font-size: 1.2rem;
    margin-bottom: 1rem;
    font-weight: 500;
}

.video-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-play, .btn-more, .btn-collect {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 1.5rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-play {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
}

.btn-more {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-collect {
    background: rgba(243, 156, 18, 0.2);
    color: #f39c12;
    border: 1px solid #f39c12;
}

.btn-play:hover, .btn-more:hover, .btn-collect:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

@media (max-width: 768px) {
    .video-info-box {
        flex-direction: column;
        padding: 1rem;
    }
    
    .video-poster {
        width: 100%;
        max-width: 300px;
        margin: 0 auto;
    }
    
    .video-info h1 {
        font-size: 1.5rem;
        text-align: center;
    }
    
    .meta-row {
        justify-content: center;
    }
    
    .video-actions {
        justify-content: center;
    }
}
</style>

<!-- 推荐影片 -->
<div class="container">
    <div class="public-box">
        <div class="public-list-box-x public-con-hdt rel">
            <h2 class="public-list-head padding-b public-padding-l">
                <i class="fa public-list-icon ds-tuijian"></i>推荐影片
            </h2>
        </div>
        
        <div class="public-r list-swiper rel overflow">
            <div class="swiper-wrapper">
                <?php foreach ($recommended ?? [] as $recommendedVideo): ?>
                    <?php if ($recommendedVideo['id'] != $video['id']): ?>
                    <div class="public-list-box public-pic-b swiper-slide">
                        <div class="public-list-div public-list-bj">
                            <a target="_blank" class="public-list-exp" href="?page=play&id=<?= $recommendedVideo['id'] ?>" title="<?= $this->escape($recommendedVideo['title']) ?>">
                                <img class="lazy lazy1 gen-movie-img mask-1"
                                     referrerpolicy="no-referrer"
                                     src="<?= $this->escape($recommendedVideo['poster']) ?>"
                                     alt="<?= $this->escape($recommendedVideo['title']) ?>封面图" />
                                <span class="public-bg"></span>
                                <span class="public-prt hide cr6">推荐</span>
                                <span class="public-list-prb hide ft2">
                                    评分: <?= $this->escape($recommendedVideo['rating']) ?>
                                </span>
                                <span class="public-play"><i class="fa">&#xe593;</i></span>
                            </a>
                        </div>
                        <div class="public-list-button">
                            <a target="_blank" class="time-title hide ft4" href="?page=play&id=<?= $recommendedVideo['id'] ?>" title="<?= $this->escape($recommendedVideo['title']) ?>"><?= $this->escape($recommendedVideo['title']) ?></a>
                            <div class="public-list-subtitle cor5 hide ft2"><?= $this->escape(mb_substr($recommendedVideo['description'], 0, 60, 'UTF-8')) ?>...</div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
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

<script>
// 初始化推荐列表轮播
var listSwiper = new Swiper('.list-swiper', {
    slidesPerView: 'auto',
    spaceBetween: 20,
    observer: true,
    observeParents: true,
    breakpoints: {
        768: {
            slidesPerView: 4,
        },
        1200: {
            slidesPerView: 6,
        }
    }
});
</script>

<style>
.detail-box {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    margin: 2rem 0;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
}

.detail-pic img {
    width: 100%;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.detail-title {
    font-size: 2rem;
    color: #fff;
    margin-bottom: 1rem;
}

.detail-meta {
    margin-bottom: 1.5rem;
}

.meta-item {
    display: flex;
    margin-bottom: 0.5rem;
}

.meta-label {
    color: #ccc;
    width: 60px;
}

.meta-value {
    color: #fff;
}

.rating-score {
    color: #f39c12;
    font-weight: bold;
}

.detail-desc {
    color: #ddd;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.detail-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-play, .btn-more, .btn-collect {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 1.5rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s;
}

.btn-play {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
}

.btn-more {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-collect {
    background: rgba(243, 156, 18, 0.2);
    color: #f39c12;
    border: 1px solid #f39c12;
}

.btn-play:hover, .btn-more:hover, .btn-collect:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}



@media (max-width: 768px) {
    .detail-box {
        grid-template-columns: 1fr;
        text-align: center;
    }
}
</style>

</body>
</html>