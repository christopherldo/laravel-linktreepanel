@extends('admin.template')

@section('title', 'Home - LinkBree')

@section('content')

    <div class="card">
        <div class="card-header bg-dark p-4">
            <header class="d-flex justify-content-between">
                <h2 class="text-light">Suas páginas</h2>
                @if (count($pages) < 2)
                    <a class="d-flex align-items-center btn btn-warning" href="{{ route('admin.newpage') }}">
                        Nova Página
                    </a>
                @endif
            </header>
        </div>

        <div class="card-body d-flex flex-wrap">
            @foreach ($pages as $page)
                <div class="card flex-fill m-3">
                    <div class="card-header">
                        <td class="align-middle text-break">{{ $page->op_title }} ({{ $page->slug }})</td>
                    </div>
                    <div class="row row-cols-md-6 px-5 card-body d-flex flex-wrap">
                        <a class="btn d-inline-flex justify-content-center btn-primary m-1" href="{{ route('page', $page->slug) }}" target="_blank">Abrir</a>
                        <a class="btn d-inline-flex justify-content-center btn-dark m-1" href="{{ route('admin.links', $page->slug) }}">Links</a>
                        <a class="btn d-inline-flex justify-content-center btn-dark m-1" href="{{ route('admin.design', $page->slug) }}">Aparência</a>
                        <a class="btn d-inline-flex justify-content-center btn-dark m-1" href="{{ route('admin.stats', $page->slug) }}">Estatísticas</a>
                        <form class="d-block px-0" action="{{ route('admin.delete', $page->slug) }}" method="GET">
                            <input class="w-100 btn btn-danger m-1" type="submit" value="Excluir">
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        const deletionForms = document.querySelectorAll('form');

        deletionForms.forEach(form => {
            form.addEventListener('submit', event => {
                event.preventDefault();

                swal({
                        title: "Deseja excluir essa página?",
                        text: "Isso não pode ser desfeito.",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            form.submit();
                        };
                    });
            });
        });

    </script>
@endsection
