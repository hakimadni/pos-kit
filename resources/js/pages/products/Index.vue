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
        title: 'Products',
        href: '/products',
    },
];
const handleDelete = (id: number) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(route('products.destroy', { id }));
    }
};
defineProps({ products: Object });
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div v-if="page.props.flash?.message" class="mb-4">
                <Alert class="mb-4 bg-blue-100">
                    <Check />
                    <AlertTitle>Product Created!</AlertTitle>
                    <AlertDescription>
                        {{ page.props.flash?.message }}
                    </AlertDescription>
                </Alert>
            </div>
            <Link href="/products/create" class="mb-4 inline-block">
                <Button>Create New</Button>
            </Link>
            <Table>
                <TableCaption>A list of your recent product.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]"> ID </TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead class="text-right"> Price </TableHead>
                        <TableHead>Menu Set</TableHead>
                        <TableHead>Category</TableHead>
                        <TableHead>Description</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="product in products" :key="product.id">
                        <TableCell class="font-medium"> {{ product.id }} </TableCell>
                        <TableCell>{{ product.name }}</TableCell>
                        <TableCell class="text-right">{{
                            new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(product.price)
                        }}</TableCell>
                        <TableCell> {{ product.menuset?.name }} </TableCell>
                        <TableCell> {{ product.category?.name ?? 'N/A' }} </TableCell>
                        <TableCell> {{ product.desc }} </TableCell>
                        <TableCell class="text-center">
                            <Button class="bg-slate-500 text-white hover:bg-slate-600">
                                <Link :href="route('products.edit', { id: product.id })">Edit</Link>
                            </Button>
                            <Button class="ml-2 bg-red-500 text-white hover:bg-red-600" @click="handleDelete(product.id)"> Delete </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
