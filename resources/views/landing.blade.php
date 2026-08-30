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



<!-- Hero Section with 3D Animated USS Emblem -->
<section id="overview" class="hero-bg relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28">
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-96 h-96 bg-brand-gold/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -top-10 -left-10 w-80 h-80 bg-brand-green/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-brand-gold animate-ping"></span>
                    <span class="text-xs font-bold text-amber-300 tracking-wide font-bangla">
                        <span data-lang-bn>বন্ধুদের অটুট ভ্রাতৃত্ব ও সমবায় উদ্যোগ</span>
                        <span data-lang-en style="display:none;">Cooperative Venture of Lifelong Friends</span>
                    </span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight font-bangla">
                    <span data-lang-bn>{{ $settings['motto_bn'] ?? 'সত্যের পথে স্বপ্নের অভিযান' }}</span>
                    <span data-lang-en style="display:none;">{{ $settings['motto_en'] ?? 'Journey of Dreams on the Path of Truth' }}</span>
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
                        <a href="{{ route('dashboard') }}" class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-brand-green to-emerald-600 hover:from-emerald-500 hover:to-emerald-700 text-white font-extrabold text-sm shadow-xl shadow-emerald-950/50 hover:scale-105 transition-all flex items-center gap-2.5">
                            <i class="fa-solid fa-gauge-high"></i>
                            <span data-lang-bn>সদস্য পোর্টাল খুলুন</span>
                            <span data-lang-en style="display:none;">Open Member Portal</span>
                        </a>
                    @else
                        <button onclick="openLoginModal()" class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-brand-gold to-amber-600 hover:from-amber-400 hover:to-amber-600 text-brand-navyDark font-black text-sm shadow-xl shadow-amber-950/50 hover:scale-105 transition-all flex items-center gap-2.5 cursor-pointer">
                            <i class="fa-solid fa-arrow-right-to-bracket"></i>
                            <span data-lang-bn>সদস্য লগইন</span>
                            <span data-lang-en style="display:none;">Member Sign In</span>
                        </button>
                    @endauth

                    <a href="#projects" class="px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/15 text-white font-bold text-sm backdrop-blur-md transition-all flex items-center gap-2">
                        <i class="fa-solid fa-briefcase text-brand-gold"></i>
                        <span data-lang-bn>বিনিয়োগ প্রকল্পসমূহ</span>
                        <span data-lang-en style="display:none;">Investment Projects</span>
                    </a>
                </div>

                <div class="pt-6 grid grid-cols-3 gap-3 max-w-lg mx-auto lg:mx-0 border-t border-white/10">
                    <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 text-center">
                        <span class="block text-xl sm:text-2xl font-black text-brand-gold font-mono">{{ $overview['total_members'] }}</span>
                        <span class="text-[11px] text-slate-400 font-bangla">
                            <span data-lang-bn>প্রতিষ্ঠাতা সদস্য</span>
                            <span data-lang-en style="display:none;">Founding Members</span>
                        </span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 text-center">
                        <span class="block text-xl sm:text-2xl font-black text-emerald-400 font-mono">{{ number_format($overview['monthly_installment']) }} ৳</span>
                        <span class="text-[11px] text-slate-400 font-bangla">
                            <span data-lang-bn>মাসিক কিস্তি</span>
                            <span data-lang-en style="display:none;">Monthly Fee</span>
                        </span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 text-center">
                        <span class="block text-xl sm:text-2xl font-black text-sky-400 font-mono">{{ $overview['est_year'] }}</span>
                        <span class="text-[11px] text-slate-400 font-bangla">
                            <span data-lang-bn>প্রতিষ্ঠাকাল</span>
                            <span data-lang-en style="display:none;">Founded</span>
                        </span>
                    </div>
                </div>

            </div>

            <!-- 3D Interactive Rotating Emblem Right Column -->
            <div class="lg:col-span-5 flex flex-col items-center justify-center relative">
                <div class="relative w-72 h-72 sm:w-96 sm:h-96 flex items-center justify-center">
                    <div id="three-container" class="w-full h-full cursor-grab active:cursor-grabbing rounded-full"></div>

                    <!-- Private Fund Badge on Public Hero (Unblurred for logged in users) -->
                    <div class="absolute -top-3 -right-2 p-3 rounded-2xl bg-slate-900/90 border {{ Auth::check() ? 'border-emerald-500/50' : 'border-amber-500/40' }} backdrop-blur-xl shadow-2xl animate-float flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl {{ Auth::check() ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400' }} flex items-center justify-center">
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

                    <div class="absolute -bottom-3 -left-2 p-3 rounded-2xl bg-slate-900/90 border border-emerald-500/40 backdrop-blur-xl shadow-2xl animate-float flex items-center gap-3" style="animation-delay: 1.5s;">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                            <i class="fa-solid fa-handshake"></i>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block font-bangla">১০০% বন্ধুত্বের বন্ধন</span>
                            <span class="text-xs font-black text-emerald-300">{{ $overview['total_members'] }} জন সদস্য</span>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-slate-400 mt-4 flex items-center gap-1.5">
                    <i class="fa-solid fa-arrows-spin text-brand-gold animate-spin" style="animation-duration: 6s;"></i>
                    <span data-lang-bn>লোগোটি মাউস দিয়ে ঘুরিয়ে ৩ডি রূপ দেখুন</span>
                    <span data-lang-en style="display:none;">Interactive 3D Emblem — Drag or Hover to Rotate</span>
                </p>
            </div>

        </div>
    </div>
</section>

<!-- Auto-Moving 3D Carousel of All 6 Members -->
<section id="members-carousel" class="py-14 bg-slate-900/90 border-y border-white/10 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 text-center">
        <span class="text-xs font-bold text-brand-gold uppercase tracking-widest font-mono">Founding Brothers</span>
        <h2 class="text-2xl sm:text-3xl font-black text-white font-bangla mt-1">
            <span data-lang-bn>আমাদের বন্ধু ও কার্যনির্বাহী পরিষদ</span>
            <span data-lang-en style="display:none;">Our Founding Brothers & Committee</span>
        </h2>
        <p class="text-xs text-slate-400 font-bangla mt-1">ক্যারোসেলটি স্বয়ংক্রিয়ভাবে চলমান (মাউস রাখলে থামবে)</p>
    </div>

    <!-- Infinite Auto Moving Marquee Track -->
    <div class="w-full overflow-hidden">
        <div class="marquee-track flex gap-6 px-4">
            @foreach(array_merge($members, $members) as $m)
            <div class="w-64 sm:w-72 p-5 rounded-3xl bg-slate-950 border border-white/15 hover:border-brand-gold/50 shadow-2xl flex-shrink-0 flex items-center gap-4 group transition-transform hover:scale-105">
                <div class="w-16 h-16 rounded-2xl overflow-hidden border-2 border-brand-gold/40 p-0.5 bg-gradient-to-tr from-brand-gold to-brand-green flex-shrink-0 group-hover:rotate-6 transition-transform">
                    <img src="{{ $m['avatar'] ?? '/assets/images/avatar-default.svg' }}" alt="{{ $m['name'] }}" class="w-full h-full object-cover rounded-xl">
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-1.5">
                        @if($m['role'] === 'superadmin')
                            <span class="px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-300 text-[10px] font-bold">সভাপতি</span>
                        @elseif($m['role'] === 'admin')
                            <span class="px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-300 text-[10px] font-bold">সহ-সভাপতি</span>
                        @elseif($m['role'] === 'cashier')
                            <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold">ক্যাশিয়ার</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full bg-slate-700 text-slate-300 text-[10px] font-bold">সদস্য</span>
                        @endif
                    </div>
                    <h4 class="text-sm font-bold text-white font-bangla group-hover:text-brand-gold transition-colors">
                        <span data-lang-bn>{{ $m['bangla_name'] }}</span>
                        <span data-lang-en style="display:none;">{{ $m['name'] }}</span>
                    </h4>
                    <p class="text-[11px] text-slate-400 font-bangla line-clamp-1">{{ $m['designation'] }}</p>
                    <p class="text-[10px] text-slate-500 font-mono"><i class="fa-solid fa-phone mr-1"></i>{{ $m['phone'] }}</p>
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
            <div class="p-6 rounded-3xl bg-gradient-to-b from-slate-800/80 to-slate-900/90 border {{ Auth::check() ? 'border-emerald-500/40 hover:border-emerald-500/70' : 'border-amber-500/30 hover:border-amber-500/60' }} transition-all hover:scale-105 shadow-xl group relative overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold {{ Auth::check() ? 'text-emerald-400' : 'text-amber-400' }} uppercase tracking-wider font-bangla">মোট সংরক্ষিত তহবিল</span>
                    <div class="w-10 h-10 rounded-xl {{ Auth::check() ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400' }} flex items-center justify-center text-lg">
                        <i class="fa-solid {{ Auth::check() ? 'fa-vault' : 'fa-lock' }}"></i>
                    </div>
                </div>
                @auth
                    <h3 class="text-3xl font-black text-emerald-400 font-mono mb-2">
                        {{ number_format($overview['total_approved_fund']) }} <span class="text-sm font-sans text-emerald-300">BDT</span>
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

            <div class="p-6 rounded-3xl bg-gradient-to-b from-slate-800/80 to-slate-900/90 border border-sky-500/30 hover:border-sky-500/60 transition-all hover:scale-105 shadow-xl group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-sky-400 uppercase tracking-wider font-bangla">যাচাই প্রক্রিয়ায় কিস্তি</span>
                    <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-lg group-hover:rotate-12 transition-transform">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-white font-mono mb-2">{{ $overview['total_pending_transactions'] }} <span class="text-sm font-sans text-sky-400">টি স্লিপ</span></h3>
                <p class="text-xs text-slate-400 font-bangla">ক্যাশিয়ার রিভিউ কিউতে অপেক্ষমাণ</p>
            </div>

            <div class="p-6 rounded-3xl bg-gradient-to-b from-slate-800/80 to-slate-900/90 border border-emerald-500/30 hover:border-emerald-500/60 transition-all hover:scale-105 shadow-xl group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider font-bangla">মোট সফল জমা</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg group-hover:rotate-12 transition-transform">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-white font-mono mb-2">{{ $overview['total_approved_transactions'] }} <span class="text-sm font-sans text-emerald-400">টি ভাউচার</span></h3>
                <p class="text-xs text-slate-400 font-bangla">সকল সদস্য কর্তৃক পরিশোধিত কিস্তির মোট সংখ্যা</p>
            </div>

            <div class="p-6 rounded-3xl bg-gradient-to-b from-slate-800/80 to-slate-900/90 border border-purple-500/30 hover:border-purple-500/60 transition-all hover:scale-105 shadow-xl group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-purple-400 uppercase tracking-wider font-bangla">সক্রিয় সদস্য সংখ্যা</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-lg group-hover:rotate-12 transition-transform">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-white font-mono mb-2">{{ $overview['total_members'] }} <span class="text-sm font-sans text-purple-400">জন</span></h3>
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


<!-- Interactive Live Installment & Savings Calculator -->
<section id="calculator" class="py-20 relative">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-br from-slate-900 via-slate-900 to-emerald-950/30 border border-emerald-500/30 shadow-2xl">
            <div class="text-center max-w-xl mx-auto mb-10 space-y-2">
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest font-mono">Interactive Financial Tool</span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-white font-bangla">
                    <span data-lang-bn>কিস্তি ও ভবিষ্যৎ সঞ্চয় প্রজেকশন সিমুলেটর</span>
                    <span data-lang-en style="display:none;">Installment &amp; Savings Growth Simulator</span>
                </h3>
                <p class="text-xs text-slate-400 font-bangla">
                    <span data-lang-bn>মাসিক ১,০০০ টাকা কিস্তিতে আমাদের যৌথ তহবিলের ভবিষ্যৎ পরিমাণ দেখুন</span>
                    <span data-lang-en style="display:none;">Simulate total accumulated fund over time</span>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                <div class="md:col-span-6 space-y-6">
                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-300 mb-2 font-bangla">
                            <span>কিস্তির মেয়াদ (মাস):</span>
                            <span id="calc-months-display" class="text-brand-gold font-mono text-sm">12 মাস</span>
                        </div>
                        <input type="range" id="calc-months-slider" min="1" max="60" value="12" class="w-full h-2 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-amber-400">
                        <div class="flex justify-between text-[10px] text-slate-500 mt-1 font-mono">
                            <span>১ মাস</span>
                            <span>১২ মাস (১ বছর)</span>
                            <span>৩৬ মাস (৩ বছর)</span>
                            <span>৬০ মাস (৫ বছর)</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-2 text-xs text-slate-300 font-bangla">
                        <div class="flex justify-between">
                            <span>মাসিক কিস্তি হার:</span>
                            <span class="font-bold text-white font-mono">{{ number_format($overview['monthly_installment']) }} BDT</span>
                        </div>
                        <div class="flex justify-between">
                            <span>মোট সক্রিয় সদস্য:</span>
                            <span class="font-bold text-white font-mono">{{ $overview['total_members'] }} জন</span>
                        </div>
                        <div class="flex justify-between text-emerald-400 font-bold">
                            <span>প্রতি মাসে মোট জমা:</span>
                            <span class="font-mono">{{ number_format($overview['monthly_installment'] * $overview['total_members']) }} BDT</span>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-6 space-y-4">
                    <div class="p-6 rounded-2xl bg-slate-950/80 border border-brand-gold/40 text-center space-y-2 shadow-xl">
                        <span class="text-xs text-slate-400 font-bangla block">একক সদস্যের মোট সঞ্চয়:</span>
                        <h4 id="calc-single-total" class="text-3xl font-black text-amber-300 font-mono">12,000 BDT</h4>
                    </div>

                    <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-950/60 to-slate-900 border border-emerald-500/40 text-center space-y-2 shadow-xl">
                        <span class="text-xs text-emerald-300 font-bangla block">৬ বন্ধুর যৌথ মোট সমবায় তহবিল:</span>
                        <h4 id="calc-group-total" class="text-4xl font-black text-emerald-400 font-mono">72,000 BDT</h4>
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
    // 1. Calculator Logic
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
    }

    if (slider) {
        slider.addEventListener('input', updateCalculator);
        updateCalculator();
    }

    // 2. Three.js 3D Animated USS Medallion using User's Logo
    function initThreeDMedallion() {
        const container = document.getElementById('three-container');
        if (!container) return;

        const width = container.clientWidth || 360;
        const height = container.clientHeight || 360;

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
        camera.position.z = 5.2;

        const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
        renderer.setSize(width, height);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        container.appendChild(renderer.domElement);

        const ambientLight = new THREE.AmbientLight(0xffffff, 0.95);
        scene.add(ambientLight);

        const pointLight1 = new THREE.PointLight(0xf59e0b, 2.2, 50);
        pointLight1.position.set(5, 5, 5);
        scene.add(pointLight1);

        const pointLight2 = new THREE.PointLight(0x10b981, 1.8, 50);
        pointLight2.position.set(-5, -5, 5);
        scene.add(pointLight2);

        const textureLoader = new THREE.TextureLoader();
        const logoTexture = textureLoader.load('/assets/images/user-logo.jpg');

        const rimMaterial = new THREE.MeshStandardMaterial({
            color: 0xd97706,
            metalness: 0.9,
            roughness: 0.2,
        });

        const faceMaterial = new THREE.MeshStandardMaterial({
            map: logoTexture,
            metalness: 0.2,
            roughness: 0.4,
        });

        const geometry = new THREE.CylinderGeometry(1.8, 1.8, 0.15, 64);
        const materials = [rimMaterial, faceMaterial, faceMaterial];
        const coin = new THREE.Mesh(geometry, materials);
        coin.rotation.x = Math.PI / 2;
        scene.add(coin);

        const particleCount = 70;
        const particleGeo = new THREE.BufferGeometry();
        const positions = new Float32Array(particleCount * 3);

        for (let i = 0; i < particleCount * 3; i += 3) {
            positions[i] = (Math.random() - 0.5) * 8;
            positions[i+1] = (Math.random() - 0.5) * 8;
            positions[i+2] = (Math.random() - 0.5) * 6;
        }
        particleGeo.setAttribute('position', new THREE.BufferAttribute(positions, 3));

        const particleMat = new THREE.PointsMaterial({
            color: 0xf59e0b,
            size: 0.05,
            transparent: true,
            opacity: 0.7,
        });
        const particles = new THREE.Points(particleGeo, particleMat);
        scene.add(particles);

        let isDragging = false;
        let prevMouseX = 0;
        let prevMouseY = 0;
        let mouseX = 0;
        let mouseY = 0;

        container.addEventListener('mousedown', (e) => {
            isDragging = true;
            prevMouseX = e.clientX;
            prevMouseY = e.clientY;
        });

        window.addEventListener('mouseup', () => {
            isDragging = false;
        });

        container.addEventListener('mousemove', (e) => {
            const rect = container.getBoundingClientRect();
            mouseX = ((e.clientX - rect.left) / width) * 2 - 1;
            mouseY = -((e.clientY - rect.top) / height) * 2 + 1;

            if (isDragging) {
                const deltaX = e.clientX - prevMouseX;
                const deltaY = e.clientY - prevMouseY;
                coin.rotation.z += deltaX * 0.01;
                coin.rotation.x += deltaY * 0.01;
                prevMouseX = e.clientX;
                prevMouseY = e.clientY;
            }
        });

        let clock = new THREE.Clock();
        function animate() {
            requestAnimationFrame(animate);
            const elapsedTime = clock.getElapsedTime();

            if (!isDragging) {
                coin.rotation.z += 0.008;
                coin.position.y = Math.sin(elapsedTime * 1.5) * 0.1;
                coin.rotation.y = THREE.MathUtils.lerp(coin.rotation.y, mouseX * 0.4, 0.05);
            }

            particles.rotation.y = elapsedTime * 0.05;
            renderer.render(scene, camera);
        }

        animate();

        window.addEventListener('resize', () => {
            const newW = container.clientWidth || 360;
            const newH = container.clientHeight || 360;
            camera.aspect = newW / newH;
            camera.updateProjectionMatrix();
            renderer.setSize(newW, newH);
        });
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

        // Find the next non-dismissed announcement
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
            // If all announcements are closed/dismissed, show the Due Warning Modal if active and not dismissed
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
                break; // Only open the highest-priority non-dismissed announcement
            }
        }

        // If no un-dismissed announcement was found, check Due Alert modal
        if (!openedAnnouncement) {
            showDueWarningModalIfNotDismissed();
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        initThreeDMedallion();
        initModalPriorityChain();
    });
</script>
@endpush
