<template>
    <section>
        <component :is="currentView" :Id="currentPathId" />
    </section>

    <div id="infoSupp" @click="toggleInfoSupp">
        <span class="material-symbols-outlined"> info </span>
        <p v-if="infoSupp">
            Projet fait dans le cadre de l'<a href="https://heig-vd.ch/"
                >HEIG-VD</a
            >
        </p>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import axios from "axios";
import Accueil from "@/components/accueil.vue";
import Carte from "@/components/carte-accueil.vue";
import Sentier from "@/components/sentier.vue";
import Etapes from "@/components/steps-list.vue";
import ListeEtapes from "@/components/steps-list.vue";
import Etape from "@/components/step.vue";
import Login from "@/components/login/login.vue";
import Compte from "@/components/login/account.vue";
import CreationSentier from "@/components/sentier-creation.vue";
import EtapesCreation from "@/components/carte-creation.vue";
import EtapeCreation from "@/components/step-creation.vue";
import Favoris from "@/components/favorite.vue";

const routes = {
    "#": {
        component: Accueil,
        label: "Accueil",
    },
    "#accueil": {
        component: Accueil,
        label: "Accueil",
    },
    "#sentier": {
        component: Sentier,
        label: "Sentier",
    },
    "#carte": {
        component: Carte,
        label: "Carte",
    },
    "#steps": {
        component: Etapes,
        label: "Étapes",
    },
    "#stepslist": {
        component: ListeEtapes,
        label: "Carte",
    },
    "#step": {
        component: Etape,
        label: "Étape",
    },
    "#login": {
        component: Login,
        label: "Se connecter",
    },
    "#account": {
        component: Compte,
        label: "Compte",
    },
    "#creationSentier": {
        component: CreationSentier,
        label: "Création de sentier",
    },
    "#creationSteps": {
        component: EtapesCreation,
        label: "Création de sentier",
    },
    "#creationStep": {
        component: EtapeCreation,
        label: "Création de sentier",
    },
    "#favorite": {
        component: Favoris,
        label: "Favoris",
    },
};

const currentPath = ref(window.location.hash);
const infoSupp = ref(false);
const currentPathId = ref();
updateCurrentPath();

// Favoris
let favoris = [];
localStorage.setItem("favoris", JSON.stringify(favoris));

function updateCurrentPath() {
    const path = window.location.hash;
    const hashs = path.split("-");
    const hashSection = hashs[0];
    const hashId = hashs[1];
    currentPath.value = routes[hashSection] ? hashSection : "#";
    currentPathId.value = hashId;

    // Redirection si l'utilisateur n'est pas connecté
    if (!localStorage.getItem("userId") && currentPath.value == "#account") {
        currentPath.value = "#login";
    }
    // Redirection si l'utilisateur est connecté
    if (localStorage.getItem("userId") && currentPath.value == "#login") {
        currentPath.value = "#account";
    }
}

window.addEventListener("hashchange", updateCurrentPath);
const toggleInfoSupp = () =>{
  infoSupp.value = !infoSupp.value
}
const currentView = computed(() => {
    return routes[currentPath.value].component;
});
</script>

<style>
/* Css sur tout le site */

/* Variables pour le site */

:root {
    font-size: 14px;
    /* Couleurs */
    --primary: #40680c;
    --secondary: #69c400;
    --tertiary: #dadada;
    --color-text: #1e1e1e;
    --color-text-secondary: #9e9e9e;

    /* Dimensions */
    --border-radius-small: 12px;
    --border-radius-medium: 20px;
    --border-radius-large: 30px;
    --border-radius-full: 100px;

    --font-size-small: 1rem;
    --font-size-medium: 1.4rem;
    --font-size-large: 1.8rem;

    --box-shadow-light: 0 0px 10px rgba(0, 0, 0, 0.3);
    --box-shadow-light-bottom: 0 8px 10px rgba(0, 0, 0, 0.1);
    --box-shadow-heavy: 0px 0px 20px rgba(0, 0, 0, 0.5);

    /* Espacement */
    --margin-small: 10px;
    --margin-medium: 20px;
    --margin-large: 30px;

    --padding-small: 5px;
    --padding-medium: 10px;
    --padding-large: 20px;

    /* Largeurs et hauteurs */
    --width-full: 100%;
    --height-full: 100%;
    --width-85: 80%;
    --width-fit: fit-content;
    --height-15vh: 15vh;
    --height-100vh: 100vh;

    /* Divers */
    --z-index-high: 5;
    --z-index-medium: 2;
}
body,
html {
    overflow: hidden scroll !important;
    width: 100vw;
    margin: 0;
    min-height: 100vh;
}
/* Éléments  */

* {
    box-sizing: border-box;
    font-family: "Rubik", sans-serif;
    margin: 0;
    padding: 0;
    color: #1e1e1e;
    transition: background-color 0.3s ease;
}
header {
    height: 6vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 7%;
}
footer {
    height: 6vh;
    width: 100vw;
    position: fixed;
    bottom: 0%;
    left: 0;
    background: white;
    z-index: 10;
    border-top: 1px solid var(--tertiary);
}
h2 {
    font-size: 1rem;
    font-weight: 500;
}
img {
    object-fit: cover;
}
section {
    padding: 30px;
    min-height: 90vh;
}
p {
    margin: 0;
}
*:disabled{
  opacity: 0.3;
  cursor: not-allowed;
}
.link {
    color: var(--color-text-secondary);
}
.link.activated {
    color: var(--primary);
    font-weight: 500;
    text-decoration: underline;
}
main {
    height: 10vh;
}
h1 {
    color: var(--primary);
}
.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    margin-bottom: var(--margin-large);
}

/* Input */
input[type="text"] {
    margin-bottom: var(--margin-medium);
    padding: var(--padding-medium) calc(var(--padding-medium) * 2.33);
    font-size: var(--font-size-small);
    width: var(--width-85);
    border-radius: var(--border-radius-large);
    border: 0;
    box-shadow: var(--box-shadow-light);
}
input[type="email"] {
    margin-bottom: var(--margin-medium);
    padding: var(--padding-medium) calc(var(--padding-medium) * 2.33);
    font-size: var(--font-size-small);
    width: var(--width-85);
    border-radius: var(--border-radius-large);
    border: 0;
    box-shadow: var(--box-shadow-light);
}
input[type="password"] {
    margin-bottom: var(--margin-medium);
    padding: var(--padding-medium) calc(var(--padding-medium) * 2.33);
    font-size: var(--font-size-small);
    width: var(--width-85);
    border-radius: var(--border-radius-large);
    border: 0;
    box-shadow: var(--box-shadow-light);
}
.button {
    margin-bottom: var(--margin-medium);
    padding: var(--padding-medium) calc(var(--padding-medium) * 2.33);
    font-size: var(--font-size-small);
    width: var(--width-85);
    border-radius: var(--border-radius-large);
    border: 0;
    box-shadow: var(--box-shadow-light);
    background: var(--primary);
    color: white !important;
    position: relative;
    left: 50%;
    transform: translate(-50%);
}
input[type="submit"] {
    margin-bottom: var(--margin-medium);
    padding: var(--padding-medium) calc(var(--padding-medium) * 2.33);
    font-size: var(--font-size-small);
    width: var(--width-85);
    border-radius: var(--border-radius-large);
    border: 0;
    box-shadow: var(--box-shadow-light);
    background: var(--primary);
    color: white;
}
input[pseudo="-webkit-file-upload-button"] {
    margin-bottom: var(--margin-medium);
    padding: var(--padding-medium) calc(var(--padding-medium) * 2.33);
    font-size: var(--font-size-small);
    width: var(--width-85);
    border-radius: var(--border-radius-large);
    border: 0;
    box-shadow: var(--box-shadow-light);
    background: var(--primary);
    color: white;
}
textarea {
    margin-bottom: var(--margin-medium);
    height: 250px;
    padding: var(--padding-medium) calc(var(--padding-medium) * 2.33);
    font-size: var(--font-size-small);
    width: var(--width-85);
    border-radius: var(--border-radius-large);
    border: 0;
    box-shadow: var(--box-shadow-light);
}

/* Bouton Filtre */

#buttonFiltre {
    padding: 10px;
    box-shadow: var(--box-shadow-light);
    border-radius: var(--border-radius-full);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 5;
    background-color: white;
}
#buttonFiltre span {
    font-size: var(--font-size-medium);
}

/* Map */

.maplibregl-ctrl-group {
    background: none !important;
    box-shadow: none !important;
}
.maplibregl-ctrl-top-right {
    position: absolute;
    z-index: 10;
    top: 37%;
}
.maplibregl-ctrl button {
    background-color: white !important;
}

.visible {
    display: block !important;
}
.material-symbols-outlined {
    font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
}
#infoSupp {
    display: flex;
    flex-direction: row-reverse;
    gap: 10px;
    padding: 10px;
    align-items: center;
    position: fixed;
    height: fit-content;
    width: fit-content;
    bottom: 0;
    right: 0;
    background-color: white;
    z-index: 20;
    border-radius: 20px 0 0 20px;
}
#infoSupp span{
    font-size: 1.3rem;
}

@media only screen and (min-width: 900px) {
  :root{
    --border-radius-small: 12px;
    --border-radius-medium: 20px;
    --border-radius-large: 30px;
    --border-radius-full: 100px;
    /* Espacement */
    --margin-small: 10px;
    --margin-medium: 20px;
    --margin-large: 30px;

    --padding-small: 5px;
    --padding-medium: 10px;
    --padding-large: 20px;

    /* Largeurs et hauteurs */
    --width-full: 100%;
    --height-full: 100%;
    --width-85: 80%;
    --width-fit: fit-content;
    --height-15vh: 15vh;
    --height-100vh: 100vh;

    font-size: 16px;
  }

  header{
margin-bottom: 2% ;
justify-content: flex-start;
padding: 0 20%;
  }
  .header{
    padding: 0 20%;
  }
}
</style>
