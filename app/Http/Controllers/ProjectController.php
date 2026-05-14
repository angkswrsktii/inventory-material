<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        // Menampilkan daftar project terbaru
        $projects = Project::orderByDesc('id')->paginate(15);
        
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:m_project,name',
        ], [
            'name.required' => 'Nama Project wajib diisi.',
            'name.unique' => 'Nama Project ini sudah ada di database.',
        ]);

        Project::create([
            'name' => $request->name,
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Data Project berhasil ditambahkan.');
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            // Validasi unique mengecualikan ID project ini sendiri saat diedit
            'name' => 'required|string|max:255|unique:m_project,name,' . $project->id,
        ], [
            'name.required' => 'Nama Project wajib diisi.',
            'name.unique' => 'Nama Project ini sudah ada di database.',
        ]);

        $project->update([
            'name' => $request->name,
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Data Project berhasil diperbarui.');
    }
}