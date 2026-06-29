<?php

namespace App\Mail;

use App\Models\PhishingCampaign;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PhishingSimulationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;
    public $user;

    public function __construct(PhishingCampaign $campaign, User $user)
    {
        $this->campaign = $campaign;
        $this->user = $user;
    }

    public function build()
    {
        $subjects = [
            'microsoft_login' => 'Microsoft Account Verification Required',
            'dhl_shipping' => 'DHL Shipment Notification: Delivery on Hold',
            'netflix_renewal' => 'Action Required: Netflix Update Payment Method',
            'payroll_update' => 'URGENT: Update Direct Deposit Directives',
        ];

        $subject = $subjects[$this->campaign->template_type] ?? 'Security Alert';

        return $this->subject($subject)
                    ->view('emails.phishing.' . $this->campaign->template_type);
    }
}
