<script setup>
	import { defineEmits } from 'vue';
	defineEmits([
		'click'
	])
</script>

<template>
	<button @click="$emit('click', event)"><slot></slot></button>
</template>

<style scoped>
	button {
        background-color: dimgray;
        padding: 0.8em;
        border: 0.2px solid gray;
        border-radius: 0.2em;
        color: white;
    }
</style>