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
                            <span class="font-semibold text-primary">{{ orderToCreate.total.toLocaleString() }}
                                Ar</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Montant reçu</span>
                            <span class="font-medium" id="modal-amount-received">{{ formattedAmountReceived }} Ar</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Monnaie à rendre</span>
                            <span class="font-medium text-secondary" id="modal-change-due">{{ changeDue >= 0 ?
                                changeDue.toLocaleString() + ' Ar' : '0 Ar' }}</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="grid grid-cols-3 gap-2" id="modal-numpad">
                            <button v-for="n in ['1', '2', '3', '4', '5', '6', '7', '8', '9', '.', '0']" :key="n"
                                @click="handleNumpadClick(n)"
                                class="h-12 bg-gray-100 hover:bg-gray-200 !rounded-button font-semibold text-lg">
                                {{ n }}
                            </button>
                            <button @click="handleNumpadClick('delete')"
                                class="h-12 bg-red-100 hover:bg-red-200 text-red-600 !rounded-button font-semibold text-lg flex items-center justify-center">
                                <i class="ri-delete-back-2-line"></i>
                            </button>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <button @click="$emit('close-payment-modal')"
                            class="flex-1 px-4 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 !rounded-button font-medium whitespace-nowrap">
                            Annuler
                        </button>
                        <button @click="saveOrder"
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
import { toast } from 'vue-sonner';
import { useArticleStore, useOrderStore } from '../../stores';
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';

export default {
    name: "PaymentModal",
    setup(_, { emit }) {
        const articleStore = useArticleStore();
        const orderStore = useOrderStore();
        const orderToCreate = articleStore.savedOrder;
        const amountReceived = ref('');

        const changeDue = computed(() => {
            const total = orderToCreate.total || 0;
            const received = parseFloat(amountReceived.value.replace(/,/g, '')) || 0;
            return received - total;
        });

        const handleNumpadClick = (value) => {
            if (value === 'delete') {
                amountReceived.value = amountReceived.value.slice(0, -1);
            } else {
                amountReceived.value += value;
            }
        }

        const formattedAmountReceived = computed(() => {
            if (!amountReceived.value) return '0';
            const numeric = parseFloat(amountReceived.value.replace(/\s/g, '')) || 0;
            return numeric.toLocaleString('fr-FR');
        });

        const saveOrder = async function () {
            try {
                if (amountReceived.value < orderToCreate.total) {
                    toast.warning('Le montant reçu est insuffisant.')
                    return;
                }
                orderStore.loading = true;
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
                    title: "order-" + Date.now(),
                    field_client: orderToCreate.clientId,
                    clientName: orderToCreate.clientName,
                    field_articles: allArticles,
                    field_total_vente: orderToCreate.total,
                    field_date: formatDateUS(),
                    status: 1,
                    field_status: "payed"
                };
                await orderStore.createOrder(data);
                if (orderStore.error) {
                    toast.error("Une erreur est survenue lors de l'ajout du commande.")
                    return
                }
                articleStore.clearCart(true);
                emit('close-payment-modal');
                orderStore.loading = false;
                toast.success('Commande ajouté avec succès !')
            } catch (err) {
                console.error("Erreur dans saveOrder :", err);
                toast.error("Une erreur inattendue est survenue.");
            } finally {
                orderStore.loading = false;
            }
        }

        // === Gestion du clavier ===
        const handleKeydown = (e) => {
            if (/^[0-9.]$/.test(e.key)) {
                // pavé numérique ou chiffres classiques
                amountReceived.value += e.key;
            } else if (e.key === 'Backspace' || e.key === 'Delete') {
                // suppression
                amountReceived.value = amountReceived.value.slice(0, -1);
            } else if (e.key === 'Enter') {
                // confirmer
                saveOrder();
            } else if (e.key === 'Escape') {
                // annuler / fermer
                emit('close-payment-modal');
            }
        };

        const formatDateUS = () => {
            const now = new Date();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const year = now.getFullYear();
            return `${year}-${month}-${day}`;
        };

        onMounted(() => {
            window.addEventListener('keydown', handleKeydown);
        });

        onBeforeUnmount(() => {
            window.removeEventListener('keydown', handleKeydown);
        });

        return {
            saveOrder,
            articleStore,
            orderToCreate,
            amountReceived,
            changeDue,
            handleNumpadClick,
            formattedAmountReceived
        }
    }

}
</script>

<style></style>