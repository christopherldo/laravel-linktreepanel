@extends('admin.page')

@section('body')
    <h3>{{isset($link) ? 'Editar Link' : 'Novo Link'}}</h3>

    @if ($errors)
        <div class="error">
            <ul>
                @foreach ($errors as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST">
        @csrf
        <label for="status">
            Status:<br>
            <select name="status" id="status">
                <option {{isset($link) ? ($link->status === 1 ? 'selected' : '') : ''}} value="1">Ativo</option>
                <option {{isset($link) ? ($link->status === 0 ? 'selected' : '') : ''}} value="0">Desativado</option>
            </select>
        </label>

        <label for="title">
            Título:<br>
            <input minlength="2" maxlength="100" required type="text" name="title" id="title" value="{{ isset($link) ? $link->title : old('title') }}">
        </label>

        <label for="href">
            URL:<br>
            <input required type="url" name="href" id="href" value="{{ isset($link) ? $link->href : old('href') }}">
        </label>

        <label for="op_bg_color">
            Cor do fundo:<br>
            <input type="color" name="op_bg_color"
                value="{{ isset($link) ? $link->op_bg_color : old('op_bg_color') ?? '#ffffff' }}" id="op_bg_color">
        </label>

        <label for="op_text_color">
            Cor do texto:<br>
            <input type="color" name="op_text_color"
                value="{{ isset($link) ? $link->op_text_color : old('op_text_color') ?? '#000000' }}" id="op_text_color">
        </label>

        <label for="op_border_type">
            Bordas:<br>
            <select name="op_border_type" id="op_border_type">
                <option {{isset($link) ? ($link->op_border_type === 'rounded' ? 'selected' : '') : ''}} value="rounded">Arredondadas</option>
                <option {{isset($link) ? ($link->op_border_type === 'square' ? 'selected' : '') : ''}} value="square">Quadradas</option>
            </select>
        </label>

        <label>
            <input type="submit" value="Salvar">
        </label>
    </form>
@endsection
