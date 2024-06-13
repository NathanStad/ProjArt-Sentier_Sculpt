<template>
    <div id="list-etapes">
        <div class="header">
            <a :href="`#sentier-${props.Id}`">
                <span class="material-symbols-outlined"> arrow_back_ios </span>
            </a>
        </div>
        <div id="map"></div>
        <div id="overlayEtape">
            <EtapeComponent
                ref="etapeComponent"
                v-if="sentier"
                :etapes="sentier.etapes"
                @active-slide-key-change="handleActiveSlideKeyChange"
            ></EtapeComponent>
            <div v-else>erreur du widget</div>
        </div>
        <div id="recenterDiv">
            <RecentrerBtnComponent @recenter="recenterMap" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, defineProps, defineEmits } from "vue";
import EtapeComponent from "@/components/elements/etapeComponent.vue";
import RecentrerBtnComponent from "@/components/elements/recentrerBtnComponent.vue";
import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";
import axios from "axios";

const props = defineProps({
    Id: {
        type: Number,
        required: true,
    },
});
const emit = defineEmits([]);

const sentier = ref(null);
const hash = ref(window.location.hash.split("-")[1] - 1);
const etapeActive = ref(0);
const markers = ref([]);
const coordonnesRecenter = ref([]);

let map;

const fetchSentier = async () => {
    if (props.Id !== "") {
        try {
            const response = await axios.get(`/data-sentier-${props.Id}`);
            sentier.value = response.data;
            console.log(sentier.value);
        } catch (error) {
            console.error("Error fetching sentiers:", error);
        }
    }
};

const removeMarkers = () => {
    markers.value.forEach((marker) => marker.remove());
    markers.value = [];
};

const afficheRoute = (sentier) => {
    removeMarkers();

    const routeLayerId = `route-layer${sentier.id}`;
    const couleur = "#40680c";
    const etapesInversees = sentier.etapes.slice().reverse();
    if (etapeActive.value === 0) {
        const idEtapeActive = sentier.etapes[0].id;
        etapeActive.value = idEtapeActive;
    }

    etapesInversees.forEach((etape) => {
        coordonnesRecenter.value = [etape.longitude, etape.latitude];
        const marker = new maplibregl.Marker({ color: couleur })
            .setLngLat(coordonnesRecenter.value)
            .addTo(map);
        marker.getElement().setAttribute("data-id", etape.id);
        markers.value.push(marker);

        const markerElement = marker._element;
        const svgElement = markerElement.querySelector("svg");
        while (svgElement.firstChild) {
            svgElement.removeChild(svgElement.firstChild);
        }

        if (etape.id !== etapeActive.value) {
            if (svgElement) {
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
            if (svgElement) {
                const circle = document.createElementNS(
                    "http://www.w3.org/2000/svg",
                    "circle"
                );
                circle.setAttribute("cx", "17");
                circle.setAttribute("cy", "17");
                circle.setAttribute("r", "16");
                circle.setAttribute("fill", "#FFF");
                circle.setAttribute("stroke", couleur);
                circle.setAttribute("stroke-width", "2");
                svgElement.appendChild(circle);

                const clipPath = document.createElementNS(
                    "http://www.w3.org/2000/svg",
                    "clipPath"
                );
                clipPath.setAttribute("id", "clip-circle");

                const clipCircle = document.createElementNS(
                    "http://www.w3.org/2000/svg",
                    "circle"
                );
                clipCircle.setAttribute("cx", "17");
                clipCircle.setAttribute("cy", "17");
                clipCircle.setAttribute("r", "14");
                clipPath.appendChild(clipCircle);

                svgElement.appendChild(clipPath);

                const image = document.createElementNS(
                    "http://www.w3.org/2000/svg",
                    "image"
                );
                image.setAttribute("x", "3");
                image.setAttribute("y", "3");
                image.setAttribute("width", "28");
                image.setAttribute("height", "28");
                image.setAttribute("class", "imageEtapeActive");
                const etapeDepart = sentier.etapes.find(
                    (etape) => etape.id === etapeActive.value
                );

                image.setAttributeNS(
                    "http://www.w3.org/1999/xlink",
                    "href",
                    etapeDepart.photo
                );
                image.setAttribute("clip-path", "url(#clip-circle)");
                svgElement.appendChild(image);

                svgElement.setAttribute("width", "63");
                svgElement.setAttribute("height", "63");
            }
        }

        marker.getElement().addEventListener("click", () => {
            if (
                etapeActive.value == marker.getElement().getAttribute("data-id")
            ) {
                console.log(`etape click: `, etape.nom);
                window.location.hash = `step-${etape.id}`;
            } else {
                etapeComponent.value.onSlideTo(etape.id);
            }
        });
    });

    map.on("load", () => {
        if (sentier.etapes.length > 1) {
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
                        coordinates: sentier.etapes.map((etape) => [
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
                })
                .catch((error) => {
                    console.error("Erreur lors de la requête:", error);
                });
        }
    });
};

const showSentier = (sentier) => {
    const recenter = [sentier.etapes[0].longitude, sentier.etapes[0].latitude];
    if (sentier) {
        map.setCenter([
            sentier.etapes[0].longitude,
            sentier.etapes[0].latitude,
        ]);
        map.setZoom(12);
        map.setMaxZoom(18);
        map.setMinZoom(11);
        afficheRoute(sentier);
    } else {
        throw new Error("Sentier non trouvé");
    }
};

const recenterMap = () => {
    console.log("Recenter");
    map.flyTo({
        center: coordonnesRecenter.value,
        zoom: 12,
        curve: 1,
        easing(t) {
            return t;
        },
    });
};

const changeDePoint = (id) => {
    console.log(hash.value);
    const etape = sentier.value.etapes.find((etape) => etape.id === id);
    map.flyTo({
        center: [etape.longitude, etape.latitude],
        zoom: 14,
        curve: 1,
        easing(t) {
            return t;
        },
    });
};

const handleActiveSlideKeyChange = (newVal) => {
    etapeActive.value = newVal;
    afficheRoute(sentier.value);
    changeDePoint(newVal);
};

onMounted( async () => {
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
    await fetchSentier();
    await showSentier(sentier.value);
    console.log(markers.value);
});

</script>

<style scoped>
header {
    display: none;
}
section > div {
    width: 100vw;
    left: 0;
    height: 90vh;
    position: absolute;
    top: 0;
}
#map {
    width: 100%;
    height: 100vh;
    flex: 1;
    position: relative;
}

#overlayEtape {
    width: 100vw;
    height: 120px;
    position: absolute;
    bottom: 0rem;
}

.imageEtapeActive {
    border-radius: 50%;
    border: 2px solid black;
    background-size: cover;
}
.header {
    position: absolute;
    z-index: 2;
    left: 8%;
    top: 5%;
}
.header span {
    color: var(--primary) !important;
}
@media only screen and (min-width: 900px) {
    #recenterDiv {
        right: 0.5%;
    }
}
</style>
<style>
:root {
    --swiper-navigation-sides-offset: 12px;
    --swiper-navigation-size: 3rem;
}
.swiper-button-prev,
.swiper-button-next {
    font-weight: 900;
}
.swiper {
    overflow: visible !important;
}
</style>
