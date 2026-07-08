<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class EncryptionService
{
    /**
     * Encrypt a value
     */
    public function encrypt(string $value): string
    {
        try {
            return Crypt::encryptString($value);
        } catch (\Exception $e) {
            Log::error('Encryption failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Decrypt a value
     */
    public function decrypt(string $encryptedValue): string
    {
        try {
            return Crypt::decryptString($encryptedValue);
        } catch (\Exception $e) {
            Log::error('Decryption failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Encrypt a value if it's not null
     */
    public function encryptNullable(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        return $this->encrypt($value);
    }

    /**
     * Decrypt a value if it's not null
     */
    public function decryptNullable(?string $encryptedValue): ?string
    {
        if ($encryptedValue === null) {
            return null;
        }
        return $this->decrypt($encryptedValue);
    }

    /**
     * Encrypt an array of values
     */
    public function encryptArray(array $values): array
    {
        return array_map(function ($value) {
            return $this->encryptNullable($value);
        }, $values);
    }

    /**
     * Decrypt an array of values
     */
    public function decryptArray(array $encryptedValues): array
    {
        return array_map(function ($value) {
            return $this->decryptNullable($value);
        }, $encryptedValues);
    }
}
