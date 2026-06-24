@extends('layouts.app')

@section('title', 'Phishing Inbox Simulator')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 h-[calc(100vh-10rem)] flex flex-col">

    <!-- Header info -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 shrink-0">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Security Inbox Training</h2>
            <p class="text-slate-500 text-sm">Practice reporting phishing simulation emails. Look out for suspicious domains and urgent requests!</p>
        </div>
        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-xl text-xs font-bold border border-blue-100 shrink-0 select-none">
            Points Awarded: <span id="inbox-points">850</span> pts
        </div>
    </div>

    <!-- Alert banner placeholder -->
    <div id="simulation-feedback" class="hidden p-4 rounded-xl border text-sm font-semibold flex items-center justify-between shrink-0 transition-all duration-300">
        <span id="feedback-text"></span>
        <button onclick="document.getElementById('simulation-feedback').classList.add('hidden')" class="text-xs underline hover:no-underline font-bold">Dismiss</button>
    </div>

    <!-- Split-Pane Email client container -->
    <div class="flex-1 min-h-0 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex">
        
        <!-- Left Pane: Mail items list -->
        <aside class="w-80 border-r border-slate-200 flex flex-col shrink-0">
            <div class="p-4 bg-slate-50 border-b border-slate-200 font-bold text-slate-800 text-sm flex items-center justify-between">
                <span>Inbox</span>
                <span class="text-[10px] bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full font-bold">4 Emails</span>
            </div>
            
            <nav class="flex-1 overflow-y-auto divide-y divide-slate-100">
                
                <!-- Email Item 1 -->
                <button onclick="selectEmail(1)" id="email-item-1" class="w-full text-left p-4 hover:bg-slate-50/50 flex flex-col gap-1 transition-all bg-blue-50/20 border-l-4 border-blue-500">
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-slate-900">Microsoft IT Support</span>
                        <span class="text-slate-400">10:45 AM</span>
                    </div>
                    <div class="text-xs font-extrabold text-slate-800 truncate">URGENT: Password Expiration Notice</div>
                    <div class="text-xs text-slate-400 line-clamp-2">Your company domain password is set to expire in 2 hours. Reset immediately...</div>
                </button>

                <!-- Email Item 2 -->
                <button onclick="selectEmail(2)" id="email-item-2" class="w-full text-left p-4 hover:bg-slate-50/50 flex flex-col gap-1 transition-all">
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-slate-600">W3Schools License Team</span>
                        <span class="text-slate-400">09:12 AM</span>
                    </div>
                    <div class="text-xs font-bold text-slate-800 truncate">Course Material Assigned</div>
                    <div class="text-xs text-slate-400 line-clamp-2">Hello Student User, your instructor has assigned SQL Injection Course...</div>
                </button>

                <!-- Email Item 3 -->
                <button onclick="selectEmail(3)" id="email-item-3" class="w-full text-left p-4 hover:bg-slate-50/50 flex flex-col gap-1 transition-all">
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-slate-600">DHL Shipping Service</span>
                        <span class="text-slate-400">Yesterday</span>
                    </div>
                    <div class="text-xs font-extrabold text-slate-850 truncate">Package Unclaimed - Delayed Status</div>
                    <div class="text-xs text-slate-400 line-clamp-2">We were unable to deliver your package code DHL-2039-NX. Confirm status...</div>
                </button>

                <!-- Email Item 4 -->
                <button onclick="selectEmail(4)" id="email-item-4" class="w-full text-left p-4 hover:bg-slate-50/50 flex flex-col gap-1 transition-all">
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-slate-655">Amazon.com Orders</span>
                        <span class="text-slate-400">Yesterday</span>
                    </div>
                    <div class="text-xs font-bold text-slate-800 truncate">Your Order #104-92736 has shipped</div>
                    <div class="text-xs text-slate-400 line-clamp-2">Thank you for shopping. Your package is on its way. Delivery expected June 26...</div>
                </button>

            </nav>
        </aside>

        <!-- Right Pane: Active Email reader -->
        <main class="flex-1 flex flex-col min-w-0 bg-slate-50/10">
            
            <!-- Email Action Toolbar -->
            <div class="h-16 border-b border-slate-200 px-6 flex items-center justify-between shrink-0 bg-white">
                <div class="flex items-center gap-3">
                    <button onclick="triggerFeedback(true)" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl shadow-md shadow-orange-100 transition-colors shrink-0">
                        🚩 Report Phishing
                    </button>
                    <button onclick="triggerFeedback(false)" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-250 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl shadow-sm transition-colors shrink-0">
                        ✔ Mark Safe
                    </button>
                </div>
            </div>

            <!-- Email Viewer Content -->
            <div class="flex-1 overflow-y-auto p-6 md:p-8 bg-white">
                <div class="max-w-2xl mx-auto space-y-6">
                    
                    <!-- Sender Details card -->
                    <div class="flex items-start justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600 select-none">M</div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-slate-900 text-sm" id="sender-display">Microsoft Support</span>
                                    <span class="text-[10px] bg-slate-100 border border-slate-200 rounded px-1.5 py-0.5 font-mono text-slate-500" id="sender-email">&lt;security@micros0ft-support.com&gt;</span>
                                </div>
                                <div class="text-[11px] text-slate-450 mt-0.5">To: Student User &lt;student@securelearn.org&gt;</div>
                            </div>
                        </div>
                        <div class="text-xs text-slate-450 font-bold" id="email-date">June 24, 2026 10:45 AM</div>
                    </div>

                    <!-- Email Title -->
                    <h3 class="text-xl font-bold text-slate-900 leading-tight" id="email-subject">URGENT: Password Expiration Notice</h3>

                    <!-- Email Body Container -->
                    <div class="border border-slate-200 rounded-2xl p-6 bg-slate-50/20 text-sm text-slate-700 leading-relaxed space-y-4" id="email-body">
                        <p>Dear Student User,</p>
                        <p>We detected that your account password is scheduled to expire in <strong>2 hours</strong>. According to company policy, failure to update credentials will lock your access to all SecureLearn services.</p>
                        <p>Please click the link below to verify your current password and update your credentials to prevent immediate termination of access:</p>
                        <p class="pt-2">
                            <a href="#" onclick="event.preventDefault(); alert('WARNING: You clicked a simulated phishing link! In a real scenario, this could install malware or steal credentials.');" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-lg shadow-sm shadow-blue-200 transition-colors">Reset Password Now</a>
                        </p>
                        <p class="text-xs text-slate-450 border-t border-slate-200/50 pt-4">This is a system-generated alert from Microsoft Admin Services. Do not reply to this email.</p>
                    </div>

                </div>
            </div>

        </main>
    </div>

</div>

<script>
    // State
    let activeEmailId = 1;
    let score = 850;

    const emailDatabase = {
        1: {
            display: "Microsoft Support",
            email: "security@micros0ft-support.com",
            subject: "URGENT: Password Expiration Notice",
            date: "June 24, 2026 10:45 AM",
            body: `<p>Dear Student User,</p>
                   <p>We detected that your account password is scheduled to expire in <strong>2 hours</strong>. According to company policy, failure to update credentials will lock your access to all SecureLearn services.</p>
                   <p>Please click the link below to verify your current password and update your credentials to prevent immediate termination of access:</p>
                   <p class="pt-2">
                       <a href="#" onclick="event.preventDefault(); alert('WARNING: You clicked a simulated phishing link! In a real scenario, this could install malware or steal credentials. Look for the sender domain micros0ft-support.com (spelled with a zero).');" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-lg shadow-sm shadow-blue-200 transition-colors">Reset Password Now</a>
                   </p>
                   <p class="text-xs text-slate-450 border-t border-slate-200/50 pt-4">This is a system-generated alert from Microsoft Admin Services. Do not reply to this email.</p>`,
            isPhish: true,
            tips: "Correct! You successfully detected the Phishing Simulation. Key red flags were: (1) Urgent timeline (2 hours), (2) Suspicious domain domain 'micros0ft-support.com' using a zero (0) instead of an 'o', and (3) Generic greeting."
        },
        2: {
            display: "W3Schools License Team",
            email: "licensing@w3schools.com",
            subject: "Course Material Assigned",
            date: "June 24, 2026 09:12 AM",
            body: `<p>Hello Student User,</p>
                   <p>Your instructor has assigned the <strong>SQL Injection Prevention</strong> course to your curriculum learning plan. Please click the button below to resume the course:</p>
                   <p class="pt-2">
                       <a href="/student/courses" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold px-5 py-2.5 rounded-lg shadow-sm shadow-green-100 transition-colors">Go to Courses</a>
                   </p>
                   <p>If you have any questions, contact support.</p>`,
            isPhish: false,
            tips: "Oops! W3Schools Course Assigned is a legitimate email. Reporting safe emails reduces your safety score."
        },
        3: {
            display: "DHL Shipping Service",
            email: "delivery-alert@dhl-tracking-portal.net",
            subject: "Package Unclaimed - Delayed Status",
            date: "June 23, 2026 04:30 PM",
            body: `<p>Attention Customer,</p>
                   <p>Your package code DHL-2039-NX is delayed at our sorting facility due to incorrect delivery details. A holding fee of <strong>$1.50</strong> is required to dispatch the item.</p>
                   <p>Verify delivery address and pay dispatch fee immediately:</p>
                   <p class="pt-2">
                       <a href="#" onclick="event.preventDefault(); alert('WARNING: Simulated Phishing link clicked! Notice the unofficial domain dhl-tracking-portal.net and the request for credit details.');" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold px-5 py-2.5 rounded-lg shadow-sm shadow-yellow-100 transition-colors">Verify Address</a>
                   </p>`,
            isPhish: true,
            tips: "Correct! You reported a Phishing Simulation. Red flags: (1) Unofficial tracking domain 'dhl-tracking-portal.net', and (2) Requesting personal or financial details for mock deliveries."
        },
        4: {
            display: "Amazon.com Orders",
            email: "auto-confirm@amazon.com",
            subject: "Your Order #104-92736 has shipped",
            date: "June 23, 2026 12:15 PM",
            body: `<p>Hello Student,</p>
                   <p>We are pleased to inform you that your package has shipped! Delivery is scheduled for June 26, 2026.</p>
                   <p>You can track the shipment status directly inside your Amazon account.</p>`,
            isPhish: false,
            tips: "Oops! Amazon order confirmation is a legitimate email from auto-confirm@amazon.com. Be careful not to report correct emails."
        }
    };

    function selectEmail(id) {
        // Remove active class from old item
        document.getElementById(`email-item-${activeEmailId}`).className = "w-full text-left p-4 hover:bg-slate-50/50 flex flex-col gap-1 transition-all";
        
        // Add active class to new item
        document.getElementById(`email-item-${id}`).className = "w-full text-left p-4 hover:bg-slate-50/50 flex flex-col gap-1 transition-all bg-blue-50/20 border-l-4 border-blue-500";
        
        // Load data
        activeEmailId = id;
        const email = emailDatabase[id];
        
        document.getElementById('sender-display').innerText = email.display;
        document.getElementById('sender-email').innerText = `<${email.email}>`;
        document.getElementById('email-date').innerText = email.date;
        document.getElementById('email-subject').innerText = email.subject;
        document.getElementById('email-body').innerHTML = email.body;
    }

    function triggerFeedback(reportedPhish) {
        const feedback = document.getElementById('simulation-feedback');
        const text = document.getElementById('feedback-text');
        const scoreLabel = document.getElementById('inbox-points');
        const email = emailDatabase[activeEmailId];

        feedback.classList.remove('hidden', 'bg-green-50', 'border-green-150', 'text-green-800', 'bg-red-50', 'border-red-150', 'text-red-800');

        if (reportedPhish) {
            // Action is reporting phish
            if (email.isPhish) {
                // Correctly reported
                score += 50;
                feedback.classList.add('bg-green-50', 'border-green-150', 'text-green-800');
                text.innerHTML = `🌟 **Correct! (+50 pts)** ${email.tips}`;
            } else {
                // False alarm
                score = Math.max(0, score - 20);
                feedback.classList.add('bg-red-50', 'border-red-150', 'text-red-800');
                text.innerHTML = `⚠ **Incorrect (-20 pts)** ${email.tips}`;
            }
        } else {
            // Action is marking safe
            if (email.isPhish) {
                // Incorrectly marked safe
                score = Math.max(0, score - 50);
                feedback.classList.add('bg-red-50', 'border-red-150', 'text-red-800');
                text.innerHTML = `🚨 **Danger! (-50 pts)** You marked a malicious email as safe. Remember to audit domains carefully!`;
            } else {
                // Correctly marked safe
                score += 10;
                feedback.classList.add('bg-green-50', 'border-green-150', 'text-green-800');
                text.innerHTML = `✔ **Correct (+10 pts)** You logged a safe email.`;
            }
        }

        scoreLabel.innerText = score;
        feedback.classList.add('opacity-100');
    }
</script>
@endsection
