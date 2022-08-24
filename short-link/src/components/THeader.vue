<script setup>
	import {ref, onBeforeUnmount} from 'vue';
	import LogoSvg from './LogoSvg.vue';
	import config from '@/hooks/config.js';
	// 配置中的 header
	const hConfig = config.body.header;
	// 生成颜色 
	// logo
	var deg360 = ref(0);
	if (!hConfig.logo) {
		var interval1 = setInterval(() => {
			deg360.value += 1;
			if (deg360.value >= 360) deg360.value = 0;
		}, 5);
	}
	// desc 的 mark
	let rgb256 = ref(0);
	let rgb256IsPlus = 1;
	const interval2 = setInterval(() => {
		rgb256.value += rgb256IsPlus ? 1 : -1;
		if (rgb256.value >= 256 || rgb256.value <= 0) rgb256IsPlus = !rgb256IsPlus;
	}, 25);
	// 清除定时器
	onBeforeUnmount(() => {
		if (!hConfig.logo) clearInterval(interval1);
		clearInterval(interval2);
	});
</script>

<template>
	<header>
		<LogoSvg 
			v-if="!hConfig.logo"
			class="logo" 
			:col1="'rgb(255, ' + rgb256 + ', 0)'"
			:col2="'rgb(255, ' + (256 - rgb256) + ', 0)'"
		/>
		<img v-else :src="hConfig.logo" class="logo" alt="logo">
		<div class="greetings">
			<h1 class="title" v-html="hConfig.title"></h1>
			<h3 class="desc">
				<span v-html="hConfig.desc.normalFront"></span>
				<mark 
					:style="{backgroundImage: 'linear-gradient(' + deg360 + 'deg, yellow, orange)'}"
					v-html="hConfig.desc.highlight"
				></mark>
				<span v-html="hConfig.desc.normalBehind"></span>
			</h3>
			<h4 v-if="hConfig.smallDesc" class="desc small" v-html="hConfig.smallDesc"></h4>
			<h6 v-if="hConfig.warnBox" class="desc smaller danger-box" v-html="hConfig.warnBox"></h6>
		</div>
	</header>
</template>

<style scoped lang="less">
	header {
		line-height: 1.5;
		text-align: center;

		.logo {
			height: 125px;
			margin: 1em;
			color: yellow;
		}
		.greetings {
			padding: 1em;
			
			.title {
				font-weight: bold;
				font-size: 2.5em;
				text-align: center;
				color: yellow;
			}
			.desc {
				font-size: 1.2em;
				text-align: center;
				&.small {
					font-size: 1em;
				}
				&.smaller {
					font-size: 0.8em;
				}

				mark {
					background-color: transparent;
					color: black;
					border-radius: 5px;
				}
			}
		}
	}
</style>
