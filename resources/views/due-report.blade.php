<!DOCTYPE html>
<html lang="bn" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>বকেয়া কিস্তি রিপোর্ট | {{ $settings['somiti_name_bn'] ?? 'উপলব্ধি সমবায় সমিতি' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ $settings['custom_favicon'] ?? '/favicon.svg' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Cinzel:wght@600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .font-bangla { font-family: 'Hind Siliguri', sans-serif; }
        .font-royal { font-family: 'Cinzel', serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        @media print {
            body {
                background: white !important;
                color: black !important;
                font-size: 11pt !important;
            }
            .no-print {
                display: none !important;
            }
            .print-border {
                border-color: #cbd5e1 !important;
            }
            .print-bg-light {
                background-color: #f8fafc !important;
            }
            .print-text-dark {
                color: #0f172a !important;
            }
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen font-bangla antialiased selection:bg-brand-gold selection:text-slate-950">

    <!-- Top Action Bar (Screen Only) -->
    <div class="no-print sticky top-0 z-50 bg-slate-900/90 backdrop-blur-md border-b border-white/10 px-4 py-3 sm:px-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-300 hover:text-white px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>ড্যাশবোর্ডে ফিরুন</span>
            </a>

            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs shadow-lg transition">
                    <i class="fa-solid fa-print"></i>
                    <span>রিপোর্ট প্রিন্ট করুন / PDF ডাউনলোড</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Printable Document Container -->
    <main class="max-w-6xl mx-auto p-4 sm:p-8 my-4 print:my-0 print:p-2 bg-slate-900 print:bg-white border border-white/10 print:border-0 rounded-3xl print:rounded-none shadow-2xl print:shadow-none text-slate-100 print:text-slate-900">
        
        <!-- Header Section -->
        <header class="border-b-2 border-amber-500/30 print:border-slate-800 pb-6 mb-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                <div class="flex items-center gap-4">
                    <img src="{{ $settings['custom_logo'] ?? '/assets/images/logo.svg' }}" alt="Logo" class="w-16 h-16 object-contain rounded-2xl bg-slate-800/60 p-1 border border-amber-500/40 print:border-slate-300">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black text-amber-400 print:text-slate-900 tracking-wide font-bangla">
                            {{ $settings['somiti_name_bn'] ?? 'উপলব্ধি সমবায় সমিতি' }}
                        </h1>
                        <p class="text-xs text-slate-400 print:text-slate-600 font-mono tracking-wider uppercase">
                            {{ $settings['somiti_name_en'] ?? 'Upolobdi Somobay Somiti (USS)' }} &bull; Est. {{ $settings['est_year'] ?? '2020' }}
                        </p>
                        <p class="text-[11px] text-amber-300/80 print:text-slate-600 italic mt-0.5">
                            "{{ $settings['motto_bn'] ?? 'সত্যের পথে স্বপ্নের অভিযান' }}"
                        </p>
                    </div>
                </div>

                <div class="text-right sm:text-right text-xs space-y-1">
                    <div class="inline-block px-3 py-1 rounded-lg bg-rose-500/20 print:bg-rose-100 border border-rose-500/30 print:border-rose-300 text-rose-300 print:text-rose-700 font-bold text-xs">
                        <i class="fa-solid fa-file-invoice-dollar mr-1"></i> কর্মকর্তা বকেয়া অডিট প্রতিবেদন
                    </div>
                    <p class="text-[11px] text-slate-400 print:text-slate-600">
                        প্রতিবেদন প্রস্তুতের তারিখ: <span class="font-mono font-bold">{{ date('d M, Y (h:i A)') }}</span>
                    </p>
                    <p class="text-[11px] text-slate-400 print:text-slate-600">
                        মুদ্রণকারী কর্মকর্তা: <span class="font-bold text-white print:text-slate-900">{{ $currentUser->get('bangla_name') ?: $currentUser->get('name') }}</span>
                    </p>
                </div>
            </div>
        </header>

        <!-- Summary Statistics Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-4 gap-3 mb-6">
            <div class="p-4 rounded-2xl bg-slate-800/80 print:bg-slate-50 border border-white/10 print:border-slate-300 text-center">
                <span class="text-[11px] text-slate-400 print:text-slate-600 block">মোট সদস্য সংখ্যা</span>
                <span class="text-xl font-black text-white print:text-slate-900 font-mono">{{ count($dueReports) }} জন</span>
            </div>
            <div class="p-4 rounded-2xl bg-rose-500/10 print:bg-rose-50 border border-rose-500/20 print:border-rose-200 text-center">
                <span class="text-[11px] text-rose-300 print:text-rose-700 block">বকেয়াদার সদস্য</span>
                <span class="text-xl font-black text-rose-400 print:text-rose-700 font-mono">{{ $totalDueMembers }} জন</span>
            </div>
            <div class="p-4 rounded-2xl bg-amber-500/10 print:bg-amber-50 border border-amber-500/20 print:border-amber-200 text-center">
                <span class="text-[11px] text-amber-300 print:text-amber-700 block">মোট বকেয়া কিস্তির সংখ্যা</span>
                <span class="text-xl font-black text-amber-400 print:text-amber-700 font-mono">{{ $totalDueMonths }} টি মাস</span>
            </div>
            <div class="p-4 rounded-2xl bg-gradient-to-br from-amber-500/20 to-brand-gold/10 print:bg-slate-100 border border-amber-500/30 print:border-slate-300 text-center">
                <span class="text-[11px] text-amber-200 print:text-slate-700 block font-bold">সর্বমোট বকেয়ার পরিমাণ</span>
                <span class="text-xl font-black text-amber-300 print:text-slate-900 font-mono">{{ number_format($totalDueAmount) }} BDT</span>
            </div>
        </section>

        <!-- Informative note -->
        <div class="p-3 mb-5 rounded-xl bg-slate-800/50 print:bg-slate-50 border border-white/10 print:border-slate-200 text-xs text-slate-300 print:text-slate-700 flex items-center justify-between">
            <div>
                <i class="fa-solid fa-circle-info text-amber-400 mr-1.5"></i>
                মাসিক কিস্তির বর্তমান হার: <span class="font-bold font-mono text-amber-300 print:text-slate-900">{{ number_format($monthlyRate) }} BDT</span> (প্রতি সদস্য)। বকেয়া হিসেব সক্রিয় কার্যকাল জানুয়ারি ২০২৫ থেকে বর্তমান মাস পর্যন্ত প্রযোজ্য।
            </div>
            <span class="text-[11px] font-mono text-slate-400 print:text-slate-500">USS-AUDIT-DUE-{{ date('Ymd') }}</span>
        </div>

        <!-- Detailed Due Table -->
        <div class="overflow-x-auto mb-8">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-800 print:bg-slate-200 text-slate-200 print:text-slate-900 border-b-2 border-amber-500/40 print:border-slate-400">
                        <th class="p-2.5 text-center font-bold">ক্র.</th>
                        <th class="p-2.5 font-bold">সদস্যের তথ্য ও আইডি</th>
                        <th class="p-2.5 font-bold">পদবী ও দায়িত্ব</th>
                        <th class="p-2.5 font-bold">মোবাইল নম্বর</th>
                        <th class="p-2.5 font-bold">বকেয়া কিস্তির সময়কাল (মাস থেকে মাস)</th>
                        <th class="p-2.5 text-center font-bold">বকেয়া মাস</th>
                        <th class="p-2.5 text-right font-bold">মোট বকেয়া (BDT)</th>
                        <th class="p-2.5 text-center font-bold">অবকাশ / স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 print:divide-slate-300">
                    @foreach($dueReports as $idx => $d)
                    <tr class="{{ $d['net_due_months'] > 0 ? 'bg-rose-500/5 print:bg-rose-50/40' : 'hover:bg-white/5 print:hover:bg-slate-50' }} transition-colors">
                        <td class="p-2.5 text-center font-mono text-slate-400 print:text-slate-600 font-bold">
                            {{ $idx + 1 }}
                        </td>
                        <td class="p-2.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg overflow-hidden border border-white/20 print:border-slate-400 flex-shrink-0">
                                    <img src="{{ $d['avatar'] }}" alt="Photo" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <span class="font-bold text-white print:text-slate-900 block">{{ $d['bangla_name'] }}</span>
                                    <span class="text-[10px] text-slate-400 print:text-slate-600 font-mono">{{ $d['member_id'] }} &bull; {{ $d['email'] }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-2.5">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $d['role'] === 'superadmin' ? 'bg-amber-500/20 text-amber-300 print:bg-amber-100 print:text-amber-800' : ($d['role'] === 'cashier' ? 'bg-blue-500/20 text-blue-300 print:bg-blue-100 print:text-blue-800' : ($d['role'] === 'admin' ? 'bg-purple-500/20 text-purple-300 print:bg-purple-100 print:text-purple-800' : 'bg-slate-700 text-slate-300 print:bg-slate-200 print:text-slate-700')) }}">
                                {{ match($d['role']) {
                                    'superadmin' => 'সভাপতি',
                                    'admin' => 'সহ-সভাপতি',
                                    'cashier' => 'ক্যাশিয়ার',
                                    default => 'সদস্য'
                                } }}
                            </span>
                        </td>
                        <td class="p-2.5 font-mono text-slate-300 print:text-slate-700 text-[11px]">
                            {{ $d['phone'] ?? 'N/A' }}
                        </td>
                        <td class="p-2.5">
                            @if($d['net_due_months'] > 0)
                                <div class="font-bold text-rose-300 print:text-rose-700 leading-snug">
                                    <i class="fa-solid fa-calendar-xmark mr-1 text-[10px] text-rose-400"></i>
                                    {{ $d['range_bn'] }}
                                </div>
                                @if(count($d['unpaid_months_bn']) > 0)
                                    <div class="text-[10px] text-slate-400 print:text-slate-500 mt-0.5 line-clamp-1">
                                        ({{ implode(', ', array_slice($d['unpaid_months_bn'], 0, 4)) }}{{ count($d['unpaid_months_bn']) > 4 ? '...' : '' }})
                                    </div>
                                @endif
                            @else
                                <span class="text-emerald-400 print:text-emerald-700 font-bold flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check"></i> সব কিস্তি পরিশোধিত
                                </span>
                            @endif
                        </td>
                        <td class="p-2.5 text-center font-mono font-bold text-sm">
                            @if($d['net_due_months'] > 0)
                                <span class="text-rose-400 print:text-rose-700">{{ $d['net_due_months'] }}</span>
                            @else
                                <span class="text-emerald-400 print:text-emerald-700">০</span>
                            @endif
                        </td>
                        <td class="p-2.5 text-right font-mono font-bold text-sm">
                            @if($d['total_due_amount'] > 0)
                                <span class="text-rose-400 print:text-rose-700">{{ number_format($d['total_due_amount']) }}</span>
                            @else
                                <span class="text-emerald-400 print:text-emerald-700">০.০০</span>
                            @endif
                        </td>
                        <td class="p-2.5 text-center">
                            @if($d['is_exempted'])
                                <span class="px-2 py-0.5 rounded-full bg-purple-500/20 print:bg-purple-100 text-purple-300 print:text-purple-700 font-bold text-[10px]">
                                    {{ $d['exemption_months'] }} মাস অবকাশপ্রাপ্ত
                                </span>
                            @elseif($d['net_due_months'] > 0)
                                <span class="px-2 py-0.5 rounded-full bg-rose-500/20 print:bg-rose-100 text-rose-300 print:text-rose-700 font-bold text-[10px]">
                                    বকেয়া রয়েছে
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 print:bg-emerald-100 text-emerald-300 print:text-emerald-700 font-bold text-[10px]">
                                    নিয়মিত
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-800 print:bg-slate-200 border-t-2 border-amber-500/40 print:border-slate-400 font-bold">
                        <td colspan="5" class="p-3 text-right font-bangla text-white print:text-slate-900">
                            সর্বমোট বকেয়া যোগফল:
                        </td>
                        <td class="p-3 text-center font-mono text-rose-400 print:text-rose-700 text-sm">
                            {{ $totalDueMonths }} মাস
                        </td>
                        <td class="p-3 text-right font-mono text-rose-400 print:text-rose-700 text-base">
                            {{ number_format($totalDueAmount) }} BDT
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Official Signatures & Seal Section (Crucial for printed audit reports) -->
        <div class="mt-12 pt-6 border-t-2 border-white/20 print:border-slate-400 grid grid-cols-3 gap-6 text-center text-xs">
            <div>
                <div class="h-14 flex items-end justify-center mb-2">
                    <span class="border-b border-dashed border-slate-500 print:border-slate-800 w-3/4"></span>
                </div>
                <p class="font-bold text-white print:text-slate-900">সাইফুল ইসলাম</p>
                <p class="text-[11px] text-slate-400 print:text-slate-600">ক্যাশিয়ার ও অর্থ সম্পাদক</p>
                <p class="text-[10px] text-slate-500 print:text-slate-500 font-mono">উপলব্ধি সমবায় সমিতি</p>
            </div>

            <div>
                <div class="h-14 flex items-end justify-center mb-2">
                    <span class="border-b border-dashed border-slate-500 print:border-slate-800 w-3/4"></span>
                </div>
                <p class="font-bold text-white print:text-slate-900">রুবেল মোল্লা</p>
                <p class="text-[11px] text-slate-400 print:text-slate-600">সহ-সভাপতি / সহ-সাধারণ সম্পাদক</p>
                <p class="text-[10px] text-slate-500 print:text-slate-500 font-mono">উপলব্ধি সমবায় সমিতি</p>
            </div>

            <div>
                <div class="h-14 flex items-end justify-center mb-2">
                    <span class="border-b border-dashed border-slate-500 print:border-slate-800 w-3/4"></span>
                </div>
                <p class="font-bold text-white print:text-slate-900">সজিব মোল্লা</p>
                <p class="text-[11px] text-slate-400 print:text-slate-600">সভাপতি ও সাধারণ সম্পাদক</p>
                <p class="text-[10px] text-slate-500 print:text-slate-500 font-mono">উপলব্ধি সমবায় সমিতি</p>
            </div>
        </div>

        <!-- Document Verification Footnote -->
        <footer class="mt-8 pt-3 border-t border-white/10 print:border-slate-200 text-center text-[10px] text-slate-500 print:text-slate-500">
            এই অডিট প্রতিবেদনটি উপলব্ধি সমবায় সমিতির কেন্দ্রীয় ডাটাবেজ থেকে প্রস্তুতকৃত। ওয়েব লিংক: <span class="font-mono">https://upolobdi.com</span> &bull; কপিরাইট &copy; {{ date('Y') }}
        </footer>

    </main>

</body>
</html>
