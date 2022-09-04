<script setup>
	import config from '@/hooks/config.js';
	const fConfig = config.body.footer;
</script>


<template>
	<footer v-html="fConfig.copyright"></footer>
</template>

<style lang="less">
	footer {
		margin: 3em 0;
		text-align: center;

		a {
			color: currentcolor;
		}
		p {
			margin: 0.3em;
			font-size: 0.6em;
		}
	}
</style>