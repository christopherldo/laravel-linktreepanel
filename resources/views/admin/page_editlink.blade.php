@extends('admin.page')

@section('body')
    <h2 class="my-2">{{ isset($link) ? 'Editar Link' : 'Novo Link' }}</h2>

    @if (count($errors) > 0)
        <div class="alert alert-danger my-2">
            @foreach ($errors as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form class="my-3" method="POST">
        @csrf

        <div class="mb-2">
            <label class="form-label" for="status">Status:</label>
            <select class="form-select" name="status" id="status">
                <option {{ isset($link) ? ($link->status === 1 ? 'selected' : '') : '' }} value="1">Ativado</option>
                <option {{ isset($link) ? ($link->status === 0 ? 'selected' : '') : '' }} value="0">Desativado</option>
            </select>
        </div>

        <div class="mb-2">
            <label for="title" class="form-label">Título:</label>
            <input class="form-control" minlength="2" maxlength="100" required type="text" name="title" id="title"
                value="{{ isset($link) ? $link->title : old('title') }}">
        </div>

        <div class="href">
            <label for="title" class="form-label">Link:</label>
            <input class="form-control" required type="url" name="href" id="href" value="{{ isset($link) ? $link->href : old('href') }}">
        </div>

        <div class="d-flex flex-wrap justify-content-center my-2">
            <div class="d-flex">
                <div class="m-3">
                    <label for="op_bg_color" class="form-label text-center">Cor do fundo:</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon3"><svg xmlns="http://www.w3.org/2000/svg"
                                width="16" height="16" fill="currentColor" class="bi bi-palette"
                                viewBox="0 0 16 16">
                                <path
                                    d="M8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm4 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM5.5 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm.5 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                                <path
                                    d="M16 8c0 3.15-1.866 2.585-3.567 2.07C11.42 9.763 10.465 9.473 10 10c-.603.683-.475 1.819-.351 2.92C9.826 14.495 9.996 16 8 16a8 8 0 1 1 8-8zm-8 7c.611 0 .654-.171.655-.176.078-.146.124-.464.07-1.119-.014-.168-.037-.37-.061-.591-.052-.464-.112-1.005-.118-1.462-.01-.707.083-1.61.704-2.314.369-.417.845-.578 1.272-.618.404-.038.812.026 1.16.104.343.077.702.186 1.025.284l.028.008c.346.105.658.199.953.266.653.148.904.083.991.024C14.717 9.38 15 9.161 15 8a7 7 0 1 0-7 7z" />
                            </svg></span>
                        <input required type="color" class="form-control" style="height: 40px; width: 100px;"
                            name="op_bg_color" id="op_bg_color" value="{{ isset($link) ? $link->op_bg_color : old('op_bg_color') ?? '#ffffff' }}">
                    </div>
                </div>
                <div class="m-3">
                    <label for="op_text_color" class="form-label text-center">Cor do texto:</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon4"><svg xmlns="http://www.w3.org/2000/svg"
                                width="16" height="16" fill="currentColor" class="bi bi-palette"
                                viewBox="0 0 16 16">
                                <path
                                    d="M8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm4 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM5.5 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm.5 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                                <path
                                    d="M16 8c0 3.15-1.866 2.585-3.567 2.07C11.42 9.763 10.465 9.473 10 10c-.603.683-.475 1.819-.351 2.92C9.826 14.495 9.996 16 8 16a8 8 0 1 1 8-8zm-8 7c.611 0 .654-.171.655-.176.078-.146.124-.464.07-1.119-.014-.168-.037-.37-.061-.591-.052-.464-.112-1.005-.118-1.462-.01-.707.083-1.61.704-2.314.369-.417.845-.578 1.272-.618.404-.038.812.026 1.16.104.343.077.702.186 1.025.284l.028.008c.346.105.658.199.953.266.653.148.904.083.991.024C14.717 9.38 15 9.161 15 8a7 7 0 1 0-7 7z" />
                            </svg></span>
                        <input required type="color" class="form-control" style="height: 40px; width: 100px;"
                            name="op_text_color" id="op_text_color" value="{{ isset($link) ? $link->op_text_color : old('op_text_color') ?? '#000000' }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label" for="op_border_type">Bordas:</label>
            <select class="form-select" name="op_border_type" id="op_border_type">
                <option {{ isset($link) ? ($link->op_border_type === 'rounded' ? 'selected' : '') : '' }} value="rounded">
                    Arredondadas</option>
                <option {{ isset($link) ? ($link->op_border_type === 'square' ? 'selected' : '') : '' }} value="square">
                    Quadradas</option>
            </select>
        </div>

        <div class="row row-cols-md-2 d-flex justify-content-center px-3 mt-4">
            <input class="btn btn-success" type="submit" value="Salvar">
        </div>
    </form>
@endsection
