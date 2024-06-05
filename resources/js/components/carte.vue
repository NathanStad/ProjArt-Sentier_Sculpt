<template>
  <div id="app">
    <carte />
    <p v-for="sentier in sentiers" :key="sentier.id">{{ sentier }}</p>

  </div>
</template>

<script setup>
import carte from './elements/carte.vue'
</script>

<script>
import axios from 'axios';
import { ref } from 'vue';

// import des données
// Récupéré les données avec le tableau JSON "sentiers"
export default {
  data() {
    return {
      sentiers: []
    };
  },
  created() {
    this.fetchSentiers();
  },
  methods: {
    async fetchSentiers() {
      try {
        const response = await axios.get('/data-sentier');
        this.sentiers = response.data;
      } catch (error) {
        console.error('Error fetching sentiers:', error);
      }
    }
  }

};
</script>

<style scoped></style>