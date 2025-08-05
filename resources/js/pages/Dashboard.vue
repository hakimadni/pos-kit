<script setup lang="ts">
import DashCard from '@/components/DashCard.vue';
import DateRangePicker from '@/components/DateRangePicker.vue';
import Sales from '@/components/Sales.vue';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { OrderItem } from '@/types/cart';
import { Head, router, usePage } from '@inertiajs/vue3';
import { CalendarDate } from '@internationalized/date';
import { DateRange } from 'reka-ui';
import { ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
const props = defineProps<{
    productsCount?: number;
    categoriesCount?: number;
    orders?: OrderItem[];
}>();

const user = usePage().props.auth.user;
const isHakim = user.name == 'hakim';

const tableData = ref<OrderItem[]>(props.orders || []);

watch(
    () => props.orders,
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

const now = new Date();
const year = now.getFullYear();
const month = now.getMonth() + 1;
const day = now.getDate();

const today = new CalendarDate(year, month, day);

const initialDateRange: DateRange = {
    start: today,
    end: today,
};

const handleDateRangeChange = (range: SelectedDateRange) => {
    if (range.start && range.end) {
        // Format dates to YYYY-MM-DD for consistency with backend
        const startDate = `${range.start.year}-${String(range.start.month).padStart(2, '0')}-${String(range.start.day).padStart(2, '0')}`;
        const endDate = `${range.end.year}-${String(range.end.month).padStart(2, '0')}-${String(range.end.day).padStart(2, '0')}`;

        router.get(
            route('dashboard'), // Your transactions index route
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
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-4">
                <DashCard Name="Product" :Count="productsCount" class="col-span-2 md:col-span-1" />
                <DashCard Name="Categories" :Count="categoriesCount" class="col-span-2 md:col-span-1" />
            </div>
            <Card class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <CardHeader>
                    <div class="grid w-full grid-cols-1 items-center md:grid-cols-2">
                        <div class="mb-2 hidden md:mb-0 md:flex">
                            <CardTitle>Order Summary</CardTitle>
                        </div>

                        <div class="flex justify-end" v-if="isHakim">
                            <div class="space-y-2">
                                <DateRangePicker @update:dateRange="handleDateRangeChange" :dateRange="initialDateRange"></DateRangePicker>
                            </div>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <Sales :orders="tableData" />
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
