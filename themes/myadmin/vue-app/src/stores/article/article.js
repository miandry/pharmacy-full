import { defineStore } from "pinia";
import { ref } from "vue";
import { getArticles } from "../../services/article";
import axios from "axios";

export const useArticleStore = defineStore("article", () => {
  const articles = ref([]);
  const loading = ref(false);
  const error = ref(null);

  async function fetchArticles(params = null) {
    loading.value = true;
    try {
      // const response = await getArticles(params);

      let response = await axios.get(
        "http://www.pharmacy.full/fr/api/v2/node/article"
      );
      articles.value = response.data;
      console.log(articles.value);
    } catch (err) {
      error.value = err;
    } finally {
      loading.value = false;
    }
  }

  return { articles, loading, error, fetchArticles };
});
