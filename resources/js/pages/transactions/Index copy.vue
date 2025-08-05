<script setup lang="ts">
import DataTable from '@/components/DataTable.vue';
import DateRangePicker from '@/components/DateRangePicker.vue';
import Alert from '@/components/ui/alert/Alert.vue';
import AlertDescription from '@/components/ui/alert/AlertDescription.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { columns } from '@/types/columns'; // Ensure this file exists and defines your columns
import { Transaction } from '@/types/transaction'; // Ensure this type is correctly defined
import { Head, router, usePage } from '@inertiajs/vue3';
import { Check } from 'lucide-vue-next';
import { ref, watch } from 'vue'; // Import watch and onMounted

const page = usePage();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Transactions',
        href: '/transactions',
    },
];

const handleDelete = (id: number) => {
    if (confirm('Are you sure you want to delete this transaction?')) {
        router.delete(route('transactions.destroy', { id }));
    }
};

// Define the transactions prop correctly
const props = defineProps<{
    transactions: Transaction[];
}>();

console.log(props.transactions);

const tableData = ref<Transaction[]>(props.transactions || []);

// Watch for changes in the transactions prop and update tableData
// This is important if transactions data can change without a full page reload (e.g., via Inertia's partial reloads)
watch(
    () => props.transactions,
    (newData) => {
        tableData.value = newData || [];
    },
    { deep: true },
);
</script>

<template>
    <Head title="Dashboard" />

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
            <Card>
                <CardHeader>
                    <div class="grid w-full grid-cols-1 items-center md:grid-cols-2">
                        <div class="mb-5 hidden md:mb-0 md:flex">
                            <CardTitle>Point of Sales</CardTitle>
                        </div>
                        <div class="flex justify-end">
                            <div class="space-y-2">
                                <DateRangePicker></DateRangePicker>
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
