<script setup>
    import { ref, defineProps } from 'vue';
    import DInput from './DInput.vue';
    import DButton from './DButton.vue';
    import { useXhrGet } from '@/hooks/xhr.js';
    import { 
        faLock,
    } from '@fortawesome/free-solid-svg-icons';

	const props = defineProps({
		suffix: {
			type: String,
			required: 1
		}
	});

    const suffix = props.suffix;

	let password = ref('');
	let tip = ref('');

    // 提交
    function submit() {
        if (!password.value) return tip.value = '请先输入密码!';
        useXhrGet('/' + suffix, {
            password: password.value
        }).then(v => {
            location.href = v;
        }).catch(e => {
            tip.value = e.message;
        });
    }
</script>

<template>
	<h2><font-awesome-icon :icon="faLock" /> 请输入访问密码以继续访问</h2>
	<p>
        <DInput v-model="password" placeholder="请在此处输入密码" />
        <DButton @click="submit()">访问</DButton>
    </p>
    <p class="cl-red">
        <small>
            {{tip}}
        </small>
    </p>
</template>

<style scoped>
	h2, 
    p {
		text-align: center;
	}
</style>