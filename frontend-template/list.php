<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no,viewport-fit=cover">
    <meta name="theme-color" content="#1a1a1a" />
    <title><?= htmlspecialchars($category['name']) ?> - 星海影院</title>
    <!-- <meta name="keywords" content="<?= htmlspecialchars($category['name']) ?>,免费观看,高清在线" /> -->
    <!-- <meta name="description" content="星海影院<?= htmlspecialchars($category['name']) ?>频道，提供最新最全的<?= htmlspecialchars($category['name']) ?>资源" /> -->
    
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
<?php include 'components/header.php'; ?>

<!-- 面包屑导航 -->
<div class="breadcrumb-container">
    <div class="container">
        <div class="breadcrumb">
            <a href="/" class="breadcrumb-item home-item">
                <div class="breadcrumb-icon">
                    <i class="fa ds-zhuye"></i>
                </div>
                <span class="breadcrumb-text">首页</span>
                <div class="breadcrumb-ripple"></div>
            </a>
            <div class="breadcrumb-separator">
                <div class="separator-line"></div>
                <div class="separator-arrow"></div>
            </div>
            <span class="breadcrumb-item current-item">
                <div class="breadcrumb-icon">
                    <i class="fa ds-dianying"></i>
                </div>
                <span class="breadcrumb-text"><?= htmlspecialchars($category['name']) ?></span>
                <div class="breadcrumb-glow"></div>
            </span>
        </div>
    </div>
</div>

<!-- 列表容器 -->
<div class="container">
    <div class="list-container">
        <div class="list-header">
            <h1 class="list-title"><?= htmlspecialchars($category['name']) ?></h1>
            <div class="list-info">共<?= $total ?>部影片 第<?= $currentPage ?>/<?= $totalPages ?>页</div>
        </div>
        
        <div class="list-filter">
            <!-- 筛选选择器 -->
            <div class="filter-selectors">
                <!-- 分类筛选 -->
                <?php if (!empty($availableFilterCategories)): ?>
                <div class="filter-selector">
                    <span class="filter-label">类型</span>
                    <div class="filter-options">
                        <a href="?page=list&category=<?= $categoryId ?>&p=1&filter_category=<?= $filterCategory ?>&year=<?= $filterYear ?>&region=<?= $filterRegion ?>&sort=<?= $sort ?>" class="filter-option <?= empty($filterCategory) ? 'active' : '' ?>">全部</a>
                        <?php foreach ($availableFilterCategories as $catId): ?>
                            <?php $cat = $categoryMap[$catId] ?? null; ?>
                            <?php if ($cat): ?>
                            <a href="?page=list&category=<?= $categoryId ?>&p=1&filter_category=<?= $catId ?>&year=<?= $filterYear ?>&region=<?= $filterRegion ?>&sort=<?= $sort ?>" class="filter-option <?= $filterCategory == $catId ? 'active' : '' ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- 年份筛选 -->
                <?php if (!empty($availableYears)): ?>
                <div class="filter-selector">
                    <span class="filter-label">年份</span>
                    <div class="filter-options">
                        <a href="?page=list&category=<?= $categoryId ?>&p=1&filter_category=<?= $filterCategory ?>&year=&region=<?= $filterRegion ?>&sort=<?= $sort ?>" class="filter-option <?= empty($filterYear) ? 'active' : '' ?>">全部</a>
                        <?php foreach ($availableYears as $year): ?>
                        <a href="?page=list&category=<?= $categoryId ?>&p=1&filter_category=<?= $filterCategory ?>&year=<?= $year ?>&region=<?= $filterRegion ?>&sort=<?= $sort ?>" class="filter-option <?= $filterYear == $year ? 'active' : '' ?>">
                            <?= $year ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- 地区筛选 -->
                <?php if (!empty($availableRegions)): ?>
                <div class="filter-selector">
                    <span class="filter-label">地区</span>
                    <div class="filter-options">
                        <a href="?page=list&category=<?= $categoryId ?>&p=1&filter_category=<?= $filterCategory ?>&year=<?= $filterYear ?>&region=&sort=<?= $sort ?>" class="filter-option <?= empty($filterRegion) ? 'active' : '' ?>">全部</a>
                        <?php foreach ($availableRegions as $region): ?>
                        <a href="?page=list&category=<?= $categoryId ?>&p=1&filter_category=<?= $filterCategory ?>&year=<?= $filterYear ?>&region=<?= $region ?>&sort=<?= $sort ?>" class="filter-option <?= $filterRegion == $region ? 'active' : '' ?>">
                            <?= htmlspecialchars($region) ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- 排序选项 -->
                <div class="filter-selector sort-selector">
                    <span class="filter-label">排序</span>
                    <div class="sort-options">
                        <a href="?page=list&category=<?= $categoryId ?>&p=<?= $currentPage ?>&sort=latest<?= !empty($filterYear) ? '&year=' . $filterYear : '' ?><?= !empty($filterRegion) ? '&region=' . $filterRegion : '' ?><?= !empty($filterCategory) ? '&filter_category=' . $filterCategory : '' ?>" class="sort-option <?= ($sort ?? '') === 'latest' ? 'active' : '' ?>">最新</a>
                        <a href="?page=list&category=<?= $categoryId ?>&p=<?= $currentPage ?>&sort=rating<?= !empty($filterYear) ? '&year=' . $filterYear : '' ?><?= !empty($filterRegion) ? '&region=' . $filterRegion : '' ?><?= !empty($filterCategory) ? '&filter_category=' . $filterCategory : '' ?>" class="sort-option <?= ($sort ?? '') === 'rating' ? 'active' : '' ?>">评分</a>
                        <a href="?page=list&category=<?= $categoryId ?>&p=<?= $currentPage ?>&sort=year<?= !empty($filterYear) ? '&year=' . $filterYear : '' ?><?= !empty($filterRegion) ? '&region=' . $filterRegion : '' ?><?= !empty($filterCategory) ? '&filter_category=' . $filterCategory : '' ?>" class="sort-option <?= ($sort ?? '') === 'year' ? 'active' : '' ?>">年份</a>
                    </div>
                </div>
            </div>
            
            <!-- 清除筛选按钮 -->
            <?php if (!empty($filterYear) || !empty($filterRegion) || !empty($filterCategory)): ?>
            <div class="filter-actions">
                <a href="?page=list&category=<?= $categoryId ?>&p=1&sort=<?= $sort ?>" class="clear-btn">
                    <svg class="clear-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z" fill="currentColor"/>
                    </svg>
                    <span>清除筛选</span>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.list-header {
    text-align: center;
    margin-bottom: 1rem;
}

.list-title {
    font-size: 2rem;
    font-weight: bold;
    color: #fff;
    margin: 0 0 0.3rem 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.list-info {
    color: #bdc3c7;
    font-size: 0.85rem;
    margin: 0;
    opacity: 0.8;
}

.list-filter {
    backdrop-filter: blur(20px);
    border: none;
    border-radius: 16px;
    padding: 0;
    margin-bottom: 1.2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    position: relative;
    overflow: hidden;
}

.list-filter::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.2), transparent);
    animation: shimmer 3s ease-in-out infinite;
}

.filter-selectors {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    align-items: center;
    background: none;
    border-radius: 0;
    padding: 1rem 1.5rem 1.2rem 1.5rem;
}

.filter-selector {
    display: flex;
    align-items: flex-start;
    gap: 0.4rem;
    background: none;
    border: none;
    border-radius: 0;
    padding: 0;
    transition: all 0.3s ease;
}

.filter-selector:hover {
    background: none;
    transform: none;
}

.filter-label {
    color: #ecf0f1;
    font-weight: 500;
    font-size: 0.8rem;
    white-space: nowrap;
    margin-top: 0.2rem;
}

.filter-select {
    background: transparent;
    border: none;
    color: #ecf0f1;
    padding: 0.15rem 0.4rem;
    font-size: 0.8rem;
    min-width: 70px;
    cursor: pointer;
    transition: all 0.3s ease;
    outline: none;
    border-radius: 0;
}

.filter-select:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.05);
}

.filter-select:focus {
    background: rgba(255, 255, 255, 0.08);
}

.filter-select option {
    background: #2a2a2a;
    color: #fff;
}

/* 排序选项样式 */
.sort-selector {
    background: none;
}

        .sort-options {
            display: flex;
            gap: 0.3rem;
            flex-shrink: 0;
        }
        
        .sort-option {
            color: #bdc3c7;
            text-decoration: none;
            padding: 0.3rem 0.6rem;
            border-radius: 0;
            transition: all 0.3s ease;
            font-size: 0.75rem;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            min-width: 50px;
            text-align: center;
            flex-shrink: 0;
        }

.sort-option:hover {
    color: #ecf0f1;
    background: rgba(102, 126, 234, 0.15);
    transform: translateY(-1px);
}

.sort-option.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
}

.filter-clear {
    margin-left: auto;
}

        .filter-actions {
            display: flex;
            justify-content: center;
            padding: 1rem 1.5rem 0.5rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .clear-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.15) 0%, rgba(220, 53, 69, 0.1) 100%);
            color: #e74c3c;
            text-decoration: none;
            border-radius: 0;
            border: 1px solid rgba(220, 53, 69, 0.2);
            transition: all 0.3s ease;
            font-size: 0.8rem;
            font-weight: 500;
            min-width: auto;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        
        .clear-btn:hover {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.25) 0%, rgba(220, 53, 69, 0.2) 100%);
            border-color: rgba(220, 53, 69, 0.4);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        }
        
        .clear-icon {
            width: 16px;
            height: 16px;
            transition: all 0.3s ease;
        }
        
        .clear-btn:hover .clear-icon {
            transform: rotate(90deg);
        }

/* 炫酷面包屑导航样式 */
.breadcrumb-container {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    margin: 60px 0 20px 0;
    position: relative;
    overflow: hidden;
}

.breadcrumb-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.5), transparent);
    animation: shimmer 3s ease-in-out infinite;
}

.breadcrumb {
    padding: 1rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    position: relative;
    margin-left: 1rem;
}

.breadcrumb-item {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.9rem;
    border-radius: 16px;
    text-decoration: none;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    cursor: pointer;
    margin: 0.2rem 0;
}

.breadcrumb-item.home-item {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
}

.breadcrumb-item.home-item:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%);
    border-color: rgba(102, 126, 234, 0.5);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
}

.breadcrumb-item.current-item {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 3px 12px rgba(102, 126, 234, 0.4);
    animation: pulse-glow 2s ease-in-out infinite alternate;
}

.breadcrumb-icon {
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.breadcrumb-item:hover .breadcrumb-icon {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

.breadcrumb-text {
    font-weight: 500;
    font-size: 0.85rem;
    letter-spacing: 0.3px;
    position: relative;
    z-index: 2;
}

.breadcrumb-separator {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 32px;
    margin: 0 0.2rem;
}

.separator-line {
    width: 1.5px;
    height: 16px;
    background: linear-gradient(180deg, transparent, rgba(102, 126, 234, 0.6), transparent);
    animation: separator-pulse 2s ease-in-out infinite;
}

.separator-arrow {
    position: absolute;
    width: 0;
    height: 0;
    border-left: 5px solid rgba(102, 126, 234, 0.6);
    border-top: 3px solid transparent;
    border-bottom: 3px solid transparent;
    animation: arrow-bounce 2s ease-in-out infinite;
}

.breadcrumb-ripple {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: all 0.6s ease;
}

.breadcrumb-item:hover .breadcrumb-ripple {
    width: 80px;
    height: 80px;
    opacity: 0;
}

.breadcrumb-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at center, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
    border-radius: 16px;
    opacity: 0;
    animation: glow-pulse 2s ease-in-out infinite;
}

/* 动画效果 */
@keyframes shimmer {
    0%, 100% { transform: translateX(-100%); opacity: 0; }
    50% { transform: translateX(100%); opacity: 1; }
}

@keyframes pulse-glow {
    0% { box-shadow: 0 3px 12px rgba(102, 126, 234, 0.4); }
    100% { box-shadow: 0 3px 20px rgba(102, 126, 234, 0.6), 0 0 25px rgba(102, 126, 234, 0.3); }
}

@keyframes separator-pulse {
    0%, 100% { opacity: 0.3; transform: scaleY(0.8); }
    50% { opacity: 1; transform: scaleY(1.2); }
}

@keyframes arrow-bounce {
    0%, 100% { transform: translateX(0); }
    50% { transform: translateX(2px); }
}

@keyframes glow-pulse {
    0%, 100% { opacity: 0; }
    50% { opacity: 0.5; }
}

/* 响应式设计 */
@media (max-width: 768px) {
    .breadcrumb-container {
        margin: 50px 0 15px 0;
    }
    
    .breadcrumb {
        padding: 0.8rem 0;
        gap: 0.4rem;
        margin-left: 0.8rem;
    }
    
    .breadcrumb-item {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
        margin: 0.15rem 0;
    }
    
    .breadcrumb-separator {
        width: 20px;
        height: 24px;
        margin: 0 0.15rem;
    }
    
    .separator-line {
        height: 12px;
    }
    
    .separator-arrow {
        border-left-width: 3px;
        border-top-width: 2px;
        border-bottom-width: 2px;
    }
    
    .breadcrumb-icon {
        width: 16px;
        height: 16px;
    }

    .list-container {
        margin-bottom: 1.2rem;
    }
    
    .list-header {
        padding: 1rem 1.2rem 0.6rem 1.2rem;
    }
    
    .list-title {
        font-size: 1.8rem;
        margin-bottom: 0.2rem;
    }
    
    .list-info {
        font-size: 0.8rem;
    }
    
    .filter-selectors {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
        padding: 0.8rem 1.2rem 1rem 1.2rem;
    }
    
    .filter-selector {
        justify-content: flex-start;
        padding: 0.4rem 0;
    }
    
    .filter-label {
        min-width: 50px;
        margin-top: 0.1rem;
    }
    
    .filter-options {
        gap: 0.25rem;
    }
    
    .filter-option {
        padding: 0.2rem 0.5rem;
        font-size: 0.7rem;
    }
    
    .filter-actions {
        padding: 0.8rem 1.2rem 0.4rem 1.2rem;
    }
    
    .clear-btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
    }
    
    .clear-icon {
        width: 14px;
        height: 14px;
    }
    
    .filter-select {
        min-width: auto;
        flex: 1;
        text-align: right;
    }
    
    .filter-clear {
        margin-left: 0;
        margin-top: 0.4rem;
    }
    
    .sort-options {
        gap: 0.2rem;
    }
    
    .sort-option {
        padding: 0.2rem 0.5rem;
        font-size: 0.7rem;
    }
}
</style>

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
    <div class="video-list-grid">
        <?php foreach ($videos as $video): ?>
        <div class="video-list-item">
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

    <!-- 分页 -->
    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <div class="pagination-wrap">
            <?php if ($currentPage > 1): ?>
            <a href="?page=list&category=<?= $categoryId ?>&p=<?= $currentPage - 1 ?>&sort=<?= $sort ?><?= !empty($filterYear) ? '&year=' . $filterYear : '' ?><?= !empty($filterRegion) ? '&region=' . $filterRegion : '' ?><?= !empty($filterCategory) ? '&filter_category=' . $filterCategory : '' ?>" class="page-btn page-prev">
                <i class="fa">&#xe621;</i> 上一页
            </a>
            <?php endif; ?>

            <?php
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages, $currentPage + 2);
            ?>

            <?php if ($startPage > 1): ?>
            <a href="?page=list&category=<?= $categoryId ?>&p=1&sort=<?= $sort ?><?= !empty($filterYear) ? '&year=' . $filterYear : '' ?><?= !empty($filterRegion) ? '&region=' . $filterRegion : '' ?><?= !empty($filterCategory) ? '&filter_category=' . $filterCategory : '' ?>" class="page-num">1</a>
            <?php if ($startPage > 2): ?>
            <span class="page-dots">...</span>
            <?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
            <a href="?page=list&category=<?= $categoryId ?>&p=<?= $i ?>&sort=<?= $sort ?><?= !empty($filterYear) ? '&year=' . $filterYear : '' ?><?= !empty($filterRegion) ? '&region=' . $filterRegion : '' ?><?= !empty($filterCategory) ? '&filter_category=' . $filterCategory : '' ?>" 
               class="page-num <?= $i == $currentPage ? 'active' : '' ?>">
                <?= $i ?>
            </a>
            <?php endfor; ?>

            <?php if ($endPage < $totalPages): ?>
            <?php if ($endPage < $totalPages - 1): ?>
            <span class="page-dots">...</span>
            <?php endif; ?>
            <a href="?page=list&category=<?= $categoryId ?>&p=<?= $totalPages ?>&sort=<?= $sort ?><?= !empty($filterYear) ? '&year=' . $filterYear : '' ?><?= !empty($filterRegion) ? '&region=' . $filterRegion : '' ?><?= !empty($filterCategory) ? '&filter_category=' . $filterCategory : '' ?>" class="page-num"><?= $totalPages ?></a>
            <?php endif; ?>

            <?php if ($currentPage < $totalPages): ?>
            <a href="?page=list&category=<?= $categoryId ?>&p=<?= $currentPage + 1 ?>&sort=<?= $sort ?><?= !empty($filterYear) ? '&year=' . $filterYear : '' ?><?= !empty($filterRegion) ? '&region=' . $filterRegion : '' ?><?= !empty($filterCategory) ? '&filter_category=' . $filterCategory : '' ?>" class="page-btn page-next">
                下一页 <i class="fa">&#xe622;</i>
            </a>
            <?php endif; ?>
        </div>
        
        <div class="page-jump">
            <form method="GET" action="/" class="jump-form">
                <input type="hidden" name="page" value="list">
                <input type="hidden" name="category" value="<?= $categoryId ?>">
                <input type="hidden" name="sort" value="<?= $sort ?>">
                <?php if (!empty($filterYear)): ?><input type="hidden" name="year" value="<?= $filterYear ?>"><?php endif; ?>
                <?php if (!empty($filterRegion)): ?><input type="hidden" name="region" value="<?= $filterRegion ?>"><?php endif; ?>
                <?php if (!empty($filterCategory)): ?><input type="hidden" name="filter_category" value="<?= $filterCategory ?>"><?php endif; ?>
                <span>跳转到</span>
                <input type="number" name="p" min="1" max="<?= $totalPages ?>" value="<?= $currentPage ?>" class="jump-input">
                <span>页</span>
                <button type="submit" class="jump-btn">GO</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<!-- 筛选功能的JavaScript -->
<script>
function updateFilter(type, value) {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (value) {
        urlParams.set(type, value);
    } else {
        urlParams.delete(type);
    }
    
    // 重置页码到第一页
    urlParams.set('p', '1');
    
    // 跳转到新的URL
    window.location.href = '?' + urlParams.toString();
}
</script>


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

.list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 2rem 0;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.05);
}

.list-title {
    font-size: 2rem;
    color: #fff;
    margin: 0;
}

.list-info {
    color: #ccc;
    font-size: 0.9rem;
}

.list-info span {
    margin-left: 1rem;
}

.video-list-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.video-list-item {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s;
}

.video-list-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
}

.video-poster {
    position: relative;
    height: 280px;
    overflow: hidden;
}

.video-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.video-list-item:hover .video-poster img {
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

.video-list-item:hover .video-overlay {
    opacity: 1;
}

.play-btn {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #333;
    transition: transform 0.3s;
}

.play-btn:hover {
    transform: scale(1.1);
}

.video-rating {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(243, 156, 18, 0.9);
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: bold;
}

.video-year {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-size: 0.8rem;
}

.video-info {
    padding: 1rem;
}

.video-title {
    margin-bottom: 0.5rem;
}

.video-title a {
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
    font-weight: bold;
    transition: color 0.3s;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.video-title a:hover {
    color: #667eea;
}

.video-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.8rem;
    font-size: 0.8rem;
    color: #ccc;
}

.video-desc {
    color: #ddd;
    line-height: 1.4;
    font-size: 0.8rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* 分页样式 */
.pagination {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    padding: 2rem;
    margin: 2rem 0;
}

.pagination-wrap {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.page-btn, .page-num {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.6rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s;
    font-size: 0.9rem;
    min-width: 40px;
    justify-content: center;
}

.page-num.active {
    background: #667eea;
    color: white;
}

.page-btn:hover, .page-num:hover {
    background: rgba(102, 126, 234, 0.8);
    transform: translateY(-2px);
}

.page-dots {
    color: #ccc;
    padding: 0 0.5rem;
}

.page-jump {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 1rem;
}

.jump-form {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #ccc;
    font-size: 0.9rem;
}

.jump-input {
    width: 60px;
    padding: 0.4rem 0.6rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 5px;
    color: #fff;
    text-align: center;
}

.jump-btn {
    padding: 0.4rem 0.8rem;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s;
}

.jump-btn:hover {
    background: #5a6fd8;
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
    .list-header {
        margin-bottom: 0.8rem;
    }
    
    .list-title {
        font-size: 1.8rem;
        margin-bottom: 0.2rem;
    }
    
    .list-info {
        font-size: 0.8rem;
    }
    
    .list-filter {
        padding: 0.6rem;
        margin-bottom: 1rem;
    }
    
    .filter-selectors {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }
    
    .filter-selector {
        justify-content: flex-start;
        padding: 0.4rem 0;
    }
    
    .filter-select {
        min-width: auto;
        flex: 1;
        text-align: right;
    }
    
    .filter-clear {
        margin-left: 0;
        margin-top: 0.4rem;
    }
    
    .sort-options {
        gap: 0.2rem;
    }
    
    .sort-option {
        padding: 0.2rem 0.5rem;
        font-size: 0.7rem;
    }
    
    .video-list-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1rem;
    }
    
    .pagination-wrap {
        gap: 0.3rem;
    }
    
    .page-btn, .page-num {
        padding: 0.5rem 0.8rem;
        font-size: 0.8rem;
        min-width: 35px;
    }
}

        .list-container {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: 0;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }
        
        .list-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.2), transparent);
            animation: shimmer 3s ease-in-out infinite;
        }
        
        .list-header {
            text-align: center;
            padding: 1.2rem 1.5rem 0.8rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .list-title {
            font-size: 2rem;
            font-weight: bold;
            color: #fff;
            margin: 0 0 0.3rem 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .list-info {
            color: #bdc3c7;
            font-size: 0.85rem;
            margin: 0;
            opacity: 0.8;
        }
        
        .list-filter {
            padding: 0;
            margin: 0;
            background: none;
            backdrop-filter: none;
            border: none;
            border-radius: 0;
            box-shadow: none;
            position: static;
            overflow: visible;
        }
        
        .list-filter::before {
            display: none;
        }

        .filter-selectors {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            align-items: stretch;
        }
        
        .filter-selector {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            background: none;
            border: none;
            border-radius: 0;
            padding: 0.6rem 0;
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .filter-selector:last-child {
            border-bottom: none;
        }
        
        .filter-selector:hover {
            background: none;
            transform: none;
        }
        
        .filter-label {
            color: #ecf0f1;
            font-weight: 500;
            font-size: 0.8rem;
            white-space: nowrap;
            margin-top: 0.1rem;
            min-width: 50px;
            flex-shrink: 0;
        }
        
        .filter-options {
            display: flex;
            flex-wrap: wrap;
            gap: 0.3rem;
            flex: 1;
            align-items: center;
            min-width: 0;
        }
        
        .filter-option {
            color: #bdc3c7;
            text-decoration: none;
            padding: 0.3rem 0.6rem;
            border-radius: 0;
            transition: all 0.3s ease;
            font-size: 0.75rem;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            white-space: nowrap;
            min-width: 50px;
            text-align: center;
            flex-shrink: 0;
        }
        
        .filter-option:hover {
            color: #ecf0f1;
            background: rgba(102, 126, 234, 0.1);
            border-color: rgba(102, 126, 234, 0.2);
            transform: translateY(-1px);
        }
        
        .filter-option.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: rgba(102, 126, 234, 0.3);
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
        }

        /* PC端优化 */
        @media (min-width: 1024px) {
            .filter-selectors {
                gap: 0.8rem;
            }
            
            .filter-selector {
                gap: 1rem;
                padding: 0.8rem 0;
            }
            
            .filter-label {
                min-width: 60px;
                font-size: 0.85rem;
            }
            
            .filter-options {
                gap: 0.4rem;
            }
            
            .filter-option {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
                min-width: 60px;
            }
            
            .sort-options {
                gap: 0.4rem;
            }
            
            .sort-option {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
                min-width: 60px;
            }
        }
        
        /* 超大屏幕优化 */
        @media (min-width: 1440px) {
            .filter-selectors {
                gap: 1rem;
            }
            
            .filter-selector {
                gap: 1.2rem;
                padding: 1rem 0;
            }
            
            .filter-label {
                min-width: 70px;
                font-size: 0.9rem;
            }
            
            .filter-options {
                gap: 0.5rem;
            }
            
            .filter-option {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
                min-width: 70px;
            }
            
            .sort-options {
                gap: 0.5rem;
            }
            
            .sort-option {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
                min-width: 70px;
            }
        }

</style>

<?php include 'components/footer.php'; ?>
</body>
</html>