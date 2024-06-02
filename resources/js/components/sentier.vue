<template>
    <!-- Sentier -->
    <div>
        <div><img src="" alt="" />lieu</div>
        <div>
            <img :src="sentier.photo" :alt="sentier.photo" />
            <img
                v-for="etape in etapes"
                :key="etape.id"
                :src="etape.photo"
                :alt="etape.description"
            />
        </div>
        <div>
            <span class="material-symbols-outlined"> location_on </span>
            <p>{{ sentier.localisation }}</p>
        </div>
        <!-- Information Essentiel -->
        <div>
            <!-- Durée -->
            <div>
                <span class="material-symbols-outlined"> schedule </span>
                <p>Durée</p>
                <p>
                    {{
                        sentier.duree < 3600
                            ? `${Math.floor(sentier.duree / 60)} minutes`
                            : `${Math.floor(sentier.duree / 3600)} heures`
                    }}
                </p>
            </div>
            <!-- Distance -->
            <div>
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
                <p>Distance</p>
                <p>{{ sentier.longueur }}km</p>
            </div>
            <!-- Difficulté -->
            <div>
                <span class="material-symbols-outlined"> hiking </span>
                <p>Difficulté</p>
                <p>
                    {{
                        sentier.difficulte === 1
                            ? "facile"
                            : niveau === 2
                            ? "normal"
                            : "difficile"
                    }}
                </p>
            </div>
        </div>
    </div>
    <div>
        <div class="link" @click="showContent('step')">Étapes</div>
        <div class="link" @click="showContent('desc')">Description</div>
        <div class="link" @click="showContent('comms')">Commentaires</div>
    </div>
    <!-- Etapes -->
    <div
        id="step"
        :class="{ content: true, active: currentContent === 'step' }"
    >
        <a :href="`#steps-${sentierId}`" v-for="etape in etapes" :key="etape.id">
            <img :src="etape.photo" :alt="etape.description" />
            <div>
                <p>{{ etape.nom }}</p>
                <div>
                    <span class="material-symbols-outlined"> schedule </span>
                    <p>Durée</p>
                    <p>
                        {{
                            etape.duree < 3600
                                ? `${Math.floor(etape.duree / 60)} minutes`
                                : `${Math.floor(etape.duree / 3600)} heures`
                        }}
                    </p>
                </div>
                <div>
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
                    <p>Distance</p>
                    <p>{{ etape.distance }}km</p>
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
        <div v-for="critere in sentier.criteres" :key="critere.id">
            <p v-html="critere.icone"></p>
            <p>{{ critere.name }}</p>
        </div>
    </div>
    <!-- Commentaires -->
    <div
        id="comms"
        :class="{ content: true, active: currentContent === 'comms' }"
    >
        <div v-for="comms in sentier.commentaires" :key="comms.id">
            <div>
                <div>{{ comms.name.slice(0, 1) }}</div>
                <div>
                    <p>{{ comms.name }}</p>
                    <p>{{ convertissorDate(comms.created_at) }}</p>
                </div>
            </div>
            <p>{{ comms.message }}</p>
        </div>
        <a href="">Voir plus</a>
        <div>
            <form action="">
                <label>
                    Nom :
                    <input type="text" />
                </label>
                <label>
                    Email :
                    <input type="text" />
                </label>
                <input type="submit" value="Envoyer votre messages" />
            </form>
        </div>
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
            message: "",
            sentier: [],
            etapes: [],
            currentContent: "step",
        };
    },
    created() {
        this.incrementCompteur();
        this.fetchSentier();
    },
    methods: {
        incrementCompteur() {
            console.log(this.sentierId.idSection);
            if (this.sentierId != null) {
                axios
                    .get(
                        `/data-sentier/prefere-incr-${this.sentierId.idSection}`
                    )
                    .then((response) => {
                        this.message = response.data.message;
                    })
                    .catch((error) => {
                        if (error.response) {
                            this.message = error.response.data.message;
                        } else {
                            this.message = "An error occurred";
                        }
                        console.error("There was an error!", error);
                    });
            }
        },
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
        showContent(content) {
            this.currentContent = content;
        },
        convertissorDate(date){
            // Obtenir les composants de la date
            const dateInitiale = new Date(date);

            const jour = dateInitiale.getDate();
            const mois = dateInitiale.getMonth() + 1; // Les mois sont indexés à partir de 0, donc ajoutez 1
            const annee = dateInitiale.getFullYear();

            // Formater la date selon le format souhaité (JJ.MM.AAAA)
            const dateFormatee = `${jour < 10 ? '0' : ''}${jour}.${mois < 10 ? '0' : ''}${mois}.${annee}`;

            return dateFormatee
        }
    },
};
</script>
<style>
svg {
    width: 20px;
    fill: #40680c;
}
.content {
    display: none;
}
.content.active {
    display: block;
}
img{
    width: 200px;
}
</style>
