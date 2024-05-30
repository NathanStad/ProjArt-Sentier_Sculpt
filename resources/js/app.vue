<template>
    <p v-if="error">{{ error }}</p>
    <!-- Menu (Seulement sur desktop) -->
    <header>

    </header>

    <!-- Accueil -->
    <section :class="{ active: currentSection === 'accueil' }">
        <Accueil v-if="currentSection === 'accueil'"/>
    </section>
    
    <!-- Carte -->
    <section :class="{ active: currentSection === 'carte' }">
        <!-- <Carte/> -->
    </section>
    
    <!-- Sentier -->
    <section :class="{ active: currentSection === 'sentier' }">
        <Sentier v-if="currentSection === 'sentier'" :sentierId="{idSection}"/>
      </section>
      
      <!-- Étapes -->
      <section :class="{ active: currentSection === 'steps' }">
        <Etapes v-if="currentSection === 'steps'" :sentierId="{idSection}"/> 
      </section>
      
      <!-- Étape -->
      <section :class="{ active: currentSection === 'step' }">
        <Etape v-if="currentSection === 'step'" :etapeId="{idSection}"/>
    </section>
    
    <!-- Dernière Étape -->
    <section :class="{ active: currentSection === 'derniere-etape' }">
        
    </section>
    
    <!-- Favoris -->
    <section :class="{ active: currentSection === 'favoris' }">
        
    </section>
    
    <!-- Accueil -->
    <section :class="{ active: currentSection === 'accueil' }">
        
    </section>
    
    <!-- Création sentier -->
    <section :class="{ active: currentSection === 'creation-sentier' }">
        
    </section>
    
    <!-- Création d'étape -->
    <section :class="{ active: currentSection === 'creation-etape' }">
        
    </section>
    
    <!-- Account -->
    <section :class="{ active: currentSection === 'account' }">
        
    </section>
    
    <!-- Login -->
    <section :class="{ active: currentSection === 'login' }">
        
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
import Sentier from "@/components/sentier.vue";
import Etapes from "@/components/steps-list.vue";
import Etape from "@/components/step.vue";

export default {
  components: {
    Accueil,
    Carte,
    Sentier,
    Etapes,
    Etape
  },
  data() {
    return {
      currentSection: 'accueil',
      idSection: '',
      error: null
    };
  },
  created() {
    // Gérer le changement d'URL lors de l'initialisation
    this.handleHashChange();
    // Écouter les changements d'URL
    window.addEventListener('hashchange', this.handleHashChange);
  },
  beforeUnmount() {
    // Nettoyer les écouteurs d'événements
    window.removeEventListener('hashchange', this.handleHashChange);
  },
  methods: {
    handleHashChange() {

      // Sélectionnez tous les éléments <section>
      const sections = document.querySelectorAll('section');

      // Parcourez chaque élément <section>
      sections.forEach(section => {
          // Supprimez la classe "active"
          section.classList.remove('active');
      });

      // Extraire la section de l'URL après le #
      const hash = window.location.hash.slice(1);
      const hashs = hash.split('-')
      const hashSection = hashs[0]
      const hashId = hashs[1]
      // Mettre à jour la section actuelle en fonction de l'URL
      this.currentSection = hashSection || 'accueil';
      this.idSection = hashId
    }
  }
};
</script>

<style>
    section {
        display: none;
    }
    section.active {
        display: block;
    }
</style>
