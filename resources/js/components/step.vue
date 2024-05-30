<template>

    <!-- Carrousel -->
    <div>
        <!-- Les images -->
        <div>
            <!-- Image de l'étape -->
            <img :src="etape.photo" :alt="etape.description">
            <!-- Images des points d'interet -->
            <img :src="pois.photo" :alt="pois.nom" v-for="pois in etape.points_interet" :key="pois.id">
        </div>
        <!-- Les petits points de changeemnt -->
        <div>
            <span></span>
        </div>
    </div>

    <!-- Description -->
    <div>
        <h3>Description</h3>
        <p>{{ etape.description }}</p>
    </div>
    <div>
        <h3>Points d'intérêts</h3>
        <div v-for="pois in etape.points_interet" :key="pois.id">
            <img :src="pois.photo" :alt="pois.nom">
            <p>{{ pois.nom }}</p>
        </div>
    </div>
    <div>
        <h3>Retour à la carte</h3>
        <a ><img src="" alt=""></a>
    </div>
</template>
<script>
import axios from "axios";

export default {
    props: {
        etapeId: ""
    },
    data() {
        return {
            etape: [],
        };
    },
    created() {
        this.fetchStep();
    },
    methods: {
        async fetchStep() {
            if (this.etapeId != null) {
                try {
                    const response = await axios.get(
                        `/data-step-${this.etapeId.idSection}`
                    );
                    this.etape = response.data;
                    console.log(response.data);
                } catch (error) {
                    console.error("Error fetching sentiers:", error);
                }
            }
        },
    }
}
</script>
<style lang="">
    
</style>