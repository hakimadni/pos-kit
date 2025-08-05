<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Product } from '@/types/product';
import { Head, useForm } from '@inertiajs/vue3';

const { product } = defineProps<{ product: Product }>();
console.log('Product:', product);
const form = useForm({
    name: product.name || '',
    price: product.price || 0,
    desc: product.desc || '',
    category_id: product.category_id || '',
    set_id: product.set_id || '',
});

const handleSubmit = () => {
    console.log('Form submitted:', form);
    form.put(route('products.update', { product: product }), {
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
        title: 'Edit a Product',
        href: `/products/${product.id}/edit`,
    },
];
</script>

<template>
    <Head title="Edit Product" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit()" class="w-1/2 space-y-4">
                <div class="space-y-4">
                    <Label for="name" class="mb-2 block"> Product Name </Label>
                    <Input v-model="form.name" type="text" placeholder="Kopi Susu" />
                    <div class="text-sm text-red-500" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <div class="space-y-4">
                    <Label for="price" class="mb-2 block"> Price </Label>
                    <Input v-model="form.price" type="number" name="price" placeholder="10000" />
                    <div class="text-sm text-red-500" v-if="form.errors.price">{{ form.errors.price }}</div>
                </div>
                <div class="space-y-4">
                    <Label for="category_id" class="mb-2 block"> Type </Label>
                    <Select v-model="form.category_id">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select Type" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Types</SelectLabel>
                                <SelectItem v-for="category in $page.props.categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <div class="text-sm text-red-500" v-if="form.errors.category_id">{{ form.errors.category_id }}</div>
                </div>
                <div class="space-y-4">
                    <Label for="set_id" class="mb-2 block"> Menu Set </Label>
                    <Select v-model="form.set_id">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select Menu Set" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Menu Sets</SelectLabel>
                                <SelectItem :value="null">None / Clear Selection</SelectItem>
                                <SelectItem v-for="set in $page.props.menuSets" :key="set.id" :value="set.id">
                                    {{ set.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <div class="text-sm text-red-500" v-if="form.errors.set_id">{{ form.errors.set_id }}</div>
                </div>
                <div class="space-y-4">
                    <Label for="price" class="mb-2 block"> Description </Label>
                    <Input type="text" v-model="form.desc" placeholder="Kopi dengan Susu" />
                    <div class="text-sm text-red-500" v-if="form.errors.desc">{{ form.errors.desc }}</div>
                </div>
                <Button :disabled="form.processing" type="submit"> Edit Product </Button>
            </form>
        </div>
    </AppLayout>
</template>
