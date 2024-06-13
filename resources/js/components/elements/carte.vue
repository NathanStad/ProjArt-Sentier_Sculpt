<template>
    <div id="map">
        <div id="formContainer">
            <form id="form" action=""></form>
        </div>
    </div>
</template>

<script>
import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";
import Sortable from "sortablejs";

export default {
    mounted() {
        // Le cycle de vie de Vue.js indique que ce bloc de code s'exécute lorsque le composant est monté dans le DOM.
        const map = new maplibregl.Map({
            // Création d'une nouvelle instance de carte MapLibre GL.
            container: "map", // L'élément HTML où la carte sera insérée.
            // URL du style de carte à utiliser.
            style: "https://api.maptiler.com/maps/de2783ff-b0c6-4f3d-8d9a-4bd8d5051450/style.json?key=kzJF26jznLlv3rUUVUK7",
            center: [6.700021, 46.602693], // Coordonnées initiales centrées sur la carte.
            zoom: 9.2, // Niveau de zoom initial.
        });

        map.on("load", () => {
            // Événement déclenché lorsque la carte est chargée.
            map.addControl(
                new maplibregl.NavigationControl({
                    showCompass: false,
                    showZoom: false,
                })
            );
            // Initialisation d'un objet pour stocker les coordonnées des points ajoutés par l'utilisateur.
            const usrCreator = {
                coordinates: [],
            };

            function addPoint(data) {
                // Fonction pour ajouter un point à la carte.
                map.on("click", (e) => {
                    // Événement déclenché lorsqu'un utilisateur clique sur la carte.
                    // Logique pour créer un marqueur et demander à l'utilisateur si il souhaite ajouter un point ici.
                    // Si oui, ajoutez le point aux données et mettez à jour la forme de saisie.
                    // Si non, supprimez simplement le div de confirmation.
                    const lngLat = e.lngLat;
                    const confirmationDiv = document.createElement("div");
                    confirmationDiv.style.position = "fixed";
                    confirmationDiv.style.top = "50%";
                    confirmationDiv.style.left = "50%";
                    confirmationDiv.style.transform = "translate(-50%, -50%)";
                    confirmationDiv.style.background = "white";
                    confirmationDiv.style.padding = "20px";
                    confirmationDiv.style.border = "2px solid black";
                    confirmationDiv.style.borderRadius = "10px";
                    confirmationDiv.innerHTML = `
                <p>Voulez-vous ajouter une étape à cette position ?</p>
                <button id="confirmYes">Oui</button>
                <button id="confirmNo">Non</button>
      `;

                    document.body.appendChild(confirmationDiv);

                    document
                        .getElementById("confirmYes")
                        .addEventListener("click", () => {
                            document.body.removeChild(confirmationDiv);
                            data.coordinates.push([lngLat.lng, lngLat.lat]);
                            afficheRoute(data);
                            updateCoordinateForm(data.coordinates);
                        });

                    document
                        .getElementById("confirmNo")
                        .addEventListener("click", () => {
                            document.body.removeChild(confirmationDiv);
                        });

                    console.log(usrCreator);
                });
            }

            function updateCoordinateForm(coordinates) {
                // Met à jour la forme de saisie avec les nouvelles coordonnées.
                // Logique pour mettre à jour la forme de saisie avec les nouvelles coordonnées.
                // Utilise Sortable.js pour permettre la réorganisation des points.
                const form = document.getElementById("formContainer");
                form.innerHTML = ""; // Clear existing inputs
                coordinates.forEach((coordinate, index) => {
                    const inputContainer = document.createElement("div");
                    inputContainer.style.display = "flex";
                    inputContainer.style.alignItems = "center";
                    inputContainer.id = `input-container-${index}`;

                    const input = document.createElement("input");
                    input.placeholder = `Étape ${index + 1}`;
                    input.value = `${coordinate[0]},${coordinate[1]}`;
                    input.classList.add("form-input", "coordinate-input");
                    input.dataset.index = index;

                    const dragHandle = document.createElement("div");
                    dragHandle.classList.add("drag-handle");

                    inputContainer.appendChild(input);
                    inputContainer.appendChild(dragHandle);
                    form.appendChild(inputContainer);
                });

                // Initialize Sortable.js
                const sortable = new Sortable(form, {
                    animation: 150,
                    handle: ".drag-handle",
                    onEnd: function (evt) {
                        // Update data.coordinates based on new order
                        const newCoordinates = [];
                        const coordinateInputs =
                            document.querySelectorAll(".coordinate-input");
                        coordinateInputs.forEach((input) => {
                            const [lng, lat] = input.value
                                .split(",")
                                .map(Number);
                            newCoordinates.push([lng, lat]);
                        });

                        usrCreator.coordinates = newCoordinates;
                        console.log(
                            "Updated coordinates:",
                            usrCreator.coordinates
                        );
                        afficheRoute(usrCreator);
                    },
                });
            }

            function afficheRoute(data) {
                // Affiche la route basée sur les coordonnées fournies.
                // Logique pour afficher les marqueurs et calculer la durée totale de la route.
                document
                    .querySelectorAll(".maplibregl-marker")
                    .forEach((marker) => marker.remove());
                const routeLayerId = "route-layer";
                if (map.getLayer(routeLayerId)) {
                    map.removeLayer(routeLayerId);
                    map.removeSource(routeLayerId);
                }

                data.coordinates.forEach((coordinate, index) => {
                    const colors = ["red", "blue", "green", "gray", "purple"];
                    const marker = new maplibregl.Marker({
                        color: colors[index % colors.length],
                    })
                        .setLngLat(coordinate)
                        .addTo(map);

                    const popup = new maplibregl.Popup({
                        offset: 25,
                        closeButton: false,
                        closeOnClick: false,
                    }).setText(`Étape ${index + 1}`);

                    marker.setPopup(popup);

                    marker.getElement().addEventListener("click", () => {
                        if (popup.isOpen()) {
                            popup.remove();
                        } else {
                            popup.addTo(map);
                        }
                    });
                });

                if (data.coordinates.length > 1) {
                    fetch(
                        "https://api.openrouteservice.org/v2/directions/foot-hiking/geojson",
                        {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                Authorization:
                                    "5b3ce3597851110001cf6248cbe7a7b654c74537a20bd21243c00b7a",
                            },
                            body: JSON.stringify(data),
                        }
                    )
                        .then((response) => response.json())
                        .then((responseData) => {
                            const coordinates =
                                responseData.features[0].geometry.coordinates;
                            const duration =
                                responseData.features[0].properties.segments.reduce(
                                    (total, segment) =>
                                        total + segment.duration,
                                    0
                                );
                            console.log("duration", duration / 60);

                            const routeId = `route-${data.coordinates[0][0]}-${data.coordinates[0][1]}`;

                            if (map.getLayer(routeId)) {
                                map.removeLayer(routeId);
                                map.removeSource(routeId);
                            }

                            map.addLayer({
                                id: routeId,
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
                                    "line-color": "#00AF00",
                                    "line-width": 5,
                                },
                            });

                            const durationElement =
                                document.createElement("div");
                            durationElement.innerHTML = `<h3>Durée totale: ${(
                                duration / 60
                            ).toFixed(2)} minutes</h3>`;
                            durationElement.style.position = "absolute";
                            durationElement.style.top = "10px";
                            durationElement.style.left = "10px";
                            durationElement.style.backgroundColor = "white";
                            durationElement.style.padding = "10px";
                            durationElement.style.borderRadius = "5px";
                            document.body.appendChild(durationElement);
                        })
                        .catch((error) => {
                            console.error("Erreur lors de la requête:", error);
                        });
                }

                // Automatically update the form after route is displayed
                updateCoordinateForm(data.coordinates);
            }

            let userMarker;
            let userCoordinates = [];

            function showUserLocation() {
                // Affiche la localisation de l'utilisateur sur la carte.
                // Logique pour obtenir la localisation de l'utilisateur et afficher un marqueur.
                if ("geolocation" in navigator) {
                    console.log("La géolocalisation est disponible.");

                    const button = document.createElement("button");
                    button.innerText = "You";
                    button.style.position = "fixed";
                    button.style.bottom = "10px";
                    button.style.left = "10px";
                    document.body.appendChild(button);

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            userCoordinates = [
                                position.coords.longitude,
                                position.coords.latitude,
                            ];

                            userMarker = new maplibregl.Marker({
                                color: "pink",
                            })
                                .setLngLat(userCoordinates)
                                .addTo(map);

                            button.addEventListener("click", () => {
                                map.setCenter(userCoordinates);
                            });

                            watchUserLocation();
                        },
                        (error) => {
                            console.error("Erreur de géolocalisation:", error);
                        }
                    );
                } else {
                    console.error(
                        "La géolocalisation n'est pas disponible dans ce navigateur."
                    );
                }
            }

            function watchUserLocation() {
                // Suivi en temps réel de la localisation de l'utilisateur.
                // Logique pour surveiller la localisation de l'utilisateur et mettre à jour le marqueur en conséquence.
                if ("geolocation" in navigator) {
                    navigator.geolocation.watchPosition(
                        (position) => {
                            userCoordinates = [
                                position.coords.longitude,
                                position.coords.latitude,
                            ];

                            userMarker.setLngLat(userCoordinates);
                        },
                        (error) => {
                            console.error(
                                "Erreur de suivi de géolocalisation:",
                                error
                            );
                        }
                    );
                } else {
                    console.error(
                        "La géolocalisation n'est pas disponible dans ce navigateur."
                    );
                }
            }
            /* Définition des parcours */
            const tousLesTours = [
                {
                    // Neuchâtel
                    coordinates: [
                        [6.929274, 46.991195], // Neuchâtel
                        [6.917862, 47.025151], // Hauterive
                        [6.894834, 47.06811], // Marin-Epagnier
                        [6.911139, 47.050413], // St-Blaise
                    ],
                },
                {
                    // Jura
                    coordinates: [
                        [7.358511, 47.34608], // Delémont
                        [7.382827, 47.344445], // Courroux
                        [7.341946, 47.341192], // Courrendlin
                        [7.406383, 47.32757], // Courtételle
                    ],
                },

                {
                    // Lausanne
                    coordinates: [
                        [6.632273, 46.519653], // Lausanne
                        [6.645889, 46.523493], // Pully
                        [6.655984, 46.548206], // Epalinges
                        [6.672305, 46.531267], // Paudex
                    ],
                },
            ];

            function showTours(tours) {
                // Affiche les parcours spécifiés.
                // Logique pour afficher les parcours sur la carte.
                tours.forEach((tour) => {
                    afficheRoute(tour);
                });
            }
            // Appel des fonctions pour initialiser la carte, afficher la localisation de l'utilisateur, et ajouter un point.
            showUserLocation();

            addPoint(usrCreator);
        });
    },
};
</script>

<style scoped>
#map {
    width: 100%;
    height: 100vh;
    background-color: blue;
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

.route-settings {
    padding: 5px;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: 0.4s;
    transition: 0.4s;
}

input:checked + .slider {
    background-color: #2196f3;
}

input:focus + .slider {
    box-shadow: 0 0 1px #2196f3;
}

input:checked + .slider:before {
    -webkit-transform: translateX(13px);
    -ms-transform: translateX(13px);
    transform: translateX(13px);
}

.slider.round {
    border-radius: 17px;
}

.slider.round:before {
    border-radius: 50%;
}

#formContainer {
    position: absolute;
    top: 10px;
    left: 10px;
    display: block;
    width: 300px;
    height: auto;
    background-color: red;
    z-index: 1000;
    padding: 10px;
    box-sizing: border-box;
}

.form-input {
    display: block;
    margin-top: 10px;
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
}

.drag-handle {
    display: inline-block;
    width: 30px;
    height: 30px;
    background-color: gray;
    cursor: grab;
    margin-left: 10px;
    vertical-align: middle;
}
</style>
