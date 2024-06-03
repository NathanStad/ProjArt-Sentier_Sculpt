<template>
    <div v-if="isLoading">
        <p>Loading...</p>
    </div>
    <div v-else>
        <h1></h1>
        <div>
            <img :src="user.photo" :alt="user.name" />

            <a><span class="material-symbols-outlined"> add_a_photo </span></a>
            <!-- Pop up Pour la photo -->
            <div></div>

            <!-- Titre -->
            <p>{{ user.name }}</p>
            <p>{{ user.role }}</p>
        </div>

        <!-- les sentiers -->
        <div>
            <h2>Mes sentiers</h2>
            <!-- Ajouter un sentier -->
            <a href="">Ajouter un sentier</a>

            <!-- Choix de si archivé ou non -->
            <div>
                <span @click="showContent('on')">Mes sentiers visibles</span>
                <span @click="showContent('off')">Mes sentiers archivés</span>
            </div>

            <!-- Sentier non-archivé -->
            <div
                v-for="sentier in userSentier"
                :key="sentier.id"
                :class="{ content: true, active: archive === 'on' }"
            >
                <a :href="`#sentier-${sentier.id}`">
                    <img :src="sentier.photo" :alt="sentier.nom" />
                </a>
                <!-- Bouton de management -->

                <span
                    class="material-symbols-outlined"
                    @click="showManagement(sentier.id)"
                >
                    more_horiz
                </span>

                <span
                    :class="{
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
                    <div @click="editSentier(sentier.id)">
                        <span class="material-symbols-outlined"> edit </span
                        >Modifer le sentier
                    </div>
                    <div @click="deleteSentier(sentier)">
                        <span class="material-symbols-outlined"> delete </span
                        >Supprimer le sentier
                    </div>
                </span>
                <!-- Contenu -->
                <div>
                    <p>{{ sentier.nom }}</p>
                    <p>
                        <span class="material-symbols-outlined">
                            location_on
                        </span>
                        {{ sentier.localisation }}
                    </p>
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
                :class="{ content: true, active: archive === 'off' }"
            >
                <a :href="`#sentier-${sentier.id}`">
                    <img :src="sentier.photo" :alt="sentier.nom" />
                </a>
                <!-- Bouton de management -->

                <span
                    class="material-symbols-outlined"
                    @click="showManagement(sentier.id)"
                >
                    more_horiz
                </span>

                <span
                    :class="{
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
                    <div @click="editSentier(sentier.id)">
                        <span class="material-symbols-outlined"> edit </span
                        >Modifer le sentier
                    </div>
                    <div @click="deleteSentier(sentier)">
                        <span class="material-symbols-outlined"> delete </span
                        >Supprimer le sentier
                    </div>
                </span>
                <!-- Contenu -->
                <div>
                    <p>{{ sentier.nom }}</p>
                    <p>
                        <span class="material-symbols-outlined">
                            location_on
                        </span>
                        {{ sentier.localisation }}
                    </p>
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

// Redirection si l'utilisateur n'est pas connecté
if (
    !localStorage.getItem("userId") &&
    window.location.href.split("#")[1] == "account"
) {
    window.location.href = window.location.href.split("#")[0] + "#login";
}

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
                `/set-sentiers/${sentierId}/archive`
            );
        } catch (error) {
            console.error("Error toggling archive status:", error);
        }
    };
    toggleArchive(sentier.id);
};

// Envoie pour la modifiction de l'objet

const editSentier = (id) => {
    sessionStorage.setItem('idSentierEdit', id);
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
.content {
    display: none;
}
.content.active {
    display: block;
}
</style>
