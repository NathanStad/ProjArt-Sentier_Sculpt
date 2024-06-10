<template>
    <div v-if="isLoading">
        <p>Loading...</p>
    </div>
    <div v-else id="etape">
        <div class="header">
            <a @click.prevent="goBack">
                <span class="material-symbols-outlined"> arrow_back_ios </span>
            </a>
            <h1>{{ etape.nom }}</h1>
            <div></div>
        </div>
        <!-- Carrousel -->
        <swiper
            :slides-per-view="1"
            :pagination="{ clickable: true }"
            :modules="[Pagination, A11y]"
            @swiper="onSwiper"
            class="carrousel"
        >
            <swiperSlide>
                <!-- Image de l'étape -->
                <img :src="etape.photo" :alt="etape.description" />
            </swiperSlide>
            <swiperSlide v-for="pois in etape.points_interet" :key="pois.id">
                <!-- Images des points d'interet -->
                <img :src="pois.photo" :alt="pois.nom" />
            </swiperSlide>
            <div class="locate">
                <span class="material-symbols-outlined"> location_on </span>
                <p>{{ sentier.localisation }}</p>
            </div>
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
            <a :href="'#stepslist-' + props.Id" id="back">
                <span class="material-symbols-outlined"> keyboard_return </span>
                <img
                    src="/storage/etapes/Capture d’écran 2024-06-09 153325.png"
                    alt="Retour à la carte"
                />
            </a>
        </div>
    </div>
</template>

<script setup>
// Import Swiper styles
import "swiper/css";
import "swiper/css/pagination";
import axios from "axios";
import { defineProps, onMounted, ref } from "vue";
// import Swiper core and required modules
import { Pagination, A11y } from "swiper/modules";

// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";
// Register components
defineExpose({
    components: {
        Swiper,
        SwiperSlide,
    },
    modules: [Pagination, A11y],
});

// Définition des props
const props = defineProps({
    Id: {
        type: String,
        required: true,
    },
});
// Data
const isLoading = ref(true);
const etape = ref();
const sentier = ref();

// Methods
const fetchStep = async () => {
    if (props.Id !== "") {
        try {
            const response = await axios.get(`/data-step-${props.Id}`);
            etape.value = response.data;
            console.log(etape.value);
        } catch (error) {
            console.error("Error fetching sentiers:", error);
        }
    }
};

const fetchSentier = async () => {
    if (props.Id !== "") {
        try {
            const response = await axios.get(
                `/data-sentier-${etape.value.sentier_id}`
            );
            sentier.value = response.data;
        } catch (error) {
            console.error("Error fetching sentiers:", error);
        }
    }
};

const goBack = () => {
    window.history.back();
};
// Lifecycle Hook
onMounted(async () => {
    await fetchStep();
    await fetchSentier();
    isLoading.value = false;
});
</script>

<style scoped>
#etape > * {
    margin-bottom: 10%;
}
h3 {
    font-weight: 600;
    margin-bottom: 2%;
}

/* Carrousel */

.carrousel {
    width: 100%;
    height: 400px;
    border-radius: var(--border-radius-medium);
    overflow: hidden !important;
    box-shadow: var(--box-shadow-light);
    text-decoration: none;
    margin-top: 10%;
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
#back {
    display: block;
    width: 100%;
    height: 100px;
    position: relative;
    background: var(--color-text);
    border-radius: var(--border-radius-medium);
    margin-bottom: 10%;
}
#back span {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    z-index: 5;
    font-size: 5rem;
    color: white;
}
#back img {
    width: 100%;
    height: 100%;
    opacity: 0.5;
}
</style>
