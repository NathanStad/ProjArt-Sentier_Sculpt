<template>
    <div class="header">
        <a href="#creationSteps">
            <span class="material-symbols-outlined"> arrow_back_ios </span>
        </a>
        <h1>Création de l'étape {{ noEtape }}</h1>
        <div></div>
    </div>
    <div class="container">
        <form @submit.prevent="handleSubmit" v-if="etapes">
            <div class="input-container">
                <p>Nom de l'étape</p>
                <input
                    type="text"
                    v-model="etapes.nom"
                    id="nom"
                    placeholder="Nom de l'étape"
                    maxlength="100"
                    required
                />
                <span v-if="errors.nom" class="error">{{ errors.nom }}</span>
            </div>
            <div class="input-container">
                <p>Description de l'étape</p>
                <textarea
                    v-model="etapes.description"
                    id="description"
                    placeholder="Description de l'étape"
                    maxlength="2000"
                    required
                ></textarea>
                <span class="char-limit">max. 2000 caractères</span>
                <span v-if="errors.description" class="error">{{
                    errors.description
                }}</span>
            </div>
            <div class="input-container">
                <p>Pointer le lieu de l'étape</p>
                <div class="map">
                    <ChoixPosition
                        :etapes="etapes"
                        :updateCoordinates="updateCoordinates"
                    />
                </div>
                <span v-if="errors.coordinates" class="error">{{
                    errors.coordinates
                }}</span>
            </div>

            <div v-if="etapes.photo" class="input-container">
                <p>Ajouter une photo</p>
                <label for="photo" class="addPhoto">
                    <img :src="etapes.photo" alt="image de l'etape" />
                    <span class="material-symbols-outlined editIcon">edit</span>
                </label>
                <input
                    type="file"
                    name="photo"
                    id="photo"
                    @change="handleFileUploadEtapes"
                    style="display: none"
                />
            </div>
            <div v-else class="input-container step">
                <p>Ajouter une photo</p>
                <label for="photo" class="addPhoto">
                    <span class="material-symbols-outlined"> add_a_photo </span>
                </label>
                <input
                    type="file"
                    name="photo"
                    id="photo"
                    @change="handleFileUploadEtapes"
                    style="display: none"
                />
                <span v-if="errors.photo" class="error">{{
                    errors.photo
                }}</span>
            </div>

            <div class="input-container" v-if="etapes.pointInteret">
                <p>Point d'intérêts</p>
                <div class="ptInteret">
                    <input
                        type="text"
                        v-model="etapes.pointInteret[0].nom"
                        placeholder="Nom du point d'intérêt"
                    />
                    <label
                        for="photoInteret-1"
                        v-if="etapes.pointInteret[0].photo"
                        class="addPhotoPoi"
                    >
                        <img
                            :src="etapes.pointInteret[0].photo"
                            alt="image de l'etape"
                        />
                        <span class="material-symbols-outlined editIcon"
                            >edit</span
                        >
                    </label>
                    <label for="photoInteret-1" v-else class="addPhotoPoi">
                        <span class="material-symbols-outlined"
                            >add_a_photo</span
                        >
                    </label>
                    <input
                        type="file"
                        name="photoInteret-1"
                        id="photoInteret-1"
                        @change="(e) => handleFileUploadPoi(e, 0)"
                        style="display: none"
                    />
                </div>
                <div class="ptInteret">
                    <input
                        name="ptInteret1"
                        type="text"
                        v-model="etapes.pointInteret[1].nom"
                        placeholder="Nom du point d'intérêt"
                    />
                    <label
                        for="photoInteret-2"
                        v-if="etapes.pointInteret[1].photo"
                        class="addPhotoPoi"
                    >
                        <img
                            :src="etapes.pointInteret[1].photo"
                            alt="image de l'etape"
                        />
                        <span class="material-symbols-outlined editIcon"
                            >edit</span
                        >
                    </label>
                    <label v-else for="photoInteret-2" class="addPhotoPoi">
                        <span class="material-symbols-outlined"
                            >add_a_photo</span
                        >
                    </label>
                    <input
                        name="ptInteret2"
                        type="file"
                        id="photoInteret-2"
                        @change="(e) => handleFileUploadPoi(e, 1)"
                        style="display: none"
                    />
                </div>
            </div>
            <div class="btnSuivant">
                <input type="submit" value="Suivant" />
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import ChoixPosition from "./elements/choixPosition.vue";

// Déclaration des états réactifs
const etapes = ref([]);
const noEtape = ref(null);
const nomPhotoEtape = ref(null);
const nomPhotoPoi = ref([null]);

// Ajout de l'état réactif pour les erreurs
const errors = ref({
    nom: "",
    description: "",
    coordinates: "",
    photo: "",
    // Ajoutez d'autres erreurs si nécessaire pour les autres champs
});

// Récupération des données de sessionStorage à l'initialisation du composant
onMounted(() => {
    const index = parseInt(window.location.hash.split("-")[1]) - 1;
    const etapesData = sessionStorage.getItem("etapes");
    if (etapesData) {
        etapes.value = JSON.parse(etapesData)[index];
        if (!etapes.value.pointInteret[0] && !etapes.value.pointInteret[1]) {
            for (let index = 0; index < 1; index++) {
                etapes.value.pointInteret[index].nom = ''
                etapes.value.pointInteret[index].photo = ''
            }
        }
        if (!etapes.value.pointInteret[1]) {
                etapes.value.pointInteret[1].nom = ''
                etapes.value.pointInteret[1].photo = ''
        }
        etapes.value = JSON.parse(etapesData)[index];
        noEtape.value = index + 1;
        console.log(etapes.value);
    } else {
        console.error("No 'etapes' data in sessionStorage.");
    }
});

// Surveillance des changements de l'état `etapes`
watch(
    etapes,
    (newEtapes) => {
        console.log(newEtapes);
    },
    { deep: true }
);

// Fonction pour gérer le téléchargement de fichier pour l'étape
const handleFileUploadEtapes = async (e) => {
    const file = e.target.files[0];
    const formData = new FormData();
    formData.append("photoEtapes", file);
    console.log(formData);
    try {
        const response = await axios.post("/submit-file-etapes", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        nomPhotoEtape.value = response.data.fileName;
        etapes.value.photo = `/storage/${response.data.path}`;
    } catch (error) {
        console.error("Error uploading file:", error);
    }
};

// Fonction pour gérer le téléchargement de fichier pour les points d'intérêt
const handleFileUploadPoi = async (e, index) => {
    const file = e.target.files[0];
    const formData = new FormData();
    formData.append("photoPoi", file);

    try {
        const response = await axios.post("/submit-file-poi", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        nomPhotoPoi.value[index] = response.data.fileName;
        etapes.value.pointInteret[
            index
        ].photo = `/storage/${response.data.path}`;
    } catch (error) {
        console.error("Error uploading file:", error);
    }
};

// Fonction pour mettre à jour les coordonnées
const updateCoordinates = (lngLat) => {
    etapes.value.coordonnees = { long: lngLat.lng, lat: lngLat.lat };
};

// Fonction pour gérer la soumission du formulaire
const handleSubmit = () => {
    // Validation des champs
    let valid = true;
    errors.value.nom = "";
    errors.value.description = "";
    errors.value.coordinates = "";
    errors.value.photo = "";

    if (!etapes.value.nom) {
        errors.value.nom = "Le nom de l'étape est requis.";
        valid = false;
    }
    if (!etapes.value.description) {
        errors.value.description = "La description de l'étape est requise.";
        valid = false;
    }
    if (
        !etapes.value.coordonnees ||
        !etapes.value.coordonnees.long ||
        !etapes.value.coordonnees.lat
    ) {
        errors.value.coordinates = "Les coordonnées de l'étape sont requises.";
        valid = false;
    }
    if (!etapes.value.photo) {
        errors.value.photo = "La photo de l'étape est requise.";
        valid = false;
    }
    // Ajoutez d'autres validations si nécessaire pour les autres champs

    // Soumettre le formulaire si tout est valide
    if (valid) {
        const index = parseInt(window.location.hash.split("-")[1]) - 1;
        const etapesData = sessionStorage.getItem("etapes");
        console.log(etapes.value    );
        if (etapesData) {
            let etapesArray = JSON.parse(etapesData);
            etapesArray[index] = etapes.value;
            console.log(etapesArray[index]);
            sessionStorage.setItem("etapes", JSON.stringify(etapesArray));
            console.log(sessionStorage.getItem("etapes"));
            window.location.hash = `creationSteps`;
        } else {
            console.error("No 'etapes' data in sessionStorage.");
        }
    }
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
    width: 100%;
    margin: auto;
}

.step label[for="photo"] {
    width: 100% !important;
    height: 150px;
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
    position: relative;
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

.ptInteret input {
    margin-bottom: 0 !important;
    margin-right: 10px !important;
}
.ptInteret span{
    font-size: 1rem;
    color: white;
    top: -5px !important;
    right: -5px !important;
}
.container .input-container .editIcon{
    top: 25px;
    right: -15px;
}
.map {
    width: 100%;
    height: 340px;
    border-radius: 10px;
    position: relative;
}
.container #recenterDiv {
    top: 20%;
}
</style>
<style scoped>
.input-container {
    align-items: flex-start;
}
p {
    padding: 15px 0px;
    font-weight: 700;
}
#nom {
    width: 100%;
}
.addPhotoPoi {
    height: 50px;
    width: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--border-radius-medium);
    border: 1px solid var(--color-text-secondary);
    overflow: hidden;
}
.addPhotoPoi span{
    color: var(--color-text-secondary);
}
textarea {
    box-shadow: var(--box-shadow-light);
}
.btnSuivant {
    margin-top: 15px;
    cursor: pointer;
}
.btnSuivant input {
    padding: 20px;
    margin-bottom: 0 !important;
    cursor: pointer;
}
@media only screen and (min-width: 900px) {
    .container {
        margin: 0;
        margin-top:5% ;
        padding: 0 18%;
    }
    .container form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: fit-content fit-content fit-content;
        gap: 40px;
        position: relative;
        min-height: 65vh;

    }
    .input-container:nth-of-type(1){
        grid-column: 1/2;
        grid-row: 1/2;
    }
    .input-container:nth-of-type(2){
        grid-column: 1/2;
        grid-row:2/3;
    }
    .input-container:nth-of-type(3){
        grid-column: 2/3;
        grid-row: 1/3;
    }
    .input-container:nth-of-type(4){
        grid-column: 1/2;
        grid-row: 3/4;
    }
    .input-container:nth-of-type(5){
        grid-column: 2/3;
        grid-row: 3/4;
    }
    .btnSuivant {
        position: absolute;
        width:30%;
        right: 0;
        bottom: -15%;
    }
    }
.editIcon{
    color: white;
}
</style>
