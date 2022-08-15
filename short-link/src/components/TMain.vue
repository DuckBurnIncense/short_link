<script setup>
    import { computed, ref, reactive } from 'vue';
    import DStep from './DStep.vue';
    import DInput from './DInput.vue';
    import DButton from './DButton.vue';
    import { useXhrPost } from '@/hooks/xhr.js';
    import { useCopy } from '@/hooks/clipboard.js';
    import { 
        faLink,
        faTriangleExclamation,
    } from '@fortawesome/free-solid-svg-icons';

    // 请求后端的参数
    var d = reactive({
        link: '',
        suffix: '',
        password: '',
    });

    // 特殊字符
    const SPEC_CHARS = ['#', '/', ' ', '\\', '%', '?'];
    function isStrHasSpecChar(s) {
        let r = false;
        SPEC_CHARS.forEach(c => {
            if (s.includes(c)) r = true;
        });
        return r;
    }

    // 后缀是否合法
    var isSuffixIllegal = computed(() => isStrHasSpecChar(d.suffix));

    // 链接是否合法
    var isLinkIllegal = computed(() => 
        !/^((https?:)?\/\/)?(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()!@:%_\+.~#?&\/\/=]*)$/
        .test(d.link)
    );

    // 密码是否合法
    var isPasswordIllegal = computed(() => isStrHasSpecChar(d.password));

    // 随机生成后缀
    const generateSuffix = () => {
        let t = 'qwertyuiopasdfghjklzxcvbnm1234567890-';
        let arr = t.split('');
        let g = '';
        for (let i = 0; i < 6; i++) {
            let r = ~~(Math.random() * 36);
            g += arr[r];
        }
        d.suffix = g;
    }

    // 各种提示
    let submitTip = ref('');
    let copyTip = ref('点击复制');
    let errorMsg = ref('');
    let loading = ref(false);

    // 请求成功活获得的短链接
    let shortLink = ref('');

    // 提交
    function submit() {
        if (isSuffixIllegal.value || isLinkIllegal.value || isPasswordIllegal.value || !d.link) {
            setTimeout(() => {submitTip.value = ''}, 2000);
            return submitTip.value = '请先将表单填写完整!';
        }
        loading.value = true;
        useXhrPost('/api/set', d).then(v => {
            shortLink.value = 'https://链.ml/' + v;
            errorMsg.value = '';
            loading.value = false;
        }).catch(e => {
            shortLink.value = '';
            errorMsg.value = '错误: ' + e.message;
            loading.value = false;
        });
    }

    // 复制链接
    function copyLink() {
        useCopy(shortLink.value);
        copyTip.value = '已复制短链接到剪贴板';
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
                <small class="cur-pot t-underline" v-if="d.suffix == ''" @click="generateSuffix()">[点击此处可随机生成]</small>
                <small class="cl-red" v-else-if="isSuffixIllegal">
                    <font-awesome-icon :icon="faTriangleExclamation" />
                    后缀中不能含有以下字符: 
                    <code>&nbsp; (空格)</code>, 
                    <code># (井号)</code>, 
                    <code>/ (斜杠)</code>, 
                    <code>\ (反斜杠)</code>, 
                    <code>% (百分号)</code>, 
                    <code>? (问号)</code>!
                </small>
                <small class="" v-else>不出意外的话, 生成的短链接将为 <span class="t-underline">https://链.ml/{{d.suffix}}</span> (不区分大小写)</small>
            </p>
        </div>
    </DStep>
    <DStep>
        <template #icon>
            <font-awesome-icon :icon="faLink" />
        </template>
        <template #heading>第三步</template>
        <div class="step3">
            <p>第三步 (可选): 设置一个访问密码</p>
            <p>只有正确输入访问密码才能访问原始链接 (长链接)</p>
            <p><DInput v-model="d.password" placeholder="可在此处设置密码" :style="{width: '100%'}" /></p>
            <small class="cl-red" v-show="isPasswordIllegal">
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
    <DStep>
        <template #icon>
            <font-awesome-icon :icon="faLink" />
        </template>
        <template #heading>第四步</template>
        <div class="step4">
            <p>第四步: 提交</p>
            <p>
                <DButton @click="submit()" :disabled="loading">{{loading ? '提交中' : '提交'}}</DButton>
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
        <template #heading>第五步</template>
        <div class="step5">
            <p>第五步: 得到缩短后的链接</p>
            <p v-if="shortLink">
                缩短后的链接 ({{copyTip}}): <a @click="copyLink()" class="cur-pot">{{shortLink}}</a>
            </p>
            <p v-else-if="errorMsg" class="cl-red">
                <small>
                    <font-awesome-icon :icon="faTriangleExclamation" />
                    {{errorMsg}}
                </small>
            </p>
            <p v-else>
                生成后的链接将会显示在这里
            </p>
        </div>
    </DStep>
</template>
