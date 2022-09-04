<script setup>
	defineEmits([
		'change'
	]);
	const props = defineProps({
		opts: {
			type: Array,
			required: 1
		},
		default: {
			type: Number,
			default: 0
		}
	});
	const randomInputName = function() {
        let t = 'qwertyuiopasdfghjklzxcvbnm1234567890-';
        let arr = t.split('');
        let g = '';
        for (let i = 0; i < 6; i++) {
            let r = ~~(Math.random() * 36);
            g += arr[r];
        }
        return g;
    }();
</script>

<template>
	<span v-for="o, index in props.opts" :key="index" class="radio">
		<input 
			@click="$emit('change', index, o)" 
			:name="randomInputName" 
			:id="randomInputName + '-' + index" 
			type="radio" 
			:checked="props.default == index"
		>
		<label :for="randomInputName + '-' + index">{{o}}</label>
	</span>
</template>

<style scoped>
	span.radio {
		margin-right: 0.5em;
	}
</style>