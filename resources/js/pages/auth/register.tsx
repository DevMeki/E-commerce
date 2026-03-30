import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler, useState } from 'react';

export default function Register() {
    const [accountType, setAccountType] = useState<'buyer' | 'brand'>('buyer');
    const [showPassword, setShowPassword] = useState(false);

    const { data, setData, post, processing, errors, reset } = useForm({
        account_type: 'buyer',
        fullname: '',
        email: '',
        password: '',
        password_confirmation: '',
        brand_name: '',
        brand_slug: '',
        brand_category: '',
        brand_location: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    const handleAccountTypeChange = (type: 'buyer' | 'brand') => {
        setAccountType(type);
        setData('account_type', type);
    };

    return (
        <div className="min-h-screen bg-brand-parchment text-brand-ink flex flex-col font-sans">
            <Head title="Sign up | LocalTrade" />
            
            {/* Header */}
            <header className="bg-brand-forest shadow-sm">
                <div className="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
                    <Link href={route('home')} className="flex items-center gap-2 group">
                        <img src={`${new URL(route('home')).pathname}/Assets/LocalTrade/10.png`.replace('//', '/')} alt="LocalTrade Logo" className="w-8 h-8 object-contain" />
                        <span className="font-bold text-xl tracking-tight text-white">LocalTrade</span>
                    </Link>
                    <Link href={route('login')} className="text-sm font-medium text-white/70 hover:text-white transition-colors">
                        Already registered? Log in
                    </Link>
                </div>
            </header>

            <main className="flex-1 flex items-center justify-center px-4 py-12">
                <div className="w-full max-w-xl">
                    <div className="bg-green-50 border border-brand-forest/5 rounded-2xl px-6 sm:px-8 py-8 sm:py-10 shadow-sm">
                        <div className="mb-6 text-center sm:text-left">
                            <h1 className="text-xl sm:text-2xl font-semibold text-brand-forest">Create your account</h1>
                            <p className="text-xs sm:text-sm text-brand-ink/50 mt-1">
                                Select account type and enter your details below.
                            </p>
                        </div>

                        <form onSubmit={submit} className="space-y-6">
                            {/* Account Type Toggle */}
                            <div className="flex items-center gap-4">
                                <button 
                                    type="button" 
                                    onClick={() => handleAccountTypeChange('buyer')}
                                    className={`flex-1 py-3 px-4 rounded-xl text-xs font-bold uppercase tracking-widest border transition-all ${
                                        accountType === 'buyer' 
                                            ? 'bg-brand-orange border-brand-orange text-white shadow-lg shadow-brand-orange/20' 
                                            : 'bg-white border-brand-forest/10 text-brand-ink/40 hover:bg-brand-parchment font-medium'
                                    }`}
                                >
                                    Buyer
                                </button>
                                <button 
                                    type="button" 
                                    onClick={() => handleAccountTypeChange('brand')}
                                    className={`flex-1 py-3 px-4 rounded-xl text-xs font-bold uppercase tracking-widest border transition-all ${
                                        accountType === 'brand' 
                                            ? 'bg-brand-orange border-brand-orange text-white shadow-lg shadow-brand-orange/20' 
                                            : 'bg-white border-brand-forest/10 text-brand-ink/40 hover:bg-brand-parchment font-medium'
                                    }`}
                                >
                                    Brand / Seller
                                </button>
                            </div>

                            {accountType === 'buyer' && (
                                <div className="space-y-6 animate-fade-in text-center">
                                     <div className="relative flex items-center justify-center my-6">
                                        <div className="absolute inset-0 flex items-center"><div className="w-full border-t border-brand-forest/10"></div></div>
                                        <span className="relative px-4 bg-green-50 text-[10px] text-brand-ink/30 uppercase tracking-[0.2em] font-bold">Registration with email</span>
                                    </div>
                                </div>
                            )}

                            <div className="grid gap-6">
                                <div>
                                    <label className="block text-[10px] font-bold text-brand-ink/60 uppercase tracking-widest mb-2">
                                        {accountType === 'brand' ? 'Owner Name' : 'Full Name'}
                                    </label>
                                    <input 
                                        type="text" 
                                        required 
                                        value={data.fullname}
                                        onChange={e => setData('fullname', e.target.value)}
                                        className="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all"
                                        placeholder="e.g. John Doe"
                                    />
                                    {errors.fullname && <p className="text-red-500 text-[10px] mt-1 font-bold">{errors.fullname}</p>}
                                </div>

                                <div>
                                    <label className="block text-[10px] font-bold text-brand-ink/60 uppercase tracking-widest mb-2">Email Address</label>
                                    <input 
                                        type="email" 
                                        required 
                                        value={data.email}
                                        onChange={e => setData('email', e.target.value)}
                                        className="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all"
                                        placeholder="name@example.com"
                                    />
                                    {errors.email && <p className="text-red-500 text-[10px] mt-1 font-bold">{errors.email}</p>}
                                </div>

                                <div className="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label className="block text-[10px] font-bold text-brand-ink/60 uppercase tracking-widest mb-2">Password</label>
                                        <input 
                                            type={showPassword ? 'text' : 'password'}
                                            required 
                                            value={data.password}
                                            onChange={e => setData('password', e.target.value)}
                                            className="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all"
                                            placeholder="••••••••"
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-[10px] font-bold text-brand-ink/60 uppercase tracking-widest mb-2">Repeat Password</label>
                                        <input 
                                            type={showPassword ? 'text' : 'password'}
                                            required 
                                            value={data.password_confirmation}
                                            onChange={e => setData('password_confirmation', e.target.value)}
                                            className="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all"
                                            placeholder="••••••••"
                                        />
                                    </div>
                                    {errors.password && <p className="col-span-2 text-red-500 text-[10px] mt-1 font-bold">{errors.password}</p>}
                                </div>

                                {accountType === 'brand' && (
                                    <div className="space-y-6 pt-6 border-t border-brand-forest/5 animate-fade-in-down">
                                        <p className="text-[10px] font-bold text-brand-orange uppercase tracking-widest">Brand Details</p>
                                        
                                        <div>
                                            <label className="block text-[10px] font-bold text-brand-ink/60 uppercase tracking-widest mb-2">Brand / Store Name</label>
                                            <input 
                                                type="text" 
                                                required={accountType === 'brand'}
                                                value={data.brand_name}
                                                onChange={e => setData('brand_name', e.target.value)}
                                                className="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all"
                                                placeholder="e.g. Heritage Crafts"
                                            />
                                        </div>

                                        <div>
                                            <label className="block text-[10px] font-bold text-brand-ink/60 uppercase tracking-widest mb-2">Store URL</label>
                                            <div className="flex items-center">
                                                <span className="bg-brand-parchment border border-brand-forest/10 border-r-0 rounded-l-xl px-4 py-3 text-xs text-brand-ink/30 font-bold uppercase tracking-tighter">localtrade.ng/</span>
                                                <input 
                                                    type="text" 
                                                    required={accountType === 'brand'}
                                                    value={data.brand_slug}
                                                    onChange={e => setData('brand_slug', e.target.value)}
                                                    className="flex-1 rounded-r-xl bg-brand-parchment border border-brand-forest/10 px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all"
                                                    placeholder="your-brand-name"
                                                />
                                            </div>
                                            {errors.brand_slug && <p className="text-red-500 text-[10px] mt-1 font-bold">{errors.brand_slug}</p>}
                                        </div>

                                        <div className="grid sm:grid-cols-2 gap-4">
                                            <div>
                                                <label className="block text-[10px] font-bold text-brand-ink/60 uppercase tracking-widest mb-2">Category</label>
                                                <select 
                                                    required={accountType === 'brand'}
                                                    value={data.brand_category}
                                                    onChange={e => setData('brand_category', e.target.value)}
                                                    className="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all appearance-none"
                                                >
                                                    <option value="">Select Category</option>
                                                    {['Fashion', 'Beauty', 'Electronics', 'Home & Living', 'Food & Drinks', 'Art & Craft', 'Textiles', 'Prints', 'Footwear'].map(cat => (
                                                        <option key={cat} value={cat}>{cat}</option>
                                                    ))}
                                                </select>
                                            </div>
                                            <div>
                                                <label className="block text-[10px] font-bold text-brand-ink/60 uppercase tracking-widest mb-2">City / State</label>
                                                <input 
                                                    type="text" 
                                                    required={accountType === 'brand'}
                                                    value={data.brand_location}
                                                    onChange={e => setData('brand_location', e.target.value)}
                                                    className="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-all"
                                                    placeholder="e.g. Lagos"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                )}
                            </div>

                            <label className="flex items-start gap-3 text-[10px] text-brand-ink/40 font-bold uppercase tracking-tighter cursor-pointer mt-4">
                                <input type="checkbox" required className="mt-0.5 rounded border-brand-forest/20 text-brand-orange focus:ring-brand-orange" />
                                <span>I agree to LocalTrade’s <a href="#" className="text-brand-orange hover:underline">Terms</a> and <a href="#" className="text-brand-orange hover:underline">Privacy Policy</a>.</span>
                            </label>

                            <button 
                                type="submit" 
                                disabled={processing}
                                className="w-full mt-2 py-4 rounded-xl bg-brand-orange text-white font-bold text-sm hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-brand-orange/20 uppercase tracking-widest"
                            >
                                {processing ? 'Creating Account...' : 'Create Account'}
                            </button>
                        </form>

                        <p className="text-center mt-8 text-sm">
                            <span className="text-brand-ink/50">Already on LocalTrade?</span>{' '}
                            <Link href={route('login')} className="text-brand-orange hover:underline font-bold">Log in</Link>
                        </p>
                    </div>
                </div>
            </main>
        </div>
    );
}
