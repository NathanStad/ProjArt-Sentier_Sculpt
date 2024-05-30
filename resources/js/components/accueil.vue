<template>
    <!-- Thèmes -->
    <div>
        <div class="theme">
            <div>
                <span class="material-symbols-outlined"> footprint </span>
            </div>
            <p>Tous</p>
        </div>
        <div v-for="theme in themes" :key="theme.id" class="theme">
          <div v-html="theme.icone"></div>
            <p>{{ theme.name }}</p>
        </div>
    </div>
    <!-- Sentiers  -->
    <div>
        <a
            v-for="sentier in sentiers"
            :key="sentier.id"
            :href="`#sentier-${sentier.id}`"
        >
            <div :style="{ 'background-image': `url(${sentier.photo})` }"></div>
            <div>
                <p>{{ sentier.nom }}</p>
                <p>{{ sentier.description }}</p>
            </div>
        </a>
    </div>
    <!-- Destinations préférés -->
    <h3>Destinations les plus vues</h3>
    <div>
        <a
            v-for="sentier in sentiersPref"
            :key="sentier.id"
            :href="`#sentier-${sentier.id}`"
        >
            <img :src="sentier.photo" :alt="sentier.nom" />
            <div>
                <p>{{ sentier.nom }}</p>
                <p>{{ sentier.description }}</p>
            </div>
        </a>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            themes: [],
            sentiers: [],
            sentiersPref: [],
        };
    },
    created() {
        this.fetchTheme();
        this.fetchSentiers();
        this.fetchSentiersPref();
    },
    methods: {
        async fetchTheme() {
            try {
                const response = await axios.get("/data-theme");
                this.themes = response.data;
            } catch (error) {
                console.error("Error fetching sentiers:", error);
            }
        },
        async fetchSentiers() {
            try {
                const response = await axios.get("/data-sentiers");
                this.sentiers = response.data;
            } catch (error) {
                console.error("Error fetching sentiers:", error);
            }
        },
        async fetchSentiersPref() {
            try {
                const response = await axios.get("/data-sentiers/prefere");
                this.sentiersPref = response.data;
                console.log(this.sentiersPref);
            } catch (error) {
                console.error("Error fetching sentiers:", error);
            }
        },
    },
};
</script>

<style></style>
