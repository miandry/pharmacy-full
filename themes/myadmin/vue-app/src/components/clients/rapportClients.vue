<template>
  <div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">
        Gestion des Clients
      </h2>
      <button
        class="px-6 py-3 bg-primary hover:bg-blue-600 text-white !rounded-button font-medium whitespace-nowrap flex items-center space-x-2"
        id="add-client-btn" @click="showModal('add')">
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
            <p class="text-2xl font-bold text-gray-900">{{ clients.total }}</p>
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

    <!-- Client Modal -->
    <div id="client-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
      :class="modalVisible ? 'flex' : 'hidden'">
      <div class="bg-white rounded-lg w-full max-w-md">
        <div class="p-6">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900" id="modal-title">
              {{ modalTitle }}
            </h3>
            <button class="text-gray-400 hover:text-gray-600" @click="closeModal">
              <div class="w-6 h-6 flex items-center justify-center">
                <i class="ri-close-line text-xl"></i>
              </div>
            </button>
          </div>
          <form @submit.prevent="handleSubmit" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ formLabels.name }}</label>
              <input type="text" v-model="formData.name"
                class="w-full px-3 py-2 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                :placeholder="formPlaceholders.name" required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ formLabels.phone }}</label>
              <input type="tel" v-model="formData.phone"
                class="w-full px-3 py-2 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                :placeholder="formPlaceholders.phone" required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ formLabels.address }}</label>
              <textarea v-model="formData.address" rows="2"
                class="w-full px-3 py-2 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                :placeholder="formPlaceholders.address"></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ formLabels.insurance }}</label>
              <div class="flex space-x-4">
                <label class="flex items-center">
                  <input type="radio" name="insurance" value="yes" v-model="formData.insurance"
                    class="w-4 h-4 text-primary border-gray-300 focus:ring-primary" />
                  <span class="ml-2 text-sm text-gray-700">{{ insuranceOptions.with }}</span>
                </label>
                <label class="flex items-center">
                  <input type="radio" name="insurance" value="no" v-model="formData.insurance"
                    class="w-4 h-4 text-primary border-gray-300 focus:ring-primary" />
                  <span class="ml-2 text-sm text-gray-700">{{ insuranceOptions.without }}</span>
                </label>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ formLabels.notes }}</label>
              <textarea v-model="formData.notes" rows="2"
                class="w-full px-3 py-2 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                :placeholder="formPlaceholders.notes"></textarea>
            </div>
            <div class="flex space-x-3 pt-4">
              <button type="button" @click="closeModal"
                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 !rounded-button font-medium whitespace-nowrap">
                {{ cancelButtonText }}
              </button>
              <button type="submit"
                class="flex-1 px-4 py-2 bg-primary hover:bg-blue-600 text-white !rounded-button font-medium whitespace-nowrap">
                {{ saveButtonText }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, computed, onMounted } from "vue";

export default {
  name: "rapportClients",
  props: {
    clients: {
      type: Array,
      required: true
    }
  },
  setup(props, { emit }) {

    const withInsurance = computed(() =>
      props.clients.rows.filter(c => c.field_assurance == 1).length
    );

    const countThisMonth = computed(() => {
      const now = new Date();
      const month = now.getMonth();
      const year = now.getFullYear();

      return props.clients.rows.filter(c => {
        const d = new Date(c.created * 1000);
        return d.getMonth() === month && d.getFullYear() === year;
      }).length;
    });

    // Static variables
    const pageTitle = "Gestion des Clients";
    const addButtonText = "Ajouter un Client";
    const cancelButtonText = "Annuler";
    const saveButtonText = "Enregistrer";

    // Form labels and placeholders
    const formLabels = {
      name: "Nom complet",
      phone: "Numéro de téléphone",
      address: "Adresse",
      insurance: "Statut assurance",
      notes: "Notes"
    };

    const formPlaceholders = {
      name: "Entrez le nom complet",
      phone: "+261 XX XX XXX XX",
      address: "Adresse complète",
      notes: "Notes additionnelles"
    };

    const insuranceOptions = {
      with: "Avec assurance",
      without: "Sans assurance"
    };

    // Modal state
    const modalVisible = ref(false);
    const modalMode = ref("add"); // "add" or "edit"

    // Form data
    const formData = reactive({
      name: "",
      phone: "",
      address: "",
      insurance: "no",
      notes: ""
    });

    // Computed modal title
    const modalTitle = computed(() => {
      return modalMode.value === "add" ? "Ajouter un Client" : "Modifier le Client";
    });

    // Modal functions
    const showModal = (mode = "add", clientData = null) => {
      modalMode.value = mode;

      if (mode === "edit" && clientData) {
        // Populate form with existing client data
        Object.assign(formData, clientData);
      } else {
        // Reset form for adding new client
        resetForm();
      }

      modalVisible.value = true;
    };

    const closeModal = () => {
      modalVisible.value = false;
      resetForm();
    };

    const resetForm = () => {
      Object.assign(formData, {
        name: "",
        phone: "",
        address: "",
        insurance: "no",
        notes: ""
      });
    };

    const handleSubmit = () => {
      // Handle form submission here
      console.log("Form submitted:", formData);

      // In a real application, you would:
      // 1. Validate the form data
      // 2. Send API request to add/edit client
      // 3. Update the UI accordingly

      // For now, just close the modal and reset the form
      closeModal();

      // Show success message
      alert(modalMode.value === "add" ? "Client ajouté avec succès!" : "Client modifié avec succès!");
    };

    // Example of how to trigger edit mode
    const editClient = (client) => {
      showModal("edit", client);
    };

    onMounted(() => {
      // You can fetch initial data here if needed
      // articles.value = (window.drupalSettings?.vueArticles || []);
    });

    return {
      // Static variables
      pageTitle,
      addButtonText,
      cancelButtonText,
      saveButtonText,
      formLabels,
      formPlaceholders,
      insuranceOptions,

      // Reactive data
      modalVisible,
      modalMode,
      formData,

      // Computed
      modalTitle,

      // Methods
      showModal,
      closeModal,
      handleSubmit,
      editClient,

      // custom
      withInsurance,
      countThisMonth
    };
  },
};
</script>