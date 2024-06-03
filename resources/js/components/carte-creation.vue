<template>
    <div id="page">
        <div id="formulaire">
            <div class="form-container">
                <h2>Création d'un sentier</h2>
                <draggable v-model="etapes" item-key="id">
                    <div class="form-container"></div>
                    <template #item="{ element, index }">
                        <div class="stepContainer">
                            <div class="infoMap">
                                <p v-if="index === etapes.length - 1">📍</p>
                                <p v-else>🗺️</p>
                            </div>
                            <div class="step" @click="clickEtape(index)">
                                <span class="step-name">{{ element.nom || 'Etape ' + (index + 1) }}</span>
                                <div class="dragHandle">🔃</div>
                            </div>
                            <div class="deleteEtape">
                                <span v-if="index !== 0" class="step-emoji" @click="delEtape(element)">❌</span>
                            </div>
                        </div>
                    </template>
                </draggable>
                <div class="add-step" @click="ajouteEtape">+</div>
            </div>
        </div>
        <div id="map">
        </div>
        <div id="btnSuivant">
            <button>Suivant</button>
        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable'
import maplibregl from "maplibre-gl";
import 'maplibre-gl/dist/maplibre-gl.css';
import EtapeComponent from './elements/etapeComponent.vue';

import axios from 'axios';

let map;

export default {
    name: 'CarteCreation',
    components: {
        draggable,
        EtapeComponent,
    },

    data() {
        return {
            etapes: [
                { nom: 'nom 1', description: 'la description', coordonnees: { lat: null, long: null }, photo: null, pointInteret: [{ nom: '', photo: null }, { nom: '', photo: null }] },
                { nom: 'nom 2', description: 'la descr', coordonnees: { lat: null, long: null }, photo: null, pointInteret: [{ nom: '', photo: null }, { nom: '', photo: null }] },
            ],
            oldIndex: '',
            newIndex: '',
        };
    },

    methods: {
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
                const coordinate = [etape.longitude, etape.latitude];
                const marker = new maplibregl.Marker({ color: couleur }).setLngLat(coordinate).addTo(map);
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
                    document.querySelector('#recenter').addEventListener('click', () => {
                        this.recenter(recenter);
                    });
                } else {
                    throw new Error('Sentier pas trouvé');
                }
            }
        },
        ajouteEtape() {
            this.etapes.push({ nom: '', description: '', coordonnees: { lat: null, long: null }, photo: null, pointInteret: [{ nom: '', photo: null }, { nom: '', photo: null }] },
            );
        },
        onEnd(evt) {
            console.log(evt);
            this.oldIndex = evt.oldIndex;
            this.newIndex = evt.newIndex;
        },
        clickEtape(index) {
            const hash = `etape-${index + 1}`;
            window.location.hash = hash;
        },
        delEtape(etape) {
            this.etapes.pop(etape);
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
                    showZoom: false,
                })
            );
        });

        sessionStorage.setItem('etapes', JSON.stringify(this.etapes));
    },
    watch: {
        etapes: {
            handler() {
                sessionStorage.setItem('etapes', JSON.stringify(this.etapes));
            },
            deep: true
        }
    },
};
</script>

<style scoped>
html,
body {
    margin: 0;
    padding: 0;
    height: 100%;
    display: flex;
    flex-direction: column;
}

#page {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100vh;
    width: 100vw;
}

#map {
    display: flex;
    flex-direction: column;
    height: 60vh;
    width: 100vw;
}

#btnSuivant {
    display: flex;
    position: absolute;
    height: 100px;
    width: 200px;
    bottom: 15px;
    justify-content: center;
}

#btnSuivant button {
    height: 60px;
    width: 100%;
    background-color: green;
    color: white;
    font-size: 20px;
    border: none;
    border-radius: 50px;
}

#formulaire {
    display: flex;
    flex-direction: column;
    height: 40vh;
    width: 100vw;
}

.form-container {
    background-color: white;
    height: 100%;
    width: 100%;
}

.form-container h2 {
    color: #4CAF50;
    text-align: center;
}

.stepContainer {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 70%;
    height: 60px;
    margin: auto;
}

.infoMap {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 15%;
}

.step {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 15px;
    border: 1px solid #ddd;
    border-radius: 10px;
    height: 80%;
    width: 100%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.deleteEtape {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 15%;
}

.add-step {
    display: flex;
    justify-content: center;
    margin-top: 10px;
    cursor: pointer;
    color: #4CAF50;
    font-size: 24px;
}

@media (min-width: 1000px) {

    #page {
        display: flex;
        flex-direction: row;
        align-items: center;
        height: 100vh;
        width: 100vw;
    }

    #formulaire {
        height: 100vh;
        width: 40vw;
    }

    #map {
        display: flex;
        flex-direction: column;
        height: 100vh;
        width: 60vw;
        background-color: green;
    }

    #btnSuivant {
        display: flex;
        position: absolute;
        left: 8%;
        bottom: 50px;
    }

    #btnSuivant button {
        height: 50px;
    }
}
</style>
