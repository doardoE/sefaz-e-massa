<script setup>
import { useRoute } from 'vue-router'
import { computed } from 'vue'

import Header from './components/Header.vue'
import Footer from './components/Footer.vue'

const route = useRoute()

// computed reativo → atualiza quando a rota muda
const showLayout = computed(() => route.path !== '/login')
</script>

<template>
  <Header v-if="showLayout"></Header>
  <router-view />
  <Footer v-if="showLayout"></Footer>
</template>
