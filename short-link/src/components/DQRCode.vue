<script setup>
	import { ref, defineProps, watchEffect } from 'vue';
	import QRCode from 'qrcode';

	const props = defineProps({
		value: {
			type: String,
			required: 1
		}
	});

	var url = ref();

	watchEffect(() => {
		QRCode.toDataURL(props.value, { mode: 'byte' }, (error, base64) => {
			if (error) console.error(error);
			url.value = base64;
		});
	});
</script>

<template>
	<img :src="url" />
</template>