<template>
    <div class="header">
        <input type="text" v-model="searchQuery" placeholder="Rechercher par lieu et nom" class="recherche" />
        <span class="material-symbols-outlined search-icone"> search </span>
        <!-- Bouton Filtre -->
        <div @click="toggleFiltre()" id="buttonFiltre">
            <span class="material-symbols-outlined"> tune </span>
        </div>
    </div>

    <!-- Conteneur du filtre -->
    <div id="filtre" :class="{ visible: filtreVisible === true }">
        <Filtre @updateFilters="updateFilters" :closeFilter="toggleFiltre"></Filtre>
    </div>
    <div id="carte-accueil">
        <div id="mapCarteAccueil"></div>
        <div id="recenterDiv">
            <RecentrerBtnComponent @recenter="recenter" />
        </div>
    </div>
    <footer>
        <Footer></Footer>
    </footer>
</template>

<script setup>
import { ref, onMounted, watchEffect, computed } from "vue";
import axios from "axios";
import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";
import RecentrerBtnComponent from "./elements/recentrerBtnComponent.vue";
import Footer from "@/components/elements/footer.vue";
import Filtre from "@/components/elements/filter.vue";

let map; // Variable pour stocker l'objet carte MapLibre
const sentiers = ref([]); // Liste des sentiers
const coordonnesRecenter = ref([6.600021, 46.602693]); // Coordonnées pour recentrer la carte
const searchQuery = ref(""); // Variable pour la barre de recherche
const selectedFilters = ref({
    selectedCriteres: [],
    selectedMotCles: [],
    difficulte: [],
}); // Filtres sélectionnés
const filtreVisible = ref(false); // Variable pour afficher ou masquer les filtres

const fetchSentiers = async () => {
    // Fonction pour récupérer les sentiers depuis l'API
    try {
        const response = await axios.get("/data-sentiers");
        sentiers.value = response.data.filter((item) => item.archive === 0);
    } catch (error) {
        console.error("Erreur lors de la récupération des sentiers:", error);
    }
};

// Méthode pour basculer l'affichage des filtres
const toggleFiltre = () => {
    filtreVisible.value = !filtreVisible.value;
};

// Filtres les sentiers en fonction des critères de recherche et des filtres sélectionnés
const filteredSentiers = computed(() => {
    return sentiers.value.filter((sentier) => {
        let matches = true; // Initialise matches à true pour tous les sentiers

        // Filtre par la requête de recherche
        if (searchQuery.value.trim() !== "") {
            matches = matches && (
                sentier.nom.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                sentier.localisation.toLowerCase().includes(searchQuery.value.toLowerCase())
            );
        }

        // Filtre par les critères sélectionnés
        if (selectedFilters.value.selectedCriteres.length > 0) {
            const critereIds = sentier.criteres.map(critere => critere.id);
            const matchesSelectedCriteres = selectedFilters.value.selectedCriteres.every(critere => critereIds.includes(critere));
            matches = matches && matchesSelectedCriteres;
        }

        // Filtre par les mots-clés sélectionnés
        if (selectedFilters.value.selectedMotCles.length > 0) {
            const motCleIds = sentier.motcles.map(motcle => motcle.id);
            const matchesSelectedMotCles = selectedFilters.value.selectedMotCles.every(motcle => motCleIds.includes(motcle));
            matches = matches && matchesSelectedMotCles;
        }

        // Filtre par difficulté
        if (selectedFilters.value.difficulte.length > 0) {
            const matchesDifficulty = selectedFilters.value.difficulte.includes(`${sentier.difficulte.graduation}`);
            matches = matches && matchesDifficulty;
        }

        // Si aucun filtre n'est sélectionné, on veut correspondre à tous les sentiers
        if (
            searchQuery.value.trim() === "" &&
            selectedFilters.value.selectedCriteres.length === 0 &&
            selectedFilters.value.selectedMotCles.length === 0 &&
            selectedFilters.value.difficulte.length === 0
        ) {
            matches = true;
        }

        return matches;
    });
});

// Met à jour les filtres
const updateFilters = (filters) => {
    selectedFilters.value = filters;
};

// Génère une couleur verte aléatoire
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

// Tableau global pour stocker les marqueurs et les couches
let existingMarkers = [];
let existingLayers = [];

// Affiche la route d'un sentier sur la carte
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

        // Ajoute le marqueur au tableau global
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
                        <path class="cls-4" d="M36.6,8.6c-3.1-3.1-7.1-4.8-11.7-4.8s-8.6,1.7-11.7,4.8c-3.3,3.3-4.8,7.3-4.8,12.4s1.3,7.1,4.2,11.3c2.7,4.2,6.9,9,12.6,14,5.7-5.2,9.8-9.8,12.6-14,2.7-4.2,4-8,4-11.3-.2-5-1.9-9-5.2-12.4ZM33.7,20.9c0,.6,0,1.1-.2,1.6,0,.2,0,.4,0,.5,0,.4-.3.8-.5,1.2-1.4,2.9-4.5,4.8-7.9,4.8s-8.6-3.8-8.6-8.5c0-4.2,3.8-7.8,8.5-8.6H26.6c.4,0,.8,0,1.2,0,.5,0,.9,0,1.3.2.2,0,.4,0,.5.2,3.9,1.1,6.9,4.5,6.9,8.5h.2v.1Z"/>
                        <circle class="cls-1" cx="25" cy="21" r="4.5"/>
                        <path class="cls-2" d="M35.3,12.7c-3.1-3.1-7.1-4.8-11.7-4.8s-8.6,1.7-11.7,4.8c-3.3,3.3-4.8,7.3-4.8,12.4s1.3,7.1,4.2,11.3c2.7,4.2,6.9,9,12.6,14,5.7-5.2,9.8-9.8,12.6-14,2.7-4.2,4-8,4-11.3-.2-5-1.9-9-5.2-12.4ZM28.4,21c0,.5,0,.9-.2,1.3v.2c0,.4-.3.8-.5,1.2-1.4,2.9-4.5,4.8-7.9,4.8-4.2,0-8.6-3.8-8.6-8.5,0-4.2,3.8-7.8,8.5-8.6h.3c.4,0,.8,0,1.2,0,.5,0,.9,0,1.3.2s.4,0,.5.2c3.7,1.1,6.6,4.6,6.6,8.6h.3Z"/>
                        <circle class="cls-3" cx="23.9" cy="19.7" r="4.5"/>
                        </svg>`;

                svgElement.innerHTML = point;
            }
        }
    });

    const coordinates = tour.etapes.map((etape) => [
        etape.longitude,
        etape.latitude,
    ]);

    // Ajoute une source GeoJSON pour la route
    map.addSource(routeLayerId, {
        type: "geojson",
        data: {
            type: "Feature",
            properties: {},
            geometry: {
                type: "LineString",
                coordinates: coordinates,
            },
        },
    });

    // Ajoute une couche pour afficher la route
    map.addLayer({
        id: routeLayerId,
        type: "line",
        source: routeLayerId,
        layout: {
            "line-join": "round",
            "line-cap": "round",
        },
        paint: {
            "line-color": couleur,
            "line-width": 4,
        },
    });

    // Ajoute la couche au tableau global
    existingLayers.push(routeLayerId);
};

// Initialisation de la carte lors du montage du composant
onMounted(async () => {
    await fetchSentiers();

    map = new maplibregl.Map({
        container: "mapCarteAccueil",
        style: `https://api.maptiler.com/maps/outdoor/style.json?key=${import.meta.env.VITE_MAPTILER_API_KEY}`,
        center: coordonnesRecenter.value,
        zoom: 12,
    });

    // Ajout des contrôles de navigation
    map.addControl(new maplibregl.NavigationControl());
    const geolocateControl = new maplibregl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true,
        },
        trackUserLocation: true,
        showUserHeading: true,
    });

    map.addControl(geolocateControl);
    geolocateControl.on("geolocate", (e) => {
        const lon = e.coords.longitude;
        const lat = e.coords.latitude;
        map.flyTo({ center: [lon, lat], zoom: 10 });
    });

    // Affiche les routes des sentiers sur la carte lors du chargement de la carte
    map.on("load", () => {
        filteredSentiers.value.forEach((tour) => {
            afficheRoute(tour);
        });
    });
});

// Observe les changements et met à jour les sentiers affichés en fonction des filtres et de la recherche
watchEffect(() => {
    if (map) {
        // Supprime les marqueurs et les couches existants
        existingMarkers.forEach((marker) => marker.remove());
        existingMarkers = [];
        existingLayers.forEach((layerId) => {
            if (map.getLayer(layerId)) {
                map.removeLayer(layerId);
                map.removeSource(layerId);
            }
        });
        existingLayers = [];

        // Ajoute les nouveaux marqueurs et couches
        filteredSentiers.value.forEach((tour) => {
            afficheRoute(tour);
        });
    }
});

// Fonction pour recentrer la carte
const recenter = () => {
    if (map) {
        map.flyTo({
            center: coordonnesRecenter.value,
            zoom: 10,
        });
    }
};
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
    .recherche[type="text"]+.search-icone {
        left: 18.5%;
    }
}
</style>
<style>
@media only screen and (min-width: 900px) {

    #carte-accueil #recenterDiv {
        right: 0.5% !important;
    }
}
</style>