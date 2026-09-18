<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminCategoryController extends Controller
{
    /**
     * Top-level categories only — each row's subcategories are loaded
     * lazily (via $category->children) in the recursive view partial, so
     * this works regardless of how many levels deep the tree goes.
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentOptions = Category::orderBy('name')->get();

        return view('admin.categories.create', compact('parentOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCategory($request);

        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?: $validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "\"{$validated['name']}\" was created.");
    }

    public function edit(Category $category)
    {
        // A category can't become its own parent or its own grandchild —
        // exclude itself and its descendants from the parent picker.
        $parentOptions = Category::whereNotIn('id', array_merge([$category->id], $category->allDescendantIds()))
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parentOptions'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $this->validateCategory($request, $category->id);

        if ($request->filled('parent_id')) {
            $invalidParents = array_merge([$category->id], $category->allDescendantIds());

            if (in_array((int) $request->parent_id, $invalidParents, true)) {
                return back()
                    ->withErrors(['parent_id' => 'A category cannot be moved under itself or one of its own subcategories.'])
                    ->withInput();
            }
        }

        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?: $validated['name'], $category->id);
        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "\"{$category->name}\" was updated.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        $productCount = $category->totalProductsCount();

        if ($productCount > 0) {
            return back()->with(
                'error',
                "Can't delete \"{$category->name}\" — it (or one of its subcategories) still has "
                    . "{$productCount} product(s) attached. Reassign or remove those products first."
            );
        }

        $name = $category->name;
        $category->delete(); // subcategories cascade-delete at the DB level

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "\"{$name}\" was deleted.");
    }

    private function validateCategory(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable', 'string', 'max:255', 'alpha_dash',
                Rule::unique('categories', 'slug')->ignore($ignoreId),
            ],
            'type' => ['required', Rule::in(['product', 'service'])],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);
    }

    /**
     * Slugify $source and, if that slug is already taken, append -2, -3, etc.
     * until it's unique. Used both when the admin leaves the slug field
     * blank and as a final safety net if they type one that collides.
     */
    private function uniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source);
        $slug = $base;
        $suffix = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $suffix++;
            $slug = "{$base}-{$suffix}";
        }

        return $slug;
    }
}
