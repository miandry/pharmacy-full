<template>
  <div class="mobile-menu-wrapper">
    <!-- Backdrop Overlay -->
    <div 
      class="menu-backdrop" 
      :class="{ 'active': isOpen }"
      @click="closeMenu"
    />

    <!-- Mobile Menu Panel -->
    <div 
      class="mobile-menu-panel"
      :class="{ 'open': isOpen }"
    >
      <!-- Menu Header -->
      <div class="menu-header">
        <div class="brand">
          <h1 class="text-xl md:text-2xl font-['Pacifico'] text-primary">
            Clinic Vonjy Aina
          </h1>
        </div>
        
        <button 
          class="close-button"
          @click="closeMenu"
          aria-label="Close menu"
        >
          <i class="fas fa-times"></i>
        </button>
      </div>

      <!-- Navigation Menu -->
      <nav class="menu-navigation">
        <router-link
          v-for="menuItem in menuItems"
          :key="menuItem.id"
          :to="menuItem.path"
          class="menu-item"
          :class="getMenuItemClass(menuItem.path)"
          @click="closeMenu"
        >
          <i :class="menuItem.icon" class="menu-icon" />
          <span class="menu-label">
            {{ menuItem.name }}
          </span>
        </router-link>
      </nav>

      <!-- Status Footer -->
      <div class="menu-footer">
        <div class="status-indicator">
          <div class="online-dot" />
          <span class="status-text">
            En ligne
          </span>
        </div>
        
        <div class="store-info">
          Magasin #001
        </div>
      </div>
    </div>
  </div>
</template>

<script>
      console.log(mydata);
export default {
  name: 'MobileMenu',
  
  // Props from parent component
  props: {
    isOpen: {
      type: Boolean,
      required: true
    }
  },
  
  // Events emitted to parent
  emits: ['close'],
  
  data() {
    return {
      // Menu configuration - easily customizable
      menuItems: mydata.menu
    }
  },
  
  methods: {
    // Close menu and notify parent
    closeMenu() {
      this.$emit('close')
    },
    
    // Determine CSS classes for menu item based on current route
    getMenuItemClass(itemPath) {
      const isActive = this.$route.path === itemPath
      
      return {
        'menu-item-active': isActive,
        'menu-item-inactive': !isActive
      }
    },
    
    // Handle Escape key press to close menu
    handleEscapeKey(event) {
      if (event.key === 'Escape' && this.isOpen) {
        this.closeMenu()
      }
    }
  },
  
  // Add/remove event listeners when menu opens/closes
  watch: {
    isOpen(newValue) {
      if (newValue) {
        document.addEventListener('keydown', this.handleEscapeKey)
      } else {
        document.removeEventListener('keydown', this.handleEscapeKey)
      }
    }
  },
  
  // Cleanup event listener when component is destroyed
  beforeUnmount() {
    document.removeEventListener('keydown', this.handleEscapeKey)
  }
}
</script>

<style scoped>
.mobile-menu-wrapper {
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 50;
}

/* Backdrop Overlay */
.menu-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
}

.menu-backdrop.active {
  opacity: 1;
  pointer-events: all;
}

/* Mobile Menu Panel */
.mobile-menu-panel {
  position: fixed;
  top: 0;
  left: 0;
  width: 280px;
  height: 100vh;
  background: white;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
  transform: translateX(-100%);
  transition: transform 0.3s ease-in-out;
  display: flex;
  flex-direction: column;
}

.mobile-menu-panel.open {
  transform: translateX(0);
}

/* Menu Header */
.menu-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #e5e7eb; /* gray-200 */
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

.close-button {
  padding: 0.5rem;
  border-radius: 0.375rem;
  color: #4b5563; /* gray-600 */
  transition: background-color 0.2s;
}

.close-button:hover {
  background-color: #f9fafb; /* gray-50 */
}

.close-button i {
  font-size: 1.25rem;
}

/* Navigation Menu */
.menu-navigation {
  flex: 1;
  padding: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.menu-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  border-radius: 0.375rem;
  font-weight: 500;
  font-size: 0.875rem;
  text-decoration: none;
  transition: all 0.2s;
  white-space: nowrap;
}

.menu-item-active {
  background-color: #3b82f6; /* primary */
  color: white;
}

.menu-item-inactive {
  color: #4b5563; /* gray-600 */
}

.menu-item-inactive:hover {
  color: #3b82f6; /* primary */
  background-color: #f9fafb; /* gray-50 */
}

.menu-icon {
  width: 1.25rem;
  margin-right: 0.75rem;
  text-align: center;
}

.menu-label {
  font-size: 0.875rem;
}

/* Menu Footer */
.menu-footer {
  padding: 1rem;
  border-top: 1px solid #f3f4f6; /* gray-100 */
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.status-indicator {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.75rem;
  color: #4b5563; /* gray-600 */
}

.online-dot {
  width: 0.5rem;
  height: 0.5rem;
  background-color: #10b981; /* secondary */
  border-radius: 50%;
}

.store-info {
  font-size: 0.75rem;
  color: #4b5563; /* gray-600 */
}

/* Hide mobile menu on large screens */
@media (min-width: 1024px) {
  .mobile-menu-wrapper {
    display: none;
  }
}
</style>