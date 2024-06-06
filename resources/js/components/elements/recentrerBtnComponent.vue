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
    background-color: green;
    border: none;
    cursor: pointer;
    border-radius: 0 0 var(--border-radius-small) var(--border-radius-small) ;
    width: fit-content;
    height: 100%;
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2);
}
#recenter span{
    color: white;
}
#recenter:hover {
    background-color: darkgreen;
}
</style>