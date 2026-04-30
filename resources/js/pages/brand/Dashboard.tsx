import BrandLayout from '@/layouts/BrandLayout';
import { BrandDashboardProps, Order } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { MapPin } from 'lucide-react';

export default function Dashboard({ brand, stats, recentOrders }: BrandDashboardProps) {
    return (
        <BrandLayout>
            <Head title="Brand Dashboard | LocalTrade" />
            
            <div className="space-y-6 sm:space-y-8">
                {/* Brand Summary */}
                <section className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8">
                        <div className="flex items-center gap-5">
                            <div className="w-16 h-16 rounded-2xl bg-white border border-brand-forest/5 flex items-center justify-center text-xl font-bold text-brand-forest shadow-sm">
                                {brand.brand_name?.substring(0, 2).toUpperCase()}
                            </div>
                            <div>
                                <p className="text-[10px] uppercase tracking-[0.25em] text-brand-orange font-bold mb-1">Brand Dashboard</p>
                                <h1 className="text-2xl font-bold text-brand-forest">{brand.brand_name}</h1>
                                <div className="text-xs text-brand-ink/50 mt-1 inline-flex items-center gap-1.5">
                                    <MapPin className="h-3.5 w-3.5" />
                                    <span>{brand.location} · Since {new Date(brand.created_at).getFullYear()}</span>
                                </div>
                            </div>
                        </div>
                        <Link 
                            href={route('brand.products.create')} 
                            className="bg-brand-orange text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-brand-orange/20 hover:scale-[1.02] active:scale-[0.98] transition-all text-center"
                        >
                            + Add New Product
                        </Link>
                    </div>

                    <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <div className="bg-white/50 rounded-2xl p-4 border border-brand-forest/5">
                            <p className="text-[10px] uppercase font-bold text-brand-ink/40 mb-1">Today's Revenue</p>
                            <p className="text-lg font-bold text-brand-orange">₦{Number(stats.revenue_today).toLocaleString()}</p>
                        </div>
                        <div className="bg-white/50 rounded-2xl p-4 border border-brand-forest/5">
                            <p className="text-[10px] uppercase font-bold text-brand-ink/40 mb-1">Revenue (30d)</p>
                            <p className="text-lg font-bold text-brand-forest">₦{Number(stats.revenue_30d).toLocaleString()}</p>
                        </div>
                        <div className="bg-white/50 rounded-2xl p-4 border border-brand-forest/5">
                            <p className="text-[10px] uppercase font-bold text-brand-ink/40 mb-1">Orders (30d)</p>
                            <p className="text-lg font-bold text-brand-forest">{stats.orders_30d}</p>
                        </div>
                        <div className="bg-white/50 rounded-2xl p-4 border border-brand-forest/5">
                            <p className="text-[10px] uppercase font-bold text-brand-ink/40 mb-1">Products Live</p>
                            <p className="text-lg font-bold text-brand-forest">{stats.products_live}</p>
                        </div>
                    </div>
                </section>

                {/* Recent Orders Table */}
                <section className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <div className="flex items-center justify-between mb-6">
                        <div>
                            <h2 className="text-lg font-bold text-brand-forest">Recent Orders</h2>
                            <p className="text-xs text-brand-ink/40">Latest sales from your store</p>
                        </div>
                        <Link href={route('brand.orders')} className="text-xs font-bold text-brand-orange hover:underline">View All Orders</Link>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="w-full text-sm border-separate border-spacing-y-3">
                            <thead>
                                <tr className="text-[10px] uppercase tracking-widest text-brand-ink/40 text-left">
                                    <th className="px-4 py-2 font-bold">Order #</th>
                                    <th className="px-4 py-2 font-bold">Customer</th>
                                    <th className="px-4 py-2 font-bold">Items</th>
                                    <th className="px-4 py-2 font-bold">Total</th>
                                    <th className="px-4 py-2 font-bold">Status</th>
                                    <th className="px-4 py-2 font-bold text-right">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                {recentOrders.map((order: Order) => (
                                    <tr key={order.id} className="bg-white/50 hover:bg-white transition-colors group">
                                        <td className="px-4 py-4 first:rounded-l-2xl border-y border-brand-forest/5 border-l font-bold text-brand-forest">
                                            {order.order_number?.substring(0, 8)}
                                        </td>
                                        <td className="px-4 py-4 border-y border-brand-forest/5 text-brand-ink/70">
                                            {order.customer_name}
                                        </td>
                                        <td className="px-4 py-4 border-y border-brand-forest/5 text-brand-ink/60 max-w-[200px] truncate">
                                            {order.items?.[0]?.product_name || 'Multiple items...'}
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
                                        <td className="px-4 py-4 last:rounded-r-2xl border-y border-brand-forest/5 border-r text-right text-xs text-brand-ink/40">
                                            {new Date(order.created_at).toLocaleDateString()}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                        {(!recentOrders || recentOrders.length === 0) && (
                            <div className="text-center py-12 text-brand-ink/30 italic text-sm">No orders yet. Your sales will appear here.</div>
                        )}
                    </div>
                </section>
            </div>
        </BrandLayout>
    );
}
