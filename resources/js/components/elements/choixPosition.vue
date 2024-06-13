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
import maplibregl from "maplibre-gl";
import 'maplibre-gl/dist/maplibre-gl.css';
import RecentrerBtnComponent from './recentrerBtnComponent.vue';
import ConfirmationModal from './ConfirmationModal.vue'; // Import du composant ConfirmationModal

let map;

export default {
    name: 'ChoixPosition',
    data() {
        return {
            marker: null,
            coordonnesRecenter: [6.700021, 46.602693],
            zoomRecentrer: 8,
            couleur: '#40680c',
            showModal: false,
            element: ""
        }
    },
    props: {
        updateCoordinates: Function,
        etapes: Array
    },
    mounted() {
        map = new maplibregl.Map({
            container: "mapChoixPosition",
            style: "https://api.maptiler.com/maps/ch-swisstopo-lbm/style.json?key=UwLYJlrEYGDsXphBkCHS",
            center: this.coordonnesRecenter,
            zoom: this.zoomRecentrer,
        });
        map.on("load", () => {
            map.addControl(
                new maplibregl.NavigationControl({
                    showCompass: false,
                    showZoom: true,
                })
            );
            if (this.etapes.coordonnees.long && this.etapes.coordonnees.lat) {
                const marker = new maplibregl.Marker()
                    .setLngLat([this.etapes.coordonnees.long, this.etapes.coordonnees.lat])
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
        });
        map.on("click", (e) => {
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
    methods: {
        enleverConfirmation() {
            this.showModal = false;
        },
        recenter() {
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
    components: {
        RecentrerBtnComponent,
        ConfirmationModal // Ajout du composant ConfirmationModal
    },
    watch: {
        marker: {
            handler() {
                this.coordonnesRecenter = [this.marker.getLngLat().lng, this.marker.getLngLat().lat];
                this.zoomRecentrer = 12;
            },
            deep: true,
        },
        etapes: {
            handler() {
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
