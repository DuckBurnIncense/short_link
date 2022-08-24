# 链.ml - 一个短链接生成平台

## Demo

[https://链.ml/](https://链.ml/)

## 依赖

- Apache
- PHP

## 安装说明

1. 下载最新的 `Release` 压缩包到你的电脑;
2. 解压 `Release` 压缩包, 得到的文件夹下文中会称为 `Release`;
3. 打开 `Release` 下的 `api` 文件夹, 修改 `configuration_sample.php`, 其中:
	1. `database` 下为数据库设置:
		1. `type` 填你的数据库类型, 如 `mysql`;
		2. `host` 填你的数据库主机地址, 如 `localhost`;
		3. `port` 为数据库端口, 一般来说是 `3306`, 无需更改;
		4. `user` 为数据库用户名;
		5. `password` 填你的数据库密码;
		6. `db` 为数据库名;
		7. `table_prefix` 为数据表的前缀, 默认`eb5a`, 无需更改;
		8. `ssl` 设置是否使用ssl加密连接数据库.
	2. `limit` 下设置访问速率限制, 本程序采用数据库 + cloudflare形式, 比较耗费性能:
		1. `enable` 为是否启用访问速率限制, 如果服务器已有访问速率限制程序保护 (如cloudflare), 则无需启用;
		2. `max_request_times_per_day` 为每24小时单ip最大访问数;
		3. `max_request_times_per_minute` 为每分钟单ip最大访问数;
		4. `cf_zone_id` 为 cloudflare 的 `zone id`, 可在 cloudflare 的 `概述` 页右侧 `API` 标题下 `区域 ID` 找到;
		5. `cf_authorization` 为 cloudflare api 的 `访问token`, 可在 https://dash.cloudflare.com/profile/api-tokens 创建, 创建方法为:
			1. 点击 `创建令牌`;
			2. `创建自定义令牌`;
			3. `令牌名称` 自己取;
			4. `权限` 选 `区域`, `区域 WAF`, `编辑`;
			5. `区域资源` 选 `包括`, `特定区域`, `(你的网站域名)`;
			6. `TTL` 选择当前日期到一年后 (一年后需要重新创建令牌替换当前令牌token, 主要是为了安全方面考虑, 不能设置太久);
			7. 点击 `继续以显示摘要`;
			8. 点击 `创建令牌`;
			9. 获得令牌 Token, 在其前面添加 `Bearer (<-这里有一个空格)`, 完成;
			10. 示例值: `Bearer qWeRtYu1oP1AsD2F3G5Hz4-aSdF7HjKl1ZxCv3V5`.
4. 将 `configuration_sample.php` 重命名为 `configuration.php`;
5. 回到 `Release` 目录, 打开 `db` 文件夹
6. 用 `navicat`, `phpmyadmin`, `mysql 命令行工具` 等工具连接到你的数据库, 并导入 `short_link.sql`;
7. 回到 `Release` 目录, 删除 `db` 文件夹;
8. 按照文件内的提示修改 `config.js`;
9. 将 `Release` 文件夹下的所有文件上传到你的服务器 (的 `htdocs` 文件夹);
10. 访问你的网站, 完成!

## 开发说明

写的有点乱, 见谅

### 语言

前端: Vue3 + Vite

后端: PHP

### 目录

```
/ # 根目录
	api/ # 后端
		cacert.pem # 连接数据库的ssl证书
		configuration_sample.php # 配置示例
		Controller.php # 控制器, 储存公共方法
		DAO.php # 连接数据库的类
		index.php # 入口文件
		LimitController.php # 访问速率限制的控制器
		LimitModel.php # 访问速率限制的模型
		RecordController.php # 控制短链接生成的控制器
		RecordModel.php # 控制短链接生成的模型
		Tools.php # 工具类
	db/
		short_link.sql # 数据库文件
	short-link/ # 前端
		public/ 
			.htaccess # 控制转发请求到后端的配置文件
		src/ # 前端的源码
	http.rest # 测试 api 接口用的, 忽略就好
```

### 启动开发服务器

```
# cmd 1
cd api/ && php -S 0.0.0.0:23456 ./index.php
# cmd 2 
cd short-link/ && npm run dev
```

### 前端打包

```
npm run build
```

剩下懒得写了...

# 许可证

MIT