<?php
require_once dirname(__DIR__) . '/controllers/UserController.php';

// 设置CORS头
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 处理预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$controller = new UserController();
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'register':
            $controller->register();
            break;
            
        case 'login':
            $controller->login();
            break;
            
        case 'logout':
            $controller->logout();
            break;
            
        case 'add_favorite':
            $controller->addFavorite();
            break;
            
        case 'remove_favorite':
            $controller->removeFavorite();
            break;
            
        case 'check_favorite':
            $controller->checkFavorite();
            break;
            
        case 'get_favorites':
            $controller->getFavorites();
            break;
            
        case 'get_watch_history':
            $controller->getWatchHistory();
            break;
            
        case 'update_watch_history':
            $controller->updateWatchHistory();
            break;
            
        case 'clear_watch_history':
            $controller->clearWatchHistory();
            break;
            
        case 'remove_watch_history':
            $controller->removeWatchHistory();
            break;
            
        case 'get_user_info':
            $controller->getUserInfo();
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => '接口不存在']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '系统错误：' . $e->getMessage()]);
}
