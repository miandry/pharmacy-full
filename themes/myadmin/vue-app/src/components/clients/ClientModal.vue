<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-md">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">
             Formulaire un Client
          </h3>
          <button class="text-gray-400 hover:text-gray-600" @click="$emit('close')">
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ri-close-line text-xl"></i>
            </div>
          </button>
        </div>
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Nom complet
            </label>
            <input
              type="text"
              v-model="form.name"
              class="w-full px-3 py-2 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="Entrez le nom complet"
              required
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Numéro de téléphone
            </label>
            <input
              type="tel"
              v-model="form.phone"
              class="w-full px-3 py-2 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="+261 XX XX XXX XX"
              required
            />
          </div>
          <div class="flex space-x-3 pt-4">
            <button
              type="button"
              @click="$emit('close')"
              class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 !rounded-button font-medium whitespace-nowrap"
            >
              Annuler
            </button>
            <button
              type="submit"
              class="flex-1 px-4 py-2 bg-primary hover:bg-blue-600 text-white !rounded-button font-medium whitespace-nowrap"
            >
              Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
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
  emits: ['close', 'save'],
  data() {
    return {
      form: {
        name: '',
        phone: '',
        address: '',
        insurance: 'no',
        notes: ''
      }
    }
  },
  watch: {
    client: {
      handler(newClient) {
        if (newClient) {
          this.form = {
            name: newClient.name,
            phone: newClient.phone,
            address: newClient.address || '',
            insurance: newClient.insurance ? 'yes' : 'no',
            notes: newClient.notes || ''
          }
        } else {
          this.resetForm()
        }
      },
      immediate: true
    }
  },
  methods: {
    handleSubmit() {
      this.$emit('save', {
        ...this.form,
        insurance: this.form.insurance === 'yes'
      })
      this.resetForm()
    },
    resetForm() {
      this.form = {
        name: '',
        phone: '',
        address: '',
        insurance: 'no',
        notes: ''
      }
    }
  }
}
</script>