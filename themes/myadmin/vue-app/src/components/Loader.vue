
<template>
    <div
       v-if="showLoader"
      id="page-loader"
      class="fixed inset-0 bg-white z-50 flex items-center justify-center"
    >
      <div class="text-center">
        <div
          class="w-12 h-12 border-4 border-gray-200 border-t-primary rounded-full animate-spin mx-auto mb-4"
        ></div>
        <h1 class="text-xl font-['Pacifico'] text-primary mb-2">
          Clinic Vonjy Aina
        </h1>
        <p class="text-sm text-gray-600">
          Chargement de la page ...
        </p>
      </div>
    </div>
</template>
  <script>
import { ref, onMounted } from 'vue';

export default {
  name: 'Loader',
  setup() {
    const showLoader = ref(true)

    // Fetch data when the component is mounted
    onMounted(() => {
      //  menu.value = (window.drupalSettings?.menu || []);
      // Si pas de Drupal, tu peux tester avec un mock JSON
     // if (menu.value.length === 0) {
       setTimeout(() => {
            showLoader.value = false
        }, 1200) // même délai que ton script original

            });

    return {
      showLoader
    };
  }
};
</script>   
<style>
/* Transition du loader */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease-out;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>