<script setup>
    import THeader from './components/THeader.vue';
    import TMain from './components/TMain.vue';
    import TFooter from './components/TFooter.vue';
    import TPassword from './components/TPassword.vue';

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
    }
</style>
