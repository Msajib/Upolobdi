<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Statamic\Facades\Entry;
use Statamic\Facades\Collection;

class SeedSomitiProjects extends Command
{
    protected $signature = 'somiti:seed-projects';
    protected $description = 'Seed Somiti Investment Projects';

    public function handle()
    {
        if (! Collection::findByHandle('projects')) {
            $col = Collection::make('projects');
            $col->title('Projects');
            $col->save();
        }

        $projects = [
            [
                'slug' => 'project-uss-agro-dairy',
                'title' => 'USS Agro & Organic Dairy Project',
                'title_bn' => 'উপলব্ধি এগ্রো ও অর্গানিক ডেইরি খামার',
                'status' => 'ongoing',
                'target_amount' => '৫,০০,০০০ ৳',
                'timeline' => '২০২৫ - ২০২৬',
                'location' => 'মানিকগঞ্জ, ঢাকা',
                'image' => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=800&auto=format&fit=crop&q=80',
                'description' => 'Collective agro & organic dairy farm venture to build sustainable recurring dividend revenue for the 6 brothers.',
                'description_bn' => '৬ বন্ধুর যৌথ অর্থায়নে প্রাকৃতিক ডেইরি ও অর্গানিক খামার সম্প্রসারণ প্রকল্প, যা দীর্ঘমেয়াদে স্থায়ী আয়ের উৎস নিশ্চিত করবে।',
            ],
            [
                'slug' => 'project-commercial-land',
                'title' => 'Commercial Land & Asset Investment',
                'title_bn' => 'ভবিষ্যৎ বাণিজ্যিক জমি ও স্থাবর সম্পত্তি বিনিয়োগ',
                'status' => 'planned',
                'target_amount' => '১০,০০,০০০ ৳',
                'timeline' => '২০২৬ - ২০২৭',
                'location' => 'পূর্বাচল এক্সপ্রেসওয়ে, ঢাকা',
                'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&auto=format&fit=crop&q=80',
                'description' => 'Long-term capital growth investment in commercial land plots along prime expansion zones.',
                'description_bn' => 'সমিতির দীর্ঘমেয়াদী সঞ্চয় মূলধন থেকে হাইওয়ে সংলগ্ন বাণিজ্যিক প্লট ক্রয়ের পরিকল্পনা, যা সমিতির স্থায়ী সম্পত্তিতে রূপ নেবে।',
            ],
            [
                'slug' => 'project-sme-supply-chain',
                'title' => 'SME Wholesale & Supply Chain Partnership',
                'title_bn' => 'ক্ষুদ্র এসএমই ও পাইকারি সাপ্লাই চেইন পার্টনারশিপ',
                'status' => 'ongoing',
                'target_amount' => '৩,০০,০০০ ৳',
                'timeline' => '২০২৫',
                'location' => 'ঢাকা ও চট্টগ্রাম',
                'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&auto=format&fit=crop&q=80',
                'description' => 'Fast-turnaround wholesale commodity trade and supply partnership for steady operational dividends.',
                'description_bn' => 'দ্রুত লভ্যাংশ প্রদানকারী পাইকারি ব্যবসা ও ভোগ্যপণ্য ট্রেডিং সাপ্লাই পার্টনারশিপ, যা মাসিক ভিত্তিতে সমিতির তহবিল বৃদ্ধি করছে।',
            ],
        ];

        foreach ($projects as $p) {
            $existing = Entry::query()->where('collection', 'projects')->where('slug', $p['slug'])->first();
            if (! $existing) {
                $entry = Entry::make()
                    ->collection('projects')
                    ->slug($p['slug'])
                    ->data($p);
                $entry->save();
            } else {
                $existing->data(array_merge($existing->data()->all(), $p));
                $existing->save();
            }
            $this->info("Project synced: {$p['title']}");
        }

        $this->info("Projects seeded successfully!");
    }
}
