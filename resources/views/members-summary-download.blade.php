<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>সদস্যদের পেমেন্ট ও বকেয়া অডিট বিবরণী | {{ $settings['somiti_name_bn'] ?? 'উপলব্ধি সমবায় সমিতি' }}</title>
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
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-slate-950 font-black text-xs flex items-center gap-2 shadow-xl hover:scale-105 transition-all cursor-pointer">
                <i class="fa-solid fa-print"></i>
                <span>প্রিন্ট ও PDF সংরক্ষণ</span>
            </button>
        </div>
    </div>

    <!-- Official Members Statement Card (Receipt Design) -->
    <div class="max-w-4xl mx-auto w-full receipt-card bg-slate-900 border border-emerald-500/40 rounded-3xl p-6 sm:p-10 shadow-2xl relative overflow-hidden text-slate-200">
        
        <!-- Background Watermark -->
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none select-none">
            <img src="{{ $settings['custom_logo'] ?? '/assets/images/user-logo.jpg' }}" alt="USS Watermark" class="w-96 h-96 object-contain">
        </div>

        <!-- Statement Header -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-b-2 border-emerald-500/40 pb-6 relative z-10">
            <div class="flex items-center gap-3.5 text-center sm:text-left">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white p-1 border-2 border-emerald-500/60 shadow-lg flex-shrink-0">
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
                    <i class="fa-solid fa-file-invoice-dollar mr-1"></i> সদস্য অডিট খতিয়ান বিবরণী
                </span>
                <span class="font-mono text-xs text-slate-300 block mt-0.5">
                    প্রতিবেদন তারিখ: {{ date('d F, Y') }}
                </span>
                <span class="font-mono text-[10px] text-amber-400 block mt-0.5">
                    মাসিক কিস্তির হার: {{ number_format($monthlyAmount) }} BDT
                </span>
            </div>
        </div>

        <!-- Metric KPI Cards Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center my-6 relative z-10 font-bangla">
            <div class="p-3.5 rounded-2xl bg-emerald-950/40 border border-emerald-500/30">
                <span class="text-[10px] text-emerald-300 block font-bold">মোট সংগৃহীত কিস্তি</span>
                <span class="text-lg font-black text-white font-mono mt-0.5 block">{{ number_format($totalPaidSum) }} BDT</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-rose-950/40 border border-rose-500/30">
                <span class="text-[10px] text-rose-300 block font-bold">মোট সদস্য বকেয়া</span>
                <span class="text-lg font-black text-rose-300 font-mono mt-0.5 block">{{ number_format($totalDueSum) }} BDT</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-white/10">
                <span class="text-[10px] text-slate-400 block font-bold">মোট সদস্য সংখ্যা</span>
                <span class="text-lg font-black text-white font-mono mt-0.5 block">{{ count($membersDue) }} জন</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-amber-950/40 border border-amber-500/30">
                <span class="text-[10px] text-amber-300 block font-bold">লেজার অডিট কোড</span>
                <span class="text-xs font-mono font-bold text-amber-400 mt-1 block">AUDIT-{{ date('Ymd') }}</span>
            </div>
        </div>

        <!-- Members Summary Table -->
        <div class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-950/60 relative z-10 mb-6 font-bangla">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-white/10 text-slate-300 bg-white/5">
                        <th class="p-3">ক্রম</th>
                        <th class="p-3">সদস্যের নাম ও পদবী</th>
                        <th class="p-3">পরিশোধিত টাকা</th>
                        <th class="p-3">পরিশোধিত মাস</th>
                        <th class="p-3">বকেয়া টাকা</th>
                        <th class="p-3">বকেয়া কিস্তির বিবরণ</th>
                        <th class="p-3 text-right">অবস্থা</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($membersDue as $index => $md)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="p-3 font-mono font-bold text-slate-400">{{ $index + 1 }}</td>
                        <td class="p-3">
                            <span class="font-bold text-white block">{{ $md['bangla_name'] }}</span>
                            <span class="text-[10px] text-amber-400 block">{{ $md['designation'] ?? $md['role'] }}</span>
                            <span class="text-[10px] text-slate-500 font-mono">{{ $md['email'] }}</span>
                        </td>
                        <td class="p-3 font-mono font-bold text-emerald-400 text-sm">
                            {{ number_format($md['total_paid_amount']) }} BDT
                        </td>
                        <td class="p-3 font-mono text-slate-300">
                            {{ $md['total_paid_months'] }} মাস
                        </td>
                        <td class="p-3 font-mono font-bold {{ $md['total_due_amount'] > 0 ? 'text-rose-400' : 'text-slate-400' }}">
                            {{ number_format($md['total_due_amount']) }} BDT
                        </td>
                        <td class="p-3 text-[11px] text-slate-300">
                            @if($md['is_exempted'])
                                <span class="px-2 py-0.5 rounded bg-purple-500/20 text-purple-300 text-[10px] font-bold">
                                    {{ $md['exemption_months'] }} মাস অবকাশ মঞ্জুরকৃত
                                </span>
                            @elseif($md['total_due_amount'] > 0)
                                <span class="text-rose-300">{{ $md['range_bn'] }}</span>
                            @else
                                <span class="text-emerald-400">সব কিস্তি পরিশোধিত</span>
                            @endif
                        </td>
                        <td class="p-3 text-right">
                            @if($md['total_due_amount'] == 0)
                                <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold">
                                    পরিশোধিত
                                </span>
                            @elseif($md['is_exempted'])
                                <span class="px-2 py-0.5 rounded-full bg-purple-500/20 text-purple-300 border border-purple-500/30 text-[10px] font-bold">
                                    অবকাশপ্রাপ্ত
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30 text-[10px] font-bold">
                                    বকেয়াপ্রাপ্ত
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-white/10 bg-white/5 font-bold text-xs">
                        <td colspan="2" class="p-3 text-white">সর্বমোট হিসাব (Grand Total)</td>
                        <td class="p-3 font-mono text-emerald-400">{{ number_format($totalPaidSum) }} BDT</td>
                        <td class="p-3 font-mono text-slate-300">{{ array_sum(array_column($membersDue, 'total_paid_months')) }} মাস</td>
                        <td class="p-3 font-mono text-rose-400">{{ number_format($totalDueSum) }} BDT</td>
                        <td colspan="2" class="p-3 text-right text-slate-400 font-mono">
                            রিপোর্ট জেনারেশন: {{ $currentUser->get('bangla_name') ?: $currentUser->get('name') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Official Signatures Block -->
        <div class="border-t border-white/10 pt-8 mt-8 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center font-bangla relative z-10">
            <div class="space-y-1">
                <div class="h-10 flex items-end justify-center">
                    <span class="text-xs font-mono text-emerald-400 font-bold italic">[যাচাইকৃত]</span>
                </div>
                <div class="border-t border-slate-600 pt-1.5">
                    <p class="text-xs font-bold text-white">সজিব মোল্লা</p>
                    <p class="text-[10px] text-amber-400">সভাপতি</p>
                    <p class="text-[9px] text-slate-500">উপলব্ধি সমবায় সমিতি</p>
                </div>
            </div>

            <div class="space-y-1">
                <div class="h-10 flex items-end justify-center">
                    <span class="text-xs font-mono text-emerald-400 font-bold italic">[যাচাইকৃত]</span>
                </div>
                <div class="border-t border-slate-600 pt-1.5">
                    <p class="text-xs font-bold text-white">কাওসার আহমেদ</p>
                    <p class="text-[10px] text-amber-400">সাধারণ সম্পাদক</p>
                    <p class="text-[9px] text-slate-500">উপলব্ধি সমবায় সমিতি</p>
                </div>
            </div>

            <div class="space-y-1">
                <div class="h-10 flex items-end justify-center">
                    <span class="text-xs font-mono text-emerald-400 font-bold italic">[যাচাইকৃত]</span>
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
            * এটি উপলব্ধি সমবায় সমিতি পোর্টালের সকল সদস্যের পেমেন্ট ও বকেয়া সংক্রান্ত অফিসিয়াল অডিট খতিয়ান প্রতিবেদন।
        </div>
    </div>

</body>
</html>
