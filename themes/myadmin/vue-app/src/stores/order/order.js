import { defineStore } from "pinia";
import { ref } from "vue";
import { buildQueryParams } from "../../utils/queryBuilder.js";
import { getOrders, saveOrder } from "../../services/order.js";

export const useOrderStore = defineStore("order", () => {
  const orders = ref({ rows: [], total: 0 });
  const order = ref([]);
  const loading = ref(false);
  const error = ref(null);

  // Charger tout les commandes
  async function fetchOrders(options) {
    loading.value = true;
    try {
      const query = buildQueryParams(options);
      const response = await getOrders(query);
      orders.value = response.data;
      // console.log(clients.value);
    } catch (err) {
      error.value = err;
    } finally {
      loading.value = false;
    }
  }

  async function createOrder(newOrderData) {
    try {
      await saveOrder(newOrderData);
    } catch (err) {
      error.value = err;
    } finally {
      loading.value = false;
    }
  }

  return {
    createOrder,
    fetchOrders,
    orders,
    loading,
    error,
  };
});
