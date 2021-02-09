@extends('admin.template')

@section('title', 'Nova Página - LinkBree')

@section('content')

    <header>
        <h2>Nova página</h2>
    </header>

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
            <input autofocus required type="text" name="slug" id="slug" minlength="2" maxlength="16" value="{{ old('slug') }}">
        </label>

        <label for="op_font_color">
            Cor da fonte:<br>
            <input required type="color" name="op_font_color" id="op_font_color" value="{{ old('op_font_color') ?? '#ffffff'}}">
        </label>

        {{-- <label for="op_bg_type">
            Tipo de fundo:<br>
            <select name="op_bg_type" id="op_bg_type">
                <option value="color">Cor sólida</option>
                <option selected value="gradient">Degradê</option>
            </select>
        </label> --}}

        <label for="op_bg_value_1">
            Cor de fundo 1:<br>
            <input required type="color" name="op_bg_value_1" id="op_bg_value_1" value="{{ old('op_bg_value_1') ?? '#fc4a1a'}}">
        </label>
        <label for="op_bg_value_2">
            Cor de fundo 2:<br>
            <input required type="color" name="op_bg_value_2" id="op_bg_value_2" value="{{ old('op_bg_value_2') ?? '#f7b733'}}">
        </label>


        <label for="op_profile_image">
            Foto do perfil:<br>
            <input type="file" name="op_profile_image" id="op_profile_image">
        </label>

        <label for="op_title">
            Título da página:<br>
            <input type="text" maxlength="100" name="op_title" id="op_title" value="{{old('op_title')}}">
        </label>

        <label for="op_description">
            Descrição da página:<br>
            <textarea name="op_description" id="op_description" style="resize: none; width: 100%; height: 100px">{{old('op_description')}}</textarea>
        </label>

        {{-- <label for="op_fb_pixel">
            Pixel do Facebook:<br>
            <input type="text" name="op_fb_pixel" id="op_fb_pixel">
        </label> --}}

        <label>
            <input type="submit" value="Criar">
        </label>
    </form>

@endsection
