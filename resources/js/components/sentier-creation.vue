<template>
    <form @submit.prevent="submitData">
        <div>
            <label> Nom sentier <input v-model="nomSentier" type="text" id="nomSentier" /> </label>
            <label> Description sentier <input v-model="descriptionSentier" type="text" id="descriptionSentier" /> </label>
            <p>Sélection d'un thème</p>
            <div>
                <!-- Thème -->
                <label v-for="theme in themes" :key="theme.id">
                    <input v-model="selectedTheme" type="radio" name="theme" :value="theme.id" />
                    <div v-html="theme.icone"></div>
                    <p>{{ theme.name }}</p>
                </label>
            </div>
            <p>Critères</p>
            <div>
                <!-- Critères -->
                <label v-for="critere in criteres" :key="critere.id">
                    <input v-model="selectedCriteres" type="checkbox" name="critere[]" :value="critere.id" />
                    {{ critere.name }}
                </label>
            </div>
            <p>Mot clés</p>
            <div>
                <!-- Mot clés -->
                <label v-for="motcle in motcles" :key="motcle.id">
                    <input v-model="selectedMotCles" type="checkbox" name="motcle[]" :value="motcle.id" />
                    {{ motcle.name }}
                </label>
            </div>
            <p>Niveau de difficulté</p>
            <div>
                <label>
                    <input v-model="difficulte" type="radio" name="difficulte" value="facile" />
                    <span class="material-symbols-outlined">bolt</span>
                    <p>Facile</p>
                </label>
                <label>
                    <input v-model="difficulte" type="radio" name="difficulte" value="moyen" />
                    <span class="material-symbols-outlined">bolt</span>
                    <span class="material-symbols-outlined">bolt</span>
                    <p>Moyen</p>
                </label>
                <label>
                    <input v-model="difficulte" type="radio" name="difficulte" value="difficile" />
                    <span class="material-symbols-outlined">bolt</span>
                    <span class="material-symbols-outlined">bolt</span>
                    <span class="material-symbols-outlined">bolt</span>
                    <p>Difficile</p>
                </label>
            </div>
            <p>Ajout une photo</p>
            <input type="file" @change="handleFileUpload" id="PhotoSentier" accept="image/png, image/jpeg, image/jpg" />
            <a href="">Suivant</a>
        </div>
        <div>
            <!-- Tristan -->
        </div>
    </form>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Define reactive state
const nomSentier = ref('');
const descriptionSentier = ref('');
const selectedTheme = ref(null);
const selectedCriteres = ref([]);
const selectedMotCles = ref([]);
const difficulte = ref('');
const photoSentier = ref(null);
const themes = ref([]);
const criteres = ref([]);
const motcles = ref([]);
const pois = ref([]);

// Function to handle file upload
const handleFileUpload = (event) => {
    photoSentier.value = event.target.files[0];
};

// Function to fetch theme data
const fetchThemes = async () => {
    try {
        const response = await axios.get('/data-theme');
        themes.value = response.data;
    } catch (error) {
        console.error('Error fetching themes:', error);
    }
};

// Function to fetch critere data
const fetchCriteres = async () => {
    try {
        const response = await axios.get('/data-critere');
        criteres.value = response.data;
    } catch (error) {
        console.error('Error fetching criteres:', error);
    }
};

// Function to fetch mot cle data
const fetchMotCles = async () => {
    try {
        const response = await axios.get('/data-motcles');
        motcles.value = response.data;
    } catch (error) {
        console.error('Error fetching motcles:', error);
    }
};

// Function to fetch poi data
const fetchPois = async () => {
    try {
        const response = await axios.get('/data-poi');
        pois.value = response.data;
    } catch (error) {
        console.error('Error fetching pois:', error);
    }
};

// Function to handle form submission
const submitData = async () => {
    const formData = new FormData();
    formData.append('nomSentier', nomSentier.value);
    formData.append('descriptionSentier', descriptionSentier.value);
    formData.append('theme', selectedTheme.value);
    selectedCriteres.value.forEach(critere => formData.append('criteres[]', critere));
    selectedMotCles.value.forEach(motcle => formData.append('motcles[]', motcle));
    formData.append('difficulte', difficulte.value);
    formData.append('photoSentier', photoSentier.value);

    try {
        const response = await axios.post('/submit-sentier', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        console.log(response.data);
        // Handle successful response
    } catch (error) {
        console.error('Error submitting data:', error);
    }
};

// Fetch data when the component is mounted
onMounted(() => {
    fetchThemes();
    fetchCriteres();
    fetchMotCles();
    fetchPois();
});
</script>

<style lang=""></style>
