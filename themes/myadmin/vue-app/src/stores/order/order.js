import { defineStore } from "pinia";
import { ref } from "vue";
import { buildQueryParams } from "../../utils/queryBuilder.js";
import { getOrder, getOrders, saveOrder } from "../../services/order.js";

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
    } catch (err) {
      error.value = err;
    } finally {
      loading.value = false;
    }
  }

  async function fetchOrder(id ,options) {
    loading.value = true;
    try {
      const query = buildQueryParams(options);
      const response = await getOrder(id, query);
      order.value = response.data;
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
    fetchOrder,
    order,
    loading,
    error,
  };
});
