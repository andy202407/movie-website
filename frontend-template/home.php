<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no,viewport-fit=cover">
    <meta name="theme-color" content="#1a1a1a" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <title><?= $this->escape($title ?? '星海影院 - 免费在线观看高清电影电视剧综艺动漫') ?></title>
    <meta name="keywords" content="星海影院,免费电影,在线观看,高清影视,最新电影,热播电视剧,免费追剧,影视大全,电影天堂,在线影院,综艺节目,动漫在线" />
    <meta name="description" content="星海影院 - 专业的免费影视在线观看平台,提供最新电影、热播电视剧、精彩综艺、热门动漫等海量高清影视资源。" />
    
    <link href="/template/yuyuyy/asset/css/common.css" rel="stylesheet" type="text/css" />
    <script src="/template/yuyuyy/asset/js/jquery.js"></script>
    <script src="/template/yuyuyy/asset/js/assembly.js"></script>
    <script src="/template/yuyuyy/asset/js/swiper.min.js"></script>
    <script>var maccms={"vod_mask":"mask-1","path2":"/","day":"2","jx":"0","so_off":"0","bt-style":"","login-login":"/","path":"","mid":"","aid":"1","url":"m.ql82.com ","wapurl":"m.ql82.com ","mob_status":"0"};</script>
    <script src="/template/yuyuyy/asset/js/ecscript.js"></script>
    <link rel="shortcut icon" href="/template/yuyuyy/asset/img/favicon.png" type="image/x-icon" />
</head>
<body class="theme2">
<div class="gen-loading bj load-icon-on">
    <img class="loading1 none" data-ii="on" src="/template/yuyuyy/asset/img/logo-1.png" alt="星海影院">
    <img class="loading2 none" src="/template/yuyuyy/asset/img/logo-2.png" alt="星海影院">
</div>

<!-- 头部导航 -->
<?php include 'components/header.php'; ?>

<!-- 轮播区域 -->
<div class="slid-e swiper3" style="background: rgb(17,19,25)">
    <div class="slid-e-list swiper-wrapper">
        <?php foreach ($bannerVideos ?? [] as $video): ?>
        <?php 
        // 使用banner图片，如果没有则使用poster图片
        $bannerImage = !empty($video['banner']) ? $video['banner'] : $video['poster'];
        ?>
        <div class="slid-e-list-box rel swiper-slide">
            <div class="slid-e-top">
                <div class="iDmKMm"></div>
                <div class="slid-e-bj slide-time-img3 wap-hide" style="background-image: url(<?= $this->escape($bannerImage) ?>);"></div>
                <div class="slid-e-bj slide-time-img3 slide-wap mask-1" style="display:none;background-image: url(<?= $this->escape($bannerImage) ?>);"></div>
            </div>
            <div class="slid-e-bottom w-100">
                <div class="box-width">
                    <?php 
                    // 从视频数据中获取分类名称
                    $categoryName = $video['category_name'] ?? '影片';
                    include_once 'components/category-styles.php';
                    $categoryClass = getCategoryClass($categoryName);
                    ?>
                    <div class="slid-e-type category-badge <?= $categoryClass ?>"><?= $this->escape($categoryName) ?></div>
                    <h3 class="slide-info-title hide"><?= $this->escape($video['title']) ?></h3>
                    <div class="slide-info hide2"><?= $this->escape($video['description']) ?></div>
                    <div class="slid-e-bnt flex between w-100">
                        <div class="left flex">
                            <a href="?page=play&id=<?= $video['id'] ?>" class="bj2 ho ol2"><i class="fa r6 ds-bofang1"></i>立即播放</a>
                            <a href="?page=play&id=<?= $video['id'] ?>" class="tim-bj">影片详情</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if (empty($bannerVideos)): ?>
        <!-- 如果没有推荐的轮播图影片，显示提示信息 -->
        <div class="slid-e-list-box rel swiper-slide">
            <div class="slid-e-top">
                <div class="iDmKMm"></div>
                <div class="slid-e-bj slide-time-img3 wap-hide" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                    <div style="text-align: center; color: white;">
                        <h3>暂无轮播图</h3>
                        <p>请在管理后台设置推荐影片作为轮播图</p>
                    </div>
                </div>
                <div class="slid-e-bj slide-time-img3 slide-wap mask-1" style="display:none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
            </div>
            <div class="slid-e-bottom w-100">
                <div class="box-width">
                    <div class="slid-e-type bj2 ol2">系统提示</div>
                    <h3 class="slide-info-title hide">设置轮播图</h3>
                    <div class="slide-info hide2">在管理后台将影片设置为推荐状态，即可在此处显示轮播图</div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- 分类导航 -->
<!-- <div class="container">
    <div class="home-category-nav">
        <h2 class="section-title">📂 影片分类</h2>
        <div class="category-tags">
            <?php foreach ($categories ?? [] as $category): ?>
            <a href="?page=list&category=<?= $category['id'] ?>" class="category-tag">
                <?= $this->escape($category['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div> -->

<!-- 内容区域 -->
<div class="container">
    <?php foreach ($parentCategoryVideos ?? [] as $parentCategoryId => $parentCategoryData): ?>
        <?php 
        $parentCategory = $parentCategoryData['category'];
        $categoryVideos = $parentCategoryData['videos'];
        ?>
        
        <div class="public-box">
            <div class="public-list-box-x public-con-hdt rel">
                <h2 class="public-list-head padding-b public-padding-l" style="margin-bottom:0;">
                    <i class="fa public-list-icon ds-tuijian"></i><?= $this->escape($parentCategory['name']) ?>
                </h2>
                <div class="more-link">
                    <a href="?page=list&category=<?= $parentCategory['id'] ?>" class="more-btn">更多<i class="fa">&#xe622;</i></a>
                </div>
            </div>
            
            <div class="public-r list-swiper rel overflow">
                <div class="swiper-wrapper">
                    <?php foreach ($categoryVideos as $video): ?>
                    <div class="public-list-box public-pic-b swiper-slide">
                        <div class="public-list-div public-list-bj">
                            <a target="_blank" class="public-list-exp" href="?page=play&id=<?= $video['id'] ?>" title="<?= $this->escape($video['title']) ?>">
                                <img class="lazy lazy1 gen-movie-img mask-1"
                                     referrerpolicy="no-referrer"
                                     src="<?= $this->escape($video['poster']) ?>"
                                     alt="<?= $this->escape($video['title']) ?>封面图" />
                                <span class="public-bg"></span>
                                
                                <!-- 精致的分类标签 -->
                                <div class="category-badge-top">
                                    <?php 
                                    if (isset($video['category_names']) && count($video['category_names']) > 1) {
                                        // 多个分类用/分隔
                                        echo implode(' / ', array_slice($video['category_names'], 0, 3)); // 最多显示3个分类
                                    } else {
                                        // 单个分类
                                        echo $video['category_name'] ?? '影片';
                                    }
                                    ?>
                                </div>
                                
                                <span class="public-prt hide cr6" style="">热映推荐</span>
                                <span class="public-list-prb hide ft2">
                                    评分: <?= $this->escape($video['rating']) ?>
                                </span>
                                <span class="public-play"><i class="fa">&#xe593;</i></span>
                            </a>
                        </div>
                        <div class="public-list-button">
                            <a target="_blank" class="time-title hide ft4" href="?page=play&id=<?= $video['id'] ?>" title="<?= $this->escape($video['title']) ?>"><?= $this->escape($video['title']) ?></a>
                            <div class="public-list-subtitle cor5 hide ft2"><?= $this->escape($video['description']) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
.public-con-hdt {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: none; /* 不要固定背景色 */
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
    background-image: linear-gradient(90deg, #a18cd1, #fbc2eb);
    font-weight: bold;
}


.more-btn {
    color: #ccc;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s;
}

.more-btn:hover {
    color: #667eea;
}

.more-btn i {
    margin-left: 5px;
}

/* 分类导航样式 */
.home-category-nav {
    margin: 2rem 0 3rem;
    text-align: center;
}

/* 精致的分类标签样式 */
.category-badge-top {
    position: absolute;
    top: 8px;
    right: 8px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    max-width: 80px;
    text-align: center;
    line-height: 1.2;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    backdrop-filter: blur(5px);
    z-index: 10;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.category-badge-top:hover {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}

/* 确保图片容器有相对定位 */
.public-list-div {
    position: relative;
}

/* 优化分类标签在小屏幕上的显示 */
@media (max-width: 768px) {
    .category-badge-top {
        font-size: 10px;
        padding: 3px 6px;
        max-width: 70px;
        top: 6px;
        right: 6px;
    }
}

.section-title {
    color: #fff;
    font-size: 1.5rem;
    margin: 0 0 1.5rem;
    font-weight: bold;
}

.category-tags {
    display: flex;
    justify-content: center;
    gap: 0.8rem;
    flex-wrap: wrap;
    max-width: 700px;
    margin: 0 auto;
}

.category-tag {
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    text-decoration: none;
    border-radius: 18px;
    transition: all 0.3s;
    border: 1px solid rgba(255, 255, 255, 0.2);
    font-size: 0.9rem;
    font-weight: 500;
    white-space: nowrap;
}

.category-tag:hover {
    background: rgba(102, 126, 234, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    color: #fff;
}

@media (max-width: 768px) {
    .category-tags {
        gap: 0.6rem;
        max-width: 100%;
        padding: 0 1rem;
    }
    
    .category-tag {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    
    .section-title {
        font-size: 1.3rem;
    }
}

@media (max-width: 480px) {
    .category-tags {
        gap: 0.5rem;
        padding: 0 0.5rem;
    }
    
    .category-tag {
        padding: 0.35rem 0.7rem;
        font-size: 0.8rem;
        border-radius: 16px;
    }
    
    .home-category-nav {
        margin: 1.5rem 0 2.5rem;
    }
    
    .section-title {
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }
}


</style>

<script>
// 初始化轮播
var mySwiper = new Swiper('.swiper3', {
    autoplay: {
        delay: 4000,
        disableOnInteraction: false,
    },
    loop: true,
    observer: true,
    observeParents: true
});

// 初始化影片列表轮播
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

<?php include 'components/footer.php'; ?>
</body>
</html>