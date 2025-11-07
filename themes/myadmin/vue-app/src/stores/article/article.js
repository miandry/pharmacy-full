import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { getArticles } from "../../services/article";
import { buildQueryParams } from "../../utils/queryBuilder.js";

export const useArticleStore = defineStore("article", () => {
  const articles = ref({ rows: [], total: 0 });
  const loading = ref(false);
  const error = ref(null);
  const savedOrder = ref(null);
  const cardItems = ref([]);

  // fetchArticles: si append=true, on ajoute les nouvelles données
  async function fetchArticles(options, append = false) {
    loading.value = true;
    try {
      const query = buildQueryParams(options);
      const response = await getArticles(query);

      const data = response.data;

      if (append && articles.value.rows.length) {
        // Ajouter les nouvelles données à la liste existante
        articles.value.rows = [...articles.value.rows, ...data.rows];
      } else {
        // Remplacer les données
        articles.value = data;
      }
    } catch (err) {
      error.value = err;
    } finally {
      loading.value = false;
    }
  }

  function addItem(article) {
    // Vérifie si l'article est déjà dans le panier
    const item = cardItems.value.find((i) => i.nid == article.nid);

    if (item) {
      // Si déjà présent, incrémente seulement la quantité
      item.quantity++;
    } else {
      // Sinon, ajoute l'article et crée la clé quantity = 1
      cardItems.value.push({
        ...article,
        quantity: 1,
      });
    }
  }

  function removeItem(index) {
    cardItems.value.splice(index, 1);
  }

  function clearCart() {
    cardItems.value = [];
  }

  // Calcul du total
  const total = computed(() => {
    return cardItems.value.reduce((sum, item) => {
      return sum + item.field_prix_unitaire * item.quantity;
    }, 0);
  });

  function saveOrder(client) {
    savedOrder.value = {
      clientId: client.nid,
      clientName: client.title,
      items: cardItems.value.map((item) => ({ ...item })), // copie des articles
      total: total.value,
    };
    return savedOrder.value;
  }

  return {
    articles,
    loading,
    error,
    fetchArticles,
    cardItems,
    addItem,
    removeItem,
    clearCart,
    total,
    savedOrder,
    saveOrder
  };
});
