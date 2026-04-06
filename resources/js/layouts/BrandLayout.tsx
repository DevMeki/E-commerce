import { Link, usePage } from '@inertiajs/react';
import { BarChart3, Box, MessageCircle, Settings, ShoppingCart } from 'lucide-react';
import { ReactNode } from 'react';

export default function BrandLayout({ children }: { children: ReactNode }) {
    const { props, url } = usePage() as any;
    const { auth } = props ?? {};
    const brand = auth?.user;

    const navItems = [
        { name: 'Dashboard', href: route('brand.dashboard'), icon: <BarChart3 className="h-4 w-4" /> },
        { name: 'Products', href: route('brand.products'), icon: <Box className="h-4 w-4" /> },
        { name: 'Orders', href: route('brand.orders'), icon: <ShoppingCart className="h-4 w-4" /> },
        { name: 'Messages', href: '#', icon: <MessageCircle className="h-4 w-4" /> }, // Placeholder for now
        { name: 'Settings', href: route('brand.onboarding'), icon: <Settings className="h-4 w-4" /> },
    ];

    return (
        <div className="min-h-screen bg-brand-parchment text-brand-ink font-sans flex flex-col">
            {/* Brand Header */}
            <header className="bg-brand-forest text-white sticky top-0 z-40 shadow-lg">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <Link href={route('brand.dashboard')} className="flex items-center gap-2">
                        <div className="w-8 h-8 rounded-full bg-brand-parchment flex items-center justify-center">
                            <span className="text-brand-forest font-bold text-xs">LT</span>
                        </div>
                        <span className="font-bold tracking-tight text-lg">LocalTrade <span className="text-brand-orange">Seller</span></span>
                    </Link>

                    <div className="flex items-center gap-4">
                        <Link href={route('store', { slug: brand?.slug })} className="hidden sm:block text-xs font-bold text-white/70 hover:text-white border border-white/20 px-3 py-1.5 rounded-full transition-all">
                            View My Store
                        </Link>
                        <div className="w-8 h-8 rounded-full bg-brand-parchment/10 flex items-center justify-center border border-white/10">
                            <span className="text-xs font-bold">{brand?.brand_name?.charAt(0)}</span>
                        </div>
                        <Link href={route('logout')} method="post" as="button" className="text-xs text-white/50 hover:text-red-400 font-bold uppercase tracking-wider">
                            Logout
                        </Link>
                    </div>
                </div>
            </header>

            <div className="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6 py-6 sm:py-8">
                {/* Sidebar Nav */}
                <aside className="w-full md:w-64 shrink-0">
                    <nav className="flex flex-row md:flex-col gap-2 overflow-x-auto md:overflow-visible pb-2 md:pb-0 scrollbar-hide">
                        {navItems.map((item) => (
                            <Link 
                                key={item.name} 
                                href={item.href}
                                className={`flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all whitespace-nowrap shrink-0 ${
                                    (url ?? '').startsWith(item.href) 
                                        ? 'bg-brand-forest text-white shadow-md' 
                                        : 'bg-green-50 text-brand-forest hover:bg-brand-forest/5 border border-brand-forest/5'
                                }`}
                            >
                                <span className="text-lg">{item.icon}</span>
                                {item.name}
                            </Link>
                        ))}
                    </nav>
                </aside>

                {/* Main Content */}
                <main className="flex-1 min-w-0">
                    {children}
                </main>
            </div>

            {/* Footer */}
            <footer className="mt-auto border-t border-brand-forest/10 bg-brand-cream/30 py-8">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-[11px] text-brand-ink/50 flex justify-between items-center">
                    <p>© {new Date().getFullYear()} LocalTrade Seller Central</p>
                    <div className="flex gap-4">
                        <Link href={route('brand.help')} className="hover:text-brand-orange transition-colors">Help Center</Link>
                        <Link href={route('brand.onboarding')} className="hover:text-brand-orange transition-colors">Store Settings</Link>
                    </div>
                </div>
            </footer>
        </div>
    );
}
