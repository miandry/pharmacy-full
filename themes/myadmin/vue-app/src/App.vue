<template>
   <!-- <Loader /> -->
    <!-- Header Component -->
    <AppHeader @toggle-menu="toggleMobileMenu" />
    
    <!-- Main Content Area -->
    <main class="main-content">
      <router-view />
    </main>

    <!-- Mobile Menu Component -->
    <MobileMenu 
      :is-open="isMobileMenuOpen" 
      @close="closeMobileMenu" 
    />
</template>

<script>
import AppHeader from './components/AppHeader.vue'
import MobileMenu from './components/MobileMenu.vue'
import Loader from './components/Loader.vue'

export default {
  name: 'App',
  components: {
    AppHeader,
    MobileMenu,
    Loader
  },
  
  data() {
    return {
      isMobileMenuOpen: false
    }
  },
  
  methods: {
    // Toggle mobile menu open/close
    toggleMobileMenu() {
      this.isMobileMenuOpen = !this.isMobileMenuOpen
      this.updateBodyScroll()
    },
    
    // Close mobile menu
    closeMobileMenu() {
      this.isMobileMenuOpen = false
      this.updateBodyScroll()
    },
    
    // Prevent body scrolling when menu is open
    updateBodyScroll() {
      if (this.isMobileMenuOpen) {
        document.body.style.overflow = 'hidden'
      } else {
        document.body.style.overflow = 'auto'
      }
    }
  },
  
  // Close menu when route changes
  watch: {
    $route() {
      this.closeMobileMenu()
    }
  }
}
</script>

<style scoped>
.main-content {
  transition: all 0.3s ease;
}
</style>