<template>
    <div v-if="isLoading">
        <p>Loading...</p>
    </div>
    <div id="accueil" v-else>
        <header><Header></Header></header>
        <!-- Bar de recherche -->
        <div class="header">
            <input
                type="text"
                v-model="searchQuery"
                placeholder="Rechercher par lieu et nom"
                class="recherche"
            />
            <span class="material-symbols-outlined search-icone"> search </span>
            <!-- Bouton Filtre -->
            <div @click="toggleFiltre()" id="buttonFiltre">
                <span class="material-symbols-outlined"> tune </span>
            </div>
        </div>

        <!-- Conteneur du filtre -->
        <div id="filtre" :class="{ visible: filtreVisible === true }">
            <Filtre @updateFilters="updateFilters" :closeFilter="toggleFiltre"></Filtre>
        </div>

        <!-- Thèmes -->
        <div id="theme-parent">
            <div id="theme">
                <div :class="{theme:true, 'current': themeCurrent == null}" @click="filterByTheme(null)">
                    <div>
                        <span class="material-symbols-outlined">
                            footprint
                        </span>
                    </div>
                    <p>Tout</p>
                </div>
                <div
                    v-for="theme in themes"
                    :key="theme.id"
                    :class="{theme:true, 'current': themeCurrent == theme.id}"
                    @click="filterByTheme(theme.id)"
                >
                    <div v-html="theme.icone"></div>
                    <p>{{ theme.name }}</p>
                </div>
            </div>
        </div>

        <!-- Sentiers  -->
        <div id="sentiers-parent">
            <div id="sentiers-accueil">
                <a
                    v-for="sentier in filteredSentiers"
                    :key="sentier.id"
                    :href="`#sentier-${sentier.id}`"
                >
                    <div
                        :style="{ 'background-image': `url(${sentier.photo})` }"
                    >
                        <buttonFavoris
                            id="favoris"
                            :sentierId="sentier.id"
                        ></buttonFavoris>
                    </div>
                    <div class="affichage">
                        <div>
                            <p class="sentier-nom">{{ sentier.nom }}</p>
                            <div>
                                <span class="material-symbols-outlined">
                                    location_on
                                </span>
                                <p>{{ sentier.localisation }}</p>
                            </div>
                        </div>
                        <div v-html="sentier.theme.icone"></div>
                    </div>
                </a>
                <p v-if="filteredSentiers < 1">Sentiers recherchés introuvables</p>
            </div>
        </div>

        <!-- Destinations préférées -->
        <h3>Sentiers incontournables</h3>
        <div id="lesPlusVues-parent">
            <div id="lesPlusVues">
                <a
                    v-for="sentier in sentiersPref"
                    :key="sentier.id"
                    :href="`#sentier-${sentier.id}`"
                >
                    <img :src="sentier.photo" :alt="sentier.nom" />
                    <div>
                        <p>{{ sentier.nom }}</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <footer><Footer></Footer></footer>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import Filtre from "@/components/elements/filter.vue";
import buttonFavoris from "@/components/elements/buttonFavorite.vue";
import Footer from "@/components/elements/footer.vue";
import Header from "@/components/elements/header.vue";

// Define reactive state
const themes = ref([]);
const sentiers = ref([]);
const sentiersPref = ref([]);
const filtreVisible = ref(false);
const searchQuery = ref("");
const isLoading = ref(true);
const themeCurrent = ref(null)

const selectedFilters = ref({
    selectedCriteres: [],
    selectedMotCles: [],
    difficulte: [],
    theme: null,
});

// Function to fetch theme data
const fetchTheme = async () => {
    try {
        const response = await axios.get("/data-theme");
        themes.value = response.data;
    } catch (error) {
        console.error("Error fetching themes:", error);
    }
};

// Function to fetch sentier data, excluding archived ones
const fetchSentiers = async () => {
    try {
        const response = await axios.get("/data-sentiers");
        sentiers.value = response.data.filter((item) => item.archive === 0);
    } catch (error) {
        console.error("Error fetching sentiers:", error);
    }
};

// Function to fetch preferred sentier data
const fetchSentiersPref = async () => {
    try {
        const response = await axios.get("/data-sentiers/prefere");
        sentiersPref.value = response.data.filter((item) => item.archive === 0);
        console.log(sentiersPref.value);
    } catch (error) {
        console.error("Error fetching preferred sentiers:", error);
    }
};

const updateFilters = (filters) => {
    selectedFilters.value = filters;
};

const toggleFiltre = () => {
    filtreVisible.value = !filtreVisible.value;
};

const filterByTheme = (themeId) => {
    selectedFilters.value.theme = themeId;
    themeCurrent.value = themeId;
};

const filteredSentiers = computed(() => {
    let filtered = sentiers.value.filter((sentier) => {
        // Filter by search query
        if (searchQuery.value.trim() !== "") {
            if (
                !sentier.nom
                    .toLowerCase()
                    .includes(searchQuery.value.toLowerCase()) &&
                !sentier.localisation
                    .toLowerCase()
                    .includes(searchQuery.value.toLowerCase())
            ) {
                return false;
            }
        }
        // Filter by selected critere
        if (selectedFilters.value.selectedCriteres.length > 0) {
            const critereIds = sentier.criteres.map(critere => critere.id);
            if (!selectedFilters.value.selectedCriteres.every(critere => critereIds.includes(critere))) {
                return false;
            }
        }
        // Filter by selected mot cle
        if (selectedFilters.value.selectedMotCles.length > 0) {
            const motCleIds = sentier.motcles.map(motcle => motcle.id);
            if (!selectedFilters.value.selectedMotCles.every(motcle => motCleIds.includes(motcle))) {
                return false;
            }
        }
        // Filter by difficulty
        if (selectedFilters.value.difficulte.length > 0) {
            if (
                !selectedFilters.value.difficulte.includes(
                    sentier.difficulte.id
                )
            ) {
                return false;
            }
        }
        if (selectedFilters.value.theme !== null) {
            if (sentier.theme_id !== selectedFilters.value.theme) {
                return false;
            }
        }
        return true;
    });
    return filtered;
});


// Fetch data when the component is mounted
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
    font-size: 1.5rem ;
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
    min-height: var(--height-100vh);
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

#theme-parent::-webkit-scrollbar,
#lesPlusVues-parent::-webkit-scrollbar,
#sentiers-parent::-webkit-scrollbar {
    display: none;
}

#theme-parent {
    position: relative;
    left: -10%;
}
.current{
    border-bottom: 1px solid var(--primary) ;
}
.current span,
.current p
{
    color: var(--primary);
}
.theme div {
    margin-right: var(--margin-small);
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
}

#theme p {
    font-size: 0.8rem;
    font-weight: 500;
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
    height: 18%;
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
    margin-bottom: 10%;
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

@media (min-width: 900px) {
  #theme-parent{
    left: -2%;
    width: 100vw;
  }
#theme{
  width: 100vw;
  padding: 0 10%;
  justify-content: space-between;
}
#theme span{
    font-size: var(--font-size-large);
}
#lesPlusVues-parent,
#sentiers-parent{
    width: 100%;
    margin-bottom: 5% !important;
}
#lesPlusVues,
#sentiers-accueil{
    padding: 0 10%;
width: 100%;
display: grid;
grid-template-columns: 1fr 1fr 1fr 1fr ;
}
h3{
    padding: 0 10%;
}
.header input{
    width: 90% !important;
  }
}
</style>
<style scoped>
@media (min-width: 900px) {

  .header {
    position: absolute !important;
    left: 30%;
    width: 50%;
    top: 4%;
    padding: 0;
  }
}
</style>