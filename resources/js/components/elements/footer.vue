<template>
    <nav>
        <a :href="'#'" :class="{ 'footer-active': site === '' || site === '#' }"><span class="material-symbols-outlined"> home </span> <h2>Accueil</h2></a>
        <a :href="'#carte'" :class="{ 'footer-active': site === '#carte' }"><span class="material-symbols-outlined"> map </span> <h2>Carte</h2></a>
        <a :href="'#favorite'" :class="{ 'footer-active': site === '#favorite' }"><span class="material-symbols-outlined"> favorite </span> <h2>Favoris</h2></a>
        <a :href="'#login'" :class="{ 'footer-active': site === '#login' || site === '#account' }"><span class="material-symbols-outlined"> person </span> <h2>Compte</h2></a>
    </nav>
</template>
<script setup>
import {ref, watch} from 'vue';

const site = ref(window.location.hash) 
</script>
<style scoped>
nav{
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100%;
    padding: 0 40px;
}
nav a{
  text-decoration: none;
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 60px;
 justify-content: center;
}
nav h2{
  font-size: 0.8rem;
}
nav span{
  font-size: 1.7rem;
  position: relative;
}
.footer-active h2
{
  color: var(--primary);
  font-weight: 700;
  position: relative;
  bottom: 15px;
  }
.footer-active span{
  color: white;
  padding: 15px;
  border-radius: 50px;
  background: var(--primary);
  font-variation-settings:
  'FILL' 1,
  'wght' 400,
  'GRAD' 0,
  'opsz' 24;
  bottom:15px;
}
@media only screen and (min-width: 900px) {
  nav{
    padding: 0 20%;
  }
}
</style>
