<ul style="list-style: none; padding-left: 0;">
    @foreach($categories as $category)
        <li style="margin-bottom: 5px;">
            <a href="{{ route('categories.show', $category->slug) }}"
               style="display: block; padding: 8px 12px;
                      background: {{ $category->parent_id ? '#f8f9fa' : '#e9ecef' }};
                      border-radius: 4px;
                      text-decoration: none;
                      color: #333;
                      border-left: 3px solid #007bff;">
                {{ $category->name }}
            </a>

            @if($category->children->count() > 0)
                <div style="margin-left: 20px; margin-top: 5px;">
                    @include('categories.partials.tree', ['categories' => $category->children])
                </div>
            @endif
        </li>
    @endforeach
</ul>
