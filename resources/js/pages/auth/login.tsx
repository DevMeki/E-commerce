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
                    <Link href={route('home')} className="flex items-center gap-2 group">
                        <img src={`${new URL(route('home')).pathname}/Assets/LocalTrade/10.png`.replace('//', '/')} alt="LocalTrade Logo" className="w-8 h-8 object-contain" />
                        <span className="font-bold text-xl tracking-tight text-white">LocalTrade</span>
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
                                    >
                                        {showPassword ? 'Hide' : 'Show'}
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
