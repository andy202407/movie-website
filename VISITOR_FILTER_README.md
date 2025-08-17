# 访客统计过滤功能说明

## 功能概述

本系统已实现访客统计过滤功能，可以自动排除来自指定域名的访问，确保统计数据的准确性。

## 主要特性

### 1. 精确域名过滤
- 自动排除来自 `m.ql83.com` 的访问
- **重要**：使用精确匹配，不会影响其他子域名（如 `aa.ql82.com`、`bb.ql82.com` 等）
- 通过检查 HTTP 请求头中的 `Referer`、`Host` 和 `Origin` 来识别来源
- 支持配置多个排除域名

### 2. 机器人过滤
- 自动排除常见的爬虫、机器人访问
- 支持配置自定义的 User-Agent 过滤规则

### 3. IP 过滤（可选）
- 支持排除特定 IP 地址段的访问
- 使用 CIDR 格式配置 IP 范围

## 配置文件

### `visitor_config.php`
主要的配置文件，包含所有过滤规则：

```php
// 被排除的域名列表（精确匹配）
$EXCLUDED_DOMAINS = [
    'm.ql83.com',        // 只排除 m.ql83.com
    // 可以添加更多特定域名
    // 例如: 'admin.ql82.com'
    // 例如: 'test.ql82.com'
    // 注意：不会影响其他子域名
];

// 是否启用各种过滤
$ENABLE_DOMAIN_FILTER = true;      // 域名过滤
$ENABLE_IP_FILTER = false;         // IP过滤
$ENABLE_USER_AGENT_FILTER = true;  // User-Agent过滤
```

## 使用方法

### 1. 添加新的排除域名
在 `visitor_config.php` 文件中的 `$EXCLUDED_DOMAINS` 数组添加新域名：

```php
$EXCLUDED_DOMAINS = [
    'm.ql83.com',        // 排除 m.ql83.com
    'admin.ql82.com',    // 排除 admin.ql82.com
    'test.ql82.com',     // 排除 test.ql82.com
    // 注意：这些是精确匹配，不会影响其他子域名
];
```

### 2. 启用/禁用特定过滤
修改配置文件中的开关：

```php
$ENABLE_DOMAIN_FILTER = true;      // 启用域名过滤
$ENABLE_IP_FILTER = true;          // 启用IP过滤
$ENABLE_USER_AGENT_FILTER = false; // 禁用User-Agent过滤
```

### 3. 配置IP过滤规则
在 `$EXCLUDED_IP_RANGES` 数组中添加IP规则：

```php
$EXCLUDED_IP_RANGES = [
    '192.168.1.0/24',    // 排除整个192.168.1.x网段
    '10.0.0.1',          // 排除单个IP
];
```

## 域名匹配规则

### 精确匹配
- `m.ql83.com` → 只排除 `m.ql83.com`
- `admin.ql82.com` → 只排除 `admin.ql82.com`
- `test.ql82.com` → 只排除 `test.ql82.com`

### 不会影响的域名
- `aa.ql82.com` → ✅ 正常统计
- `bb.ql82.com` → ✅ 正常统计
- `www.ql82.com` → ✅ 正常统计
- `api.ql82.com` → ✅ 正常统计

## 测试功能

如果需要测试过滤功能，可以创建测试脚本：

```php
<?php
require_once 'visitor_helper.php';

// 模拟来自 m.ql83.com 的访问
$_SERVER['HTTP_REFERER'] = 'https://m.ql83.com/some-page';
$shouldExclude = shouldExcludeVisit();
echo "m.ql83.com 访问: " . ($shouldExclude ? "已排除" : "未排除") . "\n";

// 模拟来自 aa.ql82.com 的访问
$_SERVER['HTTP_REFERER'] = 'https://aa.ql82.com/another-page';
$shouldExclude = shouldExcludeVisit();
echo "aa.ql82.com 访问: " . ($shouldExclude ? "已排除" : "未排除") . "\n";
?>
```

## 工作原理

1. **域名检查**：检查 HTTP 请求头中的来源信息，使用精确匹配
2. **IP 检查**：验证访问者 IP 是否在排除范围内
3. **User-Agent 检查**：识别机器人访问
4. **自动过滤**：符合条件的访问不会被记录到数据库中

## 注意事项

1. **精确匹配**：域名过滤使用精确匹配，不会影响其他子域名
2. **配置生效**：修改配置文件后，需要重新加载页面才能生效
3. **域名过滤**：主要依赖 HTTP 请求头，某些情况下可能不够准确
4. **定期检查**：建议定期检查过滤日志，确保规则按预期工作

## 故障排除

### 问题：访问仍然被记录
- 检查配置文件是否正确加载
- 确认过滤开关是否启用
- 查看错误日志是否有异常

### 问题：正常访问被误过滤
- 检查排除规则是否过于宽泛
- 临时禁用相关过滤功能进行测试
- 调整过滤规则的优先级

### 问题：子域名被误过滤
- 确认使用的是精确匹配而不是部分匹配
- 检查配置文件中是否有过于宽泛的域名规则

## 技术支持

如有问题，请检查：
1. PHP 错误日志
2. 配置文件语法
3. 数据库连接状态
4. 文件权限设置

## 更新日志

- **v1.1**: 修复域名匹配逻辑，改为精确匹配，不影响其他子域名
- **v1.0**: 初始版本，支持基本的域名、IP和User-Agent过滤 