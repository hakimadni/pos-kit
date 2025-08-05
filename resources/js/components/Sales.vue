<script setup lang="ts">
import { OrderItem } from '@/types/cart';
import { computed } from 'vue';

const props = defineProps<{
    orders?: OrderItem[];
}>();

function formatCurrency(amount: number | string) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(Number(amount));
}

const totalSales = computed(() => {
    return props.orders.reduce((sum, item) => sum + Number(item.total_sales), 0);
});
</script>
<template>
    <div class="min-h-full rounded-xl bg-white p-4 dark:bg-gray-900">
        <h2 class="mb-4 text-lg font-semibold">Sales</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm dark:border-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="border-b px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Product Name</th>
                        <th class="border-b px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Total Sold</th>
                        <th class="border-b px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in orders" :key="index" class="transition hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="border-b px-4 py-2 text-gray-800 dark:text-gray-100">
                            {{ item.product.name }}
                        </td>
                        <td class="border-b px-4 py-2 text-gray-800 dark:text-gray-100">
                            {{ item.total_quantity }}
                        </td>
                        <td class="border-b px-4 py-2 text-gray-800 dark:text-gray-100">
                            {{ item.total_sales }}
                        </td>
                    </tr>
                    <tr v-if="orders?.length === 0">
                        <td colspan="3" class="px-4 py-2 text-center text-gray-800 dark:text-white">No data available</td>
                    </tr>
                    <tr class="bg-gray-100 font-semibold dark:bg-gray-900">
                        <td class="border-t px-4 py-2 text-right dark:text-white" colspan="2">Total Sales:</td>
                        <td class="border-t px-4 py-2 text-gray-900 dark:text-white">
                            {{ formatCurrency(totalSales) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style lang="scss" scoped></style>
