import { Head, Link, useForm } from '@inertiajs/react';
import Layout from '@/layouts/Layout';
import { useState, useEffect } from 'react';

export default function Home({ featuredProducts, totalProducts, categories, featuredBrands }: any) {
    const [searchQuery, setSearchQuery] = useState('');
    const { get } = useForm();
    
    // Using a simpler search submit for now instead of the live ajax dropdown to ensure reliability
    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        if (searchQuery.trim()) {
            get(route('marketplace', { q: searchQuery }));
        }
    };

    return (
        <Layout>
            <Head title="LocalTrade – Buy Local. Sell Global." />

            {/* HERO SECTION */}
            <section className="py-10 sm:py-14">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-10 items-center">
                    {/* Hero copy */}
                    <div>
                        <p className="text-xs font-semibold tracking-[0.25em] uppercase text-brand-orange mb-3">
                            Nigeria · Marketplace
                        </p>
                        <h1 className="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4 text-brand-forest">
                            Buy from real Nigerian brands.<br className="hidden sm:block" />
                            <span className="text-brand-orange">Support local. Shop global.</span>
                        </h1>
                        <p className="text-sm sm:text-base text-brand-ink/70 mb-6">
                            LocalTrade connects you with authentic Nigerian sellers—from fashion and beauty
                            to tech and home essentials. Discover trusted brands, secure payments, and fast delivery.
                        </p>

                        {/* Search bar */}
                        <form onSubmit={handleSearch} className="bg-white border border-brand-forest/50 rounded-full p-1.5 flex items-center mb-4 relative shadow-sm">
                            <input
                                type="text"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                placeholder="Search for products, brands, or categories..."
                                className="flex-1 bg-transparent border-0 text-sm text-brand-ink placeholder-brand-ink/40 px-3 py-2 focus:outline-none focus:ring-0"
                            />
                            <button
                                type="submit"
                                className="px-4 py-2 rounded-full text-sm font-semibold text-white shadow-md shadow-brand-orange/20"
                                style={{ backgroundColor: 'var(--lt-orange)' }}
                            >
                                Search
                            </button>
                        </form>

                        {/* Stats / badges */}
                        <div className="flex flex-wrap gap-4 text-xs text-brand-ink/60">
                            <div className="flex items-center gap-2">
                                <span className="w-2 h-2 rounded-full bg-brand-forest"></span>
                                <span>Verified Nigerian brands</span>
                            </div>
                            <div className="flex items-center gap-2">
                                <span className="w-2 h-2 rounded-full bg-brand-orange"></span>
                                <span>Secure escrow payments</span>
                            </div>
                            <div className="flex items-center gap-2">
                                <span className="w-2 h-2 rounded-full bg-brand-cream border border-brand-forest/10"></span>
                                <span>Nationwide delivery</span>
                            </div>
                        </div>
                    </div>

                    <div className="lg:justify-self-end w-full max-w-100">
                        <div className="bg-brand-forest border border-white/10 rounded-3xl p-5 sm:p-6 shadow-2xl shadow-brand-forest/30">
                            <p className="text-xs text-brand-orange mb-4 font-bold uppercase tracking-[0.2em]">Trending this week</p>
                            <div className="grid grid-cols-2 gap-3 text-xs">
                                {featuredProducts && featuredProducts.length > 0 ? (
                                    featuredProducts.map((product: any) => (
                                        <Link key={product.id} href={route('product.show', { id: product.id })} className="bg-green-50 rounded-2xl p-3 flex flex-col gap-2 hover:scale-[1.02] transition-all group shadow-sm">
                                            <div className="aspect-[4/3] rounded-xl bg-brand-forest/5 flex items-center justify-center text-[10px] font-semibold text-brand-forest/30 overflow-hidden">
                                                {product.main_image ? (
                                                    <img src={product.main_image} alt={product.name} className="w-full h-full object-cover" />
                                                ) : (
                                                    product.brand?.brand_name
                                                )}
                                            </div>
                                            <div className="flex-1">
                                                <p className="font-semibold text-sm text-brand-forest truncate">{product.name}</p>
                                                <p className="text-[11px] text-brand-ink/60 truncate">{product.category}</p>
                                            </div>
                                            <div className="flex items-center justify-between mt-1">
                                                <p className="font-bold text-sm text-brand-forest">₦{Number(product.price).toLocaleString()}</p>
                                                <div className="text-[10px] px-2 py-1 rounded-full bg-brand-orange text-white font-medium shadow-sm shadow-brand-orange/20">
                                                    View
                                                </div>
                                            </div>
                                        </Link>
                                    ))
                                ) : (
                                    <p className="col-span-full text-center text-gray-400">No products available.</p>
                                )}
                            </div>
                            <p className="mt-4 text-[11px] text-white/40 text-center">
                                Over <span className="text-brand-orange font-bold">{(totalProducts || 0).toLocaleString()}+</span> products from verified Nigerian brands.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* CATEGORIES SECTION */}
            <section className="py-6 sm:py-8 border-y border-brand-forest/5 bg-brand-cream/20">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between mb-4">
                        <h2 className="text-lg sm:text-xl font-bold text-brand-forest">Shop by category</h2>
                        <Link href={route('categories')} className="text-xs text-brand-orange hover:underline uppercase tracking-wider font-semibold">View all</Link>
                    </div>

                    <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4 text-xs">
                        {categories && categories.length > 0 ? (
                            categories.map((category: string, index: number) => (
                                <Link key={index} href={route('marketplace', { category })} className="bg-green-50 hover:bg-brand-parchment border border-brand-forest/5 rounded-2xl p-3 flex flex-col items-start gap-1 text-left transition-all shadow-sm hover:shadow-md">
                                    <span className="text-sm font-semibold text-brand-forest">{category}</span>
                                    <span className="text-[11px] text-brand-ink/40">Explore products</span>
                                </Link>
                            ))
                        ) : (
                            <p className="col-span-full text-center text-gray-400">No categories available.</p>
                        )}
                    </div>
                </div>
            </section>

            {/* AVAILABLE PRODUCTS SECTION */}
            <section className="py-8 sm:py-10">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between mb-4">
                        <h2 className="text-lg sm:text-xl font-bold text-brand-forest">Available Products</h2>
                        <Link href={route('marketplace')} className="text-xs text-brand-orange hover:underline uppercase tracking-wider font-semibold">View all</Link>
                    </div>

                    <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 text-xs">
                        {featuredProducts && featuredProducts.length > 0 ? (
                            featuredProducts.map((product: any) => (
                                <Link key={`grid-${product.id}`} href={route('product.show', { id: product.id })} className="bg-green-50 border border-brand-forest/5 hover:border-brand-orange/30 rounded-2xl p-3 sm:p-4 flex flex-col gap-2 transition-all shadow-sm hover:shadow-lg group">
                                    <div className="aspect-[4/3] rounded-xl bg-gradient-to-br from-brand-forest/5 to-brand-orange/5 flex items-center justify-center text-[11px] font-semibold text-brand-forest/30 overflow-hidden">
                                        {product.main_image ? (
                                            <img src={product.main_image} alt={product.name} className="w-full h-full object-cover" />
                                        ) : (
                                            product.brand?.brand_name
                                        )}
                                    </div>
                                    <p className="text-sm font-semibold line-clamp-2 text-brand-forest">{product.name}</p>
                                    <p className="text-[11px] text-brand-ink/40">{product.category}</p>
                                    <p className="text-sm font-bold text-brand-forest">₦{Number(product.price).toLocaleString()}</p>
                                    <button className="mt-auto text-[11px] px-3 py-1.5 rounded-full bg-brand-orange text-white font-medium hover:bg-brand-orange/90 transition-all shadow-sm shadow-brand-orange/10">
                                        View product
                                    </button>
                                </Link>
                            ))
                        ) : (
                            <p className="col-span-full text-center text-gray-400">No products available.</p>
                        )}
                    </div>

                    <p className="mt-4 text-[11px] text-brand-ink/40 text-center">
                        Showing {featuredProducts?.length || 0} of many products · <Link href={route('marketplace')} className="text-brand-forest hover:underline font-semibold">Explore more</Link>
                    </p>
                </div>
            </section>

            {/* FEATURED BRANDS */}
            <section className="py-8 sm:py-10">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between mb-4">
                        <h2 className="text-lg sm:text-xl font-bold text-brand-forest">Featured Nigerian brands</h2>
                        <Link href={route('brands')} className="text-xs text-brand-orange hover:underline uppercase tracking-wider font-semibold">See all brands</Link>
                    </div>

                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                        {featuredBrands && featuredBrands.length > 0 ? (
                            featuredBrands.map((brand: any) => (
                                <Link key={brand.id} href={route('store', { slug: brand.slug })} className="bg-green-50 rounded-2xl p-4 border border-brand-forest/5 flex flex-col gap-2 hover:border-brand-forest/20 transition-all shadow-sm">
                                    {brand.logo ? (
                                        <img src={brand.logo} alt={brand.brand_name} className="w-12 h-12 rounded-lg object-cover mb-2 ring-1 ring-brand-forest/5" />
                                    ) : (
                                        <div className="w-12 h-12 rounded-lg bg-brand-parchment flex items-center justify-center font-bold text-brand-forest ring-1 ring-brand-forest/5 mb-2">
                                            {brand.brand_name.substring(0, 2).toUpperCase()}
                                        </div>
                                    )}
                                    <p className="text-sm font-semibold text-brand-forest">{brand.brand_name}</p>
                                    <p className="text-[11px] text-brand-ink/40">{brand.category}</p>
                                    <span className="mt-auto inline-flex items-center gap-1 text-[11px] text-brand-orange font-semibold">
                                        View store →
                                    </span>
                                </Link>
                            ))
                        ) : (
                            <p className="col-span-full text-center text-gray-400">No brands available.</p>
                        )}
                    </div>
                </div>
            </section>

            {/* SELLER CTA SECTION */}
            <section className="py-10 sm:py-12 bg-brand-forest text-white">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <p className="text-xs uppercase tracking-[0.2em] text-brand-orange mb-2 font-bold">
                        For Nigerian brands
                    </p>
                    <h2 className="text-2xl sm:text-3xl font-semibold mb-3">
                        Sell on LocalTrade and reach customers across Nigeria.
                    </h2>
                    <p className="text-sm sm:text-base text-white/90 mb-6">
                        Whether you’re a solo creator or a growing brand, LocalTrade gives you
                        secure payments, logistics partners, and tools to grow your business.
                    </p>
                    <div className="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <Link href={route('register', { type: 'brand' })} className="px-8 py-3 rounded-full text-sm font-bold bg-brand-orange text-white hover:opacity-90 transition-all shadow-lg shadow-brand-orange/20">
                            Start selling
                        </Link>
                        <Link href={route('brand.help')}>
                            <button className="px-8 py-3 rounded-full text-sm border border-white/30 hover:bg-white/10 transition-colors font-medium">
                                Learn how it works
                            </button>
                        </Link>
                    </div>
                </div>
            </section>
        </Layout>
    );
}
