<script setup>
    import { ref } from 'vue';
    import DStep from '@/components/DStep.vue';
    import DQRCode from '@/components/DQRCode.vue';
    import { 
        faArrowUpRightFromSquare,
        faTriangleExclamation,
    } from '@fortawesome/free-solid-svg-icons';
    import { useCopy } from '@/hooks/clipboard.js';

	const props = defineProps({
		shortLink: {
			type: String
		},
		error: {
			type: String
		}
	});

    let copyTip = ref('点击复制');

    function copyLink() {
        useCopy(props.shortLink);
        copyTip.value = '已复制短链接到剪贴板';
    }
</script>

<template>
    <DStep>
        <template #icon>
            <font-awesome-icon :icon="faArrowUpRightFromSquare" />
        </template>
        <template #heading>第六步</template>
        <div class="step5">
            <p>第六步: 得到缩短后的链接</p>
            <p v-if="shortLink">
                <p>缩短后的链接 ({{copyTip}}): <a @click="copyLink()" class="cur-pot">{{shortLink}}</a></p>
                <p><DQRCode :value="shortLink"></DQRCode></p>
            </p>
            <p v-else-if="error" class="cl-red">
                <small>
                    <font-awesome-icon :icon="faTriangleExclamation" />
                    {{error}}
                </small>
            </p>
            <p v-else>
                生成后的链接将会显示在这里
            </p>
        </div>
    </DStep>
</template>
