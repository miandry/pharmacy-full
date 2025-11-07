<template>
    <div id="add-customer-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Ajouter un nouveau client</h3>
                        <button @click="$emit('close-add-customer-modal')" class="text-gray-400 hover:text-gray-600">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="ri-close-line text-xl"></i>
                            </div>
                        </button>
                    </div>

                    <form @submit.prevent="submitClientForm" id="add-customer-form" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                            <input type="text" v-model="form.title" required
                                class="w-full px-3 py-2 border border-gray-300 !rounded-button focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                                placeholder="Ex: Rakoto Andry">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="tel" v-model="form.field_phone" required
                                class="w-full px-3 py-2 border border-gray-300 !rounded-button focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                                placeholder="Ex: +261 34 12 345 67">
                        </div>

                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-700">
                                <input type="checkbox" :checked="form.field_assurance === 1"
                                    @change="form.field_assurance = $event.target.checked ? 1 : 0"
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                Ce client a une assurance</label>
                        </div>

                        <div class="flex space-x-3 mt-6">
                            <button type="button" @click="$emit('close-add-customer-modal')"
                                class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 !rounded-button font-medium whitespace-nowrap">
                                Annuler
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-secondary text-white hover:bg-green-600 !rounded-button font-medium whitespace-nowrap">
                                Enregistrer
                            </button>
                        </div>
                    </form>

                    <p v-if="store.loading" class="mt-2 text-sm text-gray-500">Enregistrement en cours...</p>
                    <p v-if="store.error" class="mt-2 text-sm text-red-500">Erreur : {{ store.error.message }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive } from "vue";
import { useClientStore } from "../../stores/index.js";
import { toast } from "vue-sonner";

export default {
    setup(props, { emit }) {
        const store = useClientStore();

        const form = reactive({
            entity_type: "node",
            bundle: "client",
            title: "",
            field_phone: "",
            field_assurance: 0,
            status: 1,
        });

        const submitClientForm = async () => {
            store.loading = true;
            await store.createClient(form);

            if (store.error) {
                toast.error("Une erreur est survenue lors de l'ajout client.")
                return
            }

            // reset form
            form.title = "";
            form.field_phone = "";
            // fermer modal si c'est ok
            emit('close-add-customer-modal');
            emit('close-client-modal');
            toast.success('Client sélectionné avec succès !')
            store.loading = false;
        };

        return { form, submitClientForm, store };
    },
};
</script>

<style></style>