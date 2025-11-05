<template>
  <div>
    <page-loader v-if="store.loading"></page-loader>
    <!-- Champ de recherche -->
    <div class="mb-3">
      <div class="relative mb-3">
        <div
          class="w-5 h-5 flex items-center justify-center absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
          <i class="ri-search-line text-sm"></i>
        </div>
        <input v-model="searchKeyword" @keyup.enter="onSearch" type="text" placeholder="Rechercher des produits..."
          class="w-full pl-10 pr-4 py-2 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
      </div>
    </div>

    <!-- Grille de produits -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-2 overflow-y-auto flex-1">
      <product-card v-for="article in store.articles.rows" :key="article.nid" :article="article"
        class="bg-white rounded-lg p-2 shadow-sm border border-gray-100 cursor-pointer hover:shadow-md transition-shadow product-card"></product-card>
    </div>

    <!-- Bouton Voir plus -->
    <div v-if="canLoadMore" class="text-center mt-4">
      <button @click="loadMore" class="px-4 py-1 bg-primary text-white text-sm rounded hover:bg-primary-dark font-semibold">
        Voir plus
      </button>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue'
import { useArticleStore } from '../../stores/article/article'
import ProductCard from '../caisses/ProductCard.vue'
import PageLoader from '../PageLoader.vue'

export default {
  name: 'ProductGrid',
  components: { ProductCard, PageLoader },
  setup() {
    const store = useArticleStore()
    const searchKeyword = ref('')

    // Paramètres dynamiques de la requête
    const queryOptions = ref({
      fields: [
        'nid',
        'title',
        'field_prix_unitaire',
        'field_nombre_par_unite',
        'field_quantite_stock'
      ],
      sort: { val: 'nid', op: 'desc' },
      filters: {},
      pager: 0,
      offset: 4
    })

    // Construire la query string custom
    const buildQueryParams = (options) => {
      const params = new URLSearchParams()

      // Fields
      if (options.fields?.length) options.fields.forEach(f => params.append('fields[]', f))

      // Sort
      if (options.sort?.val && options.sort?.op) {
        params.append('sort[val]', options.sort.val)
        params.append('sort[op]', options.sort.op)
      }

      // Filters
      if (options.filters && Object.keys(options.filters).length > 0) {
        for (const [key, filter] of Object.entries(options.filters)) {
          if (filter?.val) {
            params.append(`filters[${key}][val]`, filter.val)
            if (filter.op) params.append(`filters[${key}][op]`, filter.op)
          }
        }
      }

      // Pagination
      params.append('pager', options.pager)
      params.append('offset', options.offset)

      return params.toString()
    }

    // Charger les articles (append=true pour "Voir plus")
    const fetchArticles = async (append = false) => {
      const query = buildQueryParams(queryOptions.value)
      await store.fetchArticles(query, append)
    }

    // Recherche (entrée clavier)
    const onSearch = () => {
      queryOptions.value.pager = 0 // reset pagination
      updateFilter('title', searchKeyword.value, 'CONTAINS')
      fetchArticles(false)
    }

    // Bouton "Voir plus"
    const loadMore = () => {
      queryOptions.value.pager += 1
      fetchArticles(true)
    }

    // Déterminer si on peut charger plus
    const canLoadMore = ref(true)
    watch(
      () => store.articles,
      (articles) => {
        if (!articles || !articles.rows) return
        canLoadMore.value = articles.rows.length < (articles.total || 0)
      },
      { deep: true, immediate: true }
    )

    // Ajouter / supprimer un filtre
    const updateFilter = (key, value, op = '=') => {
      if (!value) delete queryOptions.value.filters[key]
      else queryOptions.value.filters[key] = { val: value, op }
    }

    // Chargement initial
    onMounted(() => fetchArticles(false))

    return {
      store,
      searchKeyword,
      queryOptions,
      updateFilter,
      onSearch,
      loadMore,
      canLoadMore
    }
  }
}
</script>