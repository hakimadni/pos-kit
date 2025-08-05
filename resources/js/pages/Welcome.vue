<script setup lang="ts">
import { router } from '@inertiajs/vue3';

function goToLogin() {
    router.visit(route('login'));
}

function goToRegister() {
    router.visit(route('register'));
}
function goToDashboard() {
    router.visit(route('dashboard'));
}
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#ffe8bb] text-[#1b1b18] lg:justify-center dark:bg-[#cf9b34]">
        <header class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <!-- <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </template> -->
            </nav>
        </header>

        <div class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
            <main class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <div
                    class="m-10 mt-0 mb-10 flex flex-1 items-center rounded-4xl bg-[#FDFDFC] p-6 pb-12 text-[13px] leading-[20px] shadow-lg/50 md:m-15 md:mt-15 lg:p-10 dark:bg-[#161615] dark:text-[#EDEDEC]"
                >
                    <div class="flex">
                        <div class="items-center">
                            <h1 v-if="$page.props.auth.user" class="mb-10 text-4xl">Hi, {{ $page.props.auth.user.name }}!</h1>
                            <h1 v-else class="mb-10 text-4xl">Halo kalipers!</h1>
                            <Button
                                v-if="$page.props.auth.user"
                                @click="goToDashboard"
                                class="me-5 inline-block rounded-sm border border-[#19140035] bg-[#FFC655] px-5 py-1.5 text-sm leading-normal text-[#191501] hover:bg-[#1915014a] hover:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                            >
                                To Dashboard
                            </Button>
                            <template v-else>
                                <Button
                                    @click="goToLogin"
                                    class="me-5 inline-block rounded-sm border border-[#19140035] bg-[#FFC655] px-5 py-1.5 text-sm leading-normal text-[#191501] hover:bg-[#1915014a] hover:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                                >
                                    Log in
                                </Button>

                                <!-- <Button
                                    @click="goToRegister"
                                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                                >
                                    Register
                                </Button> -->
                            </template>
                        </div>
                    </div>
                </div>
                <div
                    class="relative aspect-335/376 w-full shrink-0 overflow-hidden rounded-t-lg lg:mb-0 lg:-ml-px lg:aspect-auto lg:w-[438px] lg:rounded-t-none lg:rounded-r-lg"
                >
                    <div class="flex h-full w-full items-center justify-center">
                        <img src="/Badged Kaliper Logo New.png" class="h-50 md:h-full" alt="" />
                    </div>
                </div>
            </main>
        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
