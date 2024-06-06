<template>
    <form @submit.prevent="submitData">
        <div id="sentier-creation">
            <div class="header">
                <a @click.prevent="goBack">
                    <span class="material-symbols-outlined">arrow_back_ios</span>
                </a>
                <h1>Création de sentier</h1>
                <div></div>
            </div>
            <label>
                Nom sentier
                <input v-model="nomSentier" type="text" id="nomSentier" required />
            </label>
            <label>
                Description sentier
                <textarea
                v-model="descriptionSentier"
                id="descriptionSentier"
                cols="30"
                rows="10"
                required
                ></textarea>
            </label>
            <br>
            <label>
                Nom de la localisation <br>
                <input v-model="lieu" type="text" id="nomSentier" required />
            </label>
            
            <p>Sélection d'un thème</p>
            <div class="three-box">
                <label
                    v-for="theme in themes"
                    :key="theme.id"
                    :class="{ active: selectedTheme === theme.id }"
                >
                    <input
                        v-model="selectedTheme"
                        type="radio"
                        name="theme"
                        :value="theme.id"
                        required
                    />
                    <div v-html="theme.icone"></div>
                    <p>{{ theme.name }}</p>
                </label>
            </div>

            <p>Critères</p>
            <div>
                <label
                    v-for="critere in criteres"
                    :key="critere.id"
                    :class="{ checkbox: true, active: selectedCriteres.includes(critere.id) }"
                >
                    <input
                        v-model="selectedCriteres"
                        type="checkbox"
                        name="critere[]"
                        :value="critere.id"
                    />
                    {{ critere.name }}
                </label>
            </div>

            <p>Mot clés</p>
            <div>
                <label
                    v-for="motcle in motcles"
                    :key="motcle.id"
                    :class="{ checkbox: true, active: selectedMotCles.includes(motcle.id) }"
                >
                    <input
                        v-model="selectedMotCles"
                        type="checkbox"
                        name="motcle[]"
                        :value="motcle.id"
                    />
                    {{ motcle.name }}
                </label>
            </div>

            <p>Niveau de difficulté</p>
            <div class="three-box">
                <label :class="{ active: difficulte === '1' }">
                    <input
                        v-model="difficulte"
                        type="radio"
                        name="difficulte"
                        value="1"
                        required
                    />
                    <div>
                        <span class="material-symbols-outlined">bolt</span>
                    </div>
                    <p>Facile</p>
                </label>
                <label :class="{ active: difficulte === '2' }">
                    <input
                        v-model="difficulte"
                        type="radio"
                        name="difficulte"
                        value="2"
                    />
                    <div>
                        <span class="material-symbols-outlined">bolt</span>
                        <span class="material-symbols-outlined">bolt</span>
                    </div>
                    <p>Moyen</p>
                </label>
                <label :class="{ active: difficulte === '3' }">
                    <input
                        v-model="difficulte"
                        type="radio"
                        name="difficulte"
                        value="3"
                    />
                    <div>
                        <span class="material-symbols-outlined">bolt</span>
                        <span class="material-symbols-outlined">bolt</span>
                        <span class="material-symbols-outlined">bolt</span>
                    </div>
                    <p>Difficile</p>
                </label>
            </div>

            <p>Ajouter une photo</p>
            <div class="input-container">
                <label v-if="photoSentier" for="photoSentier">
                    <img :src="photoSentier" alt="Photo du sentier" />
                    <span class="material-symbols-outlined editIcon">edit</span>
                </label>
                <label v-else for="photoSentier">
                    <span class="material-symbols-outlined">add_a_photo</span>
                </label>
                <input
                    type="file"
                    id="photoSentier"
                    @change="handleFileUpload"
                    style="display: none"
                    accept="image/png, image/jpeg, image/jpg"
                    required
                />
            </div>

            <button type="submit" :disabled="!isValidForm()">Suivant</button>
        </div>
    </form>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

// Define reactive state
const nomSentier = ref("");
const descriptionSentier = ref("");
const lieu = ref("");
const selectedTheme = ref(null);
const selectedCriteres = ref([]);
const selectedMotCles = ref([]);
const difficulte = ref("");
const photoSentier = ref(null);

// Si ça vient pour une mise à jour
if (sessionStorage.getItem('update')) {
    const data = JSON.parse(sessionStorage.getItem('sentierCreation'))
    console.log(data);
    nomSentier.value = data.nomSentier;
    descriptionSentier.value = data.descriptionSentier;
    lieu.value = data.location;
    selectedTheme.value = data.theme;
    selectedCriteres.value = data.criteres;
    selectedMotCles.value = data.motcles;
    difficulte.value = data.difficulte;
    photoSentier.value = data.photoSentier;
}
const themes = ref([]);
const criteres = ref([]);
const motcles = ref([]);

// Fonction pour gérer le téléchargement de fichier pour les points d'intérêt
const handleFileUpload = async (e) => {
    const file = e.target.files[0];
    const formData = new FormData();
    formData.append('photoSentier', file);

    try {
        const response = await axios.post('/submit-file-sentier', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        photoSentier.value = `/storage/${response.data.path}`;
    } catch (error) {
        console.error('Error uploading file:', error);
    }
};

// Function to fetch theme data
const fetchThemes = async () => {
    try {
        const response = await axios.get("/data-theme");
        themes.value = response.data;
    } catch (error) {
        console.error("Error fetching themes:", error);
    }
};

// Function to fetch critere data
const fetchCriteres = async () => {
    try {
        const response = await axios.get("/data-critere");
        criteres.value = response.data;
    } catch (error) {
        console.error("Error fetching criteres:", error);
    }
};

// Function to fetch mot cle data
const fetchMotCles = async () => {
    try {
        const response = await axios.get("/data-motcles");
        motcles.value = response.data;
    } catch (error) {
        console.error("Error fetching motcles:", error);
    }
};

// Function to handle form submission
const submitData = async () => {    
    // Créez un FormData pour l'upload du fichier
    const formData = new FormData();
    formData.append('nomSentier', nomSentier.value);
    formData.append('descriptionSentier', descriptionSentier.value);
    formData.append('theme', selectedTheme.value);
    selectedCriteres.value.forEach(critere => formData.append('criteres[]', critere));
    selectedMotCles.value.forEach(motcle => formData.append('motcles[]', motcle));
    formData.append('difficulte', difficulte.value);
    formData.append('photoSentier', photoSentier.value);

    try {
        // Mettez à jour le nom du fichier avec le nouveau nom renvoyé par le serveur
        const formDataToSave = {
            nomSentier: nomSentier.value,
            descriptionSentier: descriptionSentier.value,
            theme: selectedTheme.value,
            criteres: selectedCriteres.value,
            motcles: selectedMotCles.value,
            difficulte: difficulte.value,
            photoSentier: photoSentier.value, // Utilisez le nouveau nom de fichier
        };

        sessionStorage.setItem("sentierCreation", JSON.stringify(formDataToSave));
        console.log("Form data saved to sessionStorage:", formDataToSave);

        // Redirigez l'utilisateur
        window.location.hash = `creationSteps`;
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

const isValidForm = () => {
    return (
        nomSentier.value !== "" &&
        descriptionSentier.value !== "" &&
        selectedTheme.value !== null &&
        selectedCriteres.value.length > 0 &&
        selectedMotCles.value.length > 0 &&
        difficulte.value !== "" &&
        photoSentier.value !== null
    );
};

const goBack = () => {
    window.history.back();
};

// Fetch data when the component is mounted
onMounted(() => {
    fetchThemes();
    fetchCriteres();
    fetchMotCles();
});
</script>

<style>
/* Hide checkboxes and radio buttons */
input[type="checkbox"],
input[type="radio"] {
    display: none;
}

/* Style labels as buttons */
label.checkbox {
    display: inline-block;
    padding: 10px 20px;
    margin: 5px;
    border: 2px solid var(--color-text-secondary);
    color: var(--color-text-secondary);
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Highlight selected labels */
label.checkbox.active {
    background-color: var(--primary);
    color: white;
    border: 2px solid var(--primary);
}

#sentier-creation {
    position: absolute;
    z-index: 11;
    background: white;
    top: 2%;
    width: 90%;
    left: 50%;
    transform: translateX(-50%);
}

#sentier-creation > * {
    font-size: 1.2rem;
    margin-top: var(--margin-large);
}

#sentier-creation > label {
    font-size: 1.2rem;
}

#sentier-creation > label input,
#sentier-creation > label textarea {
    margin-top: var(--margin-large);
}

.three-box {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    grid-gap: 30px;
}

input[type="radio"] + div,
input[type="radio"] + div + p {
    text-align: center;
}

input[type="radio"]:checked + div span,
input[type="radio"]:checked + div + p {
    color: var(--primary);
}

input[type="radio"] + div > span {
    font-size: 1.2rem;
}

.input-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.input-container label {
    cursor: pointer;
}

.input-container img {
    max-width: 100%;
    height: auto;
    display: block;
    margin-bottom: 10px;
}

.input-container .editIcon {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    padding: 5px;
    color: white;
}
</style>
