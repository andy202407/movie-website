<?php
require_once 'Database.php';
require_once 'VisitorModel.php';
require_once 'visitor_config.php';

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
 * 检查是否应该排除此访问
 */
function shouldExcludeVisit() {
    global $EXCLUDED_DOMAINS, $EXCLUDED_IP_RANGES, $EXCLUDED_USER_AGENTS;
    global $ENABLE_DOMAIN_FILTER, $ENABLE_IP_FILTER, $ENABLE_USER_AGENT_FILTER;
    
    // 检查域名过滤
    if ($ENABLE_DOMAIN_FILTER) {
        // 检查 Referer 头
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $refererHost = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
            if ($refererHost && in_array($refererHost, $EXCLUDED_DOMAINS)) {
                return true;
            }
        }
        
        // 检查 Host 头
        if (!empty($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
            // 使用精确匹配，而不是部分匹配
            if (in_array($host, $EXCLUDED_DOMAINS)) {
                return true;
            }
        }
        
        // 检查 Origin 头
        if (!empty($_SERVER['HTTP_ORIGIN'])) {
            $originHost = parse_url($_SERVER['HTTP_ORIGIN'], PHP_URL_HOST);
            if ($originHost && in_array($originHost, $EXCLUDED_DOMAINS)) {
                return true;
            }
        }
    }
    
    // 检查IP过滤
    if ($ENABLE_IP_FILTER && !empty($EXCLUDED_IP_RANGES)) {
        $ip = getClientIP();
        foreach ($EXCLUDED_IP_RANGES as $ipRange) {
            if (isIPInRange($ip, $ipRange)) {
                return true;
            }
        }
    }
    
    // 检查User-Agent过滤
    if ($ENABLE_USER_AGENT_FILTER) {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $userAgent = strtolower($userAgent);
        foreach ($EXCLUDED_USER_AGENTS as $pattern) {
            if (strpos($userAgent, $pattern) !== false) {
                return true;
            }
        }
    }
    
    return false;
}

/**
 * 检查IP是否在指定范围内
 */
function isIPInRange($ip, $range) {
    if (strpos($range, '/') === false) {
        return $ip === $range;
    }
    
    list($subnet, $mask) = explode('/', $range);
    $ip = ip2long($ip);
    $subnet = ip2long($subnet);
    $mask = -1 << (32 - $mask);
    $subnet &= $mask;
    
    return ($ip & $mask) == $subnet;
}

/**
 * 记录网站访问
 */
function recordSiteVisit() {
    try {
        // 检查是否应该排除此访问
        if (shouldExcludeVisit()) {
            return false;
        }
        
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
        // 检查是否应该排除此访问
        if (shouldExcludeVisit()) {
            return false;
        }
        
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