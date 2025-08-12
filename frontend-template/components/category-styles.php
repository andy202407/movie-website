<?php
/**
 * 根据分类名称返回对应的CSS类名
 * @param string $categoryName 分类名称
 * @return string CSS类名
 */
function getCategoryClass($categoryName) {
    // 分类名称到CSS类名的映射
    $categoryMap = [
        '动作片' => 'action',
        '科幻片' => 'scifi',
        '喜剧片' => 'comedy',
        '恐怖片' => 'horror',
        '爱情片' => 'romance',
        '剧情片' => 'drama',
        '战争片' => 'war',
        '悬疑片' => 'mystery',
        '惊悚片' => 'thriller',
        '动画片' => 'animation',
        '纪录片' => 'documentary',
        '冒险片' => 'adventure',
        '奇幻片' => 'fantasy',
        '历史片' => 'history',
        '音乐片' => 'music',
        '西部片' => 'western',
        '犯罪片' => 'crime',
        '家庭片' => 'family',
        '传记片' => 'biography',
        '体育片' => 'sports'
    ];
    
    // 如果找到匹配的分类，返回对应的CSS类名
    if (isset($categoryMap[$categoryName])) {
        return $categoryMap[$categoryName];
    }
    
    // 如果没有找到匹配的分类，返回默认类名
    return 'default';
}
?>
