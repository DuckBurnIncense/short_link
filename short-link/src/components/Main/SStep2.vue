<script setup>
    import { computed } from 'vue';
    import DStep from '@/components/DStep.vue';
    import DInput from '@/components/DInput.vue';
    import { 
        faCode,
        faTriangleExclamation,
    } from '@fortawesome/free-solid-svg-icons';
    import { isStringHasSpecialChars } from '@/hooks/regexr';
	import config from '@/hooks/config.js';

	const URL = config.global.URL;

	const props = defineProps({
		modelValue: {
			type: String,
			required: 1
		}
	});

	defineEmits([
		'update:modelValue'
	]);

    // 链接是否合法
    var isSuffixHasSpecialChars = computed(() => isStringHasSpecialChars(props.modelValue));

	// 生成随机字符串
	const generateRandomString = () => {
        let t = 'qwertyuiopasdfghjklzxcvbnm1234567890-';
        let arr = t.split('');
        let g = '';
        for (let i = 0; i < 6; i++) {
            let r = ~~(Math.random() * 36);
            g += arr[r];
        }
        return g;
    }
</script>

<template>
    <DStep>
		<template #icon>
			<font-awesome-icon :icon="faCode" />
		</template>
		<template #heading>第二步</template>
		<div class="step2">
			<p>第二步: 设置短链接后缀</p>
			<p>{{URL}} <DInput v-model="modelValue" @input="$emit('update:modelValue', $event.target.value)" placeholder="在此处设置短链接后缀" /></p>
			<p>
				<small 
					class="cur-pot t-underline" 
					v-if="modelValue == ''" 
					@click="modelValue = generateRandomString()"
				>
					[点击此处可随机生成]
				</small>
				<small class="cl-red" v-else-if="isSuffixHasSpecialChars">
					<font-awesome-icon :icon="faTriangleExclamation" />
					后缀中不能含有以下字符: 
					<code>&nbsp; (空格)</code>, 
					<code># (井号)</code>, 
					<code>/ (斜杠)</code>, 
					<code>\ (反斜杠)</code>, 
					<code>% (百分号)</code>, 
					<code>? (问号)</code>!
				</small>
				<small class="" v-else>不出意外的话, 生成的短链接将为 <span class="t-underline">{{URL + modelValue}}</span> (不区分大小写)</small>
			</p>
		</div>
	</DStep>
</template>
