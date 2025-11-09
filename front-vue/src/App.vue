<script setup>
import Header from './components/Header.vue';
import Footer from './components/Footer.vue';
import { useRoute } from 'vue-router'

const route = useRoute()
</script>

<template>
  <Header v-if="!route.meta.hideLayout"></Header>
  <router-view />
  <Footer v-if="!route.meta.hideLayout"></Footer>
</template>
