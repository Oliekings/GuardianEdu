<?php

use App\Models\CmsSector;
use App\Models\School;

$school = School::first();

if ($school) {
    CmsSector::updateOrCreate(
        ['school_id' => $school->id, 'key' => 'hero'],
        [
            'content' => [
                'headline' => 'Defining the Academic Era',
                'subheadline' => 'The ultra-modern behavioral monitoring and academic orchestration ecosystem designed for high-performance institutions. Experience total clarity.',
                'cta_text' => 'Schedule Visitation'
            ],
            'is_visible' => true
        ]
    );

    CmsSector::updateOrCreate(
        ['school_id' => $school->id, 'key' => 'about'],
        [
            'content' => [
                'headline' => 'Complete Biometric & Academic Integration',
                'subheadline' => 'Real-time attendance syncing, dynamic grading scales, and absolute privacy for elite institutions.',
            ],
            'is_visible' => true
        ]
    );
}

echo "CMS Sectors Seeded Successfully.\n";
