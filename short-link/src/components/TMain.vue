<script setup>
    import {computed, reactive} from 'vue';
    import DStep from './DStep.vue';
    import DInput from './DInput.vue';
    import { 
        faLink,
        faTriangleExclamation,
    } from '@fortawesome/free-solid-svg-icons';
    var d = reactive({
        longLink: '',
        suffix: '',
    });
    var isSuffixIllegal = computed(() => 
        d.suffix.includes('#') || 
        d.suffix.includes('/') || 
        d.suffix.includes(' ') || 
        d.suffix.includes('\\')
    );
    var isLongLinkIllegal = computed(() => !/^((https?:)?\/\/)?(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()!@:%_\+.~#?&\/\/=]*)$/.test(d.longLink));
</script>

<template>
    <DStep>
        <template #icon>
            <font-awesome-icon :icon="faLink" />
        </template>
        <template #heading>第一步</template>
        <div class="step1">
            <p>第一步: 输入待缩短的链接</p>
            <p><DInput v-model="d.longLink" placeholder="在此处输入长链接" :style="{width: '100%'}" /></p>
            <p v-show="1">
                <small v-show="isLongLinkIllegal" class="cl-red"><font-awesome-icon :icon="faTriangleExclamation" />链接不合法!</small>
            </p>
        </div>
    </DStep>
    <DStep>
        <template #icon>
            <font-awesome-icon :icon="faLink" />
        </template>
        <template #heading>第二步</template>
        <div class="step1">
            <p>第二步 (可选): 设置短链接后缀</p>
            <p>https://链.ml/ <DInput v-model="d.suffix" placeholder="在此处设置短链接后缀" /></p>
            <p>
                <small class="" v-if="d.suffix == ''">不填则自动随机生成</small>
                <small class="cl-red" v-else-if="isSuffixIllegal">
                    <font-awesome-icon :icon="faTriangleExclamation" />
                    后缀中不能含有以下字符: 
                    <code> (空格)</code>, 
                    <code># (井号)</code>, 
                    <code>/ (斜杠)</code>, 
                    <code>\ (反斜杠)</code>!
                </small>
                <small class="" v-else>不出意外的话, 生成的链接将为 <span class="t-underline">https://链.ml/{{d.suffix}}</span></small>
            </p>
        </div>
    </DStep>
    <DStep>
        <template #icon>
            <font-awesome-icon :icon="faLink" />
        </template>
        <template #heading>第三步</template>
        <div class="step1">
            <p>第三步: 提交</p>
            <p>
                <button>提交</button>
            </p>
        </div>
    </DStep>
    <DStep>
        <template #icon>
            <font-awesome-icon :icon="faLink" />
        </template>
        <template #heading>第四步</template>
        <div class="step1">
            <p>第四步: 得到缩短后的链接</p>
            <p>
                缩短后的链接 (点击复制): <a @click="0">https://链.ml/abc123</a>
            </p>
        </div>
    </DStep>
</template>
