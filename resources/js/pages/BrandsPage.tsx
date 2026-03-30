import { Head, Link } from '@inertiajs/react';
import Layout from '@/layouts/Layout';
import { useState, useMemo } from 'react';

const GRADIENTS = [
    ['from-brand-forest/20', 'to-brand-forest/5'],
    ['from-brand-orange/20', 'to-brand-orange/5'],
    ['from-brand-forest/10', 'to-brand-orange/10'],
    ['from-brand-forest/40', 'to-brand-forest/10'],
    ['from-[#D4C4A8]', 'to-brand-parchment'],
    ['from-[#8B9F8B]', 'to-brand-parchment'],
    ['from-[#C2D1D1]', 'to-brand-parchment'],
    ['from-brand-orange/15', 'to-brand-forest/15'],
];

export default function BrandsPage({ brands }: any) {
    const [searchQuery, setSearchQuery] = useState('');
    const [activeCategory, setActiveCategory] = useState('All');

    const categories = useMemo(() => {
        const cats = new Set<string>();
        brands.data?.forEach((b: any) => {
            if (b.category) cats.add(b.category);
        });
        return ['All', ...Array.from(cats)].sort();
    }, [brands.data]);

    const filteredBrands = useMemo(() => {
        return (brands.data || []).filter((b: any) => {
            const matchesSearch = b.brand_name?.toLowerCase().includes(searchQuery.toLowerCase()) || 
                                  b.location?.toLowerCase().includes(searchQuery.toLowerCase());
            const matchesCategory = activeCategory === 'All' || b.category === activeCategory;
            return matchesSearch && matchesCategory;
        });
    }, [brands.data, searchQuery, activeCategory]);

    return (
        <Layout>
            <Head title="Brands | LocalTrade" />

            <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
                <section className="mb-6 sm:mb-8">
                    <h1 className="text-xl sm:text-2xl font-semibold mb-1 text-brand-forest">All brands</h1>
                    <p className="text-xs sm:text-sm text-brand-ink/50 max-w-2xl">
                        Browse verified Nigerian brands selling on LocalTrade. Click a brand to view its storefront and products.
                    </p>
                </section>

                <section className="mb-5 sm:mb-7">
                    <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div className="w-full md:w-80">
                            <div className="bg-white border border-brand-forest/10 rounded-md px-3 py-2 flex items-center gap-2">
                                <span className="text-brand-ink/50 text-sm">🔍</span>
                                <input 
                                    type="text" 
                                    placeholder="Search brands..." 
                                    value={searchQuery}
                                    onChange={e => setSearchQuery(e.target.value)}
                                    className="flex-1 bg-transparent border-0 text-xs sm:text-sm text-brand-ink placeholder-brand-ink/50 focus:outline-none focus:ring-0" 
                                />
                            </div>
                        </div>

                        <div className="flex items-center gap-2 text-xs">
                            <span className="text-brand-ink/50">Filter by category:</span>
                            <select 
                                value={activeCategory}
                                onChange={e => setActiveCategory(e.target.value)}
                                className="bg-white border border-brand-forest/10 rounded-md px-3 py-2 text-xs focus:outline-none text-brand-ink"
                            >
                                {categories.map(opt => (
                                    <option key={opt} value={opt}>{opt}</option>
                                ))}
                            </select>
                        </div>

                        <p className="text-xs text-brand-ink/40">{filteredBrands.length} brands available</p>
                    </div>
                </section>

                <section>
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                        {filteredBrands.map((b: any) => {
                            const grad = GRADIENTS[b.id % GRADIENTS.length];
                            return (
                                <Link key={b.id} href={route('store', { slug: b.slug })} className="bg-green-50 border border-brand-forest/5 hover:border-brand-orange/50 rounded-2xl p-4 sm:p-5 flex flex-col gap-3 transition">
                                    <div className="flex items-start justify-between gap-3">
                                        <div>
                                            <h2 className="text-sm sm:text-base font-semibold text-brand-forest">{b.brand_name}</h2>
                                            <p className="text-[11px] sm:text-xs text-brand-ink/40">📍 {b.location || 'Nigeria'}</p>
                                            <p className="mt-1 text-[11px] sm:text-xs text-brand-ink/40">Since {new Date(b.created_at).getFullYear()}</p>
                                        </div>
                                        <div className="text-right text-[11px] sm:text-xs text-brand-ink/50">
                                            <p className="mb-1 text-brand-orange">⭐ {Number(b.rating || 0).toFixed(1)}</p>
                                            {/* Products count would be via a relation if we want to show it */}
                                        </div>
                                    </div>

                                    <div className={`mt-2 aspect-[5/2] rounded-xl bg-gradient-to-r ${grad[0]} ${grad[1]} flex items-center justify-center text-[11px] text-center px-3 text-brand-forest/80 italic`}>
                                        {b.bio || `Trusted seller on LocalTrade offering quality ${b.category?.toLowerCase() || 'goods'}.`}
                                    </div>

                                    {b.category && (
                                        <div className="flex flex-wrap gap-1.5 mt-2">
                                            <span className="text-[10px] px-2 py-0.5 rounded-full bg-brand-parchment text-brand-ink/50 border border-brand-forest/5">
                                                {b.category}
                                            </span>
                                        </div>
                                    )}

                                    <div className="mt-3 flex items-center justify-between text-[11px] text-brand-orange font-bold">
                                        <span>Visit store</span>
                                        <span>→</span>
                                    </div>
                                </Link>
                            );
                        })}
                    </div>
                    {filteredBrands.length === 0 && (
                        <div className="py-20 text-center text-gray-400">No brands found.</div>
                    )}
                </section>
            </div>
        </Layout>
    );
}
