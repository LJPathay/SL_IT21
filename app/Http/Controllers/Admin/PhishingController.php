<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhishingCampaign;
use App\Models\PhishingResult;
use App\Models\User;
use App\Services\LoggingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhishingController extends Controller
{
    /**
     * Display phishing simulation dashboard.
     */
    public function index(Request $request)
    {
        $query = PhishingCampaign::query();

        if ($request->status === 'active') {
            $query->where('status', 'active');
        } elseif ($request->status === 'completed') {
            $query->where('status', 'completed');
        } elseif ($request->status === 'draft') {
            $query->where('status', 'draft');
        }

        $campaigns = $query->orderBy('created_at', 'desc')->get();

        $activeCampaigns = $campaigns->where('status', 'active')->count();
        $totalSent = $campaigns->sum('total_sent');
        $totalClicked = $campaigns->sum('total_clicked');
        $totalReported = $campaigns->sum('total_reported');

        $avgClickRate = $totalSent > 0 ? round(($totalClicked / $totalSent) * 100, 1) : 0;
        $avgReportRate = $totalSent > 0 ? round(($totalReported / $totalSent) * 100, 1) : 0;

        return view('admin.phishing', [
            'campaigns' => $campaigns,
            'activeCampaigns' => $activeCampaigns,
            'totalSent' => $totalSent,
            'avgClickRate' => $avgClickRate,
            'avgReportRate' => $avgReportRate,
        ]);
    }

    /**
     * Store a new phishing campaign.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'template_type' => 'required|in:microsoft_login,dhl_shipping,netflix_renewal,payroll_update',
            'target_audience' => 'required|in:all,cs_dept,staff',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Campaign name is required.',
            'template_type.required' => 'Template type is required.',
            'target_audience.required' => 'Target audience is required.',
        ]);

        $campaign = PhishingCampaign::create([
            'name' => $validated['name'],
            'template_type' => $validated['template_type'],
            'target_audience' => $validated['target_audience'],
            'description' => $validated['description'] ?? null,
            'status' => 'draft',
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'phishing_campaign_created',
            'PhishingCampaign',
            $campaign->id,
            $validated,
            $request,
            'success'
        );

        return redirect()->route('admin.phishing')->with('success', 'Phishing campaign created successfully!');
    }

    /**
     * Launch a phishing campaign.
     */
    public function launch(Request $request, PhishingCampaign $campaign)
    {
        if ($campaign->status !== 'draft') {
            return redirect()->route('admin.phishing')->with('error', 'Only draft campaigns can be launched.');
        }

        // Get target users based on audience
        $targetUsers = $this->getTargetUsers($campaign->target_audience);

        if ($targetUsers->isEmpty()) {
            return redirect()->route('admin.phishing')->with('error', 'No target users found for this audience.');
        }

        // Create phishing results for all target users
        foreach ($targetUsers as $user) {
            PhishingResult::firstOrCreate([
                'campaign_id' => $campaign->id,
                'user_id' => $user->id,
            ], [
                'clicked_link' => false,
                'reported_phishing' => false,
            ]);
        }

        // Update campaign status
        $campaign->update([
            'status' => 'active',
            'sent_at' => now(),
            'total_sent' => $targetUsers->count(),
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'phishing_campaign_launched',
            'PhishingCampaign',
            $campaign->id,
            ['target_count' => $targetUsers->count()],
            $request,
            'success'
        );

        // In a real implementation, this would queue emails to be sent
        // For now, we'll simulate the campaign being active

        return redirect()->route('admin.phishing')->with('success', 'Phishing campaign launched successfully!');
    }

    /**
     * Complete a phishing campaign.
     */
    public function complete(Request $request, PhishingCampaign $campaign)
    {
        if ($campaign->status !== 'active') {
            return redirect()->route('admin.phishing')->with('error', 'Only active campaigns can be completed.');
        }

        $campaign->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'phishing_campaign_completed',
            'PhishingCampaign',
            $campaign->id,
            null,
            $request,
            'success'
        );

        return redirect()->route('admin.phishing')->with('success', 'Phishing campaign completed successfully!');
    }

    /**
     * Delete a phishing campaign.
     */
    public function destroy(Request $request, PhishingCampaign $campaign)
    {
        $campaign->delete();

        LoggingService::logAudit(
            Auth::user(),
            'phishing_campaign_deleted',
            'PhishingCampaign',
            $campaign->id,
            ['name' => $campaign->name],
            $request,
            'success'
        );

        return redirect()->route('admin.phishing')->with('success', 'Phishing campaign deleted successfully!');
    }

    /**
     * Track phishing link click.
     */
    public function trackClick(Request $request, PhishingCampaign $campaign)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view this link.');
        }

        $user = Auth::user();

        $result = PhishingResult::where('campaign_id', $campaign->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$result) {
            return redirect()->route('student.dashboard')->with('error', 'Campaign not found.');
        }

        if (!$result->clicked_link) {
            $result->update([
                'clicked_link' => true,
                'clicked_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Update campaign totals
            $campaign->increment('total_clicked');
        }

        // Show phishing education page
        return view('phishing.detected', [
            'campaign' => $campaign,
            'result' => $result,
        ]);
    }

    /**
     * Report phishing email.
     */
    public function reportPhishing(Request $request, PhishingCampaign $campaign)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        $result = PhishingResult::where('campaign_id', $campaign->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$result) {
            return response()->json(['success' => false, 'message' => 'Campaign not found.'], 404);
        }

        if (!$result->reported_phishing) {
            $result->update([
                'reported_phishing' => true,
                'reported_at' => now(),
            ]);

            // Update campaign totals
            $campaign->increment('total_reported');
        }

        return response()->json(['success' => true, 'message' => 'Phishing reported successfully!']);
    }

    /**
     * Get target users based on audience.
     */
    private function getTargetUsers($audience)
    {
        $query = User::where('role', 'student')->where('is_active', true);

        switch ($audience) {
            case 'cs_dept':
                $query->where('department', 'Computer Science');
                break;
            case 'staff':
                $query->whereIn('role', ['instructor', 'admin']);
                break;
            case 'all':
            default:
                // All students
                break;
        }

        return $query->get();
    }
}
