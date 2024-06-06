<template>
  <div id="filter">
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
          value="difficile"
        />
        <div>
          <span class="material-symbols-outlined">bolt</span>
          <span class="material-symbols-outlined">bolt</span>
          <span class="material-symbols-outlined">bolt</span>
        </div>
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

#difficulte{
  display: flex;
  justify-content: space-around;
}

#difficulte label{
  display: flex;
  flex-direction: column;
}

#difficulte label > p{
  padding-top: 5px;
}
#difficulte label > p,
#difficulte label  span
{
  color: var(--color-text-secondary);
}
#difficulte input:checked + span,
#difficulte input:checked + span + p,
#difficulte input:checked + div + p,
#difficulte input:checked + div >  span
{
  color: var(--primary);
}
</style>
