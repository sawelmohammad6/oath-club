@extends('layouts.admin')

@section('title', 'Sports Teams')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Sports Management</h2>
        <p class="text-gray-500 text-sm">Cricket &amp; Football teams - {{ $teams->count() }} total</p>
    </div>
    <button onclick="openTeamModal()" class="btn-primary"><i class="fas fa-plus"></i> Create Team</button>
</div>

<div class="space-y-6">
    @forelse($teams as $team)
    <div class="admin-card">
        <div class="p-4 flex items-center justify-between border-b border-gray-100">
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $team->sport_type === 'Cricket' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">{{ $team->sport_type }}</span>
                <h3 class="font-bold text-gray-800 text-lg">{{ $team->team_name }}</h3>
                <span class="text-gray-400 text-sm">{{ $team->players->count() }} players</span>
            </div>
            <div class="flex gap-1.5">
                <button type="button" onclick="addPlayer({{ $team->id }})" class="action-btn bg-primary-100 hover:bg-primary-200 text-primary-600" title="Add Player"><i class="fas fa-user-plus"></i></button>
                <button type="button" onclick="openTeamModal({{ $team->id }})" class="action-btn bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit"><i class="fas fa-edit"></i></button>
                <form method="POST" action="{{ route('admin.sports-teams.destroy', $team->id) }}" data-confirm-delete data-confirm-title="Delete {{ $team->team_name }}?" data-confirm-text="All players will also be removed.">
                    @csrf @method('DELETE')
                    <button class="action-btn bg-red-100 hover:bg-red-200 text-red-600" title="Delete"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
        @if($team->players->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead><tr><th>Player Name</th><th>Position</th><th class="text-right">Actions</th></tr></thead>
                <tbody>
                    @foreach($team->players as $p)
                    <tr>
                        <td class="font-medium">{{ $p->player_name }}</td>
                        <td class="text-gray-500">{{ $p->position ?: '-' }}</td>
                        <td class="text-right">
                            <div class="flex gap-1.5 justify-end">
                                <button type="button" onclick="editPlayer({{ $p->id }})" class="action-btn bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit"><i class="fas fa-edit"></i></button>
                                <form method="POST" action="{{ route('admin.sports-teams.players.destroy', $p->id) }}" data-confirm-delete data-confirm-title="Remove {{ $p->player_name }}?" data-confirm-text="This player will be removed from the squad.">
                                    @csrf @method('DELETE')
                                    <button class="action-btn bg-red-100 hover:bg-red-200 text-red-600" title="Remove"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-6 text-center text-gray-400"><i class="fas fa-users text-2xl mb-2"></i><p>No players yet</p></div>
        @endif
    </div>
    @empty
    <div class="admin-card p-12 text-center text-gray-400">
        <i class="fas fa-football text-5xl mb-3"></i>
        <p class="text-xl">No teams yet. Create your first team!</p>
    </div>
    @endforelse
    <div class="px-4 py-3 border-t border-gray-100 bg-white rounded-lg shadow-sm">
        {{ $teams->links() }}
    </div>
</div>

<!-- Team Modal -->
<div id="teamModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closeTeamModal()">
    <div class="modal-panel bg-white p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="teamModalTitle">Create Team</h3>
            <button type="button" onclick="closeTeamModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="teamForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="teamMethod" value="POST">
            <input type="hidden" name="id" id="editTeamId">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Team Name <span class="text-red-500">*</span></label>
                    <input type="text" name="team_name" id="t_name" required class="form-input" placeholder="e.g. Oath Club Cricket XI">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sport Type <span class="text-red-500">*</span></label>
                    <select name="sport_type" id="t_type" required class="form-input">
                        <option value="">Select...</option>
                        <option value="Cricket">Cricket</option>
                        <option value="Football">Football</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="t_desc" rows="3" class="form-input" placeholder="Team description..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Banner Image</label>
                    <input type="file" name="banner_image" id="t_banner" accept="image/*" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 file:font-semibold file:cursor-pointer">
                </div>
                <button type="submit" class="btn-primary w-full"><i class="fas fa-save"></i> Save Team</button>
            </div>
        </form>
    </div>
</div>

<!-- Player Modal -->
<div id="playerModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closePlayerModal()">
    <div class="modal-panel bg-white p-6 w-full max-w-md shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="playerModalTitle">Add Player</h3>
            <button type="button" onclick="closePlayerModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="playerForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="playerMethod" value="POST">
            <input type="hidden" name="sports_team_id" id="p_team_id">
            <input type="hidden" name="id" id="editPlayerId">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Player Name <span class="text-red-500">*</span></label>
                    <input type="text" name="player_name" id="p_name" required class="form-input" placeholder="Player name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <input type="text" name="position" id="p_position" class="form-input" placeholder="e.g. Captain, Batsman, Goalkeeper">
                </div>
                <button type="submit" class="btn-primary w-full"><i class="fas fa-save"></i> Save Player</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const teams = @json($teams->items());
const players = teams.flatMap(t => t.players.map(p => ({...p, team_name: t.team_name})));

function openTeamModal(id) {
    const data = id ? teams.find(t => t.id === id) : null;
    document.getElementById('teamModal').classList.remove('hidden');
    document.getElementById('teamModalTitle').textContent = data ? 'Edit Team' : 'Create Team';
    document.getElementById('teamMethod').value = 'POST';
    document.getElementById('editTeamId').value = data ? data.id : '';
    document.getElementById('t_name').value = data ? data.team_name : '';
    document.getElementById('t_type').value = data ? data.sport_type : '';
    document.getElementById('t_desc').value = data ? (data.description||'') : '';
    document.getElementById('t_banner').value = '';
    document.getElementById('teamForm').action = data ? '/admin/sports-teams/' + data.id : '{{ route("admin.sports-teams.store") }}';
}
function closeTeamModal() { document.getElementById('teamModal').classList.add('hidden'); }

const initialTeamEditId = Number(@json(request('edit')));
if (initialTeamEditId) {
    const openInitialTeamEdit = () => openTeamModal(initialTeamEditId);
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', openInitialTeamEdit);
    } else {
        openInitialTeamEdit();
    }
}

function addPlayer(teamId) {
    document.getElementById('playerModal').classList.remove('hidden');
    document.getElementById('playerModalTitle').textContent = 'Add Player';
    document.getElementById('playerMethod').value = 'POST';
    document.getElementById('editPlayerId').value = '';
    document.getElementById('p_team_id').value = teamId;
    document.getElementById('p_name').value = '';
    document.getElementById('p_position').value = '';
    document.getElementById('playerForm').action = '{{ route("admin.sports-teams.players.store") }}';
}
function editPlayer(id) {
    const p = players.find(x => x.id === id);
    if (!p) return;
    document.getElementById('playerModal').classList.remove('hidden');
    document.getElementById('playerModalTitle').textContent = 'Edit Player';
    document.getElementById('playerMethod').value = 'POST';
    document.getElementById('editPlayerId').value = p.id;
    document.getElementById('p_team_id').value = p.sports_team_id;
    document.getElementById('p_name').value = p.player_name;
    document.getElementById('p_position').value = p.position||'';
    document.getElementById('playerForm').action = '/admin/sports-teams/players/' + p.id;
}
function closePlayerModal() { document.getElementById('playerModal').classList.add('hidden'); }
</script>
@endpush
@endsection
