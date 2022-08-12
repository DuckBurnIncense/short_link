<script setup>
	import {ref, onBeforeUnmount} from 'vue';
	import { 
        faLink,
    } from '@fortawesome/free-solid-svg-icons';
	// 生成十六进制颜色 
	let deg360 = ref(0);
	const interval = setInterval(() => {
		deg360.value += 1;
		if (deg360.value >= 360) deg360.value = 0;
	}, 5);
	onBeforeUnmount(() => {
		clearInterval(interval);
	});
</script>

<template>
	<header>
		<font-awesome-icon class="logo" :icon="faLink" />
		<div class="greetings">
			<h1 class="title">链.ml</h1>
			<h3 class="desc">把你的链接变<mark :style="{backgroundImage: 'linear-gradient(' + deg360 + 'deg, yellow, orange)'}">短</mark>!</h3>
			<h4 class="desc small">
				还可以自定义链接结尾哦~
				<br />
				<small>如: <a href="https://链.ml/一些好康的">https://链.ml/<span class="b">一些好康的</span></a></small>
			</h4>
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

				mark {
					background-color: transparent;
					color: black;
					border-radius: 5px;
				}
			}
		}
	}
</style>
