<template>
    <div v-if="isLoading">
      <p>Loading...</p>
    </div>
    <div id="accueil" v-else>
      <header>
        <Header />
      </header>
      <!-- Barre de recherche -->
      <div class="header">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Rechercher par lieu et nom"
          class="recherche"
        />
        <span class="material-symbols-outlined search-icone">search</span>
        <!-- Bouton de filtre -->
        <div @click="toggleFiltre" id="buttonFiltre" :class="{ filterActive: filterActived }">
          <span class="material-symbols-outlined">tune</span>
        </div>
      </div>
  
      <!-- Conteneur de filtre -->
      <div id="filtre" :class="{ visible: filtreVisible }">
        <Filtre @updateFilters="updateFilters" :closeFilter="toggleFiltre" />
      </div>
  
      <!-- Thèmes -->
      <div id="theme-parent">
        <div id="theme">
          <div
            :class="{ theme: true, current: themeCurrent === null }"
            @click="filterByTheme(null)"
          >
            <div>
              <span class="material-symbols-outlined click">footprint</span>
            </div>
            <p>Tout</p>
          </div>
          <div
            v-for="theme in themes"
            :key="theme.id"
            :class="{ theme: true, click: true, current: themeCurrent === theme.id }"
            @click="filterByTheme(theme.id)"
          >
            <div v-html="theme.icone"></div>
            <p>{{ theme.name }}</p>
          </div>
        </div>
      </div>
  
      <!-- Sentiers de randonnée -->
      <div id="sentiers-parent">
        <div id="sentiers-accueil">
          <a v-for="sentier in filteredSentiers" :key="sentier.id" :href="`#sentier-${sentier.id}`">
            <div :style="{ 'background-image': `url(${sentier.photo})` }">
              <buttonFavoris id="favoris" :sentierId="sentier.id" />
            </div>
            <div class="affichage">
              <div>
                <p class="sentier-nom">{{ sentier.nom }}</p>
                <div>
                  <span class="material-symbols-outlined">location_on</span>
                  <p>{{ sentier.localisation }}</p>
                </div>
              </div>
              <div v-html="sentier.theme.icone"></div>
            </div>
          </a>
          <p v-if="filteredSentiers.length < 1">
            Aucun sentier actuellement disponible pour ce thème
          </p>
        </div>
      </div>
  
      <!-- Sentiers préférés -->
      <h3>Sentiers incontournables</h3>
      <div id="lesPlusVues-parent">
        <div id="lesPlusVues">
          <a v-for="sentier in sentiersPref" :key="sentier.id" :href="`#sentier-${sentier.id}`">
            <img :src="sentier.photo" :alt="sentier.nom" />
            <div>
              <p>{{ sentier.nom }}</p>
            </div>
          </a>
        </div>
      </div>
    </div>
    <footer>
      <Footer />
    </footer>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, watchEffect } from "vue";
  import axios from "axios";
  import Filtre from "@/components/elements/filter.vue";
  import buttonFavoris from "@/components/elements/buttonFavorite.vue";
  import Footer from "@/components/elements/footer.vue";
  import Header from "@/components/elements/header.vue";
  
  // État réactif
  const themes = ref([]);
  const sentiers = ref([]);
  const sentiersPref = ref([]);
  const filtreVisible = ref(false);
  const searchQuery = ref("");
  const isLoading = ref(true);
  const themeCurrent = ref(null);
  const filterActived = ref(false);
  
  const selectedFilters = ref({
    selectedCriteres: [],
    selectedMotCles: [],
    difficulte: [],
    theme: null,
  });
  
  // Mise à jour de l'état du filtre actif
  watchEffect(() => {
    filterActived.value =
      selectedFilters.value.selectedCriteres.length > 0 ||
      selectedFilters.value.selectedMotCles.length > 0 ||
      selectedFilters.value.difficulte.length > 0;
  });
  
  // Fonction pour récupérer les thèmes depuis l'API
  const fetchTheme = async () => {
    try {
      const response = await axios.get("/data-theme");
      themes.value = response.data;
    } catch (error) {
      console.error("Error fetching themes:", error);
    }
  };
  
  // Fonction pour récupérer les sentiers depuis l'API
  const fetchSentiers = async () => {
    try {
      const response = await axios.get("/data-sentiers");
      sentiers.value = response.data.filter((item) => item.archive === 0);
    } catch (error) {
      console.error("Error fetching sentiers:", error);
    }
  };
  
  // Fonction pour récupérer les sentiers préférés depuis l'API
  const fetchSentiersPref = async () => {
    try {
      const response = await axios.get("/data-sentiers/prefere");
      sentiersPref.value = response.data.filter((item) => item.archive === 0);
    } catch (error) {
      console.error("Error fetching preferred sentiers:", error);
    }
  };
  
  // Fonction pour mettre à jour les filtres
  const updateFilters = (filters) => {
    selectedFilters.value = filters;
  };
  
  // Fonction pour basculer la visibilité du filtre
  const toggleFiltre = () => {
    filtreVisible.value = !filtreVisible.value;
  };
  
  // Fonction pour filtrer les sentiers par thème
  const filterByTheme = (themeId) => {
    selectedFilters.value.theme = themeId;
    themeCurrent.value = themeId;
  };
  
  // Calcul des sentiers filtrés en additionant les filtres
  const filteredSentiers = computed(() => {
    return sentiers.value.filter((sentier) => {
      let matches = false;
  
      // Filtrage par barre de recherche
      if (searchQuery.value.trim() !== "") {
        matches =
          matches ||
          sentier.nom.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          sentier.localisation.toLowerCase().includes(searchQuery.value.toLowerCase());
      }
  
      // Filtrage par critères sélectionnés
      if (selectedFilters.value.selectedCriteres.length > 0) {
        const critereIds = sentier.criteres.map((critere) => critere.id);
        const matchesSelectedCriteres = selectedFilters.value.selectedCriteres.every((critere) =>
          critereIds.includes(critere)
        );
        matches = matches || matchesSelectedCriteres;
      }
  
      // Filtrage par mots-clés sélectionnés
      if (selectedFilters.value.selectedMotCles.length > 0) {
        const motCleIds = sentier.motcles.map((motcle) => motcle.id);
        const matchesSelectedMotCles = selectedFilters.value.selectedMotCles.every((motcle) =>
          motCleIds.includes(motcle)
        );
        matches = matches || matchesSelectedMotCles;
      }
  
      // Filtrage par difficulté
      if (selectedFilters.value.difficulte.length > 0) {
        const matchesDifficulty = selectedFilters.value.difficulte.includes(
          `${sentier.difficulte.graduation}`
        );
        matches = matches || matchesDifficulty;
      }
  
      // Filtrage par thème
      if (selectedFilters.value.theme !== null) {
        const matchesTheme = sentier.theme_id === selectedFilters.value.theme;
        matches = matches || matchesTheme;
      }
  
      // Affichage de tous les sentiers si aucun filtre n'est appliqué
      if (
        searchQuery.value.trim() === "" &&
        selectedFilters.value.selectedCriteres.length === 0 &&
        selectedFilters.value.selectedMotCles.length === 0 &&
        selectedFilters.value.difficulte.length === 0 &&
        themeCurrent.value === null
      ) {
        matches = true;
      }
  
      return matches;
    });
  });
  
  // Appel des fonctions de récupération de données au montage du composant
  onMounted(async () => {
    await fetchTheme();
    await fetchSentiers();
    await fetchSentiersPref();
    isLoading.value = false;
  });
  </script>

<style>
.recherche[type="text"] {
    padding: 10px 20px 10px 40px;
    z-index: 5;
    position: relative;
    margin-bottom: 0;
}
.recherche[type="text"] + .search-icone {
    display: block;
    position: absolute;
    width: fit-content;
    height: fit-content;
    z-index: 5;
    font-size: 1.5rem;
    left: 10px;
    color: var(--color-text-secondary);
}

/* Filtre */

#filtre {
    display: none;
    position: absolute;
    background: white;
    z-index: var(--z-index-medium);
    width: 100vw;
    min-height: 100%;
    top: 0%;
    padding: calc(var(--padding-large) * 2) var(--padding-small);
    left: 0%;
    z-index: 11;
}
#accueil .header {
    margin-bottom: 0;
}

/* Thèmes */
.theme {
    display: flex;
    align-items: center;
    padding-bottom: var(--margin-small);
    width: 85px;
}

#sentiers-parent,
#lesPlusVues-parent,
#theme-parent {
    width: 100vw;
    overflow-y: scroll; /* Add the ability to scroll */
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}
#sentiers-parent,
#lesPlusVues-parent {
    width: 100vw;
    overflow-y: scroll; /* Add the ability to scroll */
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
    position: relative;
    left: -10%;
    padding-left: 10%;
}

#theme-parent::-webkit-scrollbar,
#lesPlusVues-parent::-webkit-scrollbar,
#sentiers-parent::-webkit-scrollbar {
    display: none;
}

#theme-parent {
    position: relative;
    left: -10%;
}
.current {
    border-bottom: 1px solid var(--primary);
}
.current span,
.current p {
    color: var(--primary) !important;
}

#theme {
    margin: var(--margin-medium) 0;
    display: flex;
    align-items: center;
    width: var(--width-fit);
    background: white;
    box-shadow: var(--box-shadow-light-bottom);
}

#theme span {
    font-size: var(--font-size-small);
    color: var(--color-text-secondary);
}

#theme p {
    font-size: 0.8rem;
    font-weight: 500;
    text-align: center;
    color: var(--color-text-secondary);
}

#theme .theme {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-content: center;
}

/* Sentier */

#sentiers-accueil {
    width: var(--width-fit);
    display: flex;
    gap: var(--margin-large);
    align-items: center;
    padding-right: 15%;
}

#sentiers-accueil > a {
    display: block;
    width: 260px;
    height: 350px;
    border-radius: var(--border-radius-medium);
    position: relative;
    overflow: hidden;
    color: white;
    text-decoration: none;
}

#sentiers-accueil > a > div:nth-of-type(1) {
    width: var(--width-full);
    height: var(--height-full);
    background-size: cover;
    background-position: center center;
}

#favoris {
    position: absolute;
    right: 8%;
    color: white;
    top: 5%;
    z-index: 4;
}

.affichage {
    position: absolute;
    bottom: 10%;
    left: 50%;
    width: 90%;
    min-height: 18%;
    transform: translateX(-50%);
    background: white;
    padding: var(--padding-medium) var(--padding-large);
    border-radius: var(--border-radius-small);
}

.sentier-nom {
    font-weight: 700;
}

.affichage > div:nth-of-type(1) {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    height: 100%;
    gap: 7px;
}
.affichage > div:nth-of-type(1) > div {
    display: flex;
    align-items: center;
    color: var(--color-text-secondary);
}

.affichage > div:nth-of-type(1) > div span,
.affichage > div:nth-of-type(1) > div p {
    color: var(--color-text-secondary);
}
.affichage > div:nth-of-type(1) > div span {
    margin-right: 5px;
    font-size: 1.2rem;
}

.affichage > div:nth-of-type(2) {
    position: absolute;
    top: 10px;
    right: 15px;
}

.affichage > div:nth-of-type(2) span {
    color: var(--color-text-secondary);
}

/* Sentier incontournable */

#accueil h3 {
    margin: var(--margin-large) 0 var(--margin-medium) 0;
    font-size: var(--font-size-medium);
}

#lesPlusVues-parent {
    height: var(--height-15vh);
    display: flex;
    align-items: center;
    margin-bottom: 15%;
}

#lesPlusVues {
    display: flex;
    gap: var(--margin-large);
    padding-right: 15%;
}

#lesPlusVues p {
    font-size: 1.1rem;
    font-weight: 600;
}

#lesPlusVues a {
    display: flex;
    border-radius: var(--border-radius-medium);
    width: 250px;
    height: 100px;
    align-items: center;
    text-decoration: none;
    box-shadow: var(--box-shadow-light);
    background: white;
}

#lesPlusVues a img {
    width: 40%;
    height: var(--height-full);
    border-radius: var(--border-radius-medium);
    object-fit: cover;
}

#lesPlusVues a div {
    margin-left: var(--margin-medium);
}
.filterActive span::after{
    content: '';
    position: relative;
}
.filterActive span::after{
    content: '';
    display: block;
    width: 15px;
    height: 15px;
    background: var(--secondary);
    border-radius: var(--border-radius-full);
    position: absolute;
    top: -2%;
    right: 0%;
}
@media (min-width: 900px) {
    #theme-parent {
        left: -2%;
        width: 100vw;
    }
    #theme {
        width: 100vw;
        padding: 0 18%;
        justify-content: space-between;
    }
    #theme span {
        font-size: var(--font-size-large);
    }
    #lesPlusVues-parent,
    #sentiers-parent {
        width: 100%;
        margin-bottom: 5% !important;
    }
    #sentiers-accueil {
        padding: 0 9% 0 18%;
        width: 100%;
        display: grid;
        gap: 30px;
        grid-template-columns: 1fr 1fr 1fr 1fr;
    }
    #lesPlusVues {
        padding: 0 18%;
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
    }
    #lesPlusVues a {
        width: 100%;
    }

    h3 {
        padding: 0 16%;
    }
    .header input {
        width: 90% !important;
    }
    #sentiers-accueil > a {
        width: 100%;
    }
    .sentier-nom {
        font-size: 0.9rem;
        width: 85%;
    }
}
</style>
<style scoped>
@media (min-width: 900px) {
    #filtre {
        position: absolute;
        height: 180%;
    }
    .header {
        position: absolute !important;
        left: 40%;
        width: 40%;
        top: 4%;
        padding: 0;
    }
}
</style>
