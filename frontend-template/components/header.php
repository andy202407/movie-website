<?php
// 获取父分类数据
$videoModel = new VideoModel();
$parentCategories = $videoModel->getParentCategories();
?>
<style>
/* 隐藏PC端搜索按钮 */
@media (min-width: 768px) {
    /* 隐藏主搜索表单的提交按钮 */
    .this-search button[type="submit"] {
        display: none !important;
    }
    
    /* 隐藏独立的搜索按钮 */
    .gen-search {
        display: none !important;
    }
    
    /* 隐藏任何其他可能的搜索按钮 */
    .this-search .fa.ds-sousuo {
        display: none !important;
    }
}

/* Logo样式 */
.site-logo {
    font-size: 18px;
    font-weight: bold;
    color: #667eea;
    text-decoration: none;
    padding: 10px 0;
    display: block;
    text-align: center;
    margin-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.site-logo:hover {
    color: #fff;
    text-decoration: none;
}

/* 播放记录弹窗样式 */
.gen-history-list {
    max-height: 80vh;
    overflow-y: auto;
}

.play-catalog {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 20px;
}

.play-catalog span {
    padding: 10px 20px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.play-catalog span.on {
    color: #667eea;
    border-bottom: 2px solid #667eea;
}

.play-catalog span:hover {
    color: #667eea;
}

.history-item {
    margin-bottom: 15px;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.history-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.history-link {
    display: flex;
    padding: 15px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.history-link:hover {
    background: rgba(255, 255, 255, 0.1);
    text-decoration: none;
    color: inherit;
}

.history-poster {
    width: 60px;
    height: 80px;
    margin-right: 15px;
    flex-shrink: 0;
}

.history-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
}

.history-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.history-title {
    font-weight: bold;
    color: #fff;
    margin-bottom: 5px;
    font-size: 14px;
}

.history-time {
    color: #999;
    font-size: 12px;
    margin-bottom: 5px;
}

.history-progress {
    color: #667eea;
    font-size: 12px;
}

.no-history {
    text-align: center;
    color: #999;
    padding: 40px 20px;
    font-size: 14px;
}

/* 云端登录提示样式 */
.cloud-login-prompt {
    text-align: center;
    padding: 40px 20px;
}

.cloud-icon {
    margin-bottom: 20px;
}

.cloud-text {
    color: #999;
    font-size: 16px;
    margin-bottom: 25px;
}

.cloud-login-btn {
    display: inline-block;
    background: #667eea;
    color: #fff;
    padding: 12px 30px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s ease;
}

.cloud-login-btn:hover {
    background: #5a6fd8;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    text-decoration: none;
    color: #fff;
}

/* 响应式设计 */
@media (max-width: 768px) {
    .history-poster {
        width: 50px;
        height: 70px;
        margin-right: 12px;
    }
    
    .history-title {
        font-size: 13px;
    }
    
    .history-time,
    .history-progress {
        font-size: 11px;
    }
    
    .cloud-login-prompt {
        padding: 30px 15px;
    }
    
    .cloud-text {
        font-size: 14px;
    }
    
    .cloud-login-btn {
        padding: 10px 25px;
        font-size: 14px;
    }
}
</style>

<!-- Logo显示 -->
<!-- <div class="site-logo">
    <a href="/" style="color: inherit; text-decoration: none;">
        星海影院
    </a>
</div> -->

<div class="head flex between head-c header_nav0">
    <div class="left flex">
        <div class="logo">
            <a class="logo-brand" href="/">
                <img class="logo1 none" src="/template/yuyuyy/asset/img/logo-1.png" alt="星海影院">
                <img class="logo2 none" src="/template/yuyuyy/asset/img/logo-2.png" alt="星海影院">
            </a>
        </div>
        <div class="head-nav ft4 roll bold0 pc-show1 wap-show1">
            <ul class="swiper-wrapper">
                <li class="swiper-slide"><a target="_self" href="/" class="<?= ($_GET['page'] ?? 'home') == 'home' ? 'current cor6' : '' ?>"><em class="fa ds-zhuye"></em><em class="fa none ds-zhuye2"></em>首页</a></li>
                <?php foreach ($parentCategories as $cat): ?>
                <li class="swiper-slide"><a target="_self" href="/?page=list&category=<?= $cat['id'] ?>" class="<?= (($_GET['page'] ?? '') == 'list' && ($_GET['category'] ?? '') == $cat['id']) ? 'current cor6' : '' ?>">
                    <?php if ($cat['name'] == '电影'): ?>
                        <em class="fa ds-dianying"></em><em class="fa none ds-dianying2"></em>
                    <?php elseif ($cat['name'] == '电视剧'): ?>
                        <em class="fa ds-dianshi"></em><em class="fa none ds-dianshi2"></em>
                    <?php elseif ($cat['name'] == '综艺'): ?>
                        <em class="fa ds-zongyi"></em><em class="fa none ds-zongyi2"></em>
                    <?php elseif ($cat['name'] == '动漫'): ?>
                        <em class="fa ds-dongman"></em><em class="fa none ds-dongman2"></em>
                    <?php elseif ($cat['name'] == '短剧'): ?>
                        <em class="fa ds-dianshi"></em><em class="fa none ds-dianshi2"></em>
                    <?php elseif ($cat['name'] == '纪录片'): ?>
                        <em class="fa ds-ziyuan"></em><em class="fa none ds-ziyuan2"></em>
                    <?php elseif ($cat['name'] == '有声电子书'): ?>
                        <em class="fa ds-ziyuan"></em><em class="fa none ds-ziyuan2"></em>
                    <?php else: ?>
                        <em class="fa ds-dianying"></em><em class="fa none ds-dianying2"></em>
                    <?php endif; ?>
                    <?= $this->escape($cat['name']) ?>
                </a></li>
                <?php endforeach; ?>
                <li class="rel head-more-a">
                    <a class="this-get" href="javascript:">更多<em class="fa nav-more" style="font-size:18px">&#xe563;</em></a>
                    <div class="head-more none box size">
                        <a target="_self" href="/" class="nav-link none2 <?= ($_GET['page'] ?? 'home') == 'home' ? 'cor6' : '' ?>"><em class="fa ds-zhuye"></em><em class="fa none ds-zhuye2"></em>首页</a>
                        <?php foreach ($parentCategories as $cat): ?>
                        <a target="_self" href="/?page=list&category=<?= $cat['id'] ?>" class="nav-link none2 <?= (($_GET['page'] ?? '') == 'list' && ($_GET['category'] ?? '') == $cat['id']) ? 'cor6' : '' ?>">
                            <?php if ($cat['name'] == '电影'): ?>
                                <em class="fa ds-dianying"></em><em class="fa none ds-dianying2"></em>
                            <?php elseif ($cat['name'] == '电视剧'): ?>
                                <em class="fa ds-dianshi"></em><em class="fa none ds-dianshi2"></em>
                            <?php elseif ($cat['name'] == '综艺'): ?>
                                <em class="fa ds-zongyi"></em><em class="fa none ds-zongyi2"></em>
                            <?php elseif ($cat['name'] == '动漫'): ?>
                                <em class="fa ds-dongman"></em><em class="fa none ds-dongman2"></em>
                            <?php elseif ($cat['name'] == '短剧'): ?>
                                <em class="fa ds-dianshi"></em><em class="fa none ds-dianshi2"></em>
                            <?php elseif ($cat['name'] == '纪录片'): ?>
                                <em class="fa ds-ziyuan"></em><em class="fa none ds-ziyuan2"></em>
                            <?php elseif ($cat['name'] == '有声电子书'): ?>
                                <em class="fa ds-ziyuan"></em><em class="fa none ds-ziyuan2"></em>
                            <?php else: ?>
                                <em class="fa ds-dianying"></em><em class="fa none ds-dianshi2"></em>
                            <?php endif; ?>
                            <?= $this->escape($cat['name']) ?>
                        </a>
                        <?php endforeach; ?>
                        <a href="/" class="nav-link"><em class="fa ds-zhou"></em><em class="fa none ds-zhou2"></em>每日更新</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="right flex">
        <div class="this-search">
            <form id="search2" name="search" class="flex-public" method="get" action="/search">
                <a href="javascript:" data-id="1" class="this-select">视频<i class="fa">&#xe563;</i></a>
                <input type="text" name="wd" class="this-input flex-auto cor4" value="<?= $this->escape($_GET['wd'] ?? '') ?>" placeholder="搜索影片..." autocomplete="off">
                <div class="flex">
                    <a class="this-hot" href="/"><em class="fa ds-pahang2 r3"></em>排行榜</a>
                    <button type="submit" class="fa ds-sousuo ol2"></button>
                </div>
            </form>
            
            <!-- 搜索弹窗内容 -->
            <div class="public-list-new top30" style="display:none;">
                <div class="title-m cor4 flex between">
                    <h5>搜索历史</h5>
                    <a id="re_del" class="cor6 ho" style="font-size:14px"><i class="fa r3 ds-shanchu"></i>删除</a>
                </div>
                <div class="records-list">
                    <!-- 搜索历史将通过JavaScript动态添加 -->
                </div>
            </div>
            
            <div class="wap-diy-vod-e search-hot top30" style="display:none;">
                <div class="title-m cor4 flex between">
                    <h5>热门搜索</h5>
                </div>
                <div>
                    <ul>
                        <li><a href="/search?wd=复仇者联盟" class="vod-link br b-b"><span class="vod-on-e-styles key1 cor5">1</span><div class="vod-center">复仇者联盟</div></a></li>
                        <li><a href="/search?wd=泰坦尼克号" class="vod-link br b-b"><span class="vod-on-e-styles key2 cor5">2</span><div class="vod-center">泰坦尼克号</div></a></li>
                        <li><a href="/search?wd=星际穿越" class="vod-link br b-b"><span class="vod-on-e-styles key3 cor5">3</span><div class="vod-center">星际穿越</div></a></li>
                        <li><a href="/search?wd=寄生虫" class="vod-link br b-b"><span class="vod-on-e-styles key4 cor5">4</span><div class="vod-center">寄生虫</div></a></li>
                        <li><a href="/search?wd=闪灵" class="vod-link br b-b"><span class="vod-on-e-styles key5 cor5">5</span><div class="vod-center">闪灵</div></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- 移动端按钮 -->
        <div class="gen-search margin wap-show pc-hide">
            <a class="content-center" href="javascript:">
                <i class="fa ds-sousuo"></i>
            </a>
        </div>
        <div class="gen-history margin wap-show pc-hide">
            <a class="content-center" href="javascript:">
                <i class="fa ds-lishijilu"></i>
            </a>
        </div>
        <div class="gen-left-list margin wap-show pc-hide">
            <a class="content-center" href="javascript:">
                <i class="fa ds-menu"></i>
            </a>
        </div>
        
        <!-- <div class="margin"><a class="content-center" href="/"><i class="fa ds-xiazai"></i><em>客户端</em></a></div> -->
        <div class="margin user-center" id="userCenter">
            <a class="content-center" href="/user/login" id="loginBtn">
                <i class="fa ds-yonghu"></i><em>登录</em>
            </a>
        </div>
    </div>
</div>

<!-- 移动端搜索弹窗 -->
<div class="box-bg2 pop-bj" style="display:none"></div>
<div class="pop-list-body bj pop-1" style="display:none;">
    <div class="wap-head" style="display:none">
        <div class="l">
            <a class="fa pop-bj cor4" href="javascript:">&#xe566;</a>
        </div>
        <h2 class="hidden">
            <a class="cor4 b" href="javascript:">搜索</a>
        </h2>
    </div>
    <div class="wap-head-z"></div>
    <div class="head-search input bj br">
        <form id="search" name="search" method="get" action="/search">
            <input type="text" name="wd" class="search-input mac_wd cor5" value="" placeholder="输入影片名称或关键词..." autocomplete="off" />
            <button type="submit" class="search-input-sub button fa ds-sousuo"></button>
            <button type="button" class="select-name cor5" data-id="1">视频<i class="fa cor5">&#xe563;</i></button>
        </form>
    </div>
    <div class="select-list bj radius br none">
        <div class="flex between ease cor4">
            <span data-id="1" data-name="视频" data-url="/search">视频</span>
        </div>
    </div>
    <div class="completion cor5 ft3"></div>
    <div class="public-list-new top30">
        <div class="title-m cor4 flex between">
            <h5>搜索历史</h5>
            <a id="re_del" class="cor6 ho" style="font-size:14px"><i class="fa r3 ds-shanchu"></i>删除</a>
        </div>
        <div class="records-list">
            <!-- 搜索历史将通过JavaScript动态添加 -->
        </div>
    </div>
    <div class="wap-diy-vod-e search-hot top30">
        <div class="title-m cor4 flex between">
            <h5>热门搜索</h5>
        </div>
        <div>
            <ul>
                <li><a href="/search?wd=复仇者联盟" class="vod-link br b-b"><span class="vod-on-e-styles key1 cor5">1</span><div class="vod-center">复仇者联盟</div></a></li>
                <li><a href="/search?wd=泰坦尼克号" class="vod-link br b-b"><span class="vod-on-e-styles key2 cor5">2</span><div class="vod-center">泰坦尼克号</div></a></li>
                <li><a href="/search?wd=星际穿越" class="vod-link br b-b"><span class="vod-on-e-styles key3 cor5">3</span><div class="vod-center">星际穿越</div></a></li>
                <li><a href="/search?wd=寄生虫" class="vod-link br b-b"><span class="vod-on-e-styles key4 cor5">4</span><div class="vod-center">寄生虫</div></a></li>
                <li><a href="/search?wd=闪灵" class="vod-link br b-b"><span class="vod-on-e-styles key5 cor5">5</span><div class="vod-center">闪灵</div></a></li>
            </ul>
        </div>
    </div>
</div>

<!-- 播放记录弹窗 -->
<div class="pop-list-body gen-history-list bj pop-2" style="display:none;">
    <div class="wap-head" style="display:none">
        <div class="l">
            <a class="fa pop-bj cor4" href="javascript:">&#xe566;</a>
        </div>
        <h2 class="hidden">
            <a class="cor4 b" href="javascript:">播放记录</a>
        </h2>
    </div>
    <div class="wap-head-z"></div>
    <div class="play-catalog flex around ft4 b">
        <span class="rel on" data-tab="local"><a href="javascript:">本地记录</a></span>
        <span class="rel" data-tab="cloud"><a href="javascript:">云端记录</a></span>
    </div>
    <div class="top30 mask-1-box">
        <div class="locality-history history check" id="localTab">
            <ul id="localHistoryList">
                <!-- 本地播放记录将通过JavaScript动态添加 -->
            </ul>
            <a id="l_history" href="javascript:" class="button top30 ol2" style="width:100%">清空记录</a>
        </div>
        <div class="user-history history" id="cloudTab" style="display:none;">
            <div class="cloud-login-prompt">
                <div class="cloud-icon">
                    <i class="fa ds-yonghu" style="font-size: 48px; color: #667eea;"></i>
                </div>
                <div class="cloud-text">请登录后查看</div>
                <a href="javascript:" class="cloud-login-btn">立即登录</a>
            </div>
        </div>
    </div>
</div>

<!-- 搜索功能JavaScript -->
<script>
// 确保搜索表单可以正常提交
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('search2');
    const searchInput = searchForm.querySelector('input[name="wd"]');
    const searchButton = searchForm.querySelector('button[type="submit"]');
    const searchContainer = document.querySelector('.this-search');
    const searchHistory = document.querySelector('.public-list-new');
    const hotSearch = document.querySelector('.wap-diy-vod-e.search-hot');
    
    // 移动端搜索弹窗元素
    const mobileSearchBtn = document.querySelector('.gen-search');
    const mobileSearchPopup = document.querySelector('.pop-1');
    const mobileSearchBg = document.querySelector('.box-bg2.pop-bj');
    const mobileSearchForm = document.getElementById('search');
    const mobileSearchInput = mobileSearchForm.querySelector('input[name="wd"]');
    const mobileSearchSubmit = mobileSearchForm.querySelector('button[type="submit"]');
    
    // 移动端搜索按钮点击事件
    if (mobileSearchBtn) {
        mobileSearchBtn.addEventListener('click', function() {
            mobileSearchPopup.style.display = 'block';
            mobileSearchBg.style.display = 'block';
            document.body.style.cssText = 'height: 100%; width: 100%; overflow: hidden';
            mobileSearchInput.focus();
        });
    }
    
    // 移动端搜索弹窗关闭按钮
    const closeButtons = document.querySelectorAll('.pop-bj');
    closeButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            mobileSearchPopup.style.display = 'none';
            mobileSearchBg.style.display = 'none';
            document.body.style.cssText = '';
        });
    });
    
    // 移动端搜索表单提交
    if (mobileSearchForm) {
        mobileSearchForm.addEventListener('submit', function(e) {
            const query = mobileSearchInput.value.trim();
            if (!query) {
                e.preventDefault();
                mobileSearchInput.focus();
                return false;
            }
        });
    }
    
    // 移动端搜索按钮点击
    if (mobileSearchSubmit) {
        mobileSearchSubmit.addEventListener('click', function(e) {
            const query = mobileSearchInput.value.trim();
            if (!query) {
                e.preventDefault();
                mobileSearchInput.focus();
                return false;
            }
            mobileSearchForm.submit();
        });
    }
    
    // 点击搜索框时显示弹窗
    searchInput.addEventListener('click', function() {
        this.focus();
        // 显示搜索历史和热门搜索
        searchHistory.style.display = 'block';
        hotSearch.style.display = 'block';
    });
    
    // 键盘输入时隐藏弹窗
    searchInput.addEventListener('keydown', function() {
        searchHistory.style.display = 'none';
        hotSearch.style.display = 'none';
    });
    
    // 鼠标离开搜索区域时隐藏弹窗
    searchContainer.addEventListener('mouseleave', function() {
        searchHistory.style.display = 'none';
        hotSearch.style.display = 'none';
    });
    
    // 点击搜索按钮
    searchButton.addEventListener('click', function(e) {
        const query = searchInput.value.trim();
        if (!query) {
            e.preventDefault();
            searchInput.focus();
            return false;
        }
        // 如果输入不为空，提交表单
        searchForm.submit();
    });
    
    // 回车键搜索
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const query = this.value.trim();
            if (query) {
                searchForm.submit();
            }
        }
    });
    
    // 表单提交事件
    searchForm.addEventListener('submit', function(e) {
        const query = searchInput.value.trim();
        if (!query) {
            e.preventDefault();
            searchInput.focus();
            return false;
        }
    });
    
    // 删除搜索历史按钮
    const deleteHistoryBtn = document.getElementById('re_del');
    if (deleteHistoryBtn) {
        deleteHistoryBtn.addEventListener('click', function() {
            const recordsList = document.querySelector('.records-list');
            recordsList.innerHTML = '';
            // 这里可以添加清除localStorage的逻辑
        });
    }
    
    // 点击弹窗背景关闭弹窗
    if (mobileSearchBg) {
        mobileSearchBg.addEventListener('click', function() {
            mobileSearchPopup.style.display = 'none';
            mobileSearchBg.style.display = 'none';
            document.body.style.cssText = '';
        });
    }
    
    // 播放记录功能
    const historyBtn = document.querySelector('.gen-history');
    const historyPopup = document.querySelector('.pop-2');
    const localHistoryList = document.getElementById('localHistoryList');
    const clearHistoryBtn = document.getElementById('l_history');
    
    // 播放记录按钮点击事件
    if (historyBtn) {
        historyBtn.addEventListener('click', function() {
            historyPopup.style.display = 'block';
            // 默认显示本地记录标签
            showLocalTab();
            loadPlayHistory();
        });
    }
    
    // 显示本地记录标签
    function showLocalTab() {
        // 激活本地记录标签
        document.querySelector('[data-tab="local"]').classList.add('on');
        document.querySelector('[data-tab="cloud"]').classList.remove('on');
        
        // 显示本地记录内容
        localTab.style.display = 'block';
        cloudTab.style.display = 'none';
    }
    
    // 标签切换功能
    const tabButtons = document.querySelectorAll('.play-catalog span');
    const localTab = document.getElementById('localTab');
    const cloudTab = document.getElementById('cloudTab');
    
    tabButtons.forEach(function(tab) {
        tab.addEventListener('click', function() {
            const tabType = this.getAttribute('data-tab');
            
            // 移除所有标签的激活状态
            tabButtons.forEach(t => t.classList.remove('on'));
            // 激活当前标签
            this.classList.add('on');
            
            // 隐藏所有内容
            localTab.style.display = 'none';
            cloudTab.style.display = 'none';
            
            // 显示对应内容
            if (tabType === 'local') {
                localTab.style.display = 'block';
                loadPlayHistory(); // 重新加载本地记录
            } else if (tabType === 'cloud') {
                cloudTab.style.display = 'block';
            }
        });
    });
    
    // 测试播放记录功能（开发时使用，生产环境可以删除）
    function testPlayHistory() {
        const testData = {
            videoId: 1,
            title: "测试视频",
            episode: 1,
            episodeTitle: "第1集",
            poster: "/template/yuyuyy/asset/img/img-bj-k.png",
            timestamp: Date.now() - 3600000, // 1小时前
            time: 1800, // 30分钟
            duration: 3600 // 1小时
        };
        
        let history = JSON.parse(localStorage.getItem('video_watch_history') || '[]');
        history.unshift(testData);
        localStorage.setItem('video_watch_history', JSON.stringify(history));
        console.log('测试数据已添加');
    }
    
    // 加载播放记录
    function loadPlayHistory() {
        try {
            const history = JSON.parse(localStorage.getItem('video_watch_history') || '[]');
            console.log('加载播放记录:', history); // 调试用
            
            if (history.length === 0) {
                localHistoryList.innerHTML = '<li class="no-history">暂无播放记录</li>';
                return;
            }
            
            let historyHTML = '';
            history.forEach((item, index) => {
                const timeAgo = getTimeAgo(item.timestamp);
                console.log('处理记录项:', item, '时间:', item.time, '格式化后:', formatTime(item.time)); // 调试用
                historyHTML += `
                    <li class="history-item">
                        <a href="/?page=play&id=${item.videoId}&episode=${item.episode}" class="history-link">
                            <div class="history-poster">
                                <img src="${item.poster || '/template/yuyuyy/asset/img/img-bj-k.png'}" alt="${item.title}" onerror="this.src='/template/yuyuyy/asset/img/img-bj-k.png'">
                            </div>
                            <div class="history-info">
                                <div class="history-title">${item.title} 第${item.episode}集</div>
                                <div class="history-time">${timeAgo}</div>
                                <div class="history-progress">播放至 ${formatTime(item.time)}</div>
                            </div>
                        </a>
                    </li>
                `;
            });
            
            localHistoryList.innerHTML = historyHTML;
        } catch (e) {
            console.warn('加载播放记录失败:', e);
            localHistoryList.innerHTML = '<li class="no-history">加载失败</li>';
        }
    }
    
    // 清空播放记录
    if (clearHistoryBtn) {
        clearHistoryBtn.addEventListener('click', function() {
            if (confirm('确定要清空所有播放记录吗？')) {
                localStorage.removeItem('video_watch_history');
                localHistoryList.innerHTML = '<li class="no-history">暂无播放记录</li>';
            }
        });
    }
    
    // 获取时间差
    function getTimeAgo(timestamp) {
        const now = Date.now();
        const diff = now - timestamp;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);
        
        if (days > 0) return `${days}天前`;
        if (hours > 0) return `${hours}小时前`;
        if (minutes > 0) return `${minutes}分钟前`;
        return '刚刚';
    }
    
    // 格式化时间
    function formatTime(seconds) {
        if (!seconds) return '0:00';
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    }
    
    // 关闭播放记录弹窗
    const closeHistoryButtons = document.querySelectorAll('.pop-2 .pop-bj');
    closeHistoryButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            historyPopup.style.display = 'none';
        });
    });
    
    // 点击弹窗背景关闭播放记录弹窗
    if (historyPopup) {
        historyPopup.addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
    }
    
    // 用户登录状态检查
    function checkUserLoginStatus() {
        // 检查是否有用户token
        const userToken = getCookie('user_token');
        if (userToken) {
            // 验证token有效性
            fetch('/api/user.php?action=get_user_info')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateUserUI(data.user);
                    } else {
                        clearUserUI();
                    }
                })
                .catch(error => {
                    clearUserUI();
                });
        } else {
            clearUserUI();
        }
    }
    
    // 更新用户UI
    function updateUserUI(user) {
        const userCenter = document.getElementById('userCenter');
        const loginBtn = document.getElementById('loginBtn');
        
        if (userCenter && loginBtn) {
            loginBtn.innerHTML = `
                <i class="fa ds-yonghu"></i>
                <em>${user.username}</em>
            `;
            loginBtn.href = '/user/';
            loginBtn.onclick = null; // 移除登录页跳转
            
            // 添加用户菜单
            if (!document.getElementById('userMenu')) {
                const userMenu = document.createElement('div');
                userMenu.id = 'userMenu';
                userMenu.className = 'user-menu';
                userMenu.innerHTML = `
                    <div class="user-menu-item">
                        <a href="/user/"><i class="fa ds-yonghu"></i>个人中心</a>
                    </div>
                    <div class="user-menu-item">
                        <a href="javascript:" onclick="logout()"><i class="fa ds-tuichu"></i>退出登录</a>
                    </div>
                `;
                userCenter.appendChild(userMenu);
                
                // 添加用户菜单样式
                if (!document.getElementById('userMenuStyle')) {
                    const style = document.createElement('style');
                    style.id = 'userMenuStyle';
                    style.textContent = `
                        .user-center {
                            position: relative;
                        }
                        .user-menu {
                            position: absolute;
                            top: 100%;
                            right: 0;
                            background: #fff;
                            border: 1px solid #ddd;
                            border-radius: 5px;
                            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                            display: none;
                            z-index: 1000;
                            min-width: 120px;
                        }
                        .user-menu-item {
                            padding: 10px 15px;
                            border-bottom: 1px solid #f0f0f0;
                        }
                        .user-menu-item:last-child {
                            border-bottom: none;
                        }
                        .user-menu-item a {
                            color: #333;
                            text-decoration: none;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        }
                        .user-menu-item a:hover {
                            color: #667eea;
                        }
                        .user-center:hover .user-menu {
                            display: block;
                        }
                    `;
                    document.head.appendChild(style);
                }
            }
        }
    }
    
    // 清除用户UI
    function clearUserUI() {
        const userCenter = document.getElementById('userCenter');
        const loginBtn = document.getElementById('loginBtn');
        
        if (userCenter && loginBtn) {
            loginBtn.innerHTML = `
                <i class="fa ds-yonghu"></i>
                <em>登录</em>
            `;
            loginBtn.href = '/user/login';
            
            // 移除用户菜单
            const userMenu = document.getElementById('userMenu');
            if (userMenu) {
                userMenu.remove();
            }
        }
    }
    
    // 退出登录
    function logout() {
        if (confirm('确定要退出登录吗？')) {
            fetch('/api/user.php?action=logout')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        clearUserUI();
                        window.location.reload();
                    }
                })
                .catch(error => {
                    clearUserUI();
                    window.location.reload();
                });
        }
    }
    
    // 获取Cookie
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }
    
    // 页面加载完成后检查用户登录状态
    document.addEventListener('DOMContentLoaded', function() {
        checkUserLoginStatus();
    });
});
</script>
