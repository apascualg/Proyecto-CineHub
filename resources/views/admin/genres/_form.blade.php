<div class="admin-form__grid">
    <div><label for="name">Nombre</label><input id="name" name="name" value="{{ old('name', $genre->name ?? '') }}" required /></div>
    <div><label for="slug">Slug</label><input id="slug" name="slug" value="{{ old('slug', $genre->slug ?? '') }}" required /></div>
</div>
