<template>
  <div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">
        Gestion des Clients
      </h2>
      <button
        class="px-6 py-3 bg-primary hover:bg-blue-600 text-white !rounded-button font-medium whitespace-nowrap flex items-center space-x-2"
        id="add-client-btn" @click="showModal">
        <div class="w-5 h-5 flex items-center justify-center">
          <i class="ri-add-line"></i>
        </div>
        <span>Ajouter un Client</span>
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="bg-white p-4 rounded-lg border border-gray-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Total Clients</p>
            <p class="text-2xl font-bold text-gray-900">{{ clientStore.allClients.total }}</p>
          </div>
          <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-blue-100">
            <i class="ri-user-line text-xl text-primary"></i>
          </div>
        </div>
      </div>
      <div class="bg-white p-4 rounded-lg border border-gray-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Avec Assurance</p>
            <p class="text-2xl font-bold text-gray-900">{{ withInsurance }}</p>
          </div>
          <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-green-100">
            <i class="ri-shield-check-line text-xl text-secondary"></i>
          </div>
        </div>
      </div>
      <div class="bg-white p-4 rounded-lg border border-gray-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Nouveaux ce mois</p>
            <p class="text-2xl font-bold text-gray-900">{{ countThisMonth }}</p>
          </div>
          <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-orange-100">
            <i class="ri-user-add-line text-xl text-orange-500"></i>
          </div>
        </div>
      </div>
      <div class="bg-white p-4 rounded-lg border border-gray-200 hidden">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">CA Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ withInsurance }}</p>
          </div>
          <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-purple-100">
            <i class="ri-money-dollar-circle-line text-xl text-purple-500"></i>
          </div>
        </div>
      </div>

    </div>

  </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import { useClientStore } from "../../stores/index.js";


export default {
  name: "rapportClients",
  emit: ['show'],
  setup(_, { emit }) {
    const clientStore = useClientStore();
    // Paramètres dynamiques de la requête
    const queryOptions = ref({
      fields: [
        'nid',
        'field_assurance',
        'createdx '
      ],
      pager: 0,
      offset: 2000
    })

    const fetchClients = async () => {
      await clientStore.fetchAllClients(queryOptions.value);
    }

    const withInsurance = computed(() =>
      clientStore.allClients.rows.filter(c => c.field_assurance == 1).length
    );

    const countThisMonth = computed(() => {
      const now = new Date();
      const month = now.getMonth();
      const year = now.getFullYear();

      return clientStore.allClients.rows.filter(c => {
        const d = new Date(c.created * 1000);
        return d.getMonth() === month && d.getFullYear() === year;
      }).length;
    });

    const showModal = () => {
      emit('show');
    }

    onMounted(() => {
      fetchClients();
    });

    return {
      // Methods
      showModal,
      // custom
      clientStore,
      withInsurance,
      countThisMonth
    };
  },
};
</script>