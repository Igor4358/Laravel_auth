<option value="{{ $category->id }}"
    {{ isset($post) && $post->category_id == $category->id ? 'selected' : '' }}>
    {{ str_repeat('— ', $level) }}{{ $category->name }}
</option>

@foreach($category->children as $child)
    @include('categories.partials.option', ['category' => $child, 'level' => $level + 1])
@endforeach
