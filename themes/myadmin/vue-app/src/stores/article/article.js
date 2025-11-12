import { defineStore } from "pinia";
import { computed, h, ref } from "vue";
import { getArticles } from "../../services/article";
import { buildQueryParams } from "../../utils/queryBuilder.js";
import { toast } from "vue-sonner";

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
    const item = cardItems.value.find((i) => i.nid == article.nid);

    if (item) {
      // vérifier le stock avant d'augmenter
      if (article.field_quantite_stock > 0) {
        item.quantity++;
        article.field_quantite_stock--; // décrémente le stock dans articles.rows
      } else {
        toast.warning(() =>
          h("div", ["Rupture de stock !", h("br"), h("span", article.title)])
        );
      }
    } else {
      if (article.field_quantite_stock > 0) {
        cardItems.value.push({ ...article, quantity: 1 });
        article.field_quantite_stock--;
      } else {
        toast.warning(() =>
          h("div", ["Rupture de stock !", h("br"), h("span", article.title)])
        );
      }
    }
  }

  function incrementQuantity(item) {
    const article = articles.value.rows.find((a) => a.nid == item.nid);
    if (article && article.field_quantite_stock > 0) {
      item.quantity++;
      article.field_quantite_stock--;
    } else {
      toast.warning(() =>
        h("div", ["Rupture de stock !", h("br"), h("span", article.title)])
      );
    }
  }

  function decrementQuantity(item) {
    const article = articles.value.rows.find((a) => a.nid == item.nid);
    if (item.quantity > 1) {
      item.quantity--;
      if (article) article.field_quantite_stock++;
    } else {
      removeItem(item);
    }
  }

  function removeItem(item) {
    const index = cardItems.value.findIndex((i) => i.nid == item.nid);
    if (index !== -1) {
      const article = articles.value.rows.find((a) => a.nid == item.nid);
      if (article)
        article.field_quantite_stock += cardItems.value[index].quantity;
      cardItems.value.splice(index, 1);
    }
  }

  function clearCart() {
    cardItems.value.splice(0);
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
    saveOrder,
    decrementQuantity,
    incrementQuantity,
  };
});
