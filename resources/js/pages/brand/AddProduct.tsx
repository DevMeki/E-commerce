import BrandLayout from '@/layouts/BrandLayout';
import { Product } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { useRef, useState } from 'react';

interface AddProductForm {
    [key: string]: string | number | boolean | File | File[] | null | undefined;
    name: string;
    slug: string;
    category: string;
    sku: string;
    price: string | number;
    compare_at_price: string | number;
    stock: string | number;
    status: string;
    visibility: string;
    short_desc: string;
    long_desc: string;
    ships_from: string;
    shipping_fee: string | number;
    processing_time: string;
    variants_text: string;
    main_image_file: File | null;
    gallery: File[];
}

export default function AddProduct({ product }: { product?: Product }) {
    const isEdit = !!product;
    const mainImageRef = useRef<HTMLInputElement>(null);
    const galleryRef = useRef<HTMLInputElement>(null);
    
    const [mainImagePreview, setMainImagePreview] = useState(product?.main_image || null);
    const [galleryPreviews, setGalleryPreviews] = useState<string[]>([]);
 
    const categories = [
        'Fashion', 'Beauty', 'Electronics', 'Home & Living', 'Food & Drinks', 
        'Art & Craft', 'Gadgets', 'Furniture', 'Paintings', 'Sculptures', 
        'Prints', 'Snacks', 'Herbs', 'Spices', 'Kitchen', 'Streetwear', 
        'Skincare', 'Textiles', 'Fashion Accessories', 'Footwear', 
        'Decor', 'Toiletries', 'Cosmetics', 'Education', 'Other'
    ];
 
    const { data, setData, post, processing, errors } = useForm<AddProductForm>({
        name: product?.name || '',
        slug: product?.slug || '',
        category: product?.category || '',
        sku: product?.sku || '',
        price: product?.price || '',
        compare_at_price: product?.compare_at_price || '',
        stock: product?.stock || '',
        status: product?.status || 'active',
        visibility: product?.visibility || 'public',
        short_desc: product?.short_desc || '',
        long_desc: product?.long_desc || '',
        ships_from: product?.ships_from || '',
        shipping_fee: product?.shipping_fee || '',
        processing_time: product?.processing_time || '',
        variants_text: product?.variants_text || '',
        main_image_file: null as File | null,
        gallery: [] as File[],
    });

    const handleMainImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            setData('main_image_file', file);
            setMainImagePreview(URL.createObjectURL(file));
        }
    };

    const handleGalleryChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const files = Array.from(e.target.files || []);
        setData('gallery', [...data.gallery, ...files]);
        setGalleryPreviews([...galleryPreviews, ...files.map(f => URL.createObjectURL(f))]);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (isEdit) {
            post(route('brand.products.update', { id: product.id }));
        } else {
            post(route('brand.products.store'));
        }
    };

    return (
        <BrandLayout>
            <Head title={`${isEdit ? 'Edit' : 'Add'} Product | LocalTrade`} />
            
            <form onSubmit={handleSubmit} className="space-y-8">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-brand-forest">{isEdit ? 'Edit Product' : 'Add New Product'}</h1>
                        <p className="text-xs text-brand-ink/50 mt-1">{isEdit ? 'Update your listing details.' : 'Create a new shop listing.'}</p>
                    </div>
                    <Link href={route('brand.products')} className="text-xs font-bold text-brand-ink/50 hover:text-brand-orange transition-colors">← Back to Products</Link>
                </div>

                <div className="grid lg:grid-cols-3 gap-8">
                    {/* LEFT COLUMN: BASIC INFO */}
                    <div className="lg:col-span-2 space-y-6">
                        <section className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                            <h2 className="text-lg font-bold text-brand-forest mb-2">Basic Information</h2>
                            
                            <div className="space-y-4">
                                <div>
                                    <label className="block text-xs font-bold text-brand-ink/60 uppercase tracking-widest mb-2">Product Name *</label>
                                    <input 
                                        type="text" 
                                        required
                                        value={data.name}
                                        onChange={e => setData('name', e.target.value)}
                                        className="w-full bg-white border border-brand-forest/10 rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-orange outline-none"
                                        placeholder="e.g. Ankara Panel Hoodie"
                                    />
                                    {errors.name && <p className="text-red-500 text-[10px] mt-1 font-bold">{errors.name}</p>}
                                </div>

                                <div className="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label className="block text-xs font-bold text-brand-ink/60 uppercase tracking-widest mb-2">Category *</label>
                                        <select 
                                            required
                                            value={data.category}
                                            onChange={e => setData('category', e.target.value)}
                                            className="w-full bg-white border border-brand-forest/10 rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-orange outline-none"
                                        >
                                            <option value="">Select Category</option>
                                            {categories.map(cat => <option key={cat} value={cat}>{cat}</option>)}
                                        </select>
                                    </div>
                                    <div>
                                        <label className="block text-xs font-bold text-brand-ink/60 uppercase tracking-widest mb-2">SKU (Optional)</label>
                                        <input 
                                            type="text" 
                                            value={data.sku}
                                            onChange={e => setData('sku', e.target.value)}
                                            className="w-full bg-white border border-brand-forest/10 rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-orange outline-none"
                                            placeholder="PROD-12345"
                                        />
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                            <h2 className="text-lg font-bold text-brand-forest mb-2">Pricing & Inventory</h2>
                            <div className="grid sm:grid-cols-3 gap-4">
                                <div>
                                    <label className="block text-xs font-bold text-brand-ink/60 uppercase tracking-widest mb-2">Price (₦) *</label>
                                    <input 
                                        type="number" 
                                        required
                                        value={data.price}
                                        onChange={e => setData('price', e.target.value)}
                                        className="w-full bg-white border border-brand-forest/10 rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-orange outline-none"
                                    />
                                </div>
                                <div>
                                    <label className="block text-xs font-bold text-brand-ink/60 uppercase tracking-widest mb-2">Discount Price</label>
                                    <input 
                                        type="number" 
                                        value={data.compare_at_price}
                                        onChange={e => setData('compare_at_price', e.target.value)}
                                        className="w-full bg-white border border-brand-forest/10 rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-orange outline-none"
                                    />
                                </div>
                                <div>
                                    <label className="block text-xs font-bold text-brand-ink/60 uppercase tracking-widest mb-2">Stock Level *</label>
                                    <input 
                                        type="number" 
                                        required
                                        value={data.stock}
                                        onChange={e => setData('stock', e.target.value)}
                                        className="w-full bg-white border border-brand-forest/10 rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-orange outline-none"
                                    />
                                </div>
                            </div>
                        </section>

                        <section className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                            <h2 className="text-lg font-bold text-brand-forest mb-2">Descriptions</h2>
                            <div className="space-y-4">
                                <div>
                                    <label className="block text-xs font-bold text-brand-ink/60 uppercase tracking-widest mb-2">Short Summary *</label>
                                    <textarea 
                                        required
                                        value={data.short_desc}
                                        onChange={e => setData('short_desc', e.target.value)}
                                        rows={2}
                                        className="w-full bg-white border border-brand-forest/10 rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-orange outline-none resize-none"
                                        placeholder="A brief punchy line about the product..."
                                    />
                                </div>
                                <div>
                                    <label className="block text-xs font-bold text-brand-ink/60 uppercase tracking-widest mb-2">Details</label>
                                    <textarea 
                                        value={data.long_desc}
                                        onChange={e => setData('long_desc', e.target.value)}
                                        rows={5}
                                        className="w-full bg-white border border-brand-forest/10 rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-orange outline-none"
                                        placeholder="Materials, sizing info, care instructions..."
                                    />
                                </div>
                            </div>
                        </section>
                    </div>

                    {/* RIGHT COLUMN: IMAGES & STATUS */}
                    <div className="space-y-6">
                        <section className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 shadow-sm">
                            <h2 className="text-sm font-bold text-brand-forest mb-4 uppercase tracking-widest">Visibility</h2>
                            <div className="space-y-4">
                                <div>
                                    <label className="block text-[10px] font-bold text-brand-ink/40 uppercase mb-2">Status</label>
                                    <select 
                                        value={data.status}
                                        onChange={e => setData('status', e.target.value)}
                                        className="w-full bg-white border border-brand-forest/10 rounded-xl px-3 py-2 text-xs font-bold outline-none"
                                    >
                                        <option value="active">Active (Visible)</option>
                                        <option value="draft">Draft (Hidden)</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-[10px] font-bold text-brand-ink/40 uppercase mb-2">Marketplace Visibility</label>
                                    <select 
                                        value={data.visibility}
                                        onChange={e => setData('visibility', e.target.value)}
                                        className="w-full bg-white border border-brand-forest/10 rounded-xl px-3 py-2 text-xs font-bold outline-none"
                                    >
                                        <option value="public">Public</option>
                                        <option value="private">Private (Link only)</option>
                                    </select>
                                </div>
                            </div>
                        </section>

                        <section className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 shadow-sm">
                            <h2 className="text-sm font-bold text-brand-forest mb-4 uppercase tracking-widest">Media</h2>
                            <div className="space-y-4">
                                <div>
                                    <p className="text-[10px] font-bold text-brand-ink/40 uppercase mb-2">Main Image *</p>
                                    <div 
                                        onClick={() => mainImageRef.current?.click()}
                                        className="w-full aspect-square rounded-2xl bg-white border-2 border-dashed border-brand-forest/10 flex items-center justify-center overflow-hidden cursor-pointer hover:border-brand-orange transition-colors"
                                    >
                                        {mainImagePreview ? (
                                            <img src={mainImagePreview} className="w-full h-full object-cover" />
                                        ) : (
                                            <span className="text-xs text-brand-ink/30 font-bold">+ Upload Image</span>
                                        )}
                                    </div>
                                    <input ref={mainImageRef} type="file" className="hidden" accept="image/*" onChange={handleMainImageChange} />
                                </div>
                                
                                <div>
                                    <p className="text-[10px] font-bold text-brand-ink/40 uppercase mb-2">Gallery</p>
                                    <div className="grid grid-cols-3 gap-2">
                                        {galleryPreviews.map((p, i) => (
                                            <div key={i} className="aspect-square rounded-lg bg-white border border-brand-forest/5 overflow-hidden">
                                                <img src={p} className="w-full h-full object-cover" />
                                            </div>
                                        ))}
                                        <button 
                                            type="button"
                                            onClick={() => galleryRef.current?.click()}
                                            className="aspect-square rounded-lg bg-brand-forest/5 border border-dashed border-brand-forest/20 flex items-center justify-center text-lg hover:border-brand-orange transition-colors"
                                        >
                                            +
                                        </button>
                                    </div>
                                    <input ref={galleryRef} type="file" className="hidden" accept="image/*" multiple onChange={handleGalleryChange} />
                                </div>
                            </div>
                        </section>

                        <section className="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 shadow-sm space-y-4">
                            <button 
                                type="submit" 
                                disabled={processing}
                                className="w-full bg-brand-orange text-white py-4 rounded-full font-bold shadow-lg shadow-brand-orange/20 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 transition-all"
                                style={{ backgroundColor: 'var(--lt-orange)' }}
                            >
                                {processing ? 'Saving...' : isEdit ? 'Update Product' : 'Publish Product'}
                            </button>
                            <button 
                                type="button" 
                                onClick={() => setData('status', 'draft')}
                                className="w-full bg-white border border-brand-forest/10 text-brand-forest py-4 rounded-full font-bold hover:bg-brand-forest/5 transition-all text-sm"
                            >
                                Save as Draft
                            </button>
                        </section>
                    </div>
                </div>
            </form>
        </BrandLayout>
    );
}
