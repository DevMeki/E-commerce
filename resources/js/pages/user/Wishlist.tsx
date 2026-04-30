import Layout from '@/layouts/Layout';
import { WishlistItem, WishlistProps } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Tags } from 'lucide-react';

export default function Wishlist({ wishlist }: WishlistProps) {
    return (
        <Layout>
            <Head title="Saved Items | LocalTrade" />
            
            <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="mb-8 flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-brand-forest">Saved Items</h1>
                        <span className="block h-1 w-12 bg-brand-orange mt-2 rounded-full"></span>
                    </div>
                    <Link href={route('dashboard')} className="text-sm text-brand-ink/40 hover:text-brand-orange transition-colors">
                        &larr; Back to Account
                    </Link>
                </div>

                <div className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 sm:p-8 shadow-sm">
                    {wishlist && wishlist.length > 0 ? (
                        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            {wishlist.map((item: WishlistItem) => (
                                <Link 
                                    key={item.id} 
                                    href={route('product.show', { id: item.product_id })}
                                    className="flex flex-col gap-3 p-4 rounded-2xl bg-white border border-brand-forest/5 transition-all group shadow-sm hover:shadow-md"
                                >
                                    <div className="w-full aspect-square rounded-xl bg-brand-parchment overflow-hidden relative transition-all">
                                        {item.product?.main_image ? (
                                            <img src={item.product.main_image} alt={item.product.name} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                        ) : (
                                            <div className="w-full h-full flex items-center justify-center text-brand-forest/10 font-bold uppercase tracking-widest text-[10px]">Product</div>
                                        )}
                                        <div className="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button className="bg-white/90 backdrop-blur-sm p-2 rounded-full shadow-sm text-red-500 hover:scale-110 transition-transform">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" className="w-4 h-4"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <p className="text-sm font-bold text-brand-forest truncate group-hover:text-brand-orange transition-colors">
                                            {item.product?.name}
                                        </p>
                                        <p className="text-[10px] text-brand-ink/40 uppercase tracking-widest mt-1">
                                            By {item.product?.brand?.brand_name}
                                        </p>
                                        <p className="text-sm font-bold text-brand-orange mt-2">
                                            ₦{Number(item.product?.price).toLocaleString()}
                                        </p>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    ) : (
                        <div className="text-center py-16">
                            <div className="w-20 h-20 bg-brand-forest/5 rounded-full flex items-center justify-center mx-auto mb-6">
                                <Tags className="h-10 w-10 text-brand-forest/70" />
                            </div>
                            <h2 className="text-lg font-bold text-brand-forest mb-2">Your wishlist is empty</h2>
                            <p className="text-brand-ink/40 text-sm mb-8">Save items you like to see them here later.</p>
                            <Link href={route('marketplace')} className="inline-flex px-8 py-3 bg-brand-orange text-white rounded-full font-bold shadow-lg shadow-brand-orange/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                                Browse Products
                            </Link>
                        </div>
                    )}
                </div>
            </div>
        </Layout>
    );
}
