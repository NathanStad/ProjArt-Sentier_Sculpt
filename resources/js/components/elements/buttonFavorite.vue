<template>
    <span
        :class="[
            'material-icons',
            'favorite',
            { 'favorite-filled': isFavorite },
        ]"
         @click.stop="toggleFavorite"
    >
        {{ isFavorite ? "favorite" : "favorite_border" }}
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
    console.log('test des favoris');
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

<style>
.favorite {
    cursor: pointer;
}

.favorite-filled {
    color: red;
}
</style>
