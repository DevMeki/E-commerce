import { Head, Link, router, useForm, usePage } from '@inertiajs/react';
import Layout from '@/layouts/Layout';
import { ArrowUpRight, FileText, MapPin, Package, Star, Users } from 'lucide-react';
import { useState, useMemo } from 'react';

export default function Store({ store, products, categories, isFollowing }: any) {
    const [activeTab, setActiveTab] = useState('products');
    const [categoryFilter, setCategoryFilter] = useState('All');
    const [sortOrder, setSortOrder] = useState('featured');
    
    const { post, processing } = useForm();
    const { auth } = usePage().props as any;
    const user = auth?.user;

    const [followersCount, setFollowersCount] = useState<number>(store.followers || 0);
    const [following, setFollowing] = useState(isFollowing);

    const handleFollow = () => {
        if (!user || user.type !== 'buyer') {
            router.get(route('login'));
            return;
        }

        // Optimistic UI update
        const newFollowing = !following;
        setFollowing(newFollowing);
        setFollowersCount(prev => newFollowing ? prev + 1 : prev - 1);

        post(route('brand.follow', { brand: store.id }), {
            preserveScroll: true,
            onError: () => {
                // Revert on error
                setFollowing(!newFollowing);
                setFollowersCount(prev => !newFollowing ? prev + 1 : prev - 1);
            }
        });
    };

    const handleShare = () => {
        const url = window.location.href;
        if (navigator.share) {
            navigator.share({
                title: `${store.name} on LocalTrade`,
                url: url
            }).catch(console.error);
        } else {
            navigator.clipboard.writeText(url);
            alert('Store link copied to clipboard!');
        }
    };

    const filteredAndSortedProducts = useMemo(() => {
        let result = [...(products || [])];

        if (categoryFilter !== 'All') {
            result = result.filter(p => p.category === categoryFilter);
        }

        if (sortOrder === 'price-asc') result.sort((a, b) => Number(a.price) - Number(b.price));
        else if (sortOrder === 'price-desc') result.sort((a, b) => Number(b.price) - Number(a.price));

        return result;
    }, [products, categoryFilter, sortOrder]);

    // Mock policies for UI since they aren't fully in the DB model yet or maybe they are
    const policies = {
        Shipping: store.shipping_policy || 'Standard shipping rates apply.',
        Returns: store.return_policy || 'Contact seller for return information.',
        Payments: 'All payments are processed securely via LocalTrade escrow.'
    };

    return (
        <Layout>
            <Head title={`${store.brand_name} – Store | LocalTrade`} />

            <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
                {/* STORE HERO */}
                <section className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 sm:p-8 lg:p-10 mb-8 shadow-sm">
                    <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div className="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                            <div className="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-brand-parchment flex items-center justify-center border border-brand-forest/5 shadow-inner overflow-hidden">
                                {store.logo ? (
                                    <img src={store.logo} alt={store.brand_name} className="w-full h-full object-cover" />
                                ) : (
                                    <span className="text-2xl sm:text-3xl font-bold text-brand-forest">
                                        {store.brand_name.substring(0, 2).toUpperCase()}
                                    </span>
                                )}
                            </div>
                            <div>
                                <h1 className="text-2xl sm:text-3xl font-bold mb-2 text-brand-forest">
                                    {store.brand_name}
                                </h1>
                                <div className="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs font-medium text-brand-ink/60">
                                    <div className="flex items-center gap-1.5">
                                        <Star className="h-4 w-4 text-brand-orange fill-current" />
                                        <span className="text-brand-forest font-bold">{Number(store.rating || 0).toFixed(1)}</span>
                                        <span className="text-brand-ink/20">·</span>
                                        <span>{store.total_reviews || 0} reviews</span>
                                    </div>
                                    <span className="text-brand-ink/20 hidden sm:inline">·</span>
                                    <div className="flex items-center gap-1.5">
                                        <Package className="h-4 w-4 text-brand-forest/40" />
                                        <span>{products?.length || 0} products</span>
                                    </div>
                                    <span className="text-brand-ink/20 hidden sm:inline">·</span>
                                    <div className="flex items-center gap-1.5">
                                        <Users className="h-4 w-4 text-brand-forest/40" />
                                        <span>{followersCount} followers</span>
                                    </div>
                                </div>
                                <p className="mt-4 text-sm sm:text-base text-brand-ink/70 max-w-xl leading-relaxed">
                                    {store.bio || `Welcome to ${store.brand_name}'s store.`}
                                </p>
                                <div className="mt-3 inline-flex items-center gap-2 px-3 py-1.5 bg-brand-forest/5 rounded-full text-[11px] font-bold text-brand-forest">
                                    <MapPin className="h-3.5 w-3.5" /> {store.location || 'Nigeria'} • Since {new Date(store.created_at).getFullYear()}
                                </div>
                            </div>
                        </div>

                        <div className="flex flex-col gap-3 min-w-40">
                            <button
                                onClick={handleFollow}
                                disabled={processing}
                                className={`w-full px-6 py-3 rounded-full font-bold text-sm transition-all shadow-lg ${
                                    following 
                                        ? 'bg-brand-parchment text-brand-forest border border-brand-forest/10' 
                                        : 'bg-brand-orange text-white shadow-brand-orange/20 hover:scale-[1.02]'
                                }`}
                            >
                                {following ? 'Following' : 'Follow Store'}
                            </button>
                            <button onClick={handleShare} className="w-full px-6 py-3 rounded-full border border-brand-forest/10 text-brand-forest font-bold text-sm hover:bg-brand-forest hover:text-white transition-all shadow-sm">
                                <span className="inline-flex items-center gap-1.5"><ArrowUpRight className="h-4 w-4" /> Share Store</span>
                            </button>
                        </div>
                    </div>
                </section>

                {/* TABS */}
                <section className="mb-8">
                    <div className="border-b border-brand-forest/10 flex text-[11px] font-bold uppercase tracking-widest overflow-x-auto">
                        {['products', 'about', 'policies', 'reviews'].map(tab => (
                            <button
                                key={tab}
                                onClick={() => setActiveTab(tab)}
                                className={`px-6 py-4 border-b-2 transition-colors ${
                                    activeTab === tab 
                                        ? 'border-brand-orange text-brand-forest' 
                                        : 'border-transparent text-brand-ink/40 hover:text-brand-forest'
                                }`}
                            >
                                {tab.charAt(0).toUpperCase() + tab.slice(1)}
                            </button>
                        ))}
                    </div>

                    {/* TAB CONTENT: PRODUCTS */}
                    {activeTab === 'products' && (
                        <div className="pt-8">
                            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-8">
                                <div className="flex items-center gap-4 text-[11px]">
                                    <label className="font-bold text-brand-ink/40 uppercase tracking-widest">Category</label>
                                    <select
                                        value={categoryFilter}
                                        onChange={(e) => setCategoryFilter(e.target.value)}
                                        className="bg-white border border-brand-forest/10 rounded-full px-4 py-2 text-xs font-bold text-brand-forest focus:outline-none focus:border-brand-orange transition-all cursor-pointer"
                                    >
                                        {categories?.map((cat: string) => (
                                            <option key={cat} value={cat}>{cat}</option>
                                        ))}
                                    </select>
                                </div>
                                <div className="flex items-center gap-4 text-[11px]">
                                    <label className="font-bold text-brand-ink/40 uppercase tracking-widest">Sort by</label>
                                    <select
                                        value={sortOrder}
                                        onChange={(e) => setSortOrder(e.target.value)}
                                        className="bg-white border border-brand-forest/10 rounded-full px-4 py-2 text-xs font-bold text-brand-forest focus:outline-none focus:border-brand-orange transition-all cursor-pointer"
                                    >
                                        <option value="featured">Featured</option>
                                        <option value="price-asc">Price: Low to High</option>
                                        <option value="price-desc">Price: High to Low</option>
                                    </select>
                                </div>
                            </div>

                            <div className="grid grid-cols-2 lg:grid-cols-4 gap-6 text-xs">
                                {filteredAndSortedProducts.length > 0 ? (
                                    filteredAndSortedProducts.map((p: any) => (
                                        <Link key={p.id} href={route('product.show', { id: p.id })} className="bg-green-50 border border-brand-forest/5 hover:border-brand-orange/30 rounded-2xl p-4 flex flex-col gap-3 transition-all shadow-sm hover:shadow-xl group">
                                            <div className="aspect-[4/3] rounded-xl bg-brand-parchment flex items-center justify-center border border-brand-forest/5 overflow-hidden">
                                                {p.main_image ? (
                                                    <img src={p.main_image} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                                ) : (
                                                    <span className="text-brand-forest/20 font-bold uppercase tracking-widest text-[10px]">{store.brand_name}</span>
                                                )}
                                            </div>
                                            <p className="text-sm font-bold text-brand-forest line-clamp-2">{p.name}</p>
                                            <p className="text-[11px] text-brand-ink/50">{p.category}</p>
                                            <p className="text-sm font-bold text-brand-forest pb-1 border-b border-brand-forest/5">₦{Number(p.price).toLocaleString()}</p>
                                            <button className="mt-auto text-[11px] px-3 py-1.5 rounded-full bg-brand-orange text-white font-bold transition-all shadow-sm shadow-brand-orange/10 group-hover:scale-105">
                                                View Product
                                            </button>
                                        </Link>
                                    ))
                                ) : (
                                    <p className="col-span-full text-center text-gray-400">No products match your filters.</p>
                                )}
                            </div>
                        </div>
                    )}

                    {/* TAB CONTENT: ABOUT */}
                    {activeTab === 'about' && (
                        <div className="pt-8 text-sm text-brand-ink/80 leading-relaxed">
                            <div className="bg-green-50 border border-brand-forest/5 rounded-3xl p-8 shadow-sm">
                                <h3 className="text-lg font-bold text-brand-forest mb-4">Our Story</h3>
                                <p className="mb-6 whitespace-pre-line">{store.bio || `Welcome to ${store.brand_name}'s store.`}</p>
                                <div className="p-4 bg-brand-parchment rounded-2xl inline-flex flex-col gap-1 border border-brand-forest/5">
                                    <span className="text-[10px] uppercase font-bold text-brand-ink/40">Registered Location</span>
                                    <span className="text-sm font-bold text-brand-forest">{store.location || 'Nigeria'}</span>
                                    <span className="text-xs text-brand-ink/40">Selling on LocalTrade since {new Date(store.created_at).getFullYear()}</span>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* TAB CONTENT: POLICIES */}
                    {activeTab === 'policies' && (
                        <div className="pt-8">
                            <div className="grid md:grid-cols-3 gap-6">
                                {Object.entries(policies).map(([label, policy]) => (
                                    <div key={label} className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 shadow-sm">
                                        <div className="w-10 h-10 bg-brand-forest/5 rounded-full flex items-center justify-center mb-4">
                                            <FileText className="h-5 w-5 text-brand-forest" />
                                        </div>
                                        <dt className="font-bold text-brand-forest mb-2 uppercase tracking-wide text-xs">{label}</dt>
                                        <dd className="text-brand-ink/60 text-sm leading-relaxed">{policy}</dd>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* TAB CONTENT: REVIEWS */}
                    {activeTab === 'reviews' && (
                        <div className="pt-8 text-center py-12">
                            <div className="inline-flex flex-col items-center">
                                <div className="mb-6 rounded-full bg-brand-forest/5 p-4"><Star className="h-8 w-8 text-brand-orange fill-current" /></div>
                                <h3 className="text-xl font-bold text-brand-forest mb-4">Trusted Presence</h3>
                                <p className="text-brand-ink/50 max-w-sm mb-6">
                                    {store.brand_name} has maintained an average rating of <span className="font-bold text-brand-forest">{Number(store.rating || 0).toFixed(1)}</span> across <span className="font-bold text-brand-forest">{store.total_reviews || 0}</span> verified purchases.
                                </p>
                                <span className="px-6 py-2 bg-brand-parchment border border-brand-forest/5 rounded-full text-[11px] font-bold text-brand-forest italic">
                                    Detailed buyer reviews are currently being verified for authenticity.
                                </span>
                            </div>
                        </div>
                    )}
                </section>
            </div>
        </Layout>
    );
}
