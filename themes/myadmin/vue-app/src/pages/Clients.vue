<template>
  <div class="p-4 md:p-6">
    <rapportClients/>
    <tableClients/>
  </div>  
</template>
<script>
import { ref, onMounted } from 'vue';
// import rapportClients from '../components/clients/rapportClients.vue'
// import tableClients from '../components/clients/tableClients.vue'
export default {
  name: 'Clients',
    components: {
    // rapportClients,
    // tableClients
    },
    computed: {
    transId() {
      // Access the route parameter (e.g., id)
      return this.$route.params.id;
    }
  },
  setup() {
    const articles = ref([]);         // Holds the fetched data
    // Fetch data when the component is mounted
    onMounted(() => {
        /// articles.value = (window.drupalSettings?.vueArticles || []);
        // console.log(dataDrupal);
    });

    return {
      articles
    };
  }
};
</script>