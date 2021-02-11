@extends('admin.template')

@section('title', 'Nova Página - LinkBree')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Nova Página</h2>
        </div>
        <div class="card-body">
            @if (count($errors) > 0)
                <div class="alert alert-danger mt-1">
                    @foreach ($errors as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form class="my-3" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-2">
                    <label class="form-label" for="slug">Link:</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">{{ url('/') . '/' }}</span>
                        <input class="form-control" aria-describedby="slugHelp" required type="text" name="slug" id="slug"
                            minlength="2" maxlength="16" value="{{ old('slug') }}">
                    </div>
                    <div id="slugHelp" class="mt-n1 form-text">Esse é o link da sua página</div>
                </div>

                <div class="d-flex flex-wrap justify-content-center my-5">
                    <div class="m-3">
                        <label for="op_font_color" class="form-label text-center">Cor da fonte:</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon2"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" fill="currentColor" class="bi bi-palette" viewBox="0 0 16 16">
                                    <path
                                        d="M8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm4 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM5.5 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm.5 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                                    <path
                                        d="M16 8c0 3.15-1.866 2.585-3.567 2.07C11.42 9.763 10.465 9.473 10 10c-.603.683-.475 1.819-.351 2.92C9.826 14.495 9.996 16 8 16a8 8 0 1 1 8-8zm-8 7c.611 0 .654-.171.655-.176.078-.146.124-.464.07-1.119-.014-.168-.037-.37-.061-.591-.052-.464-.112-1.005-.118-1.462-.01-.707.083-1.61.704-2.314.369-.417.845-.578 1.272-.618.404-.038.812.026 1.16.104.343.077.702.186 1.025.284l.028.008c.346.105.658.199.953.266.653.148.904.083.991.024C14.717 9.38 15 9.161 15 8a7 7 0 1 0-7 7z" />
                                </svg></span>
                            <input required type="color" class="form-control" style="height: 40px; width: 100px;"
                                name="op_font_color" id="op_font_color" value="{{ old('op_font_color') ?? '#ffffff'}}">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="m-3">
                            <label for="op_bg_value_1" class="form-label text-center">Cor primária:</label>
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
                                    name="op_bg_value_1" id="op_bg_value_1" value="{{ old('op_bg_value_1') ?? '#fc4a1a' }}">
                            </div>
                        </div>
                        <div class="m-3">
                            <label for="op_bg_value_2" class="form-label text-center">Cor secundária:</label>
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
                                    name="op_bg_value_2" id="op_bg_value_2" value="{{ old('op_bg_value_2') ?? '#f7b733' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="op_profile_image" class="form-label">Foto de perfil:</label>
                    <input class="form-control" name="op_profile_image" type="file" id="op_profile_image">
                </div>

                <div class="mb-4">
                    <label class="form-label" for="op_title">Título da página:</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon5"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                height="16" fill="currentColor" class="bi bi-type-h1" viewBox="0 0 16 16">
                                <path
                                    d="M8.637 13V3.669H7.379V7.62H2.758V3.67H1.5V13h1.258V8.728h4.62V13h1.259zm5.329 0V3.669h-1.244L10.5 5.316v1.265l2.16-1.565h.062V13h1.244z" />
                            </svg></span>
                        <input class="form-control" type="text" name="op_title" id="op_title" minlength="2" maxlength="100"
                            value="{{ old('op_title') }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="op_description" class="form-label">Descrição da página:</label>
                    <textarea class="form-control" name="op_description" id="op_description"
                        style="resize: none; height: 100px">{{ old('op_description') }}</textarea>
                </div>


                <div class="row row-cols-md-2 d-flex justify-content-center px-3">
                    <input class="btn btn-success" type="submit" value="Salvar">
                </div>
            </form>
        </div>
    </div>

@endsection
