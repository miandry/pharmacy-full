<template>
    <div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 pb-0">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900" id="modal-order-title">Détails de la commande
                            #{{ orderToShow.title }}</h3>
                        <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="ri-close-line text-xl"></i>
                            </div>
                        </button>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Informations client</h4>
                            <div class="space-y-2">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium uppercase">
                                        {{ orderToShow.field_client.title.slice(0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ orderToShow.field_client.title }}</p>
                                        <p class="text-sm text-gray-500" id="modal-customer-phone">{{
                                            orderToShow.field_client.field_phone }}</p>
                                    </div>
                                </div>
                                <div v-if="orderToShow.field_client.field_assurance == 1">
                                    <div class="flex items-center mt-2">
                                        <div class="w-2 h-2 bg-secondary rounded-full mr-2"></div>
                                        <span class="text-sm text-secondary font-medium">Client avec assurance</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Informations commande</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Numéro :</span>
                                    <span class="font-medium" id="modal-order-number">#{{ orderToShow.title }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Date :</span>
                                    <span class="font-medium" id="modal-order-date">{{
                                        formatDate(orderToShow.field_date, orderToShow.created) }}</span>
                                </div>
                                <div class="flex justify-between" v-if="statusMap[orderToShow.field_status]">
                                    <span class="text-gray-600">Statut :</span>
                                    <span
                                        :class="`px-2 py-1 rounded-full text-xs font-medium ${statusMap[orderToShow.field_status].bg}`"
                                        v-text="`${statusMap[orderToShow.field_status].text}`">

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Produits commandés</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="space-y-3" id="modal-products-list">
                                <div v-for="article in orderToShow.field_articles" :key="article.nid"
                                    class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ article.field_article.title }}</p>
                                        <p class="text-sm text-gray-500">Prix unitaire : {{
                                            Number(article.field_prix_unitaire).toLocaleString('fr-MG', {
                                                style: 'currency',
                                                currency: 'MGA'
                                            }) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium">Qté : {{ article.field_quantite }}</p>
                                        <p class="text-sm text-primary font-semibold"> {{ (article.field_prix_unitaire *
                                            article.field_quantite).toLocaleString('fr-MG', {
                                                style: 'currency',
                                                currency: 'MGA'
                                            }) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Sous-total :</span>
                                        <span class="font-medium">{{ Number(orderToShow.field_total_vente ||
                                            0).toLocaleString('fr-MG', { style: 'currency', currency: 'MGA' }) }}</span>
                                    </div>
                                    <div
                                        class="flex justify-between text-lg font-semibold pt-2 border-t border-gray-200">
                                        <span>Total :</span>
                                        <span class="text-primary">{{ Number(orderToShow.field_total_vente ||
                                            0).toLocaleString('fr-MG', { style: 'currency', currency: 'MGA' }) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 hidden">
                        <h4 class="font-semibold text-gray-900 mb-3">Historique des statuts</h4>
                        <div class="space-y-3" id="modal-status-history">
                            <div class="flex items-center space-x-3 pb-3 border-b border-gray-100 last:border-b-0">
                                <div
                                    class="w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center">
                                    <div class="w-4 h-4 flex items-center justify-center">
                                        <i class="ri-time-line text-xs"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">Commande créée</p>
                                    <p class="text-sm text-gray-500">17 novembre 2024 à 14:30</p>
                                    <p class="text-xs text-gray-400">Statut : En attente</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="flex flex-wrap gap-3 px-6 pb-6">
                    <button
                        class="px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 !rounded-button font-medium whitespace-nowrap"
                        @click="showStatusModal">
                        <div class="w-4 h-4 flex items-center justify-center inline-block mr-1">
                            <i class="ri-refresh-line"></i>
                        </div>
                        Changer statut
                    </button>
                    <button
                        class="px-4 py-2 bg-gray-50 text-gray-600 hover:bg-gray-100 !rounded-button font-medium whitespace-nowrap"
                        @click="downloadPdf">
                        <div class="w-4 h-4 flex items-center justify-center inline-block mr-1">
                            <i class="ri-printer-line"></i>
                        </div>
                        Imprimer facture
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, ref } from 'vue';
import { formatDate } from '../../utils/formateDate';

export default {
    name: "ShowOrderModal",
    props: {
        orderToShow: {
            type: Object,
            required: true,
        }
    },
    emit: ['close-details-modal', 'show-edit-status-modal'],
    setup(props, { emit }) {
        const closeModal = () => {
            emit('close-details-modal');
        }

        const showStatusModal = () => {
            closeModal();
            emit('show-edit-status-modal', props.orderToShow);
        }

        const statusMap = {
            unpayed: {
                bg: 'bg-yellow-100 text-yellow-600',
                icon: 'ri-time-line',
                text: 'Non payé',
            },
            cancel: {
                bg: 'bg-red-100 text-red-600',
                icon: 'ri-close-line',
                text: 'Annulée',
            },
            payed: {
                bg: 'bg-green-100 text-green-600',
                icon: 'ri-check-double-line',
                text: 'Payé'

            }
        };

        return {
            closeModal,
            showStatusModal,
            formatDate,
            statusMap,
        }
    }
}
</script>

<style></style>