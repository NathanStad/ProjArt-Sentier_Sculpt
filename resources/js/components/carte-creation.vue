<template>
    <div id="page">
        <div id="formulaire">
            <div class="form-container">
                <h1>Création d'un sentier</h1>
                <draggable v-model="etapes" item-key="id">
                    <div class="form-container"></div>
                    <template #item="{ element, index }">
                        <div class="stepContainer">
                            <div class="infoMap">
                                <p v-if="index === etapes.length - 1">
                                    <span class="material-symbols-outlined">
                                        radio_button_unchecked
                                    </span>
                                </p>
                                <p v-else>
                                    <span class="material-symbols-outlined">
                                        radio_button_checked
                                    </span>
                                </p>
                            </div>
                            <div class="step" @click="clickEtape(index)">
                                <span class="step-name">{{
                                    element.nom || "Etape " + (index + 1)
                                    }}</span>
                                <div class="dragHandle">
                                    <span class="material-symbols-outlined">
                                        drag_handle
                                    </span>
                                </div>
                            </div>
                            <div class="deleteEtape">
                                <span v-if="index !== 0 && index !== 1" class="step-emoji"
                                    @click="delEtape(element)"><span class="material-symbols-outlined">
                                        close
                                    </span></span>
                            </div>
                        </div>
                    </template>
                </draggable>
                <div v-if="etapes.length < 5" class="add-step" @click="ajouteEtape">
                    + ajouter une étape
                </div>
            </div>
        </div>
        <div id="partieInferieur">
            <div id="mapCreationDuSentier"></div>
            <div id="recenterDiv">
                <RecentrerBtnComponent @recenter="recenter" />
            </div>
        </div>
        <div id="btnSuivant">
            <button class="button" @click="terminerCreation" >Suivant</button>
        </div>
    </div>
</template>

<script>
import draggable from "vuedraggable";
import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";
import EtapeComponent from "./elements/etapeComponent.vue";
import RecentrerBtnComponent from "./elements/recentrerBtnComponent.vue";

import axios from "axios";

let map;

export default {
    name: "CarteCreation",
    components: {
        draggable,
        EtapeComponent,
        RecentrerBtnComponent,
    },

    data() {
        return {
            etapes: JSON.parse(sessionStorage.getItem("etapes")) || [
                {
                    nom: "nom 1",
                    description: "la description",
                    coordonnees: {
                        long: 6.6832837302582675,
                        lat: 46.575054030719826,
                    },
                    photo: null,
                    pointInteret: [
                        { nom: "", photo: null },
                        { nom: "", photo: null },
                    ],
                },
                {
                    nom: "nom 2",
                    description: "la descr",
                    coordonnees: { long: null, lat: null },
                    photo: null,
                    pointInteret: [
                        { nom: "", photo: null },
                        { nom: "", photo: null },
                    ],
                },
                {
                    nom: "nom 3",
                    description: "la descr",
                    coordonnees: {
                        long: 6.6832837302582675,
                        lat: 46.585054030719826,
                    },
                    photo: null,
                    pointInteret: [
                        { nom: "", photo: null },
                        { nom: "", photo: null },
                    ],
                },
            ],
            oldIndex: "",
            newIndex: "",
            markers: [],
            routeLayerId: "",
            couleur: "#40680c",
            mapLoaded: false,
            disable: true,
            coordonnesRecenter: [6.700021, 46.602693],
            zoomRecenter: 8,
        };
    },

    methods: {
        loadMap(tour) {
            if (map.getLayer(this.routeLayerId)) {
                map.removeLayer(this.routeLayerId);
                map.removeSource(this.routeLayerId);
            }
            if (tour.length > 1) {
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
                            coordinates: tour.map((etape) => [
                                etape.coordonnees.long,
                                etape.coordonnees.lat,
                            ]),
                        }),
                    }
                )
                    .then((response) => response.json())
                    .then((responseData) => {
                        const coordinates =
                            responseData.features[0].geometry.coordinates;
                        map.addLayer({
                            id: this.routeLayerId,
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
                                "line-color": `${this.couleur}`,
                                "line-width": 5,
                            },
                        });
                    })
                    .catch((error) => {
                        console.error("Erreur lors de la requête:", error);
                    });
            }
        },
        afficheRoute(tour) {
            this.routeLayerId = `route-layer-1`;
            const etapesInversees = tour.slice().reverse();
            etapesInversees.forEach((etape, index) => {
                const coordinate = [
                    etape.coordonnees.long,
                    etape.coordonnees.lat,
                ];
                const marker = new maplibregl.Marker({ color: this.couleur })
                    .setLngLat(coordinate)
                    .addTo(map);
                marker.getElement().setAttribute("data-id", etape.id);
                this.markers.push(marker);
                marker.setPopup(new maplibregl.Popup().setHTML(`${etape.nom}`));
                const markerElement = marker._element;
                const svgElement = markerElement.querySelector("svg");
                while (svgElement.firstChild) {
                    svgElement.removeChild(svgElement.firstChild);
                }

                if (index !== 0) {
                    if (svgElement) {
                        const circle = document.createElementNS(
                            "http://www.w3.org/2000/svg",
                            "circle"
                        );
                        circle.setAttribute("cx", "12");
                        circle.setAttribute("cy", "27");
                        circle.setAttribute("r", "12");
                        circle.setAttribute("fill", this.couleur);
                        svgElement.appendChild(circle);
                    }
                } else {
                    if (svgElement) {
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
                            .cls-1 {
                                display: none;
                            }

                            .cls-1, .cls-2, .cls-3, .cls-4 {
                                stroke-width: 0px;
                            }

                            .cls-1, .cls-4 {
                                fill: #40680c;
                            }

                            .cls-2 {
                                isolation: isolate;
                                opacity: .5;
                            }

                            .cls-2, .cls-3 {
                                fill: #fff;
                            }
                            </style>
                            </defs>
                            <path class="cls-4" d="M36.6,8.6c-3.1-3.1-7.1-4.8-11.7-4.8s-8.6,1.7-11.7,4.8c-3.3,3.3-4.8,7.3-4.8,12.4s1.3,7.1,4.2,11.3c2.7,4.2,6.9,9,12.6,14c5.7-5.2,9.8-9.8,12.6-14,2.7-4.2,4-8,4-11.3-.2-5-1.9-9-5.2-12.4ZM33.7,20.9c0,.6,0,1.1-.2,1.6,0,.2,0,.4,0,.5,0,.4-.3.8-.5,1.2-1.4,2.9-4.5,4.8-7.9,4.8s-8.6-3.8-8.8-8.3v-.8c0-1.7.6-3.2,1.5-4.5.1-.2.3-.4.5-.6s.2-.3.4-.4c0,0,.2-.2.3-.3,1.6-1.6,3.8-2.6,6.2-2.6,4.6,0,8.3,3.5,8.8,8v1.3h-.2Z"/>
                            <path class="cls-2" d="M-9.3,5.9v.5c0,.6,0,1.1-.2,1.6,0,.2,0,.4,0,.5,0,.4-.3.8-.5,1.2-1.4,2.9-4.5,4.8-7.9,4.8s-8.6-3.8-8.8-8.3v-.8c0-1.6.6-3.2,1.5-4.5.1-.2.3-.4.5-.6s.2-.3.4-.4c0,0,.2-.2.3-.3,1.6-1.6,3.8-2.6,6.2-2.6,4.6,0,8.3,3.5,8.8,8v.8h-.2Z"/>
                            <circle class="cls-3" cx="25" cy="20" r="9"/>
                            <g>
                                                <path class="cls-4" d="M24.1,18c-.3.3-.6.5-.8.7-.2.2-.3.5-.3.8,0,.5-.1,1-.2,1.4,0,.4-.3.5-.6.5-.3,0-.5-.3-.5-.6,0-.7.2-1.4.3-2.2,0-.2.2-.5.4-.6.5-.4,1-.8,1.5-1.2.5-.4,1-.6,1.6-.6.5,0,.9.2,1.1.6.2.5.4,1,.6,1.5.2.5.4.9.9,1.1.4.2.8.4,1.2.6.3.2.5.4.3.7-.1.3-.4.4-.8.2-.6-.3-1.3-.6-1.9-.9-.3-.2-.5-.5-.8-.8-.1.5-.2,1.1-.4,1.7,0,0,0,.2.1.3.9,1.4,1.8,2.8,2.7,4.2.2.3.2.6,0,.9-.2.3-.4.4-.8.3-.2,0-.4-.2-.5-.3-1.2-1.8-2.4-3.7-3.5-5.6,0,0,0-.2,0-.4.1-.7.3-1.3.4-2,0,0,0-.2,0-.4Z"/>
                                                <path class="cls-4" d="M23.4,21.7c.3.4.6.8.9,1.2,0,0,0,.1,0,.2-.1.4-.2.8-.4,1.1-.4.7-1,1.3-1.5,2-.3.4-.7.5-1,.2-.3-.2-.4-.7,0-1,.2-.2.3-.5.5-.7.7-.8,1.1-1.7,1.3-2.7,0,0,0-.1,0-.2,0,0,0,0,0-.1Z"/>
                                                <path class="cls-4" d="M24.7,14.4c0-.7.6-1.3,1.3-1.3.7,0,1.3.6,1.3,1.3,0,.7-.6,1.3-1.3,1.3-.7,0-1.3-.6-1.3-1.3Z"/>
                                            </g>
                                            <path class="cls-1" d="M21.3,20.2c0-1.1,0-2.3,0-3.4,0-1,.7-1.8,1.6-2,.5,0,.9,0,1.3.2,1.8,1.2,3.6,2.3,5.3,3.5,1.2.8,1.2,2.6,0,3.3-1.8,1.2-3.5,2.3-5.3,3.5-.6.4-1.3.4-1.9,0-.7-.4-1-1-1-1.8,0-1.2,0-2.3,0-3.5ZM23.3,23.7c1.7-1.1,3.5-2.3,5.2-3.4-1.7-1.1-3.4-2.3-5.2-3.4v6.8Z"/>
                                            </svg>`;
                            svgElement.innerHTML = point;
                        }
                    }
                }
            });

            if (this.mapLoaded) {
                this.loadMap(tour);
            }

            map.on("load", () => {
                this.mapLoaded = true;
                this.loadMap(tour);
            });
        },
        calculerDistance(coord1, coord2) {
            const R = 6371e3; // Rayon de la Terre en mètres
            const φ1 = coord1.lat * Math.PI / 180; // Latitude du premier point en radians
            const φ2 = coord2.lat * Math.PI / 180; // Latitude du deuxième point en radians
            const Δφ = (coord2.lat - coord1.lat) * Math.PI / 180; // Différence de latitude en radians
            const Δλ = (coord2.long - coord1.long) * Math.PI / 180; // Différence de longitude en radians

            const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            const distance = R * c; // Distance en mètres
            return distance;
        },
        calculerTempsMarche(distance) {
            const vitesseMarche = 5; // Vitesse moyenne de marche à pied en km/h
            const tempsSecondes = distance / (vitesseMarche * 1000 / 3600); // Temps en secondes
            return tempsSecondes;
        },

        showTours(tour) {
            this.markers.forEach((marker) => marker.remove());
            const etapeOk = [];
            tour.forEach((etape, index) => {
                if (
                    etape.coordonnees.lat === null ||
                    etape.coordonnees.long === null
                ) {
                    return;
                } else {
                    etapeOk.push(etape);
                }
            });
            if (etapeOk[0]) {
                map.setCenter([
                    etapeOk[etapeOk.length - 1].coordonnees.long,
                    etapeOk[etapeOk.length - 1].coordonnees.lat,
                ]);
                map.setZoom(12);
                this.zoomRecenter = 12;
                this.coordonnesRecenter = [
                    etapeOk[etapeOk.length - 1].coordonnees.long,
                    etapeOk[etapeOk.length - 1].coordonnees.lat,
                ];
                this.afficheRoute(etapeOk);
            } else {
                this.coordonnesRecenter = [6.700021, 46.602693];
                this.zoomRecenter = 8.5;
            }
        },
        ajouteEtape() {
            this.etapes.push({
                nom: "",
                description: "",
                coordonnees: { long: null, lat: null },
                photo: null,
                pointInteret: [
                    { nom: "", photo: null },
                    { nom: "", photo: null },
                ],
            });
        },
        clickEtape(index) {
            const hash = `creationStep-${index + 1}`;
            window.location.hash = hash;
        },
        delEtape(etape) {
            const index = this.etapes.indexOf(etape);
            if (index > -1) {
                this.etapes.splice(index, 1);
            }
        },
        terminerCreation() {
            // Récupérer les données du sessionStorage
            console.log('envoie');
            const sentierCreationData = JSON.parse(
                sessionStorage.getItem("sentierCreation")
                );
                const etapesData = JSON.parse(sessionStorage.getItem("etapes"));
                
                // Vérifier si les données existent
                if (!sentierCreationData || !etapesData) {
                    console.error(
                        "Les données du sentier ou des étapes ne sont pas présentes dans le sessionStorage."
                        );
                        return;
                        }
                    console.log(etapesData);
            let longueurTotal = 0
            let dureeTotal = 0
            let cordonnePrec = null
            for (let index = 0; index < etapesData.length; index++) {
                const element = etapesData[index];
                if (cordonnePrec != null) {
                    longueurTotal += this.calculerDistance(cordonnePrec,element.coordonnees)
                } else {
                    cordonnePrec = element.coordonnees
                }
            }
            dureeTotal = this.calculerTempsMarche(longueurTotal)
            if (sessionStorage.getItem("update")) {
                // Envoyer les données au backend
                axios
                    .post("/update-sentier", {
                        nom: sentierCreationData.nomSentier,
                        description: sentierCreationData.descriptionSentier,
                        duree: dureeTotal,
                        longueur: longueurTotal,
                        localisation: sentierCreationData.localisation,
                        criteres: sentierCreationData.criteres,
                        motcles: sentierCreationData.motcles,
                        etapes: etapesData,
                        photo: sentierCreationData.photoSentierName,
                        theme_id: sentierCreationData.theme,
                        user_id: localStorage.getItem("userId"),
                        difficulte_id: sentierCreationData.difficulte,
                    })
                    .then((response) => {
                        console.log(
                            "Sentier mis à jour avec succès:",
                            response.data
                        );
                        // Effacer les données du sessionStorage après avoir réussi à créer le sentier
                        sessionStorage.removeItem("sentierCreation");
                        sessionStorage.removeItem("etapes");
                        sessionStorage.removeItem("update");
                        // Redirigez l'utilisateur
                        window.location.hash = `account`;
                    })
                    .catch((error) => {
                        console.error(
                            "Erreur lors de la mise à jour du sentier:",
                            error
                        );
                    });
            } else {
                // Envoyer les données au backend
                axios
                    .post("/submit-sentier", {
                        nom: sentierCreationData.nomSentier,
                        description: sentierCreationData.descriptionSentier,
                        duree: dureeTotal,
                        longueur: longueurTotal,
                        localisation: sentierCreationData.localisation,
                        criteres: sentierCreationData.criteres,
                        motcles: sentierCreationData.motcles,
                        etapes: etapesData,
                        photo: sentierCreationData.photoSentierName,
                        theme_id: sentierCreationData.theme,
                        user_id: localStorage.getItem("userId"),
                        difficulte_id: sentierCreationData.difficulte,
                    })
                    .then((response) => {
                        console.log("Sentier créé avec succès:", response.data);
                        // Effacer les données du sessionStorage après avoir réussi à créer le sentier
                        sessionStorage.removeItem("sentierCreation");
                        sessionStorage.removeItem("etapes");
                        // Redirigez l'utilisateur
                        window.location.hash = `account`;
                    })
                    .catch((error) => {
                        console.error(
                            "Erreur lors de la création du sentier:",
                            error
                        );
                    });
            }
        },
        recenter() {
            map.flyTo({
                center: this.coordonnesRecenter,
                zoom: this.zoomRecenter,
                curve: 1,
                easing(t) {
                    return t;
                },
            });
        },
        disabled(){
            array.forEach(element => {
                
            });
        }
    },
    mounted() {
        map = new maplibregl.Map({
            container: "mapCreationDuSentier",
            style: "https://api.maptiler.com/maps/de2783ff-b0c6-4f3d-8d9a-4bd8d5051450/style.json?key=kzJF26jznLlv3rUUVUK7",
            center: [6.700021, 46.602693],
            zoom: 9.2,
        });

        map.on("load", () => {
            map.addControl(
                new maplibregl.NavigationControl({
                    showCompass: false,
                    showZoom: true,
                })
            );
        });
        this.showTours(this.etapes);
        console.log(this.etapes);
        sessionStorage.setItem("etapes", JSON.stringify(this.etapes));
    },
    watch: {
        etapes: {
            handler() {
                this.showTours(this.etapes);
                sessionStorage.setItem("etapes", JSON.stringify(this.etapes));
            },
            deep: true,
        },
    },
};
</script>

<style scoped>
h1{
    text-align: center;
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

#page {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100vh;
    width: 100vw;
}

#partieInferieur {
    margin-top: 80% ;
    height: 60vh;
    width: 100%;
    position: relative;
    margin-bottom: 50%
}

#mapCreationDuSentier {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
}

#btnSuivant {
    display: flex;
    position: fixed;
    height: 100px;
    width: 200px;
    bottom: 10%;
    justify-content: center;
    left: 50%;
    transform: translate(-50%);
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
    width: 100%;
    position: absolute;
    top: 5%;
    left: 50%;
    transform: translate(-50%);
}

.form-container {
    background-color: white;
    height: 100%;
    width: 100%;
    overflow-y: scroll;
    padding-bottom: 30px;
}

.form-container h2 {
    color: #4caf50;
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
    color: #4caf50;
    font-size: 24px;
}

.dragHandle {
    cursor: move;
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
