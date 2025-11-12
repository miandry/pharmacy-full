<template>
    <div class="bg-white border-t lg:border-t-0 lg:border-l border-gray-200 h-auto">
        <div class="p-3 border-b border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-semibold text-gray-900">Commande actuelle</h2>
                <button class="text-xs text-gray-500 hover:text-primary" @click="articleStore.clearCart">Tout
                    effacer</button>
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
                    <div class="text-center text-gray-300 w-full">
                        Aucun client sélectionné
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
            <div class="space-y-2 mb-3 max-h-68 overflow-y-auto" id="cart-items" v-if="articleStore.cardItems.length">
                <div class="flex items-center justify-between py-2 border-b border-gray-100 gap-2"
                    v-for="item in articleStore.cardItems" :key="item.nid">
                    <div class="flex-1 min-w-0 pr-2">
                        <h3 class="font-medium text-gray-900 text-xs truncate">{{ item.title }}</h3>
                        <p class="text-xs text-gray-500">{{ item.field_prix_unitaire }} Ar chacun</p>
                    </div>
                    <div class="flex items-center space-x-1">
                        <button @click="decrementQuantity(item)"
                            class="w-6 h-6 flex items-center justify-center bg-gray-100 hover:bg-gray-200 !rounded-button">
                            <i class="ri-subtract-line text-xs"></i>
                        </button>
                        <span class="w-4 text-center font-medium text-xs">{{ item.quantity }}</span>
                        <button @click="incrementQuantity(item)"
                            class="w-6 h-6 flex items-center justify-center bg-gray-100 hover:bg-gray-200 !rounded-button">
                            <i class="ri-add-line text-xs"></i>
                        </button>
                    </div>
                    <div class="w-22 text-right font-semibold text-primary text-xs">{{ (item.field_prix_unitaire *
                        item.quantity).toLocaleString() }} Ar</div>
                </div>
            </div>
            <div class="space-y-2 mb-3 max-h-32 overflow-y-auto text-center" v-else>
                <p class="py-3 text-gray-400">Aucun article sélectionné</p>
            </div>

            <div class="space-y-1 mb-3">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-600">Sous-total</span>
                    <span class="font-medium">{{ articleStore.total.toLocaleString() }} Ar</span>
                </div>
                <div class="flex justify-between text-xs hidden">
                    <span class="text-gray-600">TVA (20%)</span>
                    <span class="font-medium">6,640 Ar</span>
                </div>
                <div class="border-t border-gray-200 pt-1">
                    <div class="flex justify-between text-sm font-semibold">
                        <span>Total</span>
                        <span class="text-primary">{{ articleStore.total.toLocaleString() }} Ar</span>
                    </div>
                </div>
            </div>
            <div class="space-y-2 mb-3">
                <button @click="creatOrder"
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
            <div class="mb-2 hidden">
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
                @click="handleFinalizeSale">
                Finaliser la vente
            </button>
        </div>
    </div>
</template>

<script>
import { toast } from 'vue-sonner';
import { useArticleStore, useClientStore, useOrderStore } from '../../stores/index.js';

export default {
    name: 'CardSidebar',
    setup(_, { emit }) {
        const store = useClientStore();
        const articleStore = useArticleStore();

        function incrementQuantity(item) {
            articleStore.incrementQuantity(item);
        }

        function decrementQuantity(item) {
            articleStore.decrementQuantity(item);
        }

        function removeItem(item) {
            articleStore.removeItem(item);
        }

        // Validation client et articles avant de sauvegarder
        function saveCurrentOrder() {
            if (!store.client || !store.client.nid) {
                toast.error("Veuillez sélectionner un client avant de sauvegarder la commande.")
                return null;
            }
            if (!articleStore.cardItems.length) {
                toast.error("Ajoutez au moins un article.")
                return null;
            }
            return articleStore.saveOrder(store.client);
        }

        function handleFinalizeSale() {
            const order = saveCurrentOrder();
            if (order) {
                emit('open-payment-modal');
            }
        }

        const orderStore = useOrderStore();
        const creatOrder = async function () {
            const order = saveCurrentOrder();
            if (order) {
                try {
                    orderStore.loading = true;
                    const orderToCreate = articleStore.savedOrder;
                    const allArticles = orderToCreate.items.map(item => ({
                        entity_type: "paragraph",
                        bundle: "commande",
                        field_article: item.nid,
                        field_quantite: item.quantity,
                        field_prix_d_achat: item.field_prix_unitaire,
                        field_prix_unitaire: item.field_prix_unitaire,
                    }));

                    const data = {
                        entity_type: "node",
                        bundle: "commande",
                        title: "order " + orderToCreate.clientName,
                        field_client: orderToCreate.clientId,
                        clientName: orderToCreate.clientName,
                        field_articles: allArticles,
                        field_total_vente: orderToCreate.total,
                        status: 1,
                        field_status: "unpayed"
                    };
                    await orderStore.createOrder(data);
                    if (orderStore.error) {
                        toast.error("Une erreur est survenue lors de l'ajout du commande.")
                        return
                    }
                    articleStore.clearCart();
                    orderStore.loading = false;
                    toast.success('Commande ajouté avec succès !')
                } catch (err) {
                    toast.error("Une erreur inattendue est survenue.");
                } finally {
                    orderStore.loading = false;
                }
            }
        }


        return {
            store,
            articleStore,
            incrementQuantity,
            decrementQuantity,
            removeItem,
            saveCurrentOrder,
            handleFinalizeSale,
            creatOrder
        }
    }
}
</script>

<style>
.max-h-68 {
    max-height: 22rem;
}
</style>