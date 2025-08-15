    <!-- 会员中心专用Footer -->
    <footer class="member-footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>关于星海影院</h4>
                    <p>星海影院是您的一站式影视娱乐平台，提供海量高清影视资源，让您随时随地享受精彩内容。</p>
                </div>
                
                <!-- <div class="footer-section">
                    <h4>快速链接</h4>
                    <ul>
                        <li><a href="/">首页</a></li>
                        <li><a href="/?page=list&category=1">最新电影</a></li>
                        <li><a href="/?page=list&category=2">热门剧集</a></li>
                        <li><a href="/?page=list&category=3">动漫专区</a></li>
                    </ul>
                </div> -->
                
                <div class="footer-section">
                    <h4>会员服务</h4>
                    <ul>
                        <li><a href="/user">会员中心</a></li>
                        <li><a href="/user?tab=favorites">我的收藏</a></li>
                        <li><a href="/user?tab=history">观看历史</a></li>
                        <li><a href="/user?tab=profile">账户设置</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>联系我们</h4>
                    <ul>
                        <li><a href="/feedback">意见反馈</a></li>
                        <li><a href="/help">帮助中心</a></li>
                        <li><a href="/about">关于我们</a></li>
                        <li><a href="/privacy">隐私政策</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-info">
                    <p>&copy; 2021-2025 星海影院. 保留所有权利.</p>
                    <p>本站所有资源信息均从互联网搜索而来，本站不对显示的内容承担责任</p>
                </div>
                
                <div class="footer-social">
                    <a href="#" class="social-link" title="微信">
                        <i class="fa">💬</i>
                    </a>
                    <a href="#" class="social-link" title="微博">
                        <i class="fa">📱</i>
                    </a>
                    <a href="#" class="social-link" title="QQ">
                        <i class="fa">🐧</i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- 返回顶部按钮 -->
    <div class="back-to-top" id="backToTop">
        <i class="fa">⬆️</i>
    </div>
    
    <style>
        /* 会员中心专用Footer样式 */
        .member-footer {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 80px;
            padding: 60px 0 30px;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-section h4 {
            color: #667eea;
            font-size: 18px;
            margin-bottom: 20px;
            font-weight: 600;
            position: relative;
        }
        
        .footer-section h4::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 30px;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 1px;
        }
        
        .footer-section p {
            color: #888;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-section ul li {
            margin-bottom: 12px;
        }
        
        .footer-section ul li a {
            color: #ccc;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            position: relative;
        }
        
        .footer-section ul li a:hover {
            color: #667eea;
            transform: translateX(5px);
        }
        
        .footer-section ul li a::before {
            content: '▶';
            position: absolute;
            left: -15px;
            opacity: 0;
            transition: all 0.3s ease;
            color: #667eea;
            font-size: 10px;
        }
        
        .footer-section ul li a:hover::before {
            opacity: 1;
            left: -20px;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .footer-info p {
            color: #666;
            margin: 5px 0;
            font-size: 14px;
        }
        
        .footer-social {
            display: flex;
            gap: 15px;
        }
        
        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }
        
        .social-link:hover {
            background: rgba(102, 126, 234, 0.2);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        /* 返回顶部按钮 */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            z-index: 1000;
        }
        
        .back-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .back-to-top:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        /* 响应式设计 */
        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 30px;
            }
            
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
            
            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 45px;
                height: 45px;
            }
        }
        
        @media (max-width: 480px) {
            .footer-content {
                grid-template-columns: 1fr;
                gap: 25px;
            }
            
            .footer-section h4 {
                font-size: 16px;
            }
            
            .footer-section p,
            .footer-section ul li a {
                font-size: 14px;
            }
        }
    </style>
    
    <script>
        // 返回顶部功能
        document.addEventListener('DOMContentLoaded', function() {
            const backToTop = document.getElementById('backToTop');
            
            // 监听滚动事件
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTop.classList.add('show');
                } else {
                    backToTop.classList.remove('show');
                }
            });
            
            // 点击返回顶部
            backToTop.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
