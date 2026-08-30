<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SomitiService;
use Statamic\Facades\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected SomitiService $somitiService;

    public function __construct(SomitiService $somitiService)
    {
        $this->somitiService = $somitiService;
    }

    public function index(Request $request)
    {
        $settings = $this->somitiService->getSettings();
        $members = $this->somitiService->getAllMembers();
        $events = $this->somitiService->getEvents();
        $rules = $this->somitiService->getRules();
        $highlightImages = $this->somitiService->getHighlightImages();
        $masonryImages = $this->somitiService->getMasonryGalleryImages();
        $overview = $this->somitiService->getFinancialOverview();
        // Threshold=1: show anyone with ≥1 month due (after due date passed per settings)
        $overdueMembers = $this->somitiService->getOverdueMembers(1);
        $recentPayments = array_slice($this->somitiService->getPayments(), 0, 10);
        $projects = $this->somitiService->getProjects();
        $announcements = $this->somitiService->getAnnouncements();

        $currentUser = Auth::user();
        $userDue = null;
        if ($currentUser) {
            $memberData = $this->somitiService->getMemberByEmail($currentUser->email());
            if ($memberData) {
                $userDue = $this->somitiService->calculateDue($memberData, (int)($settings['monthly_installment'] ?? 1000));
            }
        }

        return view('landing', compact(
            'settings',
            'members',
            'events',
            'rules',
            'projects',
            'highlightImages',
            'masonryImages',
            'overview',
            'overdueMembers',
            'recentPayments',
            'currentUser',
            'userDue',
            'announcements'
        ));
    }
}
