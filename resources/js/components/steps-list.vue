<template>
            <!-- Bouton retour -->
            <a :href="`#sentier-${sentierId.idSection}`"></a>
        <!-- Maps -->
        <div id="app"></div>
        <div>            
            <!-- Flèche avant -->
            <span></span>
            <!-- Flèche après -->
            <span></span>
        </div>
        <div>
            <!-- Les étapes -->
            <a v-for="etape in sentier.etapes" :key="etape.id" :href="`#step-${etape.id}`">
                <img :src="etape.photo" :alt="etape.nom">
                <div>
                    <p>{{ etape.nom }}</p>
                    <div>
                        <p>Durée</p>
                        <p>{{ etape.duree }}</p>
                    </div>
                    <div>
                        <p>Distance</p>
                        <p>{{ etape.distance }}km</p>
                    </div>
                </div>
            </a>
        </div>
</template>

<script>
import axios from "axios";

export default {
    props: {
        sentierId: "",
    },
    data() {
        return {
            sentier: [],
            etapes: [],
        };
    },
    created() {
        this.fetchSentier();
    },
    methods: {
        async fetchSentier() {
            if (this.sentierId != null) {
                try {
                    const response = await axios.get(
                        `/data-sentier-${this.sentierId.idSection}`
                    );
                    this.sentier = response.data;
                    this.etapes = response.data.etapes;
                } catch (error) {
                    console.error("Error fetching sentiers:", error);
                }
            }
        },
    },
};
</script>
<style lang="">
    
</style>