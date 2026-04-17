import { useState, useEffect } from 'react';
import { Link, router, usePage } from '@inertiajs/react';

interface FlashProps {
    success?: string;
    error?: string;
    warning?: string;
    info?: string;
}

export default function Layout({ children }: { children: React.ReactNode }) {
    const { auth, flash } = usePage().props as any;
    const { component } = usePage();
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [notification, setNotification] = useState<{ message: string; type: 'success' | 'error' | 'warning' | 'info' } | null>(null);
    const [isNavigating, setIsNavigating] = useState(false);
    const [progress, setProgress] = useState(0);

    // Global navigation loading indicator
    useEffect(() => {
        let progressTimer: ReturnType<typeof setInterval>;

        const shouldShowFullPageLoader = (visit: any) => {
            // only show on full-page GET navigations
            return visit?.method?.toLowerCase() === 'get';
        };

        const startNav = router.on('start', (event: any) => {
            if (!shouldShowFullPageLoader(event.detail.visit)) {
                return;
            }

            setIsNavigating(true);
            setProgress(10);
            progressTimer = setInterval(() => {
                setProgress((p) => (p < 85 ? p + Math.random() * 8 : p));
            }, 200);
        });

        const finishNav = router.on('finish', (event: any) => {
            if (!shouldShowFullPageLoader(event.detail.visit)) {
                return;
            }

            clearInterval(progressTimer);
            setProgress(100);
            setTimeout(() => {
                setIsNavigating(false);
                setProgress(0);
            }, 350);
        });

        return () => {
            startNav();
            finishNav();
            clearInterval(progressTimer);
        };
    }, []);

    useEffect(() => {
        const msg = flash?.success || flash?.error || flash?.warning || flash?.info;
        if (!msg) return;

        const type: 'success' | 'error' | 'warning' | 'info' =
            flash?.success ? 'success' :
            flash?.error ? 'error' :
            flash?.warning ? 'warning' : 'info';

        setNotification({ message: msg, type });
        const timer = setTimeout(() => setNotification(null), 5000);
        return () => clearTimeout(timer);
    }, [flash?.success, flash?.error, flash?.warning, flash?.info]);

    const user = auth?.user;
    const isLoggedIn = !!user;

    const isActive = (routeName: string) => {
        const componentMap: { [key: string]: string[] } = {
            'home': ['Home'],
            'marketplace': ['Marketplace'],
            'categories': ['Categories'],
            'brands': ['BrandsPage'],
            'brand.help': ['BrandHelp'],
        };
        
        const componentsForRoute = componentMap[routeName] || [];
        return componentsForRoute.includes(component as string);
    };

    const navLinkClass = (routeName: string, mobile: boolean = false) => {
        const active = isActive(routeName);
        if (mobile) {
            return `text-sm font-medium ${active ? 'text-white font-bold' : 'text-white/70'}`;
        }
        return active
            ? 'text-white font-bold border-b-2 border-brand-orange py-1'
            : 'text-white/70 hover:text-white font-normal transition-colors';
    };

    return (
        <div className="bg-brand-parchment text-brand-ink min-h-screen flex flex-col font-sans">
            {/* FULLSCREEN LOADING OVERLAY */}
            {isNavigating && (
                <div className="fixed inset-0 z-[70] bg-brand-forest/10 backdrop-blur-sm flex items-center justify-center">
                    <div className="flex flex-col items-center gap-4 bg-brand-forest/95 p-10 rounded-3xl">
                        <div className="relative">
                            <svg className="h-12 w-12 animate-spin text-brand-orange" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            <div className="absolute inset-0 rounded-full border-2 border-brand-orange/30 animate-pulse"></div>
                        </div>
                        <div className="text-center">
                            <p className="text-white font-semibold text-lg">Loading...</p>
                            <div className="mt-2 w-48 h-1 bg-white/20 rounded-full overflow-hidden">
                                <div
                                    className="h-full bg-brand-orange transition-all duration-300 ease-out rounded-full"
                                    style={{ width: `${progress}%` }}
                                />
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* NOTIFICATIONS */}
            {notification && (
                <div className="fixed top-20 right-4 z-50 animate-in fade-in slide-in-from-right-4 duration-300">
                    <div className={`rounded-2xl px-6 py-4 shadow-2xl border flex items-center gap-3 backdrop-blur-md ${
                        notification.type === 'success' ? 'bg-brand-forest/90 border-brand-forest text-white' :
                        notification.type === 'error' ? 'bg-red-600/90 border-red-700 text-white' :
                        notification.type === 'warning' ? 'bg-brand-orange/90 border-brand-orange text-white' :
                        'bg-blue-600/90 border-blue-700 text-white'
                    }`}>
                        {notification.type === 'success' && (
                            <svg xmlns="http://www.w3.org/2001/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="w-5 h-5">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        )}
                        <span className="text-sm font-bold">{notification.message}</span>
                        <button onClick={() => setNotification(null)} className="ml-2 hover:opacity-70 transition-opacity">
                            <span className="text-lg">×</span>
                        </button>
                    </div>
                </div>
            )}

            <header className="border-b border-white/10 bg-brand-forest sticky top-0 z-40 shadow-lg">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex h-16 items-center justify-between gap-3">
                        {/* LEFT: Logo */}
                        <div className="flex items-center">
                            <Link href={route('home')} className="flex items-center">
                                <img src={`${new URL(route('home')).pathname}/Assets/LocalTrade/Text logo dark bg.png`.replace('//', '/')}
                                    alt="LocalTrade Logo" className="w-auto h-8 md:h-12 object-contain" />
                                {/* <span className="font-bold tracking-tight text-xl text-white">LocalTrade</span> */}
                            </Link>
                        </div>

                        {/* MIDDLE: Desktop Nav */}
                        <div className="flex items-center gap-2 sm:gap-3">
                            <nav className="hidden md:flex items-center gap-6 text-sm">
                                <Link href={route('home')} className={navLinkClass('home')}>Home</Link>
                                <Link href={route('marketplace')} className={navLinkClass('marketplace')}>Marketplace</Link>
                                <Link href={route('categories')} className={navLinkClass('categories')}>Categories</Link>
                                <Link href={route('brands')} className={navLinkClass('brands')}>Brands</Link>
                                <Link href={route('brand.help')} className={navLinkClass('brand.help')}>Help</Link>
                            </nav>
                        </div>

                        {/* RIGHT: Cart + Auth */}
                        <div className="flex items-center gap-3 sm:gap-4">
                            <Link href={route('cart.index')} className="relative flex items-center justify-center w-10 h-10 rounded-full border border-white/10 hover:border-brand-orange transition-all group">
                                <span className="text-sm group-hover:scale-110 text-white transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" className="size-6">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                    </svg>
                                </span>
                            </Link>

                            {isLoggedIn ? (
                                <Link href={user.type === 'brand' ? route('brand.dashboard') : route('dashboard')} className="hidden sm:inline-flex items-center gap-2 px-1 py-1 rounded-full border border-white/10 hover:border-white transition-colors">
                                    <div className="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center overflow-hidden">
                                        <svg xmlns="http://www.w3.org/2000/svg" className="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.607 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                </Link>
                            ) : (
                                <>
                                    <Link href={route('login')} className="hidden sm:inline-flex items-center px-4 py-2 rounded-full text-xs font-bold text-white/80 hover:text-white transition-colors">Login</Link>
                                    <Link href={route('register')} className="bg-brand-orange hidden sm:inline-flex items-center px-5 py-2 rounded-full text-xs font-bold text-dark shadow-md shadow-brand-orange/20 transition-all hover:scale-[1.02]">Sign up</Link>
                                </>
                            )}

                            <button onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)} className="flex md:hidden items-center justify-center w-10 h-10 rounded-full border border-white/10 text-white">
                                <span className="block text-lg">{isMobileMenuOpen ? '✕' : '☰'}</span>
                            </button>
                        </div>
                    </div>

                    {isMobileMenuOpen && (
                        <div className="md:hidden border-t border-white/5 mt-2 py-4 space-y-4">
                            <nav className="flex flex-col gap-3 px-2">
                                <Link href={route('home')} className={navLinkClass('home', true)}>Home</Link>
                                <Link href={route('marketplace')} className={navLinkClass('marketplace', true)}>Marketplace</Link>
                                <Link href={route('categories')} className={navLinkClass('categories', true)}>Categories</Link>
                                <Link href={route('brands')} className={navLinkClass('brands', true)}>Brands</Link>
                                <Link href={route('brand.help')} className={navLinkClass('brand.help', true)}>Help / Support</Link>
                            </nav>
                            <div className="px-2 pt-2 border-t border-white/10">
                                {isLoggedIn ? (
                                    user.type === 'brand' ? (
                                        <Link
                                            href={route('brand.dashboard')}
                                            className="inline-flex w-full items-center justify-center rounded-full border border-white/20 px-4 py-2 text-sm font-bold text-white transition-colors hover:border-white"
                                        >
                                            Brand Dashboard
                                        </Link>
                                    ) : (
                                        <Link
                                            href={route('dashboard')}
                                            className="inline-flex w-full items-center justify-center rounded-full border border-white/20 px-4 py-2 text-sm font-bold text-white transition-colors hover:border-white"
                                        >
                                            My Profile
                                        </Link>
                                    )
                                ) : (
                                    <div className="flex items-center gap-2">
                                        <Link href={route('login')} className="inline-flex flex-1 items-center justify-center rounded-full border border-white/20 px-4 py-2 text-sm font-bold text-white/90 transition-colors hover:text-white">
                                            Login
                                        </Link>
                                        <Link href={route('register')} className="inline-flex flex-1 items-center justify-center rounded-full bg-brand-orange px-4 py-2 text-sm font-bold text-dark transition-all hover:scale-[1.02]">
                                            Sign up
                                        </Link>
                                    </div>
                                )}
                            </div>
                        </div>
                        
                    )}
                </div>
            </header>

            <main className="flex-1">
                {children}
            </main>

            <footer className="border-t border-brand-forest/10 bg-brand-cream/30 mt-12 py-8">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-brand-ink/50 flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
                    <p>© {new Date().getFullYear()} LocalTrade. All rights reserved.</p>
                    <div className="flex gap-6 font-medium">
                        <Link href={route('home')} className="hover:text-brand-orange transition-colors">Privacy Policy</Link>
                        <Link href={route('home')} className="hover:text-brand-orange transition-colors">Terms of Service</Link>
                        <Link href={route('brand.help')} className="hover:text-brand-orange transition-colors">Help Center</Link>
                    </div>
                </div>
            </footer>
        </div>
    );
}
