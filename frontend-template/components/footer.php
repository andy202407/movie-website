<!-- 功能按钮 -->
<div class="top-back">
    <a class="lang-bnt fa box" data-id="1" href="javascript:">繁</a>
    <script src="/template/yuyuyy/asset/js/zh.js"></script>
    <a class="theme-style fa box" data-id="2" href="javascript:">&#xe575;</a>
    <a class="bj2 top fa ds-jiantoushang" href="javascript:"></a>
</div>

<footer class="footer box top40 wap-hide">
    <p class="this-link"><a href="/" target="_blank">问题反馈</a><a href="/" target="_blank">网站地图</a></p>
    <p class="cor5">本站所有资源信息均从互联网搜索而来，本站不对显示的内容承担责任，如您认为本站页面信息侵犯了您的权益，请附上版权证明邮件告知，在收到邮件后48小时内删除</p>
    <p class="cor5">Copyright &copy;&nbsp;2021~2025&nbsp;<a href="/">m.ql82.com </a>&nbsp;All rights reservd.</p>
    <p class="none"></p>
</footer>

<!-- 功能按钮样式 -->
<style>
/* ====== 通用：悬浮功能按钮（桌面默认） ====== */
.top-back{
  position: fixed;
  right: 24px;
  bottom: 100px;
  z-index: 999;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.top-back a{
  width: 40px; height: 40px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  text-decoration: none;
  font-size: 14px; line-height: 1;
  color:#fff;
  box-shadow: 0 8px 18px rgba(0,0,0,.35);
  backdrop-filter: blur(10px);
  transition: transform .18s ease, box-shadow .18s ease, opacity .18s ease;
}

.lang-bnt{ background:#333; }
.theme-style{ background:#555; }
.top{ background:#667eea; }

.top-back a:hover{ transform: translateY(-2px) scale(1.03); box-shadow: 0 10px 22px rgba(0,0,0,.42); }

/* 触屏设备去掉 hover 抖动，保留按压反馈 */
@media (hover: none){
  .top-back a:hover{ transform:none; }
  .top-back a:active{ transform: scale(.96); }
}

/* ====== 移动端适配：垂直排列 + 安全区避让 ====== */
@media (max-width: 767px){
  .top-back{
    right: calc(16px + env(safe-area-inset-right));
    bottom: calc(16px + env(safe-area-inset-bottom));
    flex-direction: column;         /* 垂直排列 */
    gap: 10px;
  }
  .top-back a{
    width: 48px; height: 48px;     /* 手指友好尺寸 */
    font-size: 15px;
  }
  /* 返回顶部图标稍大一点 */
  .top-back .ds-jiantoushang{ font-size: 18px; }
}

/* 极窄屏：保留主题与返回顶部，隐藏语言按钮（按需删掉这一段） */
@media (max-width: 380px){
  .lang-bnt{ display:none; }
}

/* ====== 返回顶部的显示/隐藏（通过 JS 控制 display） ====== */
.top-back .ds-jiantoushang{ display:flex; } /* 默认显示，JS 会按滚动改 */

/* ====== 页脚：移动端精简样式（若想继续隐藏 footer，删除这段即可） ====== */
@media (max-width: 767px){
  .footer.wap-hide{                /* 让原本被隐藏的移动端 footer 显示简版 */
    display:block !important;
    padding: 16px;
    background:#0e0e0e;
    border-top: 1px solid rgba(255,255,255,.06);
  }
  .footer .this-link{
    display:grid;
    grid-template-columns: repeat(2, minmax(0,1fr));
    gap:8px 12px;
    margin: 0 0 8px 0;
  }
  .footer .this-link a{ color:#9aa0aa; font-size:13px; text-decoration:none; }
  .footer p{ margin:6px 0; color:#9096a3; font-size:12px; line-height:1.6; }
}

</style>
<script>
  // 返回顶部：滚动 300px 后显示，否则隐藏
  (function(){
    const toTop = document.querySelector('.top-back .ds-jiantoushang');
    if(!toTop) return;

    // 平滑返回顶部
    toTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    const toggle = () => {
      const y = window.scrollY || document.documentElement.scrollTop;
      // 显示/隐藏：用 display 控制，避免占位
      toTop.style.display = y > 300 ? 'flex' : 'none';
    };

    toggle();
    document.addEventListener('scroll', toggle, { passive: true });
  })();

  // 主题切换（占位示例：按你项目的主题逻辑自行替换）
  document.querySelector('.theme-style')?.addEventListener('click', () => {
    document.documentElement.classList.toggle('theme-dark');
  });
</script>