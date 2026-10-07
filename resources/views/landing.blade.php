@extends('layouts.app')

@section('title', 'উপলব্ধি সমবায় সমিতি (USS) | ' . ($settings['motto_bn'] ?? 'সত্যের পথে স্বপ্নের অভিযান'))

@section('content')

<!-- 1. Royal Proclamation & Announcement Modals (Ancient Wooden Rod Letter Scroll Design) -->
@foreach($announcements as $aIndex => $ann)
@php
    $isCreator = ($currentUser && $currentUser->email() === $ann['published_by_email']);
    $annShareUrl = url('/');
    $annShareText = urlencode("📜 উপলব্ধি সমবায় সমিতি রাজকীয় ফরমান: " . $ann['title_bn'] . "\n\n" . $ann['body_bn'] . "\n— " . $ann['published_by_name'] . " (" . $ann['published_by_designation_bn'] . ")\n" . $annShareUrl);
    $annId = $ann['id'] ?? $ann['slug'] ?? ('ann_' . $aIndex);
@endphp
<div id="royalAnnouncementModal-{{ $aIndex }}" data-ann-id="{{ $annId }}" class="royal-announcement-modal fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-6 bg-slate-950/90 backdrop-blur-md">
    <div class="relative max-w-2xl w-full flex flex-col items-center animate-float">
        
        <!-- Top Wooden Cylinder Roller / Stick with Golden Brass Knobs -->
        <div class="w-full flex items-center justify-center -mb-2 z-20">
            <!-- Left Brass Finial Knob -->
            <div class="w-7 h-9 sm:w-9 sm:h-11 rounded-l-full bg-gradient-to-r from-[#4a1f0b] via-[#d4af37] to-[#8c5220] border-2 border-[#5c270d] shadow-2xl flex-shrink-0"></div>
            <!-- Cylindrical Wooden Rod -->
            <div class="flex-1 h-7 sm:h-9 bg-gradient-to-b from-[#3a1503] via-[#8b4513] to-[#2e0f02] border-y-2 border-[#d4af37]/70 shadow-2xl relative overflow-hidden flex items-center justify-between px-6">
                <div class="absolute inset-0 bg-[repeating-linear-gradient(90deg,transparent,transparent_30px,rgba(0,0,0,0.3)_30px,rgba(0,0,0,0.3)_32px)]"></div>
                <!-- Hanging Red Silk Ribbons -->
                <div class="w-3.5 h-7 bg-gradient-to-b from-[#800020] to-[#c9184a] shadow-md -translate-y-1 rounded-b"></div>
                <div class="w-3.5 h-7 bg-gradient-to-b from-[#800020] to-[#c9184a] shadow-md -translate-y-1 rounded-b"></div>
            </div>
            <!-- Right Brass Finial Knob -->
            <div class="w-7 h-9 sm:w-9 sm:h-11 rounded-r-full bg-gradient-to-l from-[#4a1f0b] via-[#d4af37] to-[#8c5220] border-2 border-[#5c270d] shadow-2xl flex-shrink-0"></div>
        </div>

        <!-- Curled / Unrolled Ancient Parchment Paper Body -->
        <div class="relative w-[94%] sm:w-[96%] max-h-[76vh] overflow-y-auto px-6 py-8 sm:px-10 sm:py-10 shadow-2xl"
             style="background: radial-gradient(circle at 50% 25%, #fffdf8 0%, #f6edd3 60%, #e9d5aa 100%);
                    border-left: 4px solid #8b5a2b; border-right: 4px solid #8b5a2b;
                    box-shadow: 0 25px 60px rgba(0,0,0,0.95), inset 0 0 60px rgba(139,90,43,0.35);
                    filter: drop-shadow(0 15px 30px rgba(0,0,0,0.85));">

            <!-- Sticky Top-Right Quick Dismiss Button -->
            <button onclick="closeAndNextAnnouncement({{ $aIndex }}, '{{ $annId }}')" title="ফরমান গ্রহণ ও বন্ধ করুন (২৪ ঘণ্টার জন্য স্থগিত)" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-[#8c5220] hover:bg-[#683b15] text-[#fbf7ee] flex items-center justify-center text-sm shadow-xl transition-transform hover:scale-110 cursor-pointer z-30">
                <i class="fa-solid fa-xmark"></i>
            </button>
            
            <!-- Royal Wax Seal & Title Header -->
            <div class="flex flex-col items-center text-center mb-6">
                <!-- Embossed Crimson Wax Seal -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-tr from-[#540813] via-[#800020] to-[#c9184a] border-4 border-[#d4af37] shadow-2xl flex items-center justify-center text-amber-200 text-2xl sm:text-3xl mb-3 transform hover:rotate-12 transition-transform">
                    @if($ann['published_by_role'] === 'superadmin')
                        <span>👑</span>
                    @elseif($ann['published_by_role'] === 'cashier')
                        <span>💼</span>
                    @else
                        <span>🎖️</span>
                    @endif
                </div>

                <span class="text-[11px] sm:text-xs font-black tracking-widest uppercase font-serif text-[#7c4a1e] block">
                    <span data-lang-bn>উপলব্ধি সমবায় সমিতি কার্যনির্বাহী সংসদ &bull; রাজকীয় সমবায় ফরমান</span>
                    <span data-lang-en style="display:none;">Upolobdi Somobay Somiti &bull; Royal Executive Proclamation</span>
                </span>
                <div class="text-[#8c5220] text-xs sm:text-sm my-1 font-serif">❦ ════════════ •⊰ 📜 ⊱• ════════════ ❦</div>
                <span class="text-[11px] text-[#7c4a1e] font-mono font-bold">
                    <span data-lang-bn>📜 জারি তারিখ: {{ $ann['announcement_date'] }} &bull; ফরমান নং: USS-DEC-00{{ $loop->iteration }}</span>
                    <span data-lang-en style="display:none;">📜 Date: {{ $ann['announcement_date'] }} &bull; Decree No: USS-DEC-00{{ $loop->iteration }}</span>
                </span>
            </div>

            <!-- Decree Title & Manuscript Body -->
            <div class="space-y-4 text-[#2c1810] my-6 font-serif">
                <h3 class="text-2xl sm:text-3xl font-black text-center text-[#5c1d1d] font-bangla leading-snug tracking-tight">
                    <span data-lang-bn>{{ $ann['title_bn'] }}</span>
                    <span data-lang-en style="display:none;">{{ $ann['title_en'] ?: $ann['title_bn'] }}</span>
                </h3>

                <div class="p-6 rounded-2xl bg-[#8c5220]/5 border-2 border-[#8c5220]/30 text-sm sm:text-base leading-relaxed text-justify font-bangla font-medium">
                    <span data-lang-bn>{{ $ann['body_bn'] }}</span>
                    <span data-lang-en style="display:none;">{{ $ann['body_en'] ?: $ann['body_bn'] }}</span>
                </div>
            </div>

            <!-- Decree Bottom: Signoff at Right & Creator Sharing at Left -->
            <div class="pt-6 border-t-2 border-[#8c5220]/30 flex flex-col sm:flex-row items-center sm:items-end justify-between gap-6">
                
                <!-- Creator-Only Social Sharing Toolbar (Left side) -->
                <div class="w-full sm:w-auto">
                    @if($isCreator)
                        <div class="space-y-1.5 text-left">
                            <span class="text-[11px] font-bold text-[#7c4a1e] font-bangla block flex items-center gap-1">
                                <i class="fa-solid fa-bullhorn text-amber-700"></i>
                                <span data-lang-bn>ফরমানটি প্রচার করুন (প্রদানকারী হিসেবে):</span>
                                <span data-lang-en style="display:none;">Broadcast Decree (Creator Only):</span>
                            </span>
                            <div class="flex items-center gap-2 flex-wrap">
                                <!-- Facebook -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($annShareUrl) }}&quote={{ $annShareText }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-[#1877f2] hover:bg-[#166fe5] text-white text-xs font-bold flex items-center gap-1.5 shadow-md transition-transform hover:scale-105">
                                    <i class="fa-brands fa-facebook"></i>
                                    <span>Facebook</span>
                                </a>
                                <!-- Messenger -->
                                <a href="fb-messenger://share/?link={{ urlencode($annShareUrl) }}&app_id=291494419142" target="_blank" class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-[#0084ff] to-[#a033ff] hover:opacity-90 text-white text-xs font-bold flex items-center gap-1.5 shadow-md transition-transform hover:scale-105">
                                    <i class="fa-brands fa-facebook-messenger"></i>
                                    <span>Messenger</span>
                                </a>
                                <!-- WhatsApp -->
                                <a href="https://api.whatsapp.com/send?text={{ $annShareText }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-[#25d366] hover:bg-[#20bd5a] text-white text-xs font-bold flex items-center gap-1.5 shadow-md transition-transform hover:scale-105">
                                    <i class="fa-brands fa-whatsapp"></i>
                                    <span>WhatsApp</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-[11px] text-[#8c5220] font-serif italic text-left">
                            <span data-lang-bn>※ কার্যনির্বাহী সংসদ কর্তৃক জারিকৃত সর্বসম্মত নির্দেশনা</span>
                            <span data-lang-en style="display:none;">※ Official resolution enacted by the Executive Council</span>
                        </div>
                    @endif
                </div>

                <!-- Bottom Right: King's Signature & Designation -->
                <div class="text-right space-y-1 sm:pl-4 border-l-0 sm:border-l border-[#8c5220]/30">
                    <div class="inline-block text-right">
                        <span class="text-xs font-serif italic text-[#8c5220] block">
                            <span data-lang-bn>আদিষ্ট হয়ে ঘোষণাকারী —</span>
                            <span data-lang-en style="display:none;">By Royal Command —</span>
                        </span>
                        <span class="text-base sm:text-lg font-black text-[#5c1d1d] font-bangla block">
                            ✍️ {{ $ann['published_by_name'] }}
                        </span>
                        <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-[#8c5220]/15 border border-[#8c5220]/40 text-xs font-bold text-[#5c1d1d] font-bangla mt-0.5">
                            <span>{{ $ann['published_by_designation_bn'] }}</span>
                            <span class="text-[10px] text-[#8c5220] font-sans">({{ $ann['published_by_designation_en'] }})</span>
                        </div>
                        <span class="text-[10px] text-[#8c5220] block font-serif mt-1">
                            <span data-lang-bn>উপলব্ধি সমবায় সমিতি সংসদীয় সিলমোহর</span>
                            <span data-lang-en style="display:none;">USS Official Seal of Governance</span>
                        </span>
                    </div>
                </div>

            </div>

            <!-- Action / Accept & Next Button -->
            <div class="mt-6 pt-4 border-t border-[#8c5220]/25 flex items-center justify-end gap-3">
                <button onclick="closeAndNextAnnouncement({{ $aIndex }}, '{{ $annId }}')" class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-gradient-to-r from-[#8c5220] to-[#5c270d] hover:from-[#754419] hover:to-[#4a1f0b] text-amber-100 font-bold text-xs sm:text-sm shadow-2xl flex items-center justify-center gap-2 transition-transform hover:scale-105 cursor-pointer">
                    <span data-lang-bn>ফরমান গ্রহণ ও বন্ধ করুন (২৪ ঘণ্টার জন্য স্থগিত)</span>
                    <span data-lang-en style="display:none;">Acknowledge & Next (Dismiss 24h)</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>

        </div>

        <!-- Bottom Wooden Cylinder Roller / Stick with Golden Brass Knobs -->
        <div class="w-full flex items-center justify-center -mt-2 z-20">
            <div class="w-7 h-9 sm:w-9 sm:h-11 rounded-l-full bg-gradient-to-r from-[#4a1f0b] via-[#d4af37] to-[#8c5220] border-2 border-[#5c270d] shadow-2xl flex-shrink-0"></div>
            <div class="flex-1 h-7 sm:h-9 bg-gradient-to-b from-[#3a1503] via-[#8b4513] to-[#2e0f02] border-y-2 border-[#d4af37]/70 shadow-2xl relative overflow-hidden">
                <div class="absolute inset-0 bg-[repeating-linear-gradient(90deg,transparent,transparent_30px,rgba(0,0,0,0.3)_30px,rgba(0,0,0,0.3)_32px)]"></div>
            </div>
            <div class="w-7 h-9 sm:w-9 sm:h-11 rounded-r-full bg-gradient-to-l from-[#4a1f0b] via-[#d4af37] to-[#8c5220] border-2 border-[#5c270d] shadow-2xl flex-shrink-0"></div>
        </div>

    </div>
</div>
@endforeach

<!-- 2. Public Due Warning Modal (Visually Distinct Modern Card with Sticky Accessible Header) -->
@if(!empty($settings['due_warning_modal_enabled']) && count($overdueMembers) > 0)
<div id="publicDueWarningModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-gradient-to-b from-slate-900 via-slate-900 to-amber-950/50 border-2 border-amber-500/60 rounded-3xl max-w-2xl w-full shadow-2xl shadow-amber-950/70 relative animate-float max-h-[88vh] overflow-y-auto flex flex-col">
        
        <!-- Sticky Top Header with Always-Accessible Close Button -->
        <div class="sticky top-0 z-30 p-6 pb-4 bg-slate-900/95 backdrop-blur-md border-b border-amber-500/30 flex items-center justify-between">
            <div class="flex items-center gap-3 text-amber-400">
                <div class="w-11 h-11 rounded-2xl bg-amber-500/20 border border-amber-500/40 flex items-center justify-center text-xl animate-pulse flex-shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 class="text-lg sm:text-xl font-extrabold text-white font-bangla">
                        <span data-lang-bn>⚠️ মাসিক কিস্তি বকেয়া সতর্কতা নোটিশ</span>
                        <span data-lang-en style="display:none;">⚠️ Overdue Installment Notice</span>
                    </h3>
                    <p class="text-xs text-amber-300/80 font-bangla">
                        <span data-lang-bn>সমিতির নিয়মানুযায়ী বকেয়াপ্রাপ্ত সদস্যদের তালিকা</span>
                        <span data-lang-en style="display:none;">Members with pending monthly installments</span>
                    </p>
                </div>
            </div>

            <!-- Sticky Top Close 'X' Button -->
            <button onclick="dismissDueWarningModal()" title="বন্ধ করুন (২৪ ঘণ্টার জন্য স্থগিত)" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center text-sm shadow-md transition-colors flex-shrink-0 cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Full Unconstrained Member List (Card scrolls smoothly as a whole) -->
        <div class="p-6 space-y-3">
            @foreach($overdueMembers as $om)
            <div class="flex items-center justify-between p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/25 hover:bg-amber-500/20 transition-colors">
                <div class="flex items-center gap-3.5">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl overflow-hidden border-2 border-amber-500/50 flex-shrink-0 shadow-md bg-slate-800">
                        <img src="{{ $om['avatar'] ?? '/assets/images/avatar-default.svg' }}" alt="{{ $om['name'] }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h5 class="text-sm sm:text-base font-bold text-white font-bangla">
                                <span data-lang-bn>{{ $om['bangla_name'] }}</span>
                                <span data-lang-en style="display:none;">{{ $om['name'] }}</span>
                            </h5>
                            <span class="px-2 py-0.5 rounded-md bg-amber-500/25 text-amber-300 text-[10px] font-mono font-bold">{{ $om['net_due_months'] }} মাস বকেয়া</span>
                        </div>
                        <p class="text-xs text-amber-200/90 font-bangla mt-0.5">
                            <span data-lang-bn>{{ $om['range_bn'] }}</span>
                            <span data-lang-en style="display:none;">{{ $om['range_en'] }}</span>
                        </p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 pl-2">
                    <span class="px-3 py-1.5 rounded-xl bg-rose-500/20 text-rose-300 border border-rose-500/40 text-xs sm:text-sm font-black font-mono shadow">
                        {{ number_format($om['total_due_amount']) }} BDT
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Bottom Action Bar & 24h Dismiss Button -->
        <div class="p-6 pt-4 bg-slate-900/95 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
            <p class="text-xs text-slate-400 font-bangla text-center sm:text-left">
                <span data-lang-bn>📢 নিয়মিত কিস্তি পরিশোধ করে সমিতির মূলধন প্রবাহ সচল রাখুন।</span>
                <span data-lang-en style="display:none;">📢 Please clear overdue installments to maintain active fund balance.</span>
            </p>
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <button onclick="dismissDueWarningModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-colors cursor-pointer">
                    <span data-lang-bn>বন্ধ করুন (২৪ ঘণ্টা)</span>
                    <span data-lang-en style="display:none;">Dismiss (24h)</span>
                </button>
                <button onclick="openLoginModal()" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-extrabold shadow-lg transition-transform hover:scale-105 cursor-pointer">
                    <span data-lang-bn>লগইন করে পরিশোধ করুন</span>
                    <span data-lang-en style="display:none;">Login to Pay</span>
                </button>
            </div>
        </div>

    </div>
</div>
@endif



<!-- Hero Section with 3D Animated USS Emblem & Constellation -->
<section id="overview" class="hero-bg relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28">
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-96 h-96 bg-brand-gold/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -top-10 -left-10 w-96 h-96 bg-brand-green/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-pill border border-brand-gold/30 shadow-lg">
                    <span class="w-2 h-2 rounded-full bg-brand-gold animate-ping"></span>
                    <span class="text-xs font-bold text-amber-300 tracking-wide font-bangla">
                        <span data-lang-bn>৬ বন্ধুর অটুট ভ্রাতৃত্ব ও সমবায় উদ্যোগ</span>
                        <span data-lang-en style="display:none;">Cooperative Venture of 6 Lifelong Friends</span>
                    </span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight font-bangla">
                    <span class="text-gradient-gold" data-lang-bn>{{ $settings['motto_bn'] ?? 'সত্যের পথে স্বপ্নের অভিযান' }}</span>
                    <span class="text-gradient-gold" data-lang-en style="display:none;">{{ $settings['motto_en'] ?? 'Journey of Dreams on the Path of Truth' }}</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-300 leading-relaxed max-w-2xl mx-auto lg:mx-0 font-bangla">
                    <span data-lang-bn>
                        {{ $settings['vision_desc_bn'] ?? 'সজিব মোল্লা, রুবেল মোল্লা, সাইফুল ইসলাম, কাউসার, দৌলত ও ফেরদৌস — আমরা ৬ বন্ধু নিয়মিত মাসিক কিস্তির মাধ্যমে গড়ে তুলছি আমাদের ভবিষ্যৎ যৌথ স্বপ্নের মূলধন।' }}
                    </span>
                    <span data-lang-en style="display:none;">
                        {{ $settings['vision_desc_en'] ?? 'Sajib Mulla, Rubel Mulla, Saiful Islam, Kawser, Doulot, and Ferdous — 6 lifelong friends contributing monthly installments to build a thriving fund for collective future investments.' }}
                    </span>
                </p>

                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-brand-green to-emerald-600 hover:from-emerald-500 hover:to-emerald-700 text-white font-extrabold text-sm shadow-xl shadow-emerald-950/60 hover:scale-105 transition-all flex items-center gap-2.5 glow-emerald">
                            <i class="fa-solid fa-gauge-high"></i>
                            <span data-lang-bn>সদস্য পোর্টাল খুলুন</span>
                            <span data-lang-en style="display:none;">Open Member Portal</span>
                        </a>
                    @else
                        <button onclick="openLoginModal()" class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-brand-gold to-amber-600 hover:from-amber-400 hover:to-amber-600 text-brand-navyDark font-black text-sm shadow-xl shadow-amber-950/60 hover:scale-105 transition-all flex items-center gap-2.5 cursor-pointer glow-gold">
                            <i class="fa-solid fa-arrow-right-to-bracket"></i>
                            <span data-lang-bn>সদস্য লগইন</span>
                            <span data-lang-en style="display:none;">Member Sign In</span>
                        </button>
                    @endauth

                    <a href="#projects" class="px-6 py-3.5 rounded-2xl glass-card hover:border-brand-gold/50 text-white font-bold text-sm transition-all flex items-center gap-2">
                        <i class="fa-solid fa-briefcase text-brand-gold"></i>
                        <span data-lang-bn>বিনিয়োগ প্রকল্পসমূহ</span>
                        <span data-lang-en style="display:none;">Investment Projects</span>
                    </a>
                </div>

                <div class="pt-6 grid grid-cols-3 gap-3 max-w-lg mx-auto lg:mx-0 border-t border-white/10">
                    <div class="p-4 rounded-2xl glass-card text-center tilt-card">
                        <span class="block text-2xl sm:text-3xl font-black text-brand-gold font-mono" data-counter="{{ $overview['total_members'] }}">{{ $overview['total_members'] }}</span>
                        <span class="text-[11px] text-slate-400 font-bangla block mt-0.5">
                            <span data-lang-bn>প্রতিষ্ঠাতা সদস্য</span>
                            <span data-lang-en style="display:none;">Founding Members</span>
                        </span>
                    </div>
                    <div class="p-4 rounded-2xl glass-card text-center tilt-card">
                        <span class="block text-2xl sm:text-3xl font-black text-emerald-400 font-mono" data-counter="{{ $overview['monthly_installment'] }}">{{ number_format($overview['monthly_installment']) }}</span>
                        <span class="text-[11px] text-slate-400 font-bangla block mt-0.5">
                            <span data-lang-bn>মাসিক কিস্তি (৳)</span>
                            <span data-lang-en style="display:none;">Monthly Fee (৳)</span>
                        </span>
                    </div>
                    <div class="p-4 rounded-2xl glass-card text-center tilt-card">
                        <span class="block text-2xl sm:text-3xl font-black text-sky-400 font-mono" data-counter="{{ $overview['est_year'] }}">{{ $overview['est_year'] }}</span>
                        <span class="text-[11px] text-slate-400 font-bangla block mt-0.5">
                            <span data-lang-bn>প্রতিষ্ঠাকাল</span>
                            <span data-lang-en style="display:none;">Founded</span>
                        </span>
                    </div>
                </div>

            </div>

            <!-- 3D Interactive Rotating Emblem Right Column with 6 Orbiting Brotherhood Nodes -->
            <div class="lg:col-span-5 flex flex-col items-center justify-center relative">
                <div class="relative w-80 h-80 sm:w-[420px] sm:h-[420px] flex items-center justify-center">
                    
                    <!-- Dedicated WebGL Three.js Container -->
                    <div id="three-container" class="w-full h-full cursor-grab active:cursor-grabbing rounded-full relative z-10"></div>

                    <!-- 3D Raycasting Floating Member Tooltip -->
                    <div id="three-brother-tooltip" class="absolute pointer-events-none opacity-0 transition-all duration-200 px-3.5 py-2 rounded-2xl bg-slate-950/95 border border-brand-gold/60 text-white text-xs font-bangla shadow-2xl backdrop-blur-xl z-30 flex items-center gap-2 transform -translate-x-1/2 -translate-y-full">
                        <span id="three-tooltip-icon" class="text-base">👑</span>
                        <div>
                            <span id="three-tooltip-name" class="font-bold text-amber-300 block leading-tight"></span>
                            <span id="three-tooltip-role" class="text-[10px] text-slate-400 block font-mono"></span>
                        </div>
                    </div>

                    <!-- Private Fund Badge on Public Hero (Unblurred for logged in users) -->
                    <div class="absolute -top-3 -right-2 p-3.5 rounded-2xl bg-slate-900/90 border {{ Auth::check() ? 'border-emerald-500/50 glow-emerald' : 'border-amber-500/40 glow-gold' }} backdrop-blur-xl shadow-2xl animate-float flex items-center gap-3 z-20">
                        <div class="w-9 h-9 rounded-xl {{ Auth::check() ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400' }} flex items-center justify-center text-sm">
                            <i class="fa-solid {{ Auth::check() ? 'fa-vault' : 'fa-lock' }}"></i>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block font-bangla">সংরক্ষিত সমবায় তহবিল</span>
                            @auth
                                <span class="text-xs font-black text-emerald-400 font-mono">{{ number_format($overview['total_approved_fund']) }} BDT</span>
                            @else
                                <!-- Amount strictly blurred on public view -->
                                <span class="text-xs font-black text-amber-300 font-mono select-none filter blur-[5px] pointer-events-none">•••••••• BDT</span>
                            @endauth
                        </div>
                    </div>

                    <div class="absolute -bottom-3 -left-2 p-3.5 rounded-2xl bg-slate-900/90 border border-emerald-500/40 backdrop-blur-xl shadow-2xl animate-float flex items-center gap-3 z-20" style="animation-delay: 1.5s;">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-handshake"></i>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block font-bangla">১০০% বন্ধুত্বের বন্ধন</span>
                            <span class="text-xs font-black text-emerald-300">{{ $overview['total_members'] }} জন প্রতিষ্ঠাতা ভাই</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md">
                    <i class="fa-solid fa-arrows-spin text-brand-gold animate-spin" style="animation-duration: 6s;"></i>
                    <span class="text-[11px] text-slate-300 font-bangla">
                        <span data-lang-bn>লোগো ও ভাইদের কক্ষপথ মাউস/আঙুল দিয়ে ৩ডি রূপ ঘুরিয়ে দেখুন</span>
                        <span data-lang-en style="display:none;">Interactive 3D Vault & Brotherhood Orbit — Drag to Rotate</span>
                    </span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Auto-Moving 3D Carousel of All 6 Members -->
<section id="members-carousel" class="py-16 bg-slate-950/80 border-y border-white/10 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 text-center">
        <span class="text-xs font-bold text-brand-gold uppercase tracking-widest font-mono">Founding Brothers</span>
        <h2 class="text-2xl sm:text-3xl font-black text-white font-bangla mt-1">
            <span class="text-gradient-gold" data-lang-bn>আমাদের ৬ বন্ধু ও কার্যনির্বাহী পরিষদ</span>
            <span class="text-gradient-gold" data-lang-en style="display:none;">Our 6 Founding Brothers & Committee</span>
        </h2>
        <p class="text-xs text-slate-400 font-bangla mt-1">ক্যারোসেলটি স্বয়ংক্রিয়ভাবে চলমান (কার্ডে মাউস রাখলে থামবে ও ৩ডি রূপ নেবে)</p>
    </div>

    <!-- Infinite Auto Moving Marquee Track with 3D Tilt Cards -->
    <div class="w-full overflow-hidden">
        <div class="marquee-track flex gap-6 px-4">
            @foreach(array_merge($members, $members) as $m)
            <div class="tilt-card w-72 sm:w-80 p-5 rounded-3xl glass-card flex-shrink-0 flex items-center gap-4 group">
                <div class="tilt-inner w-16 h-16 rounded-2xl overflow-hidden border-2 border-brand-gold/50 p-0.5 bg-gradient-to-tr from-brand-gold via-amber-500 to-brand-green flex-shrink-0 group-hover:scale-110 transition-transform shadow-lg">
                    <img src="{{ $m['avatar'] ?? '/assets/images/avatar-default.svg' }}" alt="{{ $m['name'] }}" class="w-full h-full object-cover rounded-xl bg-slate-900">
                </div>
                <div class="space-y-1 tilt-inner flex-1">
                    <div class="flex items-center gap-1.5">
                        @if($m['role'] === 'superadmin')
                            <span class="px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/40 text-[10px] font-bold">👑 সভাপতি</span>
                        @elseif($m['role'] === 'admin')
                            <span class="px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/40 text-[10px] font-bold">🎖️ সহ-সভাপতি</span>
                        @elseif($m['role'] === 'cashier')
                            <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-[10px] font-bold">💼 ক্যাশিয়ার</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 border border-white/10 text-[10px] font-bold">👤 কার্যনির্বাহী সদস্য</span>
                        @endif
                    </div>
                    <h4 class="text-sm font-bold text-white font-bangla group-hover:text-brand-gold transition-colors">
                        <span data-lang-bn>{{ $m['bangla_name'] }}</span>
                        <span data-lang-en style="display:none;">{{ $m['name'] }}</span>
                    </h4>
                    <p class="text-[11px] text-slate-400 font-bangla line-clamp-1">{{ $m['designation'] }}</p>
                    <p class="text-[10px] text-slate-500 font-mono flex items-center gap-1"><i class="fa-solid fa-phone text-[9px] text-brand-gold"></i>{{ $m['phone'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Financial Highlights Section (With Total Amount Blurred for Public, Unblurred for Members) -->
<section id="highlights" class="py-20 bg-slate-900/60 border-b border-white/10 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
            <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest font-mono">Transparency & Accounting</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-bangla">
                <span data-lang-bn>সমিতির মোট ফান্ড ও স্বচ্ছ হিসাব</span>
                <span data-lang-en style="display:none;">Somiti Total Fund & Transparent Ledger</span>
            </h2>
            <p class="text-sm text-slate-400 font-bangla">
                <span data-lang-bn>প্রতিটি সদস্যের প্রতিটি কিস্তির পাই-টু-পাই হিসাব ফাইলবেসড সিস্টেমে সংরক্ষিত</span>
                <span data-lang-en style="display:none;">Every single installment is recorded in our Statamic file-based system</span>
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Total Fund Card (Unblurred for Auth Users, Blurred for Public Guests) -->
            <div class="tilt-card p-6 rounded-3xl glass-card border {{ Auth::check() ? 'border-emerald-500/40 hover:border-emerald-500/70 glow-emerald' : 'border-amber-500/30 hover:border-amber-500/60 glow-gold' }} shadow-xl group relative overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold {{ Auth::check() ? 'text-emerald-400' : 'text-amber-400' }} uppercase tracking-wider font-bangla">মোট সংরক্ষিত তহবিল</span>
                    <div class="w-10 h-10 rounded-xl {{ Auth::check() ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400' }} flex items-center justify-center text-lg shadow-inner">
                        <i class="fa-solid {{ Auth::check() ? 'fa-vault' : 'fa-lock' }}"></i>
                    </div>
                </div>
                @auth
                    <h3 class="text-3xl font-black text-emerald-400 font-mono mb-2">
                        <span data-counter="{{ $overview['total_approved_fund'] }}">{{ number_format($overview['total_approved_fund']) }}</span> <span class="text-sm font-sans text-emerald-300">BDT</span>
                    </h3>
                    <p class="text-xs text-emerald-300/90 font-bangla flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                        <span>অনুমোদিত সদস্য ব্যালেন্স দৃশ্যমান</span>
                    </p>
                @else
                    <!-- Amount blurred for public guests -->
                    <h3 class="text-3xl font-black text-amber-300 font-mono mb-2 filter blur-[6px] select-none pointer-events-none">
                        •••••••• <span class="text-sm font-sans text-amber-400">BDT</span>
                    </h3>
                    <p class="text-xs text-amber-300/80 font-bangla flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-[10px]"></i>
                        <span>গোপনীয় সমবায় তহবিল (লগইন আবশ্যক)</span>
                    </p>
                @endauth
            </div>

            <div class="tilt-card p-6 rounded-3xl glass-card border border-sky-500/30 hover:border-sky-500/60 shadow-xl group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-sky-400 uppercase tracking-wider font-bangla">যাচাই প্রক্রিয়ায় কিস্তি</span>
                    <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-lg group-hover:rotate-12 transition-transform shadow-inner">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-white font-mono mb-2">
                    <span data-counter="{{ $overview['total_pending_transactions'] }}">{{ $overview['total_pending_transactions'] }}</span> <span class="text-sm font-sans text-sky-400">টি স্লিপ</span>
                </h3>
                <p class="text-xs text-slate-400 font-bangla">ক্যাশিয়ার রিভিউ কিউতে অপেক্ষমাণ</p>
            </div>

            <div class="tilt-card p-6 rounded-3xl glass-card border border-emerald-500/30 hover:border-emerald-500/60 shadow-xl group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider font-bangla">মোট সফল জমা</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg group-hover:rotate-12 transition-transform shadow-inner">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-white font-mono mb-2">
                    <span data-counter="{{ $overview['total_approved_transactions'] }}">{{ $overview['total_approved_transactions'] }}</span> <span class="text-sm font-sans text-emerald-400">টি ভাউচার</span>
                </h3>
                <p class="text-xs text-slate-400 font-bangla">সকল সদস্য কর্তৃক পরিশোধিত কিস্তির মোট সংখ্যা</p>
            </div>

            <div class="tilt-card p-6 rounded-3xl glass-card border border-purple-500/30 hover:border-purple-500/60 shadow-xl group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-purple-400 uppercase tracking-wider font-bangla">সক্রিয় সদস্য সংখ্যা</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-lg group-hover:rotate-12 transition-transform shadow-inner">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-white font-mono mb-2">
                    <span data-counter="{{ $overview['total_members'] }}">{{ $overview['total_members'] }}</span> <span class="text-sm font-sans text-purple-400">জন</span>
                </h3>
                <p class="text-xs text-slate-400 font-bangla">সজিব, রুবেল, সাইফুল, কাউসার, দৌলত, ফেরদৌস</p>
            </div>

        </div>

    </div>
</section>

<!-- Investment Projects Section (Added & Managed by President & VP) -->
<section id="projects" class="py-20 bg-slate-950 relative border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
            <span class="text-xs font-bold text-brand-gold uppercase tracking-widest font-mono">Future Investments & Ventures</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-bangla">
                <span data-lang-bn>আমাদের ভবিষ্যৎ ও চলমান বিনিয়োগ প্রকল্পসমূহ</span>
                <span data-lang-en style="display:none;">Our Investment Projects & Ventures</span>
            </h2>
            <p class="text-xs text-slate-400 font-bangla">
                <span data-lang-bn>সমিতির সঞ্চিত মূলধন দিয়ে যৌথভাবে প্রতিষ্ঠিত ও পরিকল্পনাধীন উদ্যোগসমূহ</span>
                <span data-lang-en style="display:none;">Collective investment ventures financed by our somiti capital</span>
            </p>
            @if(count($projects) > 3)
            <p class="text-[11px] text-brand-gold/80 font-bangla">ক্যারোসেলটি স্বয়ংক্রিয়ভাবে চলমান (মাউস রাখলে থামবে)</p>
            @endif
        </div>

        @if(count($projects) > 3)
        <!-- Infinite Auto Moving Carousel Track for >3 Projects -->
        <div class="w-full overflow-hidden">
            <div class="marquee-track flex gap-6 px-4">
                @foreach(array_merge($projects, $projects) as $proj)
                <div class="w-80 sm:w-96 rounded-3xl bg-slate-900 border border-white/15 overflow-hidden hover:border-brand-gold/50 shadow-2xl flex-shrink-0 flex flex-col justify-between group transition-transform hover:scale-[1.02]">
                    <div>
                        <!-- Project Image -->
                        <div class="relative h-52 overflow-hidden bg-slate-950">
                            <img src="{{ $proj['image'] }}" alt="{{ $proj['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent"></div>
                            
                            <!-- Status Badge -->
                            <div class="absolute top-4 right-4">
                                @if($proj['status'] === 'ongoing')
                                    <span class="px-3 py-1 rounded-full bg-emerald-600/90 text-white text-xs font-bold font-bangla shadow-lg flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                        <span>চলমান প্রকল্প</span>
                                    </span>
                                @elseif($proj['status'] === 'completed')
                                    <span class="px-3 py-1 rounded-full bg-blue-600/90 text-white text-xs font-bold font-bangla shadow-lg">
                                        সম্পন্ন
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-amber-600/90 text-slate-950 text-xs font-bold font-bangla shadow-lg">
                                        পরিকল্পনাধীন
                                    </span>
                                @endif
                            </div>

                            <div class="absolute bottom-3 left-4 text-xs font-mono text-emerald-300 font-bold">
                                <i class="fa-solid fa-location-dot mr-1 text-rose-400"></i>{{ $proj['location'] }}
                            </div>
                        </div>

                        <div class="p-6 space-y-3">
                            <h4 class="text-lg font-black text-white font-bangla group-hover:text-brand-gold transition-colors">
                                <span data-lang-bn>{{ $proj['title_bn'] }}</span>
                                <span data-lang-en style="display:none;">{{ $proj['title'] }}</span>
                            </h4>
                            
                            <p class="text-xs text-slate-300 leading-relaxed font-bangla line-clamp-3">
                                <span data-lang-bn>{{ $proj['description_bn'] }}</span>
                                <span data-lang-en style="display:none;">{{ $proj['description'] }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="p-6 pt-0 border-t border-white/10 mt-2">
                        <div class="grid grid-cols-2 gap-2 pt-4 text-xs font-bangla">
                            <div class="bg-white/5 p-2.5 rounded-xl border border-white/10">
                                <span class="text-[10px] text-slate-400 block">বিনিয়োগ বাজেট:</span>
                                @auth
                                    <span class="font-bold text-amber-300 font-mono">{{ $proj['target_amount'] }}</span>
                                @else
                                    <span class="font-bold text-amber-300 font-mono filter blur-[5px] select-none pointer-events-none">•••••••• BDT</span>
                                @endauth
                            </div>
                            <div class="bg-white/5 p-2.5 rounded-xl border border-white/10">
                                <span class="text-[10px] text-slate-400 block">মেয়াদকাল:</span>
                                <span class="font-bold text-white font-mono">{{ $proj['timeline'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <!-- Standard 3-column Grid for <=3 Projects -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($projects as $proj)
            <div class="rounded-3xl bg-slate-900 border border-white/15 overflow-hidden hover:border-brand-gold/50 shadow-2xl hover:scale-105 transition-all flex flex-col justify-between group">
                <div>
                    <!-- Real Online Project Image -->
                    <div class="relative h-52 overflow-hidden bg-slate-950">
                        <img src="{{ $proj['image'] }}" alt="{{ $proj['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent"></div>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4">
                            @if($proj['status'] === 'ongoing')
                                <span class="px-3 py-1 rounded-full bg-emerald-600/90 text-white text-xs font-bold font-bangla shadow-lg flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                    <span>চলমান প্রকল্প</span>
                                </span>
                            @elseif($proj['status'] === 'completed')
                                <span class="px-3 py-1 rounded-full bg-blue-600/90 text-white text-xs font-bold font-bangla shadow-lg">
                                    সম্পন্ন
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-amber-600/90 text-slate-950 text-xs font-bold font-bangla shadow-lg">
                                    পরিকল্পনাধীন
                                </span>
                            @endif
                        </div>

                        <div class="absolute bottom-3 left-4 text-xs font-mono text-emerald-300 font-bold">
                            <i class="fa-solid fa-location-dot mr-1 text-rose-400"></i>{{ $proj['location'] }}
                        </div>
                    </div>

                    <div class="p-6 space-y-3">
                        <h4 class="text-lg font-black text-white font-bangla group-hover:text-brand-gold transition-colors">
                            <span data-lang-bn>{{ $proj['title_bn'] }}</span>
                            <span data-lang-en style="display:none;">{{ $proj['title'] }}</span>
                        </h4>
                        
                        <p class="text-xs text-slate-300 leading-relaxed font-bangla line-clamp-3">
                            <span data-lang-bn>{{ $proj['description_bn'] }}</span>
                            <span data-lang-en style="display:none;">{{ $proj['description'] }}</span>
                        </p>
                    </div>
                </div>

                <div class="p-6 pt-0 border-t border-white/10 mt-2">
                    <div class="grid grid-cols-2 gap-2 pt-4 text-xs font-bangla">
                        <div class="bg-white/5 p-2.5 rounded-xl border border-white/10">
                            <span class="text-[10px] text-slate-400 block">বিনিয়োগ বাজেট:</span>
                            @auth
                                <span class="font-bold text-amber-300 font-mono">{{ $proj['target_amount'] }}</span>
                            @else
                                <span class="font-bold text-amber-300 font-mono filter blur-[5px] select-none pointer-events-none">•••••••• BDT</span>
                            @endauth
                        </div>
                        <div class="bg-white/5 p-2.5 rounded-xl border border-white/10">
                            <span class="text-[10px] text-slate-400 block">মেয়াদকাল:</span>
                            <span class="font-bold text-white font-mono">{{ $proj['timeline'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12 text-slate-500 text-xs font-bangla">
                বর্তমানে কোনো প্রকল্প অন্তর্ভুক্ত করা হয়নি। সভাপতি বা সহ-সভাপতি ড্যাশবোর্ড থেকে নতুন প্রকল্প যোগ করতে পারবেন।
            </div>
            @endforelse
        </div>
        @endif

    </div>
</section>

<!-- Masonry Gallery Section — Curated by Admin/President -->
<section id="events" class="py-20 bg-slate-950 border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
            <span class="text-xs font-bold text-brand-gold uppercase tracking-widest font-mono">Curated Photo Gallery</span>
            <h2 class="text-3xl font-extrabold text-white font-bangla">
                <span data-lang-bn>মেসনারি ফটো গ্যালারি</span>
                <span data-lang-en style="display:none;">Masonry Photo Gallery</span>
            </h2>
            <p class="text-xs text-slate-400 font-bangla">
                <span data-lang-bn>সমিতির কার্যক্রম থেকে বাছাইকৃত সেরা মুহূর্তগুলো — ছবিতে ক্লিক করে ফুল সাইজে দেখুন</span>
                <span data-lang-en style="display:none;">Best moments curated from our activities — click any image to view full size</span>
            </p>
        </div>

        @php
            $masonryImgList = array_column($masonryImages, 'image');
            $aspectVariants = [
                'aspect-[4/3] min-h-[220px]',
                'aspect-[3/4] min-h-[320px]',
                'aspect-[1/1] min-h-[260px]',
                'aspect-[16/10] min-h-[200px]',
                'aspect-[4/5] min-h-[300px]'
            ];
        @endphp

        @if(count($masonryImages) > 0)
        <!-- Pure Photo Masonry Grid with Dynamic Varied Heights & Seamless Responsiveness -->
        <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-4 space-y-4">
            @foreach($masonryImages as $mIdx => $mi)
            @php
                $variantClass = $aspectVariants[$mIdx % count($aspectVariants)];
            @endphp
            <div class="break-inside-avoid group cursor-pointer overflow-hidden rounded-3xl border border-white/10 hover:border-brand-gold/60 shadow-xl hover:shadow-brand-gold/25 transition-all hover:scale-[1.02] bg-slate-900"
                 onclick="openLightbox('{{ addslashes(json_encode($masonryImgList)) }}', {{ $mIdx }}, '')">
                <div class="w-full {{ $variantClass }} overflow-hidden relative">
                    <img src="{{ $mi['image'] }}" alt="USS Somiti Gallery" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 block"
                         loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-end p-4">
                        <span class="w-9 h-9 rounded-xl bg-brand-gold/90 text-brand-navyDark flex items-center justify-center text-sm shadow-lg">
                            <i class="fa-solid fa-expand"></i>
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-16 text-slate-500 font-bangla">
            <i class="fa-solid fa-images text-5xl mb-4 text-slate-700"></i>
            <p class="text-sm">কোনো ছবি নির্বাচিত হয়নি — অ্যাডমিন ড্যাশবোর্ড থেকে ইভেন্ট নির্বাচন করুন।</p>
        </div>
        @endif

    </div>
</section>


<!-- Interactive Live Installment & Savings Calculator with 3D Coin Visualizer -->
<section id="calculator" class="py-20 relative overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="p-8 sm:p-12 rounded-3xl glass-card border border-emerald-500/30 shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-gold/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="text-center max-w-xl mx-auto mb-10 space-y-2">
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest font-mono">Interactive Financial 3D Simulator</span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-white font-bangla">
                    <span class="text-gradient-emerald" data-lang-bn>কিস্তি ও ভবিষ্যৎ সঞ্চয় প্রজেকশন সিমুলেটর</span>
                    <span class="text-gradient-emerald" data-lang-en style="display:none;">Installment &amp; Savings Growth Simulator</span>
                </h3>
                <p class="text-xs text-slate-400 font-bangla">
                    <span data-lang-bn>মাসিক ১,০০০ টাকা কিস্তিতে আমাদের যৌথ তহবিলের ভবিষ্যৎ বৃদ্ধির ৩ডি সিমুলেশন দেখুন</span>
                    <span data-lang-en style="display:none;">Simulate total accumulated fund over time with real-time 3D coin stack</span>
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Left: Slider & Parameters -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="p-5 rounded-2xl bg-white/[0.04] border border-white/10 space-y-3">
                        <div class="flex justify-between text-xs font-bold text-slate-300 font-bangla">
                            <span>কিস্তির মেয়াদ (মাস):</span>
                            <span id="calc-months-display" class="text-brand-gold font-mono text-base font-black">12 মাস</span>
                        </div>
                        <input type="range" id="calc-months-slider" min="1" max="60" value="12" class="w-full h-2.5 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-amber-400">
                        <div class="flex justify-between text-[10px] text-slate-400 font-mono">
                            <span>১ মাস</span>
                            <span>১২ মাস (১ব.)</span>
                            <span>৩৬ মাস (৩ব.)</span>
                            <span>৬০ মাস (৫ব.)</span>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/[0.04] border border-white/10 space-y-2.5 text-xs text-slate-300 font-bangla">
                        <div class="flex justify-between">
                            <span>মাসিক কিস্তি হার:</span>
                            <span class="font-bold text-white font-mono">{{ number_format($overview['monthly_installment']) }} BDT</span>
                        </div>
                        <div class="flex justify-between">
                            <span>মোট সক্রিয় সদস্য:</span>
                            <span class="font-bold text-white font-mono">{{ $overview['total_members'] }} জন</span>
                        </div>
                        <div class="flex justify-between text-emerald-400 font-bold pt-1 border-t border-white/10">
                            <span>প্রতি মাসে যৌথ জমা:</span>
                            <span class="font-mono">{{ number_format($overview['monthly_installment'] * $overview['total_members']) }} BDT</span>
                        </div>
                    </div>
                </div>

                <!-- Center: Three.js 3D Interactive Coin Tower -->
                <div class="lg:col-span-4 flex flex-col items-center justify-center relative">
                    <div class="w-full h-64 sm:h-72 relative flex items-center justify-center rounded-2xl overflow-hidden bg-slate-950/60 border border-white/10 shadow-inner">
                        <div id="three-calc-container" class="w-full h-full cursor-grab active:cursor-grabbing"></div>
                        <div class="absolute bottom-2 text-[10px] text-amber-300/90 font-mono tracking-wider bg-slate-950/80 px-3 py-1 rounded-full border border-amber-500/30 backdrop-blur-md pointer-events-none flex items-center gap-1.5 shadow-lg">
                            <i class="fa-solid fa-coins text-amber-400"></i>
                            <span>লাইভ ৩ডি ফান্ড স্ট্যাক গ্রোথ</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Financial Outcome Cards -->
                <div class="lg:col-span-4 space-y-4">
                    <div class="tilt-card p-6 rounded-2xl glass-card border border-brand-gold/40 text-center space-y-2 shadow-xl glow-gold">
                        <span class="text-xs text-slate-400 font-bangla block">একক সদস্যের সঞ্চয় (ব্যক্তিগত):</span>
                        <h4 id="calc-single-total" class="text-3xl font-black text-amber-300 font-mono">12,000 BDT</h4>
                        <span class="text-[10px] text-amber-300/70 font-bangla block">সঞ্চিত নিজস্ব মূলধন</span>
                    </div>

                    <div class="tilt-card p-6 rounded-2xl glass-card border border-emerald-500/40 text-center space-y-2 shadow-xl glow-emerald">
                        <span class="text-xs text-emerald-300 font-bangla block">৬ বন্ধুর সম্মিলিত সমবায় তহবিল:</span>
                        <h4 id="calc-group-total" class="text-3xl sm:text-4xl font-black text-emerald-400 font-mono">72,000 BDT</h4>
                        <span class="text-[10px] text-emerald-300/70 font-bangla block">যৌথ বিনিয়োগযোগ্য মোট মূলধন</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<!-- Somiti Official Bylaws & Rules Board (Managed by Super Admin & Admin) -->
<section id="rules" class="py-20 border-t border-white/10 bg-slate-900/40 relative">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
            <span class="text-xs font-bold text-brand-gold uppercase tracking-widest font-mono">Official Somiti Bylaws</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-bangla">
                <span data-lang-bn>সমিতির বর্তমান কার্যকর নীতিমালা</span>
                <span data-lang-en style="display:none;">Current Somiti Bylaws & Rules</span>
            </h2>
            <p class="text-xs text-slate-400 font-bangla">
                <span data-lang-bn>সভাপতি ও কার্যনির্বাহী সংসদ কর্তৃক সর্বসম্মতভাবে নির্ধারিত ও হালনাগাদকৃত নিয়মাবলী</span>
                <span data-lang-en style="display:none;">Official rules and regulations enacted by the Executive Council</span>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm font-bangla">
            @foreach($rules as $rule)
            <div class="p-6 sm:p-7 rounded-3xl bg-gradient-to-br from-slate-900/90 via-slate-900 to-slate-950/90 border border-white/10 hover:border-brand-gold/50 shadow-2xl transition-all hover:scale-[1.01] space-y-4 group">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-brand-gold flex items-center justify-center text-xl group-hover:scale-110 group-hover:rotate-6 transition-transform shadow-lg">
                            <i class="fa-solid {{ $rule['icon'] ?? 'fa-shield-halved' }}"></i>
                        </div>
                        <div>
                            <span class="px-2.5 py-0.5 rounded-full bg-brand-gold/15 border border-brand-gold/30 text-amber-300 text-[11px] font-mono font-bold">
                                {{ $rule['rule_number'] }}
                            </span>
                            <h4 class="text-base sm:text-lg font-black text-white font-bangla mt-0.5 group-hover:text-brand-gold transition-colors">
                                <span data-lang-bn>{{ $rule['title_bn'] }}</span>
                                <span data-lang-en style="display:none;">{{ $rule['title_en'] ?: $rule['title_bn'] }}</span>
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 text-xs text-slate-300 leading-relaxed font-bangla">
                    <span data-lang-bn>{{ $rule['description_bn'] }}</span>
                    <span data-lang-en style="display:none;">{{ $rule['description_en'] ?: $rule['description_bn'] }}</span>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

<script>
    // 1. Calculator Logic & Synchronization with 3D Coin Stack
    let updateCalculatorCoins = null;

    const slider = document.getElementById('calc-months-slider');
    const monthsDisplay = document.getElementById('calc-months-display');
    const singleTotal = document.getElementById('calc-single-total');
    const groupTotal = document.getElementById('calc-group-total');
    const monthlyRate = {{ (int)($overview['monthly_installment'] ?? 1000) }};
    const totalMembersCount = {{ (int)($overview['total_members'] ?? 6) }};

    function updateCalculator() {
        if (!slider) return;
        const months = parseInt(slider.value, 10);
        monthsDisplay.innerText = months + ' মাস';
        
        const single = months * monthlyRate;
        const group = single * totalMembersCount;

        singleTotal.innerText = single.toLocaleString() + ' BDT';
        groupTotal.innerText = group.toLocaleString() + ' BDT';

        if (typeof updateCalculatorCoins === 'function') {
            updateCalculatorCoins(months);
        }
    }

    if (slider) {
        slider.addEventListener('input', updateCalculator);
        updateCalculator();
    }

    // 2. High-Resolution Three.js 3D Interactive Hero Scene (Vault Medallion + 6 Brotherhood Nodes)
    function initThreeDHeroScene() {
        const container = document.getElementById('three-container');
        if (!container) return;

        const tooltip = document.getElementById('three-brother-tooltip');
        const tooltipName = document.getElementById('three-tooltip-name');
        const tooltipRole = document.getElementById('three-tooltip-role');
        const tooltipIcon = document.getElementById('three-tooltip-icon');

        const width = container.clientWidth || 380;
        const height = container.clientHeight || 380;

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
        camera.position.set(0, 0, 6.2);

        const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true, powerPreference: "high-performance" });
        renderer.setSize(width, height);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        container.appendChild(renderer.domElement);

        // Lighting
        const ambientLight = new THREE.AmbientLight(0xffffff, 1.15);
        scene.add(ambientLight);

        const goldLight = new THREE.PointLight(0xf59e0b, 2.8, 40);
        goldLight.position.set(5, 5, 5);
        scene.add(goldLight);

        const emeraldLight = new THREE.PointLight(0x10b981, 2.2, 40);
        emeraldLight.position.set(-5, -4, 4);
        scene.add(emeraldLight);

        const blueLight = new THREE.PointLight(0x38bdf8, 1.2, 30);
        blueLight.position.set(0, 5, -4);
        scene.add(blueLight);

        // Central Medallion Group
        const medallionGroup = new THREE.Group();
        scene.add(medallionGroup);

        const textureLoader = new THREE.TextureLoader();
        const logoTexture = textureLoader.load('/assets/images/user-logo.jpg');

        const rimMaterial = new THREE.MeshStandardMaterial({
            color: 0xdfa028,
            metalness: 0.94,
            roughness: 0.22,
        });

        const faceMaterial = new THREE.MeshStandardMaterial({
            map: logoTexture,
            metalness: 0.2,
            roughness: 0.35,
        });

        // Embossed Coin Body
        const coinGeo = new THREE.CylinderGeometry(1.7, 1.7, 0.2, 64);
        const coinMaterials = [rimMaterial, faceMaterial, faceMaterial];
        const coin = new THREE.Mesh(coinGeo, coinMaterials);
        coin.rotation.x = Math.PI / 2;
        medallionGroup.add(coin);

        // Outer Beveled Golden Ring
        const ringGeo = new THREE.TorusGeometry(1.88, 0.045, 16, 90);
        const ringMat = new THREE.MeshStandardMaterial({
            color: 0xfbbf24,
            metalness: 0.95,
            roughness: 0.15,
        });
        const outerRing = new THREE.Mesh(ringGeo, ringMat);
        medallionGroup.add(outerRing);

        // Outer Orbiting Concentric Halos
        const haloGeo1 = new THREE.RingGeometry(2.1, 2.13, 64);
        const haloMat1 = new THREE.MeshBasicMaterial({ color: 0xf59e0b, side: THREE.DoubleSide, transparent: true, opacity: 0.35 });
        const halo1 = new THREE.Mesh(haloGeo1, haloMat1);
        medallionGroup.add(halo1);

        // 6 Founding Brothers Data & Orbiting Spheres
        const brothersData = [
            { name_bn: 'সজিব মোল্লা', name_en: 'Sajib Mulla', role: 'সভাপতি (President)', icon: '👑', color: 0xf43f5e, orbitR: 2.65, angleOffset: 0 },
            { name_bn: 'রুবেল মোল্লা', name_en: 'Rubel Mulla', role: 'সহ-সভাপতি (VP)', icon: '🎖️', color: 0x3b82f6, orbitR: 2.65, angleOffset: (Math.PI / 3) * 1 },
            { name_bn: 'সাইফুল ইসলাম', name_en: 'Saiful Islam', role: 'ক্যাশিয়ার (Cashier)', icon: '💼', color: 0x10b981, orbitR: 2.65, angleOffset: (Math.PI / 3) * 2 },
            { name_bn: 'কাউসার', name_en: 'Kawser', role: 'কার্যনির্বাহী সদস্য', icon: '👤', color: 0xf59e0b, orbitR: 2.65, angleOffset: (Math.PI / 3) * 3 },
            { name_bn: 'দৌলত', name_en: 'Doulot', role: 'কার্যনির্বাহী সদস্য', icon: '👤', color: 0xa855f7, orbitR: 2.65, angleOffset: (Math.PI / 3) * 4 },
            { name_bn: 'ফেরদৌস', name_en: 'Ferdous', role: 'কার্যনির্বাহী সদস্য', icon: '👤', color: 0x06b6d4, orbitR: 2.65, angleOffset: (Math.PI / 3) * 5 }
        ];

        const brotherNodes = [];
        const constellationLines = [];
        const sphereGeo = new THREE.SphereGeometry(0.2, 28, 28);

        brothersData.forEach((b) => {
            const mat = new THREE.MeshStandardMaterial({
                color: b.color,
                emissive: b.color,
                emissiveIntensity: 0.75,
                metalness: 0.5,
                roughness: 0.2
            });
            const mesh = new THREE.Mesh(sphereGeo, mat);
            mesh.userData = b;
            scene.add(mesh);
            brotherNodes.push(mesh);

            // Constellation line connecting node to central medallion
            const lineGeo = new THREE.BufferGeometry();
            const linePositions = new Float32Array([0, 0, 0, 0, 0, 0]);
            lineGeo.setAttribute('position', new THREE.BufferAttribute(linePositions, 3));
            const lineMat = new THREE.LineBasicMaterial({
                color: b.color,
                transparent: true,
                opacity: 0.45,
                linewidth: 1
            });
            const line = new THREE.Line(lineGeo, lineMat);
            scene.add(line);
            constellationLines.push(line);
        });

        // 220 Stardust Nebula Depth Particles
        const particleCount = 220;
        const particleGeo = new THREE.BufferGeometry();
        const particlePositions = new Float32Array(particleCount * 3);
        const particleColors = new Float32Array(particleCount * 3);

        for (let i = 0; i < particleCount * 3; i += 3) {
            particlePositions[i] = (Math.random() - 0.5) * 11;
            particlePositions[i + 1] = (Math.random() - 0.5) * 11;
            particlePositions[i + 2] = (Math.random() - 0.5) * 7;

            const isGold = Math.random() > 0.4;
            particleColors[i] = isGold ? 0.98 : 0.06;
            particleColors[i + 1] = isGold ? 0.75 : 0.85;
            particleColors[i + 2] = isGold ? 0.15 : 0.55;
        }
        particleGeo.setAttribute('position', new THREE.BufferAttribute(particlePositions, 3));
        particleGeo.setAttribute('color', new THREE.BufferAttribute(particleColors, 3));

        const particleMat = new THREE.PointsMaterial({
            size: 0.05,
            vertexColors: true,
            transparent: true,
            opacity: 0.75
        });
        const particles = new THREE.Points(particleGeo, particleMat);
        scene.add(particles);

        // Raycaster for Hovering Brother Nodes
        const raycaster = new THREE.Raycaster();
        const mouse = new THREE.Vector2(-999, -999);
        let hoveredNode = null;

        // Pointer Drag Controls with Inertia
        let isDragging = false;
        let prevPointerX = 0;
        let prevPointerY = 0;
        let targetRotationX = 0;
        let targetRotationY = 0;

        container.addEventListener('pointerdown', (e) => {
            isDragging = true;
            prevPointerX = e.clientX;
            prevPointerY = e.clientY;
            container.setPointerCapture(e.pointerId);
        });

        container.addEventListener('pointermove', (e) => {
            const rect = container.getBoundingClientRect();
            mouse.x = ((e.clientX - rect.left) / rect.width) * 2 - 1;
            mouse.y = -((e.clientY - rect.top) / rect.height) * 2 + 1;

            if (isDragging) {
                const deltaX = e.clientX - prevPointerX;
                const deltaY = e.clientY - prevPointerY;
                targetRotationY += deltaX * 0.008;
                targetRotationX += deltaY * 0.008;
                prevPointerX = e.clientX;
                prevPointerY = e.clientY;
            }

            // Raycasting check
            raycaster.setFromCamera(mouse, camera);
            const intersects = raycaster.intersectObjects(brotherNodes);
            if (intersects.length > 0) {
                const hit = intersects[0].object;
                if (hoveredNode !== hit) {
                    if (hoveredNode) hoveredNode.scale.set(1, 1, 1);
                    hoveredNode = hit;
                    hoveredNode.scale.set(1.4, 1.4, 1.4);
                }
                if (tooltip) {
                    const b = hit.userData;
                    tooltipIcon.innerText = b.icon;
                    tooltipName.innerText = (currentLang === 'bn') ? b.name_bn : b.name_en;
                    tooltipRole.innerText = b.role;
                    tooltip.style.left = (e.clientX - rect.left) + 'px';
                    tooltip.style.top = (e.clientY - rect.top - 15) + 'px';
                    tooltip.style.opacity = '1';
                }
            } else {
                if (hoveredNode) {
                    hoveredNode.scale.set(1, 1, 1);
                    hoveredNode = null;
                }
                if (tooltip) tooltip.style.opacity = '0';
            }
        });

        const onPointerEnd = (e) => {
            isDragging = false;
            try { container.releasePointerCapture(e.pointerId); } catch(err) {}
        };
        container.addEventListener('pointerup', onPointerEnd);
        container.addEventListener('pointercancel', onPointerEnd);
        container.addEventListener('pointerleave', () => {
            if (tooltip) tooltip.style.opacity = '0';
        });

        // Viewport IntersectionObserver to pause loop when offscreen
        let isVisible = true;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                isVisible = entry.isIntersecting;
            });
        }, { threshold: 0.1 });
        observer.observe(container);

        // Animation Loop
        const clock = new THREE.Clock();
        function animate() {
            requestAnimationFrame(animate);
            if (!isVisible) return;

            const elapsedTime = clock.getElapsedTime();

            if (!isDragging) {
                targetRotationY += 0.005;
            }
            medallionGroup.rotation.y = THREE.MathUtils.lerp(medallionGroup.rotation.y, targetRotationY, 0.08);
            medallionGroup.rotation.x = THREE.MathUtils.lerp(medallionGroup.rotation.x, targetRotationX + Math.sin(elapsedTime * 1.5) * 0.08, 0.08);
            medallionGroup.position.y = Math.sin(elapsedTime * 1.2) * 0.12;

            outerRing.rotation.z = elapsedTime * 0.2;
            halo1.rotation.z = -elapsedTime * 0.1;

            // Position 6 Brotherhood Orbs in 3D Elliptical Orbit
            brotherNodes.forEach((node, idx) => {
                const b = node.userData;
                const currentAngle = b.angleOffset + (elapsedTime * 0.45);
                const x = Math.cos(currentAngle) * b.orbitR;
                const z = Math.sin(currentAngle) * (b.orbitR * 0.85);
                const y = Math.sin(currentAngle * 2) * 0.45;

                node.position.set(x, y, z);

                // Update dynamic constellation line
                const line = constellationLines[idx];
                const posAttr = line.geometry.attributes.position;
                posAttr.setXYZ(0, medallionGroup.position.x, medallionGroup.position.y, medallionGroup.position.z);
                posAttr.setXYZ(1, x, y, z);
                posAttr.needsUpdate = true;
            });

            // Drift Stardust Particles
            particles.rotation.y = elapsedTime * 0.03;
            particles.rotation.x = elapsedTime * 0.015;

            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', () => {
            const newW = container.clientWidth || 380;
            const newH = container.clientHeight || 380;
            camera.aspect = newW / newH;
            camera.updateProjectionMatrix();
            renderer.setSize(newW, newH);
        });
    }

    // 3. Three.js 3D Interactive Coin Tower / Capital Growth Visualizer
    function initThreeDCalculatorScene() {
        const container = document.getElementById('three-calc-container');
        if (!container) return;

        const width = container.clientWidth || 300;
        const height = container.clientHeight || 260;

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(40, width / height, 0.1, 100);
        camera.position.set(0, 2.5, 6.2);
        camera.lookAt(0, 0.8, 0);

        const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true, powerPreference: "high-performance" });
        renderer.setSize(width, height);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        container.appendChild(renderer.domElement);

        // Lights
        const ambient = new THREE.AmbientLight(0xffffff, 1.25);
        scene.add(ambient);

        const goldPoint = new THREE.PointLight(0xf59e0b, 3.0, 30);
        goldPoint.position.set(4, 5, 4);
        scene.add(goldPoint);

        const greenPoint = new THREE.PointLight(0x10b981, 2.0, 30);
        greenPoint.position.set(-4, 3, 3);
        scene.add(greenPoint);

        // Base Podium
        const baseGeo = new THREE.CylinderGeometry(1.6, 1.8, 0.25, 48);
        const baseMat = new THREE.MeshStandardMaterial({
            color: 0x0f172a,
            metalness: 0.8,
            roughness: 0.3
        });
        const basePodium = new THREE.Mesh(baseGeo, baseMat);
        basePodium.position.y = -0.6;
        scene.add(basePodium);

        const baseRingGeo = new THREE.TorusGeometry(1.7, 0.04, 16, 64);
        const baseRingMat = new THREE.MeshBasicMaterial({ color: 0x10b981 });
        const baseRing = new THREE.Mesh(baseRingGeo, baseRingMat);
        baseRing.rotation.x = Math.PI / 2;
        baseRing.position.y = -0.48;
        scene.add(baseRing);

        // Milestone Floating Glowing Holographic Rings (1 yr, 3 yrs, 5 yrs)
        const ringMatAmber = new THREE.MeshBasicMaterial({ color: 0xf59e0b, transparent: true, opacity: 0.45 });
        const ringMatCyan = new THREE.MeshBasicMaterial({ color: 0x06b6d4, transparent: true, opacity: 0.45 });
        const ringMatEmerald = new THREE.MeshBasicMaterial({ color: 0x10b981, transparent: true, opacity: 0.55 });

        const mRingGeo = new THREE.TorusGeometry(1.4, 0.025, 16, 64);
        
        const ring1Yr = new THREE.Mesh(mRingGeo, ringMatAmber);
        ring1Yr.rotation.x = Math.PI / 2;
        ring1Yr.position.y = 0.2;
        scene.add(ring1Yr);

        const ring3Yr = new THREE.Mesh(mRingGeo, ringMatCyan);
        ring3Yr.rotation.x = Math.PI / 2;
        ring3Yr.position.y = 1.3;
        scene.add(ring3Yr);

        const ring5Yr = new THREE.Mesh(mRingGeo, ringMatEmerald);
        ring5Yr.rotation.x = Math.PI / 2;
        ring5Yr.position.y = 2.4;
        scene.add(ring5Yr);

        // Coins Stack Group
        const coinsGroup = new THREE.Group();
        scene.add(coinsGroup);

        const coinGeo = new THREE.CylinderGeometry(1.15, 1.15, 0.12, 40);
        const coinMat = new THREE.MeshStandardMaterial({
            color: 0xf59e0b,
            metalness: 0.92,
            roughness: 0.22,
        });

        const maxVisualCoins = 24;
        const coins = [];
        for (let i = 0; i < maxVisualCoins; i++) {
            const c = new THREE.Mesh(coinGeo, coinMat);
            c.position.y = -0.45 + (i * 0.13);
            c.rotation.y = (i * 0.35);
            c.visible = false;
            coinsGroup.add(c);
            coins.push(c);
        }

        // Dynamic Coins Update Function called by slider
        updateCalculatorCoins = function(months) {
            const activeCount = Math.max(1, Math.min(maxVisualCoins, Math.round((months / 60) * maxVisualCoins)));
            coins.forEach((c, idx) => {
                if (idx < activeCount) {
                    c.visible = true;
                } else {
                    c.visible = false;
                }
            });

            ring1Yr.material.opacity = (months >= 12) ? 0.9 : 0.25;
            ring3Yr.material.opacity = (months >= 36) ? 0.9 : 0.25;
            ring5Yr.material.opacity = (months >= 60) ? 1.0 : 0.25;
        };

        const initialMonths = slider ? parseInt(slider.value, 10) : 12;
        updateCalculatorCoins(initialMonths);

        // Mouse tilt on calculator canvas
        let targetRotY = 0;
        let targetRotX = 0;
        container.addEventListener('mousemove', (e) => {
            const rect = container.getBoundingClientRect();
            const nx = ((e.clientX - rect.left) / rect.width) * 2 - 1;
            const ny = -((e.clientY - rect.top) / rect.height) * 2 + 1;
            targetRotY = nx * 0.6;
            targetRotX = -ny * 0.2;
        });

        // Viewport Visibility Observer
        let isCalcVisible = true;
        const calcObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                isCalcVisible = entry.isIntersecting;
            });
        }, { threshold: 0.1 });
        calcObserver.observe(container);

        const calcClock = new THREE.Clock();
        function animateCalc() {
            requestAnimationFrame(animateCalc);
            if (!isCalcVisible) return;

            const dt = calcClock.getElapsedTime();
            coinsGroup.rotation.y = THREE.MathUtils.lerp(coinsGroup.rotation.y, targetRotY + dt * 0.35, 0.05);
            coinsGroup.rotation.x = THREE.MathUtils.lerp(coinsGroup.rotation.x, targetRotX, 0.05);

            baseRing.rotation.z = dt * 0.5;
            ring1Yr.rotation.z = -dt * 0.4;
            ring3Yr.rotation.z = dt * 0.4;
            ring5Yr.rotation.z = -dt * 0.5;

            renderer.render(scene, camera);
        }
        animateCalc();

        window.addEventListener('resize', () => {
            const newW = container.clientWidth || 300;
            const newH = container.clientHeight || 260;
            camera.aspect = newW / newH;
            camera.updateProjectionMatrix();
            renderer.setSize(newW, newH);
        });
    }

    // 4. CounterUp Animated Number Counters on Viewport Scroll
    function initCounterUp() {
        const counterElements = document.querySelectorAll('[data-counter]');
        if (!counterElements.length) return;

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = parseInt(el.getAttribute('data-counter'), 10);
                    if (!isNaN(target)) {
                        animateNumber(el, target);
                    }
                    obs.unobserve(el);
                }
            });
        }, { threshold: 0.2 });

        counterElements.forEach(el => observer.observe(el));

        function animateNumber(el, target) {
            const duration = 1500;
            const startTime = performance.now();

            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const easeOut = 1 - Math.pow(1 - progress, 3);
                const current = Math.round(target * easeOut);
                el.innerText = current.toLocaleString();

                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    el.innerText = target.toLocaleString();
                }
            }
            requestAnimationFrame(update);
        }
    }

    // Announcement & Due Modal Chaining Controller with 24h Cookie Expiration
    function closeAndNextAnnouncement(currentIdx, annId) {
        if (annId) {
            dismissModalFor24Hours('ann_' + annId);
        }

        const currentModal = document.getElementById('royalAnnouncementModal-' + currentIdx);
        if (currentModal) {
            currentModal.classList.add('hidden');
            currentModal.classList.remove('flex');
        }

        let nextIdx = currentIdx + 1;
        let foundNext = false;

        while (true) {
            const nextModal = document.getElementById('royalAnnouncementModal-' + nextIdx);
            if (!nextModal) break;
            const nextAnnId = nextModal.getAttribute('data-ann-id');
            if (!isModalDismissed('ann_' + nextAnnId)) {
                nextModal.classList.remove('hidden');
                nextModal.classList.add('flex');
                foundNext = true;
                break;
            }
            nextIdx++;
        }

        if (!foundNext) {
            showDueWarningModalIfNotDismissed();
        }
    }

    function dismissDueWarningModal() {
        dismissModalFor24Hours('due_alert');
        const dueModal = document.getElementById('publicDueWarningModal');
        if (dueModal) {
            dueModal.classList.add('hidden');
            dueModal.classList.remove('flex');
        }
    }

    function showDueWarningModalIfNotDismissed() {
        if (!isModalDismissed('due_alert')) {
            const dueModal = document.getElementById('publicDueWarningModal');
            if (dueModal) {
                dueModal.classList.remove('hidden');
                dueModal.classList.add('flex');
            }
        }
    }

    function initModalPriorityChain() {
        const allAnnModals = document.querySelectorAll('.royal-announcement-modal');
        let openedAnnouncement = false;

        for (let i = 0; i < allAnnModals.length; i++) {
            const m = allAnnModals[i];
            const annId = m.getAttribute('data-ann-id');
            if (!isModalDismissed('ann_' + annId)) {
                m.classList.remove('hidden');
                m.classList.add('flex');
                openedAnnouncement = true;
                break;
            }
        }

        if (!openedAnnouncement) {
            showDueWarningModalIfNotDismissed();
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        initThreeDHeroScene();
        initThreeDCalculatorScene();
        initCounterUp();
        initModalPriorityChain();
    });
</script>
@endpush
