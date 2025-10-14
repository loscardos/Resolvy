<div class="btn-group btn-group-xs" role="group">
    @can($viewGate)
        <a class="btn btn-primary" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}" title="{{ trans('global.view') }}">
            <i class="fa-fw fas fa-eye"></i>
        </a>
    @endcan
    @can($editGate)
        <a class="btn btn-info" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}" title="{{ trans('global.edit') }}">
            <i class="fa-fw fas fa-pencil-alt"></i>
        </a>
    @endcan
    @can($deleteGate)
        <button type="button" class="btn btn-danger" onclick="if(confirm('{{ trans('global.areYouSure') }}')) { document.getElementById('delete-form-{{ $row->id }}').submit(); }" title="{{ trans('global.delete') }}">
            <i class="fa-fw fas fa-trash"></i>
        </button>
        <form id="delete-form-{{ $row->id }}" action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endcan
</div>
