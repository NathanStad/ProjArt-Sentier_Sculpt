<template>
    <span :class="{'material-symbols-outlined':true, full: isFavorite === true}" @click.stop="toggleFavorite">
        favorite
    </span>
</template>

<script setup>
import { ref, defineProps } from "vue";
const isFavorite = ref(false);
const favoris = ref([]);

const props = defineProps({
    sentierId: {
        type: String,
        required: true,
    },
});

let tableau = localStorage.getItem("favoris");
if (tableau) {
    favoris.value = JSON.parse(tableau);
} else {
    favoris.value = [];
}

isFavorite.value = favoris.value.includes(props.sentierId);

const toggleFavorite = () => {
    isFavorite.value = !isFavorite.value;
    if (isFavorite.value) {
        favoris.value.push(props.sentierId);
    } else {
        const index = favoris.value.indexOf(props.sentierId);
        if (index > -1) {
            favoris.value.splice(index, 1);
        }
    }
    localStorage.setItem("favoris", JSON.stringify(favoris.value));
};
</script>

<style scoped>
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
