import { Head, Link, router } from '@inertiajs/react';
import BrandLayout from '@/layouts/BrandLayout';
import { useState } from 'react';

export default function OrderDetails({ order }: any) {
    const [processing, setProcessing] = useState(false);

    const updateStatus = (status: string) => {
        setProcessing(true);
        router.post(route('brand.orders.status', { id: order.id }), { status }, {
            onFinish: () => setProcessing(false)
        });
    };

    const timelineSteps = [
        { label: 'Order placed', status: 'pending' },
        { label: 'Payment confirmed', status: 'paid' },
        { label: 'Processing', status: 'processing' },
        { label: 'Shipped', status: 'shipped' },
        { label: 'Delivered', status: 'delivered' },
    ];

    const currentStatusIndex = timelineSteps.findIndex(s => s.status === order.status);

    return (
        <BrandLayout>
            <Head title={`Order ${order.order_number?.substring(0, 8)} | LocalTrade`} />
            
            <div className="space-y-6 sm:space-y-7">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div className="flex items-center gap-2 text-[10px] font-bold text-brand-ink/40 uppercase tracking-widest mb-2">
                            <Link href={route('brand.orders')} className="hover:text-brand-orange">Orders</Link>
                            <span>/</span>
                            <span>{order.order_number?.substring(0, 8)}</span>
                        </div>
                        <h1 className="text-2xl font-bold text-brand-forest">Order {order.order_number?.substring(0, 8)}</h1>
                        <p className="text-xs text-brand-ink/50 mt-1">Placed on {new Date(order.created_at).toLocaleString()}</p>
                    </div>
                    <div className="flex items-center gap-4">
                         <span className={`px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider ${
                            order.status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 
                            order.status === 'processing' ? 'bg-amber-100 text-amber-700' :
                            'bg-brand-parchment text-brand-ink/40'
                        }`}>
                            {order.status}
                        </span>
                        <div className="bg-green-50 border border-brand-forest/5 px-6 py-2 rounded-2xl">
                            <span className="text-[10px] font-bold text-brand-ink/40 uppercase block">Total</span>
                            <span className="text-lg font-bold text-brand-orange">₦{Number(order.total).toLocaleString()}</span>
                        </div>
                    </div>
                </div>

                <div className="grid lg:grid-cols-3 gap-6">
                    {/* Main Content */}
                    <div className="lg:col-span-2 space-y-6">
                        {/* Timeline */}
                        <section className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 sm:p-8 shadow-sm">
                            <h2 className="text-sm font-bold text-brand-forest uppercase tracking-widest mb-8">Order Progress</h2>
                            <div className="relative flex justify-between items-start max-w-xl mx-auto">
                                <div className="absolute top-[14px] left-[10%] right-[10%] h-0.5 bg-brand-forest/5 -z-0">
                                    <div 
                                        className="h-full bg-brand-orange transition-all duration-500" 
                                        style={{ width: `${(currentStatusIndex / (timelineSteps.length - 1)) * 100}%` }}
                                    ></div>
                                </div>
                                {timelineSteps.map((step, i) => (
                                    <div key={step.status} className="flex flex-col items-center gap-3 relative z-10 w-20">
                                        <div className={`w-8 h-8 rounded-full border-4 flex items-center justify-center text-[10px] font-bold transition-all ${
                                            i <= currentStatusIndex 
                                                ? 'bg-brand-orange border-brand-parchment text-white shadow-lg shadow-brand-orange/20' 
                                                : 'bg-white border-brand-forest/5 text-brand-ink/20'
                                        }`}>
                                            {i + 1}
                                        </div>
                                        <span className={`text-[10px] font-bold text-center leading-tight uppercase tracking-tighter ${
                                            i <= currentStatusIndex ? 'text-brand-forest' : 'text-brand-ink/20'
                                        }`}>
                                            {step.label}
                                        </span>
                                    </div>
                                ))}
                            </div>
                        </section>

                        {/* Items */}
                        <section className="bg-white border border-brand-forest/10 rounded-3xl p-6 sm:p-8 shadow-sm">
                            <h2 className="text-sm font-bold text-brand-forest uppercase tracking-widest mb-6">Items</h2>
                            <div className="space-y-4">
                                {order.items.map((item: any) => (
                                    <div key={item.id} className="flex items-center justify-between p-4 bg-green-50 rounded-2xl border border-brand-forest/5 group hover:bg-white transition-all">
                                        <div className="flex items-center gap-4">
                                            <div className="w-12 h-12 rounded-xl bg-white border border-brand-forest/5 flex items-center justify-center font-bold text-brand-forest text-xs">
                                                {item.product_name?.substring(0, 1)}
                                            </div>
                                            <div>
                                                <p className="text-sm font-bold text-brand-forest">{item.product_name}</p>
                                                <p className="text-[10px] text-brand-ink/40 uppercase tracking-widest">Qty: {item.quantity} · ₦{Number(item.unit_price).toLocaleString()} each</p>
                                            </div>
                                        </div>
                                        <p className="text-sm font-bold text-brand-orange">₦{Number(item.subtotal).toLocaleString()}</p>
                                    </div>
                                ))}
                            </div>
                        </section>
                    </div>

                    {/* Sidebar */}
                    <aside className="space-y-6">
                         {/* Customer Info */}
                         <section className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 shadow-sm space-y-4">
                            <h2 className="text-[10px] font-bold text-brand-ink/40 uppercase tracking-widest">Customer Information</h2>
                            <div className="space-y-3">
                                <div>
                                    <p className="text-sm font-bold text-brand-forest">{order.customer_name}</p>
                                    <p className="text-xs text-brand-ink/50">{order.customer_email}</p>
                                </div>
                                <div className="p-3 bg-white/50 rounded-xl border border-brand-forest/5">
                                    <p className="text-[10px] font-bold text-brand-ink/30 uppercase mb-1">Shipping Address</p>
                                    <p className="text-[11px] text-brand-ink/70 leading-relaxed">
                                        {order.address?.address_line1}<br />
                                        {order.address?.city}, {order.address?.state}<br />
                                        {order.address?.country}
                                    </p>
                                </div>
                            </div>
                        </section>

                        {/* Order Management */}
                        <section className="bg-white border border-brand-forest/10 rounded-3xl p-6 shadow-sm space-y-4">
                            <h2 className="text-[10px] font-bold text-brand-ink/40 uppercase tracking-widest">Manage Order</h2>
                            <div className="space-y-3">
                                <label className="block text-[10px] font-bold text-brand-ink/50 uppercase">Order Status</label>
                                <select 
                                    className="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2.5 text-xs font-bold focus:ring-1 focus:ring-brand-orange outline-none"
                                    value={order.status}
                                    onChange={(e) => updateStatus(e.target.value)}
                                    disabled={processing}
                                >
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <div className="grid grid-cols-2 gap-2 mt-4">
                                    <button 
                                        onClick={() => updateStatus('shipped')} 
                                        disabled={processing || order.status === 'shipped'}
                                        className="bg-blue-600 text-white py-2.5 rounded-xl text-[10px] font-bold uppercase transition-all hover:bg-blue-700 disabled:opacity-50"
                                    >
                                        Ship Order
                                    </button>
                                    <button 
                                        onClick={() => updateStatus('delivered')} 
                                        disabled={processing || order.status === 'delivered'}
                                        className="bg-emerald-600 text-white py-2.5 rounded-xl text-[10px] font-bold uppercase transition-all hover:bg-emerald-700 disabled:opacity-50"
                                    >
                                        Deliver
                                    </button>
                                </div>
                                <p className="text-[9px] text-brand-ink/30 italic mt-2">Updating status notifies the customer.</p>
                            </div>
                        </section>
                    </aside>
                </div>
            </div>
        </BrandLayout>
    );
}
