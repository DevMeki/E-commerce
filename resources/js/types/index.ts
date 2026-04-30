import { LucideIcon } from 'lucide-react';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    url: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    flash: {
        success?: string;
        error?: string;
        warning?: string;
        info?: string;
    };
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    brand_name?: string;
    slug?: string;
    type?: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface Brand {
    id: number;
    name?: string;
    brand_name: string;
    slug: string;
    logo?: string;
    location?: string;
    created_at: string;
    rating?: number;
    bio?: string;
    category?: string;
    total_products?: number;
    followers?: number;
    shipping_policy?: string;
    return_policy?: string;
    total_reviews?: number;
    [key: string]: unknown;
}

export interface Paginated<T> {
    data: T[];
    links?: unknown[];
    meta?: unknown;
    current_page?: number;
    last_page?: number;
    total?: number;
}

export interface Product {
    id: number;
    name: string;
    description?: string;
    price: number;
    category?: string;
    main_image?: string;
    brand?: Brand;
    stock: number;
    short_desc?: string;
    long_desc?: string;
    ships_from?: string;
    processing_time?: string;
    shipping_fee?: number;
    brand_slug?: string;
    brand_name?: string;
    rating?: number;
    total_reviews?: number;
    total_sales?: number;
    compare_at_price?: number;
    slug?: string;
    sku?: string;
    status?: string;
    visibility?: string;
    variants_text?: string;
    [key: string]: unknown;
}

export interface HomeProps {
    featuredProducts: Product[];
    totalProducts: number;
    categories: string[];
    featuredBrands: Brand[];
}

export interface MarketplaceProps {
    products: Paginated<Product>;
    categories: string[];
    filters: {
        q?: string;
        category?: string;
        [key: string]: unknown;
    };
}

export interface CartItem {
    id: string | number;
    product_id: number;
    name: string;
    price: number;
    qty: number;
    main_image?: string;
    seller: string;
    variant: string | null;
}

export interface CartProps {
    cartItems: CartItem[];
    subtotal: number;
    total: number;
    deliveryEstimate: number;
}

export interface ProductPageProps {
    product: Product;
    seller: Brand;
    images: string[];
    relatedProducts: Product[];
    moreBrandProducts: Product[];
    variants: Record<string, string[]>;
}

export interface StoreProps {
    store: Brand;
    products: Product[];
    categories: string[];
    isFollowing: boolean;
}

export interface OrderItem {
    id: number;
    product_name: string;
    product_id: number;
    product?: Product;
    quantity: number;
    unit_price: number;
    subtotal: number;
    [key: string]: unknown;
}

export interface Order {
    id: number;
    order_number: string;
    customer_name: string;
    customer_email?: string;
    total: number;
    status: string;
    created_at: string;
    address?: {
        address_line1: string;
        city: string;
        state: string;
        country: string;
    };
    items: OrderItem[];
    [key: string]: unknown;
}

export interface BrandDashboardProps {
    brand: Brand;
    stats: {
        revenue_today: number;
        revenue_30d: number;
        orders_30d: number;
        products_live: number;
    };
    recentOrders: Order[];
}

export interface OrdersProps {
    orders: Paginated<Order>;
    statusCounts: Record<string, number>;
    filters: {
        status?: string;
        q?: string;
    };
}

export interface BrandProductsProps {
    products: Paginated<Product>;
    stats: {
        all: number;
        active: number;
        draft: number;
        hidden: number;
    };
    filters: {
        status?: string;
        q?: string;
    };
}

export interface PurchasesProps {
    orders: Order[];
}

export interface WishlistItem {
    id: number;
    product_id: number;
    product: Product;
}

export interface WishlistProps {
    wishlist: WishlistItem[];
}
