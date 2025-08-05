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
        title: 'Menu Sets',
        href: '/menuSets',
    },
];
const handleDelete = (id: number) => {
    if (confirm('Are you sure you want to delete this Menu Set?')) {
        router.delete(route('menuSets.destroy', { id }));
    }
};
defineProps({ menuSets: Object });
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div v-if="page.props.flash?.message" class="mb-4">
                <Alert class="mb-4 bg-blue-100">
                    <Check />
                    <AlertTitle>MenuSet Created!</AlertTitle>
                    <AlertDescription>
                        {{ page.props.flash?.message }}
                    </AlertDescription>
                </Alert>
            </div>
            <Link href="/menuSets/create" class="mb-4 inline-block">
                <Button>Create New</Button>
            </Link>
            <Table>
                <TableCaption>A list of your recent Menu Set.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]"> ID </TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Description</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="menuSet in menuSets" :key="menuSet.id">
                        <TableCell class="font-medium"> {{ menuSet.id }} </TableCell>
                        <TableCell>{{ menuSet.name }}</TableCell>
                        <TableCell> {{ menuSet.desc }} </TableCell>
                        <TableCell class="text-center">
                            <Button class="bg-slate-500 text-white hover:bg-slate-600">
                                <Link :href="route('menuSets.edit', { id: menuSet.id })">Edit</Link>
                            </Button>
                            <Button class="ml-2 bg-red-500 text-white hover:bg-red-600" @click="handleDelete(menuSet.id)"> Delete </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
