<tr>
    <td>
        <span style="padding-left: {{ $depth * 24 }}px;">
            @if ($depth > 0)
                <i class="fas fa-level-up-alt fa-rotate-90 text-muted me-1" style="font-size: 0.75rem;"></i>
            @endif
            {{ $category->name }}
        </span>
    </td>
    <td>
        <span class="badge bg-{{ $category->type === 'service' ? 'info' : 'secondary' }}">
            {{ ucfirst($category->type) }}
        </span>
    </td>
    <td><code>{{ $category->slug }}</code></td>
    <td>{{ $category->products()->count() }}</td>
    <td>
        @if ($category->is_active)
            <span class="badge bg-success">Active</span>
        @else
            <span class="badge bg-secondary">Inactive</span>
        @endif
    </td>
    <td class="text-end">
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
        <a href="#" class="btn btn-sm btn-outline-danger" title="Delete"
            onclick="confirmDeleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}'); return false;">
            <i class="fas fa-trash"></i>
        </a>

        <form id="deleteCategoryForm{{ $category->id }}" action="{{ route('admin.categories.destroy', $category) }}"
            method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    </td>
</tr>

@foreach ($category->children as $child)
    @include('admin.categories.partials.row', ['category' => $child, 'depth' => $depth + 1])
@endforeach
