<template>
    <div id="map-container">
        <div id="mapChoixPosition"></div>
    </div>
    <div id="recenterDiv">
        <RecentrerBtnComponent @recenter="recenter" />
    </div>
    <ConfirmationModal 
        v-if="showModal" 
        @confirm="handleConfirm" 
        @cancel="enleverConfirmation"
        :type="true"
        message="Voulez-vous vraiment remplacer le marqueur existant ?"
    />
</template>

<script>
// Importation de MapLibre GL et de ses styles
import maplibregl from "maplibre-gl";
import 'maplibre-gl/dist/maplibre-gl.css';
// Importation des composants personnalisés
import RecentrerBtnComponent from './recentrerBtnComponent.vue';
import ConfirmationModal from './ConfirmationModal.vue';

let map; // Variable globale pour stocker l'instance de la carte

export default {
    name: 'ChoixPosition', // Nom du composant
    data() { // Données réactives du composant
        return {
            marker: null, // Marqueur actuel sur la carte
            coordonnesRecenter: [6.700021, 46.602693], // Coordonnées pour recentrer la carte
            zoomRecentrer: 8, // Zoom pour recentrer la carte
            couleur: '#40680c', // Couleur du cercle autour du marqueur
            showModal: false, // État pour afficher le modal de confirmation
            element: "" // Stockage temporaire des coordonnées cliquées ou sélectionnées
        }
    },
    props: { // Propriétés passées au composant
        updateCoordinates: Function, // Fonction pour mettre à jour les coordonnées
        etapes: Array // Étapes de navigation ou d'autres données liées aux coordonnées
    },
    mounted() { // Méthode appelée après le montage du composant
        // Initialisation de la carte MapLibre GL
        map = new maplibregl.Map({
            container: "mapChoixPosition", // ID du conteneur HTML pour la carte
            style: "URL du style de la carte", // URL du style JSON de la carte
            center: this.coordonnesRecenter, // Coordonnées initiales du centre de la carte
            zoom: this.zoomRecentrer, // Zoom initial de la carte
        });
        // Gestion des événements de chargement de la carte
        map.on("load", () => {
            // Ajout du contrôle de navigation
            map.addControl(new maplibregl.NavigationControl({showCompass: false, showZoom: true}));
            // Logique pour placer un marqueur si des coordonnées sont fournies
            if (this.etapes.coordonnees.long && this.etapes.coordonnees.lat) {
                // Création et ajout d'un marqueur à la carte
                const marker = new maplibregl.Marker().setLngLat([this.etapes.coordonnees.long, this.etapes.coordonnees.lat]).addTo(map);
                this.marker = marker; // Mise à jour de l'état interne pour référencer le marqueur
                // Modification visuelle du marqueur
                const markerElement = marker._element.querySelector('svg');
                while (markerElement.firstChild) {
                    markerElement.removeChild(markerElement.firstChild);
                }
                if (markerElement) {
                    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                    circle.setAttribute('cx', '12');
                    circle.setAttribute('cy', '27');
                    circle.setAttribute('r', '12');
                    circle.setAttribute('fill', this.couleur);
                    markerElement.appendChild(circle);
                }
            }
        });
        // Gestion des clics sur la carte
        map.on("click", (e) => {
            // Logique pour gérer le clic sur la carte
            if (this.marker) {
                this.showModal = true;
                this.element = e
            } else {
                const marker = new maplibregl.Marker()
                    .setLngLat(e.lngLat)
                    .addTo(map);
                this.marker = marker;
                const markerElement = marker._element;
                const svgElement = markerElement.querySelector('svg');
                while (svgElement.firstChild) {
                    svgElement.removeChild(svgElement.firstChild);
                }
                if (svgElement) {
                    console.log(svgElement);
                    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                    circle.setAttribute('cx', '12');
                    circle.setAttribute('cy', '27');
                    circle.setAttribute('r', '12');
                    circle.setAttribute('fill', this.couleur);
                    svgElement.appendChild(circle);
                }
            }
            this.updateCoordinates(e.lngLat);
        });
    },
    methods: { // Méthodes du composant
        enleverConfirmation() { // Fermeture du modal de confirmation
            this.showModal = false;
        },
        recenter() { // Réinitialisation du centre de la carte
            map.flyTo({
                center: this.coordonnesRecenter,
                zoom: this.zoomRecentrer,
                curve: 1,
                easing(t) {
                    return t;
                },
            });
        },
        handleConfirm() {
            // Gestion de la confirmation pour remplacer le marqueur
            // Logique pour remplacer le marqueur
            this.marker.remove();
            const marker = new maplibregl.Marker()
                .setLngLat(this.element.lngLat)
                .addTo(map);
            this.marker = marker;
            const markerElement = marker._element;
            const svgElement = markerElement.querySelector('svg');
            while (svgElement.firstChild) {
                svgElement.removeChild(svgElement.firstChild);
            }
            if (svgElement) {
                console.log(svgElement);
                const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                circle.setAttribute('cx', '12');
                circle.setAttribute('cy', '27');
                circle.setAttribute('r', '12');
                circle.setAttribute('fill', this.couleur);
                svgElement.appendChild(circle);
            }
            this.showModal = false;
        },
    },
    components: { // Déclaration des composants utilisés
        RecentrerBtnComponent,
        ConfirmationModal // Ajout du composant ConfirmationModal
    },
    watch: { // Observateurs pour réagir aux changements des propriétés ou des données
        marker: {
            handler() {
                // Mise à jour des coordonnées de centrage et du zoom basé sur le marqueur
                this.coordonnesRecenter = [this.marker.getLngLat().lng, this.marker.getLngLat().lat];
                this.zoomRecentrer = 12;
            },
            deep: true,
        },
        etapes: {
            handler() {
                // Logique pour réagir aux changements des étapes
                console.log(this.etapes)
            },
            deep: true,
        },
    }
}
</script>

<style>
#map-container {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
}

#mapChoixPosition {
    flex: 1;
    border-radius: 10px;
}

#recenterDiv {
    position: absolute;
    width: 40px;
    height: 58px;
    top: 30%;
    right: 2.5%;
    display: flex;
    justify-content: center;
    align-items: center;
    rotate: 90deg;
    border: transparent;
}
@media only screen and (min-width: 900px) {
    #recenterDiv{
        right: 1.5%;
    }
}
</style>
