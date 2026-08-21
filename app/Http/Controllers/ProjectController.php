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
        if ($project->portfolio_path) {
            $this->deletePortfolio($project);
        }
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Karya berhasil dihapus.');
    }

    private function handlePortfolioUpload(Project $project, $file): void
    {
        // Hapus portfolio lama
        if ($project->portfolio_path) {
            $this->deletePortfolio($project);
        }

        $hash = $project->hash_id;
        $extractPath = storage_path('app/public/portfolios/' . $hash);

        // Bersihkan folder lama
        if (is_dir($extractPath)) {
            $this->deleteDirectory($extractPath);
        }
        mkdir($extractPath, 0755, true);

        $zip = new \ZipArchive();
        if ($zip->open($file->getPathname()) === true) {
            // Security: prevent zip slip
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if (str_contains($filename, '..') || str_starts_with($filename, '/')) {
                    continue;
                }
                $zip->extractTo($extractPath);
                break;
            }
            // Extract all safely
            $zip->extractTo($extractPath);
            $zip->close();

            // Flatten if single top-level folder
            $files = scandir($extractPath);
            $files = array_diff($files, ['.', '..']);
            if (count($files) === 1) {
                $single = $extractPath . '/' . reset($files);
                if (is_dir($single)) {
                    $inner = scandir($single);
                    $inner = array_diff($inner, ['.', '..']);
                    foreach ($inner as $item) {
                        rename($single . '/' . $item, $extractPath . '/' . $item);
                    }
                    rmdir($single);
                }
            }

            // Detect index file
            $index = 'index.html';
            if (!file_exists($extractPath . '/index.html')) {
                $found = glob($extractPath . '/*.html');
                if (!empty($found)) {
                    $index = basename($found[0]);
                }
            }

            $project->update([
                'portfolio_path' => 'portfolios/' . $hash,
                'portfolio_index' => $index,
            ]);
        }
    }

    private function deletePortfolio(Project $project): void
    {
        if (!$project->portfolio_path) return;
        $path = storage_path('app/public/' . $project->portfolio_path);
        if (is_dir($path)) {
            $this->deleteDirectory($path);
        } else {
            Storage::disk('public')->delete($project->portfolio_path);
        }
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) return;
        $items = array_diff(scandir($dir), ['.', '..']);
        foreach ($items as $item) {
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
}
