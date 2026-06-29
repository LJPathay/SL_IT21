<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\User;
use App\Models\Module;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateService
{
    /**
     * Generate a certificate for a user completing a module.
     */
    public function generateCertificate(User $user, Module $module)
    {
        // Check if certificate already exists
        $existing = Certificate::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        // Generate unique certificate numbers and credentials
        $certNumber = $this->generateCertificateNumber();
        $credentialId = 'CRED-' . strtoupper(Str::random(12));

        // Create certificate record
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'module_id' => $module->id,
            'course_id' => $module->course_id,
            'certificate_number' => $certNumber,
            'credential_id' => $credentialId,
            'title' => 'Certificate of Completion: ' . ($module->title ?? 'Module Completion'),
            'issued_at' => now(),
            'expires_at' => now()->addYears(2),
        ]);

        return $certificate;
    }

    /**
     * Generate a unique certificate number.
     */
    private function generateCertificateNumber(): string
    {
        do {
            $number = 'CERT-' . strtoupper(Str::random(8)) . '-' . date('Ymd');
        } while (Certificate::where('certificate_number', $number)->exists());

        return $number;
    }

    public function generatePdf(Certificate $certificate)
    {
        $certificate->load(['user', 'module']);

        $pdf = Pdf::loadView('certificates.template', [
            'certificate' => $certificate,
            'user' => $certificate->user,
            'module' => $certificate->module,
        ]);

        return $pdf->download('certificate-' . ($certificate->credential_id ?? $certificate->certificate_number) . '.pdf');
    }

    /**
     * Verify a certificate by its ID.
     */
    public function verifyCertificate(string $id): ?Certificate
    {
        return Certificate::where('credential_id', $id)
            ->orWhere('certificate_number', $id)
            ->first();
    }

    /**
     * Check if a certificate is valid (not expired).
     */
    public function isCertificateValid(Certificate $certificate): bool
    {
        return $certificate->expires_at === null ||
               $certificate->expires_at->isFuture();
    }

    /**
     * Revoke a certificate.
     */
    public function revokeCertificate(Certificate $certificate, string $reason): bool
    {
        return $certificate->update([
            'revoked' => true,
            'revoked_at' => now(),
            'revocation_reason' => $reason,
        ]);
    }
}
