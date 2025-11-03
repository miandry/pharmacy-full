<template>
    <!-- Payment Calculator Modal -->
    <div id="payment-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Finaliser le paiement</h3>
                        <button @click="$emit('close-payment-modal')" class="text-gray-400 hover:text-gray-600">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="ri-close-line text-xl"></i>
                            </div>
                        </button>
                    </div>
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Total à payer</span>
                            <span class="font-semibold text-primary" id="modal-total">39,840 Ar</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Montant reçu</span>
                            <span class="font-medium" id="modal-amount-received">45,000 Ar</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Monnaie à rendre</span>
                            <span class="font-medium text-secondary" id="modal-change-due">5,160 Ar</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="grid grid-cols-3 gap-2" id="modal-numpad">
                            <button
                                class="h-12 bg-gray-100 hover:bg-gray-200 !rounded-button font-semibold text-lg whitespace-nowrap">1</button>
                            <button
                                class="h-12 bg-gray-100 hover:bg-gray-200 !rounded-button font-semibold text-lg whitespace-nowrap">2</button>
                            <button
                                class="h-12 bg-gray-100 hover:bg-gray-200 !rounded-button font-semibold text-lg whitespace-nowrap">3</button>
                            <button
                                class="h-12 bg-gray-100 hover:bg-gray-200 !rounded-button font-semibold text-lg whitespace-nowrap">4</button>
                            <button
                                class="h-12 bg-gray-100 hover:bg-gray-200 !rounded-button font-semibold text-lg whitespace-nowrap">5</button>
                            <button
                                class="h-12 bg-gray-100 hover:bg-gray-200 !rounded-button font-semibold text-lg whitespace-nowrap">6</button>
                            <button
                                class="h-12 bg-gray-100 hover:bg-gray-200 !rounded-button font-semibold text-lg whitespace-nowrap">7</button>
                            <button
                                class="h-12 bg-gray-100 hover:bg-gray-200 !rounded-button font-semibold text-lg whitespace-nowrap">8</button>
                            <button
                                class="h-12 bg-gray-100 hover:bg-gray-200 !rounded-button font-semibold text-lg whitespace-nowrap">9</button>
                            <button
                                class="h-12 bg-gray-100 hover:bg-gray-200 !rounded-button font-semibold text-lg whitespace-nowrap">.</button>
                            <button
                                class="h-12 bg-gray-100 hover:bg-gray-200 !rounded-button font-semibold text-lg whitespace-nowrap">0</button>
                            <button
                                class="h-12 bg-red-100 hover:bg-red-200 text-red-600 !rounded-button font-semibold text-lg whitespace-nowrap flex items-center justify-center">
                                <div class="w-5 h-5 flex items-center justify-center">
                                    <i class="ri-delete-back-2-line"></i>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <button @click="$emit('close-payment-modal')"
                            class="flex-1 px-4 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 !rounded-button font-medium whitespace-nowrap">
                            Annuler
                        </button>
                        <button @click="$emit('close-payment-modal')"
                            class="flex-1 px-4 py-3 bg-secondary text-white hover:bg-green-600 !rounded-button font-medium whitespace-nowrap">
                            Confirmer la vente
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