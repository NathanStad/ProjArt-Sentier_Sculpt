<template>
    <div v-if="isLoading">
        <p>Loading...</p>
    </div>
    <div v-else id="favorite">
        <div class="header">
            <h1>Favoris</h1>
            <input type="text" v-model="searchQuery" placeholder="Recherche parmi les favoris" class="recherche" />
            <span class="material-symbols-outlined search-icone"> search </span>
        </div>
        <a :href="`#sentier-${sentier.id}`" v-for="sentier in filteredSentiers" :key="sentier.id" class="sentier">
            <div @click="reload">
                <buttonFavoris :sentierId="sentier.id"></buttonFavoris>
            </div>
            <div>
                <img :src="sentier.photo" :alt="sentier.nom" />
                <div v-html="sentier.theme.icone"></div>
            </div>
            <div>
                <div>
                    <p>{{ sentier.nom }}</p>
                    <p>
                        <span class="material-symbols-outlined">
                            location_on
                        </span>
                        {{ sentier.localisation }}
                    </p>
                </div>
                <a :href="`#steps-${sentier.id}`" class="button">Démarrer</a>
            </div>
        </a>
        <p v-if="filteredSentiers.length < 1 && sentiers.length >= 1" id="non">
            Pas de sentier trouvé
        </p>
        <p v-if="sentiers.length < 1" id="non">
            Vous n'avez actuellement pas de favoris
        </p>
    </div>
    <footer>
        <Footer></Footer>
    </footer>
</template>

<script setup>


import { ref, onMounted, computed, watch } from "vue";
import buttonFavoris from "@/components/elements/buttonFavorite.vue";
import axios from "axios";
import Footer from "@/components/elements/footer.vue";

const isLoading = ref(true);
const sentiers = ref([]);
const favoris = ref(JSON.parse(sessionStorage.getItem("favoris")) || []);

const searchQuery = ref("");

const fetchSentier = async (sentierId) => {
    if (sentierId !== "") {
        try {
            const response = await axios.get(`/data-sentier-${sentierId}`);
            sentiers.value.push(response.data);
        } catch (error) {
            console.error("Error fetching sentiers:", error);
        }
    }
};

favoris.value.forEach((sentierId) => {
    fetchSentier(sentierId);
});

const reload = () => {
    console.log("je reload");
    location.reload();
}

const handleFavorisUpdated = () => {
}

window.addEventListener("favorisUpdated", handleFavorisUpdated);

const filteredSentiers = computed(() => {
    return sentiers.value.filter((sentier) => {
        const query = searchQuery.value.toLowerCase();
        return (
            sentier.nom.toLowerCase().includes(query) ||
            sentier.localisation.toLowerCase().includes(query)
        );
    });
});

onMounted(() => {
    isLoading.value = false;
});
</script>

<style scoped>
h1 {
    text-align: center;
    padding-bottom: 10%;
}

.sentier {
    display: flex;
    position: relative;
    padding: 15px;
    background: white;
    box-shadow: var(--box-shadow-light);
    border-radius: var(--border-radius-medium);
    width: 100%;
    height: 170px;
    text-decoration: none;
    margin-bottom: 10%;
}

input {
    padding: var(--padding-large) calc(var(--padding-medium) * 2.33) !important;
    position: relative;
    left: 50%;
    transform: translate(-50%);
    width: 100% !important;
    margin-bottom: 12% !important;
}

.sentier>div:first-of-type {
    position: absolute;
    right: 5%;
    top: 8%;
}

.sentier>div:first-of-type span {
    color: var(--primary);
}

.sentier>div:nth-of-type(2) {
    overflow: hidden;
    border-radius: var(--border-radius-small);
    position: relative;
    width: 40%;
    margin-right: 15px;
}

.sentier>div:nth-of-type(2) img {
    width: 100%;
    height: 100%;
}

.sentier>div:nth-of-type(2) div {
    position: absolute;
    z-index: 3;
    bottom: 5%;
    right: 5%;
}

.sentier>div:nth-of-type(2) * {
    color: white !important;
}

.sentier>div:nth-of-type(3)>div {
    display: flex;
    flex-direction: column;
    justify-content: start;
    gap: 5px;
    height: 50%;
    width: 125px;
}

.sentier>div:nth-of-type(3)>div>p:first-of-type {
    font-size: 1rem;
    font-weight: 600;
}

.sentier>div:nth-of-type(3)>div>p:last-of-type {
    display: flex;
    align-items: center;
    color: var(--color-text-secondary);
}

.sentier>div:nth-of-type(3)>div>p:last-of-type span {
    font-size: 1.5rem;
    color: var(--color-text-secondary);
}

.button {
    padding: 15px 15px;
    background: var(--primary);
    border-radius: var(--border-radius-small);
    text-decoration: none;
    color: white;
    right: -15%;
    bottom: -4%;
    text-align: center;
    display: block;
    position: absolute;
    left: auto;
    width: 40%;
}

.recherche {
    padding: 10px 20px 10px 40px !important;
    z-index: 5;
    position: relative;
    margin-bottom: 0 !important;
}

.recherche[type="text"]+.search-icone {
    display: block;
    position: absolute;
    width: fit-content;
    height: fit-content;
    z-index: 5;
    font-size: 1.5rem;
    top: 62%;
    left: 40px;
    color: var(--color-text-secondary);
}

.header {
    flex-direction: column;
    position: sticky;
    top: 0px;
    background: white;
    box-shadow: var(--box-shadow-light-bottom);
    width: 100vw;
    padding: 10%;
    transform: translate(-8%);
}

@media only screen and (min-width: 900px) {

    .header {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        transform: translate(-2%);
        padding: 5% 18%;
        grid-column: 1/3;
    }

    .header input {
        left: 0;
        transform: none;
    }

    h1 {
        padding-bottom: 0
    }

    .recherche {
        width: 50% !important;
    }

    .recherche[type="text"]+.search-icone {
        left: 50.5%;
        top: 92px
    }

    #favorite {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .sentier {
        height: 250px;
        width: 60%;
    }

    .sentier:nth-child(even) {
        margin-left: 32%;
    }

    .sentier:nth-child(odd) {
        margin-right: 32%;
    }

    #non {
        padding: 0 16.5%;
        width: 100%;
        grid-column: 1/3;
    }
}
</style>
