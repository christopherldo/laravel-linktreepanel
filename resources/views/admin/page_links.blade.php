@extends('admin.page')

@section('title', $page->op_title . ' | Links - LinkBree')

@section('body')
    @if(count($links) === 0)
        <div class="d-flex flex-column m-4">
            Parece que você não tem um link ainda...<br>
            Vamos criar um.

            <a class="mt-3" href="{{route('admin.newlink', $page->slug)}}">É só clicar aqui.</a>
        </div>
    @endif

    <ul id="links" class="list-unstyled mt-4 px-3">
        @foreach ($links as $link)
            <li class="card w-100 my-2" data-id="{{ $link->public_id }}">
                <div>
                    <div class="card-header d-flex align-items-center">
                        <div class="mx-1 list-move p-2">
                            <svg class="d-block" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-arrows-move" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M7.646.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 1.707V5.5a.5.5 0 0 1-1 0V1.707L6.354 2.854a.5.5 0 1 1-.708-.708l2-2zM8 10a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 14.293V10.5A.5.5 0 0 1 8 10zM.146 8.354a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L1.707 7.5H5.5a.5.5 0 0 1 0 1H1.707l1.147 1.146a.5.5 0 0 1-.708.708l-2-2zM10 8a.5.5 0 0 1 .5-.5h3.793l-1.147-1.146a.5.5 0 0 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L14.293 8.5H10.5A.5.5 0 0 1 10 8z" />
                            </svg>
                        </div>
                        {{ $link->title }}
                    </div>
                    <div class="p-3 card-body align-items-center">
                        <a target="_blank" rel="noreferrer" class="btn btn-success" href="{{ $link->href }}">Ir</a>
                        <a class="btn btn-secondary"
                            href="{{ route('admin.editlink', ['slug' => $page->slug, 'linkid' => $link->public_id]) }}">Editar</a>
                        <a class="btn btn-danger"
                            href="{{ route('admin.dellink', ['slug' => $page->slug, 'linkid' => $link->public_id]) }}">Excluir</a>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.13.0/Sortable.min.js"
        integrity="sha512-5x7t0fTAVo9dpfbp3WtE2N6bfipUwk7siViWncdDoSz2KwOqVC1N9fDxEOzk0vTThOua/mglfF8NO7uVDLRC8Q=="
        crossorigin="anonymous"></script>
    <script>
        new Sortable(document.querySelector('#links'), {
            handle: '.list-move',
            animation: 150,
            onEnd: async (e) => {
                let id = e.item.getAttribute('data-id');

                let link = `{{ url('admin/linkorder/${id}/${e.newIndex}') }}`;

                await fetch(link);

                let iframe = document.querySelector('iframe');

                iframe.src = iframe.src;
            }
        });

    </script>
@endsection
