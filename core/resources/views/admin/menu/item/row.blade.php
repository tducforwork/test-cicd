@foreach($items as $item)
    <tr>
        <td>
            {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $level) !!}
            @if($level > 0)
                <i class="las la-level-up-alt la-rotate-90"></i>
            @endif
            {{ __($item->title) }}
        </td>
        <td>{{ $item->url }}</td>
        <td>{{ $item->order }}</td>
        <td>
            @php echo $item->statusBadge; @endphp
        </td>
        <td>
            <div class="button--group">
                <button type="button" class="btn btn-sm btn-outline--primary editBtn" 
                        data-id="{{ $item->id }}" 
                        data-title="{{ $item->title }}" 
                        data-url="{{ $item->url }}" 
                        data-parent_id="{{ $item->parent_id }}" 
                        data-order="{{ $item->order }}" 
                        data-status="{{ $item->status }}"
                        data-has_mega_menu="{{ $item->has_mega_menu }}">
                    <i class="la la-pencil"></i> @lang('Edit')
                </button>
                <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" 
                        data-action="{{ route('admin.menu.item.delete', [$item->menu_group_id, $item->id]) }}" 
                        data-question="@lang('Are you sure the delete this menu item?')">
                    <i class="la la-trash"></i> @lang('Delete')
                </button>
            </div>
        </td>
    </tr>
    @if($item->children->count() > 0)
        @include('admin.menu.item.row', ['items' => $item->children, 'level' => $level + 1])
    @endif
@endforeach
