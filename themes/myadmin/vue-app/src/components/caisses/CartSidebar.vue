<template>
    <div>
        <div class="p-3 border-b border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-semibold text-gray-900">Commande actuelle</h2>
                <button class="text-xs text-gray-500 hover:text-primary">Tout effacer</button>
            </div>
            <div class="mb-3 p-2 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Client</span>
                    <button class="text-xs text-primary hover:underline" @click="$emit('open-customer-modal')">
                        {{ store.client && store.client.nid ? 'Changer' : 'Ajouter' }}
                    </button>
                </div>
                <div class="flex items-center space-x-2 mb-2" v-if="store.client && store.client.nid">
                    <div
                        class="w-8 h-8 bg-primary text-white uppercase rounded-full flex items-center justify-center text-sm font-medium">
                        {{ store.client.title.slice(0, 2) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 capitalize">{{ store.client.title }}</p>
                        <p class="text-xs text-gray-500">{{ store.client.field_phone }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 mb-2" v-else>
                    <div class="text-center text-gray-300 py-2 w-full">
                        Aucun client
                    </div>
                </div>
            
                <div class="flex items-center justify-between hidden" v-if="store.client && store.client.nid">
                    <span class="text-xs text-gray-600">Assurance</span>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" id="insurance-toggle"
                            class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <span class="text-xs font-medium" id="insurance-status">Non</span>
                    </label>
                </div>
            </div>
            <div class="space-y-2 mb-3 max-h-32 overflow-y-auto" id="cart-items">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div class="flex-1 min-w-0 pr-2">
                        <h3 class="font-medium text-gray-900 text-xs truncate">Paracetamol 500mg</h3>
                        <p class="text-xs text-gray-500">12,500 Ar each</p>
                    </div>
                    <div class="flex items-center space-x-1">
                        <button
                            class="w-6 h-6 flex items-center justify-center bg-gray-100 hover:bg-gray-200 !rounded-button">
                            <i class="ri-subtract-line text-xs"></i>
                        </button>
                        <span class="w-4 text-center font-medium text-xs">2</span>
                        <button
                            class="w-6 h-6 flex items-center justify-center bg-gray-100 hover:bg-gray-200 !rounded-button">
                            <i class="ri-add-line text-xs"></i>
                        </button>
                    </div>
                    <div class="w-14 text-right font-semibold text-primary text-xs">25,000 Ar</div>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 text-xs">Vitamin C 1000mg</h3>
                        <p class="text-xs text-gray-500">8,200 Ar each</p>
                    </div>
                    <div class="flex items-center space-x-1">
                        <button
                            class="w-6 h-6 flex items-center justify-center bg-gray-100 hover:bg-gray-200 !rounded-button">
                            <i class="ri-subtract-line text-xs"></i>
                        </button>
                        <span class="w-4 text-center font-medium text-xs">1</span>
                        <button
                            class="w-6 h-6 flex items-center justify-center bg-gray-100 hover:bg-gray-200 !rounded-button">
                            <i class="ri-add-line text-xs"></i>
                        </button>
                    </div>
                    <div class="w-14 text-right font-semibold text-primary text-xs">8,200 Ar</div>
                </div>
            </div>
            <div class="space-y-1 mb-3">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-600">Sous-total</span>
                    <span class="font-medium">33,200 Ar</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-600">TVA (20%)</span>
                    <span class="font-medium">6,640 Ar</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-600">Remise</span>
                    <span class="font-medium text-secondary">-0 Ar</span>
                </div>
                <div class="border-t border-gray-200 pt-1">
                    <div class="flex justify-between text-sm font-semibold">
                        <span>Total</span>
                        <span class="text-primary">39,840 Ar</span>
                    </div>
                </div>
            </div>
            <div class="space-y-2 mb-3">
                <button
                    class="w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 !rounded-button font-medium whitespace-nowrap flex items-center justify-center space-x-2 text-xs">
                    <div class="w-4 h-4 flex items-center justify-center">
                        <i class="ri-percent-line"></i>
                    </div>
                    <span>Appliquer remise</span>
                </button>
                <button
                    class="w-full py-2 bg-orange-100 hover:bg-orange-200 text-orange-700 !rounded-button font-medium whitespace-nowrap flex items-center justify-center space-x-2 text-xs"
                    id="save-unpaid">
                    <div class="w-4 h-4 flex items-center justify-center">
                        <i class="ri-save-line"></i>
                    </div>
                    <span>Sauvegarder as non payé</span>
                </button>
            </div>
        </div>
        <div class="flex-1 p-3">
            <div class="mb-2">
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-600">Montant reçu</span>
                    <span class="font-medium" id="amount-received">45,000 Ar</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-600">Monnaie à rendre</span>
                    <span class="font-medium text-secondary" id="change-due">5,160 Ar</span>
                </div>
            </div>
            <button
                class="w-full py-2 bg-secondary hover:bg-green-600 text-white !rounded-button font-semibold text-sm whitespace-nowrap"
                @click="$emit('open-payment-modal')">
                Finaliser la vente
            </button>
        </div>
    </div>
</template>

<script>
import { useClientStore } from '../../stores/index.js';

export default {
    name: 'CardSidebar',
    setup() {
        const store = useClientStore();
        return {
            store
        }
    }
}
</script>

<style></style>