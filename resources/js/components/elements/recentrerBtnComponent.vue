<template>
    <button id="recenter" @click="recenter">
        <span class="material-symbols-outlined">
            recenter
        </span>
    </button>
</template>

<script>
export default {
    methods: {
        recenter() {
            this.$emit('recenter');
        }
    }
}
</script>

<style>
#recenter {
    color: white;
    background-color: var(--primary);
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 0 0 var(--border-radius-small) var(--border-radius-small) ;
    width: fit-content;
    height: 100%;
    border: transparent;
}
#recenter span{
    color: white;
}
#recenter:hover {
    background-color: var(--primary);
}
</style>