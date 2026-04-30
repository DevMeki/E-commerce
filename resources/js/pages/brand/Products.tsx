import BrandLayout from '@/layouts/BrandLayout';
import { BrandProductsProps, Product } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { Archive, Pencil, Trash2, Undo2 } from 'lucide-react';
import { useState } from 'react';

export default function Products({ products, stats, filters }: BrandProductsProps) {
    const [search, setSearch] = useState(filters.q || '');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(route('brand.products'), { status: filters.status, q: search }, { preserveState: true });
    };

    const handleAction = async (productId: number, action: string) => {
        let message = `Are you sure you want to ${action} this product?`;
        if (action === 'delete') message = 'Are you sure you want to PERMANENTLY delete this product?';

        if (confirm(message)) {
            router.post(route('brand.products.action'), { product_id: productId, action }, {
                onSuccess: () => {
                    // router.reload handles it
                }
            });
        }
    };

    return (
        <BrandLayout>
            <Head title="Products | LocalTrade Brand" />
            
            <div className="space-y-6 sm:space-y-7">
                {/* Header */}
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-brand-forest">Products</h1>
                        <p className="text-xs text-brand-ink/50 mt-1">Manage your storefront listings.</p>
                    </div>
                    <Link href={route('brand.products.create')} className="bg-brand-orange text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-brand-orange/20 hover:scale-[1.02] active:scale-[0.98] transition-all text-center">
                        + Add Product
                    </Link>
                </div>

                {/* Metrics */}
                <section className="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    {[
                        { label: 'Total Products', value: stats.all, color: 'text-brand-forest' },
                        { label: 'Active Listings', value: stats.active, color: 'text-emerald-600' },
                        { label: 'Drafts', value: stats.draft, color: 'text-amber-600' },
                        { label: 'Private', value: stats.hidden, color: 'text-brand-ink/40' },
                    ].map((stat) => (
                        <div key={stat.label} className="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 shadow-sm">
                            <p className="text-[10px] uppercase font-bold text-brand-ink/40 mb-1">{stat.label}</p>
                            <p className={`text-xl font-bold ${stat.color}`}>{stat.value}</p>
                        </div>
                    ))}
                </section>

                {/* Filters */}
                <section className="bg-white border border-brand-forest/10 rounded-2xl p-5 shadow-sm">
                    <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div className="flex flex-wrap gap-2">
                            {['all', 'active', 'draft', 'archived'].map((status) => (
                                <Link 
                                    key={status} 
                                    href={route('brand.products')} 
                                    data={{ status, q: filters.q }}
                                    className={`px-4 py-2 rounded-full text-xs font-bold transition-all border ${
                                        (filters.status || 'all') === status 
                                            ? 'bg-brand-orange/10 border-brand-orange text-brand-orange' 
                                            : 'bg-brand-parchment border-brand-forest/5 text-brand-ink/60 hover:border-brand-orange/50'
                                    }`}
                                >
                                    {status.charAt(0).toUpperCase() + status.slice(1)}
                                </Link>
                            ))}
                        </div>

                        <form onSubmit={handleSearch} className="flex gap-2 w-full md:w-auto md:min-w-[300px]">
                            <input 
                                type="text" 
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Search products..." 
                                className="flex-1 bg-brand-white border border-brand-forest/10 rounded-full px-4 py-2 text-xs focus:ring-1 focus:ring-brand-orange outline-none"
                            />
                            <button type="submit" className="bg-brand-orange text-white px-5 py-2 rounded-full text-xs font-bold shadow-md shadow-brand-orange/10">Filter</button>
                        </form>
                    </div>
                </section>

                {/* Products Table */}
                <section className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm border-separate border-spacing-y-3">
                            <thead>
                                <tr className="text-[10px] uppercase tracking-widest text-brand-ink/40 text-left">
                                    <th className="px-4 pb-2 font-bold">Product</th>
                                    <th className="px-4 pb-2 font-bold">Category</th>
                                    <th className="px-4 pb-2 font-bold">Price</th>
                                    <th className="px-4 pb-2 font-bold">Stock</th>
                                    <th className="px-4 pb-2 font-bold">Status</th>
                                    <th className="px-4 pb-2 font-bold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {products.data.map((p: Product) => (
                                    <tr key={p.id} className="bg-white/50 hover:bg-white transition-colors group">
                                        <td className="px-4 py-4 first:rounded-l-2xl border-y border-brand-forest/5 border-l">
                                            <div className="flex items-center gap-3">
                                                <div className="w-10 h-10 rounded-lg overflow-hidden bg-brand-parchment border border-brand-forest/5 shrink-0">
                                                    {p.main_image ? (
                                                        <img src={p.main_image} className="w-full h-full object-cover" alt={p.name} />
                                                    ) : (
                                                        <div className="w-full h-full flex items-center justify-center text-[8px] text-brand-ink/30 font-bold uppercase">No Image</div>
                                                    )}
                                                </div>
                                                <div className="flex flex-col min-w-0">
                                                    <Link href={route('brand.products.edit', { id: p.id })} className="font-bold text-brand-forest hover:text-brand-orange transition-colors truncate">
                                                        {p.name}
                                                    </Link>
                                                    <span className="text-[10px] text-brand-ink/40">SKU: {p.sku}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-4 py-4 border-y border-brand-forest/5 text-brand-ink/70">
                                            {p.category}
                                        </td>
                                        <td className="px-4 py-4 border-y border-brand-forest/5 font-bold text-brand-orange">
                                            ₦{Number(p.price).toLocaleString()}
                                        </td>
                                        <td className={`px-4 py-4 border-y border-brand-forest/5 font-bold ${p.stock > 0 ? 'text-brand-forest' : 'text-red-500'}`}>
                                            {p.stock > 0 ? p.stock : 'Out of stock'}
                                        </td>
                                        <td className="px-4 py-4 border-y border-brand-forest/5">
                                            <span className={`inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                                                p.status === 'active' ? 'bg-emerald-100 text-emerald-700' : 
                                                p.status === 'draft' ? 'bg-amber-100 text-amber-700' :
                                                'bg-brand-parchment text-brand-ink/40'
                                            }`}>
                                                {p.status}
                                            </span>
                                        </td>
                                        <td className="px-4 py-4 last:rounded-r-2xl border-y border-brand-forest/5 border-r">
                                            <div className="flex gap-2">
                                                <Link href={route('brand.products.edit', { id: p.id })} className="p-1 text-brand-forest/50 hover:text-brand-forest transition-colors">
                                                    <Pencil className="h-4 w-4" />
                                                </Link>
                                                <button onClick={() => handleAction(p.id, 'delete')} className="p-1 text-brand-forest/50 hover:text-red-500 transition-colors">
                                                    <Trash2 className="h-4 w-4" />
                                                </button>
                                                {p.status === 'archived' ? (
                                                    <button onClick={() => handleAction(p.id, 'unarchive')} className="p-1 text-brand-forest/50 hover:text-emerald-500 transition-colors" title="Unarchive">
                                                        <Undo2 className="h-4 w-4" />
                                                    </button>
                                                ) : (
                                                    <button onClick={() => handleAction(p.id, 'archive')} className="p-1 text-brand-forest/50 hover:text-amber-500 transition-colors" title="Archive">
                                                        <Archive className="h-4 w-4" />
                                                    </button>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                        {products.data.length === 0 && (
                            <div className="text-center py-20 text-brand-ink/30 italic">No products found.</div>
                        )}
                    </div>
                </section>
            </div>
        </BrandLayout>
    );
}
