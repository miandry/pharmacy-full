<template>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Gestion des commandes</h2>

        <!-- SEARCH -->
        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <div class="w-full md:w-1/2 relative">
                <div class="relative">
                    <div
                        class="w-5 h-5 flex items-center justify-center absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="ri-search-line text-sm"></i>
                    </div>

                    <input type="text" v-model="searchKeywordClient" @keyup="searchByKeyword" @focus="showList = true"
                        @blur="handleBlur" placeholder="Rechercher une commande."
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div v-if="showList" @mousedown.prevent
                    class="max-h-48 overflow-y-auto border border-gray-300 !rounded-button bg-white absolute right-0 left-0">
                    <div v-if="clientStore.clients.rows.length" class="divide-y divide-gray-100">
                        <div v-for="(client, index) in clientStore.clients.rows" :key="index" :class="[
                            'flex items-center space-x-3 px-3 py-2 hover:bg-gray-50 cursor-pointer customer-item border-t-0',
                            selectedIndex === index ? 'bg-blue-50 border-primary border-l-4' : ''
                        ]" @click="selectClient(client.nid, client.title)">
                            <div
                                class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium uppercase ">
                                {{ client.title.slice(0, 2) }}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ client.title }}</p>
                                <p class="text-xs text-gray-500">{{ client.field_phone }}</p>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <h3 class="text-center text-gray-400 py-2">Aucun client trouvé avec ce mot-clé</h3>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/2">
                <div class="relative">
                    <input type="date" placeholder="Rechercher avec une date"
                        @change="filterByDate" v-model="dateValue"
                        class="w-full pl-4 pr-4 py-3 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
            </div>

        </div>

        <!-- FILTER BUTTONS -->
        <div class="flex space-x-2 overflow-x-auto mb-6">
            <button v-for="btn in statusOptions" :key="btn.value" @click="changeStatus(btn.value)" :class="[
                'px-4 py-2 text-sm font-medium whitespace-nowrap !rounded-button filter-btn',
                status === btn.value
                    ? 'bg-primary text-white'
                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]">
                {{ btn.label }}
            </button>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import { useClientStore } from '../../stores';

export default {
    name: 'OrderFilter',
    emits: ['on-search', 'on-filter', 'on-date-filter'],

    setup(_, { emit }) {

        const status = ref('all');
        const clientStore = useClientStore();
        const clientId = ref('');
        const searchKeywordClient = ref('');
        const dateValue = ref('');
        const showList = ref(false);
        
        const queryOptions = ref({
            fields: [
                'nid',
                'title',
                'field_phone'
            ],
            sort: { val: 'nid', op: 'desc' },
            filters: {},
            pager: 0,
            offset: 20
        })

        const statusOptions = [
            { value: 'all', label: 'Toutes' },
            { value: 'payed', label: 'Payé' },
            { value: 'unpayed', label: 'Non payé' },
            { value: 'cancel', label: 'Annulée' }
        ];

        const fetchClients = async () => {
            await clientStore.fetchClients(queryOptions.value);
        }

        const searchByKeyword = async () => {
            updateFilter('title', searchKeywordClient.value, 'CONTAINS')
            await fetchClients();
            showList.value = true;
        };

        const selectClient = async (nid, name) => {
            searchKeywordClient.value = name;
            clientId.value = nid;
            showList.value = false;
            emit('on-search', clientId.value);
        }

        const updateFilter = (key, value, op = '=') => {
            if (!value) delete queryOptions.value.filters[key]
            else queryOptions.value.filters[key] = { val: value, op }
        }

        const filterByDate = () => {
            emit('on-date-filter', dateValue.value);
        }

        const changeStatus = (value) => {
            status.value = value;
            emit('on-filter', value);
        };

        const handleBlur = async () => {
            // délai court pour laisser le clic se déclencher avant de cacher
            if (searchKeywordClient.value === '') {
                emit('on-search', '');
            }
            setTimeout(() => showList.value = false, 100);
        };

        return {
            searchKeywordClient,
            searchByKeyword,
            status,
            statusOptions,
            changeStatus,
            clientStore,
            handleBlur,
            showList,
            selectClient,
            filterByDate,
            dateValue
        };
    }
};
</script>