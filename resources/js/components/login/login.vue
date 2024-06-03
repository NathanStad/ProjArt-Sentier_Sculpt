<template>
  <form @submit.prevent="submitData">
    <div>
      <label>Email <input type="email" v-model="email" /></label>
    </div>
    <div>
      <label>Mot de passe <input type="password" v-model="password" /></label>
      <span v-if="password.length < 6" style="color: red;">Le mot de passe doit avoir au moins 6 caractères.</span>
    </div>
    <input type="submit" value="Envoyer" />
    <p>
      Seules les institutions désireuses de créer des sentiers peuvent se
      connecter à notre plateforme. Pour obtenir un mot de passe, veuillez
      cliquer ici et nous envoyer une demande. Nous serons ravis de vous
      fournir les informations nécessaires pour accéder à nos services.
      <a href="mailto:contact@ca-marche-vd.ch">Nous contacter</a>
    </p>
  </form>
</template>

<script>
import { ref } from "vue";
import axios from "axios";
const email = ref("");
const password = ref("");

if (localStorage.getItem("userId") && window.location.href.split('#')[1] == 'login') {
  window.location.replace(window.location.href.split('#')[0]+'#account');
}
  const submitData = async () => {
    try {
      const response = await axios.post("/checkdata-user", {
        email: email.value,
        password: password.value,
      });
      console.log(response.data);
      
      if (response.data.message == 'Authenticated') {
        
        localStorage.setItem("userId", response.data.user.id);
        window.location.replace(window.location.href.split('#')[0]+'#account');
      }
      
      
      
    } catch (error) {
      console.error(error);
    }
  };
  
  export default {
    setup() {
      return { email, password, submitData };
    },
  };
</script>

<style>
/* Vos styles CSS ici */
</style>
