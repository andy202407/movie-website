-- 访客记录数据库迁移文件

-- 网站访问记录表
CREATE TABLE IF NOT EXISTS `site_visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL COMMENT '访问者IP地址',
  `visit_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '访问时间',
  PRIMARY KEY (`id`),
  KEY `idx_ip_date` (`ip`, `visit_time`),
  KEY `idx_visit_time` (`visit_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='网站访问记录表';

-- 影片访问记录表
CREATE TABLE IF NOT EXISTS `video_visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL COMMENT '访问者IP地址',
  `video_id` int(11) NOT NULL COMMENT '影片ID',
  `visit_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '访问时间',
  PRIMARY KEY (`id`),
  KEY `idx_ip_video_date` (`ip`, `video_id`, `visit_time`),
  KEY `idx_video_id` (`video_id`),
  KEY `idx_visit_time` (`visit_time`),
  CONSTRAINT `fk_video_visits_video_id` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='影片访问记录表';

-- 添加索引优化查询性能
CREATE INDEX IF NOT EXISTS `idx_site_visits_ip_date` ON `site_visits` (`ip`, DATE(`visit_time`));
CREATE INDEX IF NOT EXISTS `idx_video_visits_ip_video_date` ON `video_visits` (`ip`, `video_id`, DATE(`visit_time`)); 