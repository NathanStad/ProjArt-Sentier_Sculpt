<template>
    <div class="login" id="login">
        <header><Header></Header></header>
        <div class="header">
            <div></div>
            <h1>Se connecter</h1>
            <div></div>
        </div>
        <form @submit.prevent="submitData" id="connect">
            <div>
                <label
                    >Adresse mail <br />
                    <input type="email" v-model="email" placeholder="Email" />
                </label>
                <span v-if="errors.email" class="error">{{
                    errors.email
                }}</span>
            </div>
            <div>
                <label
                    >Mot de passe <br />
                    <input
                        type="password"
                        v-model="password"
                        placeholder="Mot de passe"
                    />
                </label>
                <span v-if="errors.password" class="error">{{
                    errors.password
                }}</span>
            </div>
            <input type="submit" value="Se connecter" />
            <span v-if="errors.server" class="error">{{ errors.server }}</span>
            <p>
                Seules les institutions désireuses de créer des sentiers peuvent
                se connecter à notre plateforme. Pour obtenir un mot de passe,
                veuillez cliquer ici et nous envoyer une demande. Nous serons
                ravis de vous fournir les informations nécessaires pour accéder
                à nos services.
            </p>
            <a href="mailto:contact@ca-marche-vd.ch">Nous contacter</a>
        </form>
        <footer><Footer></Footer></footer>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Footer from "@/components/elements/footer.vue";
import Header from "@/components/elements/header.vue";

// Références pour l'email et le mot de passe
const email = ref("");
const password = ref("");

// Référence pour les erreurs
const errors = ref({
    email: "",
    password: "",
    server: "",
});

// Redirection si l'utilisateur est déjà connecté
onMounted(() => {
    if (
        localStorage.getItem("userId") &&
        window.location.href.split("#")[1] === "login"
    ) {
        window.location.replace(
            window.location.href.split("#")[0] + "#account"
        );
    }
});

// Fonction pour soumettre les données
const submitData = async () => {
    // Réinitialiser les erreurs
    errors.value.email = "";
    errors.value.password = "";
    errors.value.server = "";

    // Valider les champs
    let valid = true;
    if (!email.value) {
        errors.value.email = "L'adresse email est requise.";
        valid = false;
    }
    if (!password.value) {
        errors.value.password = "Le mot de passe est requis.";
        valid = false;
    } else if (password.value.length < 6) {
        errors.value.password =
            "Le mot de passe doit avoir au moins 6 caractères.";
        valid = false;
    }

    // Soumettre le formulaire si tout est valide
    if (valid) {
        try {
            const response = await axios.post("/checkdata-user", {
                email: email.value,
                password: password.value,
            });
            if (response.data.message === "Authenticated") {
                localStorage.setItem("userId", response.data.user.id);
                window.location.replace(
                    window.location.href.split("#")[0] + "#account"
                );
            } else {
                errors.value.server =
                    "Erreur de connexion. Veuillez vérifier vos identifiants.";
            }
        } catch (error) {
            errors.value.server =
                "Erreur de connexion. Veuillez réessayer plus tard.";
            console.error(error);
        }
    }
};
</script>

<style scoped>
form > *,
form input {
    margin-bottom: 5%;
}
form input[type="email"],
form input[type="password"] {
    margin-top: 5%;
    padding: 15px 30px;
    border-radius: 15px;
    width: 100%;
}
label {
    font-weight: 600;
}
input[type="submit"] {
    position: relative;
    left: 50%;
    transform: translate(-50%);
    padding: 20px 20px !important;
    margin-bottom: 10%;
}

a {
    font-weight: 600;
}
.error {
    display: block;
    color: red;
    font-size: 0.9rem;
    margin-top: 0.3rem;
}
header {
    display: none;
}
@media only screen and (min-width: 900px) {
    .login {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 1fr 3fr;
        position: relative;
        height: 90vh
    }
    #connect {
        padding: 0 18%;
    }
    header {
        display: flex;
        align-items: center;
        justify-content: center;
        grid-column: 1/2;
        grid-row: 1/3;
        position: relative;
        bottom: -2%;
        left: -5%;
        width: 100%;
        height: 100% ;
        margin: 0;
        background-color: var(--primary);
        border-radius: 0 50px 0 0 ;
    }
}
</style>
<style>
@media only screen and (min-width: 900px) {
  .login .cls-1 {
        fill: white;
      }
}
</style>