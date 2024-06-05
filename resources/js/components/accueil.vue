<template>
    <div v-if="isLoading">
        <p>Loading...</p>
    </div>
    <div id="accueil" v-else>
        <!-- Bar de recherche -->
        <input type="text" v-model="searchQuery" placeholder="Rechercher" />

        <!-- Bouton Filtre -->
        <div @click="toggleFiltre" id="buttonFiltre">
            <span class="material-symbols-outlined"> tune </span>
        </div>

        <!-- Conteneur du filtre -->
        <div v-if="filtreVisible" id="filtre">
            <Filtre @updateFilters="updateFilters"></Filtre>
        </div>

        <!-- Thèmes -->
        <div id="theme-parent">
            <div id="theme">
                <div class="theme" @click="filterByTheme(null)">
                    <div>
                        <span class="material-symbols-outlined">
                            footprint
                        </span>
                    </div>
                    <p>Tous</p>
                </div>
                <div
                    v-for="theme in themes"
                    :key="theme.id"
                    class="theme"
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
                        <buttonFavoris :sentierId="sentier.id"></buttonFavoris>
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
            </div>
        </div>

        <!-- Destinations préférées -->
        <h3>Destinations les plus vues</h3>
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
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import Filtre from "@/components/elements/filter.vue";
import buttonFavoris from "@/components/elements/buttonFavorite.vue";

// Define reactive state
const themes = ref([]);
const sentiers = ref([]);
const sentiersPref = ref([]);
const filtreVisible = ref(false);
const searchQuery = ref("");
const isLoading = ref(true);

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
            if (
                !selectedFilters.value.selectedCriteres.includes(
                    sentier.theme_id
                )
            ) {
                return false;
            }
        }
        // Filter by selected mot cle
        if (selectedFilters.value.selectedMotCles.length > 0) {
            if (
                !selectedFilters.value.selectedMotCles.includes(
                    sentier.difficulte_id
                )
            ) {
                return false;
            }
        }
        // Filter by difficulty
        if (selectedFilters.value.difficulte.length > 0) {
            if (
                !selectedFilters.value.difficulte.includes(
                    sentier.difficulte_id
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
section > div {
    position: relative;
}

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
button {
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
textarea {
    margin-bottom: var(--margin-medium);
    height: 300px;
    padding: var(--padding-medium) calc(var(--padding-medium) * 2.33);
    font-size: var(--font-size-small);
    width: var(--width-85);
    border-radius: var(--border-radius-large);
    border: 0;
    box-shadow: var(--box-shadow-light);
}

/* Bouton Filtre */

#buttonFiltre {
    position: absolute;
    top: 0;
    right: 0%;
    padding: var(--padding-small);
    box-shadow: var(--box-shadow-light);
    border-radius: var(--border-radius-full);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: var(--z-index-high);
}

/* Filtre */

#filtre {
    position: absolute;
    background: white;
    z-index: var(--z-index-medium);
    width: 100vw;
    height: var(--height-100vh);
    top: -10%;
    padding: calc(var(--padding-large) * 2) var(--padding-small);
    left: -2%;
}

/* Thèmes */

.theme {
    display: flex;
    align-items: center;
    margin-bottom: var(--margin-small);
    width: 110px;
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

#theme-parent{
    position: relative;
    left: -5%;
}

.theme div {
    margin-right: var(--margin-small);
}

#theme {
    margin: var(--margin-medium) 0;
    display: flex;
    gap: var(--margin-large);
    align-items: center;
    width: var(--width-fit);
    background: white;
    box-shadow: var(--box-shadow-light-bottom);
}

#theme span {
    font-size: var(--font-size-large);
}

#theme p {
    font-size: var(--font-size-small);
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
}

#sentiers-accueil > a {
    display: block;
    width: 300px;
    height: 400px;
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

#sentiers-accueil > a > div:nth-of-type(1) span {
    position: absolute;
    right: 15%;
    color: white;
    top: 5%;
}

.affichage {
    position: absolute;
    bottom: 10%;
    left: 50%;
    width: 90%;
    transform: translateX(-50%);
    background: white;
    padding: var(--padding-small) var(--padding-medium);
    border-radius: var(--border-radius-small);
}

.sentier-nom {
    font-weight: 700;
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

.affichage > div:nth-of-type(2) {
    position: absolute;
    top: 10px;
    right: 15px;
}

.affichage > div:nth-of-type(2) span {
    color: var(--color-text-secondary);
}

/* Destination les plus vues */

h3 {
    margin: var(--margin-medium) 0;
    font-size: var(--font-size-medium);
}

#lesPlusVues-parent {
    height: var(--height-15vh);
    display: flex;
    align-items: center;
}

#lesPlusVues {
    display: flex;
    gap: var(--margin-large);
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
    margin-left: var(--margin-small);
}

</style>
