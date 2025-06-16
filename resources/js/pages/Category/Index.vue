<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue';
    import { defineProps } from 'vue';
    defineProps({
        message: String
    })
</script>
<template>
    <AppLayout>
        <h1>{{message}}</h1>
    </AppLayout>
</template>