<template>
  <div class="p-4 md:p-6">
    <PageLoader v-if="store.loading" />
    <rapportClients @show="showModal" />
    <tableClients :clients="store.clients" @searchKeyWords="onSearch" @filterBy="onfilter" @paginate="onPagination"
      @show="showModal" />
    <!-- Client Modal -->
    <client-modal @close="closeModal" @show="showModal"
      :class="[modalVisible ? 'flex' : 'hidden', 'fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4']" />
  </div>
</template>
<script>
import { onMounted, ref } from 'vue';
import { useClientStore } from '../stores/index.js';
import tableClients from '../components/clients/tableClients.vue';
import rapportClients from '../components/clients/rapportClients.vue';
import PageLoader from '../components/PageLoader.vue';
import ClientModal from '../components/clients/ClientModal.vue';


export default {
  name: "Clients",
  components: { tableClients, PageLoader, rapportClients, ClientModal },
  setup() {
    const modalVisible = ref(false);
    const store = useClientStore();
    // Paramètres dynamiques de la requête
    const queryOptions = ref({
      fields: [
        'nid',
        'title',
        'field_phone',
        'field_assurance',
        'field_adresse',
        'field_age',
        'created',
      ],
      sort: { val: 'nid', op: 'desc' },
      filters: {},
      pager: 0,
      offset: 10
    })

    const fetchClients = async () => {
      await store.fetchClients(queryOptions.value);
    }

    const onSearch = async (value) => {
      updateFilter('title', value, 'CONTAINS')
      fetchClients()
    }

    const onfilter = async (value) => {
      if (value == "all") {
        value = null;
      }
      updateFilter('field_assurance', value)
      fetchClients()
    }

    const onPagination = async (value) => {
      queryOptions.value.pager = value - 1;
      fetchClients()
    }

    const updateFilter = (key, value, op = null) => {
      if (value === null || value === undefined || value === '') {
        delete queryOptions.value.filters[key];
      } else {
        queryOptions.value.filters[key] = { val: value, op };
      }
    }

    // Modal functions
    const showModal = (client = null) => {
      modalVisible.value = true;
    };

    const closeModal = () => {
      modalVisible.value = false;
    };

    onMounted(() => fetchClients());

    return {
      store,
      queryOptions,
      onSearch,
      onfilter,
      onPagination,
      modalVisible,
      showModal,
      closeModal
    }
  }

}
</script>
