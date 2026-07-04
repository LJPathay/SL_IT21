<?php

namespace App\Http\Controllers;

use App\Models\SecurityDetection;
use App\Services\SecurityDetectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityDashboardController extends Controller
{
    protected $securityService;

    public function __construct(SecurityDetectionService $securityService)
    {
        $this->securityService = $securityService;
    }

    /**
     * Display the security dashboard
     */
    public function index()
    {
        $stats = $this->securityService->getSecurityStats();
        $recentDetections = SecurityDetection::unresolved()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('security.dashboard', [
            'stats' => $stats,
            'recentDetections' => $recentDetections,
        ]);
    }

    /**
     * Display all security detections
     */
    public function detections(Request $request)
    {
        $query = SecurityDetection::with('resolver');

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Filter by severity
        if ($request->filled('severity')) {
            $query->bySeverity($request->severity);
        }

        // Filter by status
        if ($request->filled('status') && $request->status === 'unresolved') {
            $query->unresolved();
        }

        $detections = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('security.detections', [
            'detections' => $detections,
            'filters' => $request->only(['type', 'severity', 'status']),
        ]);
    }

    /**
     * Show detection details
     */
    public function showDetection(SecurityDetection $detection)
    {
        return view('security.detection-details', [
            'detection' => $detection,
        ]);
    }

    /**
     * Resolve a security detection
     */
    public function resolveDetection(Request $request, SecurityDetection $detection)
    {
        $request->validate([
            'resolution_notes' => 'required|string|max:1000',
        ]);

        $this->securityService->resolveDetection($detection, $request->resolution_notes);

        return redirect()->route('security.detections')
            ->with('success', 'Security detection resolved successfully.');
    }

    /**
     * Test phishing detection
     */
    public function testPhishing(Request $request)
    {
        $request->validate([
            'email_content' => 'required|string',
            'sender_email' => 'required|email',
            'subject' => 'required|string',
        ]);

        $detection = $this->securityService->detectPhishing(
            $request->email_content,
            $request->sender_email,
            $request->subject
        );

        return redirect()->route('security.dashboard')
            ->with('success', $detection 
                ? 'Phishing detected and logged to security dashboard.' 
                : 'No phishing characteristics detected.');
    }

    /**
     * Test social engineering detection
     */
    public function testSocialEngineering(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'context' => 'required|string',
        ]);

        $detection = $this->securityService->detectSocialEngineering(
            $request->message,
            $request->context
        );

        return redirect()->route('security.dashboard')
            ->with('success', $detection 
                ? 'Social engineering attempt detected and logged.' 
                : 'No social engineering tactics detected.');
    }

    /**
     * Test password security
     */
    public function testPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $detection = $this->securityService->assessPasswordSecurity(
            $request->password,
            Auth::user()
        );

        return redirect()->route('security.dashboard')
            ->with('success', $detection 
                ? 'Weak password detected and logged to security dashboard.' 
                : 'Password meets security requirements.');
    }

    /**
     * Test malware detection
     */
    public function testMalware(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileContent = file_get_contents($file->getPathname());
        $fileSize = $file->getSize();

        $detection = $this->securityService->detectMalware(
            $fileName,
            $fileContent,
            $fileSize
        );

        return redirect()->route('security.dashboard')
            ->with('success', $detection 
                ? 'Potential malware detected and logged to security dashboard.' 
                : 'No malware characteristics detected.');
    }

    /**
     * Test online activity detection
     */
    public function testOnlineActivity(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'user_agent' => 'required|string',
            'ip_address' => 'required|ip',
        ]);

        $detection = $this->securityService->detectUnsafeOnlineActivity(
            $request->url,
            $request->user_agent,
            $request->ip_address
        );

        return redirect()->route('security.dashboard')
            ->with('success', $detection 
                ? 'Unsafe online activity detected and logged.' 
                : 'No unsafe activity detected.');
    }
}
