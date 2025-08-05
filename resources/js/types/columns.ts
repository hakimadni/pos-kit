// resources/js/types/columns.ts
import { Badge } from '@/components/ui/badge'; // Example for status badges
import { Button } from '@/components/ui/button'; // Example for action buttons
import { router } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue'; // Needed for rendering Vue components/elements
import { OrderItem } from './cart';
import { Transaction } from './transaction'; // Your Transaction interface

export const columns: ColumnDef<Transaction>[] = [
    {
        accessorKey: 'orders', // This accessorKey is primarily for internal data handling by TanStack Table
        header: 'Order Items',
        cell: ({ row }) => {
            // Get the orders array from the current row
            const orders = row.original.orders; // Access row.original to get the full object

            if (!orders || orders.length === 0) {
                return h('div', { class: 'text-gray-500' }, 'No items');
            }

            // Slice the array to get only the first 3 items
            const limitedOrders = orders.slice(0, 3);
            const hasMore = orders.length > 3;

            // Create the list items
            const listItems = limitedOrders.map((item: OrderItem, index: number) => {
                return h('li', { key: index }, `${item.product.name} × ${item.quantity}`);
            });

            // Add an ellipsis item if there are more than 3
            if (hasMore) {
                listItems.push(h('li', { key: 'more' }, '...and more'));
            }

            // Return the unordered list
            return h('ul', { class: 'list-disc pl-4' }, listItems);
        },
    },
    {
        accessorKey: 'total_amount',
        header: () => h('div', { class: 'text-right' }, 'Amount'),
        cell: ({ row }) => {
            const amount = Number.parseFloat(row.getValue('total_amount') as string);
            const formatted = new Intl.NumberFormat('en-ID', {
                style: 'currency',
                currency: 'IDR', // Adjust to your local currency, e.g., 'IDR'
            }).format(amount);

            return h('div', { class: 'text-right font-medium' }, formatted);
        },
    },
    {
        accessorKey: 'status',
        header: 'Status',
        cell: ({ row }) => {
            const status = row.getValue('status') as string;
            let variant: 'default' | 'secondary' | 'outline' | 'destructive' = 'secondary';
            let statusText = status;

            switch (status.toLowerCase()) {
                case 'completed':
                    variant = 'default'; // Or 'success' if you define a custom success variant
                    statusText = 'Completed';
                    break;
                case 'pending':
                    variant = 'outline';
                    statusText = 'Pending';
                    break;
                case 'cancelled':
                    variant = 'destructive';
                    statusText = 'Cancelled';
                    break;
                // Add more cases as needed
            }
            // Using Shadcn Vue's Badge component for visual distinction
            return h(Badge, { variant: variant }, () => statusText);
        },
    },
    {
        accessorKey: 'created_at',
        header: 'Date',
        cell: ({ row }) => {
            const dateValue = row.getValue('created_at') as string;
            const date = new Date(dateValue);
            return h('div', {}, date.toLocaleDateString()); // Formats to MM/DD/YYYY or similar based on locale
        },
    },
    {
        id: 'actions', // Unique ID for the actions column
        enableHiding: false, // Prevents this column from being hidden by a column toggler
        header: () => h('div', { class: 'sr-only' }, 'Actions'), // Accessible header for screen readers
        cell: ({ row }) => {
            const transaction = row.original; // Get the full transaction object for actions

            return h('div', { class: 'relative flex justify-end gap-2' }, [
                h(
                    Button,
                    {
                        variant: 'secondary',
                        size: 'sm',
                        onClick: () => {
                            console.log('Viewing transaction:', transaction.id);
                            // Example: Navigate to an edit page using Inertia.js
                            // router.visit(route('transactions.show', { id: transaction.id }));
                        },
                    },
                    () => 'View',
                ),
                h(
                    Button,
                    {
                        variant: 'destructive',
                        size: 'sm',
                        onClick: () => {
                            // Implement your delete logic here
                            // You might want to emit an event up to the parent page component,
                            // or directly use Inertia.js for deletion like this:
                            if (confirm(`Are you sure you want to delete #${transaction.id}?`)) {
                                router.delete(route('transactions.destroy', { id: transaction.id }));
                                // console.log('Deleting transaction:', transaction.id);
                            }
                        },
                    },
                    () => 'Delete',
                ),
            ]);
        },
    },
];
