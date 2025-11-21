<template>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 order-card">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
            <div class="flex items-center space-x-3 mb-2 md:mb-0">

                <div v-if="statusMap[order.field_status]"
                    :class="`w-10 h-10 rounded-full flex items-center justify-center ${statusMap[order.field_status].bg}`">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <i :class="statusMap[order.field_status].icon"></i>
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Commande #{{ order.title }}</h3>
                    <p class="text-sm text-gray-500">{{ formatDate(order.field_date, order.created) }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span v-if="statusLabel[order.field_status]"
                    :class="`px-3 py-1 rounded-full text-xs font-medium ${statusStyle[order.field_status]}`">
                    {{ statusLabel[order.field_status] }}
                </span>
                <span
                    :class="[order.field_status == 'cancel' ? 'text-lg font-bold text-gray-400 line-through' : 'text-lg font-bold text-primary']">{{
                        Number(order.field_total_vente ||
                            0).toLocaleString('fr-MG', { style: 'currency', currency: 'MGA' }) }}</span>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Informations client</h4>
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium uppercase">
                        {{ order?.field_client?.title?.slice(0, 2) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 capitalize">{{ order.field_client.title }}</p>
                        <p class="text-sm text-gray-500">{{ order.field_client.field_phone }}</p>
                        <div class="flex items-center mt-1">
                            <span class="sm:hidden text-xs text-gray-500 me-2">{{ order.field_articles.length }} {{
                                order.field_articles.length > 1 ? 'produits' : 'produit'}}</span>
                            <div class="flex items-center mt-1" v-if="order.field_client.field_assurance == 1">
                                <div class="w-2 h-2 bg-secondary rounded-full mr-1"></div>
                                <span class="text-xs text-secondary font-medium">Assurance</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden sm:block">
                <h4 class="font-medium text-gray-900 mb-2">Produits commandés</h4>
                <div class="space-y-1 text-sm text-gray-600">
                    <div v-for="article in order.field_articles.slice(0, 3)" :key="article.nid"
                        class="flex justify-between">
                        <p class="flex">
                            <span class="block truncate overflow-hidden whitespace-nowrap max-w-[300px]">
                                {{ article.field_article.title }}
                            </span>
                            <span class="ms-3"> × {{ article.field_quantite }}</span>
                        </p>
                        <span>
                            {{ (article.field_prix_unitaire * article.field_quantite).toLocaleString('fr-MG', {
                                style: 'currency',
                                currency: 'MGA'
                            }) }}
                        </span>
                    </div>

                    <div v-if="order.field_articles.length > 3" class="text-xs text-gray-500">
                        + {{ order.field_articles.length - 3 }} autre {{ order.field_articles.length - 3 > 1 ? 's' : ''
                        }} article {{ order.field_articles.length - 3 > 1 ? 's' : '' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <button @click="showEditStatusModal"
                class="px-3 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 !rounded-button text-sm font-medium whitespace-nowrap change-status-btn"
                data-order-id="CMD-2024-001">
                <div class="w-4 h-4 flex items-center justify-center inline-block mr-1">
                    <i class="ri-refresh-line"></i>
                </div>
                Changer statut
            </button>
            <button @click="downloadPdf(order)"
                class="px-3 py-2 bg-gray-50 text-gray-600 hover:bg-gray-100 !rounded-button text-sm font-medium whitespace-nowrap print-invoice-btn"
                data-order-id="CMD-2024-001">
                <div class="w-4 h-4 flex items-center justify-center inline-block mr-1">
                    <i class="ri-printer-line"></i>
                </div>
                Imprimer facture
            </button>
            <button @click="showDetailsModal"
                class="px-3 py-2 bg-gray-50 text-gray-600 hover:bg-gray-100 !rounded-button text-sm font-medium whitespace-nowrap view-details-btn"
                data-order-id="CMD-2024-001">
                <div class="w-4 h-4 flex items-center justify-center inline-block mr-1">
                    <i class="ri-eye-line"></i>
                </div>
                Voir détails
            </button>
        </div>
    </div>
</template>

<script>
import { formatDate } from '../../utils/formateDate';
import { usePDF } from "vue3-pdfmake";
import { generateInvoicePdf } from '../../utils/invoicePdf.js';

export default {
    name: 'OrderCard',
    props: {
        order: {
            type: Object,
            required: true
        }
    },
    emits: ['show-edit-status-modal', 'show-details-modal'],
    setup(props, { emit }) {
        const showEditStatusModal = () => {
            emit('show-edit-status-modal', props.order);
        }

        const showDetailsModal = () => {
            emit('show-details-modal', props.order);
        }

        const statusMap = {
            unpayed: {
                bg: 'bg-yellow-100 text-yellow-600',
                text: 'Non payé',
                icon: 'ri-time-line'
            },
            cancel: {
                bg: 'bg-red-100 text-red-600',
                text: 'Annulée',
                icon: 'ri-close-line'
            },
            payed: {
                bg: 'bg-green-100 text-green-600',
                text: 'Payé',
                icon: 'ri-check-line'
            }
        };

        const statusStyle = {
            payed: "bg-green-100 text-green-800",
            unpayed: "bg-yellow-100 text-yellow-800",
            cancel: "bg-red-100 text-red-800"
        };

        const statusLabel = {
            payed: "Payé",
            unpayed: "Non payé",
            cancel: "Annulée"
        };

        const pdfMake = usePDF();

        const downloadPdf = (order) => {
            generateInvoicePdf(order, statusMap, pdfMake);
        };


        return {
            showEditStatusModal,
            showDetailsModal,
            statusMap,
            statusStyle,
            statusLabel,
            formatDate,
            downloadPdf
        }
    }
}
</script>

<style></style>