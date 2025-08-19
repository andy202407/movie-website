<?php
// 引入数据库连接
require_once '/www/wwwroot/m.ql52.com/Database.php';

// 获取当前视频ID
$videoId = $video['id'] ?? 0;

// 获取当前剧集序号（默认为1）
$currentEpisodeNumber = isset($_GET['episode']) ? intval($_GET['episode']) : 1;

// 初始化变量
$episodes = [];
$currentEpisode = null;
$hasEpisodes = false;
$playVideoUrl = '';
$playVideoTitle = '';
$playVideoPoster = '';

// 查询剧集信息
if ($videoId > 0) {
    try {
        $db = Database::getInstance();
        $episodes = $db->fetchAll("SELECT * FROM episodes WHERE video_id = ? ORDER BY episode_number ASC", [$videoId]);
        
        // 根据剧集序号查找对应剧集
        if (!empty($episodes)) {
            foreach ($episodes as $episode) {
                if ($episode['episode_number'] == $currentEpisodeNumber) {
                    $currentEpisode = $episode;
                    break;
                }
            }
            
            // 如果没找到指定序号，默认使用第一集
            if (!$currentEpisode) {
                $currentEpisode = $episodes[0];
                $currentEpisodeNumber = $currentEpisode['episode_number'];
            }
        }
    } catch (Exception $e) {
        error_log("查询剧集失败: " . $e->getMessage());
        $episodes = [];
    }
}

// 检查是否有剧集
$hasEpisodes = !empty($episodes);

// 确定要播放的视频URL
if ($hasEpisodes && $currentEpisode) {
    // 播放指定剧集
    $playVideoUrl = $currentEpisode['video_path'];
    $playVideoTitle = $video['title'] ?? ''; // 使用主视频的标题，不是剧集标题
    $playVideoPoster = $video['poster']; // 使用主视频的海报
} else {
    // 播放主视频
    $playVideoUrl = $video['video_url'] ?? '';
    $playVideoTitle = $video['title'] ?? '';
    $playVideoPoster = $video['poster'] ?? '';
}

// 构建完整的视频URL
if ($playVideoUrl && strpos($playVideoUrl, 'http') !== 0) {
    // 参考VideoModel.php中的路径处理逻辑
    // 将/public/替换为/
    $playVideoUrl = str_replace('/public/', '/', $playVideoUrl);
    
    // 确保路径以/开头
    if (strpos($playVideoUrl, '/') !== 0) {
        $playVideoUrl = '/' . $playVideoUrl;
    }
    
    // 不添加域名，让nginx处理路径映射
    // $playVideoUrl = 'https://m.ql52.com' . $playVideoUrl;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no,viewport-fit=cover">
    <meta name="theme-color" content="#1a1a1a" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <title>免费在线看 <?= $this->escape($video['title']) ?><?= ($hasEpisodes && $currentEpisode) ? ' 第' . ($currentEpisodeNumber ?? 1) . '集' : '' ?> - 动漫在线观看 - 星海影院</title>
<meta name="keywords" content="<?= $this->escape($video['title'] ?? '') ?>,<?= $this->escape($video['title'] ?? '') ?>在线观看,动漫,星海影院,冒险,动画,奇幻,<?= $this->escape($video['title'] ?? '') ?>免费在线观看,<?= $this->escape($video['title'] ?? '') ?>免费看,<?= $this->escape($video['title'] ?? '') ?>在线免费播放,<?= $this->escape($video['title'] ?? '') ?>高清在线观看,<?= $this->escape($video['title'] ?? '') ?>无广告播放,<?= $this->escape($video['title'] ?? '') ?>手机在线看,<?= $this->escape($video['title'] ?? '') ?>分集在线观看,<?= $this->escape($video['title'] ?? '') ?>全集免费看" />
<meta name="description" content="免费在线看 <?= $this->escape($video['title'] ?? '') ?> - 动漫在线观看 - 星海影院。<?= $this->escape($video['description'] ?? '') ?>。星海影院提供《<?= $this->escape($video['title'] ?? '') ?>》高清完整版免费在线观看，支持手机、电脑、平板多设备播放，无广告干扰。立即免费观看《<?= $this->escape($video['title'] ?? '') ?>》！" />
<meta itemProp="description" content="免费在线看 <?= $this->escape($video['title'] ?? '') ?> - 动漫在线观看 - 星海影院。<?= $this->escape($video['description'] ?? '') ?>。星海影院提供《<?= $this->escape($video['title'] ?? '') ?>》高清完整版免费在线观看，支持手机、电脑、平板多设备播放，无广告干扰。立即免费观看《<?= $this->escape($video['title'] ?? '') ?>》！" />
<meta property="og:title" content="<?= $this->escape($video['title']) ?>"/>
<meta property="og:description" content="<?= $this->escape($video['description']) ?>"/>
<meta property="og:type" content="video.episode"/>
<meta property="og:video:release_date" content="<?= $this->escape($video['year']) ?>-05-02" />
<meta property="og:video:tag" content="中国大陆"/>
<meta property="og:video:tag" content="冒险"/>
<meta property="og:video:tag" content="动画"/>
<meta property="og:video:tag" content="奇幻"/>
<meta property="og:site_name" content="星海影院"/>
<meta property="og:url" content="https://m.ql83.com /play/<?= $this->escape($video['title']) ?>-<?= $this->escape($video['year']) ?>-1-<?= $this->escape($video['id']) ?>.html"/>
<meta property="og:image" content="<?= $this->escape($video['poster']) ?>"/>
<meta property="og:image:alt" content="<?= $this->escape($video['title']) ?>"/>
<meta property="og:locale" content="zh_CN"/>
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:site" content="星海影院"/>
<meta property="twitter:title" content="<?= $this->escape($video['title']) ?>"/>
<meta name="twitter:description" content="<?= $this->escape($video['description']) ?>"/>
<meta property="twitter:image" content="<?= $this->escape($video['poster']) ?>"/>
<meta name="robots" content="index,follow" />
<link rel="canonical" href="https://m.ql83.com /play/<?= $this->escape($video['title']) ?>-<?= $this->escape($video['year']) ?>-1-<?= $this->escape($video['id']) ?>.html" />
<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "VideoObject",
            "name": "免费在线看 <?= $this->escape($video['title']) ?> - 动漫在线观看 - 星海影院",
            "description": "<?= $this->escape($video['description']) ?>",
            "thumbnailUrl": [
              "<?= $this->escape($video['poster']) ?>"
            ],
            "uploadDate": "<?= $this->escape($video['year']) ?>-08-04CST09:10:51.00028800", 
                        "expires": "",
            "regionsAllowed": "af,ax,al,dz,as,ad,ao,ai,aq,ag,am,aw,at,az,bs,bd,bb,by,be,bz,bj,bm,bt,bo,bq,ba,bw,bv,io,bg,bf,bi,cv,cm,ky,cf,td,cx,cc,km,cg,cd,ck,cr,ci,hr,cu,cw,cy,cz,dk,dj,dm,do,ec,sv,gq,er,ee,sz,et,fk,fo,fj,fi,gf,pf,tf,ga,gm,ge,gh,gi,gr,gl,gd,gp,gu,gt,gg,gn,gw,gy,ht,hm,va,hn,hu,is,ir,iq,ie,im,il,it,jm,je,jo,ke,ki,kp,kg,lv,lb,ls,lr,ly,li,lt,lu,mg,mw,mv,ml,mt,mh,mq,mr,mu,yt,fm,md,mc,mn,me,ms,ma,mz,na,nr,np,nl,nc,ni,ne,nu,nf,mk,mp,no,pk,pw,ps,pa,pg,py,pn,pl,pr,re,ro,rw,bl,sh,kn,lc,mf,pm,vc,ws,sm,st,sn,rs,sc,sl,sx,sk,si,sb,so,gs,ss,lk,sd,sr,sj,se,ch,sy,tj,tz,tl,tg,tk,to,tt,tn,tr,tm,tc,tv,ug,um,uy,vu,ve,vg,vi,wf,eh,ye,zm,zw,us,ca,th,ph,my,la,id,kh,bn,vn,sg,mm,hk,mo,au,nz,gb,jp,kr,es,mx,ar,cl,co,pe,br,pt,in,ru,ua,kz,uz,ae,om,bh,qa,kw,sa,eg,fr,za,ng,de,tw",
            "director": [
                            {
                "@type": "Person",
                "name": "<?= $this->escape($video['director']) ?>"
              }
                          ],
            "actor": [
                            {
                "@type": "Person",
                "name": "<?= $this->escape($video['actor'] ?? '未知演员') ?>"
              }
                          ],
            "genre": [
                            "中国大陆"
              ,              "冒险"
              ,              "动画"
              ,              "奇幻"
                          ],
            "caption": "带字幕",
            "@id": "https://m.ql83.com /play/<?= $this->escape($video['title']) ?>-<?= $this->escape($video['year']) ?>-1-<?= $this->escape($video['id']) ?>.html",
            "url": "https://m.ql83.com /play/<?= $this->escape($video['title']) ?>-<?= $this->escape($video['year']) ?>-1-<?= $this->escape($video['id']) ?>.html",
            "embedUrl": "https://m.ql83.com /play/<?= $this->escape($video['title']) ?>-<?= $this->escape($video['year']) ?>-1-<?= $this->escape($video['id']) ?>.html",
            "aggregateRating": {
              "@type": "AggregateRating",
              "ratingValue": "<?= $this->escape($video['rating']) ?>",
              "bestRating": "10",
              "ratingCount": "364"
            },
            "alternativeHeadline":""
          },
          {
            "@type": "TVSeries",
            "actor": [
                            {
                "@type": "Person",
                "name": "<?= $this->escape($video['actor'] ?? '未知演员') ?>"
              }
                          ],
            "name": "免费在线看 <?= $this->escape($video['title']) ?> - 动漫在线观看 - 星海影院",
            "@id": "https://m.ql83.com /play/<?= $this->escape($video['title']) ?>-<?= $this->escape($video['year']) ?>-1-<?= $this->escape($video['id']) ?>.html",
            "url": "https://m.ql83.com /play/<?= $this->escape($video['title']) ?>-<?= $this->escape($video['year']) ?>-1-<?= $this->escape($video['id']) ?>.html",
            "description": "<?= $this->escape($video['description']) ?>",
            "image": "<?= $this->escape($video['poster']) ?>",
                        "numberOfEpisodes": "12",
                        "copyrightYear": "<?= $this->escape($video['year']) ?>",
            "contentRating": "13+",
            "alternativeHeadline":""
          },
          {
            "@type": "BreadcrumbList",
            "itemListElement": 
              [
                {
                  "@type": "ListItem",
                  "position": 1,
                  "item": {
                    "@id": "https://m.ql83.com /",
                    "name": "星海影院",
                    "image": "https://m.ql83.com /static/images/logo.png"
                  }
                },
                {
                  "@type": "ListItem",
                  "position": 2,
                  "item": {
                    "@id": "https://m.ql83.com /play/<?= $this->escape($video['title']) ?>-<?= $this->escape($video['year']) ?>-1-<?= $this->escape($video['id']) ?>.html",
                    "name": "<?= $this->escape($video['title']) ?>",
                    "image": "<?= $this->escape($video['poster']) ?>"
                  }
                }
              ]
      }]
  }
</script>
<link href="/template/yuyuyy/asset/css/common.css?version=244" rel="stylesheet" type="text/css" />
<script src="/template/yuyuyy/asset/js/jquery.js"></script>
<script src="/template/yuyuyy/asset/js/assembly.js"></script>
<script src="/template/yuyuyy/asset/js/swiper.min.js"></script>
<script>var maccms={"vod_mask":"mask-1","path2":"/","day":"2","jx":"0","so_off":"0","bt-style":"","login-login":"/","path":"","mid":"1","aid":"15","url":"m.ql83.com ","wapurl":"m.ql83.com ","mob_status":"0"};</script>
<script src="/template/yuyuyy/asset/js/ecscript.js"></script>
<script>new WOW().init();</script><meta name="format-detection" content="telephone=no" />
<meta name="renderer" content="webkit" />
<meta name="force-rendering" content="webkit" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta name="applicable-device" content="pc,mobile" />
<meta name="mobile-agent" content="format=html5;url=/" />
<meta name="AI-generated" content="false" />
<meta name="AI-optimized" content="true" />
<meta name="content-type" content="video-streaming-website" />
<meta name="audience" content="general" />
<meta name="content-classification" content="entertainment" />
<meta name="featured-snippet-eligible" content="true" />
<meta name="answer-type" content="how-to-watch, movie-info, tv-show-info" />
<meta name="google-discover" content="enable" />
<meta name="max-image-preview" content="large" />
<meta name="max-snippet" content="-1" />
<meta name="max-video-preview" content="-1" />
<link rel="shortcut icon" href="/template/yuyuyy/asset/img/favicon.png" type="image/x-icon" />
<link rel="apple-touch-icon" href="/static/images/ios_fav.png">
<link rel="icon" sizes="180x180" type="image/png" href="/template/yuyuyy/asset/img/favicon.png">

<!-- Video.js CSS -->
<link href="https://vjs.zencdn.net/8.10.0/video-js.css" rel="stylesheet" />

<!-- 专业音频播放器样式 -->
<style>
/* 音频播放器专用样式 */
    .audio-player-container {
        display: none;
        width: 88%;
        height: 85%;
        background: #1e293b;
        border-radius: 8px;
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.8), 
            inset 0 1px 0 rgba(255, 255, 255, 0.1),
            0 0 20px rgba(59, 130, 246, 0.3);
        border: 2px solid rgba(59, 130, 246, 0.5);
        padding: 25px;
        box-sizing: border-box;
        position: relative;
        overflow: hidden;
        margin: 0 auto;
        margin-top: 15px;
        font-family: 'Courier New', monospace;
    }
    
    /* 统一四边边框发光效果 */
    .audio-player-container::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: 
            linear-gradient(90deg, rgba(59, 130, 246, 0.3) 0%, rgba(59, 130, 246, 0.15) 50%, rgba(59, 130, 246, 0.3) 100%),
            radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.25) 0%, transparent 50%);
        border-radius: 10px;
        z-index: 0;
        pointer-events: none;
    }

.audio-player-container.active {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* 机械战甲装饰元素 */
.audio-player-container::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        linear-gradient(90deg, transparent 0%, rgba(59, 130, 246, 0.15) 50%, transparent 100%),
        radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.25) 0%, transparent 50%);
    pointer-events: none;
    z-index: 1;
}

/* 网格纹理 */
/* .audio-player-container .grid-overlay {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        repeating-linear-gradient(90deg, transparent, transparent 3px, rgba(59, 130, 246, 0.1) 3px, rgba(59, 130, 246, 0.1) 6px),
        repeating-linear-gradient(0deg, transparent, transparent 3px, rgba(59, 130, 246, 0.1) 3px, rgba(59, 130, 246, 0.1) 6px);
    background-size: 25px 25px;
    opacity: 0.4;
    pointer-events: none;
    z-index: 1;
} */

/* 音频播放器内容 */
.audio-player-content {
    position: relative;
    z-index: 2;
    text-align: center;
    width: 100%;
}

/* 机械战甲风格的封面 */
.audio-cover {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    margin: 0 auto 20px;
    position: relative;
    border: 3px solid rgba(59, 130, 246, 0.6);
    box-shadow: 
        0 0 20px rgba(59, 130, 246, 0.4),
        inset 0 0 20px rgba(0, 0, 0, 0.5);
    overflow: hidden;
    z-index: 2;
    animation: rotateCover 20s linear infinite;
}

@keyframes rotateCover {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}

.audio-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.audio-cover::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #3b82f6, #1e3a8a, #3b82f6);
    border-radius: 50%;
    z-index: -1;
    animation: rotateHalo 3s linear infinite reverse;
}

.audio-cover::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(59, 130, 246, 0.2), transparent);
    border-radius: 50%;
    z-index: 1;
}

@keyframes rotateHalo {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* 音频信息 */
.audio-info {
    margin-bottom: 20px;
    color: white;
}

.audio-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 8px;
    text-shadow: 0 0 10px rgba(59, 130, 246, 0.8);
    color: #ffffff;
    letter-spacing: 1px;
    position: relative;
    z-index: 2;
}

.audio-title::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    width: 60%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #3b82f6, transparent);
    box-shadow: 0 0 10px rgba(59, 130, 246, 0.6);
}

.audio-artist {
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: 4px;
}

.audio-album {
    font-size: 12px;
    opacity: 0.7;
}

/* 音频控制栏 */
.audio-controls {
    width: 100%;
    max-width: 320px;
    margin: 0 auto;
}

/* 机械战甲风格的进度条 */
.audio-progress {
    width: 100%;
    height: 12px;
    background: rgba(0, 0, 0, 0.6);
    border-radius: 6px;
    margin-bottom: 20px;
    position: relative;
    cursor: pointer;
    overflow: visible;
    border: 1px solid rgba(59, 130, 246, 0.3);
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.5);
    z-index: 2;
}

.audio-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #1e3a8a, #3b82f6);
    border-radius: 6px;
    position: relative;
    transition: width 0.1s ease;
    box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
}

.audio-progress-bar::after {
    content: '';
    position: absolute;
    top: 50%;
    right: 0;
    width: 20px;
    height: 20px;
    background: #ffffff;
    border-radius: 50%;
    transform: translate(50%, -50%);
    box-shadow: 
        0 0 15px rgba(59, 130, 246, 0.8),
        0 0 30px rgba(59, 130, 246, 0.4);
    border: 3px solid #3b82f6;
}

/* 机械战甲风格的时间显示 */
.audio-time {
    display: flex;
    justify-content: space-between;
    color: #3b82f6;
    font-size: 11px;
    margin-bottom: 18px;
    font-weight: bold;
    text-shadow: 0 0 5px rgba(59, 130, 246, 0.5);
    font-family: 'Courier New', monospace;
    letter-spacing: 1px;
    z-index: 2;
}

/* 机械战甲风格的控制按钮 */
.audio-buttons {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
    z-index: 2;
}

.audio-btn {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    border: 2px solid rgba(59, 130, 246, 0.5);
    background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
    color: #ffffff;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 
        0 4px 8px rgba(0, 0, 0, 0.6),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.audio-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.3), transparent);
    transition: left 0.5s ease;
}

.audio-btn:hover::before {
    left: 100%;
}

.audio-btn:hover {
    transform: translateY(-2px);
    box-shadow: 
        0 6px 12px rgba(0, 0, 0, 0.8),
        0 0 20px rgba(59, 130, 246, 0.4);
    border-color: rgba(59, 130, 246, 0.8);
}

.audio-btn.play-pause {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #3b82f6, #1e3a8a);
    border-color: rgba(59, 130, 246, 0.8);
    box-shadow: 
        0 6px 12px rgba(0, 0, 0, 0.8),
        0 0 25px rgba(59, 130, 246, 0.6);
    font-size: 18px;
}

.audio-btn.play-pause:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 
        0 8px 16px rgba(0, 0, 0, 0.9),
        0 0 30px rgba(59, 130, 246, 0.8);
}



/* 机械战甲风格的音频可视化 */
.audio-visualizer {
    width: 100%;
    height: 40px;
    margin-top: 20px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    gap: 3px;
    z-index: 2;
}

.audio-bar {
    width: 4px;
    background: linear-gradient(to top, #3b82f6, #1e3a8a);
    border-radius: 2px;
    box-shadow: 0 0 8px rgba(59, 130, 246, 0.6);
    animation: audioWave 1.5s ease-in-out infinite;
}

.audio-bar:nth-child(odd) {
    animation-delay: 0.1s;
}

.audio-bar:nth-child(even) {
    animation-delay: 0.3s;
}

@keyframes audioWave {
    0%, 100% { height: 15px; }
    50% { height: 40px; }
}

/* 机械战甲风格响应式设计 */
@media (max-width: 768px) {
    .audio-player-container {
        padding: 15px;
        border-radius: 8px;
        border-width: 1px;
    }
    
    .audio-cover {
        width: 100px;
        height: 100px;
        margin-bottom: 15px;
        border-width: 2px;
    }
    
    .audio-title {
        font-size: 16px;
    }
    
    .audio-artist {
        font-size: 12px;
    }
    
    .audio-buttons {
        gap: 12px;
    }
    
    .audio-btn {
        width: 40px;
        height: 40px;
        font-size: 12px;
        border-width: 1px;
    }
    
    .audio-btn.play-pause {
        width: 50px;
        height: 50px;
        font-size: 16px;
    }
    
    /* 移动端进度条优化 */
    .audio-progress {
        height: 10px;
        border-radius: 5px;
    }
    
    .audio-progress-bar::after {
        width: 18px;
        height: 18px;
        border-width: 3px;
    }
}

/* 音频播放器切换动画 */
.audio-player-container {
    opacity: 0;
    transform: scale(0.9);
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.audio-player-container.active {
    opacity: 1;
    transform: scale(1);
}

/* 隐藏视频播放器当显示音频播放器时 */
.video-player-hidden {
    display: none !important;
}

/* 音频播放器加载状态 */
.audio-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.audio-loading-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(255,255,255,0.3);
    border-top: 3px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 15px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
</head>
<body class="theme2">
<div class="gen-loading bj load-icon-on">
    <img class="loading1 none" data-ii="on" src="/template/yuyuyy/asset/img/logo-1.png" alt="星海影院">
    <img class="loading2 none" src="/template/yuyuyy/asset/img/logo-2.png" alt="星海影院">
</div>
<?php include 'components/header.php'; ?>
<style>.footer{display:none!important}@media (min-width:993px){.MacPlayer{padding:10px 0!important}}</style>
<style>
/* 页面整体样式 */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    /* 移除overflow: hidden，允许页面滚动 */
}

/* 隐藏top-back组件 */
.top-back {
    display: none !important;
}

/* 播放器容器样式 */
.player {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: transparent;
    /* 移除overflow: hidden，允许内容滚动 */
}

.player-box {
    flex: 1;
    display: flex;
    min-height: calc(100vh - 60px);
    background: transparent;
    /* 移除overflow: hidden，允许内容滚动 */
}

.player-left {
    flex: 1;
    position: relative;
    background: transparent;
    /* 移除overflow: hidden，允许内容滚动 */
    padding: 0;
    margin: 0;
    max-width: calc(100% - 400px); /* 限制左侧播放器宽度，为右侧留出空间 */
}

.player-right {
    width: 400px;
    min-width: 400px;
    flex-shrink: 0;
    background: #1a1a1a;
    overflow-y: auto;
    padding: 0 15px;
}

/* 播放器主体区域 */
.MacPlayer {
    width: 100% !important;
    height: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    position: relative;
    overflow: hidden;
    border-radius: 0;
    background: transparent;
}

#mse {
    width: 100% !important;
    height: 100% !important;
    position: relative;
    overflow: hidden;
}

#mse .xgplayer {
    width: 100% !important;
    height: 100% !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    overflow: hidden;
}

#mse .xgplayer-video {
    width: 100% !important;
    height: 100% !important;
    object-fit: contain;
}

/* Video.js播放器控制按钮垂直居中 */
.video-js .vjs-control-bar {
    background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.8) 100%);
    backdrop-filter: blur(10px);
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    padding: 0;
    margin: 0;
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
}

/* 为所有Video.js控制按钮添加垂直居中 */
.video-js .vjs-play-control,
.video-js .vjs-pause-control,
.video-js .vjs-volume-panel,
.video-js .vjs-playback-rate,
.video-js .vjs-picture-in-picture-control,
.video-js .vjs-fullscreen-control,
.video-js .vjs-time-control,
.video-js .vjs-progress-control,
.video-js .vjs-button,
.video-js .vjs-control {
    align-self: center !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

/* 进度条垂直居中 */
.video-js .vjs-progress-control {
    height: 6px;
    align-self: center !important;
    margin: auto 0 !important;
    position: relative !important;
    display: flex !important;
    align-items: center !important;
}

.video-js .vjs-progress-holder {
    height: 6px;
    background: rgba(255,255,255,0.2);
    border-radius: 3px;
    position: relative !important;
    display: flex !important;
    align-items: center !important;
}

.video-js .vjs-play-progress {
    background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
    border-radius: 3px;
    height: 100% !important;
}

.video-js .vjs-load-progress {
    background: rgba(255,255,255,0.3);
    border-radius: 3px;
    height: 100% !important;
}

/* 修复Video.js进度条拖动点 */
.video-js .vjs-progress-control .vjs-progress-holder .vjs-play-progress:before {
    content: '';
    position: absolute;
    right: -6px;
    top: 50%;
    transform: translateY(-50%);
    width: 12px;
    height: 12px;
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 0 8px rgba(0,0,0,0.5);
    z-index: 10;
}

/* 让左右时间在进度条两端对齐 */
.xgplayer .xgplayer-time {
    white-space: nowrap !important;
    opacity: 1 !important;
    visibility: visible !important;
    font-size: 12px !important;
    margin: 0 1px !important;
}

.xgplayer .xgplayer-volume {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

.xgplayer .xgplayer-fullscreen {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

/* 隐藏播放器弹窗 */
.msg-error,
.msg-box-bj,
.msg-box,
.topfadeInUp {
    display: none !important;
    opacity: 0 !important;
    visibility: hidden !important;
}

/* 中等屏幕响应式设计 */
@media (max-width: 1024px) and (min-width: 769px) {
    .player-left {
        max-width: calc(100% - 360px);
    }
    
    .player-right {
        width: 360px;
        min-width: 360px;
        padding: 0 15px;
    }
}

/* 响应式设计 */
@media (max-width: 768px) {
    .player-box {
        flex-direction: column;
        min-height: calc(100vh - 50px);
    }
    
    .player-left {
        min-height: 40vh;
        flex: 1;
        max-width: 100%; /* 移动端全宽显示 */
    }
    
    .player-right {
        min-height: 50vh;
        flex: 1;
        width: 100%;
        min-width: 100%;
        overflow-y: auto;
    }
    
    /* 确保移动端内容可以完整显示 */
    .player {
        min-height: 100vh;
        height: auto;
    }
    
    /* 移动端播放器容器 */
    .MacPlayer {
        min-height: 40vh;
        height: auto;
        margin: 0;
        padding: 0;
    }
    
    /* 移动端剧集列表 */
    .anthology-list {
        max-height: none;
        overflow-y: visible;
    }
    
    .anthology-list-play {
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 8px;
    }
}

/* 确保所有内容都在可视区域内 */
* {
    box-sizing: border-box;
}

/* 移动端底部安全区域 */
@media (max-width: 768px) {
    body {
        padding-bottom: env(safe-area-inset-bottom, 20px);
    }
    
    .player {
        padding-bottom: env(safe-area-inset-bottom, 20px);
    }
    
    .player-right {
        padding-bottom: env(safe-area-inset-bottom, 20px);
    }
}

/* 确保播放器控制栏不被遮挡 */
.xgplayer .xgplayer-controls {
    position: absolute !important;
    bottom: 0 !important;
    left: 0 !important;
    right: 0 !important;
    z-index: 1000 !important;
}

/* 移除播放器容器的多余空间 */
.player-left .MacPlayer {
    margin: 0 !important;
    padding: 0 !important;
    background: transparent !important;
}

.player-left #mse {
    margin: 0 !important;
    padding: 0 !important;
    background: transparent !important;
}

/* 确保播放器区域没有多余的黑色空间 */
.player-left {
    background: transparent !important;
}

.player-left .MacPlayer {
    background: transparent !important;
}

.player-left #mse {
    background: transparent !important;
}

.player-left #mse .video-js {
    background: transparent !important;
}

/* 去除 player-news 的 position 属性 */
.player-news {
    position: static !important;
}

/* 去除 block-split 的 margin */
.block-split {
    margin: 0 !important;
    padding: 0 !important;
}

.block-split.br {
    margin: 0 !important;
    padding: 0 !important;
}

/* Video.js 播放器美化样式 */
.video-js {
    width: 100% !important;
    height: 100% !important;
    border-radius: 0;
    overflow: hidden;
    margin: 0;
    padding: 0;
}

/* iOS兼容性优化 */
.video-js .vjs-tech {
    /* 确保iOS上视频元素正确显示 */
    object-fit: contain;
    -webkit-object-fit: contain;
}

/* iOS上的播放按钮优化 */
.video-js .vjs-big-play-button {
    /* iOS上确保播放按钮可见 */
    -webkit-appearance: none;
    appearance: none;
    background: rgba(0,0,0,0.6);
    border: 2px solid #fff;
    border-radius: 50%;
    width: 80px;
    height: 80px;
    line-height: 76px;
    font-size: 40px;
    transition: all 0.3s ease;
    /* iOS特定优化 */
    -webkit-tap-highlight-color: transparent;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    user-select: none;
}

.video-js .vjs-control-bar {
    background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.8) 100%);
    backdrop-filter: blur(10px);
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    padding: 0;
    margin: 0;
    display: flex !important;
}

.video-js .vjs-progress-control {
    height: 6px;
    align-self: center !important;
    margin: auto 0 !important;
    position: relative !important;
}

.video-js .vjs-progress-holder {
    height: 6px;
    background: rgba(255,255,255,0.2);
    border-radius: 3px;
    position: relative !important;
}

.video-js .vjs-play-progress {
    background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
    border-radius: 3px;
    height: 100% !important;
}

.video-js .vjs-load-progress {
    background: rgba(255,255,255,0.3);
    border-radius: 3px;
    height: 100% !important;
}

/* 修复Video.js进度条拖动点 */
.video-js .vjs-progress-control .vjs-progress-holder .vjs-play-progress:before {
    content: '';
    position: absolute;
    right: -6px;
    top: 50%;
    transform: translateY(-50%);
    width: 12px;
    height: 12px;
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 0 8px rgba(0,0,0,0.5);
    z-index: 10;
}

.video-js .vjs-play-toggle,
.video-js .vjs-volume-panel,
.video-js .vjs-playback-rate,
.video-js .vjs-picture-in-picture-control,
.video-js .vjs-fullscreen-control {
    color: #fff;
    transition: all 0.3s ease;
}

.video-js .vjs-play-toggle:hover,
.video-js .vjs-volume-panel:hover,
.video-js .vjs-playback-rate:hover,
.video-js .vjs-picture-in-picture-control:hover,
.video-js .vjs-fullscreen-control:hover {
    color: #4ecdc4;
    transform: scale(1.1);
}

.video-js .vjs-time-control {
    font-size: 14px;
    font-weight: 500;
    color: #fff;
    text-shadow: 0 1px 2px rgba(0,0,0,0.8);
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* 强制显示时间控制元素，让时间显示更紧凑 */
.video-js .vjs-current-time,
.video-js .vjs-duration,
.video-js .vjs-time-divider {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    margin: 0 !important;
    padding: 0 !important;
    letter-spacing: 0 !important;
    line-height: 6px !important;
    height: 6px !important;
    vertical-align: middle !important;
}

/* 确保时间分隔符显示，直接用/隔开，不要间隔 */
.video-js .vjs-time-divider {
    color: #fff !important;
    font-weight: bold !important;
    margin: 0 !important;
    padding: 0 !important;
    letter-spacing: 0 !important;
    width: auto !important;
    min-width: auto !important;
}

/* 强制时间控制容器紧凑排列 */
.video-js .vjs-time-control {
    margin: 0 !important;
    padding: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 0 !important;
    align-self: center !important;
    line-height: 1 !important;
    height: 6px !important;
}

/* 减少控制栏元素间距 */
.video-js .vjs-control-bar {
    padding: 0 !important;
}

.video-js .vjs-control-bar > div {
    margin: 0 !important;
}

/* 减少播放按钮和音量控制之间的间距 */
.video-js .vjs-play-control {
    margin-right: 0 !important;
    padding-right: 0 !important;
    align-self: center !important;
}

.video-js .vjs-volume-panel {
    margin-right: 0 !important;
    padding-left: 0 !important;
    align-self: center !important;
}

/* 减少时间显示和进度条之间的间距 */
.video-js .vjs-duration {
    margin-right: 0 !important;
    padding-right: 0 !important;
}

/* 减少进度条和右侧按钮的间距 */
.video-js .vjs-progress-control {
    margin: 0 !important;
    padding: 0 !important;
}

/* 减少右侧按钮之间的间距 */
.video-js .vjs-picture-in-picture-control {
    margin-right: 0 !important;
    padding-left: 0 !important;
    align-self: center !important;
}

/* 减少所有控制按钮的间距 */
.video-js .vjs-control {
    margin: 0 !important;
    padding: 0 !important;
    align-self: center !important;
}

/* 减少图标按钮的间距 */
.video-js .vjs-button {
    margin: 0 !important;
    padding: 0 !important;
}

/* 强制所有控制元素紧密排列 */
.video-js .vjs-control-bar > * {
    margin: 0 !important;
    padding: 0 !important;
    gap: 0 !important;
}

/* 特别处理音量控制面板 */
.video-js .vjs-volume-panel .vjs-volume-control {
    margin: 0 !important;
    padding: 0 !important;
}

/* 特别处理时间控制 */
.video-js .vjs-time-control {
    margin: 0 !important;
    padding: 0 !important;
}

  /* 隐藏电影海报图片 */
  .video-js .vjs-poster,
  .video-js .vjs-poster-display {
    display: none !important;
  }

  .video-js .vjs-big-play-button {
    background: rgba(0,0,0,0.6);
    border: 2px solid #fff;
    border-radius: 50%;
    width: 80px;
    height: 80px;
    line-height: 76px;
    font-size: 40px;
    transition: all 0.3s ease;
  }

.video-js .vjs-big-play-button:hover {
    background: rgba(78, 205, 196, 0.8);
    border-color: #4ecdc4;
    transform: scale(1.1);
}

/* 响应式设计 */
@media (max-width: 768px) {
    .video-js .vjs-big-play-button {
        width: 60px;
        height: 60px;
        line-height: 56px;
        font-size: 30px;
    }
    
    .video-js .vjs-time-control {
        font-size: 12px;
    }
}

/* 剧集列表样式 */
.player-anthology {
    margin-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: 20px;
}

.anthology-header {
    margin-bottom: 15px;
}

.anthology-header h5 {
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    margin: 0;
}

.anthology-header .function a {
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 4px;
    background: rgba(255,255,255,0.1);
    transition: all 0.3s ease;
    color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    margin-left: 8px;
    white-space: nowrap;
    min-width: auto;
    width: auto;
    text-align: center;
}

.anthology-header .function a:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-1px);
}

/* 确保所有按钮内容垂直居中 */
.anthology-header .function a i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.anthology-header .function a span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* 确保功能按钮容器有足够空间 */
.anthology-header .function {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
    margin-left: auto;
    align-items: center;
}

/* 按钮组样式优化 */
.anthology-header .function a {
    flex-shrink: 0;
    height: 32px;
    line-height: 1;
}

/* 自动播放按钮样式 */
.anthology-header .function a#auto-play-toggle {
    transition: all 0.3s ease;
    border-radius: 4px;
    padding: 6px 12px;
    min-width: auto;
    width: auto;
    text-align: center;
    position: relative;
    white-space: nowrap;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

.anthology-header .function a#auto-play-toggle:hover {
    background: rgba(255,255,255,0.2) !important;
    transform: translateY(-1px);
}

/* 启用状态的高亮效果 */
.anthology-header .function a#auto-play-toggle.enabled {
    background: rgba(78, 205, 196, 0.3) !important;
    border: 1px solid rgba(78, 205, 196, 0.5);
    color: #4ecdc4 !important;
}

/* 禁用状态 */
.anthology-header .function a#auto-play-toggle:not(.enabled) {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
}

.anthology-header .title-m.cor4.flex.between {
    width: 100%;
    gap: 15px;
}

.anthology-list {
    max-height: 300px;
    overflow-y: auto;
}

.anthology-list-play {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.anthology-list-play li {
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 4px;
    transition: all 0.3s ease;
    cursor: pointer;
    background: rgba(0,0,0,0.3);
    min-width: 90px;
    max-width: 110px;
}

.anthology-list-play li:hover {
    border-color: #4ecdc4;
    background: rgba(78, 205, 196, 0.1);
}

.anthology-list-play li.on {
    border-color: #4ecdc4;
    background: rgba(78, 205, 196, 0.2);
}

.anthology-list-play li a {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 8px 6px;
    text-align: center;
    text-decoration: none;
    color: #fff;
    font-size: 12px;
    line-height: 1.2;
    min-height: 45px;
    width: 100%;
    box-sizing: border-box;
    position: relative;
    text-align: center;
}

.anthology-list-play li a span {
    display: block;
    margin: 0;
    font-weight: 500;
    white-space: nowrap;
    text-align: center;
    width: 100%;
    line-height: 1.2;
}

.anthology-list-play li a em.play-on {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    margin: 0 auto;
    background: transparent;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.anthology-list-play li a em.play-on::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 0;
    height: 0;
    border-left: 6px solid #4ecdc4;
    border-top: 4px solid transparent;
    border-bottom: 4px solid transparent;
    margin: 0;
}

/* 确保播放按钮在无剧集时也能正确显示 */
.anthology-list-play li.on a em.play-on {
    background: transparent;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
}

.anthology-list-play li.on a em.play-on::before {
    border-left: 6px solid #ff6b6b;
    border-top: 4px solid transparent;
    border-bottom: 4px solid transparent;
}

/* 当前播放的剧集使用不同的颜色 */
.anthology-list-play li.on a em.play-on {
    background: transparent;
}

.anthology-list-play li.on a em.play-on::before {
    border-left: 6px solid #ff6b6b;
    border-top: 4px solid transparent;
    border-bottom: 4px solid transparent;
    margin: 0;
}

/* 添加播放状态指示器 */
.anthology-list-play li.on a em.play-on::after {
    content: '';
    position: absolute;
    right: -2px;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 8px;
    background: #fff;
    border-radius: 2px;
    animation: pulse 2s infinite;
}

/* 禁用状态的剧集样式 */
.anthology-list-play li.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: rgba(0,0,0,0.5);
    border-color: rgba(255,255,255,0.1);
}

.anthology-list-play li.disabled:hover {
    border-color: rgba(255,255,255,0.1);
    background: rgba(0,0,0,0.5);
    transform: none;
}

.anthology-list-play li.disabled a {
    cursor: not-allowed;
    color: rgba(255,255,255,0.5);
}

.anthology-list-play li.disabled a em.play-on.disabled::before {
    border-left: 6px solid rgba(255,255,255,0.3);
    border-top: 4px solid transparent;
    border-bottom: 4px solid transparent;
}

.anthology-list-play li.disabled a em.play-on.disabled::after {
    background: rgba(255,255,255,0.3);
    animation: none;
}

/* 禁用状态的提示 */
.anthology-list-play li.disabled::after {
    content: '暂无片源';
    position: absolute;
    bottom: -20px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 10px;
    color: rgba(255,255,255,0.6);
    white-space: nowrap;
    background: rgba(0,0,0,0.8);
    padding: 2px 6px;
    border-radius: 3px;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.anthology-list-play li.disabled:hover::after {
    opacity: 1;
}

@keyframes pulse {
    0%, 100% { opacity: 0.6; }
    50% { opacity: 1; }
}

/* 滚动条样式 */
.anthology-list::-webkit-scrollbar {
    width: 6px;
}

.anthology-list::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.1);
    border-radius: 3px;
}

.anthology-list::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 3px;
}

.anthology-list::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.5);
}

/* 响应式剧集列表 */
@media (max-width: 768px) {
    .anthology-list-play {
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 6px;
    }
    
    .anthology-list-play li {
        min-width: 80px;
        max-width: 95px;
    }
    
    .anthology-list-play li a {
        padding: 6px 4px;
        font-size: 11px;
        min-height: 40px;
    }
    
    .anthology-list {
        max-height: none;
        overflow-y: visible;
    }
    
    /* 移动端按钮优化 */
    .anthology-header .function {
        gap: 6px;
        flex-wrap: wrap;
    }
    
    .anthology-header .function a {
        padding: 5px 8px;
        font-size: 11px;
        height: 28px;
    }
}

@media (max-width: 480px) {
    .anthology-list-play {
        grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
        gap: 5px;
    }
    
    .anthology-list-play li {
        min-width: 70px;
        max-width: 85px;
    }
    
    .anthology-list-play li a {
        padding: 5px 3px;
        font-size: 10px;
        min-height: 35px;
    }
}

/* 单列布局样式 */
.anthology-list-play.single-column {
    grid-template-columns: 1fr !important;
    gap: 4px !important;
}

.anthology-list-play.single-column li {
    border-radius: 6px;
}

.anthology-list-play.single-column li a {
    padding: 12px 8px !important;
    font-size: 14px !important;
    text-align: left !important;
    flex-direction: row !important;
    justify-content: space-between !important;
}

.anthology-list-play.single-column li a span {
    display: inline-block !important;
    margin-bottom: 0 !important;
    margin-right: 10px !important;
}

.anthology-list-play.single-column li a em.play-on {
    position: absolute !important;
    right: 8px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    float: none !important;
    margin: 0 !important;
}

/* 虚拟列表占位层 */
.anthology-list { position: relative; }
.vlist-phantom { width: 1px; opacity: 0; }
#episode-list.v-virtual { position: absolute; top: 0; left: 0; right: 0; }
</style>
<div class="player bj">
    <div class="player-box">
        <div class="player-left">
            <div class="player-switch fa">&#xe565;</div>
                        <div class="player-news">
                <!-- <div class="news-list">
                    <ul class="swiper-wrapper">
                        <li class="swiper-slide"><i class="ol1">提示</i>不要轻易相信视频中的广告，谨防上当受骗!</li>
<li class="swiper-slide"><i class="ol3">提示</i>如果无法播放请重新刷新页面。</li>
<li class="swiper-slide"><i class="ol7">提示</i>视频载入速度跟网速有关，请耐心等待几秒钟。</li>                    
</ul>
                </div> -->
                <!-- <span class="player-news-off cor6 fa ds-guanbi"></span> -->
            </div>
            <script>let announcementSwiper = new Swiper('.news-list', {direction: 'vertical', loop: true, autoplay: {delay: 2000, disableOnInteraction: false,}});$(".player-news-off").click(function(){$(".player-news").hide()});</script>
                        
            <!-- 视频播放器 -->
            <div class="MacPlayer video-player" style="z-index:99999;width:100%;height:100%;margin:0px;padding:0px;">
                <video id="mse" class="video-js vjs-default-skin" controls preload="metadata" 
       playsinline webkit-playsinline width="100%" height="100%">
    <p class="vjs-no-js">您的浏览器不支持HTML5视频播放，请升级浏览器或启用JavaScript。</p>
</video>
            </div>
            
            <!-- 音频播放器 -->
            <div class="audio-player-container" id="audioPlayer">
                <div class="grid-overlay"></div>
                <div class="audio-player-content">
                    <div class="audio-cover">
                        <img src="/template/yuyuyy/asset/img/logo-1.png" alt="音频封面" id="audioCover">
                    </div>
                    
                    <div class="audio-info">
                        <div class="audio-title" id="audioTitle"><?= $this->escape($video['title']) ?><?= $hasEpisodes ? ' 第' . ($currentEpisodeNumber ?? 1) . '集' : '' ?></div>
                        <!-- <div class="audio-artist" id="audioArtist">星海影院</div> -->
                        <div class="audio-album" id="audioAlbum">音频播放</div>
                    </div>
                    
                    <div class="audio-controls">
                        <div class="audio-progress" id="audioProgress">
                            <div class="audio-progress-bar" id="audioProgressBar"></div>
                        </div>
                        
                        <div class="audio-time">
                            <span id="audioCurrentTime">0:00</span>
                            <span id="audioDuration">0:00</span>
                        </div>
                        
                        <div class="audio-buttons">
                            <button class="audio-btn" id="audioPrev" title="上一首">
                            <i class="fa">&#xe591;</i>
                            </button>
                            <button class="audio-btn play-pause" id="audioPlayPause" title="播放/暂停">
                                <i class="fa">&#xe593;</i>
                            </button>
                            <button class="audio-btn" id="audioNext" title="下一首">
                                <i class="fa">&#xe590;</i>
                            </button>
                        </div>
                        

                    </div>
                    
                    <div class="audio-visualizer" id="audioVisualizer">
                        <div class="audio-bar" style="height: 20px;"></div>
                        <div class="audio-bar" style="height: 40px;"></div>
                        <div class="audio-bar" style="height: 30px;"></div>
                        <div class="audio-bar" style="height: 50px;"></div>
                        <div class="audio-bar" style="height: 25px;"></div>
                        <div class="audio-bar" style="height: 45px;"></div>
                        <div class="audio-bar" style="height: 35px;"></div>
                        <div class="audio-bar" style="height: 55px;"></div>
                        <div class="audio-bar" style="height: 20px;"></div>
                        <div class="audio-bar" style="height: 40px;"></div>
                        <div class="audio-bar" style="height: 30px;"></div>
                        <div class="audio-bar" style="height: 50px;"></div>
                        <div class="audio-bar" style="height: 25px;"></div>
                        <div class="audio-bar" style="height: 45px;"></div>
                        <div class="audio-bar" style="height: 35px;"></div>
                        <div class="audio-bar" style="height: 55px;"></div>
                        <div class="audio-bar" style="height: 20px;"></div>
                        <div class="audio-bar" style="height: 40px;"></div>
                        <div class="audio-bar" style="height: 30px;"></div>
                        <div class="audio-bar" style="height: 50px;"></div>
                    </div>
                </div>
            </div>
                    </div>
        <div class="player-right cor5 bj">
            <div class="player-details-box none">
                <div class="title">
                    <div class="flex between">
                        <a class="player-return cor5" href="javascript:"><span class="none">返回</span></a>
                        <a class="player-close fa cor5" href="javascript:">&#xe561;</a>
                    </div>
                </div>
                <div class="list-body">
                    <div class="plist-body">
                        <!--详情信息-->
                        <div class="player-vod-no1">
                            <div class="role-card top20">
                                <div class="card-top cf">
                                    <div class="left"><img class="lazy" data-src="<?= $this->escape($video['poster']) ?>" src="/template/yuyuyy/asset/img/img-bj-k.png" alt="<?= $this->escape($video['title']) ?>"></div>
                                    <div class="right">
                                        <a href="javascript:"><?= $this->escape($video['title']) ?></a>
                                        <p style="font-size:14px">状态：<?= $this->escape($video['status']) ?></p>
                                        <p style="font-size:14px">主演：<?= $this->escape($video['actor']) ?></p>
                                        <p style="font-size:14px">导演：<?= $this->escape($video['director']) ?></p>
                                        <p style="font-size:14px">地区：<?= $this->escape($video['region']) ?></p>
                                        <p style="font-size:14px">评分：<?= $this->escape($video['rating']) ?></p>
                                    </div>
                                </div>
                                <div class="title-m cor4" style="margin-top: 20px">
                                    <h5>影视简介</h5>
                                </div>
                                <div class="card-text"><?= $this->escape($video['description']) ?></div>
                            </div>
                                                        <div class="title-m cor4" style="margin-top: 20px">
                                <h5>演员</h5>
                            </div>
                            <div class="actor-list flex wrap" style="margin-right:-26px">
                                                            </div>
                                                    </div>
                        <!--演员资料-->
                        <div class="player-vod-no2 none">
                            <div class="top40"><div class="loading"><span></span><span></span><span></span><span></span><span></span></div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="player-vod-box">
                <!-- <div class="title">
                    <div class="flex switch-button">
                        <a class="selected" href="javascript:" title="视频信息">视频</a>
                        <a class="split Pg" href="javascript:" title="用户讨论"></a>
                        <a class="player-comment" href="javascript:" title="用户讨论" data-login="1" data-verify="1">讨论</a>
                    </div>
                </div> -->
                <div class="list-body">
                    <div class="plist-body">
                        <div class="switch-box top20">
                            <div class="check selected">
                                <h3><a href="?page=list&category=<?= $video['category_ids'][0] ?? '' ?>" target="_blank" class="player-title-link"><?= $this->escape($video['title']) ?></a></h3>
                                <div class="player-details flex wrap">
                                    <i class="fa r3 co8">&#xe596;</i><em>观看</em><span class="division">·</span>
                                    <span title="<?= $this->escape($video['year']) ?>" ><?= $this->escape($video['year']) ?></span><span class="division">·</span>
                                    <span title="<?= $this->escape($video['category_name']) ?>"><?= $this->escape($video['category_name']) ?></span><span class="division">·</span>
                                    <span><?= $this->escape($video['duration']) ?></span><span class="division">·</span>
                                                                        <a id="expand_details" href="https://movie.douban.com/subject_search?search_text=<?= urlencode($video['title']) ?>" target="_blank" class="b">详情<i class="fa">&#xe565;</i></a>
                                </div>
                                <div class="fun flex between box radius">
                                    <a class="item collection cor5" id="collectionBtn" data-type="2" data-mid="1" data-id="<?= $video['id'] ?>" onclick="toggleCollection(<?= $video['id'] ?>)">
                                        <i class="fa r6" id="collectionIcon">&#xe577;</i><span id="collectionText">收藏</span>
                                    </a>
                                    <a class="ec-report item cor5" data-url="编号【<?= $video['id'] ?>】名称【<?= $this->escape($video['title']) ?>】不能观看请检查修复" data-id="<?= $video['id'] ?>"><i class="fa r6">&#xe595;</i>报错</a>
                                    <a class="item player-share-button cor5" onclick="shareVideo()"><i class="fa r6">&#xe569;</i>分享</a>
                                </div>
                                <div class="player-share-box radius topfadeInUp none box">
                                    <div class="flex">
                                        <div class="share-qrcode">
                                            <p class="share-text">手机扫描访问</p>
                                            <div class="hl-cans none"></div>
                                            <p class="share-pic"></p>
                                        </div>
                                        <div style="margin-left:20px">
                                            <span class="share-tips">分享给好友吧~</span>
                                            <span id="bar" class="share-url bj">https://m.ql83.com /?page=play&id=<?= $video['id'] ?></span>
                                            <button type="button" class="share-copy bj2 ho radius copyBtn" data-clipboard-action="copy" data-clipboard-target="#bar">复制链接</button>
                                        </div>
                                    </div>
                                </div>
                                <div style="display:none">无模块</div>
                                
                                <?php if ($hasEpisodes): ?>
                                <!-- 剧集列表 -->
                                <div class="player-anthology">
                                    <div class="block-split br"></div>
                                    <div class="anthology-header top10">
                                        <div class="title-m cor4 flex between">
                                            <h5>选集</h5>
                                            <div class="function">
                                                <a id="zxdaoxu" class="r6 cor5" href="javascript:" onclick="toggleEpisodeOrder()"><i class="fa r3">&#xe557;</i>排序</a>
                                                <a class="player-button-ac cor5 r6" href="javascript:" onclick="toggleEpisodeLayout()"><i class="fa r3">&#xe553;</i>单列</a>
                                                <a id="auto-play-toggle" class="r6 cor5" href="javascript:" onclick="toggleAutoPlay()"><i class="fa r3">&#xe557;</i>自动播放</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="anthology wow fadeInUp">
                                        <div class="anthology-list top10 select-a">
                                            <div class='anthology-list-box none dx'>
                                                <div>
                                                    <ul class="anthology-list-play size">
                                                        <?php foreach ($episodes as $episode): ?>
                                                        <?php 
                                                        // 检查剧集是否有有效的播放链接
                                                        $hasValidLink = !empty($episode['video_path']) && trim($episode['video_path']) !== '';
                                                        $isCurrentEpisode = ($episode['episode_number'] == $currentEpisodeNumber);
                                                        $liClass = 'box border';
                                                        if ($isCurrentEpisode) {
                                                            $liClass .= ' on ecnav-dt';
                                                        }
                                                        if (!$hasValidLink) {
                                                            $liClass .= ' disabled';
                                                        }
                                                        ?>
                                                        <li class="<?= $liClass ?>" data-episode="<?= $episode['episode_number'] ?>" data-has-link="<?= $hasValidLink ? '1' : '0' ?>">
                                                            <a class="hide cor4" href="javascript:void(0)" <?= $hasValidLink ? 'onclick="switchEpisodeWithoutRefresh(' . $episode['episode_number'] . ')"' : 'onclick="showDisabledEpisodeTip()"' ?>>
                                                                <?php if ($isCurrentEpisode && $hasValidLink): ?>
                                                                <em class="play-on"><i></i><i></i><i></i><i></i></em>
                                                                <?php elseif ($isCurrentEpisode && !$hasValidLink): ?>
                                                                <em class="play-on disabled"><i></i><i></i><i></i><i></i></em>
                                                                <?php else: ?>
                                                                <span>第<?= str_pad($episode['episode_number'], 2, '0', STR_PAD_LEFT) ?>集</span>
                                                                <?php endif; ?>
                                                            </a>
                                                        </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                <!-- 无剧集时显示动画播放按钮 -->
                                <div class="player-anthology">
                                    <div class="block-split br"></div>
                                    <div class="anthology-header top10">
                                        <div class="title-m cor4 flex between">
                                            <h5>当前播放</h5>
                                        </div>
                                    </div>
                                    <div class="anthology wow fadeInUp">
                                        <div class="anthology-list top10 select-a">
                                            <div class='anthology-list-box none dx'>
                                                <div>
                                                    <ul class="anthology-list-play size">
                                                        <li class="box border on ecnav-dt">
                                                            <a class="hide cor4" href="javascript:void(0)">
                                                                <em class="play-on"><i></i><i></i><i></i><i></i></em>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                            </div>
                            <div class="check"></div>
                                                        <div class="check">
                                <div class="ds-comment" data-id="<?= $video['id'] ?>" data-mid="1" >
                                    <div class="top40"><div class="loading"><span></span><span></span><span></span><span></span><span></span></div></div>
                                </div>
                            </div>
                                                    </div>
                    </div>
                </div>
        </div>
        </div>
    </div>
</div>
<!-- Video.js JavaScript -->
<script src="https://vjs.zencdn.net/8.10.0/video.min.js"></script>
<script>
    // 防止页面自动滚动
    window.addEventListener('scroll', function(e) {
        // 阻止任何程序化的滚动
        if (e.isTrusted === false) {
            e.preventDefault();
            e.stopPropagation();
        }
    });
    
    // 禁用任何可能的自动滚动
    document.addEventListener('DOMContentLoaded', function() {
        // 防止其他脚本干扰滚动
        const originalScrollTo = window.scrollTo;
        const originalScrollBy = window.scrollBy;
        
        window.scrollTo = function(x, y) {
            // 只允许用户手动滚动，阻止程序化滚动
            if (arguments.length === 0 || (x === 0 && y === 0)) {
                return;
            }
            return originalScrollTo.apply(this, arguments);
        };
        
        window.scrollBy = function(x, y) {
            // 阻止程序化滚动
            return;
        };
        
        // 移除可能的滚动事件监听器
        $("html,body").off('scroll');
        $(window).off('scroll');
    });
    
    // 检查视频URL是否存在
    const videoUrl = "<?= $this->escape($playVideoUrl) ?>";
    const videoTitle = "<?= $this->escape($playVideoTitle) ?>";
    const videoPoster = "<?= $this->escape($playVideoPoster) ?>";
    
    // 检测是否为音频文件
    function isAudioUrl(u) {
        return /\.(mp3|m4a|aac|ogg|oga|opus|wav|flac)(\?.*)?$/i.test(u)
            || (u.includes('.m3u8') && /audio/i.test(u));
    }
    window.isAudioOnly = isAudioUrl(videoUrl);
    
    // 全局变量声明
    let player = null;
    let playerReady = false;
    let progressSaveTimer = null;
    
    // 音频播放器相关变量
    let audioPlayer = null;
    let audioPlayerReady = false;
    let audioProgressTimer = null;
    let audioVisualizerTimer = null;
    let isAudioMode = false;
    
    // 等待页面完全加载
    document.addEventListener('DOMContentLoaded', function() {
        // 检查URL参数，确保剧集信息正确
        checkUrlParameters();
        
        // 额外等待一下确保所有元素都加载完成
        setTimeout(initPlayer, 100);
        
        // 延迟检查缓存，避免与用户手动选择冲突
        setTimeout(() => {
            checkAndRestorePlayback();
        }, 1000);
        
        // 延迟高亮当前剧集，确保DOM完全渲染
        setTimeout(() => {
            highlightCurrentEpisode();
            // smartScrollToCurrentEpisode(); // 注释掉定位到剧集功能
        }, 1500);
        
        // 延迟预热下一集，提高iOS自动播放成功率
        setTimeout(() => {
            warmUpNextEpisode();
        }, 2000);
        
        // 初始化自动播放按钮状态
        setTimeout(() => {
            initAutoPlayButton();
        }, 2000);
        
        // 初始化排序按钮状态
        setTimeout(() => {
            initSortButtonState();
            // 确保剧集列表是正序
            ensureEpisodeOrder();
        }, 2000);
        
        // 清理可能存在的错误缓存
        setTimeout(() => {
            clearInvalidCache();
        }, 2500);
        
        // 初始化全局剧集状态
        setTimeout(() => {
            const currentEpisode = <?= $currentEpisodeNumber ?? 1 ?>;
            updateGlobalEpisodeState(currentEpisode);
        }, 3000);
        
        // 页面卸载时清理音频播放器资源
        window.addEventListener('beforeunload', function() {
            if (audioProgressTimer) {
                clearInterval(audioProgressTimer);
            }
            if (audioVisualizerTimer) {
                clearInterval(audioVisualizerTimer);
            }
            if (audioPlayer) {
                audioPlayer.pause();
                audioPlayer.src = '';
            }
        });
    });
    
    // 检查URL参数，确保剧集信息正确
    function checkUrlParameters() {
        const urlParams = new URLSearchParams(window.location.search);
        const videoId = urlParams.get('id');
        const episode = urlParams.get('episode');
                
        // 如果有剧集参数，确保高亮显示
        if (episode) {
            const currentEpisode = parseInt(episode);
            
            // 更新页面标题显示当前剧集
            const titleElement = document.querySelector('.player-title-link');
            if (titleElement) {
                const originalTitle = titleElement.textContent;
                // 只有多集时才显示集数
                const hasMultipleEpisodes = <?= $hasEpisodes ? 'true' : 'false' ?>;
                if (hasMultipleEpisodes && !originalTitle.includes(`第${currentEpisode}集`)) {
                    titleElement.textContent = `${originalTitle} 第${currentEpisode}集`;
                }
            }
        }
    }
    
    // 播放器初始化函数
    function initPlayer() {
        
            if (!videoUrl || videoUrl === '') {
        showPlayErrorTip('播放源错误', '视频链接失效或为空，请联系管理员');
        return;
    }
        
        // 检查是否为音频文件
        if (window.isAudioOnly) {
            initAudioPlayer();
            return;
        }
        
        // 检查Video.js是否加载
        if (typeof videojs === 'undefined') {
            console.error('Video.js 未加载');
            showPlayErrorTip('播放器错误', 'Video.js播放器未加载，请刷新页面重试');
            return;
        }
        
        // 检查目标元素是否存在
        const targetElement = document.getElementById('mse');
        if (!targetElement) {
            console.error('目标元素不存在');
            return;
        }
        
        try {
            
            // 生成唯一的存储键名
            const storageKey = `video_progress_${<?= $video['id'] ?>}_${<?= $currentEpisodeNumber ?? 1 ?>}`;
            
            // 创建Video.js播放器，添加iOS兼容选项
            player = videojs('mse', {
                controls: true,
                fluid: true,
                responsive: true,
                poster: videoPoster,
                preload: 'metadata',
                playsinline: true, // iOS兼容
                webkit_playsinline: true, // iOS兼容
                playbackRates: [0.5, 0.75, 1, 1.25, 1.5, 2],
                controlBar: {
                    children: [
                        'playToggle',
                        'volumePanel',
                        'currentTimeDisplay',
                        'timeDivider',
                        'durationDisplay',
                        'progressControl',
                        'playbackRateMenuButton',
                        'pictureInPictureToggle',
                        'fullscreenToggle'
                    ]
                },
                sources: [{
                    src: videoUrl,
                    type: videoUrl.includes('.m3u8') ? 'application/x-mpegURL' : 'video/mp4'
                }]
            });
            
            
            // 播放器就绪处理
            player.ready(function() {
                
                // 延迟设置状态，确保播放器完全初始化
                setTimeout(() => {
                    // 设置全局状态
                    window.player = player;
                    window.playerReady = true;
                    playerReady = true;
                    
                    // 启动播放记录保存
                    startHistorySaving();
                    // 确保时间显示元素存在
                    const currentTime = player.controlBar.getChild('currentTimeDisplay');
                    const duration = player.controlBar.getChild('durationDisplay');
                    const timeDivider = player.controlBar.getChild('timeDivider');
                    
                    if (currentTime) currentTime.show();
                    if (duration) duration.show();
                    if (timeDivider) timeDivider.show();
                    
                    // 统一的元数据加载处理函数
                    const handleMetadataLoaded = function() {
                        // 检测是否为纯音频
                        const mediaEl = player.el().querySelector('video, audio');
                        if (mediaEl && mediaEl.videoWidth === 0 && mediaEl.videoHeight === 0) {
                            window.isAudioOnly = true;
                        }
                        if (window.isAudioOnly) {
                            enableBackgroundAudioMode();
                        }
                        
                        // 处理进度恢复
                        if (window.autoRestoreProgress) {
                            // 使用自动恢复标记
                            player.currentTime(window.autoRestoreProgress.time);
                            console.log('自动恢复进度:', window.autoRestoreProgress.time);
                            window.autoRestoreProgress = null;
                        } else {
                            // 尝试从本地存储恢复进度
                            const savedProgress = loadProgress();
                            if (savedProgress && savedProgress.time > 0) {
                                player.currentTime(savedProgress.time);
                                console.log('本地恢复进度:', savedProgress.time);
                                showProgressTip(savedProgress);
                            }
                        }
                    };
                    
                    // 监听元数据加载完成事件
                    player.one('loadedmetadata', handleMetadataLoaded);
                    
                    // 如果元数据已经加载完成，立即处理
                    if (player.readyState() >= 1) {
                        handleMetadataLoaded();
                    }
                    
                }, 500); // 延迟500ms确保播放器完全初始化
            });
            
            
                    // 错误处理
        player.on('error', function(err) {
            console.error('播放器错误:', err);
            console.error('错误详情:', player.error());
            
            // 获取具体错误信息
            const error = player.error();
            if (error) {
                let errorMessage = '';
                let errorTitle = '';
                
                switch (error.code) {
                    case 1:
                        errorTitle = '播放错误';
                        errorMessage = '视频加载失败，可能是网络问题或文件不存在';
                        break;
                    case 2:
                        errorTitle = '网络错误';
                        errorMessage = '网络连接异常，请检查网络设置';
                        break;
                    case 3:
                        errorTitle = '解码错误';
                        errorMessage = '视频格式不支持或文件损坏';
                        break;
                    case 4:
                        errorTitle = '源错误';
                        errorMessage = '视频源无法访问，请检查播放源是否正确';
                        break;
                    default:
                        errorTitle = '播放错误';
                        errorMessage = '未知错误，请刷新页面重试';
                }
                
                // 显示错误提示
                showPlayErrorTip(errorTitle, errorMessage);
            }
        });
            
            // 加载中提示
            player.on('loadstart', function() {
                // 开始加载视频
            });

            // 加载完成
            player.on('loadeddata', function() {
                // 视频加载完成
            });
            
            // 播放过程中定期保存进度（每5秒）
            player.on('timeupdate', function() {
                // 清除之前的定时器
                if (progressSaveTimer) {
                    clearTimeout(progressSaveTimer);
                }
                
                // 设置新的定时器，5秒后保存进度
                progressSaveTimer = setTimeout(() => {
                    saveProgress();
                }, 5000);
                
                // 当播放进度超过70%时，预热下一集
                if (player.duration() > 0 && player.currentTime() / player.duration() > 0.7) {
                    // 避免重复预热
                    if (!window.warmUpTriggered) {
                        window.warmUpTriggered = true;
                        warmUpNextEpisode();
                    }
                }
            });
            
            // 播放结束
            player.on('ended', function() {
                saveProgress();
                saveWatchHistory(); // 保存观看记录到数据库
                
                // 播放完成后，可以选择删除进度记录或标记为已完成
                try {
                    const progress = JSON.parse(localStorage.getItem(storageKey) || '{}');
                    progress.completed = true;
                    progress.completedAt = Date.now();
                    localStorage.setItem(storageKey, JSON.stringify(progress));
                } catch (e) {
                    console.warn('标记完成状态失败:', e);
                }
                
                // 自动播放下一集
                autoPlayNextEpisode();
            });
            
            // 页面关闭前保存进度和观看记录
            window.addEventListener('beforeunload', function() {
                saveProgress();
                saveWatchHistory(); // 保存观看记录到数据库
                
                // 清理预热播放器
                if (window.warmUpPlayer && window.warmUpPlayer.parentNode) {
                    window.warmUpPlayer.parentNode.removeChild(window.warmUpPlayer);
                    window.warmUpPlayer = null;
                }
                
                // 清理音频播放器定时器
                if (audioProgressTimer) {
                    clearInterval(audioProgressTimer);
                }
                if (audioVisualizerTimer) {
                    clearInterval(audioVisualizerTimer);
                }
            });
            
            // 页面隐藏时保存进度和观看记录（移动端切换应用时）
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    saveProgress();
                    saveWatchHistory(); // 保存观看记录到数据库
                    
                    // 页面隐藏时取消自动播放
                    if (window.autoPlayTimer) {
                        clearTimeout(window.autoPlayTimer);
                        window.autoPlayTimer = null;
                        
                        // 移除自动播放提示
                        const tip = document.querySelector('.auto-play-tip');
                        if (tip) {
                            tip.remove();
                        }
                    }
                    
                    if (isAudioMode && audioPlayerReady) {
                        // 音频模式：页面隐藏时不暂停，继续后台播放
                        // 但可以暂停可视化动画以节省资源
                        stopAudioVisualizer();
                    } else if (player && playerReady && !player.paused() && !window.isAudioOnly) {
                        // 视频模式：iOS优化，页面隐藏时暂停播放器（音频文件不暂停）
                        player.pause(); // 只有"非音频"才后台暂停；音频后台继续播
                    }
                } else {
                    // 页面重新可见时，检查是否需要恢复播放
                    if (isAudioMode && audioPlayerReady && audioPlayer.paused) {
                        // 音频模式：可以显示恢复播放提示
                        showTip('点击播放按钮继续收听');
                    } else if (player && playerReady && player.paused()) {
                        // 视频模式：显示恢复播放提示
                        showTip('点击播放按钮继续观看');
                    }
                }
            });
            
        } catch (error) {
            console.error('创建播放器时出错:', error);
            showPlayErrorTip('播放器初始化失败', '播放器创建失败: ' + error.message + '，请刷新页面重试');
        }
    }
    
            // 音频播放器初始化函数
        function initAudioPlayer() {
            try {
                // 切换到音频模式
                isAudioMode = true;
                
                // 隐藏视频播放器，显示音频播放器
                const videoPlayer = document.querySelector('.video-player');
                const audioPlayerContainer = document.getElementById('audioPlayer');
                
                if (videoPlayer) {
                    videoPlayer.classList.add('video-player-hidden');
                }
                
                if (audioPlayerContainer) {
                    audioPlayerContainer.classList.add('active');
                }
                
                // 更新音频播放器信息
                updateAudioPlayerInfo();
                
                // 创建HTML5音频元素
                audioPlayer = new Audio();
                audioPlayer.src = videoUrl;
                audioPlayer.preload = 'metadata';
                audioPlayer.volume = 1; // 设置默认音量为80%
                
                // 音频事件监听
                audioPlayer.addEventListener('loadedmetadata', function() {
                    audioPlayerReady = true;
                    updateAudioDuration();
                    startAudioProgressUpdate();
                    startAudioVisualizer();
                    
                    // 恢复播放进度
                    const savedProgress = loadProgress();
                    if (savedProgress && savedProgress.time > 0) {
                        audioPlayer.currentTime = savedProgress.time;
                        updateAudioProgress(savedProgress.time, savedProgress.duration);
                    }
                });
                
                audioPlayer.addEventListener('play', function() {
                    updatePlayPauseButton(true);
                    startAudioVisualizer();
                });
                
                audioPlayer.addEventListener('pause', function() {
                    updatePlayPauseButton(false);
                    stopAudioVisualizer();
                });
                
                audioPlayer.addEventListener('ended', function() {
                    updatePlayPauseButton(false);
                    stopAudioVisualizer();
                    saveProgress();
                    saveWatchHistory(); // 保存观看记录到数据库
                    autoPlayNextEpisode();
                });
                
                audioPlayer.addEventListener('error', function(e) {
                    console.error('音频播放错误:', e);
                    
                    // 获取具体错误信息
                    let errorMessage = '音频加载失败，请检查文件或网络';
                    let errorTitle = '音频播放错误';
                    
                    // 检查错误类型
                    if (e.target.error) {
                        switch (e.target.error.code) {
                            case MediaError.MEDIA_ERR_ABORTED:
                                errorMessage = '音频播放被中断';
                                break;
                            case MediaError.MEDIA_ERR_NETWORK:
                                errorMessage = '网络错误，音频无法加载';
                                break;
                            case MediaError.MEDIA_ERR_DECODE:
                                errorMessage = '音频格式不支持或文件损坏';
                                break;
                            case MediaError.MEDIA_ERR_SRC_NOT_SUPPORTED:
                                errorMessage = '音频源无法访问，可能是404错误';
                                break;
                            default:
                                errorMessage = '音频播放失败，请检查文件或网络';
                        }
                    }
                    
                    // 显示错误提示
                    showPlayErrorTip(errorTitle, errorMessage);
                });
                
                // 绑定控制按钮事件
                bindAudioControls();
                
                // 启用后台播放模式
                enableBackgroundAudioMode();
                
                // 启动播放记录保存
                startHistorySaving();
                
                console.log('音频播放器初始化完成');
                
            } catch (error) {
                console.error('音频播放器初始化失败:', error);
                showPlayErrorTip('初始化错误', '音频播放器初始化失败');
            }
        }
    
    // 更新音频播放器信息
    function updateAudioPlayerInfo(episodeNumber) {
        const audioTitle = document.getElementById('audioTitle');
        const audioArtist = document.getElementById('audioArtist');
        const audioAlbum = document.getElementById('audioAlbum');
        const audioCover = document.getElementById('audioCover');
        
        if (audioTitle) {
            // 获取当前剧集号，如果没有传入参数则使用默认值
            const currentEpisode = episodeNumber || <?= $currentEpisodeNumber ?? 1 ?>;
            // 只有多集时才显示集数
            const hasMultipleEpisodes = <?= $hasEpisodes ? 'true' : 'false' ?>;
            let titleText;
            if (hasMultipleEpisodes) {
                titleText = (videoTitle || '未知音频') + ' 第' + currentEpisode + '集';
            } else {
                titleText = (videoTitle || '未知音频');
            }
            audioTitle.textContent = titleText;
        }
        
        if (audioArtist) {
            audioArtist.textContent = '星海影院';
        }
        
        if (audioAlbum) {
            audioAlbum.textContent = '音频播放';
        }
        
        if (audioCover && videoPoster) {
            audioCover.src = videoPoster;
        }
    }
    
    // 绑定音频控制按钮事件
    function bindAudioControls() {
        const playPauseBtn = document.getElementById('audioPlayPause');
        const prevBtn = document.getElementById('audioPrev');
        const nextBtn = document.getElementById('audioNext');
        const progressBar = document.getElementById('audioProgress');

        
        // 播放/暂停按钮
        if (playPauseBtn) {
            playPauseBtn.addEventListener('click', function() {
                if (audioPlayerReady) {
                    if (audioPlayer.paused) {
                        audioPlayer.play().catch(e => {
                            console.error('播放失败:', e);
                            showTip('播放失败，请点击播放按钮重试');
                        });
                    } else {
                        audioPlayer.pause();
                    }
                }
            });
        }
        
        // 上一首按钮
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                // 这里可以实现上一首逻辑，暂时显示提示
                showTip('上一首功能开发中');
            });
        }
        
        // 下一首按钮
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                // 这里可以实现下一首逻辑，暂时显示提示
                showTip('下一首功能开发中');
            });
        }
        
        // 进度条点击
        if (progressBar) {
            progressBar.addEventListener('click', function(e) {
                if (audioPlayerReady && audioPlayer.duration) {
                    const rect = progressBar.getBoundingClientRect();
                    const clickX = e.clientX - rect.left;
                    const clickPercent = clickX / rect.width;
                    const newTime = clickPercent * audioPlayer.duration;
                    audioPlayer.currentTime = newTime;
                    updateAudioProgress(newTime, audioPlayer.duration);
                }
            });
        }
        

    }
    
    // 更新播放/暂停按钮状态
    function updatePlayPauseButton(isPlaying) {
        const playPauseBtn = document.getElementById('audioPlayPause');
        if (playPauseBtn) {
            if (isPlaying) {
                playPauseBtn.innerHTML = '<i class="fa">&#xe592;</i>';
                playPauseBtn.title = '暂停';
            } else {
                playPauseBtn.innerHTML = '<i class="fa">&#xe593;</i>';
                playPauseBtn.title = '播放';
            }
        }
    }
    
    // 更新音频时长显示
    function updateAudioDuration() {
        const durationSpan = document.getElementById('audioDuration');
        if (durationSpan && audioPlayer.duration) {
            durationSpan.textContent = formatTime(audioPlayer.duration);
        }
    }
    
    // 开始音频进度更新
    function startAudioProgressUpdate() {
        if (audioProgressTimer) {
            clearInterval(audioProgressTimer);
        }
        
        audioProgressTimer = setInterval(() => {
            if (audioPlayerReady && !audioPlayer.paused) {
                updateAudioProgress(audioPlayer.currentTime, audioPlayer.duration);
                
                // 保存进度
                saveProgress();
            }
        }, 1000);
    }
    
    // 更新音频进度显示
    function updateAudioProgress(currentTime, duration) {
        const progressBar = document.getElementById('audioProgressBar');
        const currentTimeSpan = document.getElementById('audioCurrentTime');
        
        if (progressBar && duration > 0) {
            const progressPercent = (currentTime / duration) * 100;
            progressBar.style.width = progressPercent + '%';
        }
        
        if (currentTimeSpan) {
            currentTimeSpan.textContent = formatTime(currentTime);
        }
    }
    
    // 开始音频可视化
    function startAudioVisualizer() {
        if (audioVisualizerTimer) {
            clearInterval(audioVisualizerTimer);
        }
        
        audioVisualizerTimer = setInterval(() => {
            if (audioPlayerReady && !audioPlayer.paused) {
                animateAudioVisualizer();
            }
        }, 100);
    }
    
    // 停止音频可视化
    function stopAudioVisualizer() {
        if (audioVisualizerTimer) {
            clearInterval(audioVisualizerTimer);
            audioVisualizerTimer = null;
        }
        
        // 重置可视化条
        const bars = document.querySelectorAll('.audio-bar');
        bars.forEach(bar => {
            bar.style.height = '20px';
        });
    }
    
    // 动画音频可视化
    function animateAudioVisualizer() {
        const bars = document.querySelectorAll('.audio-bar');
        bars.forEach((bar, index) => {
            // 根据播放状态生成随机高度
            const baseHeight = 20;
            const maxHeight = 60;
            const randomHeight = baseHeight + Math.random() * (maxHeight - baseHeight);
            
            // 添加一些随机性，让动画更自然
            const delay = index * 0.1;
            setTimeout(() => {
                bar.style.height = randomHeight + 'px';
            }, delay);
        });
    }
    
    // 格式化时间显示
    function formatTime(seconds) {
        if (isNaN(seconds)) return '0:00';
        
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    }
    
    // 剧集排序切换（正序/倒序）
    function toggleEpisodeOrder() {
        const episodeList = document.querySelector('.anthology-list-play');
        if (!episodeList) {
            console.error('剧集列表元素未找到');
            showTip('剧集列表加载失败');
            return;
        }
        
        const episodes = Array.from(episodeList.children);
        if (episodes.length === 0) {
            console.error('没有找到剧集元素');
            showTip('没有找到剧集');
            return;
        }
        
        const button = document.getElementById('zxdaoxu');
        if (!button) {
            console.error('排序按钮未找到');
            showTip('排序按钮加载失败');
            return;
        }
        
        // 检查当前排序状态（优先使用localStorage，其次使用CSS类）
        let isReversed = false;
        
        // 尝试从localStorage获取排序状态
        try {
            const sortState = localStorage.getItem(`episode_sort_${<?= $video['id'] ?>}`);
            if (sortState) {
                isReversed = JSON.parse(sortState);
            } else {
                // 如果localStorage没有记录，默认为正序（false）
                isReversed = false;
                // 立即保存默认状态到localStorage
                try {
                    localStorage.setItem(`episode_sort_${<?= $video['id'] ?>}`, 'false');
                } catch (e2) {
                    console.warn('保存默认状态失败:', e2);
                }
            }
        } catch (e) {
            // 如果localStorage读取失败，默认为正序
            isReversed = false;
            console.warn('localStorage读取失败，使用默认正序状态');
        }
        
        if (isReversed) {
            // 当前是倒序，切换到正序
            // 按剧集号正序排列
            episodes.sort((a, b) => {
                const aNum = parseInt(a.getAttribute('data-episode')) || 0;
                const bNum = parseInt(b.getAttribute('data-episode')) || 0;
                return aNum - bNum;
            });
            
            episodeList.classList.remove('reversed');
            button.innerHTML = '<i class="fa r3">&#xe557;</i>倒序';
            showTip('已切换到正序排列');
            
            // 保存状态到localStorage
            try {
                localStorage.setItem(`episode_sort_${<?= $video['id'] ?>}`, 'false');
            } catch (e) {
                console.warn('保存排序状态失败:', e);
            }
        } else {
            // 当前是正序，切换到倒序
            // 按剧集号倒序排列
            episodes.sort((a, b) => {
                const aNum = parseInt(a.getAttribute('data-episode')) || 0;
                const bNum = parseInt(b.getAttribute('data-episode')) || 0;
                return bNum - aNum;
            });
            
            episodeList.classList.add('reversed');
            button.innerHTML = '<i class="fa r3">&#xe557;</i>正序';
            showTip('已切换到倒序排列');
            
            // 保存状态到localStorage
            try {
                localStorage.setItem(`episode_sort_${<?= $video['id'] ?>}`, 'true');
            } catch (e) {
                console.warn('保存排序状态失败:', e);
            }
        }
        
        // 重新挂载 DOM 元素
        episodes.forEach(episode => episodeList.appendChild(episode));
        
        // 重新高亮当前剧集
        highlightCurrentEpisode();
        
        // 智能滚动到当前剧集位置
        // smartScrollToCurrentEpisode(); // 注释掉定位到剧集功能
    }
    
    // 确保剧集列表是正序排列
    function ensureEpisodeOrder() {
        const episodeList = document.querySelector('.anthology-list-play');
        if (!episodeList) return;
        
        // 检查当前是否已经是正序
        const episodes = Array.from(episodeList.children);
        if (episodes.length <= 1) return;
        
        // 检查前两个剧集是否按正序排列
        const firstEpisode = parseInt(episodes[0].getAttribute('data-episode')) || 0;
        const secondEpisode = parseInt(episodes[1].getAttribute('data-episode')) || 0;
        
        if (firstEpisode > secondEpisode) {
            // 重新排序为正序
            episodes.sort((a, b) => {
                const aNum = parseInt(a.getAttribute('data-episode')) || 0;
                const bNum = parseInt(b.getAttribute('data-episode')) || 0;
                return aNum - bNum;
            });
            
            // 重新挂载
            episodes.forEach(episode => episodeList.appendChild(episode));
        }
    }
    
    // 初始化排序按钮状态
    function initSortButtonState() {
        const episodeList = document.querySelector('.anthology-list-play');
        const button = document.getElementById('zxdaoxu');
        
        if (!episodeList || !button) {
            return;
        }
        
        // 从localStorage获取排序状态
        try {
            const sortState = localStorage.getItem(`episode_sort_${<?= $video['id'] ?>}`);
            
            if (sortState) {
                const isReversed = JSON.parse(sortState);
                
                if (isReversed) {
                    episodeList.classList.add('reversed');
                    button.innerHTML = '<i class="fa r3">&#xe557;</i>正序';
                } else {
                    episodeList.classList.remove('reversed');
                    button.innerHTML = '<i class="fa r3">&#xe557;</i>倒序';
                }
            } else {
                // 确保默认是正序状态
                episodeList.classList.remove('reversed');
                button.innerHTML = '<i class="fa r3">&#xe557;</i>倒序';
                
                // 将默认正序状态保存到localStorage
                try {
                    localStorage.setItem(`episode_sort_${<?= $video['id'] ?>}`, 'false');
                } catch (e) {
                    console.warn('保存默认状态失败:', e);
                }
            }
        } catch (e) {
            console.warn('初始化排序按钮状态失败:', e);
            // 出错时使用默认状态
            episodeList.classList.remove('reversed');
            button.innerHTML = '<i class="fa r3">&#xe557;</i>倒序';
            
            // 尝试保存默认状态
            try {
                localStorage.setItem(`episode_sort_${<?= $video['id'] ?>}`, 'false');
            } catch (e2) {
                console.warn('保存默认状态失败:', e2);
            }
        }
    }
    
    // 剧集布局切换（单列/多列）
    function toggleEpisodeLayout() {
        const episodeList = document.querySelector('.anthology-list-play');
        const button = document.querySelector('.player-button-ac');
        
        if (episodeList.classList.contains('single-column')) {
            // 切换到多列
            episodeList.classList.remove('single-column');
            button.innerHTML = '<i class="fa r3">&#xe553;</i>单列';
            showTip('已切换到多列布局');
        } else {
            // 切换到单列
            episodeList.classList.add('single-column');
            button.innerHTML = '<i class="fa r3">&#xe553;</i>多列';
            showTip('已切换到单列布局');
        }
    }
    

    
    // 显示禁用剧集提示
    function showDisabledEpisodeTip() {
        showTip('该剧集暂无片源，请选择其他剧集');
    }

    // 显示提示信息
    function showTip(message) {
        const tip = document.createElement('div');
        tip.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            z-index: 10000;
            animation: tipFadeInOut 2s ease-in-out;
        `;
        tip.innerHTML = message;
        
        // 添加动画样式
        if (!document.querySelector('#tip-style')) {
            const style = document.createElement('style');
            style.id = 'tip-style';
            style.textContent = `
                @keyframes tipFadeInOut {
                    0% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
                    20% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
                    80% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
                    100% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
                }
            `;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(tip);
        
        // 2秒后自动移除
        setTimeout(() => {
            if (tip.parentNode) {
                tip.parentNode.removeChild(tip);
            }
        }, 2000);
    }
    
    // 显示播放错误提示（更醒目的错误提示）
    function showPlayErrorTip(title, message) {
        // 移除已存在的错误提示
        const existingError = document.querySelector('.play-error-tip');
        if (existingError) {
            existingError.remove();
        }
        
        const errorTip = document.createElement('div');
        errorTip.className = 'play-error-tip';
        errorTip.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 25px 30px;
            border-radius: 12px;
            font-size: 16px;
            z-index: 10001;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 8px 32px rgba(231, 76, 60, 0.4);
            border: 2px solid rgba(255, 255, 255, 0.2);
            animation: errorTipSlideIn 0.4s ease-out;
        `;
        
        errorTip.innerHTML = `
            <div style="margin-bottom: 15px;">
                <i class="fa" style="color: #fff; font-size: 32px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">&#xe595;</i>
            </div>
            <div style="margin-bottom: 20px;">
                <h3 style="margin: 0 0 10px 0; font-size: 18px; font-weight: 600; color: #fff;">${title}</h3>
                <p style="margin: 0; font-size: 14px; color: rgba(255,255,255,0.9); line-height: 1.4;">${message}</p>
            </div>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="retryPlayback()" style="
                    background: rgba(255,255,255,0.2);
                    color: white;
                    border: 1px solid rgba(255,255,255,0.3);
                    padding: 10px 20px;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 14px;
                    transition: all 0.3s ease;
                    flex: 1;
                " onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    重试播放
                </button>
                <button onclick="this.parentElement.parentElement.remove()" style="
                    background: rgba(255,255,255,0.1);
                    color: white;
                    border: 1px solid rgba(255,255,255,0.2);
                    padding: 10px 20px;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 14px;
                    transition: all 0.3s ease;
                    flex: 1;
                " onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                    关闭
                </button>
            </div>
        `;
        
        // 添加动画样式
        if (!document.querySelector('#error-tip-style')) {
            const style = document.createElement('style');
            style.id = 'error-tip-style';
            style.textContent = `
                @keyframes errorTipSlideIn {
                    0% { opacity: 0; transform: translate(-50%, -50%) scale(0.8) translateY(-20px); }
                    100% { opacity: 1; transform: translate(-50%, -50%) scale(1) translateY(0); }
                }
                
                .play-error-tip {
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                }
            `;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(errorTip);
        
        // 10秒后自动移除
        setTimeout(() => {
            if (errorTip.parentNode) {
                errorTip.parentNode.removeChild(errorTip);
            }
        }, 10000);
    }
    
    // 重试播放功能
    function retryPlayback() {
        // 移除错误提示
        const errorTip = document.querySelector('.play-error-tip');
        if (errorTip) {
            errorTip.remove();
        }
        
        if (isAudioMode && audioPlayerReady) {
            // 音频模式重试
            try {
                audioPlayer.load();
                audioPlayer.play().catch(e => {
                    console.error('重试播放失败:', e);
                    showPlayErrorTip('重试失败', '播放器重试失败，请刷新页面');
                });
            } catch (e) {
                console.error('音频重试失败:', e);
                showPlayErrorTip('重试失败', '音频播放器重试失败');
            }
        } else if (player && playerReady) {
            // 视频模式重试
            try {
                player.load();
                player.play().catch(e => {
                    console.error('重试播放失败:', e);
                    showPlayErrorTip('重试失败', '播放器重试失败，请刷新页面');
                });
            } catch (e) {
                console.error('视频重试失败:', e);
                showPlayErrorTip('重试失败', '视频播放器重试失败');
            }
        } else {
            // 播放器未就绪，刷新页面
            showTip('正在刷新页面...');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    }
    
    // 缓存管理函数 - 同时保存到本地存储和数据库
    function saveToHistory() {
        try {
            const historyData = {
                videoId: <?= $video['id'] ?>,
                title: "<?= $this->escape($video['title']) ?>",
                episode: <?= $currentEpisodeNumber ?? 1 ?>,
                episodeTitle: "<?= $this->escape($currentEpisode['title'] ?? '') ?>",
                poster: "<?= $this->escape($video['poster'] ?? '') ?>",
                timestamp: Date.now(),
                time: 0,
                duration: 0
            };
            
            // 获取当前播放进度
            if (isAudioMode && audioPlayerReady) {
                historyData.time = Math.floor(audioPlayer.currentTime || 0);
                historyData.duration = Math.floor(audioPlayer.duration || 0);
            } else if (player && playerReady) {
                historyData.time = Math.floor(player.currentTime() || 0);
                historyData.duration = Math.floor(player.duration() || 0);
            }
            
            // 如果播放器还没准备好，尝试从进度条获取
            if (historyData.time === 0) {
                const progressBar = document.getElementById('audioProgressBar');
                if (progressBar) {
                    const progress = progressBar.style.width;
                    if (progress && progress !== '0%') {
                        // 从进度条宽度估算播放时间
                        const progressPercent = parseFloat(progress) / 100;
                        historyData.time = Math.floor(progressPercent * 60); // 假设视频60分钟
                    }
                }
            }
            
            // 保存到本地存储
            let history = JSON.parse(localStorage.getItem('video_watch_history') || '[]');
            
            // 移除重复记录
            history = history.filter(item => 
                !(item.videoId == historyData.videoId && item.episode == historyData.episode)
            );
            
            // 添加到开头
            historyData.time = Math.max(historyData.time, 1); // 确保至少有1秒
            history.unshift(historyData);
            
            // 限制历史记录数量
            if (history.length > 50) {
                history = history.slice(0, 50);
            }
            
            localStorage.setItem('video_watch_history', JSON.stringify(history));
            
            // 保存最后播放的视频信息
            localStorage.setItem('last_video', JSON.stringify({
                videoId: historyData.videoId,
                episode: historyData.episode,
                timestamp: Date.now()
            }));
            
            // 同时保存到数据库（如果用户已登录）
            saveWatchHistory();
            
            console.log('保存播放记录:', historyData); // 调试用
            
        } catch (e) {
            console.warn('保存历史记录失败:', e);
        }
    }
    
    // 保存观看记录到数据库
    function saveWatchHistory() {
        // 检查用户是否已登录（通过检查是否有用户会话）
        if (!isUserLoggedIn()) {
            console.log('用户未登录，跳过数据库保存');
            return;
        }
        
        try {
            // 获取当前播放进度
            let currentTime = 0;
            let duration = 0;
            
            if (isAudioMode && audioPlayerReady) {
                currentTime = Math.floor(audioPlayer.currentTime || 0);
                duration = Math.floor(audioPlayer.duration || 0);
            } else if (player && playerReady) {
                currentTime = Math.floor(player.currentTime() || 0);
                duration = Math.floor(player.duration() || 0);
            }
            
            // 如果播放时间太短，不保存
            if (currentTime < 5) {
                console.log('播放时间太短，不保存观看记录');
                return;
            }
            
            // 准备数据
            const formData = new FormData();
            formData.append('video_id', <?= $video['id'] ?>);
            formData.append('episode', <?= $currentEpisodeNumber ?? 1 ?>);
            formData.append('progress', currentTime);
            
            // 发送到后端API
            fetch('/api/user.php?action=update_watch_history', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('观看记录保存成功:', data);
                } else {
                    console.warn('观看记录保存失败:', data.message);
                }
            })
            .catch(error => {
                console.error('保存观看记录时网络错误:', error);
            });
            
        } catch (error) {
            console.error('保存观看记录失败:', error);
        }
    }
    
    // 检查用户是否已登录
    function isUserLoggedIn() {
        try {
            // 方法1：检查页面中是否有用户信息元素（最可靠）
            const userInfoElement = document.querySelector('.user-center .content-center em');
            if (userInfoElement && userInfoElement.textContent !== '登录') {
                return true;
            }
            
            // 方法2：检查是否有用户下拉菜单
            const userDropdown = document.querySelector('.user-dropdown');
            if (userDropdown && userDropdown.style.display !== 'none') {
                return true;
            }
            
            // 方法3：检查页面中是否有用户名显示（非"登录"文本）
            const usernameElements = document.querySelectorAll('em');
            for (let element of usernameElements) {
                if (element.textContent && element.textContent !== '登录' && element.textContent.length > 1) {
                    return true;
                }
            }
            
            // 方法4：检查cookie中的用户token
            const cookies = document.cookie.split(';');
            for (let cookie of cookies) {
                const [name, value] = cookie.trim().split('=');
                if (name === 'user_token' && value && value !== '') {
                    return true;
                }
            }
            
            return false;
        } catch (error) {
            console.warn('检查登录状态失败:', error);
            return false;
        }
    }
    
    // 检查并恢复播放记录
    function checkAndRestorePlayback() {
        try {
            const lastVideo = JSON.parse(localStorage.getItem('last_video') || '{}');
            
            // 检查是否是同一个视频
            if (lastVideo.videoId == <?= $video['id'] ?>) {
                // 如果当前剧集和上次观看的剧集不同，且用户没有手动选择，则自动跳转
                if (lastVideo.episode != <?= $currentEpisodeNumber ?? 1 ?>) {
                    // 检查URL中是否有episode参数，如果有说明用户手动选择了剧集
                    const urlParams = new URLSearchParams(window.location.search);
                    const hasEpisodeParam = urlParams.has('episode');
                    
                    if (!hasEpisodeParam) {
                        // 自动跳转到上次观看的剧集
                        window.location.href = `?page=play&id=<?= $video['id'] ?>&episode=${lastVideo.episode}`;
                        return;
                    }
                }
                
                // 如果是同一个剧集，自动恢复播放进度（不显示提示）
                const progress = loadProgress();
                if (progress && progress.time > 0) {
                    // 等待播放器就绪后自动恢复进度
                    if (player && playerReady) {
                        player.currentTime(progress.time);
                        // 不自动播放，只恢复进度，避免被浏览器阻止
                        // player.play();
                    } else {
                        // 如果播放器还没就绪，设置一个标记
                        window.autoRestoreProgress = progress;
                    }
                }
            }
        } catch (e) {
            console.warn('检查播放记录失败:', e);
        }
    }
    
    // 高亮显示当前播放的剧集
    function highlightCurrentEpisode() {
        const episodeList = document.querySelector('.anthology-list-play');
        if (!episodeList) return;
        
        // 优先使用全局状态，如果没有则使用PHP传递的值
        const currentEpisode = window.currentEpisodeNumber || <?= $currentEpisodeNumber ?? 1 ?>;
        const episodeItems = episodeList.querySelectorAll('li');
        
        // 检查是否有剧集数据
        const hasEpisodes = <?= $hasEpisodes ? 'true' : 'false' ?>;
        
        episodeItems.forEach((item) => {
            // 移除所有高亮
            item.classList.remove('on', 'ecnav-dt');
            
            if (hasEpisodes) {
                // 有剧集时的处理逻辑
                const episodeNumber = parseInt(item.getAttribute('data-episode'));
                const isCurrentEpisode = episodeNumber === currentEpisode;
                const hasValidLink = item.getAttribute('data-has-link') === '1';
                
                // 获取剧集项内的链接元素
                const linkElement = item.querySelector('a');
                if (linkElement) {
                    if (isCurrentEpisode) {
                        if (hasValidLink) {
                            // 当前剧集且有链接：显示播放按钮动画
                            linkElement.innerHTML = '<em class="play-on"><i></i><i></i><i></i><i></i></em>';
                            item.classList.add('on', 'ecnav-dt');
                        } else {
                            // 当前剧集但无链接：显示禁用状态的播放按钮
                            linkElement.innerHTML = '<em class="play-on disabled"><i></i><i></i><i></i><i></i></em>';
                            item.classList.add('on', 'ecnav-dt', 'disabled');
                        }
                        
                        // 滚动到当前剧集位置
                        /*
                        setTimeout(() => {
                            item.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'center',
                                inline: 'center'
                            });
                        }, 500);
                        */
                    } else {
                        // 其他剧集：显示剧集文字
                        linkElement.innerHTML = `<span>第${String(episodeNumber).padStart(2, '0')}集</span>`;
                        
                        // 如果没有链接，添加禁用状态
                        if (!hasValidLink) {
                            item.classList.add('disabled');
                        }
                    }
                }
            } else {
                // 无剧集时，确保播放按钮正确显示
                const linkElement = item.querySelector('a');
                if (linkElement) {
                    linkElement.innerHTML = '<em class="play-on"><i></i><i></i><i></i><i></i></em>';
                    item.classList.add('on', 'ecnav-dt');
                }
            }
        });
    }
    
    // 智能定位到当前剧集（考虑排序状态）
    /*
    function smartScrollToCurrentEpisode() {
        const episodeList = document.querySelector('.anthology-list-play');
        if (!episodeList) return;
        
        const currentEpisode = <?= $currentEpisodeNumber ?? 1 ?>;
        const episodeItems = episodeList.querySelectorAll('li');
        
        // 找到当前剧集的位置
        let currentItem = null;
        let currentIndex = -1;
        
        episodeItems.forEach((item, index) => {
            const episodeNumber = parseInt(item.getAttribute('data-episode'));
            if (episodeNumber === currentEpisode) {
                currentItem = item;
                currentIndex = index;
            }
        });
        
        if (currentItem) {
            // 计算滚动位置，确保当前剧集在可视区域中心
            const container = episodeList.closest('.anthology-list');
            if (container) {
                const itemHeight = currentItem.offsetHeight;
                const containerHeight = container.offsetHeight;
                const scrollTop = currentItem.offsetTop - (containerHeight / 2) + (itemHeight / 2);
                
                // 平滑滚动到目标位置
                container.scrollTo({
                    top: Math.max(0, scrollTop),
                    behavior: 'smooth'
                });
            }
        }
    }
    */
    
    // 自动播放下一集
    function autoPlayNextEpisode() {
        // 检查用户是否启用了自动播放
        if (!isAutoPlayEnabled()) {
            return;
        }
        
        // 检查是否有剧集
        <?php if ($hasEpisodes): ?>
        // 优先使用全局状态，如果没有则使用PHP传递的值
        const currentEpisode = window.currentEpisodeNumber || <?= $currentEpisodeNumber ?? 1 ?>;
        const episodes = <?= json_encode($episodes) ?>;
        
        // 智能查找下一集（处理剧集缺失的情况）
        const nextEpisode = findNextValidEpisode(currentEpisode, episodes);
        
        if (nextEpisode) {
            // 显示自动播放提示
            // showAutoPlayTip(nextEpisode);
            
            // 延迟3秒后自动切换，给用户时间取消
            const autoPlayTimer = setTimeout(() => {
                // 使用无刷新方式切换剧集
                switchEpisodeWithoutRefresh(nextEpisode);
            }, 500);
            
            // 保存定时器ID，以便用户可以取消
            window.autoPlayTimer = autoPlayTimer;
        } else {
            // 没有找到下一集，显示播放完成提示
            showTip('已经是最后一集了');
        }
        <?php else: ?>
        // 没有剧集，显示播放完成提示
        showTip('播放完成');
        <?php endif; ?>
    }
    
    // 高级功能：预热下一集（提高iOS自动播放成功率）
    function warmUpNextEpisode() {
        if (!isAutoPlayEnabled()) return;
        
        <?php if ($hasEpisodes): ?>
        const currentEpisode = window.currentEpisodeNumber || <?= $currentEpisodeNumber ?? 1 ?>;
        const episodes = <?= json_encode($episodes) ?>;
        const nextEpisode = findNextValidEpisode(currentEpisode, episodes);
        
        if (nextEpisode) {
            const targetEpisode = episodes.find(ep => ep.episode_number == nextEpisode);
            if (targetEpisode && targetEpisode.video_path) {
                // 根据设备类型选择预热策略
                const strategy = getIOSAutoplayStrategy();
                
                if (strategy === 'muted-first') {
                    // iOS/Safari策略：使用静音预热
                    warmUpWithMutedPlayer(targetEpisode);
                } else {
                    // 标准策略：只预加载不播放
                    warmUpWithPreload(targetEpisode);
                }
            }
        }
        <?php endif; ?>
    }
    
    // iOS/Safari静音预热策略
    function warmUpWithMutedPlayer(targetEpisode) {
        // 创建隐藏的预热播放器
        const prePlayer = document.createElement('video');
        prePlayer.playsInline = true;
        prePlayer.setAttribute('webkit-playsinline', '');
        prePlayer.muted = true;
        prePlayer.preload = 'auto';
        prePlayer.style.cssText = `
            position: fixed;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
            z-index: -1;
        `;
        
        // 构建视频URL
        let videoUrl = targetEpisode.video_path;
        if (videoUrl && videoUrl.indexOf('http') !== 0) {
            videoUrl = videoUrl.replace('/public/', '/');
            if (videoUrl.indexOf('/') !== 0) {
                videoUrl = '/' + videoUrl;
            }
        }
        
        prePlayer.src = videoUrl;
        document.body.appendChild(prePlayer);
        
        // 尝试静音播放（iOS允许静音自动播放）
        prePlayer.play().catch(() => {
            // 预热失败也没关系，不影响主播放器
        });
        
        // 保存预热播放器引用，以便后续清理
        window.warmUpPlayer = prePlayer;
        
        // 5秒后自动清理预热播放器
        setTimeout(() => {
            if (window.warmUpPlayer && window.warmUpPlayer.parentNode) {
                window.warmUpPlayer.parentNode.removeChild(window.warmUpPlayer);
                window.warmUpPlayer = null;
            }
        }, 5000);
    }
    
    // 标准预加载策略
    function warmUpWithPreload(targetEpisode) {
        // 只预加载，不播放
        const link = document.createElement('link');
        link.rel = 'preload';
        link.as = 'video';
        
        let videoUrl = targetEpisode.video_path;
        if (videoUrl && videoUrl.indexOf('http') !== 0) {
            videoUrl = videoUrl.replace('/public/', '/');
            if (videoUrl.indexOf('/') !== 0) {
                videoUrl = '/' + videoUrl;
            }
        }
        
        link.href = videoUrl;
        document.head.appendChild(link);
        
        // 10秒后清理预加载链接
        setTimeout(() => {
            if (link.parentNode) {
                link.parentNode.removeChild(link);
            }
        }, 10000);
    }
    
    // 智能查找下一集（处理剧集缺失的情况）
    function findNextValidEpisode(currentEpisode, episodes) {
        // 确保当前剧集是数字类型
        const currentEpisodeNum = parseInt(currentEpisode);
        
        // 按剧集序号排序，确保类型一致
        const sortedEpisodes = episodes.sort((a, b) => {
            const aNum = parseInt(a.episode_number);
            const bNum = parseInt(b.episode_number);
            return aNum - bNum;
        });
        
        // 找到当前剧集在排序后的位置（使用数字比较）
        const currentIndex = sortedEpisodes.findIndex(ep => parseInt(ep.episode_number) === currentEpisodeNum);
        
        if (currentIndex === -1) {
            // 尝试字符串匹配
            const stringIndex = sortedEpisodes.findIndex(ep => ep.episode_number == currentEpisode);
            
            if (stringIndex === -1) {
                return null;
            }
            
            // 使用字符串匹配的结果
            const actualIndex = stringIndex;
            
            if (actualIndex >= sortedEpisodes.length - 1) {
                return null;
            }
            
            // 查找下一个有效的剧集（跳过无链接的剧集）
            for (let i = actualIndex + 1; i < sortedEpisodes.length; i++) {
                const nextEpisode = sortedEpisodes[i];
                
                // 检查剧集是否有有效的视频源
                if (nextEpisode.video_path && nextEpisode.video_path.trim() !== '') {
                    return parseInt(nextEpisode.episode_number);
                }
            }
        } else {
            // 数字匹配成功
            if (currentIndex >= sortedEpisodes.length - 1) {
                return null;
            }
            
            // 查找下一个有效的剧集（跳过无链接的剧集）
            for (let i = currentIndex + 1; i < sortedEpisodes.length; i++) {
                const nextEpisode = sortedEpisodes[i];
                
                // 检查剧集是否有有效的视频源
                if (nextEpisode.video_path && nextEpisode.video_path.trim() !== '') {
                    return parseInt(nextEpisode.episode_number);
                }
            }
        }
        
        return null; // 没有找到有效的下一集
    }
    
    // 清理错误的缓存数据
    function clearInvalidCache() {
        try {
            // 清理可能错误的自动播放缓存
            if (window.autoPlayTimer) {
                clearTimeout(window.autoPlayTimer);
                window.autoPlayTimer = null;
            }
            
            // 移除自动播放提示
            const tip = document.querySelector('.auto-play-tip');
            if (tip) {
                tip.remove();
            }
            
            // 清理播放器错误状态
            if (player && player.error()) {
                player.error(null);
            }
        } catch (e) {
            console.warn('清理缓存失败:', e);
        }
    }
    
    // 显示自动播放提示
    function showAutoPlayTip(nextEpisode) {
        // 移除已存在的提示
        const existingTip = document.querySelector('.auto-play-tip');
        if (existingTip) {
            existingTip.remove();
        }
        
        const tip = document.createElement('div');
        tip.className = 'auto-play-tip';
        tip.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.95);
            color: white;
            padding: 15px 25px;
            border-radius: 12px;
            font-size: 13px;
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            animation: slideDown 0.3s ease-out;
            border: 1px solid rgba(255,255,255,0.1);
            min-width: 300px;
            justify-content: space-between;
        `;
        
        tip.innerHTML = `
            <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                <i class="fa" style="color: #4ecdc4; font-size: 14px; margin-right: 3px;">&#xe557;</i>
                <span style="font-weight: 500; line-height: 1.2; white-space: nowrap;">3秒后自动切换到第${nextEpisode}集</span>
            </div>
            <button onclick="cancelAutoPlay()" style="
                background: linear-gradient(135deg, #ff6b6b, #ff5252);
                color: white;
                border: none;
                padding: 8px 15px;
                border-radius: 6px;
                cursor: pointer;
                font-size: 12px;
                font-weight: 500;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(255,107,107,0.3);
                white-space: nowrap;
                flex-shrink: 0;
            " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(255,107,107,0.4)'" 
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(255,107,107,0.3)'">
                取消
            </button>
        `;
        
        // 添加动画样式
        if (!document.querySelector('#auto-play-tip-style')) {
            const style = document.createElement('style');
            style.id = 'auto-play-tip-style';
            style.textContent = `
                @keyframes slideDown {
                    0% { opacity: 0; transform: translateX(-50%) translateY(-30px) scale(0.95); }
                    100% { opacity: 1; transform: translateX(-50%) translateY(0) scale(1); }
                }
                
                .auto-play-tip {
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                }
            `;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(tip);
        
        // 倒计时显示
        let countdown = 3;
        const countdownInterval = setInterval(() => {
            countdown--;
            const countdownText = tip.querySelector('span');
            if (countdownText) {
                // 优化倒计时显示，保持文字长度一致
                if (countdown > 0) {
                    countdownText.textContent = `${countdown}秒后自动切换到第${nextEpisode}集`;
                } else {
                    countdownText.textContent = `即将自动切换到第${nextEpisode}集`;
                }
            }
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
            }
        }, 1000);
        
        // 保存倒计时定时器ID
        tip.countdownInterval = countdownInterval;
    }
    
    // 取消自动播放
    function cancelAutoPlay() {
        // 清除自动播放定时器
        if (window.autoPlayTimer) {
            clearTimeout(window.autoPlayTimer);
            window.autoPlayTimer = null;
        }
        
        // 移除提示
        const tip = document.querySelector('.auto-play-tip');
        if (tip) {
            // 清除倒计时定时器
            if (tip.countdownInterval) {
                clearInterval(tip.countdownInterval);
            }
            tip.remove();
        }
        
        showTip('已取消自动播放');
    }
    
    // 切换自动播放开关
    function toggleAutoPlay() {
        const button = document.getElementById('auto-play-toggle');
        const isEnabled = localStorage.getItem('auto_play_enabled') !== 'false';
        
        if (isEnabled) {
            // 禁用自动播放
            localStorage.setItem('auto_play_enabled', 'false');
            button.classList.remove('enabled');
            showTip('已禁用自动播放');
        } else {
            // 启用自动播放
            localStorage.setItem('auto_play_enabled', 'true');
            button.classList.add('enabled');
            showTip('已启用自动播放');
        }
    }
    
    // 检查自动播放是否启用
    function isAutoPlayEnabled() {
        return localStorage.getItem('auto_play_enabled') !== 'false';
    }
    
    // 检测是否为iOS设备
    function isIOS() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) || 
               (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
    }
    
    // 检测是否为Safari浏览器
    function isSafari() {
        return /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
    }
    
    // 获取iOS自动播放策略
    function getIOSAutoplayStrategy() {
        if (isIOS()) {
            // iOS设备上，优先使用静音自动播放
            return 'muted-first';
        } else if (isSafari()) {
            // Safari浏览器，也使用静音策略
            return 'muted-first';
        } else {
            // 其他设备，使用标准策略
            return 'standard';
        }
    }
    
    // 初始化自动播放按钮状态
    function initAutoPlayButton() {
        const button = document.getElementById('auto-play-toggle');
        if (!button) return;
        
        const isEnabled = isAutoPlayEnabled();
        
        if (isEnabled) {
            button.classList.add('enabled');
        } else {
            button.classList.remove('enabled');
        }
    }
    
    // 选择剧集
    function selectEpisode(episodeNumber) {        
        // 立即更新缓存
        updateCacheImmediately(episodeNumber);
        
        // 跳转到选择的剧集
        window.location.href = `?page=play&id=<?= $video['id'] ?>&episode=${episodeNumber}`;
    }
    
    // 无刷新切换剧集（用于自动播放）
    function switchEpisodeWithoutRefresh(episodeNumber) {        
        // 获取剧集数据
        <?php if ($hasEpisodes): ?>
        const episodes = <?= json_encode($episodes) ?>;
        const targetEpisode = episodes.find(ep => ep.episode_number == episodeNumber);
        
        if (targetEpisode) {            
            // 检查视频源是否有效
            if (!targetEpisode.video_path || targetEpisode.video_path.trim() === '') {
                console.error('剧集视频源为空:', targetEpisode);
                showTip('该剧集暂无视频源，无法播放');
                return;
            }
            
            // 构建完整的视频URL
            let videoUrl = targetEpisode.video_path;
            
            // 处理相对路径，参考PHP中的逻辑
            if (videoUrl && videoUrl.indexOf('http') !== 0) {
                // 将/public/替换为/
                videoUrl = videoUrl.replace('/public/', '/');
                
                // 确保路径以/开头
                if (videoUrl.indexOf('/') !== 0) {
                    videoUrl = '/' + videoUrl;
                }
                
                // 不添加域名，让nginx处理路径映射（与PHP逻辑保持一致）
                // videoUrl = 'https://m.ql83.com' + videoUrl;
            }
            
            // 检查是否为音频文件
            const isNewAudio = isAudioUrl(videoUrl);
            
            if (isAudioMode && isNewAudio) {
                // 音频模式下的剧集切换
                switchAudioEpisode(videoUrl, episodeNumber);
            } else if (!isAudioMode && !isNewAudio) {
                // 视频模式下的剧集切换
                switchVideoEpisode(videoUrl, episodeNumber);
            } else {
                // 模式不匹配，回退到传统方式
                selectEpisode(episodeNumber);
            }
        } else {
            showTip('剧集切换失败');
        }
        <?php else: ?>
        showTip('没有可用的剧集');
        <?php endif; ?>
    }
    
    // 音频模式下的剧集切换
    function switchAudioEpisode(videoUrl, episodeNumber) {
        if (!audioPlayerReady) {
            showTip('音频播放器未准备好');
            return;
        }
        
        // 显示加载提示
        showLoadingTip(`正在加载第${episodeNumber}集...`);
        
        // 暂停当前播放
        audioPlayer.pause();
        
        // 更新音频源
        audioPlayer.src = videoUrl;
        audioPlayer.load();
        
        // 监听加载完成
        audioPlayer.addEventListener('loadedmetadata', function onLoaded() {
            audioPlayer.removeEventListener('loadedmetadata', onLoaded);
            
            // 尝试播放
            audioPlayer.play().then(() => {
                // 同步状态
                syncAllStates(episodeNumber);
                hideLoadingTip();
                showTip(`已切换到第${episodeNumber}集`);
                
                // 更新音频播放器信息
                updateAudioPlayerInfo(episodeNumber);
                
                // 立即保存新剧集的进度（初始为0）
                setTimeout(() => {
                    saveProgress();
                }, 100);
            }).catch(() => {
                hideLoadingTip();
                showPlayPrompt(episodeNumber);
            });
        });
        
        // 错误处理
        audioPlayer.addEventListener('error', function onError() {
            audioPlayer.removeEventListener('error', onError);
            hideLoadingTip();
            
            // 获取具体错误信息
            let errorMessage = '音频加载失败，请检查网络或选择其他剧集';
            let errorTitle = '音频加载失败';
            
            // 检查错误类型
            if (onError.target && onError.target.error) {
                switch (onError.target.error.code) {
                    case MediaError.MEDIA_ERR_ABORTED:
                        errorMessage = '音频播放被中断';
                        break;
                    case MediaError.MEDIA_ERR_NETWORK:
                        errorMessage = '网络错误，音频无法加载';
                        break;
                    case MediaError.MEDIA_ERR_DECODE:
                        errorMessage = '音频格式不支持或文件损坏';
                        break;
                    case MediaError.MEDIA_ERR_SRC_NOT_SUPPORTED:
                        errorMessage = '音频源无法访问，可能是404错误';
                        break;
                    default:
                        errorMessage = '音频加载失败，请检查网络或选择其他剧集';
                }
            }
            
            // 显示错误提示
            showPlayErrorTip(errorTitle, errorMessage);
        });
    }
    
    // 视频模式下的剧集切换
    function switchVideoEpisode(videoUrl, episodeNumber) {
        if (!player || !playerReady) {
            showTip('视频播放器未准备好');
            return;
        }
        
        // 显示加载提示
        showLoadingTip(`正在加载第${episodeNumber}集...`);
        
        // iOS优化：切源前先静音、暂停，避免有声自动播放被拦
        player.pause();
        player.muted(true);
        
        // 更新视频源 & 立即 load（video.js 会处理）
        player.src({
            src: videoUrl,
            type: videoUrl.includes('.m3u8') ? 'application/x-mpegURL' : 'video/mp4'
        });
        player.load();
        
        // iOS优化：等到可以播放再尝试 play（避免过早调用被拒）
        const tryAutoplay = () => {
            player.play().then(() => {
                // 播上了 -> 稍后再恢复音量（给一点缓冲时间更稳）
                setTimeout(() => player.muted(false), 300);
                
                // 同步你的 UI/状态
                syncAllStates(episodeNumber);
                hideLoadingTip();
                
                // 显示切换成功提示
                showTip(`已切换到第${episodeNumber}集`);
                
                // 立即保存新剧集的进度（初始为0）
                setTimeout(() => {
                    saveProgress();
                }, 100);
            }).catch(() => {
                // 自动播失败 -> 弹出点击继续（一次点击就是手势，必过）
                hideLoadingTip();
                showPlayPrompt(episodeNumber); // 你已有
            });
        };
        
        // 监听 canplay，再触发 tryAutoplay（readyState 足够时直接播）
        if (player.readyState() >= 3) {
            tryAutoplay();
        } else {
            player.one('canplay', tryAutoplay);
        }
        
        // 防抖：再加个兜底 watchdog，2.5s 内没触发就再试一次
        setTimeout(() => {
            if (player.paused()) { 
                tryAutoplay(); 
            }
        }, 2500);
        
        // 监听错误事件
        player.one('error', function() {
            console.error('视频加载失败');
            hideLoadingTip();
            
            // 获取具体错误信息
            const error = player.error();
            let errorMessage = '视频加载失败，请检查网络或选择其他剧集';
            
            if (error) {
                switch (error.code) {
                    case 1:
                        errorMessage = '视频加载失败，可能是网络问题或文件不存在';
                        break;
                    case 2:
                        errorMessage = '网络连接异常，请检查网络设置';
                        break;
                    case 3:
                        errorMessage = '视频格式不支持或文件损坏';
                        break;
                    case 4:
                        errorMessage = '视频源无法访问，可能是404错误';
                        break;
                    default:
                        errorMessage = '视频加载失败，请检查网络或选择其他剧集';
                }
            }
            
            // 显示错误提示
            showPlayErrorTip('剧集切换失败', errorMessage);
            
            // 清理错误缓存
            clearInvalidCache();
            
            // 回退到传统方式
            setTimeout(() => {
                selectEpisode(episodeNumber);
            }, 2000);
        });
        
        // 添加播放器点击事件监听，让用户可以通过点击来启用播放
        player.one('click', function() {
            player.play().catch(error => {
                // 播放失败处理
            });
        });
    }
    
    // 立即更新缓存（用户点击剧集时调用）
    function updateCacheImmediately(episodeNumber) {
        try {
            const historyData = {
                videoId: <?= $video['id'] ?>,
                title: "<?= $this->escape($video['title']) ?>",
                episode: episodeNumber,
                episodeTitle: "<?= $this->escape($currentEpisode['title'] ?? '') ?>",
                poster: "<?= $this->escape($video['poster'] ?? '') ?>",
                poster: "<?= $this->escape($video['poster'] ?? '') ?>",
                lastPlayed: Date.now(),
                progress: { time: 0, duration: 0 }
            };
            
            // 立即保存到历史记录
            let history = JSON.parse(localStorage.getItem('video_watch_history') || '[]');
            history = history.filter(item => 
                !(item.videoId == historyData.videoId && item.episode == historyData.episode)
            );
            history.unshift(historyData);
            
            if (history.length > 50) {
                history = history.slice(0, 50);
            }
            
            localStorage.setItem('video_watch_history', JSON.stringify(history));
            
            // 立即更新最后播放的视频信息
            localStorage.setItem('last_video', JSON.stringify({
                videoId: historyData.videoId,
                episode: historyData.episode,
                timestamp: Date.now()
            }));
            

        } catch (e) {
            console.warn('立即更新缓存失败:', e);
        }
    }
    
    // 更新页面标题
    function updatePageTitle(episodeNumber) {
        const titleElement = document.querySelector('.player-title-link');
        if (titleElement) {
            const baseTitle = "<?= $this->escape($video['title']) ?>";
            // 只有多集时才显示集数
            const hasMultipleEpisodes = <?= $hasEpisodes ? 'true' : 'false' ?>;
            if (hasMultipleEpisodes) {
                titleElement.textContent = `${baseTitle} 第${episodeNumber}集`;
            } else {
                titleElement.textContent = baseTitle;
            }
        }
        
        // 更新浏览器标题
        const hasMultipleEpisodes = <?= $hasEpisodes ? 'true' : 'false' ?>;
        if (hasMultipleEpisodes) {
            document.title = `免费在线看 ${"<?= $this->escape($video['title']) ?>"} 第${episodeNumber}集 - 动漫在线观看 - 星海影院`;
        } else {
            document.title = `免费在线看 ${"<?= $this->escape($video['title']) ?>"} - 动漫在线观看 - 星海影院`;
        }
    }
    
    // 更新剧集列表高亮和播放按钮
    function updateEpisodeHighlight(episodeNumber) {
        const episodeList = document.querySelector('.anthology-list-play');
        if (!episodeList) return;
        
        const episodeItems = episodeList.querySelectorAll('li');
        
        episodeItems.forEach((item) => {
            // 移除所有高亮
            item.classList.remove('on', 'ecnav-dt');
            
            // 使用data-episode属性获取准确的剧集号
            const itemEpisodeNumber = parseInt(item.getAttribute('data-episode'));
            const isCurrentEpisode = itemEpisodeNumber === parseInt(episodeNumber);
            
            
            // 获取剧集项内的链接元素
            const linkElement = item.querySelector('a');
            if (linkElement) {
                if (isCurrentEpisode) {
                    // 当前剧集：显示播放按钮动画
                    linkElement.innerHTML = '<em class="play-on"><i></i><i></i><i></i><i></i></em>';
                    item.classList.add('on', 'ecnav-dt');
                    
                    // 滚动到当前剧集位置
                    /*
                    setTimeout(() => {
                        item.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center',
                            inline: 'center'
                        });
                    }, 300);
                    */
                } else {
                    // 其他剧集：显示剧集文字
                    linkElement.innerHTML = `<span>第${String(itemEpisodeNumber).padStart(2, '0')}集</span>`;
                }
            }
        });
        

    }
    
    // 更新URL（不刷新页面）
    function updateURLWithoutRefresh(episodeNumber) {
        const newUrl = `?page=play&id=<?= $video['id'] ?>&episode=${episodeNumber}`;
        window.history.pushState({ episode: episodeNumber }, '', newUrl);
    }
    
    // 显示播放提示
    function showPlayPrompt(episodeNumber) {
        const tip = document.createElement('div');
        tip.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.9);
            color: white;
            padding: 20px 30px;
            border-radius: 10px;
            font-size: 16px;
            z-index: 10000;
            text-align: center;
            max-width: 300px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
        `;
        
        tip.innerHTML = `
            <div style="margin-bottom: 15px;">
                <i class="fa" style="color: #4ecdc4; font-size: 24px;">&#xe557;</i>
            </div>
            <div style="margin-bottom: 20px;">
                <p style="margin: 0 0 10px 0;">已切换到第${episodeNumber}集</p>
                <p style="margin: 0; font-size: 14px; color: #ccc;">点击播放按钮开始观看</p>
            </div>
            <button onclick="this.parentElement.remove()" style="
                background: #4ecdc4;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
                transition: all 0.3s ease;
            " onmouseover="this.style.background='#3db8b0'" onmouseout="this.style.background='#4ecdc4'">
                知道了
            </button>
        `;
        
        document.body.appendChild(tip);
        
        // 5秒后自动移除
        setTimeout(() => {
            if (tip.parentNode) {
                tip.parentNode.removeChild(tip);
            }
        }, 5000);
    }
    
    // 显示加载提示
    function showLoadingTip(message) {
        // 移除已存在的加载提示
        hideLoadingTip();
        
        const tip = document.createElement('div');
        tip.id = 'loading-tip';
        tip.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.9);
            color: white;
            padding: 20px 30px;
            border-radius: 10px;
            font-size: 16px;
            z-index: 10000;
            text-align: center;
            max-width: 300px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
        `;
        
        tip.innerHTML = `
            <div style="margin-bottom: 15px;">
                <div class="loading-spinner" style="
                    width: 40px;
                    height: 40px;
                    border: 4px solid rgba(255,255,255,0.3);
                    border-top: 4px solid #4ecdc4;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                    margin: 0 auto;
                "></div>
            </div>
            <div>
                <p style="margin: 0; font-size: 14px;">${message}</p>
            </div>
        `;
        
        // 添加旋转动画样式
        if (!document.querySelector('#loading-spinner-style')) {
            const style = document.createElement('style');
            style.id = 'loading-spinner-style';
            style.textContent = `
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            `;
        }
        
        document.body.appendChild(tip);
    }
    
    // 隐藏加载提示
    function hideLoadingTip() {
        const tip = document.querySelector('#loading-tip');
        if (tip) {
            tip.remove();
        }
    }
    
    // 同步所有状态，保持与刷新切换的一致性
    function syncAllStates(episodeNumber) {
        try {
            // 1. 更新页面标题
            updatePageTitle(episodeNumber);
            
            // 2. 更新剧集列表高亮
            updateEpisodeHighlight(episodeNumber);
            
            // 3. 更新URL（不刷新页面）
            updateURLWithoutRefresh(episodeNumber);
            
            // 4. 更新缓存
            updateCacheImmediately(episodeNumber);
            
            // 5. 同步自动播放按钮状态
            syncAutoPlayButtonState();
            
            // 6. 更新全局变量
            updateGlobalEpisodeState(episodeNumber);
            
                    // 7. 清理可能存在的错误状态
        clearInvalidCache();
        
        // 8. 如果是音频模式，更新锁屏信息
        if (window.isAudioOnly) {
            updateMediaSessionMetadata();
        }
    } catch (e) {
        console.error('状态同步失败:', e);
    }
    }
    
    // 同步自动播放按钮状态
    function syncAutoPlayButtonState() {
        const button = document.getElementById('auto-play-toggle');
        if (!button) return;
        
        const isEnabled = isAutoPlayEnabled();
        
        if (isEnabled) {
            button.classList.add('enabled');
        } else {
            button.classList.remove('enabled');
        }
        

    }
    
    // 更新全局剧集状态
    function updateGlobalEpisodeState(episodeNumber) {
        // 更新全局变量，确保其他函数能获取到正确的当前剧集
        window.currentEpisodeNumber = episodeNumber;
        
        // 更新页面中的剧集显示
        const episodeDisplay = document.querySelector('.player-title-link');
        if (episodeDisplay) {
            const baseTitle = "<?= $this->escape($video['title']) ?>";
            // 只有多集时才显示集数
            const hasMultipleEpisodes = <?= $hasEpisodes ? 'true' : 'false' ?>;
            if (hasMultipleEpisodes) {
                episodeDisplay.textContent = `${baseTitle} 第${episodeNumber}集`;
            } else {
                episodeDisplay.textContent = baseTitle;
            }
        }
    }
    
    // 自动恢复播放进度（静默执行，不显示提示）
    function autoRestoreProgress(progress) {
        if (player && playerReady) {
            player.currentTime(progress.time);
            // 不自动播放，只恢复进度
            // player.play();
        } else {
            // 如果播放器还没就绪，设置标记
            window.autoRestoreProgress = progress;
        }
    }
    
    // 保存播放进度
    function saveProgress() {
        try {
            // 使用JavaScript的当前剧集号，而不是PHP的固定值
            const currentEpisode = window.currentEpisodeNumber || <?= $currentEpisodeNumber ?? 1 ?>;
            const storageKey = `video_progress_${<?= $video['id'] ?>}_${currentEpisode}`;
            let progress = null;
            
            if (isAudioMode && audioPlayerReady) {
                // 音频模式
                progress = {
                    time: audioPlayer.currentTime,
                    duration: audioPlayer.duration,
                    timestamp: Date.now(),
                    videoId: <?= $video['id'] ?>,
                    episode: currentEpisode
                };
            } else if (player && playerReady) {
                // 视频模式
                progress = {
                    time: player.currentTime(),
                    duration: player.duration(),
                    timestamp: Date.now(),
                    videoId: <?= $video['id'] ?>,
                    episode: currentEpisode
                };
            }
            
            if (progress) {
                localStorage.setItem(storageKey, JSON.stringify(progress));
                // 同时保存到历史记录
                saveToHistory();
            }
        } catch (e) {
            console.warn('保存播放进度失败:', e);
        }
    }
    
    // 加载播放进度
    function loadProgress() {
        try {
            // 使用JavaScript的当前剧集号，而不是PHP的固定值
            const currentEpisode = window.currentEpisodeNumber || <?= $currentEpisodeNumber ?? 1 ?>;
            const storageKey = `video_progress_${<?= $video['id'] ?>}_${currentEpisode}`;
            const saved = localStorage.getItem(storageKey);
            if (saved) {
                const progress = JSON.parse(saved);
                // 检查进度是否有效（不超过媒体总长度的90%）
                if (progress.time && progress.duration && progress.time < progress.duration * 0.9) {
                    return progress;
                }
            }
        } catch (e) {
            console.warn('加载播放进度失败:', e);
        }
        return null;
    }
    
    // 定期保存播放记录
    function startHistorySaving() {
        // 每30秒保存一次播放记录
        setInterval(() => {
            if (isAudioMode && audioPlayerReady && !audioPlayer.paused) {
                saveToHistory();
            } else if (player && playerReady && !player.paused()) {
                saveToHistory();
            }
        }, 30000);
    }
    // 启用后台/锁屏播放模式（Media Session）
    function enableBackgroundAudioMode() {
        // 让控制中心/锁屏显示标题封面并控制播放
        if ('mediaSession' in navigator) {
            updateMediaSessionMetadata();

            if (isAudioMode && audioPlayerReady) {
                // 音频模式
                const setPos = () => {
                    try {
                        navigator.mediaSession.setPositionState({
                            duration: audioPlayer.duration || 0,
                            playbackRate: 1,
                            position: audioPlayer.currentTime || 0
                        });
                    } catch(e){}
                };

                navigator.mediaSession.setActionHandler('play',  () => audioPlayer.play());
                navigator.mediaSession.setActionHandler('pause', () => audioPlayer.pause());
                navigator.mediaSession.setActionHandler('seekbackward', () => audioPlayer.currentTime = Math.max(0, audioPlayer.currentTime - 10));
                navigator.mediaSession.setActionHandler('seekforward',  () => audioPlayer.currentTime = Math.min((audioPlayer.duration||0), audioPlayer.currentTime + 10));
                
                // 音频模式下的上一首/下一首
                try { 
                    navigator.mediaSession.setActionHandler('previoustrack', () => {
                        const prevBtn = document.getElementById('audioPrev');
                        if (prevBtn) prevBtn.click();
                    }); 
                } catch(e){}
                try { 
                    navigator.mediaSession.setActionHandler('nexttrack', () => {
                        const nextBtn = document.getElementById('audioNext');
                        if (nextBtn) nextBtn.click();
                    }); 
                } catch(e){}

                audioPlayer.addEventListener('timeupdate', setPos);
                audioPlayer.addEventListener('loadedmetadata', () => { 
                    updateMediaSessionMetadata(); 
                    setPos(); 
                });
            } else if (player && playerReady) {
                // 视频模式
                const setPos = () => {
                    try {
                        navigator.mediaSession.setPositionState({
                            duration: player.duration() || 0,
                            playbackRate: player.playbackRate ? player.playbackRate() : 1,
                            position: player.currentTime() || 0
                        });
                    } catch(e){}
                };

                navigator.mediaSession.setActionHandler('play',  () => player.play());
                navigator.mediaSession.setActionHandler('pause', () => player.pause());
                navigator.mediaSession.setActionHandler('seekbackward', () => player.currentTime(Math.max(0, player.currentTime() - 10)));
                navigator.mediaSession.setActionHandler('seekforward',  () => player.currentTime(Math.min((player.duration()||0), player.currentTime() + 10)));
                // 可选：上一集/下一集
                try { 
                    navigator.mediaSession.setActionHandler('previoustrack', () => null); 
                } catch(e){}
                try { 
                    navigator.mediaSession.setActionHandler('nexttrack', () => autoPlayNextEpisode()); 
                } catch(e){}

                player.on('timeupdate', setPos);
                player.on('ratechange', setPos);
                player.on('loadedmetadata', () => { 
                    updateMediaSessionMetadata(); 
                    setPos(); 
                });
            }
        }
    }

    // 切集时更新锁屏显示
    function updateMediaSessionMetadata() {
        if (!('mediaSession' in navigator)) return;
        try {
            navigator.mediaSession.metadata = new MediaMetadata({
                title: (document.querySelector('.player-title-link')?.textContent || videoTitle || '正在播放'),
                artist: '星海影院',
                album: '',
                artwork: [
                    { src: (videoPoster || '/template/yuyuyy/asset/img/logo-1.png'), sizes: '512x512', type: 'image/png' }
                ]
            });
        } catch(e){}
    }

    // 显示进度提示
    function showProgressTip(progress) {
        const tip = document.createElement('div');
        tip.style.cssText = `
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            z-index: 1000;
            animation: fadeInOut 2s ease-in-out;
        `;
        
        const minutes = Math.floor(progress.time / 60);
        const seconds = Math.floor(progress.time % 60);
        tip.innerHTML = `已恢复播放进度：${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        // 添加动画样式
        if (!document.querySelector('#progress-tip-style')) {
            const style = document.createElement('style');
            style.id = 'progress-tip-style';
            style.textContent = `
                @keyframes fadeInOut {
                    0% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
                    20% { opacity: 1; transform: translateX(-50%) translateY(0); }
                    80% { opacity: 1; transform: translateX(-50%) translateY(0); }
                    100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
                }
            `;
            document.head.appendChild(style);
        }
        
        document.querySelector('.player-left').appendChild(tip);
        
        // 2秒后自动移除
        setTimeout(() => {
            if (tip.parentNode) {
                tip.parentNode.removeChild(tip);
            }
        }, 2000);
    }
    
    // 收藏功能
    let isFavorited = false;
    
    // 页面加载时检查收藏状态
    document.addEventListener('DOMContentLoaded', function() {
        checkCollectionStatus();
    });
    
    // 检查收藏状态
    function checkCollectionStatus() {
        // 检查是否已登录
        fetch('/api/user.php?action=check_favorite&video_id=<?= $video['id'] ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    isFavorited = data.is_favorited;
                    updateCollectionUI();
                }
            })
            .catch(error => {
                console.log('未登录或检查收藏状态失败');
            });
    }
    
    // 切换收藏状态
    function toggleCollection(videoId) {
        // 检查是否已登录
        fetch('/api/user.php?action=check_favorite&video_id=' + videoId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 已登录，执行收藏/取消收藏
                    if (isFavorited) {
                        removeFromCollection(videoId);
                    } else {
                        addToCollection(videoId);
                    }
                } else {
                    // 未登录，跳转到登录页
                    if (confirm('请先登录后再收藏，是否前往登录？')) {
                        window.location.href = '/user/login';
                    }
                }
            })
            .catch(error => {
                // 网络错误，跳转到登录页
                if (confirm('网络错误，是否前往登录页？')) {
                    window.location.href = '/user/loginp';
                }
            });
    }
    
    // 添加到收藏
    function addToCollection(videoId) {
        const formData = new FormData();
        formData.append('video_id', videoId);
        formData.append('type', 1);
        
        fetch('/api/user.php?action=add_favorite', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                isFavorited = true;
                updateCollectionUI();
                showMessage('收藏成功！', 'success');
            } else {
                showMessage(data.message || '收藏失败', 'error');
            }
        })
        .catch(error => {
            showMessage('网络错误，请重试', 'error');
        });
    }
    
    // 从收藏中移除
    function removeFromCollection(videoId) {
        if (!confirm('确定要取消收藏吗？')) return;
        
        const formData = new FormData();
        formData.append('video_id', videoId);
        
        fetch('/api/user.php?action=remove_favorite', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                isFavorited = false;
                updateCollectionUI();
                showMessage('已取消收藏', 'success');
            } else {
                showMessage(data.message || '操作失败', 'error');
            }
        })
        .catch(error => {
            showMessage('网络错误，请重试', 'error');
        });
    }
    
    // 更新收藏按钮UI
    function updateCollectionUI() {
        const icon = document.getElementById('collectionIcon');
        const text = document.getElementById('collectionText');
        const btn = document.getElementById('collectionBtn');
        
        if (isFavorited) {
            icon.innerHTML = '&#xe578;'; // 已收藏图标
            text.textContent = '已收藏';
            btn.classList.add('collected');
        } else {
            icon.innerHTML = '&#xe577;'; // 未收藏图标
            text.textContent = '收藏';
            btn.classList.remove('collected');
        }
    }
    
    // 显示消息提示
    function showMessage(message, type = 'info') {
        const tip = document.createElement('div');
        tip.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : '#3498db'};
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 14px;
            z-index: 10000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideInRight 0.3s ease-out;
        `;
        tip.textContent = message;
        
        // 添加动画样式
        if (!document.querySelector('#message-tip-style')) {
            const style = document.createElement('style');
            style.id = 'message-tip-style';
            style.textContent = `
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(tip);
        
        // 3秒后自动移除
        setTimeout(() => {
            if (tip.parentNode) {
                tip.parentNode.removeChild(tip);
            }
        }, 3000);
    }
    
    // 分享视频功能
    function shareVideo() {
        // 获取当前页面信息
        const videoTitle = document.querySelector('.player-title-link')?.textContent || '精彩视频';
        const currentUrl = window.location.href;
        const videoPoster = document.querySelector('.player-poster img')?.src || '';
        
        // 检查是否支持Web Share API
        if (navigator.share && navigator.canShare) {
            // 现代浏览器，使用原生分享
            const shareData = {
                title: videoTitle,
                text: `推荐一部好片：${videoTitle}`,
                url: currentUrl
            };
            
            // 如果有海报图片，也加入分享
            if (videoPoster && videoPoster !== '') {
                shareData.url = currentUrl;
            }
            
            navigator.share(shareData)
                .then(() => {
                    console.log('分享成功');
                })
                .catch((error) => {
                    console.log('分享失败:', error);
                    // 如果原生分享失败，回退到复制链接
                    fallbackShare();
                });
        } else {
            // 不支持Web Share API，使用回退方案
            fallbackShare();
        }
    }
    
    // 回退分享方案（复制链接到剪贴板）
    function fallbackShare() {
        const videoTitle = document.querySelector('.player-title-link')?.textContent || '精彩视频';
        const currentUrl = window.location.href;
        const shareText = `${videoTitle}\n${currentUrl}`;
        
        // 尝试复制到剪贴板
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(shareText)
                .then(() => {
                    showMessage('链接已复制到剪贴板，可以分享给朋友了！', 'success');
                })
                .catch(() => {
                    // 如果复制失败，静默处理
                    console.log('复制失败');
                });
        } else {
            // 不支持剪贴板API，静默处理
            console.log('不支持剪贴板API');
        }
    }

</script>
<div class="none">
    <span class="ds-log-set" data-type="4" data-mid="1" data-id="<?= $video['id'] ?>" data-sid="1" data-nid="1"></span>
    <span class="ds-history-set" data-name="[动漫]<?= $this->escape($video['title']) ?>" data-mid="播放中" data-pic="<?= $this->escape($video['poster']) ?>"></span>
    <span class="ds-hits" data-mid="1" data-id="<?= $video['id'] ?>" data-type="hits"></span>
    <span class="ds-hits" data-mid="1" data-id="<?= $video['id'] ?>" data-type="hits_day"></span>
    <span class="ds-hits" data-mid="1" data-id="<?= $video['id'] ?>" data-type="hits_week"></span>
    <span class="ds-hits" data-mid="1" data-id="<?= $video['id'] ?>" data-type="hits_month"></span>
</div>
<?php include 'components/footer.php'; ?>
</body>
</html>
</html>