<template>
  <div id="filter">
    <!-- Critères -->
    <p>Critères</p>
    <div>
      <label v-for="critere in criteres" :key="critere.id">
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
      <label v-for="motcle in motcles" :key="motcle.id">
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
    <div>
      <label>
        <input
          v-model="difficulte"
          type="checkbox"
          name="difficulte[]"
          value="facile"
        />
        <span class="material-symbols-outlined">bolt</span>
        <p>Facile</p>
      </label>
      <label>
        <input
          v-model="difficulte"
          type="checkbox"
          name="difficulte[]"
          value="moyen"
        />
        <span class="material-symbols-outlined">bolt</span>
        <span class="material-symbols-outlined">bolt</span>
        <p>Moyen</p>
      </label>
      <label>
        <input
          v-model="difficulte"
          type="checkbox"
          name="difficulte[]"
          value="difficile"
        />
        <span class="material-symbols-outlined">bolt</span>
        <span class="material-symbols-outlined">bolt</span>
        <span class="material-symbols-outlined">bolt</span>
        <p>Difficile</p>
      </label>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, defineEmits } from 'vue';
import axios from 'axios';

const criteres = ref([]);
const motcles = ref([]);
const selectedCriteres = ref([]);
const selectedMotCles = ref([]);
const difficulte = ref([]);

const emit = defineEmits();

const fetchCriteres = async () => {
  try {
    const response = await axios.get('/data-critere');
    criteres.value = response.data;
  } catch (error) {
    console.error('Error fetching criteres:', error);
  }
};

const fetchMotCles = async () => {
  try {
    const response = await axios.get('/data-motcles');
    motcles.value = response.data;
  } catch (error) {
    console.error('Error fetching motcles:', error);
  }
};

onMounted(() => {
  fetchCriteres();
  fetchMotCles();
});

watch([selectedCriteres, selectedMotCles, difficulte], () => {
  emit('updateFilters', {
    selectedCriteres: selectedCriteres.value,
    selectedMotCles: selectedMotCles.value,
    difficulte: difficulte.value
  });
});
</script>

<style scoped>
#filter{
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 0% 10%;
  height: 100%;
}
#filter > p {
  font-weight: bold;
  padding-top: 15%;
}
label {
  display: flex;
  align-items: center;
}
.material-symbols-outlined {
  margin-right: 5px;
}
</style>
