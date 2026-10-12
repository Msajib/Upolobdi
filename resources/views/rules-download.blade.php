<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>সমিতির অফিসিয়াল গঠনতন্ত্র ও নীতিমালা | {{ $settings['somiti_name_bn'] ?? 'উপলব্ধি সমবায় সমিতি' }}</title>
    <link rel="icon" type="image/jpeg" href="{{ $settings['custom_favicon'] ?? '/assets/images/user-logo.jpg' }}">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { font-family: 'Hind Siliguri', 'Plus Jakarta Sans', sans-serif; }
        .font-bangla { font-family: 'Hind Siliguri', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff !important; color: #0f172a !important; padding: 0 !important; }
            .receipt-card { box-shadow: none !important; border: 2px solid #cbd5e1 !important; }
            .print-bg-fix { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen py-6 sm:py-10 px-3 sm:px-6 flex flex-col justify-between print-bg-fix">

    <!-- Top Action Navigation (Hidden in Print) -->
    <div class="max-w-4xl mx-auto w-full mb-6 flex items-center justify-between gap-3 no-print">
        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white font-bold text-xs flex items-center gap-2 shadow border border-white/10 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>ড্যাশবোর্ডে ফিরে যান</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs flex items-center gap-2 shadow-xl hover:scale-105 transition-all cursor-pointer">
                <i class="fa-solid fa-print"></i>
                <span>প্রিন্ট ও PDF সংরক্ষণ</span>
            </button>
        </div>
    </div>

    <!-- Official Rules Document Card (Receipt Design) -->
    <div class="max-w-4xl mx-auto w-full receipt-card bg-slate-900 border border-amber-500/40 rounded-3xl p-6 sm:p-10 shadow-2xl relative overflow-hidden text-slate-200">
        
        <!-- Background Watermark -->
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none select-none">
            <img src="{{ $settings['custom_logo'] ?? '/assets/images/user-logo.jpg' }}" alt="USS Watermark" class="w-96 h-96 object-contain">
        </div>

        <!-- Document Header -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-b-2 border-amber-500/40 pb-6 relative z-10">
            <div class="flex items-center gap-3.5 text-center sm:text-left">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white p-1 border-2 border-amber-500/60 shadow-lg flex-shrink-0">
                    <img src="{{ $settings['custom_logo'] ?? '/assets/images/user-logo.jpg' }}" alt="USS Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-white font-bangla">
                        {{ $settings['somiti_name_bn'] ?? 'উপলব্ধি সমবায় সমিতি (USS)' }}
                    </h2>
                    <p class="text-xs text-amber-400 font-bangla font-semibold">
                        {{ $settings['motto_bn'] ?? 'সত্যের পথে স্বপ্নের অভিযান' }} — স্থাপিত {{ $settings['est_year'] ?? '২০২০' }}
                    </p>
                    <p class="text-[11px] text-slate-400 font-mono mt-0.5">
                        {{ $settings['contact_address'] ?? 'ঢাকা, বাংলাদেশ' }} &bull; {{ $settings['contact_phone'] ?? '+880 1712-345678' }}
                    </p>
                </div>
            </div>

            <div class="text-center sm:text-right flex-shrink-0">
                <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/40 text-xs font-bold font-bangla inline-block mb-1 shadow-sm">
                    <i class="fa-solid fa-scroll mr-1 text-amber-400"></i> অফিসিয়াল গঠনতন্ত্র ও নীতিমালা
                </span>
                <span class="font-mono text-xs text-slate-400 block mt-0.5">
                    সর্বমোট ১৬টি কার্যকর ধারা
                </span>
                <span class="font-mono text-[10px] text-emerald-400 block mt-0.5">
                    হালনাগাদ: {{ date('d F, Y') }}
                </span>
            </div>
        </div>

        <!-- Preamble Section -->
        <div class="my-6 p-4 rounded-2xl bg-white/[0.03] border border-white/10 text-xs font-bangla leading-relaxed text-slate-300 relative z-10">
            <div class="flex items-center gap-2 text-amber-400 font-bold mb-1">
                <i class="fa-solid fa-circle-info"></i>
                <span>প্রস্তাবনা ও মূলনীতি:</span>
            </div>
            উপলব্ধি সমবায় সমিতির সকল সম্মানিত সদস্যের পারস্পরিক ঐক্য, বিশ্বস্ততা এবং আর্থিক শৃঙ্খলা রক্ষার স্বার্থে নিম্নোক্ত নীতিমালাসমূহ সর্বসম্মতভাবে প্রণীত ও কার্যকর করা হলো। প্রত্যেক সদস্য এই গঠনতান্ত্রিক নীতিমালা মান্য করতে বাধ্য থাকিবেন।
        </div>

        <!-- 16 Rules Grid -->
        <div class="space-y-3.5 relative z-10">
            @foreach($rules as $rule)
            <div class="p-4 sm:p-5 rounded-2xl bg-slate-950/70 border border-white/10 hover:border-amber-500/40 transition-colors">
                <div class="flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 font-mono font-black text-xs flex items-center justify-center flex-shrink-0 border border-amber-500/30">
                        {{ $rule['rule_number'] }}
                    </div>
                    <div class="space-y-1 flex-1 font-bangla">
                        <h4 class="text-sm sm:text-base font-bold text-white leading-snug">
                            {{ $rule['title_bn'] }}
                        </h4>
                        @if(!empty($rule['description_bn']) && $rule['description_bn'] !== $rule['title_bn'])
                        <p class="text-xs text-slate-300 leading-relaxed font-bangla pt-1 border-t border-white/5 mt-1">
                            {{ $rule['description_bn'] }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Committee Verification & Signatures -->
        <div class="border-t border-white/10 pt-8 mt-8 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center font-bangla relative z-10">
            <div class="space-y-1">
                <div class="h-10 flex items-end justify-center">
                    <span class="text-xs font-mono text-emerald-400 font-bold italic">[স্বাক্ষরিত]</span>
                </div>
                <div class="border-t border-slate-600 pt-1.5">
                    <p class="text-xs font-bold text-white">সজিব মোল্লা</p>
                    <p class="text-[10px] text-amber-400">সভাপতি</p>
                    <p class="text-[9px] text-slate-500">উপলব্ধি সমবায় সমিতি</p>
                </div>
            </div>

            <div class="space-y-1">
                <div class="h-10 flex items-end justify-center">
                    <span class="text-xs font-mono text-emerald-400 font-bold italic">[স্বাক্ষরিত]</span>
                </div>
                <div class="border-t border-slate-600 pt-1.5">
                    <p class="text-xs font-bold text-white">কাওসার আহমেদ</p>
                    <p class="text-[10px] text-amber-400">সাধারণ সম্পাদক</p>
                    <p class="text-[9px] text-slate-500">উপলব্ধি সমবায় সমিতি</p>
                </div>
            </div>

            <div class="space-y-1">
                <div class="h-10 flex items-end justify-center">
                    <span class="text-xs font-mono text-emerald-400 font-bold italic">[স্বাক্ষরিত]</span>
                </div>
                <div class="border-t border-slate-600 pt-1.5">
                    <p class="text-xs font-bold text-white">রুবেল হোসেন</p>
                    <p class="text-[10px] text-amber-400">ক্যাশিয়ার ও অর্থ সম্পাদক</p>
                    <p class="text-[9px] text-slate-500">উপলব্ধি সমবায় সমিতি</p>
                </div>
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="border-t border-white/10 pt-4 mt-6 text-center text-[10px] text-slate-500 font-bangla relative z-10">
            * এটি উপলব্ধি সমবায় সমিতির সফটওয়্যার-জেনারেটেড অফিশিয়াল গঠনতান্ত্রিক নথি। সকল তথ্য অপরিবর্তনশীলভাবে সংরক্ষিত।
        </div>
    </div>

</body>
</html>
