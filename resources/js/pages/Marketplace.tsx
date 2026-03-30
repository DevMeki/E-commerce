import Layout from '@/layouts/Layout';
import { Head, Link, router } from '@inertiajs/react';
import { useEffect, useState } from 'react';

export default function Marketplace({ products, categories, filters }: any) {
    const [searchQuery, setSearchQuery] = useState(filters.q || '');
    const [activeCategory, setActiveCategory] = useState(filters.category || 'All');
    const [minPrice, setMinPrice] = useState('');
    const [maxPrice, setMaxPrice] = useState('');
    const [sortOrder, setSortOrder] = useState('featured');

    const [localProducts, setLocalProducts] = useState(products.data || []);

    // Apply local filters for price and sort since backend only does search & cat right now
    useEffect(() => {
        let result = [...(products.data || [])];

        if (minPrice) {
            result = result.filter((p) => p.price >= Number(minPrice));
        }
        if (maxPrice) {
            result = result.filter((p) => p.price <= Number(maxPrice));
        }

        if (sortOrder === 'price-asc') {
            result.sort((a, b) => a.price - b.price);
        } else if (sortOrder === 'price-desc') {
            result.sort((a, b) => b.price - a.price);
        }

        setLocalProducts(result);
    }, [products.data, minPrice, maxPrice, sortOrder]);

    const handleApplyFilters = () => {
        router.get(
            route('marketplace'),
            {
                q: searchQuery,
                category: activeCategory !== 'All' ? activeCategory : '',
            },
            { preserveState: true },
        );
    };

    const handleClearFilters = () => {
        setSearchQuery('');
        setActiveCategory('All');
        setMinPrice('');
        setMaxPrice('');
        setSortOrder('featured');
        router.get(route('marketplace'), {}, { preserveState: true });
    };

    const handleCategoryClick = (cat: string) => {
        setActiveCategory(cat);
        router.get(
            route('marketplace'),
            {
                q: searchQuery,
                category: cat !== 'All' ? cat : '',
            },
            { preserveState: true },
        );
    };

    return (
        <Layout>
            <Head title="Marketplace | LocalTrade" />

            <div className="mx-auto max-w-6xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
                <div className="mb-5 flex flex-col gap-4 sm:mb-7 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 className="text-brand-forest mb-1 text-xl font-bold sm:text-2xl">Marketplace</h1>
                        <p className="text-brand-ink/60 text-xs sm:text-sm">
                            Discover products from verified Nigerian brands across fashion, beauty, electronics and more.
                        </p>
                    </div>
                    <div className="w-full md:w-80">
                        <div className="border-brand-forest/10 focus-within:border-brand-orange/30 flex items-center gap-2 rounded-full border bg-white px-4 py-2 shadow-sm transition-all">
                            <span className="text-brand-ink/40 text-sm">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    strokeWidth="1.5"
                                    stroke="currentColor"
                                    className="size-6"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
                                    />
                                </svg>
                            </span>
                            <input
                                type="text"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                onKeyDown={(e) => e.key === 'Enter' && handleApplyFilters()}
                                placeholder="Search products or brands..."
                                className="text-brand-ink placeholder-brand-ink/30 flex-1 border-0 bg-transparent text-xs focus:ring-0 focus:outline-none sm:text-sm"
                            />
                        </div>
                    </div>
                </div>

                <div className="grid gap-6 lg:grid-cols-[260px_1fr]">
                    {/* FILTERS SIDEBAR */}
                    <aside className="border-brand-forest/5 h-max rounded-2xl border bg-white p-4 shadow-sm sm:p-5">
                        <div className="mb-4 flex items-center justify-between">
                            <h2 className="text-brand-forest text-sm font-bold tracking-wider uppercase">Filters</h2>
                            <button onClick={handleClearFilters} className="text-brand-orange text-[11px] font-bold hover:underline">
                                Clear all
                            </button>
                        </div>

                        <div className="space-y-6">
                            <div>
                                <p className="text-brand-ink/40 mb-3 text-[11px] font-bold tracking-widest uppercase">Category</p>
                                <div className="flex flex-wrap gap-2">
                                    {['All', ...(categories || [])].map((cat: string) => (
                                        <button
                                            key={cat}
                                            onClick={() => handleCategoryClick(cat)}
                                            className={`rounded-full border px-3 py-1.5 text-[11px] transition-all ${
                                                activeCategory === cat
                                                    ? 'border-brand-orange bg-brand-orange/5 text-brand-forest font-bold'
                                                    : 'border-brand-forest/10 bg-brand-parchment text-brand-forest hover:border-brand-orange'
                                            }`}
                                        >
                                            {cat}
                                        </button>
                                    ))}
                                </div>
                            </div>

                            <div>
                                <p className="text-brand-ink/40 mb-3 text-[11px] font-bold tracking-widest uppercase">Price range (₦)</p>
                                <div className="flex items-center gap-2">
                                    <input
                                        type="number"
                                        value={minPrice}
                                        onChange={(e) => setMinPrice(e.target.value)}
                                        placeholder="Min"
                                        className="bg-brand-parchment border-brand-forest/10 text-brand-ink focus:border-brand-orange/30 w-full rounded-xl border px-3 py-2 text-xs focus:outline-none"
                                    />
                                    <span className="text-brand-ink/20">–</span>
                                    <input
                                        type="number"
                                        value={maxPrice}
                                        onChange={(e) => setMaxPrice(e.target.value)}
                                        placeholder="Max"
                                        className="bg-brand-parchment border-brand-forest/10 text-brand-ink focus:border-brand-orange/30 w-full rounded-xl border px-3 py-2 text-xs focus:outline-none"
                                    />
                                </div>
                            </div>

                            <div>
                                <p className="text-brand-ink/40 mb-3 text-[11px] font-bold tracking-widest uppercase">Sort by</p>
                                <select
                                    value={sortOrder}
                                    onChange={(e) => setSortOrder(e.target.value)}
                                    className="bg-brand-parchment border-brand-forest/10 text-brand-ink w-full cursor-pointer appearance-none rounded-xl border px-3 py-2 text-xs focus:outline-none"
                                >
                                    <option value="featured">Featured</option>
                                    <option value="price-asc">Price: Low to High</option>
                                    <option value="price-desc">Price: High to Low</option>
                                </select>
                            </div>
                        </div>

                        <button
                            onClick={handleApplyFilters}
                            className="bg-brand-orange shadow-brand-orange/20 mt-8 w-full rounded-full px-4 py-3 text-xs font-bold text-white shadow-lg transition-all hover:scale-[1.02]"
                        >
                            Apply filters
                        </button>
                        <p className="text-brand-ink/40 mt-4 text-center text-[10px] leading-relaxed">
                            Marketplace results verified for <br /> Nigerian authenticity.
                        </p>
                    </aside>

                    {/* PRODUCT GRID */}
                    <section>
                        <div className="mb-3 flex items-center justify-between text-xs text-gray-400">
                            <p>{localProducts.length} products on this page</p>
                            <p>Showing results for {activeCategory === 'All' ? 'all categories' : activeCategory}</p>
                        </div>

                        <div className="grid grid-cols-2 gap-4 text-xs sm:grid-cols-3 lg:grid-cols-4">
                            {localProducts.map((p: any) => (
                                <Link
                                    key={p.id}
                                    href={route('product.show', { id: p.id })}
                                    className="border-brand-forest/5 hover:border-brand-orange/30 group flex flex-col gap-2 rounded-2xl border bg-green-50 p-3 shadow-sm transition-all hover:shadow-lg sm:p-4"
                                >
                                    <div className="bg-brand-parchment border-brand-forest/5 flex aspect-[4/3] items-center justify-center overflow-hidden rounded-xl border px-2 text-center text-[11px]">
                                        {p.main_image ? (
                                            <img
                                                src={p.main_image}
                                                alt={p.name}
                                                className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                            />
                                        ) : (
                                            <span className="text-brand-forest/20 font-bold">{p.brand?.brand_name}</span>
                                        )}
                                    </div>
                                    <p className="text-brand-forest mt-1 line-clamp-2 text-sm font-bold">{p.name}</p>
                                    <p className="text-brand-ink/50 text-[11px]">{p.category}</p>
                                    <p className="text-brand-forest border-brand-forest/5 border-b pb-1 text-sm font-bold">
                                        ₦{Number(p.price).toLocaleString()}
                                    </p>
                                    <button className="bg-brand-orange shadow-brand-orange/20 mt-auto rounded-full px-3 py-1.5 text-[11px] font-bold text-white shadow-sm transition-all hover:scale-105 active:scale-100">
                                        View product
                                    </button>
                                </Link>
                            ))}
                        </div>

                        {/* Pagination links could be added here later using products.links */}
                        {localProducts.length === 0 && (
                            <div className="py-20 text-center text-gray-400">No products found matching your criteria.</div>
                        )}
                    </section>
                </div>
            </div>
        </Layout>
    );
}
