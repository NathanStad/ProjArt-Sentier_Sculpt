<template>
    <div v-if="isLoading">
        <p>Loading...</p>
    </div>
    <div v-else>
        <h1>Favoris</h1>
        <input type="text" value="Recherche" />
        <a :href="`#sentier-${sentier.id}`" v-for="sentier in sentiers" :key="sentier" class="sentier">
            <!-- Boutton Favoris -->
            <div>
                <img :src="sentier.photo" :alt="sentier.nom" />
                <div v-html="sentier.theme.icone"></div>
            </div>
            <div>
                <div>
                    <p>{{ sentier.nom }}</p>
                    <p><span class="material-symbols-outlined">
                            location_on
                        </span>
                        {{ sentier.localisation }}</p>
                </div>
                <buttonFavoris :is="sentier.id"></buttonFavoris>
                <a :href="`#steps-${sentier.id}`"></a>
            </div>
        </a>
        <p v-if="sentiers < 1">Vous n'avez pas encore de favoris</p>
    </div>
</template>
<script setup>
import {ref, onMounted} from 'vue';
import buttonFavoris from '@/components/elements/buttonFavorite.vue' 
import axios from "axios";

const sentiers = ref([]);
const favoris = ref(JSON.parse(localStorage.getItem("favoris")));
console.log(favoris.value);

favoris.value.forEach((sentierId) => {
    const fetchSentier = async () => {
        if (sentierId !== "") {
            try {
                const response = await axios.get(`/data-sentier-${sentierId}`);
                sentiers.value.push(response.data);
            } catch (error) {
                console.error("Error fetching sentiers:", error);
            }
        }
    };
    fetchSentier()
});

onMounted(async () => {
    
    isLoading.value = false;
});
</script>
<style lang=""></style>
