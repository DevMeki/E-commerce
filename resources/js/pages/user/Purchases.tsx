import Layout from '@/layouts/Layout';
import { Order, OrderItem, PurchasesProps } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Package } from 'lucide-react';

export default function Purchases({ orders }: PurchasesProps) {

    return (
        <Layout>
            <Head title="Purchase History | LocalTrade" />
            
            <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="mb-8 flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-brand-forest">Purchase History</h1>
                        <span className="block h-1 w-12 bg-brand-orange mt-2 rounded-full"></span>
                    </div>
                    <Link href={route('dashboard')} className="text-sm text-brand-ink/40 hover:text-brand-orange transition-colors">
                        &larr; Back to Account
                    </Link>
                </div>

                <div className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 sm:p-8 shadow-sm">
                    {orders && orders.length > 0 ? (
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            {orders.map((order: Order) => (
                                order.items.map((item: OrderItem) => (
                                    <Link 
                                        key={`${order.id}-${item.id}`} 
                                        href={route('product.show', { id: item.product_id })}
                                        className="flex flex-col gap-3 p-4 rounded-2xl bg-white/50 hover:bg-white border border-brand-forest/5 transition-all group shadow-sm hover:shadow-md"
                                    >
                                        <div className="w-full aspect-square rounded-xl bg-brand-parchment border border-brand-forest/5 overflow-hidden relative">
                                            {item.product?.main_image ? (
                                                <img src={item.product.main_image} alt={item.product.name} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                            ) : (
                                                <div className="w-full h-full flex items-center justify-center text-brand-forest/20 text-xs font-bold uppercase tracking-widest">No Image</div>
                                            )}
                                        </div>
                                        <div>
                                            <p className="text-sm font-bold text-brand-forest truncate group-hover:text-brand-orange transition-colors">
                                                {item.product?.name || 'Deleted Product'}
                                            </p>
                                            <p className="text-[11px] text-brand-ink/40 mt-1">
                                                Purchased on {new Date(order.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                            </p>
                                            <div className="flex items-center justify-between mt-3">
                                                <p className="text-sm font-bold text-brand-orange">
                                                    ₦{Number(item.unit_price).toLocaleString()}
                                                </p>
                                                <span className="text-[10px] font-bold uppercase tracking-wider text-brand-ink/30 bg-brand-forest/5 px-2 py-1 rounded-lg">
                                                    #{order.order_number?.substring(0, 8)}
                                                </span>
                                            </div>
                                        </div>
                                    </Link>
                                ))
                            ))}
                        </div>
                    ) : (
                        <div className="text-center py-16">
                            <div className="w-20 h-20 bg-brand-forest/5 rounded-full flex items-center justify-center mx-auto mb-6">
                                <Package className="h-10 w-10 text-brand-forest/70" />
                            </div>
                            <h2 className="text-lg font-bold text-brand-forest mb-2">No purchase history found</h2>
                            <p className="text-brand-ink/40 text-sm mb-8">Items you buy will appear here.</p>
                            <Link href={route('marketplace')} className="inline-flex px-8 py-3 bg-brand-orange text-white rounded-full font-bold shadow-lg shadow-brand-orange/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                                Start Shopping
                            </Link>
                        </div>
                    )}
                </div>
            </div>
        </Layout>
    );
}
