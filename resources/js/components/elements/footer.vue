<template>
    <nav>
        <a :href="'#'" :class="{ 'footer-active': site === '#' }"><span class="material-symbols-outlined"> home </span> <h2>Accueil</h2></a>
        <a :href="'#carte'" :class="{ 'footer-active': site === '#carte' }"><span class="material-symbols-outlined"> map </span> <h2>Carte</h2></a>
        <a :href="'#favorite'" :class="{ 'footer-active': site === '#favorite' }"><span class="material-symbols-outlined"> favorite </span> <h2>Favoris</h2></a>
        <a :href="'#login'" :class="{ 'footer-active': site === '#login' }"><span class="material-symbols-outlined"> person </span> <h2>Compte</h2></a>
    </nav>
</template>
<script setup>
import {defineProps} from 'vue';

const props = defineProps({
  site: {
    type: String,
    required: true,
  },
});

</script>
<style>
nav{
    display: flex;
    justify-content: space-around;
    align-items: center;
    height: 100%;
}
nav a{
  text-decoration: none;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
</style>
