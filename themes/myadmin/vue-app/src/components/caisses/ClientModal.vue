<template>
    <!-- Customer Selection Modal -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Sélectionner un client</h3>
                        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="ri-close-line text-xl"></i>
                            </div>
                        </button>
                    </div>
                    <div class="mb-4">
                        <div class="">
                            <div>
                                <div class="relative mb-3">
                                    <div
                                        class="w-4 h-4 flex items-center justify-center absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <i class="ri-search-line text-sm"></i>
                                    </div>
                                    <input type="text" v-model="clientNameSearch" @keyup.enter="onSearch"
                                        placeholder="Rechercher un client..."
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 !rounded-button focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                                </div>
                                <div class="max-h-48 overflow-y-auto border border-gray-300 !rounded-button">
                                    <div id="customer-list" class="divide-y divide-gray-100">
                                        <div v-for="(client, index) in store.clients.rows" :key="index" :class="[
                                            'flex items-center space-x-3 px-3 py-2 hover:bg-gray-50 cursor-pointer customer-item border-t-0',
                                            selectedIndex === index ? 'bg-blue-50 border-primary border-l-4' : ''
                                        ]" @click="selectClient(client.nid, index)">
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
                                </div>
                            </div>
                            <button @click="$emit('open-add-customer-modal')"
                                class="w-full px-3 py-2 mt-3 border-2 border-dashed border-gray-300 hover:border-primary text-gray-600 hover:text-primary !rounded-button font-medium text-sm whitespace-nowrap flex items-center justify-center space-x-2">
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
                        <button @click="confirmSelectedClient"
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
import { onMounted, ref } from 'vue';
import { useClientStore } from '../../stores/index.js';
import { toast } from 'vue-sonner';


export default {
    name: "CustomerModal",
    setup(_, { emit }) {
        const store = useClientStore();
        const selectedClientNid = ref(null);
        const selectedIndex = ref(null);
        const clientNameSearch = ref(null);
        // Paramètres dynamiques de la requête
        const queryOptions = ref({
            fields: [
                'nid',
                'title',
                'field_phone',
                'field_assurance',
                'field_adresse',
                'field_age'
            ],
            sort: { val: 'nid', op: 'desc' },
            filters: {},
            pager: 0,
            offset: 20
        })

        const fetchClients = async () => {
            await store.fetchClients(queryOptions.value);
        }

        const confirmSelectedClient = async () => {
            await store.fetchClient(selectedClientNid.value);

            if (store.error) {
                toast.error("Une erreur est survenue lors de la sélection du client.")
                return
            }

            toast.success('Client sélectionné avec succès !')
            emit('close')
        }

        const onSearch = async () => {
            updateFilter('title', clientNameSearch.value, 'CONTAINS')
            fetchClients()
        }

        const updateFilter = (key, value, op = '=') => {
            if (!value) delete queryOptions.value.filters[key]
            else queryOptions.value.filters[key] = { val: value, op }
        }

        const selectClient = (client, index) => {
            selectedIndex.value = index
            selectedClientNid.value = client
        }

        onMounted(() => fetchClients());

        return {
            store,
            queryOptions,
            confirmSelectedClient,
            selectedClientNid,
            selectedIndex,
            clientNameSearch,
            onSearch,
            selectClient
        }
    }

}
</script>

<style>
.border-primary {
    border-color: rgb(59, 130, 246, 1) !important;
}

.border-t-0 {
    border-top-width: 0px !important;
}
</style>