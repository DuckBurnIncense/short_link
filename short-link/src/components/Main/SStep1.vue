<script setup>
    import { computed } from 'vue';
    import DStep from '@/components/DStep.vue';
    import DInput from '@/components/DInput.vue';
    import { 
        faLink,
        faTriangleExclamation,
    } from '@fortawesome/free-solid-svg-icons';
    import { isURL } from '@/hooks/regexr';

	const props = defineProps({
		modelValue: {
			type: String,
			required: 1
		}
	});

	const $emit = defineEmits([
		'update:modelValue'
	]);

    const value = computed({
        get() {
            return props.modelValue
        },
        set(newValue) {
            return $emit('update:modelValue', newValue);
        }
    });

    // 链接是否合法
    var isLinkIllegal = computed(() => !isURL(props.modelValue));
</script>

<template>
    <DStep>
        <template #icon>
            <font-awesome-icon :icon="faLink" />
        </template>
        <template #heading>第一步</template>
        <div class="step1">
            <p>第一步: 输入待缩短的链接</p>
            <p><DInput v-model="value" placeholder="在此处输入长链接" :style="{width: '100%'}" /></p>
            <p v-show="isLinkIllegal">
                <small class="cl-red"><font-awesome-icon :icon="faTriangleExclamation" />链接不合法!</small>
            </p>
        </div>
    </DStep>
</template>
