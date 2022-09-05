<script setup>
    import { ref, watch, watchEffect } from 'vue';
    import DStep from '@/components/DStep.vue';
    import DRadio from '@/components/DRadio.vue';
    import { 
        faCalendarDays,
    } from '@fortawesome/free-solid-svg-icons';
	import Datepicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css';
	import { useFormat as formatTimestamp } from '@/hooks/timestamp.js';
	
	const props = defineProps({
		// 最终返回的时间戳
		modelValue: {
			type: Number,
			required: 1
		}
	});

	const $emit = defineEmits([
		'update:modelValue'
	]);

	// 提交
	const emit = v => $emit('update:modelValue', v);

	// 选项
	const opts = ['永不过期','一天','一周','一个月','半年','一年','自定义'];
	// 是否为自定义
	var useCus = ref(false);
	// 自定义的时间对象
	var cusExpire = ref(new Date());

	// 修改单选框
	const changeExpireDate = i => {
		if (i == 0) {
			// 如果是永不过期
			useCus.value = false;
			emit(0);
		} else if (i == 6) {
			// 如果是自定义
			useCus.value = true;
			// 如果前一个选项是自定义则需把时间更改为现在的时间
			if (props.modelValue == 0) emit(~~(new Date().getTime() / 1000));
		} else {
			// 如果是预设
			// opts 对应的天数
			let map = [0, 1, 7, 30, 182, 365, -1]
			useCus.value = false;
			// 提交
			emit( ~~( (
					new Date().setDate(
						new Date().getDate() + map[i]
					)
				) / 1000)
			);
		}
	}

	// 如果 cusExpire 更改则同步提交 (emit) 到 modelValue
	watch(cusExpire, () => {
		emit(~~(cusExpire.value.getTime() / 1000));
	}, {
		immediate: false
	});
	// 如果 modelValue 更新则同步更新 cusExpire
	watchEffect(() => {
		cusExpire.value = new Date(props.modelValue * 1000);
	});
</script>

<template>
    <DStep>
        <template #icon>
            <font-awesome-icon :icon="faCalendarDays" />
        </template>
        <template #heading>第四步</template>
        <div class="step3">
            <p>第四步 (可选): 设置一个过期时间</p>
            <p>在过期时间到达后将不能从短链接访问原始链接 (长链接)</p>
            <p>
                <DRadio 
                    :opts="opts"
                    :default="0"
                    @change="changeExpireDate"
                ></DRadio>
            </p>
            <p v-if="useCus">
                <Datepicker 
                    v-model="cusExpire"
                    format="yyyy-MM-dd HH:mm"
                    dark
                    inline
                    autoApply
                    :monthChangeOnScroll="false"
                    :minDate="new Date()"
                    selectText="确定选择"
                    cancelText="取消"
                >
                    <template #calendar-header="{ index, day }">
                        <span v-if="index == 0">周一</span>
                        <span v-else-if="index == 1">周二</span>
                        <span v-else-if="index == 2">周三</span>
                        <span v-else-if="index == 3">周四</span>
                        <span v-else-if="index == 4">周五</span>
                        <span v-else-if="index == 5">周六</span>
                        <span v-else-if="index == 6">周日</span>
                    </template>
                </Datepicker>
            </p>
            <p>
                过期时间: {{modelValue != 0 ? formatTimestamp(modelValue) : '永不过期'}}
            </p>
        </div>
    </DStep>
</template>
