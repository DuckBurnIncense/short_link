<script setup>
    import { computed } from 'vue';
    import DStep from '@/components/DStep.vue';
    import DInput from '@/components/DInput.vue';
    import { 
        faKey,
        faTriangleExclamation,
    } from '@fortawesome/free-solid-svg-icons';
    import { isStringHasSpecialChars } from '@/hooks/regexr';
    
    // props
	const props = defineProps({
        modelValue: {
            type: String,
			required: 1
		}
	});
    
    // emit
	const $emit = defineEmits([
        'update:modelValue'
	]);

    // 密码输入框的值
    const value = computed({
        get() {
            return props.modelValue
        },
        set(newValue) {
            return $emit('update:modelValue', newValue);
        }
    });

    // 密码是否合法
    var isPasswordHasSpecialChars = computed(() => isStringHasSpecialChars(props.modelValue));
</script>

<template>
    <DStep>
        <template #icon>
            <font-awesome-icon :icon="faKey" />
        </template>
        <template #heading>设置一个访问密码 (可选)</template>
        <div class="step3">
            <p>第三步 (可选): 设置一个访问密码</p>
            <p>只有正确输入访问密码才能访问原始链接 (长链接)</p>
            <p><DInput v-model="value" placeholder="可在此处设置密码" :style="{width: '100%'}" /></p>
            <small class="cl-red" v-show="isPasswordHasSpecialChars">
                <font-awesome-icon :icon="faTriangleExclamation" />
                密码中不能含有以下字符: 
                <code>&nbsp; (空格)</code>, 
                <code># (井号)</code>, 
                <code>/ (斜杠)</code>, 
                <code>\ (反斜杠)</code>, 
                <code>% (百分号)</code>, 
                <code>? (问号)</code>!
            </small>
        </div>
    </DStep>
</template>
