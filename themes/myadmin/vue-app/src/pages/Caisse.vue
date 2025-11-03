<template>
  <div class="flex flex-col lg:flex-row h-[calc(100vh-80px)]">
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

    <CustomerModal v-if="showCustomerModal" @close="showCustomerModal = false"
      @open-add-customer-modal="openAddCustomerModal" />

    <AddCustomerModal v-if="showAddCustomerModal" @close-add-customer-modal="closeAddCustomerModal" />

    <PaymentModal v-if="showPaymentModal" @close-payment-modal="showPaymentModal = false" />
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import ProductGrid from '../components/caisses/ProductGrid.vue'
import CartSidebar from '../components/caisses/CartSidebar.vue'
import CustomerModal from '../components/caisses/CustomerModal.vue'
import AddCustomerModal from '../components/caisses/AddCustomerModal.vue'
import PaymentModal from '../components/caisses/PaymentModal.vue'

export default {
  name: 'Caisse',
  components: {
    ProductGrid,
    CartSidebar,
    CustomerModal,
    AddCustomerModal,
    PaymentModal
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
    const articles = ref([]);         // Holds the fetched data
    // Fetch data when the component is mounted
    onMounted(() => {
      //  articles.value = (window.drupalSettings?.vueArticles || []);
      //console.log(dataDrupal);
    });

    return {
      articles
    };
  }
}
</script>