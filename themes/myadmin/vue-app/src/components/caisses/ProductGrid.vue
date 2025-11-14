<template>
  <div class="flex flex-col h-full mb-24 sm:mb-0">
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
      <div class="flex space-x-2 overflow-x-auto scrollbar-hide mb-2" v-if="history.length">
        <button @click="searchFromHistory('')" :class="[
          'px-3 py-1.5 rounded-button whitespace-nowrap text-xs font-medium uppercase',
          activeButton === 'all' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
        ]">
          Tous
        </button>
        <button v-for="item in history" :key="item" @click="searchFromHistory(item)" :class="[
          'px-3 py-1.5 rounded-button whitespace-nowrap text-xs font-medium uppercase',
          activeButton === item ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
        ]">
          {{ item }}
        </button>
      </div>
    </div>

    <!-- Grille de produits -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-2 overflow-y-auto"
      v-if="store.articles.rows.length">
      <product-card v-for="article in store.articles.rows" :key="article.nid" :article="article"
        @add-to-cart="handleAddToCart"
        class="bg-white rounded-lg p-2 shadow-sm border border-gray-100 cursor-pointer hover:shadow-md transition-shadow product-card"></product-card>
    </div>
    <div class="flex justify-center items-center mt-24"
      v-else>
      <h1 class="text-gray-500 text-lg">
        Aucun produits trouvé
      </h1>
    </div>

    <!-- Bouton Voir plus -->
    <div v-if="canLoadMore" class="text-center mt-4">
      <button @click="loadMore"
        class="px-4 py-1 bg-primary text-white text-sm rounded hover:bg-primary-dark font-semibold">
        Voir plus
      </button>
    </div>
  </div>
</template>


<script>
import { ref, onMounted, watch } from 'vue'
import { useArticleStore } from '../../stores/index.js'
import ProductCard from '../caisses/ProductCard.vue'
import { toast } from 'vue-sonner'
import { h } from 'vue'

export default {
  name: 'ProductGrid',
  components: { ProductCard },
  setup() {
    const store = useArticleStore()

    const searchKeyword = ref('')
    const history = ref([])
    const activeButton = ref('')

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
      offset: 12
    })

    // Charger les articles (append=true pour "Voir plus")
    const fetchArticles = async (append = false) => {
      await store.fetchArticles(queryOptions.value, append)
    }

    // Sauvegarde l’historique (max 5)
    function saveHistory(term) {
      if (!term) return

      // Supprime doublons
      history.value = history.value.filter(t => t !== term)

      // Ajoute au début
      history.value.unshift(term)

      // Max 5
      history.value = history.value.slice(0, 5)

      // Sauvegarde dans localStorage
      localStorage.setItem('search_history', JSON.stringify(history.value))
    }

    // Recherche
    const onSearch = () => {
      // Enregistrer dans l’historique
      saveHistory(searchKeyword.value)

      queryOptions.value.pager = 0
      updateFilter('title', searchKeyword.value, 'CONTAINS')
      fetchArticles(false)
    }

    // Clic sur un bouton de l’historique
    const searchFromHistory = (term) => {
      searchKeyword.value = term
      activeButton.value = term || 'all'
      onSearch()
    }

    // Bouton "Voir plus"
    const loadMore = () => {
      queryOptions.value.pager += 1
      fetchArticles(true)
    }

    // Add to cart
    function handleAddToCart(article) {
      if (article.field_quantite_stock > 0) {
        store.addItem(article)
        toast.success(() =>
          h('div', [
            'Ajouté au panier !',
            h('br'),
            h('span', article.title)
          ])
        )
      } else {
        toast.warning(() =>
          h('div', [
            'Rupture de stock !',
            h('br'),
            h('span', article.title)
          ])
        )
      }
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
    onMounted(() => {
      // Charger l’historique depuis localStorage
      const saved = localStorage.getItem('search_history')

      // Si existe, parser, sinon tableau vide
      history.value = saved ? JSON.parse(saved) : []

      fetchArticles(false)
    })

    return {
      store,
      searchKeyword,
      history,
      queryOptions,
      updateFilter,
      onSearch,
      searchFromHistory,
      loadMore,
      canLoadMore,
      handleAddToCart,
      activeButton
    }
  }
}
</script>