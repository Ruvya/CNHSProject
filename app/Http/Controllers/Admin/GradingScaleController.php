<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradingScale;
use Illuminate\Http\Request;

class GradingScaleController extends Controller
{
    public function index()
    {
        $scales = GradingScale::orderBy('order')->get();
        return view('admin.grading-scales.index', compact('scales'));
    }

    public function create()
    {
        return view('admin.grading-scales.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'min_grade' => 'required|integer|min:0|max:100',
            'max_grade' => 'required|integer|min:0|max:100|gte:min_grade',
            'description' => 'required|string|max:255',
            'remarks' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);
        GradingScale::create($validated);
        return redirect()->route('admin.grading-scales.index')->with('success', 'Grading scale added.');
    }

    public function edit(GradingScale $gradingScale)
    {
        return view('admin.grading-scales.edit', compact('gradingScale'));
    }

    public function update(Request $request, GradingScale $gradingScale)
    {
        $validated = $request->validate([
            'min_grade' => 'required|integer|min:0|max:100',
            'max_grade' => 'required|integer|min:0|max:100|gte:min_grade',
            'description' => 'required|string|max:255',
            'remarks' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);
        $gradingScale->update($validated);
        return redirect()->route('admin.grading-scales.index')->with('success', 'Grading scale updated.');
    }

    public function destroy(GradingScale $gradingScale)
    {
        $gradingScale->delete();
        return redirect()->route('admin.grading-scales.index')->with('success', 'Grading scale deleted.');
    }

    public function analytics()
    {
        $scales = GradingScale::orderBy('order')->get();
        $labels = [];
        $counts = [];
        foreach ($scales as $scale) {
            $labels[] = $scale->min_grade . '–' . $scale->max_grade . ' (' . $scale->description . ')';
            $counts[] = \App\Models\Student::whereBetween('grades.final_grade', [$scale->min_grade, $scale->max_grade])
                ->join('grades', 'students.id', '=', 'grades.student_id')
                ->count();
        }
        return response()->json(['data' => [
            'labels' => $labels,
            'counts' => $counts
        ]]);
    }
} 