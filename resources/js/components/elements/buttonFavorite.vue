<template>
    <span :class="{ 'material-symbols-outlined': true, full: isFavorite === true }"
        @click.stop.prevent="toggleFavorite">
        favorite
    </span>
</template>

<script setup>
import { ref, defineProps, onMounted, onUnmounted, watch } from "vue";

const isFavorite = ref(false);

const props = defineProps({
    sentierId: {
        type: Number,
        required: true,
    },
});

onMounted(() => {
    // Récupérer l'état du sentier favori lors de la première montée du composant
    const tableau = sessionStorage.getItem("favoris");
    if (tableau) {
        const favoris = JSON.parse(tableau);
        isFavorite.value = favoris.includes(props.sentierId);
    }

    // Écouter l'événement personnalisé pour mettre à jour l'état de favori
    window.addEventListener("favorisUpdated", handleFavorisUpdated);
});

onUnmounted(() => {
    // Nettoyer l'écouteur d'événement lors du démontage du composant
    window.removeEventListener("favorisUpdated", handleFavorisUpdated);
});

// Lors du click il est ajouté dans les favoris
const toggleFavorite = () => {
    isFavorite.value = !isFavorite.value;
    const favoris = JSON.parse(sessionStorage.getItem("favoris") || "[]");
    const index = favoris.indexOf(props.sentierId);
    if (isFavorite.value && index === -1) {
        favoris.push(props.sentierId);
    } else if (!isFavorite.value && index !== -1) {
        favoris.splice(index, 1);
    }
    sessionStorage.setItem("favoris", JSON.stringify(favoris));
    // Émettre l'événement personnalisé pour informer les autres composants de la mise à jour
    window.dispatchEvent(new Event("favorisUpdated"));
};

const handleFavorisUpdated = () => {
    // Mettre à jour l'état de favori lorsque l'événement est déclenché
    const favoris = JSON.parse(sessionStorage.getItem("favoris") || "[]");
    isFavorite.value = favoris.includes(props.sentierId);
};
</script>
<style scoped>
span {
    font-size: 2rem;
}

.favorite {
    cursor: pointer;
}

.favorite-filled {
    color: red;
}

.material-symbols-outlined.full {
    font-variation-settings:
        'FILL' 1,
        'wght' 400,
        'GRAD' 0,
        'opsz' 24
}
</style>
