<script setup lang="ts">
import Alert from '@/components/ui/alert/Alert.vue';
import AlertDescription from '@/components/ui/alert/AlertDescription.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
import Button from '@/components/ui/button/Button.vue';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Check } from 'lucide-vue-next';

const page = usePage();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Orders',
        href: '/orders',
    },
];
const handleDelete = (id: number) => {
    if (confirm('Are you sure you want to delete this order?')) {
        router.delete(route('orders.destroy', { id }));
    }
};
defineProps({ orders: Object });
</script>

<template>
    <Head title="Orders" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div v-if="page.props.flash?.message" class="mb-4">
                <Alert class="mb-4 bg-blue-100">
                    <Check />
                    <AlertTitle>Order Created!</AlertTitle>
                    <AlertDescription>
                        {{ page.props.flash?.message }}
                    </AlertDescription>
                </Alert>
            </div>
            <Table>
                <TableCaption>A list of your recent orders.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]">ID</TableHead>
                        <TableHead>User ID</TableHead>
                        <TableHead>Transaction ID</TableHead>
                        <TableHead>Order Items</TableHead>
                        <TableHead class="text-right">Total Amount</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="order in orders" :key="order.id">
                        <TableCell class="font-medium">{{ order.id }}</TableCell>
                        <TableCell>{{ order.user_id }}</TableCell>
                        <TableCell>{{ order.transaction.id }}</TableCell>
                        <TableCell>{{ order.gobiz_transaction_id }}</TableCell>
                        <TableCell class="text-right">
                            {{ new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(order.total_amount) }}
                        </TableCell>
                        <TableCell>{{ order.status }}</TableCell>
                        <TableCell class="text-center">
                            <Button class="bg-slate-500 text-white hover:bg-slate-600">
                                <Link :href="route('orders.edit', { id: order.id })">Edit</Link>
                            </Button>
                            <Button class="ml-2 bg-red-500 text-white hover:bg-red-600" @click="handleDelete(order.id)">Delete</Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
