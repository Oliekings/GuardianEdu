<?php

namespace App\Http\Controllers;

use App\Models\CmsSector;
use App\Models\LeadEnquiry;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function landing(Request $request)
    {
        // For multi-tenant, we'd identify the school by domain/session
        // For now, we take the primary school or use a fallback
        $school = School::first();

        $sectors = $school
            ? CmsSector::where('school_id', $school->id)
                ->where('is_visible', true)
                ->get()
                ->keyBy('key')
            : collect();

        return Inertia::render('Public/Landing', [
            'school' => $school,
            'sectors' => $sectors,
            'canLogin' => true,
            'canRegister' => true,
        ]);
    }

    public function submitEnquiry(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string',
            'school_id' => 'required|exists:schools,id',
        ]);

        LeadEnquiry::create($validated);

        return redirect()->back()->with('success', 'Your enquiry has been received. Our team will contact you soon.');
    }

    // Admin CMS Management
    public function cmsIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $sectors = CmsSector::where('school_id', $schoolId)->get();
        return Inertia::render('Admin/CMS/Index', ['sectors' => $sectors]);
    }

    public function updateSector(Request $request)
    {
        $request->validate(['key' => 'required', 'content' => 'required|array']);
        
        CmsSector::updateOrCreate(
            ['school_id' => Auth::user()->getScopedSchoolId(), 'key' => $request->key],
            ['content' => $request->content, 'is_visible' => $request->is_visible ?? true]
        );

        return redirect()->back()->with('success', 'CMS Section updated.');
    }

    public function enquiryIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $enquiries = LeadEnquiry::where('school_id', $schoolId)->orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/CMS/Enquiries', ['enquiries' => $enquiries]);
    }
}
