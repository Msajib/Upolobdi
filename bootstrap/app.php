<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, Request $request) {
            return back()->withErrors([
                'avatar_file' => 'ফাইলের সাইজ সার্ভার সীমার চেয়ে বেশি (সর্বোচ্চ ১০ মেগাবাইট)। অনুগ্রহ করে ছোট সাইজের ছবি আপলোড করুন।',
                'proof_image' => 'ফাইলের সাইজ সার্ভার সীমার চেয়ে বেশি (সর্বোচ্চ ১০ মেগাবাইট)। অনুগ্রহ করে ছোট সাইজের ছবি আপলোড করুন।',
                'error' => 'ফাইলের সাইজ সার্ভার সীমার চেয়ে বেশি (সর্বোচ্চ ১০ মেগাবাইট)। অনুগ্রহ করে ছোট সাইজের ফাইল আপলোড করুন।',
            ]);
        });
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
