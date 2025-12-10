<?php
// ...existing code...

namespace App\Http\Controllers;

use App\Models\In_report;
use Illuminate\Http\Request;

class InReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = In_report::all();
        return view('in_reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('in_reports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            // sesuaikan field yang dibutuhkan oleh model In_report
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        In_report::create($data);

        return redirect()->route('in_reports.index')->with('success', 'Report created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(In_report $in_report)
    {
        return view('in_reports.show', compact('in_report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(In_report $in_report)
    {
        return view('in_reports.edit', compact('in_report'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, In_report $in_report)
    {
        $data = $request->validate([
            // sesuaikan field yang dibutuhkan oleh model In_report
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $in_report->update($data);

        return redirect()->route('in_reports.index')->with('success', 'Report updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(In_report $in_report)
    {
        $in_report->delete();

        return redirect()->route('in_reports.index')->with('success', 'Report deleted.');
    }
}
// ...existing code...
