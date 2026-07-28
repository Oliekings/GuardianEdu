<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ThemeController extends Controller
{
    /**
     * Show the theme editor page.
     */
    public function show()
    {
        $school = Auth::user()->school;
        $theme = $school->settings['theme'] ?? [];

        return Inertia::render('Admin/ThemeEditor', [
            'currentTheme' => $theme,
            'defaults' => [
                'primary'      => '#6366f1',
                'primaryHover' => '#818cf8',
                'accent'       => '#a855f7',
                'bgBase'       => '#050505',
                'success'      => '#10b981',
                'danger'       => '#f43f5e',
                'warning'      => '#f59e0b',
            ],
        ]);
    }

    /**
     * Update the school's theme settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'primary'      => 'nullable|string|max:7',
            'primaryHover' => 'nullable|string|max:7',
            'accent'       => 'nullable|string|max:7',
            'bgBase'       => 'nullable|string|max:7',
            'success'      => 'nullable|string|max:7',
            'danger'       => 'nullable|string|max:7',
            'warning'      => 'nullable|string|max:7',
        ]);

        $school = Auth::user()->school;
        $settings = $school->settings ?? [];

        // Merge only non-null values into theme
        $theme = array_filter($request->only([
            'primary', 'primaryHover', 'accent', 'bgBase',
            'success', 'danger', 'warning',
        ]));

        $settings['theme'] = $theme;
        $school->settings = $settings;
        $school->save();

        return redirect()->back()->with('success', 'Theme updated successfully.');
    }
}
