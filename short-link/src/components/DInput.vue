<style scoped lang="less">
	input {
		background-color: transparent;
		padding: 0.8em;
		border: 0.5px solid gray;
		border-radius: 0.2em;
		color: white;
	}
</style>

<template>
	<input
		:value="modelValue"
		@input="$emit('update:modelValue', $event.target.value)"
		:placeholder="placeholder"
		:style="style"
	/>
</template>

<script setup>
	defineProps({
		modelValue: {
			type: String,
			required: 1
		},
		placeholder: {
			type: String
		},
		style: {
			type: Object
		}
	});
	defineEmits([
		'update:modelValue'
	]);
</script>