import { Head, Link } from '@inertiajs/react';
import Layout from '@/layouts/Layout';
import { Tags } from 'lucide-react';

export default function Categories({ categories }: any) {
    const categoryList = Object.keys(categories || {}).sort();

    return (
        <Layout>
            <Head title="Categories | LocalTrade" />
            
            <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
                <section className="mb-6 sm:mb-8">
                    <h1 className="text-xl sm:text-2xl font-semibold mb-1 text-brand-forest">All Categories</h1>
                    <p className="text-xs sm:text-sm text-brand-ink/50 max-w-2xl">
                        Shop by category across thousands of products from verified Nigerian brands.
                    </p>
                </section>

                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    {categoryList.map(cat => (
                        <Link 
                            key={cat} 
                            href={route('marketplace', { category: cat })}
                            className="bg-green-50 border border-brand-forest/5 hover:border-brand-orange/50 rounded-2xl p-5 flex flex-col items-center justify-center gap-3 transition-all shadow-sm hover:shadow-md text-center group"
                        >
                            <div className="w-16 h-16 rounded-full bg-brand-forest/5 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                <Tags className="h-8 w-8 text-brand-forest" />
                            </div>
                            <div>
                                <h2 className="text-base font-bold text-brand-forest group-hover:text-brand-orange transition-colors">{cat}</h2>
                                <p className="text-xs text-brand-ink/50 mt-1">{categories[cat]} products</p>
                            </div>
                        </Link>
                    ))}
                    
                    {categoryList.length === 0 && (
                        <div className="col-span-full py-20 text-center text-gray-400">No categories found.</div>
                    )}
                </div>
            </div>
        </Layout>
    );
}
