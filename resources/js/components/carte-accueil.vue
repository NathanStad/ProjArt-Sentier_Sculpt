<template>
    <div class="header">
        <input
            type="text"
            v-model="searchQuery"
            placeholder="Rechercher par lieu et nom"
            class="recherche"
        />
        <span class="material-symbols-outlined search-icone"> search </span>
        <!-- Bouton Filtre -->
        <div @click="toggleFiltre()" id="buttonFiltre">
            <span class="material-symbols-outlined"> tune </span>
        </div>
    </div>

    <!-- Conteneur du filtre -->
    <div id="filtre" :class="{ visible: filtreVisible === true }">
        <Filtre
            @updateFilters="updateFilters"
            :closeFilter="toggleFiltre"
        ></Filtre>
    </div>
    <div id="carte-accueil">
        <div id="mapCarteAccueil"></div>
        <div id="recenterDiv">
            <RecentrerBtnComponent @recenter="recenter" />
        </div>
        <!-- <div v-if="sentiers.length > 0">
            <p v-for="sentier in sentiers" :key="sentier.id">{{ sentier }}</p>
        </div>
        <div v-else>
            <p>No sentiers available</p>
        </div> -->
    </div>
    <footer><Footer></Footer></footer>
</template>

<script setup>
import { ref, onMounted, watchEffect, computed } from "vue";
import axios from "axios";
import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";
import RecentrerBtnComponent from "./elements/recentrerBtnComponent.vue";
import Footer from "@/components/elements/footer.vue";
import Filtre from "@/components/elements/filter.vue";


let map;
const sentiers = ref([]);
const coordonnesRecenter = ref([6.700021, 46.602693]);
const searchQuery = ref(""); // Variable pour la barre de recherche
const selectedFilters = ref({
    selectedCriteres: [],
    selectedMotCles: [],
    difficulte: [],
    theme: null,
});
const filtreVisible = ref(false); // Variable pour afficher ou masquer les filtres

const fetchSentiers = async () => {
    try {
        const response = await axios.get("/data-sentiers");
        sentiers.value = response.data.filter((item) => item.archive === 0);
    } catch (error) {
        console.error("Error fetching sentiers:", error);
    }
};
// Méthode pour basculer l'affichage des filtres
const toggleFiltre = () => {
    filtreVisible.value = !filtreVisible.value;
};
// Filtres les sentiers en fonction des critères de recherche et des filtres sélectionnés
const filteredSentiers = computed(() => {
    return sentiers.value.filter((sentier) => {
        let matches = false;

        // Filter by search query
        if (searchQuery.value.trim() !== "") {
            matches = matches || (
                sentier.nom.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                sentier.localisation.toLowerCase().includes(searchQuery.value.toLowerCase())
            );
        }

        // Filter by selected critere
        if (selectedFilters.value.selectedCriteres.length > 0) {
            const critereIds = sentier.criteres.map(critere => critere.id);
            const matchesSelectedCriteres = selectedFilters.value.selectedCriteres.every(critere => critereIds.includes(critere));
            matches = matches || matchesSelectedCriteres;
        }

        // Filter by selected mot cle
        if (selectedFilters.value.selectedMotCles.length > 0) {
            const motCleIds = sentier.motcles.map(motcle => motcle.id);
            const matchesSelectedMotCles = selectedFilters.value.selectedMotCles.every(motcle => motCleIds.includes(motcle));
            matches = matches || matchesSelectedMotCles;
        }

        // Filter by difficulty
        console.log(selectedFilters.value);
        console.log(sentier.difficulte);
        if (selectedFilters.value.difficulte.length > 0) {
            const matchesDifficulty = selectedFilters.value.difficulte.includes(`${sentier.difficulte.graduation}`);
            matches = matches || matchesDifficulty;
        }

        // Filter by theme
        if (selectedFilters.value.theme !== null) {
            const matchesTheme = sentier.theme_id === selectedFilters.value.theme;
            matches = matches || matchesTheme;
        }

        // If no filters are selected, we want to match all sentiers
        if (
            searchQuery.value.trim() === "" &&
            selectedFilters.value.selectedCriteres.length === 0 &&
            selectedFilters.value.selectedMotCles.length === 0 &&
            selectedFilters.value.difficulte.length === 0 &&
            selectedFilters.value.theme === null
        ) {
            matches = true;
        }

        return matches;
    });
});
const randomVert = () => {
    const green = Math.floor(Math.random() * 100) + 100;
    const red = Math.floor(Math.random() * 0);
    const blue = Math.floor(Math.random() * 0);

    const hex = (value) => {
        const hexValue = value.toString(16);
        return hexValue.length === 1 ? "0" + hexValue : hexValue;
    };

    const color = `#${hex(red)}${hex(green)}${hex(blue)}`;
    return color;
};

// Global array to store markers and layers
let existingMarkers = [];
let existingLayers = [];

const afficheRoute = (tour) => {
    const routeLayerId = `route-layer${tour.id}`;
    if (map.getLayer(routeLayerId)) {
        map.removeLayer(routeLayerId);
        map.removeSource(routeLayerId);
    }

    const couleur = randomVert();
    const etapesInversees = tour.etapes.slice().reverse();
    const idDepart = tour.etapes[0].id;

    etapesInversees.forEach((etape, index) => {
        const coordinate = [etape.longitude, etape.latitude];
        const marker = new maplibregl.Marker({ color: couleur })
            .setLngLat(coordinate)
            .addTo(map);

        // Add the marker to the global array
        existingMarkers.push(marker);

        if (etape.id !== idDepart) {
            const markerElement = marker._element;
            const svgElement = markerElement.querySelector("svg");
            if (svgElement) {
                svgElement.innerHTML = "";

                svgElement.style.position = "relative";
                svgElement.style.zIndex = "12";

                const circle = document.createElementNS(
                    "http://www.w3.org/2000/svg",
                    "circle"
                );
                circle.setAttribute("cx", "12");
                circle.setAttribute("cy", "27");
                circle.setAttribute("r", "12");
                circle.setAttribute("fill", couleur);

                svgElement.appendChild(circle);
            }
        } else {
            const markerElement = marker._element;
            const svgElement = markerElement.querySelector("svg");
            if (svgElement) {
                svgElement.innerHTML = "";
                svgElement.setAttribute("width", "90");
                svgElement.setAttribute("height", "90");
                svgElement.style.position = "relative";
                svgElement.style.zIndex = "1000";

                const point = `<svg id="Calque_1" data-name="Calque 1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 50 50">
                        <defs>
                          <style>
                            .cls-1 { display: none; }
                            .cls-1, .cls-2, .cls-3, .cls-4 { stroke-width: 0px; }
                            .cls-1, .cls-4 { fill: #40680c; }
                            .cls-2 { isolation: isolate; opacity: .5; }
                            .cls-2, .cls-3 { fill: #fff; }
                          </style>
                        </defs>
                        <path class="cls-4" d="M36.6,8.6c-3.1-3.1-7.1-4.8-11.7-4.8s-8.6,1.7-11.7,4.8c-3.3,3.3-4.8,7.3-4.8,12.4s1.3,7.1,4.2,11.3c2.7,4.2,6.9,9,12.6,14,5.7-5.2,9.8-9.8,12.6-14,2.7-4.2,4-8,4-11.3-.2-5-1.9-9-5.2-12.4ZM33.7,20.9c0,.6,0,1.1-.2,1.6,0,.2,0,.4,0,.5,0,.4-.3.8-.5,1.2-1.4,2.9-4.5,4.8-7.9,4.8s-8.6-3.8-8.8-8.3v-.8c0-1.7.6-3.2,1.5-4.5.1-.2.3-.4.5-.6s.2-.3.4-.4c0,0,.2-.2.3-.3,1.6-1.6,3.8-2.6,6.2-2.6,4.6,0,8.3,3.5,8.8,8v1.3h-.2Z"/>
                        <path class="cls-2" d="M-9.3,5.9v.5c0,.6,0,1.1-.2,1.6,0,.2,0,.4,0,.5,0,.4-.3.8-.5,1.2-1.4,2.9-4.5,4.8-7.9,4.8s-8.6-3.8-8.8-8.3v-.8c0-1.6.6-3.2,1.5-4.5.1-.2.3-.4.5-.6s.2-.3.4-.4c0,0,.2-.2.3-.3,1.6-1.6,3.8-2.6,6.2-2.6,4.6,0,8.3,3.5,8.8,8v.8h-.2Z"/>
                        <circle class="cls-3" cx="25" cy="20" r="9"/>
                        <g>
                          <path class="cls-4" d="M24.1,18c-.3.3-.6.5-.8.7-.2.2-.3.5-.3.8,0,.5-.1,1-.2,1.4,0,.4-.3.5-.6.5-.3,0-.5-.3-.5-.6,0-.7.2-1.4.3-2.2,0-.2.2-.5.4-.6.5-.4,1-.8,1.5-1.2.5-.4,1-.6,1.6-.6.5,0,.9.2,1.1.6.2.5.4,1,.6,1.5.2.5.4.9.9,1.1.4.2.8.4,1.2.6.3.2.5.4.3.7-.1.3-.4.4-.8.2-.6-.3-1.3-.6-1.9-.9-.3-.2-.5-.5-.8-.8-.1.5-.2,1.1-.4,1.7,0,0,0,.2.1.3.9,1.4,1.8,2.8,2.7,4.2.2.3.2.6,0,.9-.2.3-.4.4-.8.3-.2,0-.4-.2-.5-.3-1.2-1.8-2.4-3.7-3.5-5.6,0,0,0-.2,0-.4.1-.7.3-1.3.4-2,0,0,0-.2,0-.4Z"/>
                          <path class="cls-4" d="M23.4,21.7c.3.4.6.8.9,1.2,0,0,0,.1,0,.2-.1.4-.2.8-.4 1.1-.4.7-1 1.3-1.5 2-.3.4-.7.5-1 .2-.3-.2-.4-.7,0-1,.2-.2.3-.5.5-.7.7-.8,1.1-1.7,1.3-2.7,0,0,0-.1,0-.2,0,0,0,0,0-.1Z"/>
                          <path class="cls-4" d="M24.7,14.4c0-.7.6-1.3,1.3-1.3.7,0,1.3.6,1.3,1.3,0,.7-.6,1.3-1.3,1.3-.7,0-1.3-.6-1.3-1.3Z"/>
                        </g>
                        <path class="cls-1" d="M21.3,20.2c0-1.1,0-2.3,0-3.4,0-1,.7-1.8,1.6-2,.5,0,.9,0,1.3.2,1.8,1.2,3.6,2.3,5.3,3.5,1.2.8,1.2,2.6,0,3.3-1.8,1.2-3.5,2.3-5.3,3.5-.6.4-1.3.4-1.9,0-.7-.4-1-1-1-1.8,0-1.2,0-2.3,0-3.5ZM23.3,23.7c1.7-1.1,3.5-2.3,5.2-3.4-1.7-1.1-3.4-2.3-5.2-3.4v6.8Z"/>
                      </svg>`;
                svgElement.innerHTML = point;
            }
        }

        marker.getElement().addEventListener("click", () => {
            window.location.hash = `sentier-${tour.id}`;
        });
    });

    if (tour.etapes.length > 1) {
        fetch(
            "https://api.openrouteservice.org/v2/directions/foot-hiking/geojson",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Authorization:
                        "5b3ce3597851110001cf6248cbe7a7b654c74537a20bd21243c00b7a",
                },
                body: JSON.stringify({
                    coordinates: tour.etapes.map((etape) => [
                        etape.longitude,
                        etape.latitude,
                    ]),
                }),
            }
        )
            .then((response) => response.json())
            .then((responseData) => {
                const coordinates =
                    responseData.features[0].geometry.coordinates;

                map.addLayer({
                    id: routeLayerId,
                    type: "line",
                    source: {
                        type: "geojson",
                        data: {
                            type: "Feature",
                            geometry: {
                                type: "LineString",
                                coordinates: coordinates,
                            },
                        },
                    },
                    layout: {
                        "line-join": "round",
                        "line-cap": "round",
                    },
                    paint: {
                        "line-color": `${couleur}`,
                        "line-width": 5,
                    },
                });

                // Add the layer to the global array
                existingLayers.push(routeLayerId);
            })
            .catch((error) => {
                console.error("Erreur lors de la requête:", error);
            });
    }
};

const showTours = (tours) => {
    // Remove existing markers
    existingMarkers.forEach(marker => marker.remove());
    existingMarkers = []; // Clear the array

    // Remove existing layers
    existingLayers.forEach(layerId => {
        if (map.getLayer(layerId)) {
            map.removeLayer(layerId);
            map.removeSource(layerId);
        }
    });
    existingLayers = []; // Clear the array

    tours.forEach((tour) => {
        afficheRoute(tour);
    });
};


const recenter = () => {
    console.log("recenter");
    map.flyTo({
        center: coordonnesRecenter.value,
        zoom: 8.5,
        curve: 1,
        easing(t) {
            return t;
        },
    });
};

onMounted(() => {
    map = new maplibregl.Map({
        container: "mapCarteAccueil",
        style: "https://api.maptiler.com/maps/de2783ff-b0c6-4f3d-8d9a-4bd8d5051450/style.json?key=kzJF26jznLlv3rUUVUK7",
        center: [6.700021, 46.602693],
        zoom: 8.5,
    });

    map.on("load", () => {
        map.addControl(
            new maplibregl.NavigationControl({
                showCompass: false,
                showZoom: true,
            })
        );
    });

    fetchSentiers();
});

watchEffect(() => {
  console.log(filteredSentiers.value);
    showTours(filteredSentiers.value);
});
</script>

<style scoped>
#mapCarteAccueil {
    width: 100vw;
    height: 100vh;
    position: absolute;
    left: 0;
    top: 0;
}
.header span {
    top: 8px;
}
#recenter {
    padding: 5px 10px;
}
.recherche {
    top: 5% !important;
    width: 80%;
}
#buttonFiltre {
    top: 5% !important;
    right: 5%;
}
@media only screen and (min-width: 900px) {
    .recherche[type="text"] + .search-icone{
        left:18.5%;
    }
}
</style>
<style>
@media only screen and (min-width: 900px) {

#carte-accueil #recenterDiv{
    right: 0.5% !important;
}
}
</style>