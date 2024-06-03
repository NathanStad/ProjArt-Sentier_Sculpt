<template>
  <!-- Menu (Seulement sur desktop) -->
  <header>
    <Header></Header>
  </header>

  <!-- Accueil -->
  <section>
    <component :is="currentView" :Id="currentPathId"/>
  </section>

  <!-- Footer / Nav -->
  <footer>
    <Footer></Footer>
  </footer>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from "axios";
import Accueil from "@/components/accueil.vue";
import Carte from "@/components/carte.vue";
import Sentier from "@/components/sentier.vue";
import Etapes from "@/components/steps-list.vue";
import Etape from "@/components/step.vue";
import Login from "@/components/login/login.vue";
import Compte from "@/components/login/account.vue";
import CreationSentier from "@/components/sentier-creation.vue";
import Favoris from "@/components/favorite.vue";

import Footer from "@/components/elements/footer.vue";
import Header from "@/components/elements/header.vue";

const routes = {
  '#': {
    component: Accueil,
    label: 'Bookmarks',
  },
  '#sentier': {
    component: Sentier,
    label: 'Sentier',
  },
  '#carte': {
    component: Carte,
    label: 'Carte',
  },
  '#steps': {
    component: Etapes,
    label: 'Étapes',
  },
  '#step': {
    component: Etape,
    label: 'Étape',
  },
  '#login': {
    component: Login,
    label: 'Se connecter',
  },
  '#account': {
    component: Compte,
    label: 'Compte',
  },
  '#creationSentier': {
    component: CreationSentier,
    label: 'Création de sentier',
  },
  '#favorite': {
    component: Favoris,
    label: 'Favoris',
  }
};

const currentPath = ref(window.location.hash);
const currentPathId = ref();
updateCurrentPath();

// Favoris
let favoris = [];
localStorage.setItem('favoris', JSON.stringify(favoris));

function updateCurrentPath() {
  const path = window.location.hash;
  const hashs = path.split('-');
  const hashSection = hashs[0];
  const hashId = hashs[1];
  currentPath.value = routes[hashSection] ? hashSection : '#';
  currentPathId.value = hashId;
  console.log(currentPath.value);
  console.log(currentPathId.value);
}

window.addEventListener('hashchange', updateCurrentPath);

const currentView = computed(() => {
  return routes[currentPath.value].component;
});
</script>

<style>
* {
  box-sizing: border-box;
}
header {
  height: 10vh;
  display: flex;
  justify-content: center;
  align-items: center;
}
footer{
  height: 10vh;
  width: 100vw;
  position: fixed;
  bottom: 0;
  left: 0;
  background: white;
}
body {
  overflow: hidden scroll;
  width: 100vw;
  margin: 0;
}
section{
  padding: 10px;
}
</style>
