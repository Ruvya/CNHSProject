<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::query()
            ->orderBy('track')
            ->orderBy('strand')
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get()
            ->groupBy(function ($s) {
                return trim(($s->strand ?: $s->track) ?: 'Unspecified');
            });

        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        $teachers = Teacher::orderBy('name')->get();
        $tracks = ['STEM','ABM','HUMSS','GAS','TVL'];
        $gradeLevels = ['Grade 11','Grade 12'];
        return view('admin.sections.create', compact('teachers','tracks','gradeLevels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|in:Grade 11,Grade 12',
            'track' => 'required|string|in:STEM,ABM,HUMSS,GAS,TVL',
            'strand' => 'nullable|string|max:255',
            'adviser_id' => 'nullable|exists:teachers,id',
            'max_capacity' => 'nullable|integer|min:1',
            'status' => 'nullable|in:active,inactive,full',
        ]);

        $data['max_capacity'] = $data['max_capacity'] ?? 40;
        $data['school_year'] = $data['school_year'] ?? $this->currentSchoolYear();
        $data['grading_period'] = $data['grading_period'] ?? 'First Grading';

        // Unique name within strand/track for current term
        $exists = Section::where('name', $data['name'])
            ->where('school_year', $data['school_year'])
            ->where('grading_period', $data['grading_period'])
            ->exists();
        if ($exists) {
            return back()->withErrors(['name' => 'Section name already exists for the current term.'])->withInput();
        }

        // Optional: ensure adviser matches strand/track if adviser has a track set
        if (!empty($data['adviser_id'])) {
            $adviser = Teacher::find($data['adviser_id']);
            if ($adviser && !empty($adviser->track) && strcasecmp($adviser->track, $data['track']) !== 0) {
                return back()->withErrors(['adviser_id' => 'Selected adviser does not belong to the chosen track.'])->withInput();
            }
        }

        Section::create($data);

        return redirect()->route('admin.sections.index')->with('success', 'Section created successfully.');
    }

    public function edit(Section $section)
    {
        $teachers = Teacher::orderBy('name')->get();
        $tracks = ['STEM','ABM','HUMSS','GAS','TVL'];
        $gradeLevels = ['Grade 11','Grade 12'];
        return view('admin.sections.edit', compact('section','teachers','tracks','gradeLevels'));
    }

    public function update(Request $request, Section $section)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|in:Grade 11,Grade 12',
            'track' => 'required|string|in:STEM,ABM,HUMSS,GAS,TVL',
            'strand' => 'nullable|string|max:255',
            'adviser_id' => 'nullable|exists:teachers,id',
            'max_capacity' => 'nullable|integer|min:1',
            'status' => 'nullable|in:active,inactive,full',
        ]);

        // Optional adviser-track validation
        if (!empty($data['adviser_id'])) {
            $adviser = Teacher::find($data['adviser_id']);
            if ($adviser && !empty($adviser->track) && strcasecmp($adviser->track, $data['track']) !== 0) {
                return back()->withErrors(['adviser_id' => 'Selected adviser does not belong to the chosen track.'])->withInput();
            }
        }

        $section->update($data);

        return redirect()->route('admin.sections.index')->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section)
    {
        // Soft-deactivate instead of delete
        $section->update(['status' => 'inactive']);
        return redirect()->route('admin.sections.index')->with('success', 'Section deactivated.');
    }

    public function hardDelete(Section $section)
    {
        $section->delete();
        return redirect()->route('admin.sections.index')->with('success', 'Section deleted permanently.');
    }

    public function toggleStatus(Section $section)
    {
        $section->status = $section->status === 'inactive' ? 'active' : 'inactive';
        $section->save();
        return redirect()->route('admin.sections.index')->with('success', 'Section status updated.');
    }

    public function reportPerStrand()
    {
        $report = Section::select(
                DB::raw("COALESCE(NULLIF(strand,''), track) as strand_label"),
                DB::raw('COUNT(*) as sections_count'),
                DB::raw('SUM(current_enrollment) as total_students')
            )
            ->groupBy('strand_label')
            ->orderBy('strand_label')
            ->get();

        return view('admin.sections.report-per-strand', compact('report'));
    }

    public function apiSectionsByFilters(Request $request)
    {
        $request->validate([
            'grade_level' => 'nullable|string|in:Grade 11,Grade 12',
            'strand' => 'nullable|string',
        ]);

        $query = Section::query()->where('status', '!=', 'inactive');
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }
        if ($request->filled('strand')) {
            $query->where(function($q) use ($request) {
                $q->where('strand', $request->strand)
                  ->orWhere('track', $request->strand);
            });
        }

        $sections = $query->orderBy('grade_level')->orderBy('name')->get(['id','name','grade_level','track','strand','max_capacity','current_enrollment','status']);
        return response()->json(['data' => $sections]);
    }

    private function currentSchoolYear(): string
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        if ($currentMonth >= 6) {
            return $currentYear . '-' . ($currentYear + 1);
        }
        return ($currentYear - 1) . '-' . $currentYear;
    }
}


