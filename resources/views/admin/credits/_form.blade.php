<div class="admin-form__grid">
    <div><label for="title_id">Título</label><select id="title_id" name="title_id" required><option value="">Selecciona un título</option>@foreach ($titles as $title)<option value="{{ $title->id }}" @selected((string) old('title_id', $credit->title_id ?? '') === (string) $title->id)>{{ $title->title }}</option>@endforeach</select></div>
    <div><label for="person_id">Persona</label><select id="person_id" name="person_id" required><option value="">Selecciona una persona</option>@foreach ($people as $person)<option value="{{ $person->id }}" @selected((string) old('person_id', $credit->person_id ?? '') === (string) $person->id)>{{ $person->name }}</option>@endforeach</select></div>
    <div><label for="role_type">Rol</label><select id="role_type" name="role_type" required>@foreach (['actor', 'director', 'creator'] as $role)<option value="{{ $role }}" @selected(old('role_type', $credit->role_type ?? '') === $role)>{{ ucfirst($role) }}</option>@endforeach</select></div>
    <div><label for="character_name">Personaje</label><input id="character_name" name="character_name" value="{{ old('character_name', $credit->character_name ?? '') }}" /></div>
    <div><label for="billing_order">Orden</label><input id="billing_order" name="billing_order" type="number" min="1" value="{{ old('billing_order', $credit->billing_order ?? '') }}" /></div>
</div>
