@extends('components.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
	<div class="mb-6">
		<h1 class="text-2xl font-bold text-gray-900">Gebruikers beheer</h1>
		<p class="text-gray-600 mt-1">Beheer gebruikers en wijs rollen toe (alleen voor admins).</p>
	</div>

	@if(session('status'))
		<div class="mb-4 text-green-700 bg-green-100 p-3 rounded">{{ session('status') }}</div>
	@endif

	<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
		<div class="overflow-x-auto">
			<table class="w-full table-auto">
				<thead>
					<tr class="text-left text-sm text-gray-600">
						<th class="p-3">Naam</th>
						<th class="p-3">E-mail</th>
						<th class="p-3">Afdeling</th>
						<th class="p-3">Rol</th>
						<th class="p-3">Acties</th>
					</tr>
				</thead>
				<tbody class="text-sm text-gray-700">
					@foreach($users as $user)
						<tr class="border-t">
							<td class="p-3">{{ $user->name }}</td>
							<td class="p-3">{{ $user->email }}</td>
							<td class="p-3">
								<select name="department_id" class="border rounded px-2 py-1 bg-white" form="user-form-{{ $user->id }}">
											<option value="">-- Geen afdeling --</option>
											@foreach($departments as $dept)
												<option value="{{ $dept->id }}" {{ $user->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
											@endforeach
										</select>
                                    </td>
							<td class="p-3">
								<select name="role" class="border rounded px-2 py-1 bg-white" form="user-form-{{ $user->id }}">
											<option value="employee" {{ $user->role === 'employee' ? 'selected' : '' }}>Employee</option>
											<option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
											<option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
										</select>
                                    </td>
							<td class="p-3">
								@if(auth()->user() && auth()->user()->isAdmin())
									<form id="user-form-{{ $user->id }}" action="{{ route('users.update.role', $user) }}" method="POST" class="flex items-center gap-2">
										@csrf



										<button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Sla op</button>
									</form>
								@else
									<span class="text-sm text-gray-600">Geen rechten</span>
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
