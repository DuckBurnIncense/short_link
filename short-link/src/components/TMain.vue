<script setup>
    import {computed, ref, reactive} from 'vue';
    import DStep from './DStep.vue';
    import DInput from './DInput.vue';
    import { 
        faLink,
        faTriangleExclamation,
    } from '@fortawesome/free-solid-svg-icons';
    import {useXhrPost} from '@/hooks/xhr.js';

    var d = reactive({
        link: '',
        suffix: '',
    });

    var isSuffixIllegal = computed(() => 
        d.suffix.includes('#') || 
        d.suffix.includes('/') || 
        d.suffix.includes(' ') || 
        d.suffix.includes('\\')
    );
    var isLinkIllegal = computed(() => 
        !/^((https?:)?\/\/)?(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()!@:%_\+.~#?&\/\/=]*)$/
        .test(d.link)
    );

    const genSuffix = () => {
        let t = 'qwertyuiopasdfghjklzxcvbnm1234567890-';
        let arr = t.split('');
        let g = '';
        for (let i = 0; i < 6; i++) {
            let r = ~~(Math.random() * 36);
            g += arr[r];
        }
        d.suffix = g;
    }

    let submitTip = ref('');
    let errorMsg = ref('');
    let successLink = ref('');

    function submit() {
        if (isSuffixIllegal.value || isLinkIllegal.value) {
            setTimeout(() => {submitTip.value = ''}, 2000);
            return submitTip.value = '请将表单填写完整!';
        }
        useXhrPost('/api/?p=set', d).then(v => {
            successLink.value = 'https://链.ml/' + v;
            errorMsg.value = '';
        }).catch(e => {
            successLink.value = '';
            errorMsg.value = '错误: ' + e.message;
        });
    }
</script>

<template>
    <DStep>
        <template #icon>
            <font-awesome-icon :icon="faLink" />
        </template>
        <template #heading>第一步</template>
        <div class="step1">
            <p>第一步: 输入待缩短的链接</p>
            <p><DInput v-model="d.link" placeholder="在此处输入长链接" :style="{width: '100%'}" /></p>
            <p v-show="1">
                <small v-show="isLinkIllegal" class="cl-red"><font-awesome-icon :icon="faTriangleExclamation" />链接不合法!</small>
            </p>
        </div>
    </DStep>
    <DStep>
        <template #icon>
            <font-awesome-icon :icon="faLink" />
        </template>
        <template #heading>第二步</template>
        <div class="step2">
            <p>第二步: 设置短链接后缀</p>
            <p>https://链.ml/ <DInput v-model="d.suffix" placeholder="在此处设置短链接后缀" /></p>
            <p>
                <small class="cur-pot" v-if="d.suffix == ''" @click="genSuffix()">[点击此处可随机生成]</small>
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
        <div class="step3">
            <p>第三步: 提交</p>
            <p>
                <button @click="submit()">提交</button>
            </p>
            <p v-show="submitTip" class="cl-red">
                <small>
                    <font-awesome-icon :icon="faTriangleExclamation" />
                    {{submitTip}}
                </small>
            </p>
        </div>
    </DStep>
    <DStep>
        <template #icon>
            <font-awesome-icon :icon="faLink" />
        </template>
        <template #heading>第四步</template>
        <div class="step4">
            <p>第四步: 得到缩短后的链接</p>
            <p v-if="successLink">
                缩短后的链接 (点击复制): <a @click="0" class="cur-pot">{{successLink}}</a>
            </p>
            <p v-else-if="errorMsg" class="cl-red">
                <small>
                    <font-awesome-icon :icon="faTriangleExclamation" />
                    {{errorMsg}}
                </small>
            </p>
            <p v-else>
                链接将会显示在这里
            </p>
        </div>
    </DStep>
</template>
