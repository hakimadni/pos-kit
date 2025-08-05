<script setup lang="ts">
import DataTable from '@/components/DataTable.vue';
import DateRangePicker from '@/components/DateRangePicker.vue';
import Alert from '@/components/ui/alert/Alert.vue';
import AlertDescription from '@/components/ui/alert/AlertDescription.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { columns } from '@/types/columns';
import { Transaction } from '@/types/transaction';
import { Head, router, usePage } from '@inertiajs/vue3'; // Import 'router'
import { CalendarDate } from '@internationalized/date'; // Import CalendarDate for date range type
import { Check } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const page = usePage();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Transactions',
        href: '/transactions',
    },
];

const props = defineProps<{
    transactions: Transaction[];
}>();

const tableData = ref<Transaction[]>(props.transactions || []);

watch(
    () => props.transactions,
    (newData) => {
        tableData.value = newData || [];
    },
    { deep: true },
);

// --- New: Handle date range change ---
interface SelectedDateRange {
    start: CalendarDate;
    end: CalendarDate;
}

const handleDateRangeChange = (range: SelectedDateRange) => {
    if (range.start && range.end) {
        // Format dates to YYYY-MM-DD for consistency with backend
        const startDate = `${range.start.year}-${String(range.start.month).padStart(2, '0')}-${String(range.start.day).padStart(2, '0')}`;
        const endDate = `${range.end.year}-${String(range.end.month).padStart(2, '0')}-${String(range.end.day).padStart(2, '0')}`;

        router.get(
            route('transactions.index'), // Your transactions index route
            {
                start_date: startDate,
                end_date: endDate,
            },
            {
                preserveState: true, // Preserve current scroll position, form data
                preserveScroll: true, // Preserve scroll position
                replace: true, // Replace the current history entry instead of pushing a new one
            },
        );
    }
};
</script>

<template>
    <Head title="Transaction" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div v-if="page.props.flash?.message" class="mb-4">
                <Alert class="mb-4 bg-blue-100">
                    <Check />
                    <AlertTitle>Transaction Created!</AlertTitle>
                    <AlertDescription>
                        {{ page.props.flash?.message }}
                    </AlertDescription>
                </Alert>
            </div>
            <Card class="pb-2">
                <CardHeader>
                    <div class="grid w-full grid-cols-1 items-center md:grid-cols-2">
                        <div class="mb-2 hidden md:mb-0 md:flex">
                            <CardTitle>Transactions</CardTitle>
                        </div>
                        <div class="flex justify-end">
                            <div class="space-y-2">
                                <DateRangePicker @update:dateRange="handleDateRangeChange"></DateRangePicker>
                            </div>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <DataTable :columns="columns" :data="tableData" />
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
