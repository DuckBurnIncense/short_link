<script setup>
    import { ref, reactive } from 'vue';
    import SStep1 from './SStep1.vue';
    import SStep2 from './SStep2.vue';
    import SStep3 from './SStep3.vue';
    import SStep4 from './SStep4.vue';
    import SStep5 from './SStep5.vue';
    import SStep6 from './SStep6.vue';

    const d = reactive({
        link: '',
        suffix: '',
        password: '',
        expire: 0
    });

    var shortLink = ref(),
        error = ref();
    function cb(s, e) {
        shortLink.value = s;
        error.value = e;
    }
</script>

<template>
    <SStep1 v-model="d.link"></SStep1>
    <SStep2 v-model="d.suffix"></SStep2>
    <SStep3 v-model="d.password"></SStep3>
    <SStep4 v-model="d.expire"></SStep4>
    <SStep5 :data="d" :callback="cb"></SStep5>
    <SStep6 :shortLink="shortLink" :error="error"></SStep6>
</template>
