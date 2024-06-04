<template>
    <div class="header">
        <a :href="`#sentier-${props.Id}`">
            <span class="material-symbols-outlined"> arrow_back_ios </span>
        </a>
        <h1>{{ etape.nom }}</h1>
    </div>
    <!-- Carrousel -->
    <swiper :slides-per-view="1" pagination class="carrousel">
        <swiper-slide>
            <!-- Image de l'étape -->
            <img :src="etape.photo" :alt="etape.description" />
        </swiper-slide>
        <swiper-slide v-for="pois in etape.points_interet" :key="pois.id">
            <!-- Images des points d'interet -->
            <img :src="pois.photo" :alt="pois.nom" />
        </swiper-slide>
    </swiper>

    <!-- Description -->
    <div>
        <h3>Description</h3>
        <p>{{ etape.description }}</p>
    </div>

    <div id="poi">
        <h3>Points d'intérêts</h3>
        <div>
            <div
                v-for="pois in etape.points_interet"
                :key="pois.id"
                class="poi-element"
            >
                <img :src="pois.photo" :alt="pois.nom" />
                <p>{{ pois.nom }}</p>
            </div>
        </div>
    </div>

    <div>
        <h3>Retour à la carte</h3>
        <a :href="`#steps-${etape.sentier_id}`">
            <img src="" alt="Retour à la carte" />
        </a>
    </div>
</template>

<script setup>
import axios from "axios";
import { defineProps, onMounted, ref } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css";

// Définition des props
const props = defineProps({
    Id: {
        type: Object,
        required: true,
    },
});

// Data
const etape = ref({ points_interet: [] });

// Methods
const fetchStep = async () => {
    if (props.Id !== "") {
        try {
            const response = await axios.get(`/data-step-${props.Id}`);
            etape.value = response.data;
            console.log(response.data);
        } catch (error) {
            console.error("Error fetching sentiers:", error);
        }
    }
};

// Lifecycle Hook
onMounted(() => {
    fetchStep();
});
</script>

<style scoped>

/* Carrousel */

.carrousel {
    width: 350px;
    height: 400px;
    border-radius: var(--border-radius-medium);
    overflow: hidden;
    box-shadow: var(--box-shadow-light);
    text-decoration: none;
}
.carrousel img {
    width: 100%;
    height: 100%;
}

#poi > div {
    display: flex;
    gap: 20px;
}
.poi-element {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 180px;
    height: 180px;
    border-radius: var(--border-radius-medium);
    overflow: hidden;
    box-shadow: var(--box-shadow-light);
    text-decoration: none;
}
.poi-element img {
    height: 80%;
}
.poi-element p {
    padding-top: 5px;
    font-weight: 500;
}
h3 {
    color: var(--primary);
}
</style>
