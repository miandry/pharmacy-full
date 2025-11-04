<template>
  <div>
    <div class="mb-3">
      <div class="relative mb-3">
        <div
          class="w-5 h-5 flex items-center justify-center absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
          <i class="ri-search-line text-sm"></i>
        </div>
        <input type="text" id="search-input" placeholder="Rechercher des produits..."
          class="w-full pl-10 pr-4 py-2 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
      </div>
      <div class="flex space-x-2 overflow-x-auto">
        <button
          class="px-3 py-1.5 bg-primary text-white !rounded-button whitespace-nowrap text-xs font-medium">Tous</button>
        <button
          class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 !rounded-button whitespace-nowrap text-xs font-medium">Médicaments</button>
        <button
          class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 !rounded-button whitespace-nowrap text-xs font-medium">Vitamines</button>
        <button
          class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 !rounded-button whitespace-nowrap text-xs font-medium">Matériel</button>
        <button
          class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 !rounded-button whitespace-nowrap text-xs font-medium">Hygiène</button>
      </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-2 overflow-y-auto flex-1">
      <product-card v-for="article in store.articles.rows" :key="article.nid" :article="article"
        class="bg-white rounded-lg p-2 shadow-sm border border-gray-100 cursor-pointer hover:shadow-md transition-shadow product-card"></product-card>
    </div>
  </div>
</template>

<script>
import { useArticleStore } from '../../stores/article/article';
import ProductCard from '../caisses/ProductCard.vue'
import { onMounted } from 'vue'


export default {
  name: 'ProductGrid',
  components: {
    ProductCard,
  },
  setup() {
    const store = useArticleStore();

    onMounted(() => {
      store.fetchArticles()
    })

    return { store }
  },
}
</script>