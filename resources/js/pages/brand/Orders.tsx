import BrandLayout from '@/layouts/BrandLayout';
import { Order, OrdersProps } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';

export default function Orders({ orders, statusCounts, filters }: OrdersProps) {
    const [search, setSearch] = useState(filters.q || '');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(route('brand.orders'), { status: filters.status, q: search }, { preserveState: true });
    };

    const statusOptions = {
        all: 'All',
        processing: 'Processing',
        paid: 'Paid',
        shipped: 'Shipped',
        delivered: 'Delivered',
        cancelled: 'Cancelled',
    };

    return (
        <BrandLayout>
            <Head title="Orders | LocalTrade" />
            
            <div className="space-y-6 sm:space-y-7">
                <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <h1 className="text-2xl font-bold text-brand-forest">Orders</h1>
                        <p className="text-xs text-brand-ink/50 mt-1">Manage and track your storefront sales.</p>
                    </div>
                    <div className="flex gap-4">
                        <div className="bg-green-50 border border-brand-forest/5 px-4 py-2 rounded-2xl shadow-sm">
                            <span className="text-[10px] uppercase font-bold text-brand-ink/40 block">Total Orders</span>
                            <span className="text-lg font-bold text-brand-forest">{statusCounts.all}</span>
                        </div>
                    </div>
                </div>

                {/* Filters */}
                <section className="bg-white border border-brand-forest/10 rounded-2xl p-5 shadow-sm">
                    <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div className="flex flex-wrap gap-2">
                            {Object.entries(statusOptions).map(([key, label]) => (
                                <Link 
                                    key={key} 
                                    href={route('brand.orders')} 
                                    data={{ status: key, q: filters.q }}
                                    className={`px-4 py-2 rounded-full text-xs font-bold transition-all border ${
                                        (filters.status || 'all') === key 
                                            ? 'bg-brand-orange/10 border-brand-orange text-brand-orange' 
                                            : 'bg-brand-parchment border-brand-forest/5 text-brand-ink/60 hover:border-brand-orange/50'
                                    }`}
                                >
                                    {label}
                                    <span className="ml-2 text-[10px] opacity-40">{statusCounts[key]}</span>
                                </Link>
                            ))}
                        </div>

                        <form onSubmit={handleSearch} className="flex gap-2 w-full md:w-auto md:min-w-[300px]">
                            <input 
                                type="text" 
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Order ID or Customer Name" 
                                className="flex-1 bg-brand-white border border-brand-forest/10 rounded-full px-4 py-2 text-xs focus:ring-1 focus:ring-brand-orange outline-none"
                            />
                            <button type="submit" className="bg-brand-orange text-white px-5 py-2 rounded-full text-xs font-bold shadow-md shadow-brand-orange/10">Search</button>
                        </form>
                    </div>
                </section>

                {/* Orders Table */}
                <section className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm border-separate border-spacing-y-3">
                            <thead>
                                <tr className="text-[10px] uppercase tracking-widest text-brand-ink/40 text-left">
                                    <th className="px-4 pb-2 font-bold">Order #</th>
                                    <th className="px-4 pb-2 font-bold">Customer</th>
                                    <th className="px-4 pb-2 font-bold">Items</th>
                                    <th className="px-4 pb-2 font-bold">Total</th>
                                    <th className="px-4 pb-2 font-bold">Status</th>
                                    <th className="px-4 pb-2 font-bold text-right">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                {orders.data.map((order: Order) => (
                                    <tr key={order.id} className="bg-white/50 hover:bg-white transition-colors group">
                                        <td className="px-4 py-4 first:rounded-l-2xl border-y border-brand-forest/5 border-l">
                                            <Link href={route('brand.orders.show', { id: order.id })} className="font-bold text-brand-forest hover:text-brand-orange transition-colors">
                                                {order.order_number?.substring(0, 8)}
                                            </Link>
                                            <p className="text-[10px] text-brand-ink/40 mt-1 uppercase tracking-tighter">ID: #{order.id}</p>
                                        </td>
                                        <td className="px-4 py-4 border-y border-brand-forest/5">
                                            <div className="flex flex-col">
                                                <span className="font-bold text-brand-forest">{order.customer_name}</span>
                                                <span className="text-[10px] text-brand-ink/40">{order.customer_email}</span>
                                            </div>
                                        </td>
                                        <td className="px-4 py-4 border-y border-brand-forest/5 text-brand-ink/60">
                                            {order.items?.length || 0} items
                                        </td>
                                        <td className="px-4 py-4 border-y border-brand-forest/5 font-bold text-brand-forest">
                                            ₦{Number(order.total).toLocaleString()}
                                        </td>
                                        <td className="px-4 py-4 border-y border-brand-forest/5">
                                            <span className={`inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                                                order.status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 
                                                order.status === 'processing' ? 'bg-amber-100 text-amber-700' :
                                                order.status === 'shipped' ? 'bg-blue-100 text-blue-700' :
                                                'bg-brand-parchment text-brand-ink/40'
                                            }`}>
                                                {order.status}
                                            </span>
                                        </td>
                                        <td className="px-4 py-4 last:rounded-r-2xl border-y border-brand-forest/5 border-r text-right">
                                            <p className="text-xs font-bold text-brand-ink/50">{new Date(order.created_at).toLocaleDateString()}</p>
                                            <p className="text-[9px] text-brand-ink/30 uppercase">{new Date(order.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</p>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                        {orders.data.length === 0 && (
                            <div className="text-center py-20 text-brand-ink/30 italic">No orders found.</div>
                        )}
                    </div>
                </section>
            </div>
        </BrandLayout>
    );
}
