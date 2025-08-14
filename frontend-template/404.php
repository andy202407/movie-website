<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no,viewport-fit=cover">
    <meta name="theme-color" content="#1a1a1a" />
    <title>页面不存在 - 星海影院</title>
    <meta name="keywords" content="404,页面不存在,星海影院" />
    <meta name="description" content="很抱歉，您访问的页面不存在" />
    
    <link href="/template/yuyuyy/asset/css/common.css" rel="stylesheet" type="text/css" />
    <script src="/template/yuyuyy/asset/js/jquery.js"></script>
    <script src="/template/yuyuyy/asset/js/assembly.js"></script>
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
                <!-- <img class="logo2 none" src="/template/yuyuyy/asset/img/logo-2.png" alt="星海影院"> -->
            </a>
        </div>
    </div>
    <div class="right flex">
        <div class="this-search">
            <form id="search2" name="search" class="flex-public" method="get" action="/">
                <input type="hidden" name="page" value="search">
                <input type="text" name="q" class="this-input flex-auto cor4" value="" placeholder="搜索影片..." autocomplete="off">
                <button type="submit" class="fa ds-sousuo ol2"></button>
            </form>
        </div>
    </div>
</div>

<!-- 404内容 -->
<div class="container">
    <div class="error-404">
        <div class="error-content">
            <div class="error-icon">
                <span class="error-number">4</span>
                <span class="error-film">🎬</span>
                <span class="error-number">4</span>
            </div>
            
            <h1 class="error-title">页面走丢了</h1>
            <p class="error-subtitle">很抱歉，您访问的页面可能已被删除、重命名或暂时不可用</p>
            
            <div class="error-actions">
                <a href="/" class="btn-primary">
                    <i class="fa ds-zhuye"></i>
                    返回首页
                </a>
                <a href="javascript:history.back()" class="btn-secondary">
                    <i class="fa ds-fanhui"></i>
                    返回上页
                </a>
            </div>
            
            <div class="search-section">
                <h3>试试搜索您要找的内容</h3>
                <form method="GET" action="/" class="search-form-404">
                    <input type="hidden" name="page" value="search">
                    <div class="search-group">
                        <input type="text" name="q" placeholder="输入影片名称..." class="search-input-404">
                        <button type="submit" class="search-btn-404">
                            <i class="fa ds-sousuo"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="suggestions">
                <h3>您可能感兴趣的内容</h3>
                <div class="suggestion-links">
                    <?php
                    // 动态获取分类数据
                    $videoModel = new VideoModel();
                    $categories = $videoModel->getAllCategories();
                    foreach ($categories as $category):
                    ?>
                    <a href="/?page=list&category=<?= $category['id'] ?>" class="suggestion-item">
                        <i class="fa ds-dianying"></i>
                        <span><?= $this->escape($category['name']) ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
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

<style>
.error-404 {
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 0;
}

.error-content {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.error-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2rem;
    font-size: 6rem;
    font-weight: bold;
}

.error-number {
    color: #667eea;
    text-shadow: 0 0 30px rgba(102, 126, 234, 0.5);
    animation: glow 2s ease-in-out infinite alternate;
}

.error-film {
    margin: 0 1rem;
    font-size: 5rem;
    animation: bounce 2s infinite;
}

@keyframes glow {
    from {
        text-shadow: 0 0 30px rgba(102, 126, 234, 0.5);
    }
    to {
        text-shadow: 0 0 50px rgba(102, 126, 234, 0.8);
    }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-20px);
    }
    60% {
        transform: translateY(-10px);
    }
}

.error-title {
    font-size: 2.5rem;
    color: #fff;
    margin-bottom: 1rem;
}

.error-subtitle {
    font-size: 1.2rem;
    color: #ccc;
    margin-bottom: 3rem;
    line-height: 1.6;
}

.error-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.btn-primary, .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    border-radius: 30px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s;
    font-size: 1.1rem;
}

.btn-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.btn-primary:hover, .btn-secondary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.search-section {
    margin-bottom: 3rem;
}

.search-section h3 {
    color: #fff;
    font-size: 1.3rem;
    margin-bottom: 1rem;
}

.search-form-404 {
    max-width: 400px;
    margin: 0 auto;
}

.search-group {
    display: flex;
    gap: 0.5rem;
}

.search-input-404 {
    flex: 1;
    padding: 0.8rem 1.2rem;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 25px;
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    font-size: 1rem;
    outline: none;
    transition: all 0.3s;
}

.search-input-404::placeholder {
    color: #ccc;
}

.search-input-404:focus {
    border-color: #667eea;
    background: rgba(255, 255, 255, 0.15);
}

.search-btn-404 {
    padding: 0.8rem 1.2rem;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 1rem;
}

.search-btn-404:hover {
    background: #5a6fd8;
    transform: scale(1.05);
}

.suggestions h3 {
    color: #fff;
    font-size: 1.3rem;
    margin-bottom: 1.5rem;
}

.suggestion-links {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
    max-width: 500px;
    margin: 0 auto;
}

.suggestion-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    text-decoration: none;
    color: #fff;
    transition: all 0.3s;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.suggestion-item:hover {
    background: rgba(102, 126, 234, 0.2);
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.suggestion-item i {
    font-size: 1.5rem;
    color: #667eea;
}

.suggestion-item span {
    font-size: 0.9rem;
    color: #fff;
}

@media (max-width: 768px) {
    .error-icon {
        font-size: 4rem;
    }
    
    .error-film {
        font-size: 3rem;
    }
    
    .error-title {
        font-size: 2rem;
    }
    
    .error-subtitle {
        font-size: 1rem;
    }
    
    .error-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .search-group {
        flex-direction: column;
    }
    
    .suggestion-links {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

</body>
</html>