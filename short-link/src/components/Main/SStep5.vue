<script setup>
    import { ref, computed, watchEffect } from 'vue';
    import DStep from '@/components/DStep.vue';
    import DButton from '@/components/DButton.vue';
    import { 
        faCircleCheck,
        faTriangleExclamation,
    } from '@fortawesome/free-solid-svg-icons';
	import { useXhrPost } from '@/hooks/xhr.js';
    import { isURL, isStringHasSpecialChars } from '@/hooks/regexr';
	import config from '@/hooks/config.js';

	const URL = config.global.URL;

	const props = defineProps({
		data: {
			type: Object,
			required: 1
		},
		callback: {
			type: Function,
			required: 1
		}
	});

	// 长链接是否合法
    var isLinkIllegal = computed(() => !isURL(props.data.link));
	// 后缀是否合法
    var isSuffixIllegal = computed(() => isStringHasSpecialChars(props.data.suffix));
	// 密码是否合法
    var isPasswordIllegal = computed(() => isStringHasSpecialChars(props.data.password));

	var tip = ref();
	var illegal = ref(true);
	watchEffect(() => {
		illegal.value = true;
		if (isLinkIllegal.value) return tip.value = '链接不合法!';
		if (isSuffixIllegal.value) return tip.value = '后缀不合法!';
		if (isPasswordIllegal.value) return tip.value = '密码不合法!';
		if (props.data.suffix == '') return tip.value = '请填写或生成后缀!';
		tip.value = '';
		illegal.value = false;
	});

	var loading = ref(false);
	function submit() {
        loading.value = true;
        useXhrPost('/api/set', {
            link: props.data.link,
            suffix: props.data.suffix,
            password: props.data.password,
            expire: props.data.expire
        }).then(v => {
			props.callback(URL + v, '');
            loading.value = false;
        }).catch(e => {
			props.callback(null, '错误: ' + e.message);
            loading.value = false;
        });
    }
</script>

<template>
    <DStep>
        <template #icon>
            <font-awesome-icon :icon="faCircleCheck" />
        </template>
        <template #heading>点击提交按钮</template>
        <div class="step4">
            <p>第五步: 点击提交按钮</p>
            <p>
                <DButton @click="submit()" :disabled="loading || illegal">{{loading ? '提交中' : '提交'}}</DButton>
            </p>
            <p v-show="tip" class="cl-red">
                <small>
                    <font-awesome-icon :icon="faTriangleExclamation" />
                    {{tip}}
                </small>
            </p>
        </div>
    </DStep>
</template>
