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
  async function fetchOrders(options, append = false) {
    loading.value = true;
    try {
      const query = buildQueryParams(options);
      const response = await getOrders(query);
      const data = response.data;
      if (append && orders.value.rows.length) {
        // Ajouter les nouvelles données à la liste existante
        orders.value.rows = [...orders.value.rows, ...data.rows];
      } else {
        // Remplacer les données
        orders.value = data;
      }
    } catch (err) {
      error.value = err;
    } finally {
      loading.value = false;
    }
  }

  async function fetchOrder(id, options) {
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

  async function saveOrderData(newOrderData) {
    loading.value = true;
    try {
      await saveOrder(newOrderData);
    } catch (err) {
      error.value = err;
    } finally {
      loading.value = false;
    }
  }

  return {
    saveOrderData,
    fetchOrders,
    orders,
    fetchOrder,
    order,
    loading,
    error,
  };
});
