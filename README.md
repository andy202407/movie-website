# 星海影院收藏系统

这是一个完整的用户收藏系统，包含用户注册、登录、收藏管理、观看历史等功能。

## 功能特性

### 🎯 用户系统
- **用户注册**：支持用户名、邮箱、密码注册
- **用户登录**：支持用户名/邮箱登录，记住登录状态
- **用户中心**：个人信息管理、收藏列表、观看历史

### ❤️ 收藏功能
- **添加收藏**：一键收藏喜欢的影片
- **取消收藏**：轻松管理收藏列表
- **收藏状态**：实时显示收藏状态
- **收藏列表**：在用户中心查看所有收藏

### 📺 观看历史
- **自动记录**：自动记录观看进度和剧集
- **历史管理**：在用户中心查看观看历史
- **进度恢复**：支持从上次观看位置继续播放

## 文件结构

```
├── models/
│   └── UserModel.php          # 用户数据模型
├── controllers/
│   └── UserController.php     # 用户控制器
├── api/
│   └── user.php              # 用户API接口
├── frontend-template/
│   ├── user/
│   │   ├── register.php      # 用户注册页面
│   │   ├── login.php         # 用户登录页面
│   │   └── index.php         # 用户中心页面
│   ├── components/
│   │   └── header.php        # 页面头部（包含用户状态）
│   └── play.php              # 播放页面（包含收藏功能）
├── database/
│   └── schema.sql            # 数据库表结构
└── README.md                 # 说明文档
```

## 安装步骤

### 1. 数据库设置
```sql
-- 执行 database/schema.sql 中的SQL语句
-- 创建用户表、收藏表、观看历史表
```

### 2. 配置数据库连接
确保 `config/database.php` 文件中的数据库连接信息正确。

### 3. 文件权限
确保PHP有读写权限，特别是session和cookie相关功能。

## 使用方法

### 用户注册
1. 访问 `/user/register.php`
2. 填写用户名、邮箱、密码
3. 点击"立即注册"

### 用户登录
1. 访问 `/user/login.php`
2. 输入用户名/邮箱和密码
3. 可选择"记住我"
4. 点击"立即登录"

### 收藏影片
1. 在播放页面点击"收藏"按钮
2. 如果未登录，会提示前往登录页
3. 登录后即可收藏
4. 收藏成功后会显示"已收藏"状态

### 管理收藏
1. 登录后访问 `/user/`
2. 在"我的收藏"标签页查看所有收藏
3. 可以取消收藏或继续观看

### 查看观看历史
1. 在用户中心的"观看历史"标签页
2. 查看所有观看过的影片
3. 点击"继续观看"从上次位置继续

## API接口

### 用户相关
- `POST /api/user.php?action=register` - 用户注册
- `POST /api/user.php?action=login` - 用户登录
- `POST /api/user.php?action=logout` - 用户登出
- `GET /api/user.php?action=get_user_info` - 获取用户信息

### 收藏相关
- `POST /api/user.php?action=add_favorite` - 添加收藏
- `POST /api/user.php?action=remove_favorite` - 取消收藏
- `GET /api/user.php?action=check_favorite` - 检查收藏状态
- `GET /api/user.php?action=get_favorites` - 获取收藏列表

### 观看历史
- `POST /api/user.php?action=update_watch_history` - 更新观看历史
- `GET /api/user.php?action=get_watch_history` - 获取观看历史

## 测试账号

系统预置了一个测试账号：
- 用户名：`test`
- 密码：`123456`
- 邮箱：`test@example.com`

## 技术特点

- **安全性**：密码使用 bcrypt 加密
- **响应式**：支持PC和移动端
- **用户体验**：实时状态更新、友好的提示信息
- **数据持久化**：支持数据库存储和本地存储
- **API设计**：RESTful API设计，易于扩展

## 注意事项

1. 确保PHP版本 >= 7.0
2. 需要启用PDO扩展
3. 需要启用session功能
4. 建议使用HTTPS以保护用户数据安全

## 扩展建议

- 添加用户头像上传功能
- 实现收藏分类管理
- 添加观看历史导出功能
- 实现用户评分和评论系统
- 添加推荐算法基于收藏历史

## 问题反馈

如果遇到问题或有改进建议，请及时反馈。
