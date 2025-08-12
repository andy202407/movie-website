<?php
// 获取分类数据
$videoModel = new VideoModel();
$categories = $videoModel->getAllCategories();
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
</style>

<!-- Logo显示 -->
<!-- <div class="site-logo">
    <a href="/" style="color: inherit; text-decoration: none;">
        鱼鱼影院
    </a>
</div> -->

<div class="head flex between head-c header_nav0">
    <div class="left flex">
        <div class="logo">
            <a class="logo-brand" href="/">
                <img class="logo1 none" src="/template/yuyuyy/asset/img/logo-1.png" alt="鱼鱼影院">
                <img class="logo2 none" src="/template/yuyuyy/asset/img/logo-2.png" alt="鱼鱼影院">
            </a>
        </div>
        <div class="head-nav ft4 roll bold0 pc-show1 wap-show1">
            <ul class="swiper-wrapper">
                <li class="swiper-slide"><a target="_self" href="/" class="<?= ($_GET['page'] ?? 'home') == 'home' ? 'current cor6' : '' ?>"><em class="fa ds-zhuye"></em><em class="fa none ds-zhuye2"></em>首页</a></li>
                <?php foreach ($categories as $cat): ?>
                <li class="swiper-slide"><a target="_self" href="/?page=list&category=<?= $cat['id'] ?>" class="<?= (($_GET['page'] ?? '') == 'list' && ($_GET['category'] ?? '') == $cat['id']) ? 'current cor6' : '' ?>"><em class="fa ds-dianying"></em><em class="fa none ds-dianying2"></em><?= $this->escape($cat['name']) ?></a></li>
                <?php endforeach; ?>
                <li class="rel head-more-a">
                    <a class="this-get" href="javascript:">更多<em class="fa nav-more" style="font-size:18px">&#xe563;</em></a>
                    <div class="head-more none box size">
                        <a target="_self" href="/" class="nav-link none2 <?= ($_GET['page'] ?? 'home') == 'home' ? 'cor6' : '' ?>"><em class="fa ds-zhuye"></em><em class="fa none ds-zhuye2"></em>首页</a>
                        <?php foreach ($categories as $cat): ?>
                        <a target="_self" href="/?page=list&category=<?= $cat['id'] ?>" class="nav-link none2 <?= (($_GET['page'] ?? '') == 'list' && ($_GET['category'] ?? '') == $cat['id']) ? 'cor6' : '' ?>"><em class="fa ds-dianying"></em><em class="fa none ds-dianying2"></em><?= $this->escape($cat['name']) ?></a>
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
        <div class="gen-left-list margin wap-show pc-hide">
            <a class="content-center" href="javascript:">
                <i class="fa ds-menu"></i>
            </a>
        </div>
        
        <!-- <div class="gen-history margin"><a class="content-center" href="javascript:"><i class="fa ds-lishijilu"></i><em>播放记录</em></a></div>
        <div class="margin"><a class="content-center" href="/"><i class="fa ds-xiazai"></i><em>客户端</em></a></div>
        <div class="margin user-center"><a class="content-center" href="javascript:"><i class="fa ds-yonghu"></i><em>登录</em></a></div> -->
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
});
</script>
