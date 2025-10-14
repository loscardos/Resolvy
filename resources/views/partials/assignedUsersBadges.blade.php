@forelse($users as $user)
    <span class="badge badge-info">{{ $user->name }}</span>
@empty
    <span class="badge badge-secondary">Unassigned</span>
@endforelse
