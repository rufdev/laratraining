<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link ,usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, LaptopMinimalCheck } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
        roles: ['super_admin','inventory_manager','inventory_user'],
    },
    {
        title: 'Assets',
        href: '/assets',
        icon: LaptopMinimalCheck,
        roles: ['super_admin','inventory_user'],
    },
    {
        title: 'Categories',
        href: '/categories',
        icon: LayoutGrid,
        roles: ['super_admin'],
    },
    {
        title: 'Manufacturers',
        href: '/manufacturers',
        icon: LayoutGrid,
        roles: ['super_admin','inventory_manager'],
    },
    {
        title: 'Locations',
        href: '/locations',
        icon: LayoutGrid,
        roles: ['super_admin','inventory_manager'],
    },

];


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

// Access the authenticated user's role from Inertia props
const page = usePage();
const userRole = computed(() => page.props.auth?.user?.role || null);


// Filter navigation items based on the user's role
const filteredNavItems = computed(() => {
    return mainNavItems.filter((item) => {
        // If no roles are defined, show the item to everyone
        if (!item.roles) return true;
        // Check if the user's role is allowed
        return item.roles.includes(userRole.value);
    });
});

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
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
            <NavMain :items="filteredNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
