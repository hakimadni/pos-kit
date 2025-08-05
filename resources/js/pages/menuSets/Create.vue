<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    desc: '',
});

const handleSubmit = () => {
    console.log('Form submitted:', form);
    form.post(route('menuSets.store'), {
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
        title: 'Create menuSet',
        href: '/menuSets/create',
    },
];
</script>

<template>
    <Head title="Create menuSet" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit()" class="w-1/2 space-y-4">
                <div class="space-y-4">
                    <Label for="name" class="mb-2 block"> menuSet Name </Label>
                    <Input v-model="form.name" type="text" placeholder="Event? Bazaar? Lapak?" />
                    <div class="text-sm text-red-500" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <div class="space-y-4">
                    <Label for="price" class="mb-2 block"> Description </Label>
                    <Input type="text" v-model="form.desc" placeholder="Apa ini deskripsi nya?" />
                    <div class="text-sm text-red-500" v-if="form.errors.desc">{{ form.errors.desc }}</div>
                </div>
                <Button :disabled="form.processing" type="submit"> Create menuSet </Button>
            </form>
        </div>
    </AppLayout>
</template>
