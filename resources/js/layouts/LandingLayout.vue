<script setup lang="ts">
import { Toaster } from 'vue-sonner';
import Footer from '@/components/Footer.vue';
</script>

<template>
    <slot />
    <Footer />
    <Toaster position="bottom-center" />
</template>
