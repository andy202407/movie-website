<?php

class Router {
    private $routes = [];
    private $templateEngine;
    private $videoModel;
    
    public function __construct($templateEngine, $videoModel) {
        $this->templateEngine = $templateEngine;
        $this->videoModel = $videoModel;
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
            'search' => 'search'
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
            default:
                $this->home();
        }
    }
    
    private function home() {
        $videos = $this->videoModel->getAllVideos();
        $categories = $this->videoModel->getAllCategories();
        $recommended = $this->videoModel->getRecommendedVideos(6);
        $bannerVideos = $this->videoModel->getBannerVideos(3); // 轮播图用的banner影片
        
        // 创建分类索引映射
        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['id']] = $cat;
        }
        
        $this->templateEngine->assignArray([
            'title' => '鱼鱼影院 - 免费在线观看高清电影电视剧综艺动漫',
            'videos' => $videos,
            'categories' => $categories,
            'categoryMap' => $categoryMap,
            'recommended' => $recommended,
            'bannerVideos' => $bannerVideos,
            'current_page' => 'home'
        ]);
        
        $this->templateEngine->display('home');
    }
    
    private function video() {
        $id = $_GET['id'] ?? 0;
        $video = $this->videoModel->getVideoById($id);
        
        if (!$video) {
            header('HTTP/1.0 404 Not Found');
            $categories = $this->videoModel->getAllCategories();
            $this->templateEngine->assignArray([
                'title' => '页面不存在 - 鱼鱼影院',
                'categories' => $categories
            ]);
            $this->templateEngine->display('404');
            return;
        }
        
        $category = $this->videoModel->getCategoryById($video['category_id']);
        $categories = $this->videoModel->getAllCategories();
        $recommended = $this->videoModel->getRecommendedVideos(4);
        
        $this->templateEngine->assignArray([
            'title' => $video['title'] . ' - 鱼鱼影院',
            'video' => $video,
            'category' => $category,
            'categories' => $categories,
            'recommended' => $recommended,
            'current_page' => 'video'
        ]);
        
        $this->templateEngine->display('video');
    }
    
    private function play() {
        $id = $_GET['id'] ?? 0;
        $video = $this->videoModel->getVideoById($id);
        
        if (!$video) {
            header('HTTP/1.0 404 Not Found');
            $categories = $this->videoModel->getAllCategories();
            $this->templateEngine->assignArray([
                'title' => '页面不存在 - 鱼鱼影院',
                'categories' => $categories
            ]);
            $this->templateEngine->display('404');
            return;
        }
        
        $category = $this->videoModel->getCategoryById($video['category_id']);
        $categories = $this->videoModel->getAllCategories();
        
        $this->templateEngine->assignArray([
            'title' => '免费在线看 ' . $video['title'] . ' - 动漫在线观看 - 鱼鱼影院',
            'video' => $video,
            'category' => $category,
            'categories' => $categories,
            'current_page' => 'play'
        ]);
        
        $this->templateEngine->display('play');
    }
    
    private function category() {
        $categoryId = $_GET['id'] ?? 0;
        $category = $this->videoModel->getCategoryById($categoryId);
        $categories = $this->videoModel->getAllCategories();
        
        if (!$category) {
            header('HTTP/1.0 404 Not Found');
            $this->templateEngine->assignArray([
                'title' => '页面不存在 - 鱼鱼影院',
                'categories' => $categories
            ]);
            $this->templateEngine->display('404');
            return;
        }
        
        $videos = $this->videoModel->getVideosByCategory($categoryId);
        
        $this->templateEngine->assignArray([
            'title' => $category['name'] . ' - 鱼鱼影院',
            'category' => $category,
            'videos' => $videos,
            'categories' => $categories,
            'current_page' => 'category'
        ]);
        
        $this->templateEngine->display('category');
    }
    
    private function list() {
        $categoryId = $_GET['category'] ?? 0;
        $page = max(1, intval($_GET['p'] ?? 1));
        $perPage = 12; // 每页显示12个
        $sort = $_GET['sort'] ?? 'latest'; // 默认按最新排序
        
        $category = $this->videoModel->getCategoryById($categoryId);
        $categories = $this->videoModel->getAllCategories();
        
        if (!$category) {
            header('HTTP/1.0 404 Not Found');
            $this->templateEngine->assignArray([
                'title' => '页面不存在 - 鱼鱼影院',
                'categories' => $categories
            ]);
            $this->templateEngine->display('404');
            return;
        }
        
        // 获取分类下的所有视频
        $allVideos = $this->videoModel->getVideosByCategory($categoryId);
        
        // 根据排序方式处理
        switch ($sort) {
            case 'rating':
                usort($allVideos, function($a, $b) {
                    return $b['rating'] <=> $a['rating'];
                });
                break;
            case 'year':
                usort($allVideos, function($a, $b) {
                    return $b['year'] <=> $a['year'];
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
        
        $this->templateEngine->assignArray([
            'title' => $category['name'] . ' - 第' . $page . '页 - 鱼鱼影院',
            'category' => $category,
            'categories' => $categories,
            'categoryMap' => $categoryMap,
            'videos' => $videos,
            'categoryId' => $categoryId,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'sort' => $sort,
            'current_page' => 'list'
        ]);
        
        $this->templateEngine->display('list');
    }
    
    private function search() {
        $keyword = $_GET['wd'] ?? '';
        $videos = [];
        
        if ($keyword) {
            $videos = $this->videoModel->searchVideos($keyword);
        }
        
        $categories = $this->videoModel->getAllCategories();
        $latestVideos = $this->videoModel->getLatestVideos(5); // 获取最新5部影片
        
        $title = $keyword ? '搜索: ' . $keyword . ' - 鱼鱼影院' : '搜索影片 - 鱼鱼影院';
        
        $this->templateEngine->assignArray([
            'title' => $title,
            'keyword' => $keyword,
            'videos' => $videos,
            'categories' => $categories,
            'latestVideos' => $latestVideos, // 传递最新影片数据
            'current_page' => 'search'
        ]);
        
        $this->templateEngine->display('search');
    }
}