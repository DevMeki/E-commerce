import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler, useState } from 'react';

export default function Onboarding() {
    const [currentStep, setCurrentStep] = useState(1);
    const totalSteps = 3;

    const { data, setData, post, processing, errors } = useForm({
        brand_name: '',
        brand_slug: '',
        brand_category: '',
        brand_location: '',
        brand_logo: null as File | null,
        brand_tagline: '',
        brand_bio: '',
        whatsapp: '',
        instagram: '',
        shipping_policy: '',
        return_policy: '',
        contact_email: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('brand.onboarding'));
    };

    const nextStep = () => setCurrentStep(prev => Math.min(prev + 1, totalSteps));
    const prevStep = () => setCurrentStep(prev => Math.max(prev - 1, 1));

    return (
        <div className="min-h-screen bg-brand-parchment text-brand-ink flex flex-col font-sans">
            <Head title="Seller Onboarding | LocalTrade" />
            
            {/* Header */}
            <header className="border-b border-brand-forest/5 bg-brand-forest/95 backdrop-blur text-white">
                <div className="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
                    <Link href={route('home')} className="flex items-center gap-2">
                        <div className="w-8 h-8 rounded-xl flex items-center justify-center bg-brand-orange shadow-lg shadow-brand-orange/20">
                            <span className="text-white font-bold text-xl">L</span>
                        </div>
                        <span className="font-bold text-lg tracking-tight">LocalTrade</span>
                    </Link>
                    <Link href={route('brand.dashboard')} className="text-xs text-white/70 hover:text-brand-orange transition-colors">
                        Skip for now →
                    </Link>
                </div>
            </header>

            <main className="flex-1 px-4 py-8 flex justify-center">
                <div className="w-full max-w-3xl">
                    <div className="bg-white border border-brand-forest/10 rounded-2xl px-5 py-6 sm:px-7 sm:py-8 shadow-sm">
                        
                        {/* Title */}
                        <div className="mb-6">
                            <p className="text-[11px] uppercase tracking-[0.2em] text-brand-orange font-bold mb-2">
                                Seller onboarding
                            </p>
                            <h1 className="text-xl sm:text-2xl font-semibold text-brand-forest">Set up your LocalTrade brand</h1>
                            <p className="text-xs sm:text-sm text-brand-ink/50 mt-1">
                                Complete these quick steps so buyers can discover and trust your brand.
                            </p>
                        </div>

                        {/* Step Indicator */}
                        <div className="flex items-center gap-3 mb-8 text-xs">
                            {[1, 2, 3].map(step => (
                                <div key={step} className="flex-1 flex items-center gap-2">
                                    <div className={`w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold ${
                                        currentStep === step ? 'bg-brand-orange text-white' : 
                                        currentStep > step ? 'bg-brand-orange/60 text-white' : 'bg-brand-forest/5 text-brand-ink/40'
                                    }`}>
                                        {step}
                                    </div>
                                    <span className={`hidden sm:inline font-semibold ${currentStep >= step ? 'text-brand-forest' : 'text-brand-ink/40'}`}>
                                        {step === 1 ? 'Brand basics' : step === 2 ? 'Logo & profile' : 'Policies & review'}
                                    </span>
                                    {step < 3 && <div className="h-px flex-1 bg-brand-forest/10 ml-2"></div>}
                                </div>
                            ))}
                        </div>

                        <form onSubmit={submit}>
                            {/* STEP 1: BRAND BASICS */}
                            {currentStep === 1 && (
                                <section className="space-y-6 animate-fade-in">
                                    <div className="border-b border-brand-forest/5 pb-2">
                                        <h2 className="text-sm font-bold text-brand-forest uppercase tracking-widest">Step 1 · Brand basics</h2>
                                        <p className="text-[10px] text-brand-ink/40 mt-1 uppercase tracking-tighter font-bold">This information appears on your storefront.</p>
                                    </div>

                                    <div className="grid gap-4">
                                        <div>
                                            <label className="block text-xs font-bold text-brand-ink/70 mb-2 uppercase tracking-widest">Brand / Store Name</label>
                                            <input 
                                                type="text" 
                                                value={data.brand_name}
                                                onChange={e => setData('brand_name', e.target.value)}
                                                className="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all"
                                                placeholder="e.g. Lagos Streetwear Co."
                                            />
                                        </div>

                                        <div>
                                            <label className="block text-xs font-bold text-brand-ink/70 mb-2 uppercase tracking-widest">Store URL handle</label>
                                            <div className="flex items-center">
                                                <span className="bg-brand-parchment border border-brand-forest/10 border-r-0 rounded-l-xl px-4 py-3 text-xs text-brand-ink/30 font-bold uppercase tracking-tighter">localtrade.ng/store/</span>
                                                <input 
                                                    type="text" 
                                                    value={data.brand_slug}
                                                    onChange={e => setData('brand_slug', e.target.value)}
                                                    className="flex-1 rounded-r-xl bg-brand-parchment border border-brand-forest/10 px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all"
                                                    placeholder="lagos-streetwear"
                                                />
                                            </div>
                                        </div>

                                        <div className="grid sm:grid-cols-2 gap-4">
                                            <div>
                                                <label className="block text-xs font-bold text-brand-ink/70 mb-2 uppercase tracking-widest">Primary Category</label>
                                                <select 
                                                    value={data.brand_category}
                                                    onChange={e => setData('brand_category', e.target.value)}
                                                    className="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all appearance-none"
                                                >
                                                    <option value="">Select Category</option>
                                                    {['Fashion', 'Beauty', 'Electronics', 'Home & Living', 'Food & Drinks', 'Art & Craft'].map(cat => (
                                                        <option key={cat} value={cat}>{cat}</option>
                                                    ))}
                                                </select>
                                            </div>
                                            <div>
                                                <label className="block text-xs font-bold text-brand-ink/70 mb-2 uppercase tracking-widest">City / State</label>
                                                <input 
                                                    type="text" 
                                                    value={data.brand_location}
                                                    onChange={e => setData('brand_location', e.target.value)}
                                                    className="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all"
                                                    placeholder="e.g. Lagos, Nigeria"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            )}

                            {/* STEP 2: LOGO & PROFILE */}
                            {currentStep === 2 && (
                                <section className="space-y-6 animate-fade-in">
                                    <div className="border-b border-brand-forest/5 pb-2">
                                        <h2 className="text-sm font-bold text-brand-forest uppercase tracking-widest">Step 2 · Logo & brand profile</h2>
                                        <p className="text-[10px] text-brand-ink/40 mt-1 uppercase tracking-tighter font-bold">Add your logo and tell your brand story.</p>
                                    </div>

                                    <div className="grid gap-6">
                                        <div>
                                            <label className="block text-xs font-bold text-brand-ink/70 mb-3 uppercase tracking-widest">Brand Logo</label>
                                            <div className="flex items-center gap-4">
                                                <div className="w-20 h-20 rounded-2xl bg-brand-parchment border-2 border-dashed border-brand-forest/10 flex items-center justify-center overflow-hidden">
                                                    {data.brand_logo ? (
                                                        <img src={URL.createObjectURL(data.brand_logo)} className="w-full h-full object-cover" alt="Preview" />
                                                    ) : (
                                                        <span className="text-[10px] text-brand-ink/30 font-bold uppercase">No Logo</span>
                                                    )}
                                                </div>
                                                <div className="flex-1">
                                                    <input 
                                                        type="file" 
                                                        onChange={e => setData('brand_logo', e.target.files ? e.target.files[0] : null)}
                                                        className="block w-full text-xs text-brand-ink/40 file:mr-3 file:px-4 file:py-2 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:uppercase file:bg-brand-forest file:text-white cursor-pointer" 
                                                    />
                                                    <p className="text-[10px] text-brand-ink/30 mt-2 font-bold uppercase tracking-tighter">Square PNG/JPG, min 400x400px.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label className="block text-xs font-bold text-brand-ink/70 mb-2 uppercase tracking-widest">Short Tagline</label>
                                            <input 
                                                type="text" 
                                                value={data.brand_tagline}
                                                onChange={e => setData('brand_tagline', e.target.value)}
                                                className="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all"
                                                placeholder="e.g. Urban fashion made in Lagos"
                                            />
                                        </div>

                                        <div>
                                            <label className="block text-xs font-bold text-brand-ink/70 mb-2 uppercase tracking-widest">Brand Story / Bio</label>
                                            <textarea 
                                                rows={4}
                                                value={data.brand_bio}
                                                onChange={e => setData('brand_bio', e.target.value)}
                                                className="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all resize-none"
                                                placeholder="Tell buyers who you are, what you make..."
                                            />
                                        </div>

                                        <div className="grid sm:grid-cols-2 gap-4">
                                            <div>
                                                <label className="block text-xs font-bold text-brand-ink/70 mb-2 uppercase tracking-widest">WhatsApp</label>
                                                <input 
                                                    type="text" 
                                                    value={data.whatsapp}
                                                    onChange={e => setData('whatsapp', e.target.value)}
                                                    className="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all"
                                                    placeholder="+234..."
                                                />
                                            </div>
                                            <div>
                                                <label className="block text-xs font-bold text-brand-ink/70 mb-2 uppercase tracking-widest">Instagram</label>
                                                <input 
                                                    type="text" 
                                                    value={data.instagram}
                                                    onChange={e => setData('instagram', e.target.value)}
                                                    className="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all"
                                                    placeholder="@yourbrand"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            )}

                            {/* STEP 3: POLICIES */}
                            {currentStep === 3 && (
                                <section className="space-y-6 animate-fade-in">
                                    <div className="border-b border-brand-forest/5 pb-2">
                                        <h2 className="text-sm font-bold text-brand-forest uppercase tracking-widest">Step 3 · Store policies & contact</h2>
                                        <p className="text-[10px] text-brand-ink/40 mt-1 uppercase tracking-tighter font-bold">Clear policies help build buyer trust.</p>
                                    </div>

                                    <div className="grid gap-6">
                                        <div>
                                            <label className="block text-xs font-bold text-brand-ink/70 mb-2 uppercase tracking-widest">Shipping Policy</label>
                                            <textarea 
                                                rows={4}
                                                value={data.shipping_policy}
                                                onChange={e => setData('shipping_policy', e.target.value)}
                                                className="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all resize-none"
                                                placeholder="e.g. We ship across Nigeria within 2-5 days..."
                                            />
                                        </div>

                                        <div>
                                            <label className="block text-xs font-bold text-brand-ink/70 mb-2 uppercase tracking-widest">Return Policy</label>
                                            <textarea 
                                                rows={4}
                                                value={data.return_policy}
                                                onChange={e => setData('return_policy', e.target.value)}
                                                className="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all resize-none"
                                                placeholder="e.g. Returns accepted within 7 days..."
                                            />
                                        </div>

                                        <div>
                                            <label className="block text-xs font-bold text-brand-ink/70 mb-2 uppercase tracking-widest">Support Contact Email</label>
                                            <input 
                                                type="email" 
                                                value={data.contact_email}
                                                onChange={e => setData('contact_email', e.target.value)}
                                                className="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all"
                                                placeholder="support@yourbrand.com"
                                            />
                                        </div>
                                    </div>
                                </section>
                            )}

                            {/* Nav Buttons */}
                            <div className="mt-10 flex items-center justify-between">
                                <button 
                                    type="button" 
                                    onClick={prevStep}
                                    disabled={currentStep === 1}
                                    className="px-6 py-3 rounded-xl border border-brand-forest/10 text-xs font-bold uppercase tracking-widest disabled:opacity-30"
                                >
                                    ← Back
                                </button>

                                {currentStep < totalSteps ? (
                                    <button 
                                        type="button" 
                                        onClick={nextStep}
                                        className="px-8 py-3 rounded-xl bg-brand-orange text-white text-xs font-bold uppercase tracking-widest shadow-lg shadow-brand-orange/20"
                                    >
                                        Next →
                                    </button>
                                ) : (
                                    <button 
                                        type="submit" 
                                        disabled={processing}
                                        className="px-8 py-3 rounded-xl bg-brand-orange text-white text-xs font-bold uppercase tracking-widest shadow-lg shadow-brand-orange/20"
                                    >
                                        {processing ? 'Submitting...' : 'Submit Profile'}
                                    </button>
                                )}
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    );
}
