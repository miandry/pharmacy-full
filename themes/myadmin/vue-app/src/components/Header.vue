
<template>
    <header class="bg-white border-b border-gray-200 px-4 md:px-6 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 md:space-x-8">
          <h1 class="text-xl md:text-2xl font-['Pacifico'] text-primary">
            Clinic Vonjy Aina
          </h1>
          <nav class="hidden lg:flex space-x-1">
            <router-link
            v-for="a in menu"
            :key="a.id"
            :to="a.url"
            class="px-4 xl:px-6 py-3 !rounded-button whitespace-nowrap font-medium text-sm xl:text-base"
            :class="[
              $route.path === a.url
                ? 'bg-primary text-white'
                : 'text-gray-600 hover:text-primary hover:bg-gray-50'
            ]"
          >
            {{ a.name }}
          </router-link>
          </nav>
          <button @click="handleClick"
            class="lg:hidden p-2 text-gray-600 hover:text-primary"
            id="mobile-menu-toggle" 
          >
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ri-menu-line text-xl"></i>
            </div>
          </button>
        </div>
        <div class="hidden md:flex items-center space-x-2 md:space-x-4">
          <div
            class="flex items-center space-x-2 text-xs md:text-sm text-gray-600"
          >
            <div class="w-2 h-2 bg-secondary rounded-full"></div>
            <span class="hidden sm:inline">En ligne</span>
          </div>
          <div class="text-xs md:text-sm text-gray-600">Magasin #001</div>
        </div>
      </div>
    </header>
        <!-- Mobile Menu -->
    <div
      v-show="isOpen"
      id="mobile-menu"
      class="lg:hidden bg-white border-b border-gray-200 px-4 py-2"
    >
      <nav  class="flex flex-wrap gap-2">
        <router-link
            v-for="a in menu"
            :key="a.id"
            :to="a.url"
            class="px-4 xl:px-6 py-3 !rounded-button whitespace-nowrap font-medium text-sm xl:text-base"
            :class="[
              $route.path === a.url
                ? 'bg-primary text-white'
                : 'text-gray-600 hover:text-primary hover:bg-gray-50'
            ]"
          >
           
            {{ a.name }}

          </router-link>
      </nav>
      <div
        class="flex items-center justify-center space-x-4 mt-3 pt-3 border-t border-gray-100 md:hidden"
      >
        <div class="flex items-center space-x-2 text-xs text-gray-600">
          <div class="w-2 h-2 bg-secondary rounded-full"></div>
          <span>En ligne</span>
        </div>
        <div class="text-xs text-gray-600">Magasin #001</div>
      </div>
    </div>
        <!-- Navbar -->
</template>
  <script>
import { ref, onMounted } from 'vue';

export default {
  name: 'Header',
  computed: {
    transId() {
      // Access the route parameter (e.g., id)
      return this.$route.params.id;
    }
  },
  data() {
    return {
      isOpen:false
    };
  },
  setup() {
    const menu = ref([]);         // Holds the fetched data
    const activeId = ref(null);
    const setActive = (id) => {
      activeId.value = id;
    };

    // Fetch data when the component is mounted
    onMounted(() => {
      //  menu.value = (window.drupalSettings?.menu || []);
      // Si pas de Drupal, tu peux tester avec un mock JSON
     // if (menu.value.length === 0) {
        menu.value = [
        { id: 1, name: "Caisse", url: "/caisse" },
        { id: 2, name: "Clients", url: "/clients" },
        { id: 3, name: "Factures", url: "/factures" },
        { id: 5, name: "Paramètres", url: "/parametres" }
        ];
     // }
       console.log("Menu chargé:", menu.value);
     if (menu.value.length > 0) {
        activeId.value = menu.value[0].id;
      }

    });

    return {
      menu,
      activeId,
      setActive
    };
  },
  methods: {
   
        handleClick() {
             return this.isOpen = !this.isOpen ;
        }
  }
};
</script>   