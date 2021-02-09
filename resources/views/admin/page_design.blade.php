@extends('admin.page')

@section('body')

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

    <form method="POST" enctype="multipart/form-data">
        @csrf
        <label for="slug">
            Link: {{ url('/') }}/<br>
            <input autofocus required type="text" name="slug" id="slug" minlength="2" maxlength="16"
                value="{{ old('slug') ?? $page->slug }}">
        </label>

        <label for="op_font_color">
            Cor da fonte:<br>
            <input required type="color" name="op_font_color" id="op_font_color"
                value="{{ old('op_font_color') ?? $page->op_font_color }}">
        </label>

        <label for="op_bg_value_1">
            Cor de fundo 1:<br>
            <input required type="color" name="op_bg_value_1" id="op_bg_value_1"
                value="{{ old('op_bg_value_1') ?? $page->op_bg_value[0] }}">
        </label>
        <label for="op_bg_value_2">
            Cor de fundo 2:<br>
            <input required type="color" name="op_bg_value_2" id="op_bg_value_2"
                value="{{ old('op_bg_value_2') ?? $page->op_bg_value[1] }}">
        </label>


        <label for="op_profile_image">
            Foto do perfil:<br>
            <input type="file" name="op_profile_image" id="op_profile_image">
        </label>

        <label for="op_title">
            Título da página:<br>
            <input type="text" maxlength="100" name="op_title" id="op_title"
                value="{{ old('op_title') ?? $page->op_title }}">
        </label>

        <label for="op_description">
            Descrição da página:<br>
            <textarea name="op_description" id="op_description"
                style="resize: none; width: 100%; height: 100px">{{ old('op_description') ?? $page->op_description }}</textarea>
        </label>

        <label>
            <input type="submit" value="Salvar">
        </label>
    </form>
@endsection
