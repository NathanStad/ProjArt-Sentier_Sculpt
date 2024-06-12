<template>
    <div id="page" class="page">
        <div class="header">
            <a href="#creationSentier">
                <span class="material-symbols-outlined"> arrow_back_ios </span>
            </a>
            <h1>Création d’un sentier</h1>
            <div></div>
        </div>
        <div id="formulaire">
            <draggable v-model="etapes" item-key="id">
                <div class="form-container"></div>
                <template #item="{ element, index }">
                    <div class="stepContainer">
                        <div class="infoMap">
                            <p v-if="index === etapes.length - 1" class="last-etape">
                                <span class="material-symbols-outlined">
                                    location_on
                                </span>
                            </p>
                            <p v-else class="prec-etape">
                                <span class="material-symbols-outlined">
                                    radio_button_checked
                                </span>
                            </p>
                        </div>
                        <div class="step" @click="clickEtape(index)">
                            <p class="step-name">
                                {{ element.nom || "Etape " + (index + 1) }}
                            </p>
                            <div class="dragHandle">
                                <span class="material-symbols-outlined">
                                    drag_handle
                                </span>
                            </div>
                        </div>
                        <div class="deleteEtape">
                            <span v-if="index !== 0 && index !== 1" class="step-emoji" @click="delEtape(element)"><span
                                    class="material-symbols-outlined">
                                    close
                                </span></span>
                        </div>
                    </div>
                </template>
            </draggable>
            <div v-if="etapes.length < 5" class="add-step" @click="ajouteEtape">
                + Ajouter une étape
            </div>
            <div id="duree">
                Durée actuelle : {{ Math.floor(duree / 3600) }} h
                {{ Math.floor((duree % 3600) / 60) }} min
            </div>
        </div>
        <div id="partieInferieur">
            <div id="mapCreationDuSentier"></div>
            <div id="recenterDiv">
                <RecentrerBtnComponent @recenter="recenter" />
            </div>
        </div>
        <div id="btnSuivant">
            <button class="button" :disabled="disable" @click="terminerCreation">
                Terminer
            </button>
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
                    nom: "Étape 1",
                    description: "",
                    coordonnees: { long: null, lat: null },
                    photo: null,
                    pointInteret: [
                        { nom: "", photo: null },
                        { nom: "", photo: null },
                    ],
                },
                {
                    nom: "Étape 2",
                    description: "",
                    coordonnees: { long: null, lat: null },
                    photo: null,
                    pointInteret: [
                        { nom: "", photo: null },
                        { nom: "", photo: null },
                    ],
                },
                {
                    nom: "Étape 3",
                    description: "",
                    coordonnees: { long: null, lat: null },
                    photo: null,
                    pointInteret: [
                        { nom: "", photo: null },
                        { nom: "", photo: null },
                    ],
                },
            ],
            duree: "",
            markers: [],
            routeLayerId: `route-layer`,
            couleur: "#40680c",
            mapLoaded: false,
            disable: true,
            coordonnesRecenter: [6.700021, 46.602693],
            zoomRecenter: 8,
            etapeOk: [],
            mapLayer: null,
        };
    },

    methods: {
        loadMap(tour) {
            this.markers.forEach((marker) => marker.remove());
            const etapesInversees = tour.slice().reverse();
            etapesInversees.forEach((etape, index) => {
                const coordinate = [
                    etape.coordonnees.long,
                    etape.coordonnees.lat,
                ];
                const marker = new maplibregl.Marker({ color: this.couleur })
                    .setLngLat(coordinate)
                    .addTo(map);
                this.markers.push(marker);
                marker.setPopup(new maplibregl.Popup().setHTML(`${etape.nom}`));
                const markerElement = marker._element;
                const svgElement = markerElement.querySelector("svg");
                while (svgElement.firstChild) {
                    svgElement.removeChild(svgElement.firstChild);
                }

                if (index !== etapesInversees.length - 1) {
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

            if (tour.length > 1) {
                if (map.getLayer(this.routeLayerId)) {
                    map.removeLayer(this.routeLayerId);
                    map.removeSource(this.routeLayerId);
                }
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
                        this.mapLayer = map.getLayer(this.routeLayerId);
                    })
                    .catch((error) => {
                        console.error("Erreur lors de la requête:", error);
                    });
            }
        },
        afficheRoute(tour) {
            map.on("load", () => {
                this.mapLoaded = true;
                this.loadMap(tour);
            });
            if (this.mapLoaded) {
                this.loadMap(tour);
            }
        },
        calculerDistance(coord1, coord2) {
            const R = 6371e3; // Rayon de la Terre en mètres
            const φ1 = (coord1.lat * Math.PI) / 180; // Latitude du premier point en radians
            const φ2 = (coord2.lat * Math.PI) / 180; // Latitude du deuxième point en radians
            const Δφ = ((coord2.lat - coord1.lat) * Math.PI) / 180; // Différence de latitude en radians
            const Δλ = ((coord2.long - coord1.long) * Math.PI) / 180; // Différence de longitude en radians

            const a =
                Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) *
                Math.cos(φ2) *
                Math.sin(Δλ / 2) *
                Math.sin(Δλ / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            const distance = R * c; // Distance en mètres
            return distance;
        },
        calculeDureeTot() {
            let longueurTotal = 0;
            let dureeTotal = 0;
            let coordonneesPrec = null;

            for (let index = 0; index < this.etapes.length; index++) {
                const etape = this.etapes[index];
                if (coordonneesPrec !== null) {
                    longueurTotal += this.calculerDistance(
                        coordonneesPrec,
                        etape.coordonnees
                    );
                }
                coordonneesPrec = etape.coordonnees;
            }

            dureeTotal = this.calculerTempsMarche(longueurTotal);
            this.duree = dureeTotal;
            return { longueurTotal, dureeTotal };
        },
        calculerTempsMarche(distance) {
            const vitesseMarche = 5; // Vitesse moyenne de marche à pied en km/h
            const tempsSecondes = distance / ((vitesseMarche * 1000) / 3600); // Temps en secondes
            return tempsSecondes;
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
            const index = this.etapeOk.indexOf(etape);
            if (index > -1) {
                console.log("index", index)
                console.log(this.etapeOk);
                this.etapeOk.splice(index, 1);
            }
            const indexEtapes = this.etapes.indexOf(etape);
            if (indexEtapes > -1) {
                this.etapes.splice(indexEtapes, 1);
            }
        },
        verifierCoordonneesCompletes() {
            return this.etapes.every(
                (etape) =>
                    etape.coordonnees.lat !== null &&
                    etape.coordonnees.long !== null
            );
        },
        async calculeDureeTotale() {
            let longueurTotal = 0;
            let dureeTotal = 0;
            const etapes = JSON.parse(sessionStorage.getItem("etapes")) || [];

            for (let i = 0; i < etapes.length - 1; i++) {
                const coord1 = etapes[i].coordonnees;
                const coord2 = etapes[i + 1].coordonnees;

                if (
                    coord1.lat !== null &&
                    coord1.long !== null &&
                    coord2.lat !== null &&
                    coord2.long !== null
                ) {
                    const response = await fetch(
                        "https://api.openrouteservice.org/v2/directions/foot-hiking/geojson",
                        {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                Authorization:
                                    "5b3ce3597851110001cf6248cbe7a7b654c74537a20bd21243c00b7a",
                            },
                            body: JSON.stringify({
                                coordinates: [
                                    [coord1.long, coord1.lat],
                                    [coord2.long, coord2.lat],
                                ],
                            }),
                        }
                    );

                    const data = await response.json();
                    const route = data.features[0];
                    const distance = route.properties.segments[0].distance; // distance en mètres
                    const duration = route.properties.segments[0].duration; // durée en secondes

                    etapes[i].distanceToNext = distance;
                    etapes[i].durationToNext = duration;

                    longueurTotal += distance;
                    dureeTotal += duration;
                }
                etapes[i].ordre = i + 1;
            }

            // La dernière étape a une distance et une durée de 0
            etapes[etapes.length - 1].distanceToNext = 0;
            etapes[etapes.length - 1].durationToNext = 0;
            etapes[etapes.length - 1].ordre = etapes.length;

            sessionStorage.setItem("etapes", JSON.stringify(etapes));

            return { longueurTotal, dureeTotal };
        },
        async terminerCreation() {
            if (!this.verifierCoordonneesCompletes()) {
                console.error(
                    "Toutes les coordonnées des étapes doivent être remplies."
                );
                return;
            }

            console.log("envoie");
            const sentierCreationData = JSON.parse(
                sessionStorage.getItem("sentierCreation")
            );
            const etapesData = JSON.parse(sessionStorage.getItem("etapes"));

            if (!sentierCreationData || !etapesData) {
                console.error(
                    "Les données du sentier ou des étapes ne sont pas présentes dans le sessionStorage."
                );
                return;
            }

            const { longueurTotal, dureeTotal } =
                await this.calculeDureeTotale();
            const payload = {
                // ID Si exite
                nom: sentierCreationData.nomSentier,
                //
                nom: sentierCreationData.nomSentier,
                description: sentierCreationData.descriptionSentier,
                duree: dureeTotal,
                longueur: longueurTotal / 1000,
                localisation: sentierCreationData.lieu,
                criteres: sentierCreationData.criteres,
                motcles: sentierCreationData.motcles,
                etapes: etapesData.map((etape, index) => ({
                    id: etape.id,
                    nom: etape.nom,
                    description: etape.description,
                    latitude: etape.coordonnees.lat,
                    longitude: etape.coordonnees.long,
                    duree: etape.durationToNext || 0,
                    distance: etape.distanceToNext || 0,
                    ordre: etape.ordre || index + 1,
                    photo: etape.photo,
                    points_interet: etape.pointsInteret
                        ? etape.pointsInteret.map((poi) => ({
                            id: poi.id,
                            nom: poi.nom,
                            photo: poi.photo || null,
                        }))
                        : [],
                })),
                photo: sentierCreationData.photoSentier,
                theme_id: sentierCreationData.theme,
                difficulte_id: sentierCreationData.difficulte,
                archive: sentierCreationData.archive || 0,
            };
            // Conversion de la durée en entier
            payload.duree = parseInt(payload.duree);

            // Conversion des critères en tableau
            payload.criteres = Array.isArray(payload.criteres)
                ? payload.criteres
                : [payload.criteres];

            // Conversion des mots-clés en tableau
            payload.motcles = Array.isArray(payload.motcles)
                ? payload.motcles
                : [payload.motcles];

            console.log(payload);
            const formData = new FormData();
            formData.append("nom", payload.nom);
            formData.append("description", payload.description);
            formData.append("duree", payload.duree);
            formData.append("longueur", payload.longueur);
            formData.append("localisation", payload.localisation);

            // Erreur 

            formData.append("criteres", JSON.stringify(payload.criteres));
            formData.append("motcles", JSON.stringify(payload.motcles));

            //

            payload.etapes.forEach((etape, index) => {
                if (etape.id) {
                    formData.append(`etapes[${index}][id]`, etape.id);
                }
                formData.append(`etapes[${index}][nom]`, etape.nom);
                formData.append(
                    `etapes[${index}][description]`,
                    etape.description
                );
                formData.append(`etapes[${index}][latitude]`, etape.latitude);
                formData.append(`etapes[${index}][longitude]`, etape.longitude);
                formData.append(`etapes[${index}][duree]`, etape.duree);
                formData.append(`etapes[${index}][distance]`, etape.distance);
                formData.append(`etapes[${index}][ordre]`, etape.ordre);
                formData.append(`etapes[${index}][photo]`, etape.photo);
                etape.points_interet.forEach((poi, poiIndex) => {
                    if (poi.id) {
                        formData.append(
                            `etapes[${index}][points_interet][${poiIndex}][id]`,
                            poi.id
                        );
                    }
                    formData.append(
                        `etapes[${index}][points_interet][${poiIndex}][nom]`,
                        poi.nom
                    );
                    formData.append(
                        `etapes[${index}][points_interet][${poiIndex}][photo]`,
                        poi.photo
                    );
                });
            });

            formData.append("photo", payload.photo);
            formData.append("user_id", localStorage.getItem('userId'));
            formData.append("theme_id", payload.theme_id);
            formData.append("difficulte_id", payload.difficulte_id);
            formData.append("archive", payload.archive);

            const apiUrl = sessionStorage.getItem("update")
                ? `/update/sentier/${payload.id}`
                : "/submit/sentier";
            console.log(JSON.stringify(payload.criteres));

            // put pour update
            try {
                const response = await axios.post(apiUrl, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                console.log("Sentier traité avec succès:", response.data);
                sessionStorage.removeItem("sentierCreation");
                sessionStorage.removeItem("etapes");
                if (!sessionStorage.getItem("update"))
                    sessionStorage.removeItem("update");
                window.location.hash = `account`;
            } catch (error) {
                if (error.response && error.response.data) {
                    console.error(
                        "Erreur lors du traitement du sentier:",
                        error.response.data
                    );
                } else {
                    console.error(
                        "Erreur lors du traitement du sentier:",
                        error.message
                    );
                }
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
        disabled() {
            array.forEach((element) => { });
        },
        updateEtapeOk() {
            this.etapeOk = this.etapes.filter(
                (etape) =>
                    etape.coordonnees.lat !== null &&
                    etape.coordonnees.long !== null
            );
        }
    },
    mounted() {
        // console.log(JSON.parse(sessionStorage.getItem("etapes")));
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
        // this.showTours(this.etapes);
        this.updateEtapeOk();
        this.afficheRoute(this.etapeOk);
        this.calculeDureeTot();
        this.disable = !this.verifierCoordonneesCompletes();
        sessionStorage.setItem("etapes", JSON.stringify(this.etapes));
    },
    watch: {
        etapes: {
            handler() {
                this.updateEtapeOk();
                this.loadMap(this.etapeOk);
                sessionStorage.setItem("etapes", JSON.stringify(this.etapes));
                this.calculeDureeTot();
                this.disable = !this.verifierCoordonneesCompletes();
            },
            deep: true,
        },
    },
};
</script>

<style scoped>
h1 {
    text-align: center;
}

span {
    color: var(--color-text-secondary);
}

#page {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100vh;
    width: 100vw;
}

#partieInferieur {
    height: 60vh;
    width: 100%;
    position: relative;
}

#recenterDiv {
    right: -7%;
    top: 25%;
}

#mapCreationDuSentier {
    display: flex;
    flex-direction: column;
    height: 110%;
    width: 100vw;
    transform: translate(-8.5%);
}

#btnSuivant {
    display: flex;
    position: fixed;
    height: 100px;
    width: 200px;
    bottom: 0%;
    justify-content: center;
    left: 50%;
    transform: translate(-50%);
}

#btnSuivant button {
    height: 60px;
    width: 100%;
    background-color: var(--secondary);
    color: white;
    font-size: 20px;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    z-index: 410;
}

#formulaire {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 31vh;
    width: 100%;
    padding-bottom: 5%;
    position: relative;
}

#formulaire>div {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    gap: 15px;
    width: 100%;
}

.stepContainer {
    display: flex;
    align-items: center;
    width: 100%;
    height: 100%;
    gap: 5px;
    padding: 0px 20px;
}

.stepContainer:nth-of-type(5) {
    margin-bottom: 10%;
}

.stepContainer .prec-etape,
.stepContainer .prec-etape span,
.stepContainer .last-etape span {
    font-size: 1.4rem;
    display: flex;
    position: relative;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.stepContainer .prec-etape::after,
.stepContainer .prec-etape::before {
    content: "";
    display: block;
    background: var(--color-text-secondary);
    width: 5px;
    height: 5px;
    position: absolute;
    border-radius: var(--border-radius-full);
}

.stepContainer .prec-etape span::before,
.stepContainer .last-etape span::before {
    content: "";
    display: block;
    background: var(--secondary);
    width: 10px;
    height: 10px;
    position: absolute;
    border-radius: var(--border-radius-full);
}

.stepContainer .last-etape span::before {
    width: 8px;
    height: 8px;
    top: 5px;
}

.stepContainer .prec-etape span::before {
    top: 4.5px;
}

.stepContainer .prec-etape::before {
    top: 30px;
}

.stepContainer .prec-etape::after {
    top: 45px;
}

.stepContainer .last-etape span {
    font-size: 1.6rem;
}

.stepContainer .deleteEtape span {
    font-size: 1.6rem;
    cursor: pointer;
}

.stepContainer .step {
    display: flex;
    justify-content: space-between;
    width: 75%;
    align-items: center;
    padding: 5px 15px;
    border-radius: var(--border-radius-small);
    box-shadow: var(--box-shadow-light);
}

.add-step {
    font-size: 1.3rem;
    color: var(--color-text-secondary);
    padding: 15px 23px;
    cursor: pointer;
}

#duree {
    position: absolute;
    bottom: 0;
    z-index: 3;
    color: var(--color-text-secondary);
    padding-bottom: 2px;
    width: fit-content !important;
    left: 0%;
}

form {
    width: 100%;
}

.dragHandle {
    cursor: grab;
}

@media only screen and (min-width: 900px) {
    .page {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 1fr 3fr;
        width: 100%;
        height: 90vh;
    }

    #btnSuivant {
        width: 50%;
    }

    .header {
        grid-column: 1/2;
        grid-row: 1/2;
    }

    #formulaire {
        grid-column: 1/2;
        grid-row: 2/3;
        margin-left: 15%;
    }

    #partieInferieur {
        grid-column: 2/3;
        grid-row: 1/3;
        height: 100%;
    }

    #mapCreationDuSentier {
        width: 103%;
        left: 0;
        height: 115%;
        transform: translate(2%, -5%);
    }

    #recenterDiv {
        right: -3%;
    }

    #formulaire>div {
        gap: 40px;
    }
}
</style>
