<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অফিসিয়াল আর্থিক খতিয়ান ও লেজার রিপোর্ট | উপলব্ধি সমবায় সমিতি</title>
    <link rel="icon" type="image/jpeg" href="{{ $settings['custom_favicon'] ?? '/assets/images/user-logo.jpg' }}">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { font-family: 'Hind Siliguri', 'Plus Jakarta+Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff !important; color: #000000 !important; }
            .page-break { page-break-after: always; }
            .sig-section { margin-top: 80px !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 min-h-screen py-8 px-4 sm:px-6">

    <!-- Top Action Bar (Hidden in Print) -->
    <div class="max-w-5xl mx-auto mb-6 flex flex-wrap items-center justify-between gap-3 no-print">
        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs flex items-center gap-2 shadow">
            <i class="fa-solid fa-arrow-left"></i>
            <span>ড্যাশবোর্ডে ফিরে যান</span>
        </a>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Filter Form -->
            <form action="{{ route('ledger.export') }}" method="GET" class="flex flex-wrap items-center gap-2 text-xs">
                <input type="month" name="from_month" value="{{ $fromMonth ?? '' }}" class="px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-800">
                <span>হতে</span>
                <input type="month" name="to_month" value="{{ $toMonth ?? '' }}" class="px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-800">
                <select name="status" class="px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-800 font-bold">
                    <option value="approved" {{ ($statusFilter ?? 'approved') === 'approved' ? 'selected' : '' }}>শুধু অনুমোদিত</option>
                    <option value="all" {{ ($statusFilter ?? '') === 'all' ? 'selected' : '' }}>সকল ট্রানজেকশন</option>
                </select>
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-white font-bold">ফিল্টার</button>
            </form>

            <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg hover:scale-105 transition-all">
                <i class="fa-solid fa-print"></i>
                <span>প্রিন্ট / PDF সংরক্ষণ</span>
            </button>
        </div>
    </div>

    <!-- Official Printable Document Sheet -->
    <div class="max-w-5xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-xl border border-slate-200">
        
        <!-- Header with Logo -->
        <div class="flex items-center justify-between border-b-2 border-emerald-600 pb-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-full border-2 border-amber-500 overflow-hidden p-0.5 bg-white shadow-md flex-shrink-0">
                    <img src="{{ $settings['custom_logo'] ?? '/assets/images/user-logo.jpg' }}" alt="USS Official Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-950 tracking-tight">
                        {{ $settings['somiti_name_bn'] ?? 'উপলব্ধি সমবায় সমিতি (USS)' }}
                    </h1>
                    <p class="text-sm font-bold text-amber-600 mt-0.5">
                        {{ $settings['motto_bn'] ?? 'সত্যের পথে স্বপ্নের অভিযান' }} — স্থাপিত {{ $settings['est_year'] ?? '২০২০' }}
                    </p>
                    <p class="text-xs text-slate-600 mt-1">
                        নিবন্ধন ও অডিট খতিয়ান &bull; {{ $settings['contact_address'] ?? 'ঢাকা, বাংলাদেশ' }} &bull; {{ $settings['contact_phone'] ?? '+880 1712-345678' }}
                    </p>
                </div>
            </div>

            <div class="text-right">
                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-xs uppercase tracking-wider block mb-1">অফিসিয়াল আর্থিক লেজার</span>
                <span class="text-xs text-slate-500 font-mono block">তারিখ: {{ date('d M, Y h:i A') }}</span>
                <span class="text-xs text-slate-600 font-bold font-mono">মুদ্রণকারী: {{ $currentUser->get('bangla_name') ?: $currentUser->get('name') }}</span>
            </div>
        </div>

        <!-- Meta Overview -->
        <div class="grid grid-cols-3 gap-4 mb-6 text-xs bg-slate-50 p-4 rounded-2xl border border-slate-200">
            <div>
                <span class="text-slate-500 block">মোট সদস্য:</span>
                <span class="font-bold text-slate-900 text-sm">{{ count($members) }} জন প্রতিষ্ঠাতা সদস্য</span>
            </div>
            <div>
                <span class="text-slate-500 block">রিপোর্ট সময়কাল:</span>
                <span class="font-bold text-slate-900 text-sm">
                    @if($fromMonth || $toMonth)
                        {{ $fromMonth ?: 'শুরু' }} হতে {{ $toMonth ?: 'বর্তমান' }}
                    @else
                        সর্বমোট কার্যক্রম (২০২৫ - বর্তমান)
                    @endif
                </span>
            </div>
            <div class="text-right">
                <span class="text-slate-500 block">মোট সংগৃহীত কিস্তি ফান্ড:</span>
                <span class="font-black text-emerald-700 text-base font-mono">{{ number_format($totalAmount) }} BDT</span>
            </div>
        </div>

        <!-- Pagination Info -->
        <div class="flex items-center justify-between mb-4 text-xs text-slate-500 no-print">
            <span>মোট রেকর্ড: <strong>{{ $totalRecords }}</strong> টি | পেজ <strong>{{ $page }}</strong> / <strong>{{ $totalPages }}</strong></span>
            <span>প্রতি পেজে: {{ $perPage }} টি রেকর্ড</span>
        </div>

        <!-- Ledger Table -->
        <div class="overflow-x-auto mb-8">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white font-bold">
                        <th class="py-2.5 px-3 rounded-l-lg">ক্র নং</th>
                        <th class="py-2.5 px-3">সদস্যের নাম ও আইডি</th>
                        <th class="py-2.5 px-3">কিস্তির মাস</th>
                        <th class="py-2.5 px-3">টাকার পরিমাণ</th>
                        <th class="py-2.5 px-3">মাধ্যম ও TrxID</th>
                        <th class="py-2.5 px-3">জমার তারিখ</th>
                        <th class="py-2.5 px-3">এন্ট্রি উৎস</th>
                        <th class="py-2.5 px-3 rounded-r-lg">যাচাইকারী</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @php $globalOffset = ($page - 1) * $perPage; @endphp
                    @forelse($pagedPayments as $index => $p)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/70' }}">
                        <td class="py-2.5 px-3 font-mono font-bold">{{ $globalOffset + $index + 1 }}</td>
                        <td class="py-2.5 px-3">
                            <span class="font-bold text-slate-900">{{ $p['member_bangla_name'] }}</span>
                            <span class="block text-[10px] text-slate-500 font-mono">{{ $p['member_id'] }}</span>
                        </td>
                        <td class="py-2.5 px-3 font-medium">{{ $p['payment_months'] ?: ($p['months_count'].' মাস') }}</td>
                        <td class="py-2.5 px-3 font-mono font-black text-emerald-700">{{ number_format($p['amount']) }} BDT</td>
                        <td class="py-2.5 px-3">
                            <span class="uppercase font-bold text-[10px] text-slate-700">{{ $p['payment_method'] }}</span>
                            <span class="block text-[10px] text-slate-500 font-mono">{{ $p['reference_number'] }}</span>
                        </td>
                        <td class="py-2.5 px-3 font-mono text-slate-600">{{ $p['payment_date'] }}</td>
                        <td class="py-2.5 px-3">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $p['entry_type'] === 'cashier' ? 'bg-blue-100 text-blue-800' : ($p['entry_type'] === 'superadmin' ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-700') }}">
                                {{ $p['entry_created_by'] }}
                            </span>
                        </td>
                        <td class="py-2.5 px-3 text-[11px] text-slate-700">{{ $p['reviewed_by'] ?: 'অপেক্ষমাণ' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-500">কোনো পেমেন্ট রেকর্ড পাওয়া যায়নি</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-100 font-black text-slate-900 border-t-2 border-slate-300">
                        <td colspan="3" class="py-3 px-3 text-right">সর্বমোট অনুমোদিত ফান্ড:</td>
                        <td colspan="5" class="py-3 px-3 font-mono text-base text-emerald-700">{{ number_format($totalAmount) }} BDT</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pagination Controls (No Print) -->
        @if($totalPages > 1)
        <div class="flex items-center justify-center gap-2 mb-8 no-print">
            @if($page > 1)
                <a href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}" class="px-3 py-1.5 rounded-lg bg-slate-800 text-white text-xs font-bold hover:bg-slate-700">
                    <i class="fa-solid fa-chevron-left"></i> আগে
                </a>
            @endif

            @for($i = 1; $i <= $totalPages; $i++)
                <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $i === $page ? 'bg-emerald-600 text-white' : 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-100' }}">{{ $i }}</a>
            @endfor

            @if($page < $totalPages)
                <a href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}" class="px-3 py-1.5 rounded-lg bg-slate-800 text-white text-xs font-bold hover:bg-slate-700">
                    পরে <i class="fa-solid fa-chevron-right"></i>
                </a>
            @endif
        </div>
        @endif

        <!-- Official Signatures Block -->
        <div class="sig-section mt-16 pt-12 border-t-2 border-slate-300 grid grid-cols-2 gap-12 text-center text-xs">
            @php
                $cashierMember = collect($members)->first(fn($m) => $m['role'] === 'cashier');
                $presidentMember = collect($members)->first(fn($m) => $m['role'] === 'superadmin');
            @endphp
            <div class="flex flex-col items-center">
                <div class="w-48 border-b-2 border-slate-900 pb-1 mb-2">
                    <span class="font-bold text-slate-800">{{ $cashierMember['bangla_name'] ?? 'সাইফুল ইসলাম' }}</span>
                </div>
                <span class="font-black text-slate-900 text-sm">ক্যাশিয়ার (Cashier)</span>
                <span class="text-slate-500 text-[11px]">{{ $settings['somiti_name_bn'] ?? 'উপলব্ধি সমবায় সমিতি' }}</span>
            </div>

            <div class="flex flex-col items-center">
                <div class="w-48 border-b-2 border-slate-900 pb-1 mb-2">
                    <span class="font-bold text-slate-800">{{ $presidentMember['bangla_name'] ?? 'সজিব মোল্লা' }}</span>
                </div>
                <span class="font-black text-slate-900 text-sm">সভাপতি (President)</span>
                <span class="text-slate-500 text-[11px]">{{ $settings['somiti_name_bn'] ?? 'উপলব্ধি সমবায় সমিতি' }}</span>
            </div>
        </div>

        <div class="mt-8 text-center text-[10px] text-slate-400 border-t border-slate-200 pt-3">
            <p>&copy; {{ date('Y') }} {{ $settings['somiti_name_en'] ?? 'Upolobdi Somobay Somiti (USS)' }}. এই অডিট ডকুমেন্টটি অফিশিয়াল নথিপত্রের অংশ হিসেবে গণ্য হবে।</p>
        </div>
    </div>

</body>
</html>
