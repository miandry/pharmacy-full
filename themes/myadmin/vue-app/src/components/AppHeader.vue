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
                : 'text-gray-600 hover:text-primary hover:bg-gray-50',
            ]"
          >
            {{ a.name }}
          </router-link>
        </nav>
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
  <div class="app-header">
    <!-- Logo and Brand -->
    <div class="brand">
      <h1 class="text-xl md:text-2xl font-['Pacifico'] text-primary">
        Clinic Vonjy Aina
      </h1>
      <span class="store-name"> My Store </span>
    </div>

    <!-- Menu Toggle Button -->
    <button
      class="menu-toggle"
      @click="emitToggleMenu"
      aria-label="Toggle navigation menu"
    >
      <i class="fas fa-bars"></i>
    </button>
  </div>
</template>

<script>
export default {
  name: "AppHeader",

  // Define events this component emits
  emits: ["toggle-menu"],

  methods: {
    // Emit event to parent to toggle menu
    emitToggleMenu() {
      this.$emit("toggle-menu");
    },
  },
};
</script>

<style scoped>
.app-header {
  background: white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.brand {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.logo {
  height: 2rem;
  width: auto;
}

.store-name {
  font-weight: 600;
  color: #1f2937; /* gray-800 */
}

.menu-toggle {
  padding: 0.5rem;
  border-radius: 0.375rem;
  color: #4b5563; /* gray-600 */
  transition: background-color 0.2s;
}

.menu-toggle:hover {
  background-color: #f9fafb; /* gray-50 */
}

.menu-toggle i {
  font-size: 1.25rem;
}

/* Hide header on large screens (menu is always visible in sidebar) */
@media (min-width: 1024px) {
  .app-header {
    display: none;
  }
}
@media (max-width: 1024px) {
  header {
    display: none;
  }
}
</style>
