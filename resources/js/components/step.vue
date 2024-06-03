<template>

    <!-- Carrousel -->
    <div>
        <!-- Les images -->
        <div>
            <!-- Image de l'étape -->
            <img :src="etape.photo" :alt="etape.description">
            <!-- Images des points d'interet -->
            <img :src="pois.photo" :alt="pois.nom" v-for="pois in etape.points_interet" :key="pois.id">
        </div>
        <!-- Les petits points de changeemnt -->
        <div>
            <span></span>
        </div>
    </div>

    <!-- Description -->
    <div>
        <h3>Description</h3>
        <p>{{ etape.description }}</p>
    </div>
    <div>
        <h3>Points d'intérêts</h3>
        <div v-for="pois in etape.points_interet" :key="pois.id">
            <img :src="pois.photo" :alt="pois.nom">
            <p>{{ pois.nom }}</p>
        </div>
    </div>
    <div>
        <h3>Retour à la carte</h3>
        <a :href="`#steps-${etape.sentier_id}`">a<img src="" alt=""></a>
    </div>
</template>
<script setup>
import axios from 'axios';
import { defineProps, onMounted, ref } from 'vue';

// Définition des props
const props = defineProps({
  Id: {
    type: Object,
    required: true,
  },
});

// Data
const etape = ref([]);

// Methods
const fetchStep = async () => {
  if (props.Id !== '') {
    try {
      const response = await axios.get(`/data-step-${props.Id}`);
      etape.value = response.data;
      console.log(response.data);
    } catch (error) {
      console.error('Error fetching sentiers:', error);
    }
  }
};

// Lifecycle Hook
onMounted(() => {
  fetchStep();
});
</script>

<style lang="">
    
</style>