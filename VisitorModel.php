<?php

class VisitorModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * 检查IP是否来自被排除的域名
     */
    private function isExcludedIP($ip) {
        // 这里可以添加更多的IP过滤逻辑
        // 比如检查IP是否属于特定的网络段
        return false; // 默认不过滤IP
    }
    
    /**
     * 记录网站访问
     */
    public function recordSiteVisit($ip) {
        // 检查IP是否应该被排除
        if ($this->isExcludedIP($ip)) {
            return false;
        }
        
        $today = date('Y-m-d');
        
        // 检查今天是否已经记录过这个IP
        $sql = "SELECT id FROM site_visits WHERE ip = ? AND DATE(visit_time) = ?";
        $existing = $this->db->fetchOne($sql, [$ip, $today]);
        
        if (!$existing) {
            // 今天还没记录过，插入新记录
            $sql = "INSERT INTO site_visits (ip, visit_time) VALUES (?, NOW())";
            return $this->db->query($sql, [$ip]);
        }
        
        return false; // 今天已经记录过了
    }
    
    /**
     * 记录影片访问
     */
    public function recordVideoVisit($ip, $videoId) {
        // 检查IP是否应该被排除
        if ($this->isExcludedIP($ip)) {
            return false;
        }
        
        $today = date('Y-m-d');
        
        // 检查今天是否已经记录过这个IP访问这个影片
        $sql = "SELECT id FROM video_visits WHERE ip = ? AND video_id = ? AND DATE(visit_time) = ?";
        $existing = $this->db->fetchOne($sql, [$ip, $videoId, $today]);
        
        if (!$existing) {
            // 今天还没记录过，插入新记录
            $sql = "INSERT INTO video_visits (ip, video_id, visit_time) VALUES (?, ?, NOW())";
            return $this->db->query($sql, [$ip, $videoId]);
        }
        
        return false; // 今天已经记录过了
    }
    
    /**
     * 获取网站访问统计
     */
    public function getSiteVisitStats($days = 30) {
        $sql = "SELECT DATE(visit_time) as date, COUNT(*) as count 
                FROM site_visits 
                WHERE visit_time >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY DATE(visit_time)
                ORDER BY date DESC";
        return $this->db->fetchAll($sql, [$days]);
    }
    
    /**
     * 获取影片访问统计
     */
    public function getVideoVisitStats($videoId, $days = 30) {
        $sql = "SELECT DATE(visit_time) as date, COUNT(*) as count 
                FROM video_visits 
                WHERE video_id = ? AND visit_time >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY DATE(visit_time)
                ORDER BY date DESC";
        return $this->db->fetchAll($sql, [$videoId, $days]);
    }
    
    /**
     * 获取热门影片（按访问量）
     */
    public function getPopularVideos($limit = 10, $days = 30) {
        $sql = "SELECT v.id, v.title, v.intro, v.cover_path, v.rating, v.director, COUNT(vv.id) as visit_count
                FROM videos v
                LEFT JOIN video_visits vv ON v.id = vv.video_id 
                AND vv.visit_time >= DATE_SUB(NOW(), INTERVAL ? DAY)
                WHERE v.status = 'published'
                GROUP BY v.id
                ORDER BY visit_count DESC, v.rating DESC
                LIMIT ?";
        return $this->db->fetchAll($sql, [$days, $limit]);
    }
    
    /**
     * 获取今日访问量
     */
    public function getTodayVisits() {
        $today = date('Y-m-d');
        $sql = "SELECT COUNT(*) as count FROM site_visits WHERE DATE(visit_time) = ?";
        $result = $this->db->fetchOne($sql, [$today]);
        return $result ? intval($result['count']) : 0;
    }
    
    /**
     * 获取总访问量
     */
    public function getTotalVisits() {
        $sql = "SELECT COUNT(*) as count FROM site_visits";
        $result = $this->db->fetchOne($sql);
        return $result ? intval($result['count']) : 0;
    }
} 