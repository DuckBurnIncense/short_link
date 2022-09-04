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
        background-color: #212121;
        padding: 0.8em;
        border: 0.1px solid #2d2d2d;
        border-radius: 4px;
        color: white;
    }
	button:hover {
        background-color: #484848;
    }
    button:disabled {
        background: #1b1b1b;
        color: #3d3d3d;
    }
</style>