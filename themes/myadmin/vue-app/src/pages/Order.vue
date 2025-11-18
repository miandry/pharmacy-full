<template>
    <div>
        <page-loader v-if="orderStore.loading" />
        <div class="p-4 md:p-6 min-h-[calc(100vh-80px)]">
            <div class="max-w-7xl mx-auto">
                <order-filter @onSearch="onSearch" @onFilter="onFilter"/>
                <div class="grid gap-4">
                    <order-card v-for="order in orderStore.orders.rows" :key="order.nid" :order="order"
                        class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 order-card"
                        @showEditStatusModal="showEditStatusModal" @showDetailsModal="showDetailsModal" />
                </div>
            </div>
        </div>
        <!-- edit status -->
        <edit-status-modal @showEditStatusModal="showEditStatusModal" @closeEditStatusModal="closeEditStatusModal"
            :class="[showEditStatusModalIsVisible ? '' : 'hidden', 'fixed inset-0 bg-black bg-opacity-50 z-50']" />
            <!-- details -->
        <show-order-modal @showDetailsModal="showDetailsModal" @closeDetailsModal="closeDetailsModal"
            @showEditStatusModal="showEditStatusModal" :orderToShow="orderStore.order"
            :class="['fixed inset-0 bg-black bg-opacity-50 z-50']" v-if="showDetailsModalIsVisible" />
    </div>
</template>

<script>
import { onMounted, ref } from 'vue';
import EditStatusModal from '../components/orders/EditStatusModal.vue';
import OrderCard from '../components/orders/OrderCard.vue';
import OrderFilter from '../components/orders/OrderFilter.vue';
import ShowOrderModal from '../components/orders/ShowOrderModal.vue';
import { useOrderStore } from '../stores/index.js';
import PageLoader from '../components/PageLoader.vue';

export default {
    name: "Order",
    components: {
        OrderFilter,
        OrderCard,
        EditStatusModal,
        ShowOrderModal,
        PageLoader
    },
    setup() {
        const orderStore = useOrderStore();
        const orderId = ref(null);
        const queryOptions = ref({
            fields: [
                'nid',
                'title',
                'field_articles',
                'field_client',
                'field_date',
                'field_status',
                'field_total_vente',
                'created'
            ],
            sort: { val: 'nid', op: 'desc' },
            filters: {},
            pager: 0,
            offset: 10
        })
        const queryOptionsForDetails = {
            fields: [
                'nid',
                'title',
                'field_articles',
                'field_client',
                'field_date',
                'field_status',
                'field_total_vente',
                'created'
            ]
        }

        const fetchOrders = async () => {
            await orderStore.fetchOrders(queryOptions.value);
        }

        const fetchOrder = async () => {
            await orderStore.fetchOrder(orderId.value, queryOptionsForDetails);
        }

        // Recherche
        const onSearch = (value) => {
            queryOptions.value.pager = 0
            updateFilter('title', value, 'CONTAINS')
            fetchOrders()
        }

        const onFilter = (value) => {
            queryOptions.value.pager = 0
            if (value == 'all') {
                value = null;
            }
            updateFilter('field_status', value, '=')
            fetchOrders()
        }

        const updateFilter = (key, value, op = '=') => {
            if (!value) delete queryOptions.value.filters[key]
            else queryOptions.value.filters[key] = { val: value, op }
        }

        onMounted(() => {
            fetchOrders();
        })


        const showEditStatusModalIsVisible = ref(false);
        const showDetailsModalIsVisible = ref(false);
        // Edit status Modal handler
        const showEditStatusModal = () => {
            showEditStatusModalIsVisible.value = true;
        }
        const closeEditStatusModal = () => {
            showEditStatusModalIsVisible.value = false;
        }
        // Details modal Handler
        const showDetailsModal = async (order) => {
            orderId.value = order.nid;
            await fetchOrder();
            showDetailsModalIsVisible.value = true;
        }
        const closeDetailsModal = () => {
            showDetailsModalIsVisible.value = false;
        }

        return {
            showEditStatusModalIsVisible,
            showEditStatusModal,
            closeEditStatusModal,
            showDetailsModalIsVisible,
            showDetailsModal,
            closeDetailsModal,
            // order
            fetchOrders,
            fetchOrder,
            orderStore,
            onSearch,
            onFilter
        }
    }
}
</script>

<style></style>