<template>
  <div class="bg-white rounded-lg border border-gray-200">
    <!-- Search and Filter Section -->
    <div class="p-4 border-b border-gray-200">
      <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
        <div class="relative flex-1">
          <div
            class="w-5 h-5 flex items-center justify-center absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
            <i class="ri-search-line text-sm"></i>
          </div>
          <input type="text" placeholder="Rechercher par nom" v-model="searchQuery" @keyup.enter="searchByKeys"
            class="w-full pl-10 pr-4 py-2 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />
        </div>
        <div class="flex space-x-2 overflow-x-auto">
          <button :class="[
            'px-4 py-2 !rounded-button whitespace-nowrap text-sm font-medium filter-btn',
            filterQueryActive === 'all'
              ? 'bg-primary text-white'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          ]" @click="filter('all')">
            Tous
          </button>
          <button :class="[
            'px-4 py-2 !rounded-button whitespace-nowrap text-sm font-medium filter-btn hidden',
            'bg-gray-100 text-gray-700 hover:bg-gray-200'
          ]">
            Clients réguliers
          </button>
          <button :class="[
            'px-4 py-2 !rounded-button whitespace-nowrap text-sm font-medium filter-btn',
            filterQueryActive === 1
              ? 'bg-primary text-white'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          ]" @click="filter(1)">
            Avec assurance
          </button>
          <button :class="[
            'px-4 py-2 !rounded-button whitespace-nowrap text-sm font-medium filter-btn',
            filterQueryActive === 0
              ? 'bg-primary text-white'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          ]" @click="filter(0)">
            Sans assurance
          </button>
        </div>
      </div>
    </div>


    <!-- Desktop Table -->
    <div class="md:block overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              <input type="checkbox" v-model="selectAll" @change="toggleSelectAll"
                class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" />
            </th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Client
            </th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Téléphone
            </th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="client in clients.rows" :key="client.nid" class="hover:bg-gray-50">

            <td class="px-4 py-4">
              <input type="checkbox" v-model="selectedClients" :value="client.id"
                class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary client-checkbox" />
            </td>
            <td class="px-4 py-4">
              <div class="flex items-center space-x-3">
                <div :class="[
                  'w-10 h-10 text-white rounded-full flex items-center justify-center font-medium',
                  `bg-orange-500`
                ]">
                  {{ "RA" }}
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900">
                    {{ client.title }}
                  </p>
                  <p class="text-xs text-gray-500">{{ client.type }}</p>
                </div>
              </div>
            </td>
            <td class="px-4 py-4 text-sm text-gray-900">{{ client.field_phone }}</td>
            <td class="px-4 py-4">
              <div class="flex space-x-2">
                <button @click="editClient(client)"
                  class="p-2 text-gray-400 hover:text-primary !rounded-button hover:bg-gray-50 edit-client">
                  <div class="w-4 h-4 flex items-center justify-center">
                    <i class="ri-edit-line"></i>
                  </div>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Empty State -->
      <div v-if="clients.rows.length === 0" class="text-center py-8">
        <i class="ri-user-search-line text-4xl text-gray-300 mb-2"></i>
        <p class="text-gray-500">Aucun client trouvé</p>
      </div>
    </div>
    <!-- Pagination and Bulk Actions -->
    <div class="px-4 py-3 border-t border-gray-200 flex flex-col md:flex-row md:items-center justify-center"
      v-if="clients.total > 10">
      <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
        <div class="flex flex-wrap justify-center gap-1 order-1 md:order-2">
          <button @click="previousPage" :class="[
            'px-2 md:px-3 py-1 text-xs md:text-sm !rounded-button',
            currentPage === 1
              ? 'bg-gray-100 text-gray-400 cursor-not-allowed hidden'
              : 'bg-gray-100 hover:bg-gray-200'
          ]">
            Précédent
          </button>
          <button v-for="page in visiblePages" :key="page" @click="goToPage(page)" :class="[
            'px-2 md:px-3 py-1 text-xs md:text-sm !rounded-button',
            page === currentPage
              ? 'bg-primary text-white'
              : 'bg-gray-100 hover:bg-gray-200'
          ]">
            {{ page }}
          </button>
          <button @click="nextPage" :disabled="currentPage === totalPages" :class="[
            'px-2 md:px-3 py-1 text-xs md:text-sm !rounded-button',
            currentPage === totalPages
              ? 'bg-gray-100 text-gray-400 cursor-not-allowed hidden'
              : 'bg-gray-100 hover:bg-gray-200'
          ]">
            Suivant
          </button>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import { computed, ref } from 'vue';

export default {
  name: 'tableClients',

  // Définition des props
  props: {
    clients: {
      type: Array,
      required: true
    }
  },

  emits: ['searchKeyWords', 'filterBy', 'paginate'],
  setup(props, { emit }) {
    const searchQuery = ref("");
    const filterQueryActive = ref("all");
    const perPage = 10;
    const currentPage = ref(1);

    const totalPages = computed(() => Math.ceil(props.clients.total / perPage));

    // Pages visibles (3 pages max)
    const visiblePages = computed(() => {
      const pages = [];
      const total = totalPages.value;
      const current = currentPage.value;

      if (total <= 3) {
        // Si total ≤ 3 → affichage normal
        for (let i = 1; i <= total; i++) pages.push(i);
      } else {
        // Toujours 3 pages visibles autour de la page active
        if (current === 1) pages.push(1, 2, 3);
        else if (current === total) pages.push(total - 2, total - 1, total);
        else pages.push(current - 1, current, current + 1);
      }

      return pages;
    });

    // Actions
    const goToPage = page => {
      if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        emit('paginate', currentPage.value)
      }
    };

    const nextPage = () => {
      if (currentPage.value < totalPages.value) {
        currentPage.value++
        emit('paginate', currentPage.value)
      };
    };

    const previousPage = () => {
      if (currentPage.value > 1) {
        currentPage.value--
        emit('paginate', currentPage.value)
      };
    };

    function searchByKeys() {
      emit('searchKeyWords', searchQuery.value)
    }

    function filter(value) {
      filterQueryActive.value = value;
      emit('filterBy', value);
    }

    function editClient(client) {
      emit('show', client);
    }

    return {
      searchByKeys,
      searchQuery,
      filter,
      filterQueryActive,
      visiblePages,
      currentPage,
      totalPages,
      goToPage,
      nextPage,
      previousPage,
      editClient
    }
  }
}
</script>