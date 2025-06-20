<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface Totals {
    total_assets: number;
    total_categories: number;
    total_manufacturers: number;
    total_locations: number;
    total_users: number;
}

const totals = ref<Totals>({
    total_assets: 0,
    total_categories: 0,
    total_manufacturers: 0,
    total_locations: 0,
    total_users: 0,
});
const charts = ref({} as any);
const loading = ref(true);

const fetchStats = async () => {
    try {
        const response = await axios.get('/dashboard/stats');
        totals.value = response.data.totals;
        charts.value = response.data.charts;
    } catch (error) {
        console.error('Error fetching dashboard stats:', error);
    }
};

const generateRandomColor = () => {
    const randomColor = `#${Math.floor(Math.random() * 16777215).toString(16)}`;
    return randomColor.padEnd(7, '0'); // Ensure the color is always 6 characters long
};

onMounted(async () => {
    loading.value = true; // Set loading state to true
    await fetchStats(); // Fetch stats from the server
    loading.value = false; // Set loading state to false
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4">
                <!-- Metric Item Start -->
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Total Assets</p>

                    <div class="mt-3 flex items-end justify-between">
                        <div>
                            <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ totals.total_assets }}</h4>
                        </div>
                    </div>
                </div>
                <!-- Metric Item End -->

                <!-- Metric Item Start -->
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Total Categories</p>

                    <div class="mt-3 flex items-end justify-between">
                        <div>
                            <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ totals.total_categories }}</h4>
                        </div>
                    </div>
                </div>
                <!-- Metric Item End -->

                <!-- Metric Item Start -->
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Total Manufacturers</p>

                    <div class="mt-3 flex items-end justify-between">
                        <div>
                            <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">{{totals.total_manufacturers}}</h4>
                        </div>
                       
                    </div>
                </div>
                <!-- Metric Item End -->

                <!-- Metric Item Start -->
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Total Locations</p>

                    <div class="mt-3 flex items-end justify-between">
                        <div>
                            <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">{{totals.total_locations}}</h4>
                        </div>
                       
                    </div>
                </div>
                <!-- Metric Item End -->
            </div>
            <div class="relative min-h-[60vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
