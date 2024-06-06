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
            <div></div>
        </div>
        <div id="user">
            <img :src="user.photo" :alt="user.name" />

            <!-- <a><span class="material-symbols-outlined"> add_a_photo </span></a>-->
            <!-- Pop up Pour la photo -->
            <!-- <div></div> -->

            <!-- Titre -->
            <div>
                <h3>{{ user.name }}</h3>
                <h4>{{ user.role }}</h4>
            </div>
        </div>

        <!-- les sentiers -->
        <div id="vos-santiers">
            <!-- Ajouter un sentier -->
            <a @click="newSentier" class="boutton">Ajouter un sentier</a>

            <!-- Choix de si archivé ou non -->
            <div id="button-archive">
                <span @click="showContent('on')" :class="{ actived: archive === 'on' }">Mes sentiers visibles</span>
                <span @click="showContent('off')" :class="{ actived: archive === 'off' }">Mes sentiers archivés</span>
            </div>

            <!-- Sentier non-archivé -->
            <div
                v-for="sentier in userSentier"
                :key="sentier.id"
                :class="{sentierItem: true, content: true, active: archive === 'on' }"
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
                        menuSentier:true,
                        content: true,
                        active: buttonSentier === sentier.id,
                    }"
                >
                    <div @click="moveToArchive(sentier, 1)">
                        <span class="material-symbols-outlined">
                            inventory_2
                        </span>
                        Archiver le sentier
                    </div>
                    <div @click="editSentier(sentier)">
                        <span class="material-symbols-outlined"> edit </span
                        >Modifer le sentier
                    </div>
                    <div @click="deleteSentier(sentier)">
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

            <!-- Sentier archivé -->
            <div
                v-for="sentier in userSentierArchive"
                :key="sentier.id"
                :class="{sentierItem: true, content: true, active: archive === 'off' }"
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
                        menuSentier:true,
                        content: true,
                        active: buttonSentier === sentier.id,
                    }"
                >
                    <div @click="moveToArchive(sentier, 0)">
                        <span class="material-symbols-outlined">
                            inventory_2
                        </span>
                        Désarchiver le sentier
                    </div>
                    <div @click="editSentier(sentier)">
                        <span class="material-symbols-outlined"> edit </span
                        >Modifer le sentier
                    </div>
                    <div @click="deleteSentier(sentier)">
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
                v-if="userSentierArchive.length < 1"
                :class="{ content: true, active: archive === 'off' }"
            >
                Vous n'avez pas publié de sentier
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const userId = localStorage.getItem("userId");
const user = ref(null);
const userSentier = ref([]);
const userSentierArchive = ref([]);
const archive = ref("on");
const buttonSentier = ref(null);
const isLoading = ref(true);

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
// Créerun nouveau seniter

const newSentier = () =>{
    sessionStorage.removeItem('update')
    sessionStorage.removeItem('sentierCreation')
    sessionStorage.removeItem('etapes')
    window.location.href =
        window.location.href.split("#")[0] + "#creationSentier";
}

// Envoie pour la modifiction de l'objet

const editSentier = (sentier) => {
    console.log(sentier);
    if (!(sessionStorage.getItem('sentierCreation')) && !sessionStorage.getItem('etapes')) {
        const seniterData = {
            nomSentier: sentier.nom,
            descriptionSentier: sentier.description ,
            lieu: sentier.localisation ,
            theme: sentier.theme_id ,
            criteres: [] ,
            motcles: [],
            difficulte: sentier.difficulte.id,
            photoSentier:sentier.photo
        }
        sentier.criteres.forEach(critere => {
            seniterData.criteres.push(critere.id)
        });
        sentier.motcles.forEach(motcle => {
            seniterData.motcles.push(motcle.id)
        });
        const etapeData = sentier.etapes
        console.log(seniterData, etapeData);
        sessionStorage.setItem('update', true)
        sessionStorage.setItem('sentierCreation', JSON.stringify(seniterData))
        sessionStorage.setItem('etapes', JSON.stringify(etapeData))
    }
    window.location.href =
        window.location.href.split("#")[0] + "#creationSentier";
};

// Suppression du sentier avec une demande de confirmation

const deleteSentier = (sentier) => {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce sentier ?")) {
        axios
            .delete(`/delete-sentier/${sentier.id}`)
            .then((response) => {
                console.log(response.data);
            })
            .catch((error) => {
                console.error(error);
            });
    }
};

// Initialisation des données utilisateur et sentiers

onMounted(async () => {
    await fetchUser();
    await fetchUserByUser();
    isLoading.value = false;
});
</script>

<style>

/* Utilisateur */

#user{
    height: 25vh;
    display: flex;
    justify-content: space-around;
    border-bottom: 1px solid var(--color-text-secondary) ;
    margin-bottom: 20px;
    align-items: center;
}
#user img{
    width: 150px;
    height: 150px;
    border-radius: var( --border-radius-full);
}
#user h3{
    margin: var(--margin-small) 0 ;
}
/* Vos Sentiers */

#vos-santiers{
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: center;
}

/* Boutton sentiers */

.boutton{
    width: 100%;
    padding: 20px;
    border: 1px solid var(--primary) ;
    text-align: center;
    text-decoration: none;
    border-radius: var(--border-radius-medium);
    color: var(--primary);
    font-size: 1.2rem;
    font-weight: 600;
    position: relative;
}
.boutton::after{
    content: '+';
    position: absolute;
    right: 5%;
    font-size: 3rem;
    top: 50%;
    font-weight: 300;
    transform: translateY(-50%);
}

/* Button Archive */

#button-archive{
    margin: 50px 0;
    display: flex;
    justify-content: space-between;
    font-size: 0.9rem;
    width: 100%;
    padding: 15px;
    text-align: center;
    text-decoration: none;
    border-radius: var(--border-radius-large);
    color: var(--primary);
    position: relative;
    background-color: var(--color-text-secondary);
}
#button-archive{
    margin: 50px 0;
    display: flex;
    justify-content: space-between;
    font-size: 0.9rem;
    width: 100%;
    text-align: center;
    text-decoration: none;
    border-radius: var(--border-radius-large);
    color: var(--primary);
    position: relative;
    background-color: var(--color-text-secondary);
    padding: 0;
}
#button-archive span{
    padding: 15px 15px;
    width: 60%;
    border-radius: var(--border-radius-large);
    
}
#button-archive span.actived{
    background-color:var(--primary) ;
    color: white;
}

.sentierItem{
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
}

.sentierItem img{
    width: 100%;
    height: 200;
}

.menuSentierButton{
    position: absolute;
    right: -0.5%;
    top: -0.5%;
    z-index: 5;
    background-color: white;
    border-radius: var(--border-radius-full);
    padding: 2%;

}

.menuSentier{
    position: absolute;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    right: 0%;
    top: 10%;
    z-index: 4;
    background-color: white;
    border-radius: var(--border-radius-medium) 0 var(--border-radius-medium) var(--border-radius-medium);
    padding: 2% 5%;
    height: 50%;
}
.menuSentier div{
    display: flex;
    align-items: center;
    gap: 10px;
}
.menuSentier div:last-of-type span,
.menuSentier div:last-of-type
{
    color: red;
}

.content {
    display: none;
}
.content.active {
    display: flex;
}
#vos-santiers{
    margin-bottom: 25%;
}
#vos-santiers .affichage{
    bottom: 0%;
    height: 80px;
    width: 105%;
    padding: var(--padding-medium) 40px;
}
#vos-santiers .affichage > div:nth-of-type(1) > p{
    font-weight: 600;
}
#vos-santiers .affichage > div:nth-of-type(2){
    right:40px
}
</style>
