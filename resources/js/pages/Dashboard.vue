<script setup lang="ts">

import BarChart from '@/components/chartcomponents/BarChart.vue';
import PieChart from '@/components/chartcomponents/PieChart.vue';
import DoughnutChart from '@/components/chartcomponents/DoughnutChart.vue';

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


let chartData: any;
let chartOptions: any;

let piechartData: any;
let piechartOptions: any;

let doughnutData: any;
let doughnutOptions: any;

let chart2Data: any;
let chart2Options: any;

const rendercharts = () => {
    // Asset by Status Bar Chart
    chartData = {
        labels: charts.value.assets_by_status ? charts.value.assets_by_status.map((item: any) => item.status) : [],
        datasets: [
            {
                label: 'Assets by Status',
                backgroundColor: charts.value.assets_by_status ? charts.value.assets_by_status.map(() => generateRandomColor()) : [],
                data: charts.value.assets_by_status ? charts.value.assets_by_status.map((item: any) => item.total) : [],
            },
        ],
    };
    chartOptions = {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
            title: {
                display: true,
                text: 'Total Assets by Status',
            },
            datalabels: {
                display: true,
                color: '#000000', // Label text color
                font: {
                    size: 14, // Label font size
                    weight: 'bold',
                },
                formatter: (value: any) => {
                    // const label = context.chart.data.labels[context.dataIndex];
                    return `${value}`; // Format: "Label: Value"
                },
            },
        },
    };

    //Pie Chart Asset by Category
    piechartData = {
        labels: charts.value.assets_by_category ? charts.value.assets_by_category.map((item: any) => item.category.name) : [],
        datasets: [
            {
                label: 'Assets by Category',
                backgroundColor: charts.value.assets_by_category ? charts.value.assets_by_category.map(() => generateRandomColor()) : [],
                data: charts.value.assets_by_category ? charts.value.assets_by_category.map((item: any) => item.total) : [],
            },
        ],
    };

    piechartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                
                position: 'left',
            },
            title: {
                display: true,
                text: 'Total Assets by Category',
            },
            datalabels: {
                display: true,
                color: '#000000', // Label text color
            },
        },
    };
    // Doughnut Chart Asset by Location
    doughnutData = {
        labels: charts.value.assets_by_location ? charts.value.assets_by_location.map((item: any) => item.location?.name || "NO LOCATION") : [],
        datasets: [
            {
                label: 'Assets by Location',
                backgroundColor: charts.value.assets_by_location ? charts.value.assets_by_location.map(() => generateRandomColor()) : [],
                data: charts.value.assets_by_location ? charts.value.assets_by_location.map((item: any) => item.total) : [],
            },
        ],
    };

    doughnutOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                
                position: 'left',
            },
            title: {
                display: true,
                text: 'Total Assets by Location',
            },
            datalabels: {
                display: true,
                color: '#000000', // Label text color
            },
        },
    };
    //Bar Chart Asset by User
    chart2Data = {
        labels: charts.value.assets_by_assigned_user ? charts.value.assets_by_assigned_user.map((item: any) => item.assigned_to?.name || "NOT ASSIGNED" ) : [],
        datasets: [
            {
                label: 'Assets by Status',
                backgroundColor: charts.value.assets_by_assigned_user ? charts.value.assets_by_assigned_user.map(() => generateRandomColor()) : [],
                data: charts.value.assets_by_assigned_user ? charts.value.assets_by_assigned_user.map((item: any) => item.total) : [],
            },
        ],
    };
    chart2Options = {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
            title: {
                display: true,
                text: 'Total Assets by User',
            },
            datalabels: {
                display: true,
                color: '#000000', // Label text color
                font: {
                    size: 14, // Label font size
                    weight: 'bold',
                },
                formatter: (value: any) => {
                    // const label = context.chart.data.labels[context.dataIndex];
                    return `${value}`; // Format: "Label: Value"
                },
            },
        },
    };
};

onMounted(async () => {
    loading.value = true; // Set loading state to true
    await fetchStats(); // Fetch stats from the server
    rendercharts();
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
                            <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ totals.total_manufacturers }}</h4>
                        </div>
                    </div>
                </div>
                <!-- Metric Item End -->

                <!-- Metric Item Start -->
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Total Locations</p>

                    <div class="mt-3 flex items-end justify-between">
                        <div>
                            <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ totals.total_locations }}</h4>
                        </div>
                    </div>
                </div>
                <!-- Metric Item End -->
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div v-if="loading" class="flex h-full items-center justify-center">
                        <div class="spinner-border inline-block h-8 w-8 animate-spin rounded-full border-4" role="status"></div>
                    </div>
                    <div v-else>
                        <BarChart :chart-data="chartData" :chart-options="chartOptions" />
                    </div>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div v-if="loading" class="flex h-full items-center justify-center">
                        <div class="spinner-border inline-block h-8 w-8 animate-spin rounded-full border-4" role="status"></div>
                    </div>
                    <div v-else>
                        <PieChart :chart-data="piechartData" :chart-options="piechartOptions" />
                    </div>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div v-if="loading" class="flex h-full items-center justify-center">
                        <div class="spinner-border inline-block h-8 w-8 animate-spin rounded-full border-4" role="status"></div>
                    </div>
                    <div v-else>
                        <DoughnutChart :chart-data="doughnutData" :chart-options="doughnutOptions" />
                    </div>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div v-if="loading" class="flex h-full items-center justify-center">
                        <div class="spinner-border inline-block h-8 w-8 animate-spin rounded-full border-4" role="status"></div>
                    </div>
                    <div v-else>
                        <BarChart :chart-data="chart2Data" :chart-options="chart2Options" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
