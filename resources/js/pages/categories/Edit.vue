<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Category } from '@/types/category';
import { Head, useForm } from '@inertiajs/vue3';

const { category } = defineProps<{ category: Category }>();
console.log('category:', category);
const form = useForm({
    name: category.name || '',
    type: category.type || 0,
    desc: category.desc || '',
});

const handleSubmit = () => {
    console.log('Form submitted:', form);
    form.put(route('categories.update', { category: category }), {
        onSuccess: () => {
            form.reset();
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
        },
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Edit a category',
        href: `/categories/${category.id}/edit`,
    },
];
</script>

<template>
    <Head title="Edit category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit()" class="w-1/2 space-y-4">
                <div class="space-y-4">
                    <Label for="name" class="mb-2 block"> category Name </Label>
                    <Input v-model="form.name" type="text" placeholder="Kopi Susu" />
                    <div class="text-sm text-red-500" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <div class="space-y-4">
                    <Label for="type" class="mb-2 block"> Type </Label>
                    <select v-model="form.type" name="type" id="type" class="w-full rounded border px-3 py-2 focus:ring focus:outline-none">
                        <option value="" disabled>Select type</option>
                        <option value="1">Beverage</option>
                        <option value="2">Food</option>
                        <option value="3">Other</option>
                    </select>
                    <div class="text-sm text-red-500" v-if="form.errors.type">{{ form.errors.type }}</div>
                </div>
                <div class="space-y-4">
                    <Label for="price" class="mb-2 block"> Description </Label>
                    <Input type="text" v-model="form.desc" placeholder="Kopi dengan Susu" />
                    <div class="text-sm text-red-500" v-if="form.errors.desc">{{ form.errors.desc }}</div>
                </div>
                <Button :disabled="form.processing" type="submit"> Edit category </Button>
            </form>
        </div>
    </AppLayout>
</template>
