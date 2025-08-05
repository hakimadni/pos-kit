<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { mainNavItems, restrictedNavItems } from '@/types/navItems';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

// Get the Inertia page props
const user = usePage().props.auth.user;

// Access the is_hakim flag from the shared props
const isHakim = user?.name == 'hakim' || false;

// Computed property to determine which items to display
const visibleNavItems = computed<NavItem[]>(() => {
    if (isHakim) {
        return restrictedNavItems;
    } else {
        // Others see Dashboard and POS
        return mainNavItems.filter((item) => item.href === '/dashboard' || item.href === '/pos');
    }
});

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
console.log(isHakim);
</script>

<template>
    <Sidebar collapsible="icon" variant="floating">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <h1>Hi, {{ user.name }}</h1>
            <NavMain :items="visibleNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
