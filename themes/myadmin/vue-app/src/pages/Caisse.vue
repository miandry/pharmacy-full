<template>
  <div class="flex flex-col lg:flex-row h-[calc(100vh-80px)]">
    <!-- Product Grid Section -->
    <div class="flex-1 p-3 order-2 lg:order-1 flex flex-col">
      <ProductGrid />
    </div>
    
    <!-- Cart Sidebar -->
    <div class="w-full lg:w-80 bg-white border-t lg:border-t-0 lg:border-l border-gray-200 flex flex-col order-1 lg:order-2 h-full">
      <CartSidebar />
    </div>
    
    <!-- Modals -->
    <!-- <PaymentModal v-if="showPaymentModal" @close="showPaymentModal = false" @confirm="handlePaymentConfirm" />
    <CustomerModal v-if="showCustomerModal" @close="showCustomerModal = false" @confirm="handleCustomerSelect" />
    <AddCustomerModal v-if="showAddCustomerModal" @close="showAddCustomerModal = false" @save="handleAddCustomer" /> -->
  </div>
</template>

<script>
import ProductGrid from '../components/ProductGrid.vue'
import CartSidebar from '../components/CartSidebar.vue'

export default {
  name: 'Caisse',
  components: {
    ProductGrid,
    CartSidebar,
  },
  data() {
    return {
      // showPaymentModal: false,
      // showCustomerModal: false,
      // showAddCustomerModal: false
    }
  },
  setup() {
    // const posStore = usePOSStore()
    // return { posStore }
  },
  methods: {
    // handlePaymentConfirm() {
    //   // Logique de confirmation de paiement
    //   this.showPaymentModal = false
    //   this.$notify({
    //     type: 'success',
    //     message: 'Vente finalisée avec succès'
    //   })
    //   // Redirection ou autre logique
    // },
    
    // handleCustomerSelect(customer) {
    //   this.posStore.setCustomer(customer)
    //   this.showCustomerModal = false
    // },
    
    // handleAddCustomer(customer) {
    //   this.posStore.addCustomer(customer)
    //   this.showAddCustomerModal = false
    //   this.showCustomerModal = false
    // },
    
    // openPaymentModal() {
    //   this.showPaymentModal = true
    // },
    
    // openCustomerModal() {
    //   this.showCustomerModal = true
    // },
    
    // openAddCustomerModal() {
    //   this.showAddCustomerModal = true
    //   this.showCustomerModal = false
    // }
  }
}
</script>