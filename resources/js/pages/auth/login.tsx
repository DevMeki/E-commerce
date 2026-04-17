import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler, useState } from 'react';

export default function Login({ status, canResetPassword }: { status?: string; canResetPassword: boolean }) {
    const [showPassword, setShowPassword] = useState(false);
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <div className="min-h-screen bg-brand-parchment text-brand-ink flex flex-col font-sans">
            <Head title="Login | LocalTrade" />
            
            {/* Header */}
            <header className="bg-brand-forest shadow-sm">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <Link href={route('home')} className="flex items-center">
                        <img
                            src={`${new URL(route('home')).pathname}/Assets/LocalTrade/Text logo dark bg.png`.replace('//', '/')}
                            alt="LocalTrade Logo"
                            className="h-8 md:h-12 w-auto object-contain"
                        />
                        {/* <span className="font-bold tracking-tight text-xl text-white">LocalTrade</span> */}
                    </Link>
                    <Link href={route('home')} className="text-sm font-medium text-white/70 hover:text-white transition-colors">
                        Back to home
                    </Link>
                </div>
            </header>

            <main className="flex-1 flex items-center justify-center px-4 py-8">
                <div className="w-full max-w-md">
                    <div className="bg-green-50 border border-brand-forest/5 rounded-2xl px-6 sm:px-8 py-8 sm:py-10 shadow-sm">
                        <div className="mb-6 sm:mb-8 text-center sm:text-left">
                            <h1 className="text-xl sm:text-2xl font-semibold text-brand-forest">Login to LocalTrade</h1>
                            <p className="text-xs sm:text-sm text-brand-ink/50 mt-1">
                                Welcome back! Please enter your details.
                            </p>
                        </div>

                        {status && (
                            <div className="mb-6 rounded-xl bg-brand-forest p-4 text-xs text-white font-medium shadow-lg">
                                {status}
                            </div>
                        )}

                        <form onSubmit={submit} className="space-y-4">
                            <div>
                                <label className="block text-xs font-bold text-brand-ink/70 mb-1.5 uppercase tracking-widest">Email address</label>
                                <input 
                                    type="email" 
                                    required 
                                    value={data.email}
                                    onChange={e => setData('email', e.target.value)}
                                    className="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-3 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:border-brand-orange transition-all"
                                    placeholder="you@example.com"
                                />
                                {errors.email && <p className="text-red-500 text-[10px] mt-1 font-bold">{errors.email}</p>}
                            </div>

                            <div>
                                <div className="flex items-center justify-between mb-1.5">
                                    <label className="block text-xs font-bold text-brand-ink/70 uppercase tracking-widest">Password</label>
                                    <Link href={route('password.request')} className="text-[10px] text-brand-orange hover:underline font-bold uppercase tracking-tighter">
                                        Forgot password?
                                    </Link>
                                </div>
                                <div className="relative">
                                    <input 
                                        type={showPassword ? 'text' : 'password'}
                                        required
                                        value={data.password}
                                        onChange={e => setData('password', e.target.value)}
                                        className="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-3 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:border-brand-orange transition-all"
                                        placeholder="••••••••"
                                    />
                                    <button 
                                        type="button"
                                        onClick={() => setShowPassword(!showPassword)}
                                        className="absolute right-3 top-1/2 -translate-y-1/2 text-brand-ink/30 hover:text-brand-ink transition-colors"
                                        aria-label={showPassword ? 'Hide password' : 'Show password'}
                                    >
                                        {showPassword ? (
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-4 h-4">
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                            </svg>
                                        ) : (
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-4 h-4">
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        )}
                                    </button>
                                </div>
                                {errors.password && <p className="text-red-500 text-[10px] mt-1 font-bold">{errors.password}</p>}
                            </div>

                            <div className="flex items-center py-1">
                                <label className="flex items-center gap-2 cursor-pointer select-none">
                                    <input 
                                        type="checkbox" 
                                        checked={data.remember}
                                        onChange={e => setData('remember', e.target.checked as any)}
                                        className="w-4 h-4 rounded border-brand-forest/20 text-brand-orange focus:ring-brand-orange" 
                                    />
                                    <span className="text-xs text-brand-ink/50 font-bold uppercase tracking-tighter">Remember me</span>
                                </label>
                            </div>

                            <button 
                                type="submit" 
                                disabled={processing}
                                className="w-full mt-2 py-4 rounded-xl bg-brand-orange text-white font-bold text-sm hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-brand-orange/20"
                            >
                                {processing ? 'Logging in...' : 'Login'}
                            </button>
                        </form>

                        <p className="text-center mt-8 text-sm">
                            <span className="text-brand-ink/50">New to LocalTrade?</span>{' '}
                            <Link href={route('register')} className="text-brand-orange hover:underline font-bold">Join now</Link>
                        </p>
                    </div>

                    <p className="mt-6 text-[10px] text-center text-brand-ink/30 max-w-sm mx-auto uppercase tracking-widest leading-relaxed">
                        By logging in, you agree to LocalTrade's<br />
                        <a href="#" className="text-brand-orange hover:underline">Terms</a> and <a href="#" className="text-brand-orange hover:underline">Privacy Policy</a>.
                    </p>
                </div>
            </main>
        </div>
    );
}
