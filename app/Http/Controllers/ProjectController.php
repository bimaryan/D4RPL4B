<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\WebpConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'image_url' => 'nullable|url|max:512',
            'tech_stack' => 'nullable|string',
            'demo_url' => 'nullable|url|max:512',
            'repo_url' => 'nullable|url|max:512',
            'portfolio' => 'nullable|file|mimes:zip|max:51200',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_url'] = WebpConverter::store($request->file('image'), 'projects', 1600, 82);
        } elseif (!empty($validated['image_url'])) {
            // keep url as is
        } else {
            $validated['image_url'] = null;
        }
        unset($validated['image']);
        $portfolioFile = $validated['portfolio'] ?? null;
        unset($validated['portfolio']);

        if (!empty($validated['tech_stack'])) {
            $validated['tech_stack'] = array_map('trim', explode(',', $validated['tech_stack']));
        } else {
            $validated['tech_stack'] = [];
        }

        $project = Project::create($validated);

        if ($portfolioFile) {
            $this->handlePortfolioUpload($project, $portfolioFile);
        }

        return redirect()->route('projects.index')->with('success', 'Karya berhasil ditambahkan.' . ($portfolioFile ? ' Portfolio hosting aktif.' : ''));
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'image_url' => 'nullable|url|max:512',
            'tech_stack' => 'nullable|string',
            'demo_url' => 'nullable|url|max:512',
            'repo_url' => 'nullable|url|max:512',
            'remove_image' => 'nullable|boolean',
            'portfolio' => 'nullable|file|mimes:zip|max:51200',
            'remove_portfolio' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($project->image_url && !str_starts_with($project->image_url, 'http')) {
                Storage::disk('public')->delete($project->image_url);
            }
            $validated['image_url'] = WebpConverter::store($request->file('image'), 'projects', 1600, 82);
        } elseif (!empty($validated['image_url'])) {
            if ($project->image_url && !str_starts_with($project->image_url, 'http') && $validated['image_url'] !== $project->image_url) {
                Storage::disk('public')->delete($project->image_url);
            }
        } elseif ($request->boolean('remove_image')) {
            if ($project->image_url && !str_starts_with($project->image_url, 'http')) {
                Storage::disk('public')->delete($project->image_url);
            }
            $validated['image_url'] = null;
        } else {
            unset($validated['image_url']);
        }
        unset($validated['image'], $validated['remove_image']);

        // Portfolio ZIP handling
        $portfolioFile = $request->file('portfolio');
        $removePortfolio = $request->boolean('remove_portfolio');
        unset($validated['portfolio'], $validated['remove_portfolio']);

        if (array_key_exists('tech_stack', $validated)) {
            if (!empty($validated['tech_stack'])) {
                $validated['tech_stack'] = array_map('trim', explode(',', $validated['tech_stack']));
            } else {
                $validated['tech_stack'] = [];
            }
        }

        $project->update($validated);

        if ($portfolioFile) {
            $this->handlePortfolioUpload($project, $portfolioFile);
        } elseif ($removePortfolio && $project->portfolio_path) {
            $this->deletePortfolio($project);
            $project->update(['portfolio_path' => null, 'portfolio_index' => null]);
        }

        return redirect()->route('projects.index')->with('success', 'Karya berhasil diupdate.' . ($portfolioFile ? ' Portfolio hosting diperbarui.' : ''));
    }

    public function destroy(Project $project)
    {
        if ($project->image_url && !str_starts_with($project->image_url, 'http')) {
            Storage::disk('public')->delete($project->image_url);
        }
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Karya berhasil dihapus.');
    }
}
