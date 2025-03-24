<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;

class GradesController extends Controller
{
    public function list(Request $request)
    {
        // Get all grades grouped by term
        $grades = Grade::orderBy('term')->get()->groupBy('term');

        // Calculating CGPA and CCH
        $totalCH = Grade::sum('credit_hours');
        $totalPoints = Grade::sum(\DB::raw('credit_hours * grade'));
        $CGPA = $totalCH ? $totalPoints / $totalCH : 0;

        return view('grades.list', compact('grades', 'CGPA', 'totalCH'));
    }

    public function add()
    {
        $grade = new Grade();
        return view('grades.form', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        return view('grades.form', compact('grade'));
    }

    public function save(Request $request, Grade $grade = null)
    {
        $grade = $grade ?? new Grade();

        $request->validate([
            'subject' => 'required|string',
            'term' => 'required|string',
            'credit_hours' => 'required|integer|min:1',
            'grade' => 'required|numeric|min:0|max:4'
        ]);

        $grade->fill($request->all());
        $grade->save();

        return redirect()->route('grades_list')->with('success', 'Grade saved successfully!');
    }

    public function delete(Grade $grade)
    {
        $grade->delete();

        return redirect()->route('grades_list')->with('success', 'Grade deleted successfully!');
    }
}
