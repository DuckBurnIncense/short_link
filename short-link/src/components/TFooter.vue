<script setup>
	const msg = '搭个小破网站不容易, 求各位大佬手下留情放过我, 别攻击...';
	console.log(msg);
</script>


<template>
	<footer>
		<p>{{msg}}</p>
		<p>&copy; 2022 DuckBurnIncense, All rights reserved.</p>
		<p>Contact: <a href="mailto:duckburnincense@outlook.com">DuckBurnIncense@outlook.com</a></p>
	</footer>
</template>

<style scoped lang="less">
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