<script setup lang="ts">
import Cart from '@/components/Cart.vue';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import Sheet from '@/components/ui/sheet/Sheet.vue';
import SheetContent from '@/components/ui/sheet/SheetContent.vue';
import SheetTrigger from '@/components/ui/sheet/SheetTrigger.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { CartItem } from '@/types/cart';
import { Product } from '@/types/product';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Point of Sales',
        href: '/pos',
    },
];
const page = usePage();
const props = defineProps<{
    productsCount?: number;
    categoriesCount?: number;
    products?: Product[];
    cart?: CartItem[];
    sets?: { id: number; name: string }[];
    currentSetId?: string | null;
}>();
const selectedMenuSet = ref(props.currentSetId ?? 'All');

//watch menu set
watch(selectedMenuSet, (newValue) => {
    router.get(route('pos.index'), { menu_set_id: newValue }, { preserveState: true, replace: true });
});

//watch new Message
watch(
    () => page.props.flash?.message,
    (message) => {
        if (message) {
            toast.success('Transaction Created!', {
                description: message,
            });
        }
    },
    { immediate: true },
);

const add = useForm({
    product_id: 0,
});

const atc = (product_id: number) => {
    add.product_id = product_id;
    toast.promise(
        new Promise((resolve, reject) => {
            add.post(route('carts.store'), {
                onSuccess: () => {
                    add.reset();
                    resolve(true);
                },
                onError: (errors) => {
                    console.error('Form submission errors:', errors);
                    reject(errors);
                },
            });
        }),
        {
            loading: 'Adding product to cart...',
            success: 'Product added to cart successfully',
            error: 'Failed to add product to cart',
        },
    );
};

const showConfirmDialog = ref(false);
const sheetOpen = ref(false);

const confirmCheckout = () => {
    showConfirmDialog.value = true;
};

const paymentTotal = computed(() => {
    if (!props.cart || props.cart.length === 0) {
        return (0).toLocaleString('id-ID'); // Handle empty cart
    }
    return props.cart.reduce((sum, item) => sum + item.price * item.quantity, 0).toLocaleString('id-ID');
});

const proceedToCheckout = () => {
    sheetOpen.value = false;
    router.post(route('pos.checkout'));
};
</script>

<template>
    <Head title="Point of Sales" />

    <AppLayout :breadcrumbs="breadcrumbs" class="max-h-screen overflow-hidden">
        <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid min-h-full grid-cols-2 gap-4 md:grid-cols-5">
                <Card
                    class="relative col-span-full flex rounded-xl border border-sidebar-border/70 lg:col-span-3 2xl:col-span-4 dark:border-sidebar-border"
                >
                    <CardHeader>
                        <div class="grid w-full grid-cols-1 items-center md:grid-cols-2">
                            <div class="mb-5 hidden md:mb-0 md:flex">
                                <CardTitle>Point of Sales</CardTitle>
                            </div>
                            <div class="flex justify-end">
                                <div class="space-y-2">
                                    <Label for="menu-set-filter">Menu Set</Label>
                                    <Select v-model="selectedMenuSet">
                                        <SelectTrigger id="menu-set-filter" class="w-[200px]">
                                            <SelectValue :placeholder="'Filter by Menu Set'" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem :value="'All'">All Products</SelectItem>
                                                <SelectItem v-for="set in props.sets" :key="set.id" :value="String(set.id)">
                                                    {{ set.name }}
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent class="max-h-[calc(100vh-250px)] flex-1 overflow-y-auto">
                        <div class="grid w-full grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-2 2xl:grid-cols-4">
                            <Card
                                v-for="product in products"
                                :key="product.id"
                                class="flex max-h-[200px] flex-col justify-between p-4"
                                @click="atc(product.id)"
                            >
                                <div>
                                    <h3 class="text-md mb-2 font-semibold">{{ product.name }}</h3>
                                    <p class="mb-4 text-sm text-gray-500">{{ product.desc }}</p>
                                </div>
                                <div class="mt-auto flex items-center justify-between">
                                    <span class="text-lg font-bold text-primary">Rp {{ product.price.toLocaleString() }}</span>
                                </div>
                            </Card>
                        </div>
                    </CardContent>
                </Card>
                <Card
                    class="relative col-span-full hidden h-full overflow-y-auto rounded-xl p-0 lg:col-span-2 lg:flex 2xl:col-span-1 dark:border-sidebar-border"
                >
                    <Cart :cartItems="cart ?? []" @confirm-checkout="confirmCheckout" />
                </Card>
                <Sheet v-model:open="sheetOpen" class="lg:hidden">
                    <SheetTrigger as-child>
                        <button class="fixed right-6 bottom-6 z-50 rounded-full bg-primary px-6 py-3 text-white shadow-lg lg:hidden dark:text-black">
                            Cart
                        </button>
                    </SheetTrigger>
                    <SheetContent side="right" class="max-h-[100vh] overflow-y-auto">
                        <Cart :cartItems="cart ?? []" @confirm-checkout="confirmCheckout" />
                    </SheetContent>
                </Sheet>
            </div>
            <AlertDialog v-model:open="showConfirmDialog">
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Payment</AlertDialogTitle>
                        <h1 class="text-3xl font-bold">Total: Rp {{ paymentTotal }}</h1>
                        <h1 class="text-xl">Pastikan Sudah bayar</h1>
                        <img src="/Qris.png" alt="Payment Confirmation" class="my-4 h-75 w-full object-contain lg:h-full" />
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel @click="showConfirmDialog = false">Cancel</AlertDialogCancel>
                        <AlertDialogAction @click="proceedToCheckout">Paid?</AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </div>
    </AppLayout>
</template>
