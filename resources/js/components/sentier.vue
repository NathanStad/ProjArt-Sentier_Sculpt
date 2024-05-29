<template>
    <div>
        <div><img src="" alt="" />lieu</div>
        <div><img src="" alt="" /></div>
        <div>
            <img src="" alt="" />
            <div>
                <div></div>
            </div>
        </div>
        <div>
            <div>
                <img src="" alt="" />
                <p></p>
                <p></p>
            </div>
            <div>
                <img src="" alt="" />
                <p></p>
                <p></p>
            </div>
            <div>
                <img src="" alt="" />
                <p></p>
                <p></p>
            </div>
        </div>
    </div>
    <div>
        <a href="">Description</a>
        <a href="">Étapes</a>
        <a href="">Commentaires</a>
    </div>
    <!-- Description -->
    <div></div>
    <!-- Etapes -->
    <div>
        <div>
            <img src="" alt="" />
            <p></p>
        </div>
    </div>
    <!-- Commentaires -->
    <div>
        <div>
            <img src="" alt="" />
            <div>
                <p></p>
                <p></p>
            </div>
        </div>
        <a href="">Voir plus</a>
        <div>
            <form action=""></form>
            <label>
                Email :
                <input type="text" />
            </label>
            <label>
                Email :
                <input type="text" />
            </label>
        </div>
    </div>
</template>
<script>
import axios from 'axios';



export default {
  data() {
    return {
      message: '',
      sentier: [],
      sentierId: ''
    };
  },
  methods: {
    incrementCompteur() {
      axios.post(`/api/incr-compteur/${sentierId}`)
        .then(response => {
          this.message = response.data.message;
        })
        .catch(error => {
          if (error.response) {
            this.message = error.response.data.message;
          } else {
            this.message = 'An error occurred';
          }
          console.error('There was an error!', error);
        });
    },
    async fetchSentiers() {
        try {
          const response = await axios.get('/data-sentier');
          this.sentiers = response.data;
        } catch (error) {
          console.error('Error fetching sentiers:', error);
        }
      },

  }
};
</script>
<style lang=""></style>
