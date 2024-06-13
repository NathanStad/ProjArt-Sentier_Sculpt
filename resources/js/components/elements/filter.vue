<template>
  <div id="filter">
    <div class="header">
      <span @click="callComeBack" class="material-symbols-outlined">arrow_back_ios</span>
      <h1>Filtre</h1>
      <div @click="resetFilters">Réinitialiser</div>
    </div>
    <!-- Critères -->
    <p>Critères</p>
    <div>
      <label v-for="critere in criteres" :key="critere.id" :class="{ checkbox: true, active: selectedCriteres.includes(critere.id) }">
        <input
          v-model="selectedCriteres"
          type="checkbox"
          name="critere[]"
          :value="critere.id"
        />
        {{ critere.name }}
      </label>
    </div>

    <!-- Mot clés -->
    <p>Mot clés</p>
    <div>
      <label v-for="motcle in motcles" :key="motcle.id" :class="{ checkbox: true, active: selectedMotCles.includes(motcle.id) }">
        <input
          v-model="selectedMotCles"
          type="checkbox"
          name="motcle[]"
          :value="motcle.id"
        />
        {{ motcle.name }}
      </label>
    </div>

    <!-- Difficulté -->
    <p>Difficulté</p>
    <div id="difficulte">
      <label>
        <input
          v-model="difficulte"
          type="checkbox"
          name="difficulte[]"
          value="1"
        />
        <span class="material-symbols-outlined">bolt</span>
        <p>Facile</p>
      </label>
      <label>
        <input
          v-model="difficulte"
          type="checkbox"
          name="difficulte[]"
          value="2"
        />
        <div>
          <span class="material-symbols-outlined">bolt</span>
          <span class="material-symbols-outlined">bolt</span>
        </div>
        <p>Moyen</p>
      </label>
      <label>
        <input
          v-model="difficulte"
          type="checkbox"
          name="difficulte[]"
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
    <div id="buttons">
      <a @click="callParentMethod" class="button">Enregistrer</a>
      <a @click="callComeBack" class="annuler">Annuler</a>
    </div>
  </div>
</template>

<script setup>
  // Importation des fonctions nécessaires de Vue et d'Axios
  import { ref, onMounted, watch, defineEmits, defineProps } from 'vue';
  import axios from 'axios';

  // Définition des propriétés reçues par le composant
  const props = defineProps({
    closeFilter: {
      type: Function,
      required: true
    }
  });

  // Méthode pour fermer le filtre en appelant une méthode parente
  function callParentMethod() {
    props.closeFilter();
  }

  // Méthode pour réinitialiser les filtres et fermer le filtre
  function callComeBack() {
    selectedCriteres.value = [];
    selectedMotCles.value = [];
    difficulte.value = [];
    props.closeFilter();
  }

  // Références pour stocker les données des critères, mots-clés sélectionnés et la difficulté
  const criteres = ref([]);
  const motcles = ref([]);
  const selectedCriteres = ref([]);
  const selectedMotCles = ref([]);
  const difficulte = ref([]);

  // Émission d'événements personnalisés
  const emit = defineEmits();

  // Récupération des critères et mots-clés depuis l'API
  const fetchCriteres = async () => {
    try {
      const response = await axios.get('/data-critere');
      criteres.value = response.data;
    } catch (error) {
      console.error('Erreur lors de la récupération des critères:', error);
    }
  };

  const fetchMotCles = async () => {
    try {
      const response = await axios.get('/data-motcles');
      motcles.value = response.data;
    } catch (error) {
      console.error('Erreur lors de la récupération des mots-clés:', error);
    }
  };

  // Initialisation des appels API au montage du composant
  onMounted(() => {
    fetchCriteres();
    fetchMotCles();
  });

  // Surveillance des changements dans les références pour émettre un événement personnalisé
  watch([selectedCriteres, selectedMotCles, difficulte], () => {
    emit('updateFilters', {
      selectedCriteres: selectedCriteres.value,
      selectedMotCles: selectedMotCles.value,
      difficulte: difficulte.value
    });
  });

  // Fonction pour réinitialiser tous les inputs à zéro
  function resetFilters() {
    selectedCriteres.value = [];
    selectedMotCles.value = [];
    difficulte.value = [];
  }
</script>

<style scoped>
#filter {
  display: absolute;
  flex-direction: column;
  justify-content: space-between;
  padding: 0% 10%;
  height: 100%;
}
#filter .header{
  position: sticky;
  top: 0;
  background: white;
  padding-bottom: 5%;
  padding-top: 5%;
}
#filter .header div:last-of-type{
  color: red;
  font-weight: 500;
  margin-top: 5px ;
}
#filter > p {
  font-weight: bold;
  padding-top: 7%;
  padding-bottom: 5%;
}
label {
  display: flex;
  align-items: center;
}
.material-symbols-outlined {
  margin-right: 5px;
}

#difficulte {
  display: flex;
  justify-content: space-between;
}

#difficulte label {
  display: flex;
  flex-direction: column;
}

#difficulte label > p {
  padding-top: 5px;
}
#difficulte label > p,
#difficulte label span {
  color: var(--color-text-secondary);
}
#difficulte input:checked + span,
#difficulte input:checked + span + p,
#difficulte input:checked + div + p,
#difficulte input:checked + div > span {
  color: var(--secondary);
}
#buttons{
  height: 15vh;
  padding-top: 10%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.button, .annuler{
  position: relative;
  top: 0;
  left: 0;
  transform: none;
  text-align: center;
  padding: 4%;
  margin: 0;
}
@media only screen and (min-width: 900px) {
  #filter {
    padding: 0% 20%;

  }
  #buttons{
  flex-direction: row-reverse;
  justify-content: start;
  gap: 20px;
}
  #buttons .button{
  width: 20%;
}
  #buttons{
  margin-bottom: 20px;
}
.button, .annuler{

  padding: 2%;
}
}
</style>
