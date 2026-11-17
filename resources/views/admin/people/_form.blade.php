<div class="admin-form__grid">
    <div><label for="name">Nombre</label><input id="name" name="name" value="{{ old('name', $person->name ?? '') }}" required /></div>
    <div><label for="birth_date">Fecha de nacimiento</label><input id="birth_date" name="birth_date" type="date" value="{{ old('birth_date', isset($person) && $person->birth_date ? $person->birth_date->format('Y-m-d') : '') }}" /></div>
    <div><label for="photo_path">URL de la foto</label><input id="photo_path" name="photo_path" value="{{ old('photo_path', $person->photo_path ?? '') }}" /></div>
</div>
<div><label for="biography">Biografía</label><textarea id="biography" name="biography" rows="6">{{ old('biography', $person->biography ?? '') }}</textarea></div>
