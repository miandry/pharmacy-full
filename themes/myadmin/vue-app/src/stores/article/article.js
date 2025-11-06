import { defineStore } from "pinia";
import { ref } from "vue";
import { getArticles } from "../../services/article";
import { buildQueryParams } from "../../utils/queryBuilder.js";

export const useArticleStore = defineStore("article", () => {
  const articles = ref({ rows: [], total: 0 });
  const loading = ref(false);
  const error = ref(null);

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

  return { articles, loading, error, fetchArticles };
});
