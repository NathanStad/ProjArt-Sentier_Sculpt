<template>
    <div>
        <div id="map"></div>
        <div v-if="sentiers.length > 0">
            <p v-for="sentier in sentiers" :key="sentier.id">{{ sentier }}</p>
        </div>
        <div v-else>
            <p>No sentiers available</p>
        </div>
    </div>
</template>

<script>
import maplibregl from "maplibre-gl";
import 'maplibre-gl/dist/maplibre-gl.css';
import axios from 'axios';
import { ref } from 'vue';

let map;

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
                const response = await axios.get('/data-sentiers');
                this.sentiers = response.data;
            } catch (error) {
                console.error('Error fetching sentiers:', error);
            }
        }, randomVert() {
            const green = Math.floor(Math.random() * 200);
            const red = Math.floor(Math.random() * 0);
            const blue = Math.floor(Math.random() * 0);

            const hex = (value) => {
                const hexValue = value.toString(16);
                return hexValue.length === 1 ? '0' + hexValue : hexValue;
            };

            const color = `#${hex(red)}${hex(green)}${hex(blue)}`;
            return color;
        },
        afficheRoute(tour) {
            const routeLayerId = `route-layer${tour.id}`;
            if (map.getLayer(routeLayerId)) {
                map.removeLayer(routeLayerId);
                map.removeSource(routeLayerId);
            }

            const couleur = this.randomVert();
            tour.etapes.forEach((etape, index) => {
                const coordinate = [etape.longitude, etape.latitude];
                //console.log("Coordinate:", coordinate);

                const marker = new maplibregl.Marker({}).setLngLat(coordinate).addTo(map);
                console.log("Marker:", marker._element);

                // console.log("Marker:", marker._lngLat);

                const popup = new maplibregl.Popup({
                    offset: 0,
                    closeButton: false,
                    closeOnClick: true,
                }).setText(`Étape ${index + 1}`);

                marker.setPopup(popup);
                marker.getElement().addEventListener("click", () => {
                });
            });

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
        },
        showTours(tours) {
            tours.forEach((tour) => {
                this.afficheRoute(tour);
            });
        },
    },
    mounted() {
        map = new maplibregl.Map({
            container: "map",
            style: "https://api.maptiler.com/maps/de2783ff-b0c6-4f3d-8d9a-4bd8d5051450/style.json?key=kzJF26jznLlv3rUUVUK7",
            center: [6.700021, 46.602693],
            zoom: 9.2,
        });

        map.on("load", () => {
            map.addControl(
                new maplibregl.NavigationControl({
                    showCompass: false,
                    showZoom: false,
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
        }
    }
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
</style>