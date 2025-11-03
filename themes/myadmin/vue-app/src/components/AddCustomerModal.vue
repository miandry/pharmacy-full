<template>
    <!-- Add New Customer Modal -->
    <div id="add-customer-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Ajouter un nouveau client</h3>
                        <button @click="$emit('close-add-customer-modal')" class="text-gray-400 hover:text-gray-600">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="ri-close-line text-xl"></i>
                            </div>
                        </button>
                    </div>
                    <form id="add-customer-form" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                            <input type="text" id="customer-name" name="customer-name" required
                                class="w-full px-3 py-2 border border-gray-300 !rounded-button focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                                placeholder="Ex: Rakoto Andry">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="tel" id="customer-phone" name="customer-phone"
                                class="w-full px-3 py-2 border border-gray-300 !rounded-button focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                                placeholder="Ex: +261 34 12 345 67">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                            <textarea id="customer-address" name="customer-address" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 !rounded-button focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm resize-none"
                                placeholder="Adresse du client (optionnel)"></textarea>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="customer-insurance" name="customer-insurance"
                                class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="customer-insurance" class="text-sm text-gray-700">Ce client a une
                                assurance</label>
                        </div>
                    </form>
                    <div class="flex space-x-3 mt-6">
                        <button @click="$emit('close-add-customer-modal')"
                            class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 !rounded-button font-medium whitespace-nowrap">
                            Annuler
                        </button>
                        <button @click="$emit('close-add-customer-modal')"
                            class="flex-1 px-4 py-2 bg-secondary text-white hover:bg-green-600 !rounded-button font-medium whitespace-nowrap">
                            Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

}
</script>

<style></style>