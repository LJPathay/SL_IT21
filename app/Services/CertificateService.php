<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\User;
use App\Models\Module;
use Illuminate\Support\Str;

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

        // Generate unique certificate ID
        $certificateId = $this->generateCertificateId();

        // Create certificate record
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'module_id' => $module->id,
            'certificate_id' => $certificateId,
            'issued_at' => now(),
            'expiry_date' => now()->addYears(2),
        ]);

        return $certificate;
    }

    /**
     * Generate a unique certificate ID.
     */
    private function generateCertificateId(): string
    {
        do {
            $id = 'CERT-' . strtoupper(Str::random(8)) . '-' . date('Ymd');
        } while (Certificate::where('certificate_id', $id)->exists());

        return $id;
    }

    /**
     * Generate PDF certificate (requires barryvdh/laravel-dompdf).
     * This is a placeholder - the actual implementation requires the package to be installed.
     */
    public function generatePdf(Certificate $certificate)
    {
        // Note: This requires composer require barryvdh/laravel-dompdf
        // Uncomment after installing the package

        // $pdf = \PDF::loadView('certificates.template', [
        //     'certificate' => $certificate,
        //     'user' => $certificate->user,
        //     'module' => $certificate->module,
        // ]);

        // return $pdf->download('certificate-' . $certificate->certificate_id . '.pdf');

        // For now, return null
        return null;
    }

    /**
     * Verify a certificate by its ID.
     */
    public function verifyCertificate(string $certificateId): ?Certificate
    {
        return Certificate::where('certificate_id', $certificateId)->first();
    }

    /**
     * Check if a certificate is valid (not expired).
     */
    public function isCertificateValid(Certificate $certificate): bool
    {
        return $certificate->expiry_date === null ||
               $certificate->expiry_date->isFuture();
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
