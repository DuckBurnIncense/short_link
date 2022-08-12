<template>
    <div class="step">
        <span class="icon-wrapper">
            <slot name="icon"></slot>
        </span>
        <div class="details">
            <h3 class="heading">
                <slot name="heading"></slot>
            </h3>
            <slot></slot>
        </div>
    </div>
</template>

<style scoped lang="less">
    .step {
        display: flex;
        padding: 1em 3em;

        .icon-wrapper {
            display: flex;
            place-items: center;
            place-content: center;
            width: 32px;
            height: 32px;
            margin-top: 1em;
        }
        .details {
            flex: 1;
            margin-left: 0.2em;

            .heading {
                font-size: 1.2rem;
                font-weight: bold;
                margin: 1em 0 0.4rem 0;
                color: white;
            }
        }
    }
</style>
