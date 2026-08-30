<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>মানি রসিদ ({{ $targetPayment['receipt_number'] ?? 'USS-RECEIPT' }}) | {{ $settings['somiti_name_bn'] ?? 'উপলব্ধি সমবায় সমিতি' }}</title>
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
    <div class="max-w-3xl mx-auto w-full mb-6 flex items-center justify-between gap-3 no-print">
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

    <!-- Official Printable Money Receipt Card -->
    <div class="max-w-3xl mx-auto w-full receipt-card bg-slate-900 border border-amber-500/40 rounded-3xl p-6 sm:p-10 shadow-2xl relative overflow-hidden text-slate-200">
        
        <!-- Background Watermark -->
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none select-none">
            <img src="{{ $settings['custom_logo'] ?? '/assets/images/user-logo.jpg' }}" alt="USS Watermark" class="w-96 h-96 object-contain">
        </div>

        <!-- Receipt Header -->
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
                <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-xs font-bold font-bangla inline-block mb-1 shadow-sm">
                    <i class="fa-solid fa-certificate mr-1 text-emerald-400"></i> অফিসিয়াল মানি রসিদ
                </span>
                <span class="font-mono text-sm sm:text-base font-black text-amber-400 tracking-wider block">
                    {{ $targetPayment['receipt_number'] ?? 'USS-REC-2025-XXXX' }}
                </span>
            </div>
        </div>

        <!-- Meta Details Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs bg-white/[0.04] p-4 rounded-2xl border border-white/10 font-bangla my-6 relative z-10">
            <div>
                <span class="text-[10px] text-slate-400 block">জমার তারিখ:</span>
                <span class="font-bold text-white font-mono text-xs">{{ $targetPayment['payment_date'] ?? '-' }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 block">অনুমোদনের তারিখ:</span>
                <span class="font-bold text-emerald-400 font-mono text-xs">{{ $targetPayment['reviewed_at'] ?? $targetPayment['payment_date'] ?? '-' }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 block">পেমেন্ট মাধ্যম:</span>
                <span class="font-bold text-white uppercase text-xs">{{ $targetPayment['payment_method_label'] ?? $targetPayment['payment_method'] ?? '-' }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 block">TrxID / রেফারেন্স:</span>
                <span class="font-bold text-amber-300 font-mono text-xs truncate block" title="{{ $targetPayment['reference_number'] }}">{{ $targetPayment['reference_number'] ?? '-' }}</span>
            </div>
        </div>

        <!-- Member Info Section -->
        <div class="border border-white/10 rounded-2xl p-4 sm:p-5 bg-slate-950/60 font-bangla space-y-2.5 text-xs relative z-10 mb-6">
            <div class="flex items-center justify-between border-b border-white/10 pb-2">
                <span class="text-slate-400">সদস্যের নাম ও পদবী:</span>
                <span class="font-black text-white text-sm sm:text-base">
                    {{ $targetPayment['member_bangla_name'] ?? $targetPayment['member_name'] }} 
                    <span class="text-xs font-normal text-amber-300">({{ $targetPayment['member_role_bn'] ?? 'সদস্য' }})</span>
                </span>
            </div>
            <div class="flex items-center justify-between border-b border-white/10 pb-2">
                <span class="text-slate-400">ইমেইল ও মেম্বার আইডি:</span>
                <span class="font-mono text-slate-300 text-xs sm:text-sm">
                    {{ $targetPayment['member_email'] }} | ID: {{ $targetPayment['member_id'] ?? 'USS' }}
                </span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-slate-400">পরিশোধিত কিস্তির মাস:</span>
                <span class="font-bold text-amber-300 text-xs sm:text-sm">
                    {{ $targetPayment['payment_months'] ?: ($targetPayment['months_count'] . ' মাসের কিস্তি') }}
                </span>
            </div>
        </div>

        <!-- Amount Highlight Card -->
        <div class="bg-gradient-to-r from-emerald-950/60 via-slate-900 to-emerald-950/60 border-2 border-emerald-500/40 rounded-2xl p-5 text-center space-y-1.5 shadow-xl relative z-10 mb-6">
            <span class="text-xs text-emerald-300 font-bangla font-semibold block">পরিশোধিত মোট জমার পরিমাণ</span>
            <h3 class="text-3xl sm:text-4xl font-black text-emerald-400 font-mono tracking-tight">
                {{ number_format($targetPayment['amount']) }} BDT
            </h3>
            <p class="text-xs text-slate-300 font-bangla italic">
                কথায়: {{ $targetPayment['amount_words'] ?? 'টাকা মাত্র' }}
            </p>
        </div>

        <!-- Verification & Digital Somiti Seal Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 items-end relative z-10 border-t border-white/10">
            <div class="space-y-1 text-xs font-bangla">
                <span class="text-[10px] text-slate-400 block">যাচাইকারী ও অনুমোদনকারী:</span>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-bold text-white">{{ $targetPayment['reviewed_by'] ?: 'সাইফুল ইসলাম (ক্যাশিয়ার)' }}</span>
                </div>
                <span class="text-[10px] text-slate-500 font-mono block">
                    সত্যায়ন কোড: <span class="text-amber-300 font-bold">AUDIT-{{ strtoupper(substr(md5($targetPayment['id'] . ($targetPayment['receipt_number'] ?? '')), 0, 8)) }}</span>
                </span>
            </div>

            <div class="text-left sm:text-right">
                <div class="inline-block border-2 border-emerald-500/50 rounded-xl px-4 py-2 bg-emerald-950/50 text-center font-mono text-[10px] text-emerald-300 shadow-lg">
                    <div class="font-black uppercase tracking-wider">★ OFFICIAL AUDIT ★</div>
                    <div class="text-[9px] text-emerald-400 font-bangla font-bold">উপলব্ধি সমবায় সমিতি</div>
                    <div class="text-[8px] text-slate-400">IMMUTABLE RECORD</div>
                </div>
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="border-t border-white/10 pt-4 mt-6 text-center text-[10px] text-slate-500 font-bangla relative z-10">
            * এটি উপলব্ধি সমবায় সমিতির সফটওয়্যার-জেনারেটেড অফিশিয়াল মানি রসিদ। এর রেকর্ড ক্লাউড ডাটাবেজে স্থায়ীভাবে সংরক্ষিত।
        </div>
    </div>

    <div class="mt-6 text-center text-xs text-slate-500 no-print">
        © {{ date('Y') }} {{ $settings['somiti_name_bn'] ?? 'উপলব্ধি সমবায় সমিতি' }} &bull; All Rights Reserved
    </div>

</body>
</html>
