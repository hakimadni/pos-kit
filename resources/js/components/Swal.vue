<template>
    <button @click="showAlert">Hello world</button>
</template>

<script setup lang="ts">
export default {
    methods: {
        showAlert() {
            // Use sweetalert2
            this.$swal('Hello Vue world!!!');
        },
    },
};
</script>
