<?php

class CacheManager {
    private $cacheDir;
    private $defaultTTL;
    private $cacheEnabled;
    
    public function __construct($cacheDir = 'cache') {
        $this->cacheDir = rtrim($cacheDir, '/') . '/';
        
        // 从数据库获取缓存设置
        $this->loadCacheSettings();
        
        if (!$this->ensureCacheDirectory()) {
            error_log("缓存系统初始化失败，目录创建不成功");
        }
    }
    
    /**
     * 从数据库加载缓存设置
     */
    private function loadCacheSettings() {
        try {
            $db = Database::getInstance();
            
            // 获取缓存启用状态
            $enabled = $db->fetchOne("SELECT value FROM cache_settings WHERE `key` = 'cache_enabled'");
            $this->cacheEnabled = $enabled ? (bool)$enabled['value'] : true;
            
            // 获取缓存过期时间
            $expiration = $db->fetchOne("SELECT value FROM cache_settings WHERE `key` = 'cache_expiration'");
            $this->defaultTTL = $expiration ? (int)$expiration['value'] : 86400; // 默认24小时
            
        } catch (Exception $e) {
            error_log("加载缓存设置失败: " . $e->getMessage());
            // 使用默认值
            $this->cacheEnabled = true;
            $this->defaultTTL = 86400;
        }
    }
    
    /**
     * 确保缓存目录存在
     */
    private function ensureCacheDirectory() {
        try {
            if (!is_dir($this->cacheDir)) {
                if (!mkdir($this->cacheDir, 0755, true)) {
                    error_log("无法创建缓存目录: " . $this->cacheDir);
                    return false;
                }
            }
            
            // 创建页面缓存目录
            $pagesDir = $this->cacheDir . 'pages/';
            if (!is_dir($pagesDir)) {
                if (!mkdir($pagesDir, 0755, true)) {
                    error_log("无法创建页面缓存目录: " . $pagesDir);
                    return false;
                }
            }
            
            return true;
        } catch (Exception $e) {
            error_log("创建缓存目录时出错: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * 生成缓存键和路径
     */
    private function generateCachePath($url, $params = []) {
        // 解析URL路径
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'] ?? '/';
        
        // 处理路径分层
        $pathParts = array_filter(explode('/', $path));
        if (empty($pathParts)) {
            $pathParts = ['home'];
        }
        
        // 移除.php扩展名，构建目录路径
        $cleanPathParts = [];
        foreach ($pathParts as $part) {
            $cleanPart = str_replace('.php', '', $part);
            if (!empty($cleanPart)) {
                $cleanPathParts[] = $cleanPart;
            }
        }
        
        $dirPath = implode('/', $cleanPathParts);
        
        // 生成文件名（包含参数）
        $queryString = '';
        if (!empty($params)) {
            $queryString = '?' . http_build_query($params);
        }
        
        $fileName = md5($path . $queryString) . '.html';
        
        return [
            'dir' => $dirPath,
            'file' => $fileName,
            'fullPath' => $this->cacheDir . 'pages/' . $dirPath . '/' . $fileName
        ];
    }
    
    /**
     * 获取缓存内容（自动检查过期）
     */
    public function get($url, $params = []) {
        $cachePath = $this->generateCachePath($url, $params);
        $cacheFile = $cachePath['fullPath'];
        
        if (file_exists($cacheFile)) {
            $fileTime = filemtime($cacheFile);
            $currentTime = time();
            
            // 检查是否过期（24小时）
            if (($currentTime - $fileTime) < $this->defaultTTL) {
                // 添加缓存命中标记
                $content = file_get_contents($cacheFile);
                $content = "<!-- CACHE HIT: " . date('Y-m-d H:i:s') . " -->\n" . $content;
                return $content;
            } else {
                // 自动删除过期缓存
                unlink($cacheFile);
            }
        }
        
        return false;
    }
    
    /**
     * 设置缓存内容
     */
    public function set($url, $content, $params = []) {
        try {
            $cachePath = $this->generateCachePath($url, $params);
            $cacheFile = $cachePath['fullPath'];
            
            // 确保目录存在
            $cacheDir = dirname($cacheFile);
            if (!is_dir($cacheDir)) {
                if (!mkdir($cacheDir, 0755, true)) {
                    error_log("无法创建缓存目录: " . $cacheDir);
                    return false;
                }
            }
            
            // 添加缓存生成标记
            $cacheContent = "<!-- CACHE GENERATED: " . date('Y-m-d H:i:s') . " -->\n" . $content;
            return file_put_contents($cacheFile, $cacheContent);
        } catch (Exception $e) {
            error_log("写入缓存文件失败: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * 检查是否应该使用缓存
     */
    public function shouldCache() {
        // 首先检查数据库中的缓存启用状态
        if (!$this->cacheEnabled) {
            return false;
        }
        
        // 添加调试日志
        $debug = [
            'method' => $_SERVER['REQUEST_METHOD'],
            'uri' => $_SERVER['REQUEST_URI'],
            'session_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'none',
            'cache_enabled' => $this->cacheEnabled,
            'cache_ttl' => $this->defaultTTL,
            'should_cache' => true
        ];
        
        // 不缓存POST请求
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $debug['should_cache'] = false;
            $debug['reason'] = 'POST request';
            error_log("缓存调试: " . json_encode($debug));
            return false;
        }
        
        // 不缓存用户登录后的页面
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
            $debug['should_cache'] = false;
            $debug['reason'] = 'User logged in';
            error_log("缓存调试: " . json_encode($debug));
            return false;
        }
        
        // 不缓存API请求
        if (strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
            $debug['should_cache'] = false;
            $debug['reason'] = 'API request';
            error_log("缓存调试: " . json_encode($debug));
            return false;
        }
        
        error_log("缓存调试: " . json_encode($debug));
        return true;
    }
    
    /**
     * 获取缓存统计信息
     */
    public function getStats() {
        $files = glob($this->cacheDir . 'pages/*.html');
        $totalSize = 0;
        $expiredCount = 0;
        $currentTime = time();
        
        foreach ($files as $file) {
            $totalSize += filesize($file);
            if (($currentTime - filemtime($file)) >= $this->defaultTTL) {
                $expiredCount++;
            }
        }
        
        return [
            'total_files' => count($files),
            'total_size' => $this->formatBytes($totalSize),
            'expired_files' => $expiredCount,
            'cache_dir' => $this->cacheDir,
            'cache_enabled' => $this->cacheEnabled,
            'cache_ttl' => $this->defaultTTL,
            'cache_ttl_hours' => round($this->defaultTTL / 3600, 1)
        ];
    }
    
    /**
     * 格式化字节数
     */
    private function formatBytes($bytes) {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
