-- 删除弹幕功能相关表
-- 执行此脚本将永久删除弹幕数据

-- 删除弹幕表
DROP TABLE IF EXISTS `danmaku`;

-- 删除弹幕相关的缓存文件（如果有的话）
-- 注意：这需要在PHP层面执行，这里只是提醒

-- 验证弹幕表已被删除
SHOW TABLES LIKE 'danmaku';
