<template>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Gestion des commandes</h2>

        <!-- SEARCH -->
        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <div class="flex-1">
                <div class="relative">
                    <div
                        class="w-5 h-5 flex items-center justify-center absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="ri-search-line text-sm"></i>
                    </div>

                    <input type="text" v-model="searchKeyword" @keyup.enter="searchByKeyword"
                        placeholder="Rechercher une commande."
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 !rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
            </div>

            <button
                class="px-4 py-3 bg-primary text-white !rounded-button font-medium whitespace-nowrap flex items-center space-x-2 text-sm">
                <i class="ri-add-line text-lg"></i>
                <span>Nouvelle commande</span>
            </button>
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

export default {
    name: 'OrderFilter',
    emits: ['on-search', 'on-filter'],

    setup(_, { emit }) {

        const searchKeyword = ref('');
        const status = ref('all');

        const statusOptions = [
            { value: 'all', label: 'Toutes' },
            { value: 'payed', label: 'Payé' },
            { value: 'unpayed', label: 'Non payé' },
            { value: 'cancel', label: 'Annulée' }
        ];

        const searchByKeyword = () => {
            emit('on-search', searchKeyword.value);
        };

        const changeStatus = (value) => {
            status.value = value;
            emit('on-filter', value);
        };

        return {
            searchKeyword,
            searchByKeyword,
            status,
            statusOptions,
            changeStatus
        };
    }
};
</script>