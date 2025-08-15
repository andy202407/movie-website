<?php

require_once dirname(__DIR__) . '/Database.php';

class UserModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 用户注册
    public function register($username, $password) {
        try {
            // 检查用户名是否已存在
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => '用户名已存在'];
            }
            
            // 密码加密
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // 插入新用户
            $stmt = $this->db->prepare("INSERT INTO users (username, password, email, created_at) VALUES (?, ?, NULL, NOW())");
            $result = $stmt->execute([$username, $hashedPassword]);
            
            if ($result) {
                return ['success' => true, 'message' => '注册成功', 'user_id' => $this->db->lastInsertId()];
            } else {
                return ['success' => false, 'message' => '注册失败'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => '系统错误：' . $e->getMessage()];
        }
    }
    
    // 用户登录
    public function login($username, $password) {
        try {
            $stmt = $this->db->prepare("SELECT id, username, password, email, created_at FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                // 更新最后登录时间
                $stmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);
                
                return ['success' => true, 'message' => '登录成功', 'user' => $user];
            } else {
                return ['success' => false, 'message' => '用户名或密码错误'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => '系统错误：' . $e->getMessage()];
        }
    }
    
    // 根据ID获取用户信息
    public function getUserById($userId) {
        try {
            $stmt = $this->db->prepare("SELECT id, username, email, created_at, last_login FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }
    
    // 添加收藏
    public function addFavorite($userId, $videoId, $type = 1) {
        try {
            // 检查是否已收藏
            $stmt = $this->db->prepare("SELECT id FROM user_favorites WHERE user_id = ? AND video_id = ?");
            $stmt->execute([$userId, $videoId]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => '已经收藏过了'];
            }
            
            // 添加收藏
            $stmt = $this->db->prepare("INSERT INTO user_favorites (user_id, video_id, type, created_at) VALUES (?, ?, ?, NOW())");
            $result = $stmt->execute([$userId, $videoId, $type]);
            
            if ($result) {
                return ['success' => true, 'message' => '收藏成功'];
            } else {
                return ['success' => false, 'message' => '收藏失败'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => '系统错误：' . $e->getMessage()];
        }
    }
    
    // 取消收藏
    public function removeFavorite($userId, $videoId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM user_favorites WHERE user_id = ? AND video_id = ?");
            $result = $stmt->execute([$userId, $videoId]);
            
            if ($result) {
                return ['success' => true, 'message' => '取消收藏成功'];
            } else {
                return ['success' => false, 'message' => '取消收藏失败'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => '系统错误：' . $e->getMessage()];
        }
    }
    
    // 检查是否已收藏
    public function isFavorited($userId, $videoId) {
        try {
            $stmt = $this->db->prepare("SELECT id FROM user_favorites WHERE user_id = ? AND video_id = ?");
            $stmt->execute([$userId, $videoId]);
            return $stmt->fetch() ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    // 获取用户收藏列表
    public function getFavorites($userId, $page = 1, $limit = 20) {
        try {
            $offset = ($page - 1) * $limit;
            
            // 使用LIMIT offset, count语法避免PDO参数绑定问题
            $stmt = $this->db->prepare("
                SELECT f.*, v.title, v.cover_path as poster, v.status, v.actor, v.director, v.year, v.region 
                FROM user_favorites f 
                LEFT JOIN videos v ON f.video_id = v.id 
                WHERE f.user_id = ? 
                ORDER BY f.created_at DESC 
                LIMIT {$offset}, {$limit}
            ");
            $stmt->execute([$userId]);
            $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // 处理图片路径：将 /public/uploads/ 转换为 /uploads/
            foreach ($favorites as &$item) {
                if (!empty($item['poster'])) {
                    $item['poster'] = str_replace('/public/', '/', $item['poster']);
                    // 确保路径以/开头
                    if (strpos($item['poster'], '/') !== 0) {
                        $item['poster'] = '/' . $item['poster'];
                    }
                }
            }
            
            // 获取总数
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM user_favorites WHERE user_id = ?");
            $stmt->execute([$userId]);
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            return [
                'success' => true,
                'favorites' => $favorites,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'pages' => ceil($total / $limit)
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => '系统错误：' . $e->getMessage()];
        }
    }
    
    // 获取用户观看历史
    public function getWatchHistory($userId, $page = 1, $limit = 20) {
        try {
            $offset = ($page - 1) * $limit;
            
            // 使用LIMIT offset, count语法避免PDO参数绑定问题
            $stmt = $this->db->prepare("
                SELECT h.*, v.title, v.cover_path as poster, v.status 
                FROM user_watch_history h 
                LEFT JOIN videos v ON h.video_id = v.id 
                WHERE h.user_id = ? 
                ORDER BY h.last_watched DESC 
                LIMIT {$offset}, {$limit}
            ");
            $stmt->execute([$userId]);
            $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // 处理图片路径：将 /public/uploads/ 转换为 /uploads/
            foreach ($history as &$item) {
                if (!empty($item['poster'])) {
                    $item['poster'] = str_replace('/public/', '/', $item['poster']);
                    // 确保路径以/开头
                    if (strpos($item['poster'], '/') !== 0) {
                        $item['poster'] = '/' . $item['poster'];
                    }
                }
            }
            
            // 获取总数
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM user_watch_history WHERE user_id = ?");
            $stmt->execute([$userId]);
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            return [
                'success' => true,
                'history' => $history,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'pages' => ceil($total / $limit)
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => '系统错误：' . $e->getMessage()];
        }
    }
    
    // 更新观看历史
    public function updateWatchHistory($userId, $videoId, $episode = 1, $progress = 0) {
        try {
            // 检查是否已有记录
            $stmt = $this->db->prepare("SELECT id FROM user_watch_history WHERE user_id = ? AND video_id = ?");
            $stmt->execute([$userId, $videoId]);
            $existing = $stmt->fetch();
            
            if ($existing) {
                // 更新现有记录
                $stmt = $this->db->prepare("
                    UPDATE user_watch_history 
                    SET episode = ?, progress = ?, last_watched = NOW() 
                    WHERE user_id = ? AND video_id = ?
                ");
                $stmt->execute([$episode, $progress, $userId, $videoId]);
            } else {
                // 创建新记录
                $stmt = $this->db->prepare("
                    INSERT INTO user_watch_history (user_id, video_id, episode, progress, last_watched) 
                    VALUES (?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$userId, $videoId, $episode, $progress]);
            }
            
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => '系统错误：' . $e->getMessage()];
        }
    }

    // 移除单条观看历史
    public function removeWatchHistory($userId, $videoId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM user_watch_history WHERE user_id = ? AND video_id = ?");
            $result = $stmt->execute([$userId, $videoId]);
            
            if ($result) {
                return ['success' => true, 'message' => '观看记录已删除'];
            } else {
                return ['success' => false, 'message' => '删除失败'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => '系统错误：' . $e->getMessage()];
        }
    }

    // 清除所有观看历史
    public function clearWatchHistory($userId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM user_watch_history WHERE user_id = ?");
            $result = $stmt->execute([$userId]);
            
            if ($result) {
                return ['success' => true, 'message' => '所有观看记录已清除'];
            } else {
                return ['success' => false, 'message' => '清除失败'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => '系统错误：' . $e->getMessage()];
        }
    }
}
