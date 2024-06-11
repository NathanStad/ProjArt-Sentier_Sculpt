<template>
    <form @submit.prevent="submitData">
        <div id="sentier-creation">
            <div class="header">
                <a href="#account">
                    <span class="material-symbols-outlined"
                        >arrow_back_ios</span
                    >
                </a>
                <h1>Création d'un sentier</h1>
                <div></div>
            </div>
            <label class="block">
                Nom sentier
                <input
                    v-model="nomSentier"
                    type="text"
                    id="nomSentier"
                    required
                    placeholder="Nom du sentier"
                />
                <br /><span v-if="errors.nomSentier" class="error">{{
                    errors.nomSentier
                }}</span>
            </label>
            <label class="block">
                Description du sentier
                <textarea
                    v-model="descriptionSentier"
                    id="descriptionSentier"
                    cols="30"
                    rows="10"
                    required
                    placeholder="Description du sentier"
                ></textarea>
                <br /><span v-if="errors.descriptionSentier" class="error">{{
                    errors.descriptionSentier
                }}</span>
            </label>
            <br />
            <label class="block">
                Nom de la localisation <br />
                <input
                    v-model="lieu"
                    type="text"
                    id="nomLieu"
                    required
                    placeholder="Nom de la localisation"
                />
                <span v-if="errors.lieu" class="error">{{ errors.lieu }}</span>
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
                <br /><span
                    v-if="errors.selectedTheme"
                    class="error"
                    style="grid-column: 1/4"
                    >{{ errors.selectedTheme }}</span
                >
            </div>

            <p>Critères</p>
            <div>
                <label
                    v-for="critere in criteres"
                    :key="critere.id"
                    :class="{
                        checkbox: true,
                        active: selectedCriteres.includes(critere.id),
                    }"
                >
                    <input
                        v-model="selectedCriteres"
                        type="checkbox"
                        name="critere[]"
                        :value="critere.id"
                    />
                    {{ critere.name }}
                </label>
                <br /><span v-if="errors.selectedCriteres" class="error">{{
                    errors.selectedCriteres
                }}</span>
            </div>

            <p>Mot-clés</p>
            <div>
                <label
                    v-for="motcle in motcles"
                    :key="motcle.id"
                    :class="{
                        checkbox: true,
                        active: selectedMotCles.includes(motcle.id),
                    }"
                >
                    <input
                        v-model="selectedMotCles"
                        type="checkbox"
                        name="motcle[]"
                        :value="motcle.id"
                    />
                    {{ motcle.name }}
                </label>
                <br /><span v-if="errors.selectedMotCles" class="error">{{
                    errors.selectedMotCles
                }}</span>
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
                    <p>Difficile</p> </label
                ><span
                    v-if="errors.difficulte"
                    class="error"
                    style="grid-column: 1/4"
                    >{{ errors.difficulte }}</span
                >
            </div>

            <p>Ajouter une photo</p>
            <div class="input-container">
                <label v-if="photoSentier" for="photoSentier" class="addPhoto">
                    <img :src="photoSentier" alt="Photo du sentier" />
                    <span class="material-symbols-outlined editIcon">edit</span>
                </label>
                <label v-else for="photoSentier" class="addPhoto">
                    <span class="material-symbols-outlined">add_a_photo</span>
                </label>
                <input
                    type="file"
                    id="photoSentier"
                    name="photoSentier"
                    @change="handleFileUpload"
                    style="display: none"
                    accept="image/png, image/jpeg, image/jpg"
                /><span
                    v-if="errors.photoSentier"
                    class="error"
                    style="width: 100%"
                    >{{ errors.photoSentier }}</span
                >
            </div>

            <button type="submit" :disabled="!isValidForm()" class="button">
                Suivant
            </button>
        </div>
    </form>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";

// Define reactive state
const idSentier = ref("");
const nomSentier = ref("");
const descriptionSentier = ref("");
const lieu = ref("");
const selectedTheme = ref(null);
const selectedCriteres = ref([]);
const selectedMotCles = ref([]);
const difficulte = ref("");
const photoSentier = ref(null);

// State for error messages
const errors = ref({
    nomSentier: "",
    descriptionSentier: "",
    lieu: "",
    selectedTheme: "",
    selectedCriteres: "",
    selectedMotCles: "",
    difficulte: "",
    photoSentier: "",
});

// Si ça vient pour une mise à jour
if (sessionStorage.getItem("sentierCreation")) {
    const data = JSON.parse(sessionStorage.getItem("sentierCreation"));
    idSentier.value = data.idSentier;
    nomSentier.value = data.nomSentier;
    descriptionSentier.value = data.descriptionSentier;
    lieu.value = data.lieu;
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
    formData.append("photoSentier", file);

    try {
        const response = await axios.post("/submit-file-sentier", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        photoSentier.value = `/storage/${response.data.path}`;
        errors.value.photoSentier = ""; // Clear error message on successful upload
    } catch (error) {
        console.error("Error uploading file:", error);
        errors.value.photoSentier =
            "Erreur lors du téléchargement de la photo.";
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
    // Validate form before submitting
    validateForm();

    if (isValidForm()) {
        try {
            const formDataToSave = {
                idSentier: idSentier.value,
                nomSentier: nomSentier.value,
                descriptionSentier: descriptionSentier.value,
                lieu: lieu.value,
                theme: selectedTheme.value,
                criteres: selectedCriteres.value,
                motcles: selectedMotCles.value,
                difficulte: difficulte.value,
                photoSentier: photoSentier.value,
            };

            sessionStorage.setItem(
                "sentierCreation",
                JSON.stringify(formDataToSave)
            );
            console.log("Form data saved to sessionStorage:", formDataToSave);

            // Redirect user
            window.location.hash = `creationSteps`;
        } catch (error) {
            console.error("Error submitting form:", error);
        }
    }
};

// Validate the form and set error messages
const validateForm = () => {
    errors.value.nomSentier = nomSentier.value
        ? ""
        : "Le nom du sentier est requis.";
    errors.value.descriptionSentier = descriptionSentier.value
        ? ""
        : "La description du sentier est requise.";
    errors.value.lieu = lieu.value
        ? ""
        : "Le nom de la localisation est requis.";
    errors.value.selectedTheme = selectedTheme.value
        ? ""
        : "Veuillez sélectionner un thème.";
    errors.value.selectedCriteres =
        selectedCriteres.value.length > 0
            ? ""
            : "Veuillez sélectionner au moins un critère.";
    errors.value.selectedMotCles =
        selectedMotCles.value.length > 0
            ? ""
            : "Veuillez sélectionner au moins un mot-clé.";
    errors.value.difficulte = difficulte.value
        ? ""
        : "Veuillez sélectionner un niveau de difficulté.";
    errors.value.photoSentier = photoSentier.value
        ? ""
        : "Veuillez ajouter une photo.";
};

// Fonction pour vérifier si le formulaire est valide
const isValidForm = () => {
    return (
        !errors.value.nomSentier &&
        !errors.value.descriptionSentier &&
        !errors.value.lieu &&
        !errors.value.selectedTheme &&
        !errors.value.selectedCriteres &&
        !errors.value.selectedMotCles &&
        !errors.value.difficulte &&
        !errors.value.photoSentier
    );
};

// Mettre à jour l'état du bouton de soumission en fonction de la validité du formulaire
watch(
    [
        nomSentier,
        descriptionSentier,
        lieu,
        selectedTheme,
        selectedCriteres,
        selectedMotCles,
        difficulte,
        photoSentier,
    ],
    () => {
        validateForm();
    }
);

// Fetch data when the component is mounted
onMounted(() => {
    fetchThemes();
    fetchCriteres();
    fetchMotCles();
});
</script>

<style>
/* styles remain unchanged */
#sentier-creation input[type="text"],
#sentier-creation textarea {
    border-radius: var(--border-radius-small);
    width: 100%;
}
input[type="checkbox"],
input[type="radio"] {
    display: none;
}
#sentier-creation .block {
    display: block;
}
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
label.checkbox.active {
    background-color: var(--secondary);
    color: white;
    border: 2px solid var(--secondary);
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
    margin-top: var(--margin-small);
}
#sentier-creation > p {
    margin-top: var(--margin-large);
    margin-bottom: var(--margin-medium);
}
#sentier-creation > p,
#sentier-creation > .block {
    font-weight: 700;
}
#sentier-creation > label {
    font-size: 1.2rem;
}
#sentier-creation > label input,
#sentier-creation > label textarea {
    margin-top: var(--margin-small);
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
    color: var(--secondary);
    font-weight: 700;
}
input[type="radio"] + div > span {
    font-size: 1.2rem;
}
.input-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-bottom: 10px !important;
}
.input-container label {
    cursor: pointer;
    overflow: hidden;
}
.input-container img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    overflow: hidden;
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
.addPhoto {
    height: 150px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--border-radius-medium);
    border: 2px solid var(--color-text-secondary);
}
.addPhoto span {
    color: var(--color-text-secondary);
}
.error {
    color: red;
    font-size: 0.9rem;
    margin-top: 0.3rem;
}
</style>
<style scoped>

    @media only screen and (min-width: 900px) {
        #sentier-creation > * {
            margin: 1% 18%;
        }
        .header {
            margin: 3% 0 !important;
            margin-bottom: 5% !important;
        }
        .input-container {
            width: 64% !important;
        }
        .button {
            left: 32.5% !important;
            width: 30% !important;
        }
    }
</style>
