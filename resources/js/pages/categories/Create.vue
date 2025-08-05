<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    type: '',
    desc: '',
});

const handleSubmit = () => {
    console.log('Form submitted:', form);
    form.post(route('categories.store'), {
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
        title: 'Create category',
        href: '/categories/create',
    },
];
</script>

<template>
    <Head title="Create category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit()" class="w-1/2 space-y-4">
                <div class="space-y-4">
                    <Label for="name" class="mb-2 block"> category Name </Label>
                    <Input v-model="form.name" type="text" placeholder="Teh? Kopi? Sandwich?" />
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
                    <Input type="text" v-model="form.desc" placeholder="Apa ini deskripsi nya?" />
                    <div class="text-sm text-red-500" v-if="form.errors.desc">{{ form.errors.desc }}</div>
                </div>
                <Button :disabled="form.processing" type="submit"> Create category </Button>
            </form>
        </div>
    </AppLayout>
</template>
