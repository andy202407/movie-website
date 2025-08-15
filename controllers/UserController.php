<?php

require_once dirname(__DIR__) . '/models/UserModel.php';

class UserController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    // 用户注册
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => '请求方法错误']);
            return;
        }
        
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // 验证输入
        if (empty($username) || empty($password)) {
            $this->jsonResponse(['success' => false, 'message' => '请填写完整信息']);
            return;
        }
        
        if (strlen($username) < 3 || strlen($username) > 20) {
            $this->jsonResponse(['success' => false, 'message' => '用户名长度应在3-20个字符之间']);
            return;
        }
        
        if (strlen($password) < 6) {
            $this->jsonResponse(['success' => false, 'message' => '密码长度至少6位']);
            return;
        }
        
        $result = $this->userModel->register($username, $password);
        $this->jsonResponse($result);
    }
    
    // 用户登录
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => '请求方法错误']);
            return;
        }
        
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $this->jsonResponse(['success' => false, 'message' => '请填写用户名和密码']);
            return;
        }
        
        $result = $this->userModel->login($username, $password);
        
        if ($result['success']) {
            // 设置session
            session_start();
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['username'] = $result['user']['username'];
            $_SESSION['email'] = $result['user']['email'] ?? '';
            
            // 设置cookie（可选）
            if (isset($_POST['remember']) && $_POST['remember'] == '1') {
                setcookie('user_token', $result['user']['id'], time() + 30 * 24 * 3600, '/');
            }
        }
        
        $this->jsonResponse($result);
    }
    
    // 用户登出
    public function logout() {
        session_start();
        session_destroy();
        setcookie('user_token', '', time() - 3600, '/');
        
        // 检查是否是AJAX请求
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            // AJAX请求，返回JSON响应
            $this->jsonResponse(['success' => true, 'message' => '登出成功', 'redirect' => '/']);
        } else {
            // 普通请求，直接重定向到首页
            header('Location: /');
            exit;
        }
    }
    
    // 添加收藏
    public function addFavorite() {
        if (!$this->checkLogin()) {
            $this->jsonResponse(['success' => false, 'message' => '请先登录']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => '请求方法错误']);
            return;
        }
        
        $videoId = intval($_POST['video_id'] ?? 0);
        $type = intval($_POST['type'] ?? 1);
        
        if ($videoId <= 0) {
            $this->jsonResponse(['success' => false, 'message' => '视频ID无效']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $result = $this->userModel->addFavorite($userId, $videoId, $type);
        $this->jsonResponse($result);
    }
    
    // 取消收藏
    public function removeFavorite() {
        if (!$this->checkLogin()) {
            $this->jsonResponse(['success' => false, 'message' => '请先登录']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => '请求方法错误']);
            return;
        }
        
        $videoId = intval($_POST['video_id'] ?? 0);
        
        if ($videoId <= 0) {
            $this->jsonResponse(['success' => false, 'message' => '视频ID无效']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $result = $this->userModel->removeFavorite($userId, $videoId);
        $this->jsonResponse($result);
    }
    
    // 检查收藏状态
    public function checkFavorite() {
        if (!$this->checkLogin()) {
            $this->jsonResponse(['success' => false, 'message' => '请先登录']);
            return;
        }
        
        $videoId = intval($_GET['video_id'] ?? 0);
        
        if ($videoId <= 0) {
            $this->jsonResponse(['success' => false, 'message' => '视频ID无效']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $isFavorited = $this->userModel->isFavorited($userId, $videoId);
        
        $this->jsonResponse(['success' => true, 'is_favorited' => $isFavorited]);
    }
    
    // 获取收藏列表
    public function getFavorites() {
        if (!$this->checkLogin()) {
            $this->jsonResponse(['success' => false, 'message' => '请先登录']);
            return;
        }
        
        $page = intval($_GET['page'] ?? 1);
        $limit = intval($_GET['limit'] ?? 20);
        
        $userId = $_SESSION['user_id'];
        $result = $this->userModel->getFavorites($userId, $page, $limit);
        $this->jsonResponse($result);
    }
    
    // 获取观看历史
    public function getWatchHistory() {
        if (!$this->checkLogin()) {
            $this->jsonResponse(['success' => false, 'message' => '请先登录']);
            return;
        }
        
        $page = intval($_GET['page'] ?? 1);
        $limit = intval($_GET['limit'] ?? 20);
        
        $userId = $_SESSION['user_id'];
        $result = $this->userModel->getWatchHistory($userId, $page, $limit);
        $this->jsonResponse($result);
    }
    
    // 更新观看历史
    public function updateWatchHistory() {
        if (!$this->checkLogin()) {
            $this->jsonResponse(['success' => false, 'message' => '请先登录']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => '请求方法错误']);
            return;
        }
        
        $videoId = intval($_POST['video_id'] ?? 0);
        $episode = intval($_POST['episode'] ?? 1);
        $progress = intval($_POST['progress'] ?? 0);
        
        if ($videoId <= 0) {
            $this->jsonResponse(['success' => false, 'message' => '视频ID无效']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $result = $this->userModel->updateWatchHistory($userId, $videoId, $episode, $progress);
        $this->jsonResponse($result);
    }

    // 清除观看历史（单条）
    public function removeWatchHistory() {
        if (!$this->checkLogin()) {
            $this->jsonResponse(['success' => false, 'message' => '请先登录']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => '请求方法错误']);
            return;
        }
        
        $videoId = intval($_POST['video_id'] ?? 0);
        
        if ($videoId <= 0) {
            $this->jsonResponse(['success' => false, 'message' => '视频ID无效']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $result = $this->userModel->removeWatchHistory($userId, $videoId);
        $this->jsonResponse($result);
    }

    // 清除观看历史（全部）
    public function clearWatchHistory() {
        if (!$this->checkLogin()) {
            $this->jsonResponse(['success' => false, 'message' => '请先登录']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => '请求方法错误']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $result = $this->userModel->clearWatchHistory($userId);
        $this->jsonResponse($result);
    }
    
    // 获取用户信息
    public function getUserInfo() {
        if (!$this->checkLogin()) {
            $this->jsonResponse(['success' => false, 'message' => '请先登录']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $userInfo = $this->userModel->getUserById($userId);
        
        if ($userInfo) {
            $this->jsonResponse(['success' => true, 'user' => $userInfo]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => '获取用户信息失败']);
        }
    }
    
    // 检查登录状态
    private function checkLogin() {
        session_start();
        
        // 检查session
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
            return true;
        }
        
        // 检查cookie
        if (isset($_COOKIE['user_token']) && $_COOKIE['user_token'] > 0) {
            $_SESSION['user_id'] = $_COOKIE['user_token'];
            return true;
        }
        
        return false;
    }
    
    // JSON响应
    private function jsonResponse($data) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
