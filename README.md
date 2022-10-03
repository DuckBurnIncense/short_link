# 链.ml - 一个短链接生成平台

## Demo

[https://链.ml/](https://链.ml/)

注: 本人采用 freenom (域名) + cloudflare (防刷接口) + infinityfree (代码托管) + planetscale (数据库) 的纯白嫖方式搭建此demo.

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
