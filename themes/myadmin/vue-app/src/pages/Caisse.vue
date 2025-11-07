<template>
  <div class="flex flex-col lg:flex-row h-[calc(100vh-80px)]">
    <PageLoader v-if="clientStore.loading || articleStore.loading || orderStore.loading" />
    <!-- Product Grid Section -->
    <div class="flex-1 p-3 order-2 lg:order-1 flex flex-col">
      <ProductGrid />
    </div>

    <!-- Cart Sidebar -->
    <div
      class="w-full lg:w-80 bg-white border-t lg:border-t-0 lg:border-l border-gray-200 flex flex-col order-1 lg:order-2 h-full">
      <CartSidebar @open-customer-modal="showCustomerModal = true" @open-payment-modal="showPaymentModal = true" />
    </div>

    <!-- Modals -->

    <ClientModal v-if="showCustomerModal" @close="showCustomerModal = false"
      @open-add-customer-modal="openAddCustomerModal" />

    <AddClientModal v-if="showAddCustomerModal" @close-add-customer-modal="closeAddCustomerModal" @close-client-modal="showCustomerModal = false" />

    <PaymentModal v-if="showPaymentModal" @close-payment-modal="showPaymentModal = false" />
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import ProductGrid from '../components/caisses/ProductGrid.vue'
import CartSidebar from '../components/caisses/CartSidebar.vue'
import ClientModal from '../components/caisses/ClientModal.vue'
import AddClientModal from '../components/caisses/AddClientModal.vue'
import PaymentModal from '../components/caisses/PaymentModal.vue'
import PageLoader from '../components/PageLoader.vue';
import { useArticleStore, useClientStore, useOrderStore } from '../stores/index.js';

export default {
  name: 'Caisse',
  components: {
    ProductGrid,
    CartSidebar,
    ClientModal,
    AddClientModal,
    PaymentModal,
    PageLoader
  },
  data() {
    return {
      showCustomerModal: false,
      showAddCustomerModal: false,
      showPaymentModal: false
    }
  },
  methods: {
    openAddCustomerModal() {
      this.showCustomerModal = false;
      this.showAddCustomerModal = true;
    },
    closeAddCustomerModal() {
      this.showAddCustomerModal = false;
      this.showCustomerModal = true;
    }
  },
  setup() {
    const clientStore = useClientStore();
    const articleStore = useArticleStore();
    const orderStore = useOrderStore();

    return {
      clientStore,
      articleStore,
      orderStore
    };
  }
}
</script>