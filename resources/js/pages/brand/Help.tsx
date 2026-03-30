import BrandLayout from '@/layouts/BrandLayout';
import { Head } from '@inertiajs/react';
import { useState } from 'react';

const FAQ_DATA = [
    {
        title: 'Getting started & store setup',
        questions: [
            { q: 'What do I need before opening a LocalTrade store?', a: 'You’ll need your brand name, logo, a short description, contact details, and at least one product you’re ready to sell. If you have a registered business, you can also add your BN/RC number in your brand profile.' },
            { q: 'How do I set my store policies?', a: 'During seller onboarding, you can define your handling time, return window, and basic rules for refunds or exchanges. Keep it clear and realistic—customers see these policies on your brand page and product listings.' },
            { q: 'Can I update my logo and brand info later?', a: 'Yes. You can edit your logo, description, social links and other details from your Brand settings page at any time. Major changes may be reviewed by LocalTrade.' }
        ]
    },
    {
        title: 'Product approval & listing quality',
        questions: [
            { q: 'Why do my products need approval?', a: 'LocalTrade reviews new listings to protect buyers and keep the marketplace clean. We check for prohibited items, misleading content, poor-quality images, and missing information like pricing or sizing.' },
            { q: 'How long does product review take?', a: 'Most products are reviewed within 24 hours on business days. During very busy periods, it might take slightly longer, but you can still edit drafts while you wait for approval.' },
            { q: 'My product was rejected. What should I do?', a: 'Check the rejection reason in your product details. It might be due to unclear images, missing details, or a policy violation. Fix the highlighted issues, save changes and resubmit.' }
        ]
    },
    {
        title: 'Payouts & earnings',
        questions: [
            { q: 'When do I get paid after an order?', a: 'Payouts are usually released after the order is marked as delivered and the buyer’s return window has passed. Depending on your payout schedule, funds might land weekly or on a custom cycle.' },
            { q: 'How are LocalTrade fees calculated?', a: 'For each order, we deduct the platform fee and payment processing charge before sending your payout. You’ll see a breakdown per order in your Earnings or Payout view.' }
        ]
    },
    {
        title: 'Shipping, logistics & returns',
        questions: [
            { q: 'Who handles delivery—the brand or LocalTrade?', a: 'This depends on your configuration. Some brands handle their own couriers, while others plug into LocalTrade’s logistics partners.' },
            { q: 'What is expected response time for orders?', a: 'We encourage sellers to confirm and ship orders within their stated processing time, usually 1–3 business days. Slow responses can impact your seller rating.' }
        ]
    }
];

export default function Help() {
    const [openIndex, setOpenIndex] = useState<string | null>(null);

    const toggleFaq = (id: string) => {
        setOpenIndex(openIndex === id ? null : id);
    };

    return (
        <BrandLayout>
            <Head title="Seller Help & Support | LocalTrade" />
            
            <div className="space-y-8 animate-fade-in">
                {/* HERO */}
                <section className="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between border-b border-brand-forest/5 pb-8">
                    <div>
                        <p className="text-[10px] uppercase tracking-[0.2em] text-brand-orange font-bold mb-2">
                            Seller help
                        </p>
                        <h1 className="text-2xl sm:text-3xl font-bold tracking-tight text-brand-forest">
                            Get help running your LocalTrade store
                        </h1>
                        <p className="text-xs sm:text-sm text-brand-ink/50 mt-2 max-w-xl font-medium">
                            From payouts and product approval to shipping and store policies, this space is for brands selling on LocalTrade.
                        </p>
                    </div>
                    <div className="bg-green-50 border border-brand-forest/5 rounded-2xl px-6 py-5 text-xs text-brand-ink/70 shadow-sm max-w-xs">
                        <p className="font-bold text-brand-forest mb-2 uppercase tracking-widest text-[10px]">Need urgent help?</p>
                        <p className="mb-2 font-bold text-brand-forest">
                            seller-support@localtrade.ng
                        </p>
                        <p className="text-brand-ink/40 font-bold uppercase text-[9px] tracking-tighter">
                            Typical response: <span className="text-brand-orange">1–4 business hours</span>.
                        </p>
                    </div>
                </section>

                {/* FAQ GRID */}
                <section className="grid lg:grid-cols-2 gap-6">
                    {FAQ_DATA.map((category, catIdx) => (
                        <div key={catIdx} className="bg-white border border-brand-forest/5 rounded-2xl p-6 shadow-sm">
                            <h2 className="text-xs font-bold mb-6 text-brand-forest uppercase tracking-widest flex items-center justify-between">
                                {category.title}
                                <span className="text-[9px] text-brand-ink/30 px-2 py-1 bg-brand-parchment rounded-lg">{category.questions.length} topics</span>
                            </h2>

                            <div className="space-y-1">
                                {category.questions.map((faq, faqIdx) => {
                                    const id = `${catIdx}-${faqIdx}`;
                                    const isOpen = openIndex === id;
                                    return (
                                        <div key={faqIdx} className={`rounded-xl transition-all ${isOpen ? 'bg-brand-parchment' : ''}`}>
                                            <button 
                                                onClick={() => toggleFaq(id)}
                                                className="w-full text-left p-4 flex items-center justify-between gap-4 group"
                                            >
                                                <span className={`text-[13px] font-bold tracking-tight transition-colors ${isOpen ? 'text-brand-orange' : 'text-brand-ink/70 group-hover:text-brand-forest'}`}>
                                                    {faq.q}
                                                </span>
                                                <span className={`text-lg font-light transition-transform ${isOpen ? 'rotate-45 text-brand-orange' : 'text-brand-ink/20'}`}>+</span>
                                            </button>
                                            {isOpen && (
                                                <div className="px-4 pb-4 text-xs text-brand-ink/50 leading-relaxed font-medium animate-fade-in-down">
                                                    {faq.a}
                                                </div>
                                            )}
                                        </div>
                                    );
                                })}
                            </div>
                        </div>
                    ))}
                </section>

                {/* STILL STUCK */}
                <section className="bg-brand-forest rounded-2xl p-8 text-white shadow-xl shadow-brand-forest/20">
                    <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                        <div className="max-w-xl">
                            <h2 className="text-xl font-bold mb-3">Still stuck with something?</h2>
                            <p className="text-sm text-white/60 font-medium leading-relaxed">
                                Share your brand name, affected order or product ID, and a short description of the issue.
                                Our seller support team will get back to you as quickly as possible.
                            </p>
                        </div>
                        <div className="flex flex-wrap gap-3">
                            <a href="mailto:seller-support@localtrade.ng" className="px-6 py-4 rounded-xl bg-white text-brand-forest text-xs font-bold uppercase tracking-widest hover:scale-[1.02] transition-all">
                                Email seller support
                            </a>
                            <button className="px-6 py-4 rounded-xl bg-brand-orange text-white text-xs font-bold uppercase tracking-widest hover:scale-[1.02] transition-all">
                                Open support ticket
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </BrandLayout>
    );
}
