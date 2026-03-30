import { Head, Link } from '@inertiajs/react';
import Layout from '@/layouts/Layout';

export default function Dashboard({ user }: any) {
    return (
        <Layout>
            <Head title="My Account | LocalTrade" />
            
            <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
                <div className="flex flex-col md:flex-row gap-8 lg:gap-12">
                    
                    {/* SIDEBAR */}
                    <aside className="w-full md:w-64 shrink-0">
                        <div className="bg-green-50 rounded-3xl p-6 border border-brand-forest/5 shadow-sm sticky top-24">
                            <div className="flex items-center gap-4 mb-8">
                                <div className="w-12 h-12 rounded-full bg-brand-forest text-white flex items-center justify-center text-lg font-bold shrink-0">
                                    {user.name?.charAt(0) || user.fullname?.charAt(0) || 'U'}
                                </div>
                                <div>
                                    <p className="text-sm font-bold text-brand-forest line-clamp-1">{user.name || user.fullname}</p>
                                    <p className="text-[10px] text-brand-ink/40 uppercase tracking-widest mt-0.5">Verified Buyer</p>
                                </div>
                            </div>

                            <nav className="flex flex-col gap-2">
                                <Link href={route('dashboard')} className="px-4 py-3 rounded-xl text-sm font-bold bg-brand-forest text-white transition-all shadow-sm">
                                    Dashboard
                                </Link>
                                <Link href={route('purchases')} className="px-4 py-3 rounded-xl text-sm font-bold text-brand-ink/60 hover:text-brand-forest hover:bg-brand-forest/5 transition-all">
                                    My Purchases
                                </Link>
                                <Link href={route('wishlist')} className="px-4 py-3 rounded-xl text-sm font-bold text-brand-ink/60 hover:text-brand-forest hover:bg-brand-forest/5 transition-all">
                                    Saved Items
                                </Link>
                                <Link href={route('settings')} className="px-4 py-3 rounded-xl text-sm font-bold text-brand-ink/60 hover:text-brand-forest hover:bg-brand-forest/5 transition-all">
                                    Settings
                                </Link>
                                <div className="h-px bg-brand-forest/5 my-2"></div>
                                <Link href={route('logout')} method="post" as="button" className="px-4 py-3 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 text-left transition-all w-full">
                                    Log out
                                </Link>
                            </nav>
                        </div>
                    </aside>

                    {/* MAIN CONTENT */}
                    <div className="flex-1">
                        <h1 className="text-2xl font-bold text-brand-forest mb-2">Welcome back!</h1>
                        <p className="text-sm text-brand-ink/60 mb-8">Manage your orders and discover new Nigerian brands.</p>

                        <div className="grid sm:grid-cols-2 gap-4 sm:gap-6 mb-10">
                            <Link href={route('purchases')} className="bg-white border border-brand-forest/5 rounded-3xl p-6 hover:border-brand-orange/30 transition-all shadow-sm group">
                                <div className="w-12 h-12 rounded-xl bg-brand-parchment text-brand-forest flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                                    📦
                                </div>
                                <h3 className="text-base font-bold text-brand-forest mb-1">My Purchases</h3>
                                <p className="text-xs text-brand-ink/50">Track, return, or buy items again</p>
                            </Link>

                            <Link href={route('wishlist')} className="bg-white border border-brand-forest/5 rounded-3xl p-6 hover:border-brand-orange/30 transition-all shadow-sm group">
                                <div className="w-12 h-12 rounded-xl bg-brand-parchment text-brand-forest flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                                    ❤️
                                </div>
                                <h3 className="text-base font-bold text-brand-forest mb-1">Saved Items</h3>
                                <p className="text-xs text-brand-ink/50">View your wishlist</p>
                            </Link>
                        </div>
                        
                        {/* Quick CTA */}
                        <div className="bg-brand-forest rounded-3xl p-6 sm:p-8 text-white text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-6 shadow-xl">
                            <div>
                                <h3 className="text-lg font-bold mb-2">Discover new arrivals</h3>
                                <p className="text-sm text-white/70">Explore the latest products from top-rated Nigerian brands.</p>
                            </div>
                            <Link href={route('marketplace')} className="shrink-0 px-6 py-3 rounded-full text-sm font-bold bg-brand-orange text-white hover:scale-[1.02] shadow-lg shadow-brand-orange/20 transition-all">
                                Shop Now
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
