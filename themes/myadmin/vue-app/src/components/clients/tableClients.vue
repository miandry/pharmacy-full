<template>
  <div class="bg-white rounded-lg border border-gray-200">
    <!-- Search and Filter Section -->
    <div class="p-4 border-b border-gray-200">
      <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
        <div class="relative flex-1">
          <div class="w-5 h-5 flex items-center justify-center absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
            <i class="ri-search-line text-sm"></i>
          </div>
          <input
            type="text"
            v-model="searchQuery"
            @input="handleSearch"
            placeholder="Rechercher par nom ou téléphone..."
            class="w-full pl-10 pr-4 py-2 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
          />
        </div>
        <div class="flex space-x-2 overflow-x-auto">
          <button
            @click="setFilter('all')"
            :class="[
              'px-4 py-2 !rounded-button whitespace-nowrap text-sm font-medium filter-btn',
              activeFilter === 'all' 
                ? 'bg-primary text-white' 
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
            data-filter="all"
          >
            Tous
          </button>
          <button
            @click="setFilter('regular')"
            :class="[
              'px-4 py-2 !rounded-button whitespace-nowrap text-sm font-medium filter-btn',
              activeFilter === 'regular' 
                ? 'bg-primary text-white' 
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
            data-filter="regular"
          >
            Clients réguliers
          </button>
          <button
            @click="setFilter('insurance')"
            :class="[
              'px-4 py-2 !rounded-button whitespace-nowrap text-sm font-medium filter-btn',
              activeFilter === 'insurance' 
                ? 'bg-primary text-white' 
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
            data-filter="insurance"
          >
            Avec assurance
          </button>
          <button
            @click="setFilter('no-insurance')"
            :class="[
              'px-4 py-2 !rounded-button whitespace-nowrap text-sm font-medium filter-btn',
              activeFilter === 'no-insurance' 
                ? 'bg-primary text-white' 
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
            data-filter="no-insurance"
          >
            Sans assurance
          </button>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="p-8 text-center">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
      <p class="mt-2 text-sm text-gray-600">Chargement des clients...</p>
    </div>

    <!-- Desktop Table -->
    <div  class="md:block overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              <input
                type="checkbox"
                v-model="selectAll"
                @change="toggleSelectAll"
                class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary"
              />
            </th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Client
            </th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Téléphone
            </th>
            <!-- <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Assurance
            </th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Dernière visite
            </th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Total achats
            </th> -->
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr 
            v-for="client in clients" 
            :key="client.id" 
            class="hover:bg-gray-50"
          >
          
            <td class="px-4 py-4">
              <input
                type="checkbox"
                v-model="selectedClients"
                :value="client.id"
                class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary client-checkbox"
              />
            </td>
            <td class="px-4 py-4">
              <div class="flex items-center space-x-3">
                <div
                  :class="[
                    'w-10 h-10 text-white rounded-full flex items-center justify-center font-medium',
                    `bg-${getAvatarColor(client.id)}-500`
                  ]"
                >
                  {{ getInitials(client.name) }}
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900">
                    {{ client.name }}
                  </p>
                  <p class="text-xs text-gray-500">{{ client.type }}</p>
                </div>
              </div>
            </td>
            <td class="px-4 py-4 text-sm text-gray-900">{{ client.phone }}</td>
            <!-- <td class="px-4 py-4">
              <span
                :class="[
                  'px-2 py-1 text-xs font-medium rounded-full',
                  client.hasInsurance 
                    ? 'bg-green-100 text-green-800' 
                    : 'bg-red-100 text-red-800'
                ]"
              >
                {{ client.hasInsurance ? 'Avec assurance' : 'Sans assurance' }}
              </span>
            </td>
            <td class="px-4 py-4 text-sm text-gray-900">{{ formatDate(client.lastVisit) }}</td>
            <td class="px-4 py-4 text-sm font-semibold text-gray-900">
              {{ formatCurrency(client.totalPurchases) }}
            </td> -->
            <td class="px-4 py-4">
              <div class="flex space-x-2">
                <button
                  @click="editClient(client)"
                  class="p-2 text-gray-400 hover:text-primary !rounded-button hover:bg-gray-50 edit-client"
                >
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
      <div v-if="clients.length === 0" class="text-center py-8">
        <i class="ri-user-search-line text-4xl text-gray-300 mb-2"></i>
        <p class="text-gray-500">Aucun client trouvé</p>
      </div>
    </div>
    <!-- Pagination and Bulk Actions -->
    <div class="px-4 py-3 border-t border-gray-200 flex flex-col md:flex-row md:items-center justify-between">
      <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
        <span class="text-sm text-gray-700 order-2 md:order-1">
          Affichage {{ startIndex }}-{{ endIndex }} sur {{ totalClients }}
        </span>
        <div class="flex flex-wrap justify-center gap-1 order-1 md:order-2">
          <button
            @click="previousPage"
            :disabled="currentPage === 1"
            :class="[
              'px-2 md:px-3 py-1 text-xs md:text-sm !rounded-button',
              currentPage === 1
                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                : 'bg-gray-100 hover:bg-gray-200'
            ]"
          >
            Précédent
          </button>
          <button
            v-for="page in visiblePages"
            :key="page"
            @click="goToPage(page)"
            :class="[
              'px-2 md:px-3 py-1 text-xs md:text-sm !rounded-button',
              page === currentPage
                ? 'bg-primary text-white'
                : 'bg-gray-100 hover:bg-gray-200'
            ]"
          >
            {{ page }}
          </button>
          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            :class="[
              'px-2 md:px-3 py-1 text-xs md:text-sm !rounded-button',
              currentPage === totalPages
                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                : 'bg-gray-100 hover:bg-gray-200'
            ]"
          >
            Suivant
          </button>
        </div>
      </div>
    </div>

  </div>



    <!-- Modal d'ajout/modification -->
    <ClientModal
      :is-open="modalOpen"
      :client="editingClient"
      @close="closeModal"
      @save="saveClient"
    />
</template>

<script>
import { ref, computed, onMounted, watch } from "vue";
import ClientModal from './ClientModal.vue'

export default {
  name: "tableClients",
  components: {
    ClientModal
  },
  computed: {
    transId() {
      return this.$route.params.id;
    },
  },
  setup() {
    // Reactive data
    const clients = ref([]);
    const loading = ref(false);
    const searchQuery = ref("");
    const activeFilter = ref("all");
    const selectedClients = ref([]);
    const selectAll = ref(false);
   
    const modalOpen = ref(false);
    const editingClient = ref(null);

  
    
    // Pagination
    const currentPage = ref(1);
    const itemsPerPage = ref(6);
    const totalClients = ref(0);


    const modalVisible = ref(false);
    
    // Debounce search
    let searchTimeout = null;

    // Computed properties
    const totalPages = computed(() => Math.ceil(totalClients.value / itemsPerPage.value));
    
    const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage.value + 1);
    
    const endIndex = computed(() => {
      const end = currentPage.value * itemsPerPage.value;
      return end > totalClients.value ? totalClients.value : end;
    });
    const visiblePages = computed(() => {
      const pages = [];
      const maxVisiblePages = 3;
      
      let start = Math.max(1, currentPage.value - Math.floor(maxVisiblePages / 2));
      let end = Math.min(totalPages.value, start + maxVisiblePages - 1);
      
      if (end - start + 1 < maxVisiblePages) {
        start = Math.max(1, end - maxVisiblePages + 1);
      }
      
      for (let i = start; i <= end; i++) {
        pages.push(i);
      }
      
      return pages;
    });

    // Methods
    const fetchClients = async () => {
      loading.value = true;
      const url = buildPathClients();
      try {
        // Simulate API call - replace with your actual API endpoint
        const response = await fetch(url , {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            search: searchQuery.value,
            filter: activeFilter.value,
            page: currentPage.value,
            limit: itemsPerPage.value
          })
        });
        
        const data = await response.json();
        clients.value = data.rows || [];
        totalClients.value = data.total || 0;
      } catch (error) {
        console.error('Error fetching clients:', error);
        // Fallback to sample data
        clients.value = getSampleClients();
        totalClients.value = clients.value.length;
      } finally {
        loading.value = false;
      }
    };

     const buildPathClients = () => {
         const baseUrl = mydata.baseUrl;
         const page = currentPage.value ;
         let path = '/api/v2/node/client?fields[]=nid&changes[nid]=id' ;
          path = path + '&fields[]=title&changes[title]=name' ;
          path = path + '&fields[]=field_phone&changes[field_phone]=phone' ;
          path = path + '&pager='+page ;
          
         
         return baseUrl + path ;
     }

    const handleSearch = () => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        currentPage.value = 1;
        fetchClients();
      }, 300);
    };

    const setFilter = (filter) => {
      activeFilter.value = filter;
      currentPage.value = 1;
      fetchClients();
    };

    const toggleSelectAll = () => {
      if (selectAll.value) {
        selectedClients.value = clients.value.map(client => client.id);
      } else {
        selectedClients.value = [];
      }
    };

    const getAvatarColor = (id) => {
      const colors = ['blue', 'green', 'purple', 'orange', 'pink', 'indigo', 'red', 'yellow', 'teal'];
      return colors[id % colors.length];
    };

    const getInitials = (name) => {
      return name.split(' ').map(word => word[0]).join('').toUpperCase();
    };

    const formatDate = (dateString) => {
      const date = new Date(dateString);
      return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
      });
    };

    const formatCurrency = (amount) => {
      return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'MGA',
        minimumFractionDigits: 0
      }).format(amount);
    };

 const openModal = () => {
      editingClient.value = null;
      modalOpen.value = true;
    };

    const closeModal = () => {
      modalOpen.value = false;
      editingClient.value = null;
    };

    const editClient = (client) => {
      editingClient.value = { ...client };
      modalOpen.value = true;
    };

    const saveClient = (clientData) => {
      console.log('Saving client:', clientData);
      closeModal();
    };

    const goToPage = (page) => {
      currentPage.value = page;
      fetchClients();
    };

    const previousPage = () => {
      if (currentPage.value > 1) {
        currentPage.value--;
        fetchClients();
      }
    };

    const nextPage = () => {
      if (currentPage.value < totalPages.value) {
        currentPage.value++;
        fetchClients();
      }
    };

    // Watchers
    watch(selectedClients, (newVal) => {
      selectAll.value = newVal.length === clients.value.length && clients.value.length > 0;
    });

    // Lifecycle
    onMounted(() => {
      fetchClients();
    });

    return {
      clients,
      loading,
      searchQuery,
      activeFilter,
      selectedClients,
      selectAll,
      currentPage,
      totalClients,
      startIndex,
      endIndex,
      totalPages,
      visiblePages,
      handleSearch,
      setFilter,
      toggleSelectAll,
      getAvatarColor,
      getInitials,
      formatDate,
      formatCurrency,
      editClient,
      goToPage,
      previousPage,
      nextPage,
      editingClient,
      modalOpen,
      openModal,
      closeModal,
      editClient,
      saveClient
    };
  },
};
</script>