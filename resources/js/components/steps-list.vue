<template>
            <!-- Bouton retour -->
            <a :href="`#sentier-${Id}`">a</a>
        <!-- Maps -->
        <div id="app"></div>
        <div>            
            <!-- Flèche avant -->
            <span></span>
            <!-- Flèche après -->
            <span></span>
        </div>
        <div>
            <!-- Les étapes -->
            <a v-for="etape in sentier.etapes" :key="etape.id" :href="`#step-${etape.id}`">
                <img :src="etape.photo" :alt="etape.nom">
                <div>
                    <p>{{ etape.nom }}</p>
                    <div>
                        <p>Durée</p>
                        <p>{{ etape.duree }}</p>
                    </div>
                    <div>
                        <p>Distance</p>
                        <p>{{ etape.distance }}km</p>
                    </div>
                </div>
            </a>
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
const sentier = ref([]);
const etapes = ref([]);

// Methods
const fetchSentier = async () => {
  if (props.Id !== '') {
    try {
      const response = await axios.get(`/data-sentier-${props.Id}`);
      sentier.value = response.data;
      etapes.value = response.data.etapes;
    } catch (error) {
      console.error('Error fetching sentiers:', error);
    }
  }
};

// Lifecycle Hook
onMounted(() => {
  fetchSentier();
});
</script>

<style lang="">
    
</style>