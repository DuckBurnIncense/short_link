<script setup>
    import THeader from './components/Header/THeader.vue';
    import TMain from './components/Main/TMain.vue';
    import TFooter from './components/Footer/TFooter.vue';
    import TPassword from './components/TPassword.vue';
    import config from '@/hooks/config.js';

    function getQueryVariable(variable){
        let query = window.location.search.substring(1);
        let vars = query.split("&");
        for (let i=0;i<vars.length;i++) {
            let pair = vars[i].split("=");
            if(pair[0] == variable){return pair[1];}
        }
        return(false);
    }

    const suffix = getQueryVariable('suffix');

    document.title = import.meta.env.DEV ? ('开发环境 - ' + config.head.title) : config.head.title;
    if (config.global.consoleLog) console.log(config.global.consoleLog);
    if (config.head.faviconIco) {
        let link = document.querySelector("link[rel*='icon']") || document.createElement('link');
        link.type = 'image/x-icon';
        link.rel = 'shortcut icon';
        link.href = config.head.faviconIco;
        document.getElementsByTagName('head')[0].appendChild(link);
    }
    if (config.head.metaDescription) {
        let meta = document.querySelector("meta[name*='description']") || document.createElement('link');
        meta.content = config.head.metaDescription;
        document.getElementsByTagName('head')[0].appendChild(meta);
    }
</script>

<template>
    <div id="root">
        <div class="container" v-if="!suffix">
            <THeader></THeader>
            <TMain></TMain>
            <TFooter></TFooter>
        </div>
        <div class="container" v-else>
            <TPassword :suffix="suffix"></TPassword>
        </div>
    </div>
</template>

<style scoped lang="less">
    #root {
        color-scheme: dark;
        display: grid; 
        grid-auto-columns: 1fr; 
        grid-template-columns: 1fr 900px 1fr; 
        gap: 0px 0px; 
        grid-template-areas: ". container ."; 
        .container {
            grid-area: container;
        }
    }
    @media screen and (max-width: 960px) {
        #root {
            display: block; 
        }
    }
</style>

<style>
    #root {
        background-color: #181818;
        color: #939393;
        height: 100%;
        width: 100%;
        overflow: auto;
    }
    a {
        color: orange;
        text-decoration: none;
    }
    a:hover {
        color: yellow;
        text-decoration: underline;
    }
    .b {
        font-weight: bolder;
    }
    * {
        box-sizing: border-box;
        word-wrap: break-word;
        word-break: break-all;
    }
    .cl-red {
        color: red;
    }
    .t-underline {
        text-decoration: underline;
    }
    code {
        background-color: #939393;
        padding: 3px;
        border-radius: 2px;
        margin: 0 2px;
    }
    .cur-pot {
        cursor: pointer;
    }
    .danger-box {
        color: red;
        border: 1px solid red;
        border-radius: 5px;
        box-shadow: 0 0 1em red;
        padding: 0.5em;
    }
</style>
