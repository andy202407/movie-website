<?php
// 引入数据库连接
require_once __DIR__ . '/Database.php';

class Router {
    private $routes = [];
    private $templateEngine;
    private $videoModel;
    private $cacheManager;
    
    public function __construct($templateEngine, $videoModel, $cacheManager = null) {
        $this->templateEngine = $templateEngine;
        $this->videoModel = $videoModel;
        $this->cacheManager = $cacheManager;
        $this->setupRoutes();
    }
    
    private function setupRoutes() {
        $this->routes = [
            '' => 'home',
            'home' => 'home',
            'video' => 'video',
            'play' => 'play',
            'category' => 'category',
            'list' => 'list',
            'search' => 'search',
            'user' => 'user',
            'user/register' => 'user_register',
            'user/login' => 'user_login'
        ];
    }
    
    public function route() {
        // 获取请求路径
        $requestUri = $_SERVER['REQUEST_URI'];
        $path = parse_url($requestUri, PHP_URL_PATH);
        
        // 移除开头的斜杠
        $path = ltrim($path, '/');
        
        // 检查是否有搜索参数
        if (empty($path) && isset($_GET['wd']) && !empty($_GET['wd'])) {
            // 如果有搜索参数，重定向到搜索页面
            $searchUrl = '/search?' . http_build_query($_GET);
            header('Location: ' . $searchUrl);
            exit;
        }
        
        // 检查路径是否在路由表中
        $action = $this->routes[$path] ?? 'home';
        
        // 特殊处理搜索路径
        if ($path === 'search') {
            $this->search();
            return;
        }
        
        // 特殊处理用户路径
        if (strpos($path, 'user/') === 0) {
            $userAction = substr($path, 5); // 移除 'user/' 前缀
            if ($userAction === 'register') {
                $this->user_register();
                return;
            } elseif ($userAction === 'login') {
                $this->user_login();
                return;
            } elseif (empty($userAction)) {
                $this->user();
                return;
            }
        }
        
        // 检查是否有查询参数路由（兼容旧版本）
        if (empty($path) && isset($_GET['page'])) {
            $page = $_GET['page'];
            if (in_array($page, ['list', 'video', 'play', 'category', 'search'])) {
                $action = $page;
                switch ($action) {
                    case 'list':
                        $this->list();
                        return;
                    case 'video':
                        $this->video();
                        return;
                    case 'play':
                        $this->play();
                        return;
                    case 'category':
                        $this->category();
                        return;
                    case 'search':
                        $this->search();
                        return;
                }
            }
        }
        
        // 如果是空路径，设置为home
        if (empty($path)) {
            $path = 'home';
        }
        
        // 检查路径是否在路由表中
        $action = $this->routes[$path] ?? 'home';
        
        switch ($action) {
            case 'home':
                $this->home();
                break;
            case 'video':
                $this->video();
                break;
            case 'play':
                $this->play();
                break;
            case 'category':
                $this->category();
                break;
            case 'list':
                $this->list();
                break;
            case 'search':
                $this->search();
                break;
            case 'user':
                $this->user();
                break;
            case 'user_register':
                $this->user_register();
                break;
            case 'user_login':
                $this->user_login();
                break;
            default:
                $this->home();
        }
    }
    
    private function home() {
        // 获取父分类
        $parentCategories = $this->videoModel->getParentCategories();
        
        // 为每个父分类获取最新的10部影片
        $parentCategoryVideos = [];
        foreach ($parentCategories as $parentCategory) {
            $videos = $this->videoModel->getLatestVideosByParentCategory($parentCategory['id'], 10);
            if (!empty($videos)) {
                $parentCategoryVideos[$parentCategory['id']] = [
                    'category' => $parentCategory,
                    'videos' => $videos
                ];
            }
        }
        
        $categories = $this->videoModel->getAllCategories();
        $recommended = $this->videoModel->getRecommendedVideos(100);
        $bannerVideos = $this->videoModel->getBannerVideos(100); // 轮播图用的banner影片
        
        // 创建分类索引映射
        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['id']] = $cat;
        }
        
        $this->templateEngine->assignArray([
            'title' => '星海影院 - 免费在线观看高清电影电视剧综艺动漫',
            'parentCategoryVideos' => $parentCategoryVideos,
            'categories' => $categories,
            'categoryMap' => $categoryMap,
            'recommended' => $recommended,
            'bannerVideos' => $bannerVideos,
            'current_page' => 'home'
        ]);
        
        // 渲染页面内容
        $content = $this->templateEngine->render('home');
        
        // 如果启用了缓存，保存到缓存
        if ($this->cacheManager && $this->cacheManager->shouldCache()) {
            $requestUri = $_SERVER['REQUEST_URI'];
            $result = $this->cacheManager->set($requestUri, $content, $_GET);
            if ($result) {
                error_log("缓存已保存: {$requestUri}");
            } else {
                error_log("缓存保存失败: {$requestUri}");
            }
        }
        
        // 输出内容
        echo $content;
    }
    
    private function video() {
        $id = $_GET['id'] ?? 0;
        $video = $this->videoModel->getVideoById($id);
        
        if (!$video) {
            header('HTTP/1.0 404 Not Found');
            $categories = $this->videoModel->getAllCategories();
            $this->templateEngine->assignArray([
                'title' => '页面不存在 - 星海影院',
                'categories' => $categories
            ]);
            $this->templateEngine->display('404');
            return;
        }
        
        $category = $this->videoModel->getCategoryById($video['category_id']);
        $categories = $this->videoModel->getAllCategories();
        $recommended = $this->videoModel->getRecommendedVideos(4);
        
        $this->templateEngine->assignArray([
            'title' => $video['title'] . ' - 星海影院',
            'video' => $video,
            'category' => $category,
            'categories' => $categories,
            'recommended' => $recommended,
            'current_page' => 'video'
        ]);
        
        // 渲染页面内容
        $content = $this->templateEngine->render('video');
        
        // 如果启用了缓存，保存到缓存
        if ($this->cacheManager && $this->cacheManager->shouldCache()) {
            $this->cacheManager->set($_SERVER['REQUEST_URI'], $content, $_GET);
        }
        
        // 输出内容
        echo $content;
    }
    
    private function play() {
        $videoId = $_GET['id'] ?? 0;
        $video = $this->videoModel->getVideoById($videoId);
        
        if (!$video) {
            header('HTTP/1.0 404 Not Found');
            $this->templateEngine->assignArray([
                'title' => '页面不存在 - 星海影院',
                'categories' => $this->videoModel->getAllCategories()
            ]);
            $this->templateEngine->display('404');
            return;
        }
        
        // 获取视频的主要分类（用于面包屑导航等）
        $mainCategory = null;
        if (isset($video['category_ids']) && !empty($video['category_ids'])) {
            // 使用第一个分类ID作为主要分类
            $mainCategoryId = $video['category_ids'][0];
            $mainCategory = $this->videoModel->getCategoryById($mainCategoryId);
        } elseif (isset($video['category_id']) && !empty($video['category_id'])) {
            // 兼容旧数据
            $mainCategory = $this->videoModel->getCategoryById($video['category_id']);
        }
        
        $categories = $this->videoModel->getAllCategories();
        
        $this->templateEngine->assignArray([
            'title' => '免费在线看 ' . $video['title'] . ' - 动漫在线观看 - 星海影院',
            'video' => $video,
            'category' => $mainCategory,
            'categories' => $categories,
            'current_page' => 'play'
        ]);
        
        // 渲染页面内容
        $content = $this->templateEngine->render('play');
        
        // 如果启用了缓存，保存到缓存
        if ($this->cacheManager && $this->cacheManager->shouldCache()) {
            $this->cacheManager->set($_SERVER['REQUEST_URI'], $content, $_GET);
        }
        
        // 输出内容
        echo $content;
    }
    
    private function category() {
        $categoryId = $_GET['id'] ?? 0;
        $category = $this->videoModel->getCategoryById($categoryId);
        $categories = $this->videoModel->getAllCategories();
        
        if (!$category) {
            header('HTTP/1.0 404 Not Found');
            $this->templateEngine->assignArray([
                'title' => '页面不存在 - 星海影院',
                'categories' => $categories
            ]);
            $this->templateEngine->display('404');
            return;
        }
        
        $videos = $this->videoModel->getVideosByCategory($categoryId);
        
        $this->templateEngine->assignArray([
            'title' => $category['name'] . ' - 星海影院',
            'category' => $category,
            'videos' => $videos,
            'categories' => $categories,
            'current_page' => 'category'
        ]);
        
        // 渲染页面内容
        $content = $this->templateEngine->render('category');
        
        // 如果启用了缓存，保存到缓存
        if ($this->cacheManager && $this->cacheManager->shouldCache()) {
            $this->cacheManager->set($_SERVER['REQUEST_URI'], $content, $_GET);
        }
        
        // 输出内容
        echo $content;
    }
    

    
    private function list() {
        $categoryId = $_GET['category'] ?? 0;
        $page = max(1, intval($_GET['p'] ?? 1));
        $perPage = 12; // 每页显示12个
        $sort = $_GET['sort'] ?? 'latest'; // 默认按最新排序
        
        // 新增筛选参数
        $filterYear = $_GET['year'] ?? '';
        $filterRegion = $_GET['region'] ?? '';
        $filterCategory = $_GET['filter_category'] ?? '';
        
        $category = $this->videoModel->getCategoryById($categoryId);
        $categories = $this->videoModel->getAllCategories();
        
        if (!$category) {
            header('HTTP/1.0 404 Not Found');
            $this->templateEngine->assignArray([
                'title' => '页面不存在 - 星海影院',
                'categories' => $categories
            ]);
            $this->templateEngine->display('404');
            return;
        }
        
        // 获取分类下的所有视频（支持筛选）
        // 检查是否为父分类，如果是则获取其下所有子分类的影片
        if ($this->videoModel->isParentCategory($categoryId)) {
            $allVideos = $this->videoModel->getVideosByParentCategory($categoryId);
        } else {
            $allVideos = $this->videoModel->getVideosByCategory($categoryId);
        }
        
        // 应用筛选条件
        if (!empty($filterYear) || !empty($filterRegion) || !empty($filterCategory)) {
            $allVideos = array_filter($allVideos, function($video) use ($filterYear, $filterRegion, $filterCategory) {
                // 年份筛选
                if (!empty($filterYear) && $video['year'] != $filterYear) {
                    return false;
                }
                
                // 地区筛选
                if (!empty($filterRegion) && $video['region'] != $filterRegion) {
                    return false;
                }
                
                // 分类筛选（检查是否包含指定分类）
                if (!empty($filterCategory)) {
                    $filterCategoryId = intval($filterCategory);
                    if (!isset($video['category_ids']) || !in_array($filterCategoryId, $video['category_ids'])) {
                        return false;
                    }
                }
                
                return true;
            });
        }
        
        // 根据排序方式处理
        switch ($sort) {
            case 'rating':
                usort($allVideos, function($a, $b) {
                    $ratingA = floatval(str_replace(['暂无评分', '分钟'], '', $a['rating']));
                    $ratingB = floatval(str_replace(['暂无评分', '分钟'], '', $b['rating']));
                    return $ratingB <=> $ratingA;
                });
                break;
            case 'year':
                usort($allVideos, function($a, $b) {
                    $yearA = intval($a['year']);
                    $yearB = intval($b['year']);
                    return $yearB <=> $yearA;
                });
                break;
            case 'latest':
            default:
                // 假设数组已经按最新排序
                break;
        }
        
        $total = count($allVideos);
        $totalPages = max(1, ceil($total / $perPage));
        
        // 确保页码有效
        $page = min($page, $totalPages);
        
        // 分页处理
        $offset = ($page - 1) * $perPage;
        $videos = array_slice($allVideos, $offset, $perPage);
        
        // 创建分类索引映射，便于模板中按ID查找
        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['id']] = $cat;
        }
        
        // 获取可用的筛选选项
        $availableYears = [];
        $availableRegions = [];
        $availableFilterCategories = [];
        
        // 从当前分类下的影片中提取可用的筛选选项（只显示当前分类下的选项）
        foreach ($allVideos as $video) {
            // 年份
            if (!empty($video['year']) && $video['year'] != '未知') {
                $availableYears[] = $video['year'];
            }
            
            // 地区
            if (!empty($video['region']) && $video['region'] != '未知地区') {
                $availableRegions[] = $video['region'];
            }
        }
        
        // 分类筛选选项：智能判断
        if ($this->videoModel->isParentCategory($categoryId)) {
            // 获取当前父分类下的所有子分类ID
            $childCategories = $this->videoModel->getChildCategories($categoryId);
            if (!empty($childCategories)) {
                // 如果有子分类，只显示子分类
                $availableFilterCategories = array_column($childCategories, 'id');
            } else {
                // 如果没有子分类，显示所有分类
                foreach ($categories as $cat) {
                    $availableFilterCategories[] = $cat['id'];
                }
            }
        } else {
            // 如果是子分类，显示所有分类
            foreach ($categories as $cat) {
                $availableFilterCategories[] = $cat['id'];
            }
        }
        
        // 去重并排序
        $availableYears = array_unique($availableYears);
        rsort($availableYears); // 年份降序
        
        $availableRegions = array_unique($availableRegions);
        sort($availableRegions); // 地区按字母顺序
        
        $availableFilterCategories = array_unique($availableFilterCategories);
        sort($availableFilterCategories);
        
        $this->templateEngine->assignArray([
            'title' => $category['name'] . ' - 第' . $page . '页 - 星海影院',
            'category' => $category,
            'categories' => $categories,
            'categoryMap' => $categoryMap,
            'videos' => $videos,
            'categoryId' => $categoryId,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'sort' => $sort,
            'current_page' => 'list',
            // 筛选相关
            'filterYear' => $filterYear,
            'filterRegion' => $filterRegion,
            'filterCategory' => $filterCategory,
            'availableYears' => $availableYears,
            'availableRegions' => $availableRegions,
            'availableFilterCategories' => $availableFilterCategories
        ]);
        
        // 渲染页面内容
        $content = $this->templateEngine->render('list');
        
        // 如果启用了缓存，保存到缓存
        if ($this->cacheManager && $this->cacheManager->shouldCache()) {
            $this->cacheManager->set($_SERVER['REQUEST_URI'], $content, $_GET);
        }
        
        // 输出内容
        echo $content;
    }
    
    private function search() {
        $keyword = $_GET['wd'] ?? '';
        $videos = [];
        
        if ($keyword) {
            $videos = $this->videoModel->searchVideos($keyword);
        }
        
        $categories = $this->videoModel->getAllCategories();
        $latestVideos = $this->videoModel->getLatestVideos(5); // 获取最新5部影片
        
        $title = $keyword ? '搜索: ' . $keyword . ' - 星海影院' : '搜索影片 - 星海影院';
        
        $this->templateEngine->assignArray([
            'title' => $title,
            'keyword' => $keyword,
            'videos' => $videos,
            'categories' => $categories,
            'latestVideos' => $latestVideos, // 传递最新影片数据
            'current_page' => 'search'
        ]);
        
        // 渲染页面内容
        $content = $this->templateEngine->render('search');
        
        // 如果启用了缓存，保存到缓存
        if ($this->cacheManager && $this->cacheManager->shouldCache()) {
            $this->cacheManager->set($_SERVER['REQUEST_URI'], $content, $_GET);
        }
        
        // 输出内容
        echo $content;
    }
    
    private function user() {
        // 检查用户是否已登录
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
            header('Location: /user/login');
            exit;
        }
        
        // 获取用户信息
        $userInfo = $this->getUserInfo($_SESSION['user_id']);
        
        // 获取分类数据用于header
        $categories = $this->videoModel->getAllCategories();
        $parentCategories = array_filter($categories, function($cat) {
            return empty($cat['parent_id']);
        });
        
        // 设置变量，使其在模板中可用
        $this->templateEngine->assignArray([
            'userInfo' => $userInfo,
            'categories' => $categories,
            'parentCategories' => $parentCategories
        ]);
        
        // 使用模板引擎显示用户页面
        $this->templateEngine->display('user/index');
        exit;
    }
    
    // 添加escape方法，用于模板中的安全输出
    public function escape($string) {
        if (is_string($string)) {
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        }
        return $string;
    }
    
    // 获取用户信息
    private function getUserInfo($userId) {
        try {
            $db = Database::getInstance();
            $user = $db->fetchOne("SELECT id, username, email, created_at, last_login FROM users WHERE id = ?", [$userId]);
            return $user;
        } catch (Exception $e) {
            error_log("获取用户信息失败: " . $e->getMessage());
            return null;
        }
    }
    
    private function user_register() {
        // 直接包含注册页面
        include 'frontend-template/user/register.php';
        exit;
    }
    
    private function user_login() {
        // 直接包含登录页面
        include 'frontend-template/user/login.php';
        exit;
    }
}