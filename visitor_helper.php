<?php
require_once 'Database.php';
require_once 'VisitorModel.php';

/**
 * 获取客户端IP
 */
function getClientIP() {
    $ip = '';
    
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    }
    
    // 如果是代理IP，取第一个
    if (strpos($ip, ',') !== false) {
        $ip = trim(explode(',', $ip)[0]);
    }
    
    return $ip;
}

/**
 * 记录网站访问
 */
function recordSiteVisit() {
    try {
        $visitorModel = new VisitorModel();
        $ip = getClientIP();
        
        if (!empty($ip) && $ip !== 'unknown') {
            $visitorModel->recordSiteVisit($ip);
        }
    } catch (Exception $e) {
        // 静默处理错误，不影响页面正常显示
        error_log('Visitor record error: ' . $e->getMessage());
    }
}

/**
 * 记录影片访问
 */
function recordVideoVisit($videoId) {
    try {
        $visitorModel = new VisitorModel();
        $ip = getClientIP();
        
        if (!empty($ip) && $ip !== 'unknown' && $videoId > 0) {
            $visitorModel->recordVideoVisit($ip, $videoId);
        }
    } catch (Exception $e) {
        // 静默处理错误，不影响页面正常显示
        error_log('Video visit record error: ' . $e->getMessage());
    }
}

/**
 * 自动记录访问（在页面加载时调用）
 */
function autoRecordVisit() {
    // 记录网站访问
    recordSiteVisit();
    
    // 如果是影片详情页或播放页，记录影片访问
    $page = $_GET['page'] ?? '';
    $videoId = intval($_GET['id'] ?? 0);
    
    if (($page === 'video' || $page === 'play') && $videoId > 0) {
        recordVideoVisit($videoId);
    }
} 