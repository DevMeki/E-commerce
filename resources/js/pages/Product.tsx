import Layout from '@/layouts/Layout';
import { Product as ProductType, ProductPageProps, SharedData } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { MapPin, Package, Star } from 'lucide-react';
import { useState } from 'react';

export default function Product({ product, seller, images = [], relatedProducts = [], moreBrandProducts = [], variants = {} }: ProductPageProps) {
    const { auth } = usePage<SharedData>().props;
    const user = auth?.user;

    const [mainImage, setMainImage] = useState(images?.[0] || 'https://via.placeholder.com/600x600?text=Product+Image');
    const [activeTab, setActiveTab] = useState('description');

    const [quantity, setQuantity] = useState(1);
    const [selectedVariants, setSelectedVariants] = useState<Record<string, string>>({});

    const [isAddingToCart, setIsAddingToCart] = useState(false);
    const [isBuyNow, setIsBuyNow] = useState(false);
    const [showLoginPrompt, setShowLoginPrompt] = useState(false);

    const handleQuantityChange = (type: 'inc' | 'dec') => {
        if (type === 'inc' && quantity < product.stock) {
            setQuantity((prev) => prev + 1);
        } else if (type === 'dec' && quantity > 1) {
            setQuantity((prev) => prev - 1);
        }
    };

    const handleVariantSelect = (type: string, value: string) => {
        setSelectedVariants((prev) => ({
            ...prev,
            [type]: value,
        }));
    };

    const handleAddToCart = (buyNow: boolean = false) => {
        // Show login prompt for guests instead of hard redirect
        if (!user) {
            setShowLoginPrompt(true);
            return;
        }

        // Block brand accounts from buying
        // if (user.type === 'brand') {
        //     alert('Brand accounts cannot purchase products. Please use a buyer account.');
        //     return;
        // }

        if (buyNow) setIsBuyNow(true);
        else setIsAddingToCart(true);

        router.post(
            route('cart.add'),
            {
                product_id: product.id,
                quantity: quantity,
                variants: JSON.stringify(selectedVariants),
                redirect_to_checkout: buyNow ? 'true' : 'false',
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    setIsAddingToCart(false);
                    setIsBuyNow(false);
                },
                onError: () => {
                    setIsAddingToCart(false);
                    setIsBuyNow(false);
                },
                onFinish: () => {
                    setIsAddingToCart(false);
                    setIsBuyNow(false);
                },
            },
        );
    };

    const discount =
        (product.compare_at_price ?? 0) > product.price 
            ? Math.round(((product.compare_at_price! - product.price) / product.compare_at_price!) * 100) 
            : 0;

    return (
        <Layout>
            <Head title={`${product?.name || 'Product'} | LocalTrade`} />

            <div className="mx-auto max-w-6xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8 overflow-x-hidden">
                <div className="grid gap-10 lg:grid-cols-2">
                    {/* LEFT: Gallery */}
                    <section>
                        <div className="border-brand-forest/5 rounded-3xl border bg-green-50 p-4 shadow-sm sm:p-6">
                            <div className="bg-brand-parchment border-brand-forest/5 mb-4 flex aspect-square items-center justify-center overflow-hidden rounded-2xl border">
                                {product?.main_image ? (
                                    <img src={mainImage} alt={product.name} className="h-full w-full object-cover" />
                                ) : (
                                    <div className="flex flex-col items-center justify-center p-6 text-center opacity-20 grayscale">
                                        <span className="mb-2 text-4xl">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                strokeWidth="1.5"
                                                stroke="currentColor"
                                                className="size-6"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    stroke-linejoin="round"
                                                    d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"
                                                />
                                            </svg>
                                        </span>
                                        <p className="text-brand-forest text-xl font-bold tracking-widest uppercase">
                                            {product?.brand?.brand_name || 'LocalTrade'}
                                        </p>
                                    </div>
                                )}
                            </div>

                            <div className="grid grid-cols-4 gap-3">
                                {images?.map((img: string, idx: number) => (
                                    <button
                                        key={idx}
                                        onClick={() => setMainImage(img)}
                                        className={`overflow-hidden rounded-xl transition-all focus:outline-none ${mainImage === img ? 'border-brand-orange border-2' : 'border-brand-forest/10 hover:border-brand-orange/50 border-2 border-transparent'}`}
                                    >
                                        <img src={img} alt={`Thumbnail ${idx}`} className="aspect-square h-full w-full object-cover" />
                                    </button>
                                ))}
                            </div>
                        </div>
                    </section>

                    {/* RIGHT: Product Info */}
                    <section className="flex flex-col gap-4 sm:gap-5">
                        {/* Title & Rating */}
                        <div>
                            <h1 className="text-brand-forest mb-1 text-2xl font-bold sm:text-3xl">{product.name}</h1>
                            <div className="text-brand-ink/60 flex flex-wrap items-center gap-3 text-xs">
                                <div className="flex items-center gap-1">
                                    <Star className="h-3.5 w-3.5 text-brand-orange fill-current" />
                                    <span className="text-brand-ink font-bold">{Number(product.rating || 0).toFixed(1)}</span>
                                    <span className="text-brand-ink/20">·</span>
                                    <span>{product.total_reviews || 0} reviews</span>
                                </div>
                                <span className="text-brand-ink/20">·</span>
                                <span className={product.stock > 0 ? 'text-brand-forest font-bold' : 'text-red-500'}>
                                    {product.stock > 0 ? `In stock (${product.stock} available)` : 'Out of stock'}
                                </span>
                                {(product.total_sales ?? 0) > 0 && (
                                    <>
                                        <span className="text-brand-ink/20">·</span>
                                        <span>{product.total_sales} sold</span>
                                    </>
                                )}
                            </div>
                        </div>

                        {/* Price */}
                        <div className="flex items-baseline gap-3">
                            <p className="text-brand-forest text-2xl font-bold sm:text-3xl">₦{Number(product.price).toLocaleString()}</p>
                            {discount > 0 && (
                                <>
                                    <p className="text-sm text-gray-400 line-through">₦{Number(product.compare_at_price).toLocaleString()}</p>
                                    <span className="rounded-full bg-red-500/20 px-2 py-1 text-xs font-bold text-red-700">-{discount}%</span>
                                </>
                            )}
                        </div>

                        {/* Short Desc */}
                        <p className="text-brand-ink/80 text-sm leading-relaxed sm:text-base">{product.short_desc}</p>

                        {/* Variants */}
                        {variants && Object.keys(variants).length > 0 && (
                            <div className="border-brand-forest/5 rounded-2xl border bg-green-50 p-4 shadow-sm">
                                <h3 className="text-brand-forest mb-4 text-sm font-bold tracking-wider uppercase">Options</h3>
                                {Object.entries(variants).map(([vType, options]: [string, string[]]) => (
                                    <div key={vType} className="mb-4 last:mb-0">
                                        <p className="text-brand-ink/40 mb-3 text-[11px] font-bold tracking-widest uppercase">Select {vType}</p>
                                        <div className="flex flex-wrap gap-2">
                                            {options.map((opt: string) => (
                                                <button
                                                    key={opt}
                                                    onClick={() => handleVariantSelect(vType, opt)}
                                                    className={`rounded-xl border px-4 py-2 text-xs font-medium transition-all ${
                                                        selectedVariants[vType] === opt
                                                            ? 'border-brand-orange bg-brand-orange/5 text-brand-forest'
                                                            : 'border-brand-forest/10 hover:border-brand-orange text-brand-forest text-brand-forest/80'
                                                    }`}
                                                >
                                                    {opt}
                                                </button>
                                            ))}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}

                        {/* Quantity and Actions */}
                        <div className="border-brand-forest/5 flex flex-col gap-4 rounded-2xl border bg-green-50 p-4 shadow-sm sm:p-5">
                            <div className="flex items-center justify-between gap-3">
                                <div className="flex items-center gap-4">
                                    <span className="text-brand-ink/40 text-[11px] font-bold tracking-widest uppercase">Quantity</span>
                                    <div className="border-brand-forest/10 bg-brand-parchment flex items-center overflow-hidden rounded-full border">
                                        <button
                                            onClick={() => handleQuantityChange('dec')}
                                            className="text-brand-forest hover:bg-brand-forest/5 flex h-10 w-10 items-center justify-center text-lg transition-colors"
                                        >
                                            -
                                        </button>
                                        <input
                                            type="number"
                                            value={quantity}
                                            readOnly
                                            className="text-brand-forest w-12 border-0 bg-transparent text-center text-sm font-bold focus:ring-0 focus:outline-none"
                                        />
                                        <button
                                            onClick={() => handleQuantityChange('inc')}
                                            className="text-brand-forest hover:bg-brand-forest/5 flex h-10 w-10 items-center justify-center text-lg transition-colors"
                                        >
                                            +
                                        </button>
                                    </div>
                                </div>
                                <div className="text-brand-ink/40 text-right text-[11px] font-medium">{product.stock} available</div>
                            </div>

                            <div className="flex flex-col gap-4 sm:flex-row">
                                <button
                                    onClick={() => handleAddToCart(false)}
                                    disabled={product.stock <= 0 || isAddingToCart || isBuyNow}
                                    className="bg-brand-orange shadow-brand-orange/20 flex flex-1 items-center justify-center gap-2 rounded-full px-8 py-4 text-sm font-bold text-white shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    {isAddingToCart ? 'Adding...' : product.stock <= 0 ? 'Out of Stock' : 'Add to Cart'}
                                </button>
                                <button
                                    onClick={() => handleAddToCart(true)}
                                    disabled={product.stock <= 0 || isAddingToCart || isBuyNow}
                                    className="border-brand-forest/10 text-brand-forest hover:bg-brand-forest flex-1 rounded-full border px-8 py-4 text-sm font-bold shadow-sm transition-all hover:text-white disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    {isBuyNow ? 'Processing...' : 'Buy now'}
                                </button>
                            </div>
                        </div>

                        {/* Seller */}
                        {seller && (
                            <div className="border-brand-forest/5 flex flex-wrap items-start justify-between gap-4 rounded-2xl border bg-green-50 p-4 sm:p-5 shadow-sm">
                                <div className="min-w-[200px] flex-1">
                                    <p className="text-brand-ink/40 mb-2 text-[10px] font-bold tracking-[0.2em] uppercase">Verified Brand</p>
                                    <p className="text-brand-forest text-base font-bold">{seller.name}</p>
                                    <p className="text-brand-ink/50 mt-1 text-xs inline-flex items-center gap-1">
                                        <MapPin className="h-3.5 w-3.5" /> {seller.location}
                                    </p>
                                    <div className="text-brand-forest/70 mt-4 flex flex-wrap gap-4 text-xs font-medium">
                                        <div className="flex items-center gap-1.5">
                                            <Star className="h-3.5 w-3.5 text-brand-orange fill-current" />
                                            <span>{Number(seller.rating || 0).toFixed(1)} rating</span>
                                        </div>
                                        <div className="flex items-center gap-1.5">
                                            <Package className="h-3.5 w-3.5 text-brand-forest/40" />
                                            <span>{seller.total_products} products</span>
                                        </div>
                                    </div>
                                </div>
                                {product?.brand_slug && (
                                    <Link
                                        href={route('store', { slug: product.brand_slug })}
                                        className="border-brand-forest/10 text-brand-forest hover:bg-brand-forest rounded-full border px-4 py-2 text-[11px] font-bold whitespace-nowrap shadow-sm transition-all hover:text-white"
                                    >
                                        Visit Store
                                    </Link>
                                )}
                            </div>
                        )}

                        {/* Tabs */}
                        <div className="border-brand-forest/5 overflow-hidden rounded-2xl border bg-green-50 shadow-sm">
                            <div className="border-brand-forest/5 bg-brand-parchment/50 flex overflow-x-auto border-b text-[11px] font-bold tracking-wider uppercase">
                                {['description', 'details', 'shipping', 'reviews'].map((tab) => (
                                    <button
                                        key={tab}
                                        onClick={() => setActiveTab(tab)}
                                        className={`min-w-max flex-1 border-b-2 px-4 sm:px-6 py-4 text-center transition-colors ${
                                            activeTab === tab
                                                ? 'border-brand-orange text-brand-forest'
                                                : 'text-brand-ink/40 hover:text-brand-forest border-transparent'
                                        }`}
                                    >
                                        {tab === 'reviews' ? `Reviews (${product.total_reviews ?? 0})` : tab}
                                    </button>
                                ))}
                            </div>
                            <div className="text-brand-ink/80 p-6 text-sm leading-relaxed">
                                {activeTab === 'description' && <p className="whitespace-pre-line">{product.long_desc || product.short_desc}</p>}
                                {activeTab === 'details' && (
                                    <dl className="space-y-2">
                                        <div className="flex justify-between gap-4">
                                            <dt className="text-brand-ink/60">Category</dt>
                                            <dd className="text-right font-medium">{product.category}</dd>
                                        </div>
                                        <div className="flex justify-between gap-4">
                                            <dt className="text-brand-ink/60">Reference ID</dt>
                                            <dd className="text-right font-medium">{product.id}</dd>
                                        </div>
                                    </dl>
                                )}
                                {activeTab === 'shipping' && (
                                    <dl className="space-y-4 text-sm">
                                        <div className="border-brand-forest/5 flex justify-between gap-4 border-b pb-4">
                                            <dt className="text-brand-ink/40 font-medium">Ships from</dt>
                                            <dd className="text-brand-forest text-right font-bold">{product.ships_from}</dd>
                                        </div>
                                        <div className="border-brand-forest/5 flex justify-between gap-4 border-b pb-4">
                                            <dt className="text-brand-ink/40 font-medium">Processing time</dt>
                                            <dd className="text-brand-forest text-right font-bold">
                                                {product.processing_time || '2-4 business days'}
                                            </dd>
                                        </div>
                                        <div className="flex justify-between gap-4">
                                            <dt className="text-brand-ink/40 font-medium">Shipping fee</dt>
                                            <dd className="text-brand-forest text-right font-bold">
                                                {product.shipping_fee && product.shipping_fee > 0
                                                    ? `₦${Number(product.shipping_fee).toLocaleString()}`
                                                    : 'Free Delivery'}
                                            </dd>
                                        </div>
                                    </dl>
                                )}
                                {activeTab === 'reviews' && (
                                    <div>
                                        {(product.total_reviews ?? 0) > 0 ? (
                                            <>
                                                <div className="mb-4 flex items-center gap-2">
                                                    <span className="text-brand-forest text-2xl font-bold">
                                                        {Number(product.rating || 0).toFixed(1)}
                                                    </span>
                                                    <span className="text-brand-ink/40 text-xs">({product.total_reviews} reviews)</span>
                                                </div>
                                                <p className="text-brand-ink/50 text-xs italic">
                                                    Detailed reviews are being verified for authenticity.
                                                </p>
                                            </>
                                        ) : (
                                            <p className="text-brand-ink/40 italic">No reviews yet. Be the first to share your experience!</p>
                                        )}
                                    </div>
                                )}
                            </div>
                        </div>
                    </section>
                </div>

                {/* Related Products */}
                {relatedProducts && relatedProducts.length > 0 && (
                    <section className="border-brand-forest/10 mt-16 border-t pt-10 sm:mt-20">
                        <div className="mb-8 flex items-center justify-between">
                            <div>
                                <h2 className="text-brand-forest text-lg font-bold sm:text-2xl">You may also like</h2>
                                <span className="bg-brand-orange mt-2 block h-1 w-12 rounded-full"></span>
                            </div>
                            <Link
                                href={route('marketplace', { category: product.category })}
                                className="text-brand-orange text-xs font-bold tracking-wider uppercase hover:underline"
                            >
                                Explore Category
                            </Link>
                        </div>
                        <div className="grid grid-cols-2 gap-4 sm:gap-6 text-xs lg:grid-cols-4">
                            {relatedProducts.map((rp: ProductType) => (
                                <Link
                                    key={rp.id}
                                    href={route('product.show', { id: rp.id })}
                                    className="border-brand-forest/5 group flex flex-col gap-3 rounded-2xl border bg-green-50 p-4 shadow-sm transition-all hover:shadow-xl"
                                >
                                    <div className="bg-brand-parchment border-brand-forest/5 aspect-[4/3] overflow-hidden rounded-xl border">
                                        <img
                                            src={rp.main_image}
                                            alt={rp.name}
                                            className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        />
                                    </div>
                                    <p className="text-brand-forest line-clamp-1 text-sm font-bold">{rp.name}</p>
                                    <p className="text-brand-forest border-brand-forest/5 border-b pb-1 text-sm font-bold">
                                        ₦{Number(rp.price).toLocaleString()}
                                    </p>
                                    <button className="bg-brand-orange shadow-brand-orange/10 mt-auto rounded-full px-3 py-1.5 text-[11px] font-bold text-white shadow-sm transition-all group-hover:scale-105">
                                        View Product
                                    </button>
                                </Link>
                            ))}
                        </div>
                    </section>
                )}

                {/* More from Brand */}
                {moreBrandProducts && moreBrandProducts.length > 0 && (
                    <section className="border-brand-forest/10 mt-16 border-t pt-10 sm:mt-20">
                        <div className="mb-8 flex items-center justify-between">
                            <div>
                                <h2 className="text-brand-forest text-lg font-bold sm:text-2xl">More from {product.brand_name}</h2>
                                <span className="bg-brand-orange mt-2 block h-1 w-12 rounded-full"></span>
                            </div>
                            {product?.brand_slug && (
                                <Link
                                    href={route('store', { slug: product.brand_slug })}
                                    className="text-brand-orange text-xs font-bold tracking-wider uppercase hover:underline"
                                >
                                    Visit Store
                                </Link>
                            )}
                        </div>
                        <div className="grid grid-cols-2 gap-4 sm:gap-6 text-xs lg:grid-cols-4">
                            {moreBrandProducts.map((mp: ProductType) => (
                                <Link
                                    key={mp.id}
                                    href={route('product.show', { id: mp.id })}
                                    className="border-brand-forest/5 group flex flex-col gap-3 rounded-2xl border bg-green-50 p-4 shadow-sm transition-all hover:shadow-xl"
                                >
                                    <div className="bg-brand-parchment border-brand-forest/5 aspect-[4/3] overflow-hidden rounded-xl border">
                                        <img
                                            src={mp.main_image}
                                            alt={mp.name}
                                            className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        />
                                    </div>
                                    <p className="text-brand-forest line-clamp-1 text-sm font-bold">{mp.name}</p>
                                    <p className="text-brand-forest border-brand-forest/5 border-b pb-1 text-sm font-bold">
                                        ₦{Number(mp.price).toLocaleString()}
                                    </p>
                                    <button className="bg-brand-orange shadow-brand-orange/10 mt-auto rounded-full px-3 py-1.5 text-[11px] font-bold text-white shadow-sm transition-all group-hover:scale-105">
                                        View Product
                                    </button>
                                </Link>
                            ))}
                        </div>
                    </section>
                )}
            </div>
            {/* Login Prompt Modal */}
            {showLoginPrompt && (
                <div className="fixed inset-0 z-50 flex items-center justify-center px-4" onClick={() => setShowLoginPrompt(false)}>
                    <div className="absolute inset-0 bg-black/40 backdrop-blur-sm" />
                    <div
                        className="relative w-full max-w-sm rounded-3xl bg-white p-8 shadow-2xl text-center"
                        onClick={(e) => e.stopPropagation()}
                    >
                        <div className="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-brand-forest/5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-8 h-8 text-brand-forest">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <h2 className="text-brand-forest mb-2 text-xl font-bold">Sign in to continue</h2>
                        <p className="text-brand-ink/50 mb-6 text-sm">You need an account to add items to your cart or make a purchase.</p>
                        <div className="flex flex-col gap-3">
                            <Link
                                href={route('login')}
                                className="w-full bg-brand-orange rounded-full py-3 text-sm font-bold text-white shadow-lg shadow-brand-orange/20 transition-all hover:scale-[1.02]">
                                Log in
                            </Link>
                            <Link
                                href={route('register')}
                                className="border-brand-forest/10 text-brand-forest w-full rounded-full border py-3 text-sm font-bold transition-all hover:bg-brand-forest/5"
                            >
                                Create an account
                            </Link>
                            <button
                                onClick={() => setShowLoginPrompt(false)}
                                className="text-brand-ink/40 mt-1 text-xs hover:text-brand-ink transition-colors"
                            >
                                Maybe later
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </Layout>
    );
}
