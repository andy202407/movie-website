-- 数据库结构文件
-- 确保videos表包含评分和导演字段

-- 检查并添加rating字段
ALTER TABLE `videos` ADD COLUMN IF NOT EXISTS `rating` DECIMAL(3,1) DEFAULT NULL COMMENT '影片评分(0-10)';

-- 检查并添加director字段  
ALTER TABLE `videos` ADD COLUMN IF NOT EXISTS `director` VARCHAR(255) DEFAULT NULL COMMENT '导演姓名';

-- 检查并添加actor字段
ALTER TABLE `videos` ADD COLUMN IF NOT EXISTS `actor` TEXT DEFAULT NULL COMMENT '演员信息';

-- 为rating字段添加索引以提高查询性能
CREATE INDEX IF NOT EXISTS `idx_videos_rating` ON `videos` (`rating`);

-- 为director字段添加索引以提高查询性能
CREATE INDEX IF NOT EXISTS `idx_videos_director` ON `videos` (`director`);

-- 更新现有记录的默认值（如果需要）
-- UPDATE `videos` SET `rating` = NULL WHERE `rating` = 0;
-- UPDATE `videos` SET `director` = NULL WHERE `director` = '';

-- 显示表结构
DESCRIBE `videos`;
