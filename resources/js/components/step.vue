<template>
    <div class="header">
        <a @click.prevent="goBack">
            <span class="material-symbols-outlined"> arrow_back_ios </span>
        </a>
        <h1>{{ etape.nom }}</h1>
        <div></div>
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
        <a @click.prevent="goBack" id="back">
            <span class="material-symbols-outlined"> keyboard_return </span>
            <img
                src="/storage/pois/Capture d’écran 2024-06-06 115019.png"
                alt="Retour à la carte"
            />
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
const goBack = () => {
    window.history.back();
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
    padding-top: 7px;
    font-weight: 700;
}
h3 {
    color: var(--primary);
}
#back{
    display: block;
    width: 100%;
    height: fit-content;
    position: relative;
    background: var(--color-text);
    border-radius: var(--border-radius-medium);
    margin-bottom: 20%;
}
#back span{
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    z-index: 5;
    font-size: 5rem;
    color: white;
}
#back img{
    width: 100%;
    height: 100%;
    opacity: 0.5;
}
</style>
