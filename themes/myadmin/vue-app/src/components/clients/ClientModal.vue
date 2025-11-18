<template>
  <div>
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
          <input type="hidden" name="nid" v-model="nid">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nom complet</label>
            <input type="text" v-model="formData.name"
              class="w-full px-3 py-2 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="Entrez le nom complet" required />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Âge</label>
            <input type="number" min="1" v-model="formData.age"
              class="w-full px-3 py-2 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="ex: 12" required />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de téléphone</label>
            <input type="tel" v-model="formData.phone"
              class="w-full px-3 py-2 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="+261 XX XX XXX XX" required />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
            <textarea v-model="formData.address" rows="2"
              class="w-full px-3 py-2 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="Adresse complète"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Statut assurance</label>
            <div class="flex space-x-4">
              <label class="flex items-center">
                <input type="radio" name="insurance" value="1" v-model="formData.insurance"
                  class="w-4 h-4 text-primary border-gray-300 focus:ring-primary" />
                <span class="ml-2 text-sm text-gray-700">Avec assurance</span>
              </label>
              <label class="flex items-center">
                <input type="radio" name="insurance" value="0" checked v-model="formData.insurance"
                  class="w-4 h-4 text-primary border-gray-300 focus:ring-primary" />
                <span class="ml-2 text-sm text-gray-700">Sans assurance</span>
              </label>
            </div>
          </div>
          <div class="flex space-x-3 pt-4">
            <button type="button" @click="closeModal"
              class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 !rounded-button font-medium whitespace-nowrap">
              Annuler
            </button>
            <button type="submit"
              class="flex-1 px-4 py-2 bg-primary hover:bg-blue-600 text-white !rounded-button font-medium whitespace-nowrap">
              Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, reactive, ref } from 'vue';

export default {
  name: 'ClientModal',
  props: {
    isOpen: {
      type: Boolean,
      required: true
    },
    client: {
      type: Object,
      default: null
    }
  },
  emits: ['close'],
  setup(_, { emit }) {
    // Modal state
    const modalMode = ref("add");

    // Form data
    const formData = reactive({
      nid: "",
      name: "",
      phone: "",
      address: "",
      insurance: 0,
      notes: ""
    });

    // Computed modal title
    const modalTitle = computed(() => {
      return modalMode.value === "add" ? "Ajouter un Client" : "Modifier le Client";
    });


    const resetForm = () => {
      Object.assign(formData, {
        name: "",
        phone: "",
        address: "",
        insurance: 0,
        notes: ""
      });
    };

    const handleSubmit = () => {
      console.log("Form submitted:", formData);
      closeModal();
      // alert(modalMode.value === "add" ? "Client ajouté avec succès!" : "Client modifié avec succès!");
    };

    const editClient = (client) => {
      showModal("edit", client);
    };

    const closeModal = () => {
      emit('close');
    }

    return {
      modalTitle,
      modalMode,
      formData,
      handleSubmit,
      editClient,
      closeModal
    }
  }
}
</script>