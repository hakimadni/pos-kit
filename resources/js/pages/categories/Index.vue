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
        title: 'categories',
        href: '/categories',
    },
];
const handleDelete = (id: number) => {
    if (confirm('Are you sure you want to delete this category?')) {
        router.delete(route('categories.destroy', { id }));
    }
};
defineProps({ categories: Object });
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div v-if="page.props.flash?.message" class="mb-4">
                <Alert class="mb-4 bg-blue-100">
                    <Check />
                    <AlertTitle>Category Created!</AlertTitle>
                    <AlertDescription>
                        {{ page.props.flash?.message }}
                    </AlertDescription>
                </Alert>
            </div>
            <Link href="/categories/create" class="mb-4 inline-block">
                <Button>Create New</Button>
            </Link>
            <Table>
                <TableCaption>A list of your recent category.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]"> ID </TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead class="text-right"> Type </TableHead>
                        <TableHead>Description</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="category in categories" :key="category.id">
                        <TableCell class="font-medium"> {{ category.id }} </TableCell>
                        <TableCell>{{ category.name }}</TableCell>
                        <TableCell class="text-right">
                            {{ category.type === 1 ? 'Beverages' : category.type === 2 ? 'Food' : category.type === 3 ? 'Others' : '' }}
                        </TableCell>
                        <TableCell> {{ category.desc }} </TableCell>
                        <TableCell class="text-center">
                            <Button class="bg-slate-500 text-white hover:bg-slate-600">
                                <Link :href="route('categories.edit', { id: category.id })">Edit</Link>
                            </Button>
                            <Button class="ml-2 bg-red-500 text-white hover:bg-red-600" @click="handleDelete(category.id)"> Delete </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
