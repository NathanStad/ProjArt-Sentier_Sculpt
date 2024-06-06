<template>
    <div class="container">
        <h1 class="titreEtape">Étape {{ this.noEtape }}</h1>
        <form @submit.prevent="handleSubmit" v-if="etapes">
            <div class="input-container">
                <label for="nom">Nom de l'étape</label>
                <input
                    type="text"
                    v-model="etapes.nom"
                    id="nom"
                    placeholder="Nom de l'étape"
                    maxlength="100"
                    required
                />
                <span class="char-limit">max. 100 caractères</span>
            </div>
            <div class="input-container">
                <label for="description">Description de l'étape</label>
                <textarea
                    v-model="etapes.description"
                    id="description"
                    placeholder="Description de l'étape"
                    maxlength="2000"
                    required
                ></textarea>
                <span class="char-limit">max. 2000 caractères</span>
            </div>
            <div class="input-container">
                <label>Pointer le lieu de l'étape</label>
                <div class="map">
                    <ChoixPosition
                        :etapes="etapes"
                        :updateCoordinates="updateCoordinates"
                    />
                </div>
            </div>

            <div v-if="etapes.photo" class="input-container">
                <label>Ajouter une photo</label>
                <label for="photo">
                    <img :src="etapes.photo" alt="image de l'etape" />
                    <span class="material-symbols-outlined editIcon">edit</span>
                </label>
                <input
                    type="file"
                    id="photo"
                    @change="handleFileUploadEtapes"
                    style="display: none"
                />
            </div>
            <div v-else class="input-container">
                <label>Ajouter une photo</label>
                <label for="photo">
                    <span class="material-symbols-outlined"> add_a_photo </span>
                </label>
                <input
                    type="file"
                    id="photo"
                    @change="handleFileUploadEtapes"
                    style="display: none"
                />
            </div>

            <div class="input-container" v-if="etapes.pointInteret">
                <label>Point d'intérêts</label>
                <div class="ptInteret">
                    <input
                        type="text"
                        v-model="etapes.pointInteret[0].nom"
                        placeholder="Nom du point d'intérêt"
                    />
                    <label for="photoInteret-1">
                        <span class="material-symbols-outlined"
                            >add_a_photo</span
                        >
                    </label>
                    <input
                        type="file"
                        id="photoInteret-1"
                        @change="(e) => handleFileUploadPoi(e, 0)"
                        style="display: none"
                    />
                </div>
                <div class="ptInteret">
                    <input
                        type="text"
                        v-model="etapes.pointInteret[1].nom"
                        placeholder="Nom du point d'intérêt"
                    />
                    <label for="photoInteret-2">
                        <span class="material-symbols-outlined"
                            >add_a_photo</span
                        >
                    </label>
                    <input
                        type="file"
                        id="photoInteret-2"
                        @change="(e) => handleFileUploadPoi(e, 1)"
                        style="display: none"
                    />
                </div>
            </div>
            <div class="btnSuivant">
                <input type="submit" value="Envoyer">
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import ChoixPosition from './elements/choixPosition.vue';

// Déclaration des états réactifs
const etapes = ref([]);
const noEtape = ref(null);
const nomPhotoEtape = ref(null);
const nomPhotoPoi = ref([null]);

// Récupération des données de sessionStorage à l'initialisation du composant
onMounted(() => {
    const index = parseInt(window.location.hash.split("-")[1]) - 1;
    const etapesData = sessionStorage.getItem("etapes");
    if (etapesData) {
        etapes.value = JSON.parse(etapesData)[index];
        noEtape.value = index + 1;
    } else {
        console.error("No 'etapes' data in sessionStorage.");
    }
});

// Surveillance des changements de l'état `etapes`
watch(etapes, (newEtapes) => {
    console.log(newEtapes);
}, { deep: true });

// Fonction pour gérer le téléchargement de fichier pour l'étape
const handleFileUploadEtapes = async (e) => {
    const file = e.target.files[0];
    const formData = new FormData();
    formData.append('photoEtapes', file);

    try {
        const response = await axios.post('/submit-file-etapes', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        nomPhotoEtape.value = response.data.fileName;
        etapes.value.photo = `/storage/${response.data.path}`;
    } catch (error) {
        console.error('Error uploading file:', error);
    }
};

// Fonction pour gérer le téléchargement de fichier pour les points d'intérêt
const handleFileUploadPoi = async (e, index) => {
    const file = e.target.files[0];
    const formData = new FormData();
    formData.append('photoPoi', file);

    try {
        const response = await axios.post('/submit-file-poi', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        nomPhotoPoi.value[index] = response.data.fileName;
        etapes.value.pointInteret[index].photo = `/storage/${response.data.path}`;
    } catch (error) {
        console.error('Error uploading file:', error);
    }
};

// Fonction pour mettre à jour les coordonnées
const updateCoordinates = (lngLat) => {
    etapes.value.coordonnees.long = lngLat.lng;
    etapes.value.coordonnees.lat = lngLat.lat;
};

// Fonction pour gérer la soumission du formulaire
const handleSubmit = () => {
    const index = parseInt(window.location.hash.split("-")[1]) - 1;
    const etapesData = sessionStorage.getItem("etapes");
    if (etapesData) {
        let etapesArray = JSON.parse(etapesData);
        console.log(etapesArray[index]);
        etapesArray[index] = etapes.value;
        console.log(etapesArray[index]);
        sessionStorage.setItem("etapes", JSON.stringify(etapesArray));
        console.log(sessionStorage.getItem("etapes"));
        window.location.hash = `creationSteps`;
    } else {
        console.error("No 'etapes' data in sessionStorage.");
    }
};

// Déclaration des composants enfants
const components = {
    ChoixPosition,
};
</script>


<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
.container img {
    width: 100%;
    height: 100%;
}

.btnSuivant {
    display: flex;
    justify-content: center;
    width: 100%;
}

.btnSuivant button {
    height: 60px;
    width: 205px;
    border: none;
    padding-top: 0;
    border-radius: 30px;
    background-color: #f1f1f1;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    background-color: var(--primary);
    color: white;
}

.container {
    height: 100%;
    width: 90%;
    margin: auto;
    margin-bottom: 20%;
}

.titreEtape {
    text-align: center;
    padding-top: 20px;
    margin-bottom: 20px;
}

.input-container {
    width: 100%;
    padding-bottom: 30px;
    position: relative;
}

.input-container input {
    width: 100%;
    height: 35px;
    border: 1px solid #ccc;
    padding-left: 20px;
    border-radius: 10px;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.input-container textarea {
    padding-top: 10px;
    width: 100%;
    height: 70px;
    border: 1px solid #ccc;
    padding-left: 20px;
    border-radius: 10px;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    resize: vertical;
    min-height: 100px;
}

.char-limit {
    position: absolute;
    right: 5%;
    bottom: 40px;
    font-size: 14px;
    color: #aaa;
    pointer-events: none;
}

.input-container label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.input-container label[for="photo"] {
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 10px;
    height: 75px;
    font-size: 40px;
    color: #676464;
    cursor: pointer;
}

.material-symbols-outlined {
    font-size: 30px;
}

.ptInteret {
    display: flex;
    width: 100%;
    justify-content: space-between;
    align-items: center;
    padding-right: 25px;
}

.ptInteret input {
    width: 80%;
    height: 35px;
    border: 1px solid #ccc;
    padding-left: 20px;
    border-radius: 10px;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 10px;
}

.map {
    width: 100%;
    height: 340px;
    border-radius: 10px;
    position: relative;
}
</style>
