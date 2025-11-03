<template>
    <!-- Customer Selection Modal -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Sélectionner un client</h3>
                        <button  @click="$emit('close')" class="text-gray-400 hover:text-gray-600">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="ri-close-line text-xl"></i>
                            </div>
                        </button>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                        <div class="space-y-3">
                            <select id="customer-select"
                                class="w-full px-3 py-2 border border-gray-300 !rounded-button focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                                <option value="0">Rakoto Andry - +261 34 12 345 67</option>
                                <option value="1">Rabe Hery - +261 33 98 765 43</option>
                                <option value="2">Rasoa Marie - +261 32 55 123 89</option>
                                <option value="3">Randriamampionona Jean - +261 34 87 654 32</option>
                                <option value="4">Rasoanirina Paul - +261 33 11 223 44</option>
                                <option value="5">Client de passage - Pas de téléphone</option>
                            </select>
                            <button @click="$emit('open-add-customer-modal')"
                                class="w-full px-3 py-2 border-2 border-dashed border-gray-300 hover:border-primary text-gray-600 hover:text-primary !rounded-button font-medium text-sm whitespace-nowrap flex items-center justify-center space-x-2">
                                <div class="w-4 h-4 flex items-center justify-center">
                                    <i class="ri-add-line"></i>
                                </div>
                                <span>Ajouter un nouveau client</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <button @click="$emit('close')"
                            class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 !rounded-button font-medium whitespace-nowrap">
                            Annuler
                        </button>
                        <button  @click="$emit('close')"
                            class="flex-1 px-4 py-2 bg-primary text-white hover:bg-blue-600 !rounded-button font-medium whitespace-nowrap">
                            Confirmer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CustomerModal",
    methods: {
        confirmPayment() {
            this.$emit('confirm', this.amountReceived)
            this.$emit('close')
        }
    }
}
</script>

<style></style>