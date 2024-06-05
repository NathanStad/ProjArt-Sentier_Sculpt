<template>
    <div>
        <div id="map"></div>
        <div id="overlayEtape">
            <EtapeComponent ref="etapeComponent" v-if="sentiers && sentiers[hash]" :etapes="sentiers[hash]"
                @active-slide-key-change="handleActiveSlideKeyChange"></EtapeComponent>
        </div>
        <div id="recenterDiv">
            <RecentrerBtnComponent @recenter="recenter" />
        </div>
    </div>
</template>

<script>
import maplibregl from "maplibre-gl";
import 'maplibre-gl/dist/maplibre-gl.css';
import EtapeComponent from './elements/etapeComponent.vue';
import RecentrerBtnComponent from './elements/recentrerBtnComponent.vue';


import axios from 'axios';
let map;

export default {
    data() {
        return {
            sentiers: [],
            hash: window.location.hash.split('-')[1] - 1,
            etapeActive: 0,
            markers: [],
            coordonnesRecenter: [],
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
        },
        removeMarkers() {
            this.markers.forEach(marker => marker.remove());
            this.markers = [];
        },
        afficheRoute(tour) {
            this.removeMarkers();

            const routeLayerId = `route-layer${tour.id}`;
            const couleur = '#40680c';
            const etapesInversees = tour.etapes.slice().reverse();
            if (this.etapeActive === 0) {
                const idEtapeActive = tour.etapes[0].id;
                this.etapeActive = idEtapeActive;
            }

            etapesInversees.forEach((etape) => {
                this.coordonnesRecenter = [etape.longitude, etape.latitude];
                const marker = new maplibregl.Marker({ color: couleur }).setLngLat(this.coordonnesRecenter).addTo(map);
                marker.getElement().setAttribute('data-id', etape.id);
                this.markers.push(marker);

                const markerElement = marker._element;
                const svgElement = markerElement.querySelector('svg');
                while (svgElement.firstChild) {
                    svgElement.removeChild(svgElement.firstChild);
                }

                if (etape.id !== this.etapeActive) {
                    if (svgElement) {
                        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        circle.setAttribute('cx', '12');
                        circle.setAttribute('cy', '27');
                        circle.setAttribute('r', '12');
                        circle.setAttribute('fill', couleur);
                        svgElement.appendChild(circle);
                    }
                } else {
                    if (svgElement) {
                        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        circle.setAttribute('cx', '17');
                        circle.setAttribute('cy', '17');
                        circle.setAttribute('r', '16');
                        circle.setAttribute('fill', '#FFF');
                        circle.setAttribute('stroke', couleur);
                        circle.setAttribute('stroke-width', '2');
                        svgElement.appendChild(circle);

                        // Créez le clipPath
                        const clipPath = document.createElementNS('http://www.w3.org/2000/svg', 'clipPath');
                        clipPath.setAttribute('id', 'clip-circle');

                        const clipCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        clipCircle.setAttribute('cx', '17');
                        clipCircle.setAttribute('cy', '17');
                        clipCircle.setAttribute('r', '14'); // Le rayon est plus petit pour laisser de l'espace
                        clipPath.appendChild(clipCircle);

                        svgElement.appendChild(clipPath);

                        const image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
                        image.setAttribute('x', '3'); // Ajustez pour centrer l'image
                        image.setAttribute('y', '3'); // Ajustez pour centrer l'image
                        image.setAttribute('width', '28'); // Plus petit que le cercle vert
                        image.setAttribute('height', '28'); // Plus petit que le cercle vert
                        image.setAttribute('class', 'imageEtapeActive');
                        const etapeDepart = tour.etapes.find(etape => etape.id === this.etapeActive);

                        image.setAttributeNS('http://www.w3.org/1999/xlink', 'href', etapeDepart.photo);
                        image.setAttribute('clip-path', 'url(#clip-circle)');
                        svgElement.appendChild(image);

                        svgElement.setAttribute('width', '63');
                        svgElement.setAttribute('height', '63');
                    }
                }

                marker.getElement().addEventListener("click", () => {
                    if (this.etapeActive == marker.getElement().getAttribute('data-id')) {
                        console.log(`etape click: `, etape.nom);
                        window.location.hash = `etape-${etape.id}`
                    } else {
                        this.$refs.etapeComponent.onSlideTo(etape.id);
                    }
                });
            });

            map.on("load", () => {
                if (tour.etapes.length > 1) {
                    fetch("https://api.openrouteservice.org/v2/directions/foot-hiking/geojson", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Authorization": "5b3ce3597851110001cf6248cbe7a7b654c74537a20bd21243c00b7a",
                        },
                        body: JSON.stringify({
                            coordinates: tour.etapes.map(etape => [etape.longitude, etape.latitude]),
                        }),
                    })
                        .then(response => response.json())
                        .then(responseData => {
                            const coordinates = responseData.features[0].geometry.coordinates;

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
                        })
                        .catch(error => {
                            console.error("Erreur lors de la requête:", error);
                        });
                }
            });
        },
        showTours(tours) {
            const hash = window.location.hash;
            const numero = hash.split('-')[1];
            if (numero) {
                const tour = tours.find(tour => tour.id === parseInt(numero));
                const recenter = [tour.etapes[0].longitude, tour.etapes[0].latitude];
                if (tour) {
                    map.setCenter([tour.etapes[0].longitude, tour.etapes[0].latitude]);
                    map.setZoom(12);
                    map.setMaxZoom(18);
                    map.setMinZoom(11);
                    this.afficheRoute(tour);
                } else {
                    throw new Error('Sentier pas trouvé');
                }
            }
        },
        recenter() {
            console.log('recenter');
            map.flyTo({
                center: this.coordonnesRecenter,
                zoom: 12,
                curve: 1,
                easing(t) {
                    return t;
                },
            });
        },
        changeDePoint(id) {
            const etape = this.sentiers[this.hash].etapes.find(etape => etape.id === id);
            map.flyTo({
                center: [etape.longitude, etape.latitude],
                zoom: 14,
                curve: 1,
                easing(t) {
                    return t;
                },
            });
        },
        handleActiveSlideKeyChange(newVal) {
            this.etapeActive = newVal;
            this.afficheRoute(this.sentiers[this.hash])
            this.changeDePoint(newVal);
        },
    },
    mounted() {
        map = new maplibregl.Map({
            container: "map",
            style: "https://api.maptiler.com/maps/de2783ff-b0c6-4f3d-8d9a-4bd8d5051450/style.json?key=kzJF26jznLlv3rUUVUK7",
            center: [6.700021, 46.602693],
            zoom: 9.2,
            maxZoom: 18,
            minZoom: 9,
        });

        map.on("load", () => {
            map.addControl(
                new maplibregl.NavigationControl({
                    showCompass: false,
                    showZoom: true,
                })
            );
        });
    },
    watch: {
        sentiers: {
            handler() {
                this.showTours(this.sentiers);
            },
            deep: true
        },
    },
    components: {
        EtapeComponent,
        RecentrerBtnComponent,
    },
};
</script>

<style scoped>
#map {
    width: 100%;
    height: 100vh;
    flex: 1;
    position: relative;
}

html,
body {
    margin: 0;
    padding: 0;
    height: 100%;
    display: flex;
    flex-direction: column;
}

#recenterDiv {
    position: absolute;
    width: 60px;
    height: 58px;
    top: 10px;
    right: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
}

#overlayEtape {
    width: 100vw;
    height: 120px;
    position: absolute;
    bottom: 2rem;
}

.imageEtapeActive {
    border-radius: 50%;
    border: 2px solid black;
    background-size: cover;
}
</style>