import { defineStore } from "pinia";
import { ref } from "vue";
import { getClients, saveClient } from "../../services/cliens";

export const useClientStore = defineStore("client", () => {
  const clients = ref({ rows: [], total: 0 });
  const client = ref([]);
  const loading = ref(false);
  const error = ref(null);

  // fetchClient
  async function fetchClient(params) {
    loading.value = true;
    try {
      const response = await getClients(params);
      clients.value = response.data;
      console.log(clients.value);
    } catch (err) {
      error.value = err;
    } finally {
      loading.value = false;
    }
  }

  async function createClient(newClient) {
    try {
        const response = await saveClient(newClient);
        client.value = response;
        console.log(client.value);
    } catch (err) {
        error.value = err;
    } finally {
        loading.value = false;
    }
  }

  return { clients, client, loading, error, fetchClient, createClient };
});
