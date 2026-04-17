import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler, useEffect, useMemo, useState } from 'react';

type NigeriaLocations = Record<string, string[]>;

export default function Register() {
    const [accountType, setAccountType] = useState<'buyer' | 'brand'>('buyer');
    const [showPassword, setShowPassword] = useState(false);
    const [brandState, setBrandState] = useState('');
    const [brandCity, setBrandCity] = useState('');
    const [locationError, setLocationError] = useState('');
    const [nigeriaLocations, setNigeriaLocations] = useState<NigeriaLocations>({});

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
        if (accountType === 'brand') {
            const validState = stateOptions.includes(brandState);
            const validCity = validState && cityOptions.includes(brandCity);
            if (!validState || !validCity) {
                setLocationError('Please select a valid state and town/city from the suggested options.');
                return;
            }
        }

        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    const handleAccountTypeChange = (type: 'buyer' | 'brand') => {
        setAccountType(type);
        setData('account_type', type);
        setLocationError('');
    };

    useEffect(() => {
        let mounted = true;
        const loadLocations = async () => {
            try {
                const appBasePath = new URL(route('home')).pathname.replace(/\/$/, '');
                const response = await fetch(`${appBasePath}/data/nigeria-locations.json`);
                if (!response.ok) return;
                const json = (await response.json()) as NigeriaLocations;
                if (mounted) setNigeriaLocations(json);
            } catch {
                // If loading fails, the form still works with free-text input.
            }
        };
        void loadLocations();
        return () => {
            mounted = false;
        };
    }, []);

    const stateOptions = useMemo(() => Object.keys(nigeriaLocations), [nigeriaLocations]);
    const cityOptions = useMemo(() => nigeriaLocations[brandState] ?? [], [nigeriaLocations, brandState]);

    return (
        <div className="bg-brand-parchment text-brand-ink flex min-h-screen flex-col font-sans">
            <Head title="Sign up | LocalTrade" />

            {/* Header */}
            <header className="bg-brand-forest shadow-sm">
                <div className="mx-auto flex h-16 max-w-6xl items-center justify-between px-4">
                    <Link href={route('home')} className="flex items-center">
                        <img
                            src={`${new URL(route('home')).pathname}/Assets/LocalTrade/Text logo dark bg.png`.replace('//', '/')}
                            alt="LocalTrade Logo"
                            className="h-8 md:h-12 w-auto object-contain"
                        />
                        {/* <span className="font-bold tracking-tight text-xl text-white">LocalTrade</span> */}
                    </Link>
                    <Link href={route('login')} className="text-sm font-medium text-white/70 transition-colors hover:text-white">
                        Already registered? Log in
                    </Link>
                </div>
            </header>

            <main className="flex flex-1 items-center justify-center px-4 py-12">
                <div className="w-full max-w-xl">
                    <div className="border-brand-forest/5 rounded-2xl border bg-green-50 px-6 py-8 shadow-sm sm:px-8 sm:py-10">
                        <div className="mb-6 text-center sm:text-left">
                            <h1 className="text-brand-forest text-xl font-semibold sm:text-2xl">Create your account</h1>
                            <p className="text-brand-ink/50 mt-1 text-xs sm:text-sm">Select account type and enter your details below.</p>
                        </div>

                        <form onSubmit={submit} className="space-y-6">
                            {/* Account Type Toggle */}
                            <div className="flex items-center gap-4">
                                <button
                                    type="button"
                                    onClick={() => handleAccountTypeChange('buyer')}
                                    className={`flex-1 rounded-xl border px-4 py-3 text-xs font-bold tracking-widest uppercase transition-all ${
                                        accountType === 'buyer'
                                            ? 'bg-brand-orange border-brand-orange shadow-brand-orange/20 text-white shadow-lg'
                                            : 'border-brand-forest/10 text-brand-ink/90 hover:bg-brand-parchment bg-white font-medium'
                                    }`}
                                >
                                    Buyer
                                </button>
                                <button
                                    type="button"
                                    onClick={() => handleAccountTypeChange('brand')}
                                    className={`flex-1 rounded-xl border px-4 py-3 text-xs font-bold tracking-widest uppercase transition-all ${
                                        accountType === 'brand'
                                            ? 'bg-brand-orange border-brand-orange shadow-brand-orange/20 text-white shadow-lg'
                                            : 'border-brand-forest/10 text-brand-ink/90 hover:bg-brand-parchment bg-white font-medium'
                                    }`}
                                >
                                    Brand / Seller
                                </button>
                            </div>

                            {accountType === 'buyer' && (
                                <div className="animate-fade-in space-y-6 text-center">
                                    <div className="relative my-6 flex items-center justify-center">
                                        <div className="absolute inset-0 flex items-center">
                                            <div className="border-brand-forest/10 w-full border-t"></div>
                                        </div>
                                        <span className="text-brand-ink/30 relative bg-green-50 px-4 text-[10px] font-bold tracking-[0.2em] uppercase">
                                            Registration with email
                                        </span>
                                    </div>
                                </div>
                            )}

                            <div className="grid gap-6">
                                <div>
                                    <label className="text-brand-ink/60 mb-2 block text-[10px] font-bold tracking-widest uppercase">
                                        {accountType === 'brand' ? 'Owner Name' : 'Full Name'}
                                    </label>
                                    <input
                                        type="text"
                                        required
                                        value={data.fullname}
                                        onChange={(e) => setData('fullname', e.target.value)}
                                        className="bg-brand-parchment border-brand-forest/10 focus:border-brand-orange w-full rounded-xl border px-4 py-3 text-sm transition-all focus:outline-none"
                                        placeholder="e.g. John Doe"
                                    />
                                    {errors.fullname && <p className="mt-1 text-[10px] font-bold text-red-500">{errors.fullname}</p>}
                                </div>

                                <div>
                                    <label className="text-brand-ink/60 mb-2 block text-[10px] font-bold tracking-widest uppercase">
                                        Email Address
                                    </label>
                                    <input
                                        type="email"
                                        required
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                        className="bg-brand-parchment border-brand-forest/10 focus:border-brand-orange w-full rounded-xl border px-4 py-3 text-sm transition-all focus:outline-none"
                                        placeholder="name@example.com"
                                    />
                                    {errors.email && <p className="mt-1 text-[10px] font-bold text-red-500">{errors.email}</p>}
                                </div>

                                <div className="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label className="text-brand-ink/60 mb-2 block text-[10px] font-bold tracking-widest uppercase">
                                            Password
                                        </label>
                                        <div className="relative">
                                            <input
                                                type={showPassword ? 'text' : 'password'}
                                                required
                                                value={data.password}
                                                onChange={(e) => setData('password', e.target.value)}
                                                className="bg-brand-parchment border-brand-forest/10 focus:border-brand-orange w-full rounded-xl border px-4 py-3 pr-10 text-sm transition-all focus:outline-none"
                                                placeholder="••••••••"
                                            />
                                            <button
                                                type="button"
                                                onClick={() => setShowPassword(!showPassword)}
                                                className="text-brand-ink/30 hover:text-brand-ink absolute top-1/2 right-3 -translate-y-1/2 transition-colors"
                                                aria-label={showPassword ? 'Hide password' : 'Show password'}
                                            >
                                                {showPassword ? (
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        strokeWidth={1.5}
                                                        stroke="currentColor"
                                                        className="h-4 w-4"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"
                                                        />
                                                    </svg>
                                                ) : (
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        strokeWidth={1.5}
                                                        stroke="currentColor"
                                                        className="h-4 w-4"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                                                        />
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                )}
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label className="text-brand-ink/60 mb-2 block text-[10px] font-bold tracking-widest uppercase">
                                            Repeat Password
                                        </label>
                                        <div className="relative">
                                            <input
                                                type={showPassword ? 'text' : 'password'}
                                                required
                                                value={data.password_confirmation}
                                                onChange={(e) => setData('password_confirmation', e.target.value)}
                                                className="bg-brand-parchment border-brand-forest/10 focus:border-brand-orange w-full rounded-xl border px-4 py-3 pr-10 text-sm transition-all focus:outline-none"
                                                placeholder="••••••••"
                                            />
                                            <button
                                                type="button"
                                                onClick={() => setShowPassword(!showPassword)}
                                                className="text-brand-ink/30 hover:text-brand-ink absolute top-1/2 right-3 -translate-y-1/2 transition-colors"
                                                aria-label={showPassword ? 'Hide password' : 'Show password'}
                                            >
                                                {showPassword ? (
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        strokeWidth={1.5}
                                                        stroke="currentColor"
                                                        className="h-4 w-4"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"
                                                        />
                                                    </svg>
                                                ) : (
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        strokeWidth={1.5}
                                                        stroke="currentColor"
                                                        className="h-4 w-4"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                                                        />
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                )}
                                            </button>
                                        </div>
                                    </div>
                                    {errors.password && <p className="col-span-2 mt-1 text-[10px] font-bold text-red-500">{errors.password}</p>}
                                </div>

                                {accountType === 'brand' && (
                                    <div className="border-brand-forest/5 animate-fade-in-down space-y-6 border-t pt-6">
                                        <p className="text-brand-orange text-[10px] font-bold tracking-widest uppercase">Brand Details</p>

                                        <div>
                                            <label className="text-brand-ink/60 mb-2 block text-[10px] font-bold tracking-widest uppercase">
                                                Brand / Store Name
                                            </label>
                                            <input
                                                type="text"
                                                required={accountType === 'brand'}
                                                value={data.brand_name}
                                                onChange={(e) => setData('brand_name', e.target.value)}
                                                className="bg-brand-parchment border-brand-forest/10 focus:border-brand-orange w-full rounded-xl border px-4 py-3 text-sm transition-all focus:outline-none"
                                                placeholder="e.g. Heritage Crafts"
                                            />
                                        </div>

                                        <div>
                                            <label className="text-brand-ink/60 mb-2 block text-[10px] font-bold tracking-widest uppercase">
                                                Store URL
                                            </label>
                                            <div className="flex items-center">
                                                <span className="bg-brand-parchment border-brand-forest/10 text-brand-ink/30 rounded-l-xl border border-r-0 px-4 py-3 text-xs font-bold tracking-tighter uppercase">
                                                    localtrade.ng/
                                                </span>
                                                <input
                                                    type="text"
                                                    required={accountType === 'brand'}
                                                    value={data.brand_slug}
                                                    onChange={(e) => setData('brand_slug', e.target.value)}
                                                    className="bg-brand-parchment border-brand-forest/10 focus:border-brand-orange flex-1 rounded-r-xl border px-4 py-3 text-sm transition-all focus:outline-none"
                                                    placeholder="your-brand-name"
                                                />
                                            </div>
                                            {errors.brand_slug && <p className="mt-1 text-[10px] font-bold text-red-500">{errors.brand_slug}</p>}
                                        </div>

                                        <div className="grid gap-4 sm:grid-cols-2">
                                            <div>
                                                <label className="text-brand-ink/60 mb-2 block text-[10px] font-bold tracking-widest uppercase">
                                                    Category
                                                </label>
                                                <select
                                                    required={accountType === 'brand'}
                                                    value={data.brand_category}
                                                    onChange={(e) => setData('brand_category', e.target.value)}
                                                    className="bg-brand-parchment border-brand-forest/10 focus:border-brand-orange w-full appearance-none rounded-xl border px-4 py-3 text-sm transition-all focus:outline-none"
                                                >
                                                    <option value="">Select Category</option>
                                                    {[
                                                        'Fashion',
                                                        'Beauty',
                                                        'Electronics',
                                                        'Home & Living',
                                                        'Food & Drinks',
                                                        'Art & Craft',
                                                        'Textiles',
                                                        'Prints',
                                                        'Footwear',
                                                    ].map((cat) => (
                                                        <option key={cat} value={cat}>
                                                            {cat}
                                                        </option>
                                                    ))}
                                                </select>
                                            </div>
                                            <div>
                                                <label className="text-brand-ink/60 mb-2 block text-[10px] font-bold tracking-widest uppercase">
                                                    State
                                                </label>
                                                <input
                                                    type="text"
                                                    list="nigeria-states"
                                                    required={accountType === 'brand'}
                                                    value={brandState}
                                                    onChange={(e) => {
                                                        const nextState = e.target.value;
                                                        setBrandState(nextState);
                                                        const nextCities = nigeriaLocations[nextState] ?? [];
                                                        const keepCity = brandCity && nextCities.includes(brandCity);
                                                        const nextCity = keepCity ? brandCity : '';
                                                        if (!keepCity) setBrandCity('');
                                                        setData('brand_location', nextCity ? `${nextCity}, ${nextState}` : nextState);
                                                        setLocationError('');
                                                    }}
                                                    onBlur={() => {
                                                        if (!brandState) return;
                                                        if (!stateOptions.includes(brandState)) {
                                                            setBrandState('');
                                                            setBrandCity('');
                                                            setData('brand_location', '');
                                                            setLocationError('Please choose a state from the dropdown suggestions.');
                                                        }
                                                    }}
                                                    className="bg-brand-parchment border-brand-forest/10 focus:border-brand-orange w-full rounded-xl border px-4 py-3 text-sm transition-all focus:outline-none"
                                                    disabled={stateOptions.length === 0}
                                                    placeholder={stateOptions.length === 0 ? 'Loading states...' : 'Search state'}
                                                />
                                                <datalist id="nigeria-states">
                                                    {stateOptions.map((state) => (
                                                        <option key={state} value={state} />
                                                    ))}
                                                </datalist>
                                            </div>
                                            <div>
                                                <label className="text-brand-ink/60 mb-2 block text-[10px] font-bold tracking-widest uppercase">
                                                    Town / City
                                                </label>
                                                <input
                                                    type="text"
                                                    list="nigeria-cities"
                                                    required={accountType === 'brand'}
                                                    value={brandCity}
                                                    onChange={(e) => {
                                                        const nextCity = e.target.value;
                                                        setBrandCity(nextCity);
                                                        setData('brand_location', brandState ? `${nextCity}, ${brandState}` : nextCity);
                                                        setLocationError('');
                                                    }}
                                                    onBlur={() => {
                                                        if (!brandState || !brandCity) return;
                                                        if (!cityOptions.includes(brandCity)) {
                                                            setBrandCity('');
                                                            setData('brand_location', brandState);
                                                            setLocationError('Please choose a town/city from the dropdown suggestions.');
                                                        }
                                                    }}
                                                    className="bg-brand-parchment border-brand-forest/10 focus:border-brand-orange w-full rounded-xl border px-4 py-3 text-sm transition-all focus:outline-none"
                                                    disabled={!brandState || cityOptions.length === 0}
                                                    placeholder={!brandState ? 'Select a state first' : 'Search town / city'}
                                                />
                                                <datalist id="nigeria-cities">
                                                    {cityOptions.map((city) => (
                                                        <option key={city} value={city} />
                                                    ))}
                                                </datalist>
                                            </div>
                                            {locationError && (
                                                <p className="mt-1 text-[10px] font-bold text-red-500 sm:col-span-2">{locationError}</p>
                                            )}
                                        </div>
                                    </div>
                                )}
                            </div>

                            <label className="text-brand-ink/40 mt-4 flex cursor-pointer items-start gap-3 text-[10px] font-bold tracking-tighter uppercase">
                                <input
                                    type="checkbox"
                                    required
                                    className="border-brand-forest/20 text-brand-orange focus:ring-brand-orange mt-0.5 rounded"
                                />
                                <span>
                                    I agree to LocalTrade’s{' '}
                                    <a href="#" className="text-brand-orange hover:underline">
                                        Terms
                                    </a>{' '}
                                    and{' '}
                                    <a href="#" className="text-brand-orange hover:underline">
                                        Privacy Policy
                                    </a>
                                    .
                                </span>
                            </label>

                            <button
                                type="submit"
                                disabled={processing}
                                className="bg-brand-orange shadow-brand-orange/20 mt-2 w-full rounded-xl py-4 text-sm font-bold tracking-widest text-white uppercase shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98]"
                            >
                                {processing ? 'Creating Account...' : 'Create Account'}
                            </button>
                        </form>

                        <p className="mt-8 text-center text-sm">
                            <span className="text-brand-ink/50">Already on LocalTrade?</span>{' '}
                            <Link href={route('login')} className="text-brand-orange font-bold hover:underline">
                                Log in
                            </Link>
                        </p>
                    </div>
                </div>
            </main>
        </div>
    );
}
