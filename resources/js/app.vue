<template>
    <p v-if="error">{{ error }}</p>
    <!-- Menu (Seulement sur desktop) -->
    <header>

    </header>

    <!-- Accueil -->
    <section>
        <!-- <Accueil /> -->
    </section>

    <!-- Carte -->
    <section>
        <CarteAccueil />
    </section>

    <!-- Accueil -->
    <section>

    </section>

    <!-- Sentier -->
    <section>
        <CarteSentier />
    </section>

    <!-- Étape -->
    <section>

    </section>

    <!-- Dernière Étape -->
    <section>

    </section>

    <!-- Favoris -->
    <section>

    </section>

    <!-- Accueil -->
    <section>

    </section>

    <!-- Création sentier -->
    <section class="active">
        <CarteCreation />
    </section>

    <!-- Création d'étape -->
    <section>
        <CreationEtape />
    </section>

    <!-- Account -->
    <section>

    </section>

    <!-- Login -->
    <section>

    </section>

    <!-- Footer / Nav -->
    <footer>

    </footer>
</template>

<script>
import { ref, onMounted } from 'vue';

import axios from "axios";
import Accueil from "@/components/accueil.vue";
import Carte from "@/components/carte.vue";
import CarteAccueil from "@/components/carte-accueil.vue";
import CarteSentier from "@/components/carte-sentier.vue";
import CarteCreation from "@/components/carte-creation.vue";
import CreationEtape from "@/components/etape-creation.vue";
import 'normalize.css'


// import DetailEtape from "@/components/details-step.vue";
// import Favortie from "@/components/favorite.vue";
// import SentierCreation from "@/components/sentier-creation.vue";
// import Sentier from "@/components/sentier.vue";
// import EtapeCreation from "@/components/step-creation.vue";
// import Etape from "@/components/step.vue";
// import EtapeList from "@/components/steps-list.vue";
// import Header from "@/components/header.vue";
// import Footer from "@/components/footer.vue";

export default {
    components: {
        Accueil,
        Carte,
        CarteAccueil,
        CarteSentier,
        CarteCreation,
        CreationEtape,
    }
};

</script>

<style>
html,
body {
    margin: 0;
    padding: 0;
    height: 100%;
    display: flex;
    flex-direction: column;
}

section {
    display: none;
}

section.active {
    display: block;
}
</style>