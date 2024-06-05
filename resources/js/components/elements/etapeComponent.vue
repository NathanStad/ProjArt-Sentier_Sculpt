<template>
    <span v-if="etapes">
        <swiper ref="swiperRef" :slidesPerView="1.3" :centeredSlides="true" :spaceBetween="15" :navigation="true"
            :modules="modules" class="mySwiper" @swiper="onSwiperReady" @slideChange="onSlideChange"
            @slideTo="onSlideTo">
            <swiper-slide v-for="(etape) in etapes.etapes" :key="etape.id" @click="chargerEtape(etape)">
                <div class="slide-content">
                    <div class="etape">
                        <div class="etapePhoto">
                            <img :src="etape.photo" alt="" />
                        </div>
                        <div class="texte">
                            <div class="titre">
                                <p>{{ etape.nom }}</p>
                            </div>
                            <div class="info">
                                <div class="duree">
                                    <p>Durée</p>
                                    <p>{{ Math.floor((etape.duree / 60).toFixed(2)) }} min</p>
                                </div>
                                <div class="distance">
                                    <p>Distance</p>
                                    <p>{{ Math.floor(etape.distance * 100) / 100 }} km</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </swiper-slide>
        </swiper>
        <p class="append-buttons"></p>
    </span>
</template>

<script>
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue';

// Import Swiper styles
import 'swiper/css';

import 'swiper/css/pagination';
import 'swiper/css/navigation';


// import required modules
import { Pagination, Navigation } from 'swiper/modules';

export default {
    data() {
        return {
            activeSlideKey: null,
            swiper: null
        };
    },
    components: {
        Swiper,
        SwiperSlide,
    },
    setup() {
        return {
            modules: [Pagination, Navigation],
        };
    },
    name: 'EtapeComponent',
    props: {
        etapes: {
            type: Object,
            required: true
        }
    },
    watch: {
        activeSlideKey() {
            this.$emit('active-slide-key-change', this.activeSlideKey);
        },
    },
    methods: {
        onSwiperReady(swiper) {
            this.swiper = swiper;
        },
        onSlideChange(swiper) {
            const activeIndex = swiper.activeIndex;
            this.activeSlideKey = this.etapes.etapes[activeIndex].id;
        },
        chargerEtape(etape) {
            console.log("etape click: ", etape.nom);
            window.location.hash = `etape-${etape.id}`
        },
        onSlideTo(id) {
            const slideIndex = this.etapes.etapes.findIndex(etape => etape.id === id);
            this.swiper.slideTo(slideIndex)
        },
    }

};
</script>

<style>
:root {
    --swiper-navigation-color: #40680c !important;
    --swiper-theme-color: #40680c !important;
}
</style>


<style scoped>
html,
body {
    margin: 0;
    padding: 0;
    height: 100%;
    width: 100%;
    box-sizing: border-box;
}

.etape {
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 100%;
    flex-grow: 1;
    height: 100%;
    border-radius: 15px;
    background-color: white;
}

.etapePhoto {
    width: 100px;
    display: flex;
    padding-left: 10px;
    height: 85px;
    justify-content: center;
    align-items: center;
}

.etapePhoto img {
    border-radius: 15%;
    width: 100px;
    height: 100%;
}

.texte {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.texte p {
    margin: 0;
    text-align: center;
}

.titre {
    font-size: 20px;
    font-weight: bold;
    height: 50%;
    width: 100%;
    align-content: center;
    justify-content: center;
}

.info {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    height: 50%;
    width: 100%;
}

.duree {
    font-size: 15px;
    width: 50%;
}


.distance {
    font-size: 15px;
    width: 50%;
}

.slide-content {
    width: 100%;
    max-width: 450px;
    height: 100%;
}

.swiper-slide {
    text-align: center;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;

    width: 100%;
    margin: 0;

}

.swiper {
    width: 100%;
    height: 100px;
    margin-top: 10px;
    margin-bottom: 10px;

}

.append-buttons {
    text-align: center;
    margin-top: 20px;
}

.append-buttons button {
    display: inline-block;
    cursor: pointer;
    border: 1px solid '#40680c';
    color: #40680c;
    text-decoration: none;
    padding: 4px 10px;
    border-radius: 4px;
    margin: 0 10px;
    font-size: 13px;
}


/* .swiper-pagination {
    bottom: 95% !important;
} */
</style>