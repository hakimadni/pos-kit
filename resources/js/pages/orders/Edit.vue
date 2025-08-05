<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Order } from '@/types/order';
import { Head, useForm } from '@inertiajs/vue3';

const { order } = defineProps<{ order: Order }>();
console.log('Order:', order);
const form = useForm({
    name: order.name || '',
    price: order.price || 0,
    desc: order.desc || '',
    category_id: order.category_id || '',
});

const handleSubmit = () => {
    console.log('Form submitted:', form);
    form.put(route('orders.update', { order: order }), {
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
        title: 'Edit a Order',
        href: `/orders/${order.id}/edit`,
    },
];
</script>

<template>
    <Head title="Edit Order" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit()" class="w-1/2 space-y-4">
                <div class="space-y-4">
                    <Label for="name" class="mb-2 block"> Order Name </Label>
                    <Input v-model="form.name" type="text" placeholder="Kopi Susu" />
                    <div class="text-sm text-red-500" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <div class="space-y-4">
                    <Label for="price" class="mb-2 block"> Price </Label>
                    <Input v-model="form.price" type="number" name="price" placeholder="10000" />
                    <div class="text-sm text-red-500" v-if="form.errors.price">{{ form.errors.price }}</div>
                </div>
                <div class="space-y-4">
                    <Label for="type" class="mb-2 block"> Type </Label>
                    <select v-model="form.category_id" name="type" id="type" class="w-full rounded border px-3 py-2 focus:ring focus:outline-none">
                        <option value="" disabled>Select categories</option>
                        <option v-for="category in $page.props.categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    <div class="text-sm text-red-500" v-if="form.errors.category_id">{{ form.errors.category_id }}</div>
                </div>
                <div class="space-y-4">
                    <Label for="price" class="mb-2 block"> Description </Label>
                    <Input type="text" v-model="form.desc" placeholder="Kopi dengan Susu" />
                    <div class="text-sm text-red-500" v-if="form.errors.desc">{{ form.errors.desc }}</div>
                </div>
                <Button :disabled="form.processing" type="submit"> Edit Order </Button>
            </form>
        </div>
    </AppLayout>
</template>
