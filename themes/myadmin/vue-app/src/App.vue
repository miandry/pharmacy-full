<template>
    <Loader/>
    <Header/>
    <router-view />
</template>

<script setup>
// Logic global (si besoin)
</script>

<style scoped>
h1 {
  color: #2c3e50;
  margin-bottom: 20px;
}
nav a {
  margin-right: 10px;
  color: blue;
}
</style>
 
 <script>
import { ref, onMounted } from 'vue';
import Header from "./components/Header.vue";
import Loader from "./components/Loader.vue";
export default {
  name: 'App',
   components: {
    Header,
    Loader
  }
};
</script>   