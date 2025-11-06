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
                            <div v-if="store.clients.rows.length">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                                <select id="customer-select" v-model="selectedClientNid"
                                    class="w-full px-3 py-2 border border-gray-300 !rounded-button focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                                    <option v-for="client in store.clients.rows" :key="client.nid" :value="client.nid">
                                        {{ client.title }} - {{ client.field_phone }}
                                    </option>
                                </select>
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

export default {
    name: "CustomerModal",
    setup(_, { emit }) {
        const store = useClientStore();
        const selectedClientNid = ref(null);
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
            // Sélectionner le premier client par défaut si la liste n'est pas vide
            if (store.clients.rows.length > 0) {
                selectedClientNid.value = store.clients.rows[0].nid;
            }
        }

        const confirmSelectedClient = async () => {
            await store.fetchClient(selectedClientNid.value);
            emit('close');
        }

        onMounted(() => fetchClients());

        return {
            store,
            queryOptions,
            confirmSelectedClient,
            selectedClientNid
        }
    }

}
</script>

<style></style>