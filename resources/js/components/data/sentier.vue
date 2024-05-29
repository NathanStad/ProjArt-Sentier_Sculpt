
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        sentiers: []
      };
    },
    created() {
      this.fetchSentiers();
    },
    methods: {
      async fetchSentiers() {
        try {
          const response = await axios.get('/api/sentiers');
          this.sentiers = response.data;
        } catch (error) {
          console.error('Error fetching sentiers:', error);
        }
      }
    }
  };
  </script>
  