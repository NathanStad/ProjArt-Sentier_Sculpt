<template>
    <div v-if="isLoading">
        <p>Loading...</p>
    </div>
    <div v-else id="sentier">
        <div class="header">
            <a @click.prevent="goBack">
                <span class="material-symbols-outlined"> arrow_back_ios </span>
            </a>
            <h1>
                {{ sentier.nom }} <span v-html="sentier.theme.icone"></span>
            </h1>
            <div @click="copyUrlToClipboard">
                <span class="material-symbols-outlined"> content_copy </span>
            </div>
        </div>
        <!-- Sentier -->
        <div id="seniter-sentier">
            <div>
                <swiper
                    :slides-per-view="1"
                    :pagination="{ clickable: true }"
                    :modules="modules"
                    @swiper="onSwiper"
                    @slideChange="onSlideChange"
                >
                    <swiperSlide>
                        <img :src="sentier.photo" :alt="sentier.photo" />
                    </swiperSlide>
                    <swiperSlide v-for="(etape, index) in etapes" :key="index">
                        <img :src="etape.photo" :alt="etape.description" />
                    </swiperSlide>
                </swiper>
            </div>
            <div class="locate">
                <span class="material-symbols-outlined"> location_on </span>
                <p>{{ sentier.localisation }}</p>
            </div>
            <buttonFavoris id="favoris" :sentierId="sentier.id"></buttonFavoris>
            <!-- Information Essentiel -->
            <div class="info-box">
                <!-- Durée -->
                <div class="info-box-element">
                    <span class="material-symbols-outlined"> schedule </span>
                    <div>
                        <p>Durée</p>
                        <p>
                            {{
                                sentier.duree < 3600
                                    ? `${Math.floor(sentier.duree / 60)} min`
                                    : `${Math.floor(sentier.duree / 3600)} h`
                            }}
                        </p>
                    </div>
                </div>
                <!-- Distance -->
                <div class="info-box-element">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        id="Calque_2"
                        data-name="Calque 2"
                        viewBox="0 0 303.24 290.05"
                    >
                        <g id="Calque_2-2" data-name="Calque 2">
                            <path
                                class="cls-1"
                                d="M251.6,290.05c-1.12,0-2.24-.06-3.37-.19-17.79-1.96-31.2-18.61-45.65-38.53-17.78-24.51-41.72-34.12-75.34-30.24-18.11,2.09-37.57,19.62-53.2,33.71-7.51,6.76-13.99,12.6-19.97,16.54-12.83,8.45-21.18,5.47-25.92,1.49C12.32,259.54-.56,226.35.02,200.36c.49-22.18,13.62-43.98,24.55-58.36,13.25-17.44,30.51-33.29,46.16-42.4,10.68-6.21,15.62-14.39,21.34-23.85,7.28-12.04,15.52-25.69,35.85-34.21,20.29-8.51,30.9-7.17,38.65-6.19,5.1.65,7.21.91,12.44-1.7,3.95-1.98,9.66-6.87,15.18-11.61C207.19,10.88,221.96-1.78,238.09.21c8.17,1,15.16,5.51,20.79,13.42,14.81,20.81-8.32,56.17-32.8,93.6-10.44,15.96-27.81,42.51-27.59,50.28,4.15,7.72,6.78,8.31,11.89,9.46,4.98,1.12,11.8,2.65,18.26,9.44,5.25,5.51,8.13,7.01,9.39,7.42.54.18.77.25,1.94-.71l1.69-1.39c20.08-16.48,34.91-26.17,46.79-23.65,5,1.07,9.01,4.18,11.31,8.77,5.5,11,3.34,38.55,1.2,53.09-2.92,19.86-9.52,46.28-24.33,59.37-8.08,7.14-16.49,10.74-25.03,10.74ZM141.22,197.63c33.84,0,60,13.31,79.67,40.41,7.68,10.59,20.54,28.31,29.82,29.33,1.15.13,4.67.51,10.94-5.03,15.19-13.43,20.49-62.69,18.74-80.56-4.18,2.09-11.52,6.86-24.38,17.42l-1.78,1.46c-12.62,10.25-26.74,7.33-41.97-8.67-1.66-1.74-2.97-2.1-6.85-2.97-7.2-1.62-18.08-4.07-27.27-21.62-7.83-14.95,5.24-36.23,29.01-72.57,10.26-15.68,20.86-31.89,27.49-45.64,8.7-18.05,5.84-22.42,5.81-22.46-1.79-2.52-3.51-3.89-5.1-4.09-6.24-.79-18.4,9.66-26.44,16.56-6.86,5.88-13.35,11.45-19.79,14.67-11.36,5.68-19.14,4.7-25.39,3.91-6.08-.77-12.36-1.56-27.07,4.61-13.52,5.67-18.69,14.22-25.24,25.05-6.4,10.59-13.65,22.58-29.32,31.7-27.01,15.72-58.88,54.83-59.47,81.71h0c-.42,18.92,8.91,42.57,17.77,52.41,4.43-2.61,12.41-9.8,18.49-15.27,18.03-16.24,40.47-36.46,65.75-39.37,5.72-.66,11.24-.99,16.58-.99Z"
                            />
                        </g>
                    </svg>
                    <div>
                        <p>Distance</p>
                        <p>{{ sentier.longueur }}km</p>
                    </div>
                </div>
                <!-- Difficulté -->
                <div class="info-box-element">
                    <span class="material-symbols-outlined"> hiking </span>
                    <div>
                        <p>Difficulté</p>
                        <p>
                            {{
                                sentier.difficulte === 1
                                    ? "Facile"
                                    : sentier.difficulte === 2
                                    ? "Normal"
                                    : "Difficile"
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="menu">
            <div
                class="link"
                @click="showContent('step')"
                :class="{ activated: currentContent === 'step' }"
            >
                Étapes
            </div>
            <div
                class="link"
                @click="showContent('desc')"
                :class="{ activated: currentContent === 'desc' }"
            >
                Description
            </div>
            <div
                class="link"
                @click="showContent('comms')"
                :class="{ activated: currentContent === 'comms' }"
            >
                Commentaires
            </div>
        </div>
        <!-- Etapes -->
        <div
            id="step"
            :class="{ content: true, active: currentContent === 'step' }"
        >
            <a
                :href="`#step-${etape.id}`"
                v-for="etape in etapes"
                :key="etape.id"
            >
                <img :src="etape.photo" :alt="etape.description" />
                <div class="info-box">
                    <p>{{ etape.nom }}</p>
                    <div class="info-box-element">
                        <span class="material-symbols-outlined">
                            schedule
                        </span>
                        <div>
                            <p>Durée</p>
                            <p>
                                {{
                                    etape.duree < 3600
                                        ? `${Math.floor(etape.duree / 60)} min`
                                        : `${Math.floor(etape.duree / 3600)} h`
                                }}
                            </p>
                        </div>
                    </div>
                    <div class="info-box-element">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            id="Calque_2"
                            data-name="Calque 2"
                            viewBox="0 0 303.24 290.05"
                        >
                            <g id="Calque_2-2" data-name="Calque 2">
                                <path
                                    class="cls-1"
                                    d="M251.6,290.05c-1.12,0-2.24-.06-3.37-.19-17.79-1.96-31.2-18.61-45.65-38.53-17.78-24.51-41.72-34.12-75.34-30.24-18.11,2.09-37.57,19.62-53.2,33.71-7.51,6.76-13.99,12.6-19.97,16.54-12.83,8.45-21.18,5.47-25.92,1.49C12.32,259.54-.56,226.35.02,200.36c.49-22.18,13.62-43.98,24.55-58.36,13.25-17.44,30.51-33.29,46.16-42.4,10.68-6.21,15.62-14.39,21.34-23.85,7.28-12.04,15.52-25.69,35.85-34.21,20.29-8.51,30.9-7.17,38.65-6.19,5.1.65,7.21.91,12.44-1.7,3.95-1.98,9.66-6.87,15.18-11.61C207.19,10.88,221.96-1.78,238.09.21c8.17,1,15.16,5.51,20.79,13.42,14.81,20.81-8.32,56.17-32.8,93.6-10.44,15.96-27.81,42.51-27.59,50.28,4.15,7.72,6.78,8.31,11.89,9.46,4.98,1.12,11.8,2.65,18.26,9.44,5.25,5.51,8.13,7.01,9.39,7.42.54.18.77.25,1.94-.71l1.69-1.39c20.08-16.48,34.91-26.17,46.79-23.65,5,1.07,9.01,4.18,11.31,8.77,5.5,11,3.34,38.55,1.2,53.09-2.92,19.86-9.52,46.28-24.33,59.37-8.08,7.14-16.49,10.74-25.03,10.74ZM141.22,197.63c33.84,0,60,13.31,79.67,40.41,7.68,10.59,20.54,28.31,29.82,29.33,1.15.13,4.67.51,10.94-5.03,15.19-13.43,20.49-62.69,18.74-80.56-4.18,2.09-11.52,6.86-24.38,17.42l-1.78,1.46c-12.62,10.25-26.74,7.33-41.97-8.67-1.66-1.74-2.97-2.1-6.85-2.97-7.2-1.62-18.08-4.07-27.27-21.62-7.83-14.95,5.24-36.23,29.01-72.57,10.26-15.68,20.86-31.89,27.49-45.64,8.7-18.05,5.84-22.42,5.81-22.46-1.79-2.52-3.51-3.89-5.1-4.09-6.24-.79-18.4,9.66-26.44,16.56-6.86,5.88-13.35,11.45-19.79,14.67-11.36,5.68-19.14,4.7-25.39,3.91-6.08-.77-12.36-1.56-27.07,4.61-13.52,5.67-18.69,14.22-25.24,25.05-6.4,10.59-13.65,22.58-29.32,31.7-27.01,15.72-58.88,54.83-59.47,81.71h0c-.42,18.92,8.91,42.57,17.77,52.41,4.43-2.61,12.41-9.8,18.49-15.27,18.03-16.24,40.47-36.46,65.75-39.37,5.72-.66,11.24-.99,16.58-.99Z"
                                />
                            </g>
                        </svg>
                        <div>
                            <p>Distance</p>
                            <p>{{ Math.round(+etape.distance) }}km</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Description -->
        <div
            id="desc"
            :class="{ content: true, active: currentContent === 'desc' }"
        >
            <p>{{ sentier.description }}</p>

            <div
                v-for="critere in sentier.criteres"
                :key="critere"
                v-html="critere.icone + '<p>' + critere.name + '</p>'"
            ></div>
        </div>
        <!-- Commentaires -->
        <div
            id="comms"
            :class="{ content: true, active: currentContent === 'comms' }"
        >
            <ul>
                <li
                    v-for="commentaire in sortedCommentaires"
                    :key="commentaire.id"
                >
                    <div>
                        <div
                            :style="{
                                backgroundColor: commentaire.backgroundColor,
                            }"
                        >
                            {{ commentaire.name.slice(0, 1).toUpperCase() }}
                        </div>
                        <div>
                            <p>{{ commentaire.name }}</p>
                            <p>
                                {{ convertissorDate(commentaire.created_at) }}
                            </p>
                        </div>
                    </div>
                    <p>{{ commentaire.message }}</p>
                </li>
            </ul>
            <form @submit.prevent="submitComment">
                <div>
                    <textarea
                        v-model="newComment.message"
                        placeholder="Rédiger un commentaire"
                        required
                    ></textarea>
                    <input
                        v-model="newComment.name"
                        type="text"
                        placeholder="Votre nom"
                        required
                    />
                    <input
                        v-model="newComment.email"
                        type="email"
                        placeholder="Votre email"
                        required
                    />
                    <button type="submit" class="button">
                        <span class="material-symbols-outlined"> send </span>
                    </button>
                    <div v-if="showNotification" class="notification">
                        <div>
                            <span class="material-symbols-outlined">
                                check
                            </span>
                            <p>
                                Commentaire envoyé avec succès. <br />
                                Merci !
                            </p>
                            <span
                                class="material-symbols-outlined"
                                @click="toggleCloseNotif"
                            >
                                close
                            </span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <a class="boutton-demarer" :href="`#stepslist-${props.Id}`">Démarrer</a>
    </div>
</template>

<script setup>
import { ref, onMounted, defineProps, computed } from "vue";
import axios from "axios";
import buttonFavoris from "@/components/elements/buttonFavorite.vue";
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
// Import Swiper styles
import "swiper/css";
import "swiper/css/pagination";

const goBack = () => {
    window.history.back();
};

const modules = [Pagination, A11y];

// Définition des props
const props = defineProps({
    Id: {
        type: Number,
        required: true,
    },
});

// Data
const message = ref("");
const sentier = ref({});
const etapes = ref([]);
const commentaires = ref([]);
const currentContent = ref("step");
const isLoading = ref(true);
const showNotification = ref(false);
function getRandomColor() {
    // Génère une valeur de couleur aléatoire pour la composante G (verte)
    const g = Math.floor(Math.random() * 175) + 81;
    // Fixe les valeurs des composantes R et B à 0
    const r = Math.floor(Math.random() * 64);
    const b = Math.floor(Math.random() * 14);
    // Combine les valeurs pour former une couleur au format hexadécimal
    return (
        "#" +
        r.toString(16).padStart(2, "0") +
        g.toString(16).padStart(2, "0") +
        b.toString(16).padStart(2, "0")
    );
}
const attributionColor = () => {
    for (let index = 0; index < commentaires.value.length; index++) {
        commentaires.value[index].backgroundColor = getRandomColor();
    }
    console.log(commentaires.value);
};
// Methods
const incrementCompteur = () => {
    if (props.Id !== "") {
        axios
            .get(`/data-sentier/prefere-incr-${props.Id}`)
            .then((response) => {
                message.value = response.data.message;
            })
            .catch((error) => {
                if (error.response) {
                    message.value = error.response.data.message;
                } else {
                    message.value = "An error occurred";
                }
                console.error("There was an error!", error);
            });
    }
};

const fetchSentier = async () => {
    if (props.Id !== "") {
        try {
            const response = await axios.get(`/data-sentier-${props.Id}`);
            sentier.value = response.data;
            etapes.value = response.data.etapes;
            commentaires.value = response.data.commentaires;
            attributionColor();
        } catch (error) {
            console.error("Error fetching sentiers:", error);
        }
    }
};

const showContent = (content) => {
    currentContent.value = content;
};

const convertissorDate = (date) => {
    const dateInitiale = new Date(date);
    const jour = dateInitiale.getDate();
    const mois = dateInitiale.getMonth() + 1; // Les mois sont indexés à partir de 0, donc ajoutez 1
    const annee = dateInitiale.getFullYear();
    const dateFormatee = `${jour < 10 ? "0" : ""}${jour}.${
        mois < 10 ? "0" : ""
    }${mois}.${annee}`;
    return dateFormatee;
};
const sortedCommentaires = computed(() => {
    return commentaires.value
        .slice()
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

// Envoie du commentaire
const submitComment = async () => {
    console.log(newComment.value);
    try {
        const response = await axios.post(`/commentaire`, {
            sentier_id: props.Id,
            name: newComment.value.name,
            email: newComment.value.email,
            message: newComment.value.message,
        });
        console.log(response);
        if (response.statusText == "Created") {
            newComment.value.name = "";
            newComment.value.email = "";
            newComment.value.message = "";
            fetchSentier();
            toggleCloseNotif();
        } else {
            console.error("Error submitting comment");
        }
    } catch (error) {
        console.error("Error submitting comment:", error);
    }
};
// Copie dans le presse papier
const copyUrlToClipboard = () => {
    const url = window.location.href;
    navigator.clipboard
        .writeText(url)
        .then(() => {
            console.log("URL copied to clipboard");
        })
        .catch((err) => {
            console.error("Failed to copy: ", err);
        });
};
const toggleCloseNotif = () => {
    showNotification.value = !showNotification.value;
};
// Lifecycle Hook
onMounted(async () => {
    await incrementCompteur();
    await fetchSentier();
    isLoading.value = false;
});
const newComment = ref({
    name: "",
    email: "",
    message: "",
});
</script>

<style>
h1 {
    display: flex;
    align-content: center;
    align-items: center;
    justify-content: center;
}
h1 span {
    display: flex;
    align-content: center;
    align-items: center;
    justify-content: center;
    color: var(--primary);
}
#sentier {
    min-height: 90vh;
}
/* Sentier */

#seniter-sentier {
    display: block;
    border-radius: var(--border-radius-medium);
    overflow: hidden;
    position: relative;
    width: 350;
    height: 450px;
    box-shadow: var(--box-shadow-light);
}
#seniter-sentier img {
    object-fit: cover;
    width: 100%;
    height: 100%;
}

#seniter-sentier > div:first-of-type {
    height: 80%;
}
#seniter-sentier div:first-of-type .swiper,
#seniter-sentier div:first-of-type .swiper-wrapper,
#seniter-sentier div:first-of-type .swiper-slide {
    height: 100%;
}
.locate {
    width: fit-content;
    height: fit-content;
    display: flex;
    align-items: center;
    gap: 5px;
    position: absolute;
    top: 4%;
    left: 5%;
    z-index: 3;
    background: rgba(255, 255, 255, 0.6);
    padding: var(--padding-small) var(--padding-large);
    border-radius: var(--border-radius-large);
}
.locate span {
    font-size: 1.7rem;
}

.info-box {
    display: flex;
    align-items: center;
    justify-content: space-around;
    height: 20%;
    padding: 0 10px;
}
.info-box-element {
    display: flex;
    align-items: center;
    gap: 5px;
}
.info-box-element > div > p:first-of-type {
    font-weight: 300;
    font-size: 0.8rem;
    color: var(--primary);
}
.info-box-element > div > p:last-of-type {
    font-weight: 500;
    font-size: 1.1rem;
    color: var(--primary);
}
.info-box-element svg,
.info-box-element span {
    width: 1.5rem;
    font-size: 1.5rem;
    text-align: center;
    color: var(--primary);
}

/* Menu des prochains affichages */

.menu {
    height: 100px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Affichage Étapes */

#step {
    flex-direction: column;
    gap: 50px;
}
#step a {
    display: block;
    width: 350;
    height: 250px;
    border-radius: var(--border-radius-medium);
    overflow: hidden;
    box-shadow: var(--box-shadow-light);
    text-decoration: none;
}
#step img {
    height: 60%;
    width: 100%;
}
#step .info-box {
    height: 40%;
}
#step .info-box > p {
    width: 30%;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary);
}

/* Styles CSS pour le composant */
.content {
    display: none;
}
.content.active {
    display: flex;
}
.link {
    cursor: pointer;
}
.boutton-demarer {
    width: 60vw;
    background: var(--primary);
    padding: var(--padding-large) var(--padding-large);
    position: fixed;
    bottom: 6%;
    text-align: center;
    color: white;
    left: 50%;
    transform: translateX(-50%);
    border-radius: var(--border-radius-full);
    font-weight: 600;
    font-size: 1.2rem;
    z-index: 5;
    text-decoration: none;
}
#sentier,
#desc,
#step {
    margin-bottom: 40%;
}
#desc {
    flex-direction: column;
    gap: 30px;
}
#desc > div {
    gap: 10px;
    display: flex;
    align-items: center;
}
#desc > div span {
    font-size: 1.7rem;
    color: var(--primary);
}
#desc > div p {
    color: var(--primary);
}
#comms {
    flex-direction: column;
}
ul {
    list-style-type: none;
    height: 50vh;
    overflow: hidden scroll;
    margin-bottom: 10%;
}
li {
    padding-bottom: 10%;
}
li:last-of-type {
    padding-bottom: 15%;
}
li > div {
    padding-bottom: 5%;
    display: flex;
    align-items: center;
    gap: 20px;
}
li > div div:first-of-type {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 400;
    font-size: 1.5rem;
    width: 50px;
    height: 50px;
    border-radius: 50px;
    mix-blend-mode: exclusion;
}
li > div div:last-of-type p:first-of-type {
    font-weight: 700;
}
li > div div:last-of-type p:last-of-type {
    font-weight: 400;
    font-size: 0.8rem;
    color: var(--color-text-secondary);
}
li > p {
    width: 95%;
}
#comms form > div {
    background: white;
    box-shadow: var(--box-shadow-light);
    border-radius: var(--border-radius-large);
    position: relative;
    padding: 5%;
}
#comms form > div > input,
#comms form > div > textarea {
    width: 100% !important;
    box-shadow: none !important;
    border-radius: 0 !important;
    position: relative;
    margin: 0 !important;
    padding: 20px 10px !important;
}
#comms form > div > input:first-of-type,
#comms form > div > textarea {
    border-bottom: 1px var(--color-text-secondary) solid;
}
#comms form > div button {
    background: none;
    box-shadow: none !important;
    position: absolute;
    width: fit-content;
    transform: translate(120%);
    bottom: -2.5%;
}
#comms form > div button span {
    color: var(--primary);
}
</style>
<style scoped>
.header div span {
    color: var(--color-text-secondary);
    cursor: pointer;
}
.notification {
    position: absolute;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    top: 0px;
    right: 0px;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.6);
    z-index: 4;
    text-align: center;
    color: var(--primary);
}
.notification div {
    position: relative;
    padding: 5px 10px;
    border: 1px solid var(--color-text-secondary);
    border-radius: var(--border-radius-medium);
    width: 80%;
}
.notification span:last-of-type {
    font-size: 2rem;
    color: var(--color-text-secondary);
    position: absolute;
    top: 5%;
    right: 5%;
}
.notification span:first-of-type {
    font-size: 4rem;
    color: var(--primary);
}
.notification p {
    color: var(--primary);
    font-weight: 600;
}
@media only screen and (min-width: 900px) {
    #seniter-sentier {
        margin: 0 10%;
        height: 600px;
    }
    .menu {
        margin: 0 10%;
    }
    .content {
        display: none;
    }
    #step.active {
        display: grid;
        margin: 0 10%;
        grid-template-columns: 1fr 1fr;
    }
    #desc.active {
        margin: 0 10%;
    }
    #sentier,
    #desc,
    #step {
        margin-bottom: 7%;
    }
    #step a {
        height: 350px;
    }
}
</style>
