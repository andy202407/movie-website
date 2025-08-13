<?php

require_once 'Database.php';

class VideoModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    

    
    public function getAllVideos() {
        $sql = "SELECT v.id, v.title, v.intro, v.cover_path, v.banner_path, v.file_path, 
                       v.region, v.year, v.category_id, v.categories, v.duration, v.status, v.is_recommended,
                       v.director, v.actor, v.rating
                FROM videos v 
                WHERE v.status = 'published' 
                ORDER BY v.created_at DESC";
        $videos = $this->db->fetchAll($sql);
        return $this->formatVideos($videos);
    }
    
    public function getVideoById($id) {
        $sql = "SELECT v.id, v.title, v.intro, v.cover_path, v.banner_path, v.file_path, 
                       v.region, v.year, v.category_id, v.categories, v.duration, v.status, v.is_recommended,
                       v.director, v.actor, v.rating
                FROM videos v 
                WHERE v.id = ? AND v.status = 'published'";
        $video = $this->db->fetchOne($sql, [$id]);
        return $video ? $this->formatVideo($video) : null;
    }
    
    public function getVideosByCategory($categoryId) {
        $sql = "SELECT v.id, v.title, v.intro, v.cover_path, v.banner_path, v.file_path, 
                       v.region, v.year, v.category_id, v.categories, v.duration, v.status, v.is_recommended,
                       v.director, v.actor, v.rating
                FROM videos v 
                WHERE v.status = 'published' 
                AND (
                    v.categories LIKE ? OR 
                    v.categories LIKE ? OR 
                    v.categories LIKE ? OR
                    v.categories LIKE ?
                ) 
                ORDER BY v.created_at DESC";
        
        // 更精确的匹配模式，避免误匹配
        $searchPattern1 = '%[' . $categoryId . ',%';      // [1,2,3
        $searchPattern2 = '%,' . $categoryId . ',%';      // ,1,2
        $searchPattern3 = '%,' . $categoryId . ']%';      // ,1]
        $searchPattern4 = '%[' . $categoryId . ']%';      // [1]
        
        $videos = $this->db->fetchAll($sql, [$searchPattern1, $searchPattern2, $searchPattern3, $searchPattern4]);
        return $this->formatVideos($videos);
    }
    
    public function getVideosByParentCategory($parentCategoryId) {
        // 首先获取该父分类下的所有子分类ID
        $sql = "SELECT id FROM categories WHERE parent_id = ?";
        $childCategories = $this->db->fetchAll($sql, [$parentCategoryId]);
        
        if (empty($childCategories)) {
            return [];
        }
        
        $childCategoryIds = array_column($childCategories, 'id');
        
        // 构建查询条件，查找包含任一子分类ID的影片
        $conditions = [];
        $params = [];
        
        foreach ($childCategoryIds as $catId) {
            $conditions[] = "(
                v.categories LIKE ? OR 
                v.categories LIKE ? OR 
                v.categories LIKE ? OR
                v.categories LIKE ?
            )";
            
            // 更精确的匹配模式
            $searchPattern1 = '%[' . $catId . ',%';      // [1,2,3
            $searchPattern2 = '%,' . $catId . ',%';      // ,1,2
            $searchPattern3 = '%,' . $catId . ']%';      // ,1]
            $searchPattern4 = '%[' . $catId . ']%';      // [1]
            
            $params = array_merge($params, [$searchPattern1, $searchPattern2, $searchPattern3, $searchPattern4]);
        }
        
        $sql = "SELECT v.id, v.title, v.intro, v.cover_path, v.banner_path, v.file_path, 
                       v.region, v.year, v.category_id, v.categories, v.duration, v.status, v.is_recommended,
                       v.director, v.actor, v.rating
                FROM videos v 
                WHERE v.status = 'published' 
                AND (" . implode(' OR ', $conditions) . ")
                ORDER BY v.created_at DESC";
        
        $videos = $this->db->fetchAll($sql, $params);
        return $this->formatVideos($videos);
    }
    
    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY sort_order ASC";
        $categories = $this->db->fetchAll($sql);
        
        // 直接返回分类数组
        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'id' => (int)$category['id'],
                'name' => $category['name'],
                'slug' => strtolower(str_replace(['片', '剧'], ['', ''], $category['name']))
            ];
        }
        return $result;
    }
    
    public function getParentCategories() {
        $sql = "SELECT * FROM categories WHERE parent_id IS NULL ORDER BY sort_order ASC";
        $categories = $this->db->fetchAll($sql);
        
        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'id' => (int)$category['id'],
                'name' => $category['name'],
                'slug' => strtolower(str_replace(['片', '剧'], ['', ''], $category['name']))
            ];
        }
        return $result;
    }
    
    public function isParentCategory($categoryId) {
        $sql = "SELECT parent_id FROM categories WHERE id = ?";
        $result = $this->db->fetchOne($sql, [$categoryId]);
        return $result && $result['parent_id'] === null;
    }
    
    public function getChildCategories($parentCategoryId) {
        $sql = "SELECT * FROM categories WHERE parent_id = ? ORDER BY sort_order ASC";
        $categories = $this->db->fetchAll($sql, [$parentCategoryId]);
        
        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'id' => (int)$category['id'],
                'name' => $category['name'],
                'slug' => strtolower(str_replace(['片', '剧'], ['', ''], $category['name']))
            ];
        }
        return $result;
    }
    
    public function getLatestVideosByParentCategory($parentCategoryId, $limit = 10) {
        // 首先获取该父分类下的所有子分类ID
        $sql = "SELECT id FROM categories WHERE parent_id = ?";
        $childCategories = $this->db->fetchAll($sql, [$parentCategoryId]);
        
        if (empty($childCategories)) {
            return [];
        }
        
        $childCategoryIds = array_column($childCategories, 'id');
        
        // 构建查询条件，查找包含任一子分类ID的影片
        $conditions = [];
        $params = [];
        
        foreach ($childCategoryIds as $catId) {
            $conditions[] = "(
                v.categories LIKE ? OR 
                v.categories LIKE ? OR 
                v.categories LIKE ? OR
                v.categories LIKE ?
            )";
            
            // 更精确的匹配模式
            $searchPattern1 = '%[' . $catId . ',%';      // [1,2,3
            $searchPattern2 = '%,' . $catId . ',%';      // ,1,2
            $searchPattern3 = '%,' . $catId . ']%';      // ,1]
            $searchPattern4 = '%[' . $catId . ']%';      // [1]
            
            $params = array_merge($params, [$searchPattern1, $searchPattern2, $searchPattern3, $searchPattern4]);
        }
        
        $sql = "SELECT v.id, v.title, v.intro, v.cover_path, v.banner_path, v.file_path, 
                       v.region, v.year, v.category_id, v.categories, v.duration, v.status, v.is_recommended,
                       v.director, v.actor, v.rating
                FROM videos v 
                WHERE v.status = 'published' 
                AND (" . implode(' OR ', $conditions) . ")
                ORDER BY v.created_at DESC 
                LIMIT " . intval($limit);
        
        $videos = $this->db->fetchAll($sql, $params);
        return $this->formatVideos($videos);
    }
    
    public function getCategoryById($id) {
        $sql = "SELECT * FROM categories WHERE id = ?";
        $category = $this->db->fetchOne($sql, [$id]);
        
        if ($category) {
            return [
                'id' => (int)$category['id'],
                'name' => $category['name'],
                'slug' => strtolower(str_replace(['片', '剧'], ['', ''], $category['name']))
            ];
        }
        return null;
    }
    
    public function getRecommendedVideos($limit = 4) {
        $sql = "SELECT v.id, v.title, v.intro, v.cover_path, v.banner_path, v.file_path, 
                       v.region, v.year, v.category_id, v.categories, v.duration, v.status, v.is_recommended,
                       v.director, v.actor, v.rating
                FROM videos v 
                WHERE v.status = 'published' AND v.is_recommended = 1 
                ORDER BY v.created_at DESC 
                LIMIT ?";
        $videos = $this->db->fetchAll($sql, [$limit]);
        
        // 如果推荐的不够，补充普通视频
        if (count($videos) < $limit) {
            $remaining = $limit - count($videos);
            $videoIds = array_column($videos, 'id');
            $placeholders = $videoIds ? str_repeat('?,', count($videoIds) - 1) . '?' : '';
            $notInClause = $videoIds ? "AND v.id NOT IN ($placeholders)" : '';
            
            $sql2 = "SELECT v.id, v.title, v.intro, v.cover_path, v.banner_path, v.file_path, 
                            v.region, v.year, v.category_id, v.categories, v.duration, v.status, v.is_recommended,
                            v.director, v.actor, v.rating
                     FROM videos v 
                     WHERE v.status = 'published' 
                     $notInClause
                     ORDER BY v.created_at DESC 
                     LIMIT ?";
            $params = $videoIds ? array_merge($videoIds, [$remaining]) : [$remaining];
            $moreVideos = $this->db->fetchAll($sql2, $params);
            $videos = array_merge($videos, $moreVideos);
        }
        
        return $this->formatVideos($videos);
    }
    
    public function searchVideos($keyword) {
        $sql = "SELECT v.id, v.title, v.intro, v.cover_path, v.banner_path, v.file_path, 
                       v.region, v.year, v.category_id, v.categories, v.duration, v.status, v.is_recommended,
                       v.director, v.actor, v.rating
                FROM videos v 
                WHERE v.status = 'published' 
                AND (v.title LIKE ? OR v.intro LIKE ?) 
                ORDER BY v.created_at DESC";
        $searchTerm = "%$keyword%";
        $videos = $this->db->fetchAll($sql, [$searchTerm, $searchTerm]);
        return $this->formatVideos($videos);
    }
    
    public function getBannerVideos($limit = 3) {
        // 严格按照 is_recommended = 1 获取轮播图影片
        $sql = "SELECT v.id, v.title, v.intro, v.cover_path, v.banner_path, v.file_path, 
                       v.region, v.year, v.category_id, v.categories, v.duration, v.status, v.is_recommended,
                       v.director, v.actor, v.rating
                FROM videos v 
                WHERE v.status = 'published' 
                AND v.is_recommended = 1 
                ORDER BY v.created_at DESC 
                LIMIT " . intval($limit);
        $videos = $this->db->fetchAll($sql);
        
        // 只返回推荐的影片，不做任何补充
        // 如果推荐影片数量不足，轮播图就显示实际的数量
        return $this->formatVideos($videos);
    }
    
    public function getLatestVideos($limit = 5) {
        $sql = "SELECT v.id, v.title, v.intro, v.cover_path, v.banner_path, v.file_path, 
                       v.region, v.year, v.category_id, v.categories, v.duration, v.status, v.is_recommended,
                       v.director, v.actor, v.rating
                FROM videos v 
                WHERE v.status = 'published' 
                ORDER BY v.created_at DESC 
                LIMIT ?";
        $videos = $this->db->fetchAll($sql, [$limit]);
        return $this->formatVideos($videos);
    }
    
    private function formatVideo($video) {
        // 处理封面图片路径
        $poster = 'https://via.placeholder.com/300x400/333/fff?text=No+Image';
        if (!empty($video['cover_path'])) {
            // 数据库存储的是 /public/uploads/covers/xxx 格式
            // 需要转换为 /uploads/covers/xxx 供前端访问
            $poster = str_replace('/public/', '/', $video['cover_path']);
            
            // 如果路径仍然以/public开头，再次处理
            if (strpos($poster, '/public/') === 0) {
                $poster = str_replace('/public/', '/', $poster);
            }
            
            // 确保路径以/开头
            if (strpos($poster, '/') !== 0) {
                $poster = '/' . $poster;
            }
        }
        
        // 处理banner图片路径
        $banner = '';
        if (!empty($video['banner_path'])) {
            $banner = str_replace('/public/', '/', $video['banner_path']);
            
            // 如果路径仍然以/public开头，再次处理
            if (strpos($banner, '/public/') === 0) {
                $banner = str_replace('/public/', '/', $banner);
            }
            
            // 确保路径以/开头
            if (strpos($banner, '/') !== 0) {
                $banner = '/' . $banner;
            }
        }
        
        // 处理视频文件路径
        $videoUrl = '';
        if (!empty($video['file_path'])) {
            // 如果是完整的URL，直接使用
            if (strpos($video['file_path'], 'http://') === 0 || strpos($video['file_path'], 'https://') === 0) {
                $videoUrl = $video['file_path'];
            } else {
                // 如果是相对路径，转换为正确的URL
                $videoUrl = str_replace('/public/', '/', $video['file_path']);
                // 确保路径以/开头
                if (strpos($videoUrl, '/') !== 0) {
                    $videoUrl = '/' . $videoUrl;
                }
            }
        }
        
        // 处理多个分类
        $categoryNames = [];
        $categoryIds = [];
        if (!empty($video['categories'])) {
            // 处理categories字段，可能是JSON格式或字符串格式
            if (is_string($video['categories'])) {
                // 去除可能的外层双引号
                $cleanCategories = trim($video['categories'], '"');
                
                // 尝试解析为JSON
                $decoded = json_decode($cleanCategories, true);
                if (is_array($decoded)) {
                    $categoryIds = $decoded;
                } else {
                    // 如果不是有效的JSON，尝试解析为数组字符串
                    $str = trim($cleanCategories, '[]');
                    if (!empty($str)) {
                        $categoryIds = array_map('intval', explode(',', $str));
                    }
                }
            } elseif (is_array($video['categories'])) {
                $categoryIds = $video['categories'];
            }
            
            if (is_array($categoryIds)) {
                foreach ($categoryIds as $catId) {
                    // 过滤掉无效的分类ID
                    if ($catId > 0) {
                        $category = $this->getCategoryById($catId);
                        if ($category) {
                            $categoryNames[] = $category['name'];
                        }
                    }
                }
            }
        }
        
        // 如果没有分类信息，使用旧的category_id作为备用
        if (empty($categoryNames) && !empty($video['category_id'])) {
            $category = $this->getCategoryById($video['category_id']);
            if ($category) {
                $categoryNames[] = $category['name'];
                $categoryIds = [$video['category_id']];
            }
        }
        
        return [
            'id' => $video['id'],
            'title' => $video['title'],
            'category_ids' => $categoryIds,
            'category_names' => $categoryNames,
            'category_name' => implode(' / ', $categoryNames), // 兼容旧代码
            'poster' => $poster,
            'banner' => $banner,
            'video_url' => $videoUrl,
            'rating' => $video['rating'] ?: '暂无评分',
            'year' => $video['year'] ?: '未知',
            'description' => $video['intro'] ?: '暂无简介',
            'duration' => $video['duration'] ? $video['duration'] . '分钟' : '未知时长',
            'director' => $video['director'] ?: '暂无导演信息',
            'actor' => $video['actor'] ?: '暂无演员信息',
            'status' => $video['status'] === 'published' ? '已完结' : '更新中',
            'is_recommended' => $video['is_recommended'] ?? 0,
            'region' => $video['region'] ?: '未知地区'
        ];
    }
    
    private function formatVideos($videos) {
        $result = [];
        foreach ($videos as $video) {
            $result[] = $this->formatVideo($video);
        }
        return $result;
    }
}