<template>
    <div v-if="isLoading">
        <p>Loading...</p>
    </div>
    <div v-else>

        
            <!-- Bar de recherche -->
        
            <input
                type="text"
                v-model="searchQuery"
                placeholder="Rechercher un thème"
            />
        
            <!-- Bouton Filtre -->
            <div @click="toggleFiltre">Afficher le filtre</div>
        
            <!-- Conteneur du filtre -->
            <div v-if="filtreVisible">
                <Filtre @updateFilters="updateFilters"></Filtre>
            </div>
        
            <!-- Thèmes -->
            <div id="theme-parent">
                <div id="theme">
                    <div class="theme" @click="filterByTheme(null)">
                        <div>
                            <span class="material-symbols-outlined"> footprint </span>
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
            <div>
                <a
                    v-for="sentier in filteredSentiers"
                    :key="sentier.id"
                    :href="`#sentier-${sentier.id}`"
                >
                    <div :style="{ 'background-image': `url(${sentier.photo})` }">
                      <buttonFavoris :is="sentier.id"></buttonFavoris>
                    </div>
                    <div>
                      <div>
                        <p>{{ sentier.nom }}</p>
                        <p>
                            <span class="material-symbols-outlined"> location_on </span>
                            {{ sentier.localisation }}
                        </p>
                      </div>
                      <div v-html="sentier.theme.icone"></div>
                    </div>
                </a>
            </div>
        
            <!-- Destinations préférées -->
            <h3>Destinations les plus vues</h3>
            <div>
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
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import Filtre from "@/components/elements/filter.vue";
import buttonFavoris from '@/components/elements/buttonFavorite.vue' 

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
    difficulte: "",
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
                !sentier.description
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
        if (selectedFilters.value.difficulte !== "") {
            if (sentier.difficulte_id !== selectedFilters.value.difficulte) {
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
.theme {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}
.theme div {
    margin-right: 10px;
}
input[type="text"] {
    margin-bottom: 20px;
    padding: 10px;
    font-size: 16px;
    width: 100%;
}
#theme-parent {
    width: 100vw;
    overflow-y: scroll; /* Add the ability to scroll */
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}
#theme-parent::-webkit-scrollbar {
    display: none;
}
#theme {
    display: flex;
    justify-content: space-around;
    align-items: center;
    width: 200vw;
}
#theme * {
    font-size: 1.5rem;
}
</style>
