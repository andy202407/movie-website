<?php
/**
 * 访客统计配置文件
 * 用于配置访客统计的排除规则
 */

// 被排除的域名列表（不记录这些域名的访问）
// 注意：这里使用精确匹配，不会影响其他子域名
$EXCLUDED_DOMAINS = [
    'm.ql82.com',        // 只排除 m.ql82.com
    // 如果需要排除其他特定域名，请在这里添加
    // 例如: 'admin.ql82.com'
    // 例如: 'test.ql82.com'
];

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