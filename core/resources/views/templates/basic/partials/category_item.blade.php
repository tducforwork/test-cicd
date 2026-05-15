<li
    class="category-item {{ $category->allSubcategories->count() > 0 ? 'has-sub' : '' }} {{ @$activeCategoryId == $category->id ? 'active' : '' }}">
    <div class="category-header" style="display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('pages', $category->slug) }}"
            class="category-link ajax-category {{ @$activeCategoryId == $category->id ? 'text-primary font-weight-bold active' : '' }}"
            data-id="{{ $category->id }}"
            style="flex-grow: 1; padding: 5px 0; font-size: 14px; color: #555; text-decoration: none;">
            {{ __($category->name) }} <span
                style="color: #999; font-size: 12px;">({{ $category->total_products_count ?? 0 }})</span>
        </a>
        @if($category->allSubcategories->count() > 0)
            <i class="fa-solid fa-chevron-down toggle-sub"
                style="cursor: pointer; padding: 5px; color: #999; font-size: 10px;"></i>
        @endif
    </div>
    @if($category->allSubcategories->count() > 0)
        <ul class="sub-category-list"
            style="display: {{ collect($category->allSubcategories)->contains('id', @$activeCategoryId) ? 'block' : 'none' }}; list-style: none; padding-left: 15px; border-left: 1px solid #eee; margin-top: 5px;">
            @foreach($category->allSubcategories as $sub)
                @include('Template::partials.category_item', ['category' => $sub, 'activeCategoryId' => @$activeCategoryId])
            @endforeach
        </ul>
    @endif
</li>