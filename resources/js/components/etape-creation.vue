<template>
    <div class="container">
        <h1 class="titreEtape">Étape {{ this.noEtape }}</h1>
        <form action="" v-if="etapes">
            <!-- Utilisation de @submit.prevent pour gérer la soumission -->
            <div class="input-container">
                <label for="nom">Nom de l'étape</label>
                <input type="text" v-model="etapes.nom" id="nom" placeholder="Nom de l'étape" maxlength="100">
                <span class="char-limit">max. 100 caractères</span>
            </div>
            <div class="input-container">
                <label for="description">Description de l'étape</label>
                <textarea v-model="etapes.description" id="description" placeholder="Description de l'étape"
                    maxlength="2000"></textarea>
                <span class="char-limit">max. 2000 caractères</span>
            </div>
            <div class="input-container">
                <label>Poiter le lieu de l'étape</label>
                <div class="map"></div>
            </div>





            <div class="input-container" v-if="etapes.photo">
                <label>Ajouter une photo</label>
                <label for="photo">
                    <img :src="etapes.photo" alt="image de l'etape">
                    <span class="material-symbols-outlined editIcon">edit</span>
                </label>
                <input type="file" id="photo" @change="handleFileUpload" style="display: none;">
            </div>
            <div class="input-container" v-else>
                <label>Ajouter une photo</label>
                <label for="photo">
                    <span class="material-symbols-outlined"> add_a_photo </span>
                </label>
                <input type="file" id="photo" @change="handleFileUpload" style="display: none;">
            </div>





            <div class="input-container" v-if="etapes.pointInteret">
                <label>Point d'intérêts</label>
                <div class="ptInteret">
                    <input type="text" v-model="etapes.pointInteret[0].nom" placeholder="Nom du point d'intérêt">
                    <label for="photoInteret-1">
                        <span class="material-symbols-outlined"> add_a_photo </span>
                    </label>
                    <input type="file" id="photoInteret-1" @change="handleFileUpload" style="display: none;">
                </div>
                <div class="ptInteret">
                    <input type="text" v-model="etapes.pointInteret[1].nom" placeholder="Nom du point d'intérêt">
                    <label for="photoInteret-2">
                        <span class="material-symbols-outlined"> add_a_photo </span>
                    </label>
                    <input type="file" id="photoInteret-2" @change="handleFileUpload" style="display: none;">
                </div>
            </div>
            <div class="btnSuivant">
                <button type="submit">Suivant</button>
            </div>
        </form>
    </div>
</template>

<script>

export default {
    data() {
        return {
            etapes: [],
            noEtape: null,
        };
    },
    mounted() {
        let index = parseInt((window.location.hash.split('-')[1])) - 1;
        const etapesData = sessionStorage.getItem('etapes');
        if (etapesData) {
            this.etapes = JSON.parse(etapesData)[index];
            this.noEtape = index + 1;
        } else {
            console.error("No 'etapes' data in sessionStorage.");
        }
    },
    watch: {
        etapes: {
            handler() {
                console.log(this.etapes)
            },
            deep: true
        }
    },
    methods: {
        handleFileUpload(e) {
            console.log("here");
            const file = e.target.files[0];
            if (e.target.id === 'photo') {
                this.etapes.photo = file;
            } else {
                const index = e.target.id.split('-')[1] - 1
                this.etapes.pointInteret[index].photo = file;
            }
        }
    }
}
</script>

<style scoped>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

.btnSuivant {
    display: flex;
    justify-content: center;
    width: 100%;
}

.btnSuivant button {
    height: 60px;
    width: 205px;
    border: none;
    padding-top: 0;
    border-radius: 30px;
    background-color: #f1f1f1;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    background-color: green;
    color: white;
}

.container {
    height: 100%;
    width: 90%;
    margin: auto;
    padding-bottom: 30px;
}

.titreEtape {
    text-align: center;
    padding-top: 20px;
    margin-bottom: 20px;
}

.input-container {
    width: 100%;
    padding-bottom: 30px;
    position: relative;
}

.input-container input {
    width: 100%;
    height: 35px;
    border: 1px solid #ccc;
    padding-left: 20px;
    border-radius: 10px;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.input-container textarea {
    padding-top: 10px;
    width: 100%;
    height: 70px;
    border: 1px solid #ccc;
    padding-left: 20px;
    border-radius: 10px;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    resize: vertical;
    min-height: 100px;
}

.char-limit {
    position: absolute;
    right: 5%;
    bottom: 40px;
    font-size: 14px;
    color: #aaa;
    pointer-events: none;
}

.input-container label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.input-container label[for="photo"] {
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 10px;
    height: 75px;
    font-size: 40px;
    color: #676464;
    cursor: pointer;
}

.material-symbols-outlined {
    font-size: 30px;
}

.ptInteret {
    display: flex;
    width: 100%;
    justify-content: space-between;
    align-items: center;
    padding-right: 25px;
}

.ptInteret input {
    width: 80%;
    height: 35px;
    border: 1px solid #ccc;
    padding-left: 20px;
    border-radius: 10px;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 10px;
}

.map {
    width: 100%;
    height: 340px;
    background-color: blue;
    border-radius: 10px;
}
</style>
