<template >
        <form @submit.prevent="submitData">
            <div>
                <label> Email
                <input type="text"></label>
            </div>
            <div>
                <label>Mot de passe
                <input type="password"></label>
            </div>
            <button type="submit">Submit</button>
            <p>Seules les institutions désireuses de créer des sentiers peuvent se connecter à notre plateforme. Pour obtenir un mot de passe, veuillez cliquer ici et nous envoyer une demande. Nous serons ravis de vous fournir les informations nécessaires pour accéder à nos services.<a href="mailto:contact@ca-marche-vd.ch">Nous contacter</a></p>
        </form>
</template>
<script>
export default {
    data() {
    return {
      data: {
        field1: '',
        field2: ''
      }
    };
  },
  methods: {
    async submitData() {
      try {
        const response = await axios.post('/api/insert-data', this.data);
        console.log(response.data);
      } catch (error) {
        console.error(error);
      }
    }
  }
};
</script>
<style lang="">
    
</style>