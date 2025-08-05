<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { router } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';
import { computed, defineProps, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import { CartItem } from '../types/cart';
import NumberField from './ui/number-field/NumberField.vue';
import NumberFieldContent from './ui/number-field/NumberFieldContent.vue';
import NumberFieldDecrement from './ui/number-field/NumberFieldDecrement.vue';
import NumberFieldIncrement from './ui/number-field/NumberFieldIncrement.vue';
import NumberFieldInput from './ui/number-field/NumberFieldInput.vue';

const dialogOpen = ref(false);
const selectedItemId = ref<number | null>(null);

const props = defineProps<{
    cartItems: CartItem[];
}>();

const localCartItems = ref<CartItem[]>([]);

watch(
    () => props.cartItems,
    (newItems) => {
        localCartItems.value = newItems.map((item) => ({ ...item }));
    },
    { immediate: true },
);

const confirmDelete = (id: number) => {
    selectedItemId.value = id;
    dialogOpen.value = true;
};

const handleDialogConfirm = () => {
    if (!selectedItemId.value) return;
    router.delete(route('carts.destroy', { id: selectedItemId.value }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            localCartItems.value = localCartItems.value.filter((item) => item.id !== selectedItemId.value);

            dialogOpen.value = false;
            selectedItemId.value = null;
        },
    });
};

const handleUpdate = (value: number, item: CartItem) => {
    if (value === item.quantity) return;

    router.put(
        route('carts.update', { cart: item.id }),
        {
            product_id: item.product.id,
            quantity: value,
            price: item.price,
        },
        {
            preserveScroll: true,
            preserveState: true, // allow props refresh
            only: ['cart'],
            onSuccess: () => {
                toast.success('Cart updated successfully');
            },
        },
    );
};

const total = computed(() => props.cartItems.reduce((sum, item) => sum + item.price * item.quantity, 0).toLocaleString('id-ID'));

// const cartItems = props.cartItems;
const emit = defineEmits<{
    (e: 'confirm-checkout'): void;
}>();
</script>

<template>
    <AlertDialog v-model:open="dialogOpen">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Remove this item?</AlertDialogTitle>
                <AlertDialogDescription> This will remove the product from your cart. You can’t undo this action. </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel @click="dialogOpen = false">Cancel</AlertDialogCancel>
                <AlertDialogAction @click="handleDialogConfirm">Remove</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>

    <div class="m-0 flex h-full w-full flex-col">
        <div class="flex items-center space-x-3 border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <div class="flex aspect-square size-10 items-center justify-center rounded-lg bg-blue-600 text-white shadow">
                <ShoppingCart class="size-6" />
            </div>
            <span class="text-xl font-bold tracking-tight">Your Cart</span>
        </div>
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <div v-if="localCartItems.length">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li v-for="item in localCartItems" :key="item.id" class="flex-col items-center justify-between py-3">
                        <div class="flex items-center justify-between space-x-4">
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ item.product.name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Rp {{ item.product.price.toLocaleString('id-ID') }}</div>
                        </div>
                        <div class="flex items-center justify-between space-x-4">
                            <div class="font-semibold text-gray-800 dark:text-gray-200">
                                Rp {{ (item.quantity * item.price).toLocaleString('id-ID') }}
                            </div>
                        </div>
                        <div class="grid grid-cols-2 items-center gap-2 pt-2">
                            <NumberField :model-value="item.quantity" :min="1" @update:model-value="handleUpdate($event, item)">
                                <NumberFieldContent>
                                    <NumberFieldDecrement />
                                    <NumberFieldInput />
                                    <NumberFieldIncrement />
                                </NumberFieldContent>
                            </NumberField>
                            <button
                                class="ml-2 text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                @click="() => confirmDelete(item.id)"
                            >
                                Remove
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
            <div v-else class="py-10 text-center text-gray-400">
                <ShoppingCart class="mx-auto mb-2 size-10" />
                <div class="font-medium">Your cart is empty</div>
            </div>
        </div>
        <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
            <div class="flex-col items-center justify-between">
                <div class="flex items-center justify-between">
                    <div class="text-base font-semibold">Products</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ localCartItems.length }} Products</div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="text-base font-semibold">Quantity</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ localCartItems.reduce((sum, item) => sum + item.quantity, 0) }} items
                    </div>
                </div>
                <div class="flex items-center justify-between py-2">
                    <div class="text-base font-semibold">Total</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Rp. {{ total }}</div>
                </div>
                <button
                    class="btn btn-primary rounded-lg bg-blue-600 px-6 py-2 font-semibold text-white shadow transition hover:bg-blue-700"
                    :disabled="!cartItems.length"
                    @click="emit('confirm-checkout')"
                >
                    Checkout
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
