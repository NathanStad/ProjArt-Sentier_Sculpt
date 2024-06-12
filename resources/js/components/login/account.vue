<template>
    <div v-if="isLoading">
        <p>Loading...</p>
    </div>
    <div v-else id="compte">
        <div class="header">
            <a @click.prevent="goBack">
                <span class="material-symbols-outlined"> arrow_back_ios </span>
            </a>
            <h1>Mon Compte</h1>
            <span class="material-symbols-outlined" @click="logOut" id="logout">
                logout
            </span>
        </div>
        <div id="user">
            <img :src="user.photo" :alt="user.name" />

            <label for="photo" id="photoProfil"
                ><span class="material-symbols-outlined click">
                    add_a_photo
                </span></label
            >
            <!-- Pop up Pour la photo -->
            <input
                type="file"
                id="photo"
                name="photoProfil"
                @change="handleFileUpload"
                style="display: none"
                accept="image/png, image/jpeg, image/jpg"
            />

            <!-- Titre -->
            <div>
                <h3>{{ user.name }}</h3>
                <h4>
                    {{
                        user.role.slice(0, 1).toUpperCase() + user.role.slice(1)
                    }}
                </h4>
            </div>
        </div>

        <a @click="newSentier" class="boutton click">Ajouter un sentier</a>
        <!-- les sentiers -->
        <div id="vos-santiers">
            <!-- Ajouter un sentier -->

            <!-- Choix de si archivé ou non -->
            <div id="button-archive" class="click">
                <span
                    @click="showContent('on')"
                    :class="{ actived: archive === 'on' }"
                    >Mes sentiers visibles</span
                >
                <span
                    @click="showContent('off')"
                    :class="{ actived: archive === 'off' }"
                    >Mes sentiers archivés</span
                >
            </div>
            <div id="sentiers">
            <!-- Sentier non-archivé -->
            <div
                v-for="sentier in userSentier"
                :key="sentier.id"
                :class="{
                    sentierItem: true,
                    content: true,
                    active: archive === 'on',
                }"
            >
                <a :href="`#sentier-${sentier.id}`">
                    <img :src="sentier.photo" :alt="sentier.nom" />
                </a>
                <!-- Bouton de management -->

                <span
                    class="material-symbols-outlined menuSentierButton click"
                    @click="showManagement(sentier.id)"
                >
                    more_horiz
                </span>

                <span
                    :class="{
                        menuSentier: true,
                        content: true,
                        active: buttonSentier === sentier.id,
                    }"
                >
                    <div @click="moveToArchive(sentier, 1)" class="click">
                        <span class="material-symbols-outlined">
                            inventory_2
                        </span>
                        Archiver le sentier
                    </div>
                    <div @click="editSentier(sentier)" class="click">
                        <span class="material-symbols-outlined"> edit </span
                        >Modifier le sentier
                    </div>
                    <div @click="deleteSentier(sentier)" class="click">
                        <span class="material-symbols-outlined"> delete </span
                        >Supprimer le sentier
                    </div>
                </span>
                <!-- Contenu -->
                <div class="affichage">
                    <div>
                        <p>{{ sentier.nom }}</p>
                        <div>
                            <span class="material-symbols-outlined">
                                location_on
                            </span>
                            <p>
                                {{ sentier.localisation }}
                            </p>
                        </div>
                    </div>
                    <div v-html="sentier.theme.icone"></div>
                </div>
            </div>

            <p
                v-if="userSentier.length < 1"
                :class="{ content: true, active: archive === 'on' }"
            >
                Vous n'avez pas publié de sentier
            </p>
                <div
                    v-for="sentier in userSentierArchive"
                    :key="sentier.id"
                    :class="{
                        sentierItem: true,
                        content: true,
                        active: archive === 'off',
                    }"
                >
                    <a :href="`#sentier-${sentier.id}`">
                        <img :src="sentier.photo" :alt="sentier.nom" />
                    </a>
                    <!-- Bouton de management -->

                    <span
                        class="material-symbols-outlined menuSentierButton"
                        @click="showManagement(sentier.id)"
                    >
                        more_horiz
                    </span>

                    <span
                        :class="{
                            menuSentier: true,
                            content: true,
                            active: buttonSentier === sentier.id,
                        }"
                    >
                        <div @click="moveToArchive(sentier, 0)" class="click">
                            <span class="material-symbols-outlined">
                                inventory_2
                            </span>
                            Désarchiver le sentier
                        </div>
                        <div @click="editSentier(sentier)" class="click">
                            <span class="material-symbols-outlined"> edit </span
                            >Modifer le sentier
                        </div>
                        <div @click="deleteSentier(sentier)" class="click">
                            <span class="material-symbols-outlined">
                                delete </span
                            >Supprimer le sentier
                        </div>
                    </span>
                    <!-- Contenu -->
                    <div class="affichage">
                        <div>
                            <p>{{ sentier.nom }}</p>
                            <div>
                                <span class="material-symbols-outlined">
                                    location_on
                                </span>
                                <p>
                                    {{ sentier.localisation }}
                                </p>
                            </div>
                        </div>
                        <div v-html="sentier.theme.icone"></div>
                    </div>
                </div>
            <p
                v-if="userSentierArchive.length < 1"
                :class="{ content: true, active: archive === 'off' }"
            >
                Vous n'avez pas de sentier dans l'archive
            </p>
            </div>
            <!-- Sentier archivé -->

        </div>
    </div>
    <ConfirmationModal
        v-if="modal"
        :message="modal.message"
        :onConfirm="modal.onConfirm"
        :onCancel="modal.onCancel"
        :type="false"
    />
    <footer><Footer></Footer></footer>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import Footer from "@/components/elements/footer.vue";
import ConfirmationModal from "@/components/elements/ConfirmationModal.vue";
import axios from "axios";

const userId = localStorage.getItem("userId");
const user = ref(null);
const userSentier = ref([]);
const userSentierArchive = ref([]);
const archive = ref("on");
const buttonSentier = ref(null);
const isLoading = ref(true);
const modal = ref(false);

// Fonction pour récupérer les informations utilisateur
const fetchUser = async () => {
    try {
        const response = await axios.get(`/data-user-${userId}`);
        user.value = response.data;
    } catch (error) {
        console.error("Error fetching user data:", error);
    }
};

// Fonction pour récupérer les sentiers de l'utilisateur
const fetchUserByUser = async () => {
    try {
        const response = await axios.get(`/data-sentiers/by-user-${userId}`);
        userSentier.value = response.data.filter((item) => item.archive === 0);
        userSentierArchive.value = response.data.filter(
            (item) => item.archive === 1
        );
    } catch (error) {
        console.error("Error fetching sentiers:", error);
    }
};
const fetchStep = async (id) => {
    if (id !== "") {
        try {
            const response = await axios.get(`/data-step-${id}`);
            return response.data;
        } catch (error) {
            console.error("Error fetching sentiers:", error);
            return error;
        }
    }
};
// Fonction pour afficher le contenu en fonction du choix (archivé ou non)
const showContent = (content) => {
    archive.value = content;
};

// Fonction pour afficher le menu de gestion des sentiers
const showManagement = (sentierId) => {
    buttonSentier.value = buttonSentier.value === sentierId ? null : sentierId;
};

// Fonction pour déplacer un sentier dans l'archive ou inversément
const moveToArchive = (sentier, targetArchive) => {
    sentier.archive = targetArchive;

    if (targetArchive === 0) {
        userSentierArchive.value = userSentierArchive.value.filter(
            (item) => item.id !== sentier.id
        );
        userSentier.value.push(sentier);
    } else {
        userSentier.value = userSentier.value.filter(
            (item) => item.id !== sentier.id
        );
        userSentierArchive.value.push(sentier);
    }

    // Mise à jour sur le serveur
    const toggleArchive = async (sentierId) => {
        try {
            const response = await axios.patch(
                `/set-sentier/${sentierId}/archive`
            );
        } catch (error) {
            console.error("Error toggling archive status:", error);
        }
    };
    toggleArchive(sentier.id);
};
const goBack = () => {
    window.history.back();
};
const logOut = () => {
    localStorage.removeItem("userId");
    window.location.replace(window.location.href.split("#")[0] + "#login");
    location.reload();
};
// Créerun nouveau seniter

const newSentier = () => {
    sessionStorage.removeItem("update");
    sessionStorage.removeItem("sentierCreation");
    sessionStorage.removeItem("etapes");
    window.location.href =
        window.location.href.split("#")[0] + "#creationSentier";
};

// Envoie pour la modifiction de l'objet

const editSentier = async (sentier) => {
    let etapeData = [];
    for (let index = 0; index < sentier.etapes.length; index++) {
        let value = await fetchStep(sentier.etapes[index].id);
        etapeData.push(value);
    }
    console.log(etapeData);
    if (
        !sessionStorage.getItem("sentierCreation") &&
        !sessionStorage.getItem("etapes")
    ) {
        sessionStorage.removeItem("sentierCreation");
        sessionStorage.removeItem("etapes");
    }
    let seniterData = {
        idSentier: sentier.id,
        nomSentier: sentier.nom,
        descriptionSentier: sentier.description,
        lieu: sentier.localisation,
        theme: sentier.theme_id,
        criteres: [],
        motcles: [],
        difficulte: sentier.difficulte.id,
        photoSentier: sentier.photo,
    };
    sentier.criteres.forEach((critere) => {
        seniterData.criteres.push(critere.id);
    });
    sentier.motcles.forEach((motcle) => {
        seniterData.motcles.push(motcle.id);
    });
    for (let index = 0; index < etapeData.length; index++) {
        etapeData[index].pointInteret = etapeData[index].points_interet;
        etapeData[index].coordonnees = {};
        etapeData[index].coordonnees.long = etapeData[index].longitude;
        etapeData[index].coordonnees.lat = etapeData[index].latitude;
        etapeData[index].coordonnees.lat = etapeData[index].latitude;
    }
    console.log(etapeData, sentier);
    sessionStorage.setItem("update", true);
    sessionStorage.setItem("sentierCreation", JSON.stringify(seniterData));
    sessionStorage.setItem("etapes", JSON.stringify(etapeData));
    window.location.href =
        window.location.href.split("#")[0] + "#creationSentier";
};

// Suppression du sentier avec une demande de confirmation

const deleteSentier = (sentier) => {
    const handleConfirm = () => {
        axios
            .delete(`/delete-sentier/${sentier.id}`)
            .then((response) => {
                console.log(response.data);
                modal.value = false;
                location.reload();
            })
            .catch((error) => {
                console.error(error);
            });
    };

    const handleCancel = () => {
        modal.value = false;
    };

    const message = "Êtes-vous sûr de vouloir supprimer ce sentier ?";

    modal.value = {
        message,
        onConfirm: handleConfirm,
        onCancel: handleCancel,
    };
};
// Fonction pour gérer le téléchargement de fichier pour les points d'intérêt
const handleFileUpload = async (e) => {
    const errors = ref();
    const file = e.target.files[0];
    const formData = new FormData();
    console.log(localStorage.getItem("userId"));
    formData.append("photoProfil", file);
    formData.append("userId", localStorage.getItem("userId"));

    try {
        const response = await axios.post("/submit-file-account", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        user.value.photo = `/storage/${response.data.path}`;
        // errors.value.photo = ""; // Clear error message on successful upload
    } catch (error) {
        console.error("Error uploading file:", error);
        // errors.value.photo = "Erreur lors du téléchargement de la photo.";
    }
};
// Initialisation des données utilisateur et sentiers

onMounted(async () => {
    if (userId.value && window.location.href.split("#")[1] === "login") {
        window.location.replace(
            window.location.href.split("#")[0] + "#account"
        );
    }
    await fetchUser();
    await fetchUserByUser();
    isLoading.value = false;
});

if (!localStorage.getItem("userId")) {
    window.location.hash = "#login";
}
</script>

<style>
/* Utilisateur */
#compte {
    width: 100%;
}
#user {
    height: 25vh;
    display: flex;
    justify-content: start;
    gap: 20px;
    border-bottom: 1px solid var(--color-text-secondary);
    margin-bottom: 20px;
    align-items: center;
    position: relative;
}
#user img {
    width: 130px;
    height: 130px;
    border-radius: var(--border-radius-full);
}
#user h3 {
    margin: 10px 0;
    font-size: 1.35rem;
}
#user h4 {
    font-weight: 500;
}
/* Vos Sentiers */

#vos-santiers {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: center;
}

/* Boutton sentiers */

.boutton {
    display: block;
    width: 100%;
    padding: 15px 20px;
    border: 1px solid var(--primary);
    text-align: center;
    text-decoration: none;
    border-radius: var(--border-radius-medium);
    color: var(--primary);
    font-size: 1.2rem;
    font-weight: 600;
    position: relative;
}
.boutton::after {
    content: "+";
    position: absolute;
    right: 5%;
    font-size: 3rem;
    top: 50%;
    font-weight: 300;
    transform: translateY(-50%);
}

/* Button Archive */

#button-archive {
    margin: 30px 0;
    display: flex;
    justify-content: space-between;
    font-size: 0.9rem;
    width: 100%;
    text-align: center;
    text-decoration: none;
    border-radius: var(--border-radius-large);
    color: var(--primary);
    position: relative;
    background-color: #dadada;
    padding: 0;
}
#button-archive span {
    padding: 10px 15px;
    width: 60%;
    border-radius: var(--border-radius-large);
}
#button-archive span.actived {
    background-color: var(--primary);
    color: white;
}

.sentierItem {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    height: 250px;
    border-radius: var(--border-radius-medium);
    overflow: hidden;
    box-shadow: var(--box-shadow-light);
    text-decoration: none;
    position: relative;
    margin-bottom: 10%;
}

.sentierItem img {
    width: 100%;
    height: 100%;
}

.menuSentierButton {
    position: absolute;
    right: -0.5%;
    top: -0.5%;
    z-index: 5;
    background-color: white;
    border-radius: 0 0 0 var(--border-radius-medium);
    padding: 2%;
}

.menuSentier {
    position: absolute;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    right: 0%;
    top: 10%;
    z-index: 4;
    background-color: white;
    border-radius: var(--border-radius-medium) 0 var(--border-radius-medium)
        var(--border-radius-medium);
    padding: 2% 5%;
    height: 50%;
}
.menuSentier div {
    display: flex;
    align-items: center;
    gap: 10px;
}
.menuSentier div:last-of-type span,
.menuSentier div:last-of-type {
    color: red;
}

.content {
    display: none;
}
.content.active {
    display: flex;
}
#vos-santiers {
    margin-bottom: 25%;
}
#vos-santiers .affichage {
    bottom: 0%;
    min-height: 75px;
    width: 105%;
    padding: var(--padding-medium) 40px;
}
#vos-santiers .affichage > div:nth-of-type(1) {
    justify-content: start;
    gap: 10px;
}
#vos-santiers .affichage > div:nth-of-type(1) > p {
    font-weight: 600;
    font-size: 1.2rem;
}
#vos-santiers .affichage > div:nth-of-type(2) {
    right: 40px;
}
#photoProfil {
    position: absolute;
    padding: 5px;
    border-radius: 20px;
    background: #dadada;
    display: flex;
    align-items: center;
    justify-content: center;
    top: 65%;
}
#photoProfil span {
    font-size: 1.3rem;
}
#logout {
    cursor: pointer;
    color: var(--color-text-secondary);
}
#sentier h1{
    text-align: center;
    font-size: 1.4rem ;
}
@media only screen and (min-width: 900px) {
    #compte {
        display: grid;
        grid-template-columns: 1fr 2fr;
        grid-template-rows: 1fr 3fr 1fr;
        height: 90vh;
        padding: 0 5%;
    }
    #compte .header {
        grid-column: 1/2;
        grid-row: 1/2;
    }
    #user {
        grid-column: 1/2;
        grid-row: 2/3;
        flex-direction: column;
        height: 100%;
        justify-content: center;
        border-bottom: none;
    }
    #user img {
        width: 300px;
        height:300px;
        border-radius: 200px;
    }
    #user h3 {
        padding: 0;
        margin: 0;
        font-size: 2rem;
        text-align: center;
    }
    #photoProfil {
        top: 55%;
        left: 25%;
    }
    #photoProfil span {
        font-size: 2.3rem;
    }
    #user h4 {
        padding: 0;
        margin-top: 5%;
        font-size: 1.3rem;
        text-align: center;
    }
    .boutton {
        grid-column: 1/2;
        grid-row: 3/4;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 50%;
        position: relative;
        top: 50%;
        transform: translateY(-50%);
    }
    #vos-santiers {
        grid-column: 2/3;
        grid-row: 1/4;
        margin-bottom: 0;
        display: grid;
        grid-template-columns: 1fr;
        grid-template-rows: 1fr 6fr;
        align-items: unset;
        border-left: 1px solid var(--color-text-secondary);
        padding: 0 2.5%;
        margin: 0 2.5%;
        height: 100%;
    }
    #button-archive {
        margin: 0 18% !important;
        height: 30%;
        width: 70%;
        margin: 0;
        position: relative;
        top: 50%;
        transform: translateY(-50%);
    }
    #button-archive span {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #sentiers{
        padding: 5%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 30px;
        height: 95%;
        overflow: overlay;
    }
    .login .cls-1 {
        fill: white;
    }
    .sentierItem{
        margin-bottom: 0;
    }
    .affichage p{
        width: 90%;
    }
}
</style>
