<?php

namespace App\Services;

use App\Models\School;

class FeatureGate
{
    /**
     * Check if a school has access to a specific feature based on their subscription tier.
     *
     * @param School $school
     * @param string $feature
     * @return bool
     */
    public static function hasAccess(School $school, string $feature): bool
    {
        // Default to 'free' if no active subscription
        $subscription = $school->activeSubscription;
        $tier = $subscription ? $subscription->plan->tier : 'free';

        $matrix = [
            'push_notifications' => ['free', 'premium', 'max'],
            'fee_payments'       => ['free', 'premium', 'max'],
            'behavioral_alerts'  => ['premium', 'max'],
            'teacher_messaging'  => ['premium', 'max'],
            'bus_tracking'       => ['premium', 'max'],
            'classroom_cam'      => ['max'],
            'ai_analytics'       => ['max'],
        ];

        $allowedTiers = $matrix[$feature] ?? [];

        return in_array($tier, $allowedTiers);
    }
}
