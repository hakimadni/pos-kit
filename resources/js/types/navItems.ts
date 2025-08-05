// types/nav.ts (or wherever you define NavItem)
import { Boxes, LayoutGrid, PackageSearch, TvMinimal, WalletMinimal } from 'lucide-vue-next'; // Import your icons
import type { FunctionalComponent, HTMLAttributes, SVGAttributes, VNodeProps } from 'vue';

export interface NavItem {
    title: string;
    href: string;
    icon: FunctionalComponent<SVGAttributes & VNodeProps & HTMLAttributes>;
}

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Point of Sales',
        href: '/pos',
        icon: TvMinimal,
    },
];

// Define the "restricted" items
const restrictedNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Point of Sales',
        href: '/pos',
        icon: TvMinimal,
    },
    // {
    //     title: 'Orders',
    //     href: '/orders',
    //     icon: ClipboardList,
    // },
    {
        title: 'Transactions',
        href: '/transactions',
        icon: WalletMinimal,
    },
    {
        title: 'Products',
        href: '/products',
        icon: PackageSearch,
    },
    {
        title: 'Menu Set',
        href: '/menuSets',
        icon: Boxes,
    },
    {
        title: 'Categories',
        href: '/categories',
        icon: Boxes,
    },
];

export { mainNavItems, restrictedNavItems };
