window.configs = { // 前端配置文件
	global: {
		URL: 'https://链.ml/', // 你网站的 URL (需带协议头及最后的斜杠)
		consoleLog: '搭个小破网站不容易, 求各位大佬手下留情放过我, 别攻击...' // (选填) 可以在浏览器开发者工具控制台输出一段话
	},
	head: {
		title: '链.ml - 短链接生成', // 浏览器标签页标签上显示的标题
		faviconIco: '', // (选填) 浏览器标签页标签上标题左侧显示的图标
		metaDescription: '一个免费无广告, 无需注册登录即可使用的短链接生成工具, 还支持自定义链接后缀和设置可选的过期时间, 可选的密码保护等功能! ' // (选填) 网站的描述
	},
	body: {
		header: {
			logo: '', // (选填) 展示的 logo
			title: '链.ml', // 黄色的那个标题
			desc: { // 黄色标题下面的灰色小字
				normalFront: '把你的链接变', // 普通的灰色小字
				highlight: '短', // 高亮 (背景黄橙) 的黑色小字
				normalBehind : '!' // 普通的灰色小字
			},
			smallDesc: `
				还可以自定义链接结尾哦~
				<br />
				<small>如: <a href="https://链.ml/一些好康的" target="_blank">https://链.ml/一些好康的</a></small>
			`, // (选填, 支持HTML) 黄色标题下面的灰色小字下面的灰色小字
			warnBox: `注意: 生成的短链接不支持非中国大陆 IP 访问 (原因: 其他地区的 IP 经常攻击本网站)` // (选填, 支持HTML) 黄色标题下面的 灰色小字下面的 灰色小字下边的 红色警告框里面的 红色小字 (￣ε(#￣)
		},
		footer: {
			copyright: `
				<p>
					搭个小破网站不容易, 求各位大佬手下留情放过我, 别攻击... 
					<a href="http://fxxk-attacker.xn--eb5a.ml/bans.html" target="_blank">[小黑屋]</a>
				</p>
				<p><a href="/open-source" target="_blank">开源地址: https://链.ml/open-source</a></p>
				<p style="color: lightgray;">个人用爱发电的小站点, 会尽最大努力保证网站长期稳定运行, 但绝不对长期稳定运行提供担保.</p>
				<p>&copy; 2022 <a href="mailto:admin@xn--eb5a.ml">DuckBurnIncense</a>, All rights reserved.</p>
			` // (支持HTML) 页面最下方的脚注 (版权信息)
		}
	}
};