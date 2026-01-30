<?php

namespace App\Services;

use App\Models\Pin;

class PinService
{
    /**
     * Generate a random unique PIN code
     * Format: 4 groups of 4 characters (e.g., XXXX-XXXX-XXXX-XXXX)
     */
    public function generateUniquePin(): string
    {
        do {
            $pin = $this->generatePin();
        } while (Pin::where('pin_code', $pin)->exists());

        return $pin;
    }

    /**
     * Generate a single PIN code
     */
    private function generatePin(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $groups = [];

        for ($i = 0; $i < 4; $i++) {
            $group = '';
            for ($j = 0; $j < 4; $j++) {
                $group .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $groups[] = $group;
        }

        return implode('-', $groups);
    }

    /**
     * Create a new PIN
     */
    public function createPin(int $maxDownloads = 3): Pin
    {
        return Pin::create([
            'pin_code' => $this->generateUniquePin(),
            'max_downloads' => $maxDownloads,
            'used_downloads' => 0,
            'status' => 'active',
        ]);
    }

    /**
     * Create multiple PINs in bulk
     * 
     * @return \Illuminate\Support\Collection
     */
    public function createBulkPins(int $quantity, int $maxDownloads = 3)
    {
        $pins = collect();
        
        for ($i = 0; $i < $quantity; $i++) {
            $pins->push($this->createPin($maxDownloads));
        }

        return $pins;
    }

    /**
     * Validate a PIN code
     */
    public function validatePin(string $pinCode): ?Pin
    {
        $pin = Pin::where('pin_code', $pinCode)->first();

        if (!$pin) {
            return null;
        }

        if (!$pin->canDownload()) {
            return null;
        }

        return $pin;
    }

    /**
     * Revoke a PIN
     */
    public function revokePin(string $pinCode): bool
    {
        $pin = Pin::where('pin_code', $pinCode)->first();

        if (!$pin) {
            return false;
        }

        $pin->update(['status' => 'revoked']);
        return true;
    }

    /**
     * Get PIN statistics
     */
    public function getPinStats(Pin $pin): array
    {
        return [
            'pin_code' => $pin->pin_code,
            'status' => $pin->status,
            'used_downloads' => $pin->used_downloads,
            'max_downloads' => $pin->max_downloads,
            'remaining_downloads' => $pin->getRemainingDownloads(),
            'created_at' => $pin->created_at,
            'updated_at' => $pin->updated_at,
            'is_active' => $pin->isActive(),
        ];
    }
}
