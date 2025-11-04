<template>
  <div data-regular-price="12500" data-insurance-price="9400">
    <div class="aspect-square mb-1 rounded-md overflow-hidden">
      <img
        src="https://readdy.ai/api/search-image?query=Paracetamol%20tablets%20in%20blister%20pack%20with%20white%20pharmaceutical%20packaging%2C%20clean%20medical%20background%2C%20professional%20pharmacy%20product%20photography%2C%20soft%20natural%20lighting%2C%20clinical%20and%20sterile%20aesthetic&width=200&height=200&seq=paracetamol1&orientation=squarish"
        alt="Paracetamol 500mg" class="w-full h-full object-cover object-top">
    </div>
    <h3 class="font-medium text-gray-900 mb-1 text-xs line-clamp-2">{{ article.title }}</h3>
    <div class="mb-1">
      <p class="text-sm font-semibold text-primary price-display">{{ article.field_prix_unitaire }} Ar</p>
      <p class="text-xs text-gray-500 insurance-price hidden">Prix assurance: {{ article.field_nombre_par_unite }} Ar</p>
    </div>
    <div class="flex items-center justify-between">
      <span class="text-xs text-secondary font-medium truncate">En stock : {{ article.field_quantite_stock }}</span>
      <div class="w-2 h-2 bg-secondary rounded-full flex-shrink-0"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductCard',

  // Définition des props
  props: {
    article: {
      type: Object,
      required: true
    }
  },

  // Déclaration des événements émis
  emits: ['add-to-cart'],

  setup(props, { emit }) {

    // Fonction pour émettre l'événement vers le parent
    // function addToCart() {
    //   emit('add-to-cart', props.product)
    // }

    // Tout ce qu'on retourne ici sera accessible dans le template
    // return {
    //   addToCart
    // }
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>