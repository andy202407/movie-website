<?php
/**
 * 访客统计配置文件
 * 用于配置访客统计的排除规则
 */

// 被排除的域名列表（从数据库读取，不记录这些域名的访问）
// 注意：这里使用精确匹配，不会影响其他子域名
$EXCLUDED_DOMAINS = getIgnoredDomainsFromDB();

/**
 * 从数据库获取忽略统计的域名列表（带缓存）
 */
function getIgnoredDomainsFromDB() {
    static $cachedDomains = null;
    static $cacheTime = 0;
    
    // 缓存5分钟
    $cacheExpiry = 300;
    
    // 如果缓存存在且未过期，直接返回
    if ($cachedDomains !== null && (time() - $cacheTime) < $cacheExpiry) {
        return $cachedDomains;
    }
    
    try {
        $db = Database::getInstance();
        $domains = $db->fetchAll("SELECT domain FROM ignore_domains WHERE status = 'active'");
        
        $ignoredDomains = [];
        foreach ($domains as $row) {
            $ignoredDomains[] = $row['domain'];
        }
        
        // 更新缓存
        $cachedDomains = $ignoredDomains;
        $cacheTime = time();
        
        return $ignoredDomains;
    } catch (Exception $e) {
        // 如果数据库查询失败，返回默认列表
        error_log("获取忽略域名失败: " . $e->getMessage());
        return ['m.ql83.com']; // 默认排除
    }
}

// 被排除的IP地址段（不记录这些IP段的访问）
$EXCLUDED_IP_RANGES = [
    // 可以在这里添加需要排除的IP段
    // 例如: '192.168.1.0/24'
];

// 被排除的用户代理（不记录这些User-Agent的访问）
$EXCLUDED_USER_AGENTS = [
    'bot',
    'crawler',
    'spider',
    'scraper',
    'curl',
    'wget',
    'python',
    'java',
    'perl',
    'ruby',
    'php'
];

// 是否启用域名过滤
$ENABLE_DOMAIN_FILTER = true;

// 是否启用IP过滤
$ENABLE_IP_FILTER = false;

// 是否启用User-Agent过滤
$ENABLE_USER_AGENT_FILTER = true; 