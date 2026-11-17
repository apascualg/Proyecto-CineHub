<div class="admin-form__grid">
    <div>
        <label for="title">Título</label>
        <input id="title" name="title" value="{{ old('title', $title->title ?? '') }}" required />
    </div>
    <div>
        <label for="original_title">Título original</label>
        <input id="original_title" name="original_title" value="{{ old('original_title', $title->original_title ?? '') }}" />
    </div>
    <div>
        <label for="slug">Slug</label>
        <input id="slug" name="slug" value="{{ old('slug', $title->slug ?? '') }}" required />
    </div>
    <div>
        <label for="type">Tipo</label>
        <select id="type" name="type" required>
            <option value="movie" @selected(old('type', $title->type ?? '') === 'movie')>Película</option>
            <option value="series" @selected(old('type', $title->type ?? '') === 'series')>Serie</option>
        </select>
    </div>
    <div>
        <label for="release_date">Fecha de estreno</label>
        <input id="release_date" name="release_date" type="date" value="{{ old('release_date', isset($title) && $title->release_date ? $title->release_date->format('Y-m-d') : '') }}" />
    </div>
    <div>
        <label for="status">Estado</label>
        <select id="status" name="status" required>
            <option value="published" @selected(old('status', $title->status ?? 'published') === 'published')>Publicado</option>
            <option value="draft" @selected(old('status', $title->status ?? '') === 'draft')>Borrador</option>
        </select>
    </div>
    <div>
        <label for="duration_minutes">Duración (minutos)</label>
        <input id="duration_minutes" name="duration_minutes" type="number" min="1" value="{{ old('duration_minutes', $title->duration_minutes ?? '') }}" />
    </div>
    <div>
        <label for="seasons_count">Temporadas</label>
        <input id="seasons_count" name="seasons_count" type="number" min="1" value="{{ old('seasons_count', $title->seasons_count ?? '') }}" />
    </div>
    <div>
        <label for="episodes_count">Episodios</label>
        <input id="episodes_count" name="episodes_count" type="number" min="1" value="{{ old('episodes_count', $title->episodes_count ?? '') }}" />
    </div>
    <div>
        <label for="poster_path">URL del póster</label>
        <input id="poster_path" name="poster_path" value="{{ old('poster_path', $title->poster_path ?? '') }}" />
    </div>
    <div>
        <label for="backdrop_path">URL de portada</label>
        <input id="backdrop_path" name="backdrop_path" value="{{ old('backdrop_path', $title->backdrop_path ?? '') }}" />
    </div>
    <div>
        <label for="trailer_url">URL del tráiler</label>
        <input id="trailer_url" name="trailer_url" type="url" value="{{ old('trailer_url', $title->trailer_url ?? '') }}" />
    </div>
</div>
<div>
    <label for="synopsis">Sinopsis</label>
    <textarea id="synopsis" name="synopsis" rows="5">{{ old('synopsis', $title->synopsis ?? '') }}</textarea>
</div>
<div>
    <span class="admin-form__label">Géneros</span>
    <div class="admin-checkboxes">
        @foreach ($genres as $genre)
            <label><input type="checkbox" name="genres[]" value="{{ $genre->id }}" @checked(in_array($genre->id, old('genres', isset($title) ? $title->genres->pluck('id')->all() : []))) /> {{ $genre->name }}</label>
        @endforeach
    </div>
</div>
