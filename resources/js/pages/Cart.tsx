import { Head, Link, router } from '@inertiajs/react';
import Layout from '@/layouts/Layout';

export default function Cart({ cartItems, subtotal, total, deliveryEstimate }: any) {

    const updateQuantity = (cartId: number, currentQty: number, type: 'inc' | 'dec') => {
        let newQty = currentQty;
        if (type === 'inc') newQty++;
        else if (type === 'dec' && currentQty > 1) newQty--;
        else return;

        router.post(route('cart.update'), { cart_id: cartId, quantity: newQty }, { preserveScroll: true });
    };

    const removeItem = (cartId: number) => {
        if (confirm("Are you sure you want to remove this item?")) {
            router.post(route('cart.remove'), { cart_id: cartId }, { preserveScroll: true });
        }
    };

    return (
        <Layout>
            <Head title="Your Cart | LocalTrade" />

            <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="mb-8">
                    <h1 className="text-3xl font-bold text-brand-forest">Your Shopping Cart</h1>
                    <span className="block h-1 w-12 bg-brand-orange mt-2 rounded-full"></span>
                </div>

                {!cartItems || cartItems.length === 0 ? (
                    <div className="bg-green-50 border border-brand-forest/5 rounded-3xl p-12 text-center shadow-sm">
                        <div className="w-20 h-20 bg-brand-forest/5 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span className="text-4xl text-brand-forest">🛒</span>
                        </div>
                        <h2 className="text-xl font-bold text-brand-forest mb-2">Your cart is feeling light</h2>
                        <p className="text-brand-ink/50 mb-8">It seems you haven't added anything to your cart yet.</p>
                        <Link href={route('marketplace')} className="bg-brand-orange inline-flex px-8 py-3 bg-brand-orange text-white rounded-full font-bold shadow-lg shadow-brand-orange/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Start Exploring
                        </Link>
                    </div>
                ) : (
                    <div className="grid lg:grid-cols-[minmax(0,2fr)_minmax(280px,1fr)] gap-6 lg:gap-10">
                        {/* CART ITEMS */}
                        <section className="space-y-4">
                            {cartItems.map((item: any) => (
                                <div key={item.id} className="cart-item bg-green-50 border border-brand-forest/5 rounded-3xl p-4 sm:p-5 flex gap-4 sm:gap-6 shadow-sm hover:shadow-md transition-shadow">
                                    <div className="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-brand-parchment border border-brand-forest/5 flex items-center justify-center text-[10px] font-bold text-brand-forest/20 uppercase tracking-widest text-center overflow-hidden shrink-0">
                                        {item.main_image ? (
                                            <img src={item.main_image} className="w-full h-full object-cover" alt={item.name} />
                                        ) : (
                                            <span>Local brand</span>
                                        )}
                                    </div>

                                    <div className="flex-1 flex flex-col justify-between">
                                        <div className="flex items-start justify-between gap-4">
                                            <div>
                                                <Link href={route('product.show', { id: item.product_id })} className="hover:underline">
                                                    <p className="text-base sm:text-lg font-bold text-brand-forest leading-tight">
                                                        {item.name}
                                                    </p>
                                                </Link>
                                                <p className="text-xs font-medium text-brand-ink/40 mt-1 uppercase tracking-wider">
                                                    by <span className="text-brand-forest">{item.seller}</span>
                                                </p>
                                                {item.variant && (
                                                    <p className="text-[11px] font-medium text-brand-forest/60 bg-brand-forest/5 px-2 py-0.5 rounded mt-2 inline-block">
                                                        {item.variant}
                                                    </p>
                                                )}
                                            </div>
                                            <button 
                                                onClick={() => removeItem(item.id)}
                                                className="remove-item text-[10px] font-bold text-red-400 hover:text-red-500 uppercase tracking-widest bg-red-50 px-3 py-1.5 rounded-full transition-colors shrink-0"
                                            >
                                                Remove
                                            </button>
                                        </div>

                                        <div className="mt-3 flex flex-wrap items-center justify-between gap-3">
                                            <div className="flex items-center gap-2 text-xs">
                                                <span className="text-brand-ink/50">Qty</span>
                                                <div className="flex items-center border border-brand-forest/10 rounded-full overflow-hidden">
                                                    <button onClick={() => updateQuantity(item.id, item.qty, 'dec')} className="w-7 h-7 flex items-center justify-center text-lg text-brand-ink/50 hover:bg-brand-forest/5">-</button>
                                                    <input type="number" value={item.qty} readOnly className="qty-input w-10 text-center text-xs bg-transparent border-0 text-brand-ink focus:outline-none focus:ring-0" />
                                                    <button onClick={() => updateQuantity(item.id, item.qty, 'inc')} className="w-7 h-7 flex items-center justify-center text-lg text-brand-ink/50 hover:bg-brand-forest/5">+</button>
                                                </div>
                                            </div>

                                            <div className="text-right text-sm sm:text-base">
                                                <p className="font-bold text-brand-forest line-total">
                                                    ₦{Number(item.price * item.qty).toLocaleString()}
                                                </p>
                                                <p className="text-[11px] text-brand-ink/50">
                                                    ₦{Number(item.price).toLocaleString()} each
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </section>

                        {/* ORDER SUMMARY */}
                        <aside className="bg-brand-forest rounded-3xl p-6 sm:p-8 text-white h-max shadow-xl sticky top-24">
                            <h2 className="text-lg font-bold mb-6 uppercase tracking-wider">Order summary</h2>
                            <dl className="space-y-4 text-sm mb-8">
                                <div className="flex justify-between items-baseline">
                                    <dt className="text-white/60 font-medium">Subtotal</dt>
                                    <dd className="font-bold">₦{Number(subtotal).toLocaleString()}</dd>
                                </div>
                                <div className="flex justify-between items-baseline">
                                    <dt className="text-white/60 font-medium">Estimated delivery</dt>
                                    <dd className="font-bold">₦{Number(deliveryEstimate).toLocaleString()}</dd>
                                </div>
                                <div className="flex justify-between items-baseline pb-4 border-b border-white/10">
                                    <dt className="text-white/60 font-medium">Promo code</dt>
                                    <dd className="text-[10px] font-bold uppercase tracking-widest text-brand-orange">Review at checkout</dd>
                                </div>
                                <div className="pt-4 flex justify-between items-baseline text-xl font-bold">
                                    <dt>Total</dt>
                                    <dd className="text-brand-orange">₦{Number(total).toLocaleString()}</dd>
                                </div>
                            </dl>

                            <button onClick={() => alert('Checkout not yet implemented.')} className="w-full bg-brand-orange py-4 rounded-full text-sm font-bold bg-brand-orange text-white shadow-lg shadow-brand-orange/30 hover:scale-[1.02] active:scale-[0.98] transition-all">
                                Proceed to checkout
                            </button>

                            <p className="mt-6 text-[11px] text-white/40 leading-relaxed italic text-center">
                                Payments are secured via LocalTrade escrow. <br />You’ll review delivery options at the next step.
                            </p>
                        </aside>
                    </div>
                )}
            </div>
        </Layout>
    );
}
