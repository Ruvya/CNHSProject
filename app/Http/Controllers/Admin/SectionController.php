<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\SchoolYear;
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

        // Get report data for the modal
        $reportData = Section::select(
                DB::raw("COALESCE(NULLIF(strand,''), track) as strand_label"),
                DB::raw('COUNT(*) as sections_count'),
                DB::raw('SUM(current_enrollment) as total_students')
            )
            ->groupBy('strand_label')
            ->orderBy('strand_label')
            ->get();

        return view('admin.sections.index', compact('sections', 'reportData'));
    }

    public function show(Section $section)
    {
        $section->load(['adviser','students' => function($q){
            $q->orderBy('last_name')->orderBy('first_name');
        }]);

        // For assignment UI
        $eligibleStudents = Student::query()
            ->when($section->grade_level, function($q) use ($section){
                $q->where('grade_level', $section->grade_level);
            })
            ->when(($section->strand ?: $section->track), function($q) use ($section){
                $strand = $section->strand ?: $section->track;
                $q->where(function($qq) use ($strand){
                    $qq->where('cluster', $strand)->orWhere('track', $strand);
                });
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get(['id','student_id','first_name','middle_name','last_name','name','section','grade_level']);

        return view('admin.sections.show', compact('section','eligibleStudents'));
    }

    public function assignStudents(Request $request, Section $section)
    {
        $data = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'integer|exists:students,id',
        ]);

        // Capacity check
        $toAssignCount = count($data['student_ids']);
        $current = $section->students()->count();
        if ($section->max_capacity && ($current + $toAssignCount) > $section->max_capacity) {
            return back()->withErrors(['student_ids' => 'Assignment exceeds section capacity.']);
        }

        Student::whereIn('id', $data['student_ids'])->update(['section' => $section->name]);

        // Update enrollment count/status
        if (method_exists($section, 'updateEnrollmentCount')) {
            $section->refresh();
            $section->updateEnrollmentCount();
        }

        return redirect()->route('admin.sections.show', $section)->with('success', 'Students assigned to section.');
    }

    public function removeStudent(Section $section, Student $student)
    {
        if (strcasecmp($student->section, $section->name) !== 0) {
            return back()->withErrors(['student' => 'Student does not belong to this section.']);
        }

        $student->update(['section' => null]);

        if (method_exists($section, 'updateEnrollmentCount')) {
            $section->refresh();
            $section->updateEnrollmentCount();
        }

        return redirect()->route('admin.sections.show', $section)->with('success', 'Student removed from section.');
    }

    public function create()
    {
        $teachers = Teacher::orderBy('name')->get();
        $years = SchoolYear::orderByDesc('start_year')->get(['name','status']);
        $tracks = ['STEM','ABM','HUMSS','GAS','TVL'];
        $gradeLevels = ['Grade 11','Grade 12'];
        return view('admin.sections.create', compact('teachers','tracks','gradeLevels','years'));
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
            'school_year' => 'nullable|string|exists:school_years,name',
            'semester' => 'nullable|string|in:1st Semester,2nd Semester',
        ]);

        $data['max_capacity'] = $data['max_capacity'] ?? 40;
        if (empty($data['school_year'])) {
            $activeYear = \App\Models\SchoolYear::active()->first();
            $latestYear = \App\Models\SchoolYear::orderByDesc('start_year')->first();
            $data['school_year'] = optional($activeYear)->name ?? optional($latestYear)->name ?? $this->currentSchoolYear();
        }
        $data['semester'] = $data['semester'] ?? '1st Semester';

        // Unique name within strand/track for current term
        $exists = Section::where('name', $data['name'])
            ->where('school_year', $data['school_year'])
            ->where('semester', $data['semester'])
            ->exists();
        if ($exists) {
            return back()->withErrors(['name' => 'Section name already exists for the current term.'])->withInput();
        }

        // Optional: ensure adviser matches cluster/strand selection
        if (!empty($data['adviser_id'])) {
            $adviser = Teacher::find($data['adviser_id']);
            if ($adviser) {
                $validTracks = ['STEM','ABM','HUMSS','GAS','TVL'];
                $adviserClusterOrTrack = $adviser->cluster ?: $adviser->track;

                if (!empty($adviserClusterOrTrack)) {
                    $adviserValue = strtoupper(trim($adviserClusterOrTrack));
                    $selectedValue = strtoupper(trim($data['track']));

                    // Only enforce when adviser has a recognizable strand/cluster value
                    if (in_array($adviserValue, $validTracks, true) && $adviserValue !== $selectedValue) {
                        return back()->withErrors(['adviser_id' => 'Selected adviser does not belong to the chosen cluster.'])->withInput();
                    }
                }
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

        // Optional adviser cluster/strand validation (prefer teacher.cluster)
        if (!empty($data['adviser_id'])) {
            $adviser = Teacher::find($data['adviser_id']);
            if ($adviser) {
                $validTracks = ['STEM','ABM','HUMSS','GAS','TVL'];
                $adviserClusterOrTrack = $adviser->cluster ?: $adviser->track;

                if (!empty($adviserClusterOrTrack)) {
                    $adviserValue = strtoupper(trim($adviserClusterOrTrack));
                    $selectedValue = strtoupper(trim($data['track']));

                    if (in_array($adviserValue, $validTracks, true) && $adviserValue !== $selectedValue) {
                        return back()->withErrors(['adviser_id' => 'Selected adviser does not belong to the chosen cluster.'])->withInput();
                    }
                }
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

    /**
     * Return students for a given section with the section adviser info.
     */
    public function apiSectionStudents(Section $section)
    {
        // Adviser (may be null)
        $adviser = $section->adviser ? [
            'id' => $section->adviser->id,
            'name' => $section->adviser->name,
            'email' => $section->adviser->email ?? null,
        ] : null;

        // Students whose "section" text matches this section's name
        $students = Student::query()
            ->where('section', $section->name)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get(['id','student_id','first_name','middle_name','last_name','name','gender','section'])
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'student_id' => $s->student_id,
                    'name' => $s->name,
                    'first_name' => $s->first_name,
                    'middle_name' => $s->middle_name,
                    'last_name' => $s->last_name,
                    'gender' => $s->gender,
                ];
            })
            ->values();

        return response()->json([
            'section' => [
                'id' => $section->id,
                'name' => $section->name,
                'grade_level' => $section->grade_level,
                'track' => $section->track,
                'strand' => $section->strand,
                'school_year' => $section->school_year,
                'semester' => $section->semester,
                'status' => $section->status,
            ],
            'adviser' => $adviser,
            'students' => $students,
        ]);
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


