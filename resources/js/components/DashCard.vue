<template>
    <div
        class="relative flex aspect-video flex-col justify-between overflow-hidden rounded-xl border border-sidebar-border/70 p-5 dark:border-sidebar-border"
    >
        <img
            src="https://images.unsplash.com/photo-1588345921523-c2dcdb7f1dcd?w=800&dpr=2&q=80"
            alt="Photo by Drew Beamer"
            class="absolute inset-0 h-full w-full rounded-xl object-cover opacity-30"
        />
        <h1 class="relative text-2xl font-bold">{{ Name }} Count</h1>
        <h1 class="relative mt-auto mb-0 self-end text-5xl font-extrabold">{{ Count }}</h1>
    </div>
</template>

<script setup lang="ts">
interface Props {
    Name: string;
    Count?: number;
}

defineProps<Props>();
</script>

<style scoped></style>
