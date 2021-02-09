@extends('admin.page')

@section('body')
    <a class="bigbutton" href="{{route('admin.newlink', $page->slug)}}">
        Novo Link
    </a>

    <ul id="links">
        @foreach($links as $link)
            <li class="link--item" data-id="{{$link->public_id}}">
                <div class="link--item-order">
                    <img src="{{url('assets/images/sort.png')}}" alt="Ordenar" width="18">
                </div>
                <div class="link--item-info">
                    <div class="link--item-title">
                        {{$link->title}}
                    </div>
                    <div class="link--item-href">
                        <a href="{{$link->href}}">{{$link->href}}</a>
                    </div>
                </div>
                <div class="link--item-buttons">
                    <a href="{{url('admin/'. $page->slug . '/editlink/'. $link->public_id)}}">Editar</a>
                    <a href="{{url('admin/'. $page->slug . '/dellink/'. $link->public_id)}}">Excluir</a>
                </div>
            </li>
        @endforeach
    </ul>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.13.0/Sortable.min.js" integrity="sha512-5x7t0fTAVo9dpfbp3WtE2N6bfipUwk7siViWncdDoSz2KwOqVC1N9fDxEOzk0vTThOua/mglfF8NO7uVDLRC8Q==" crossorigin="anonymous"></script>
    <script>
        new Sortable(document.querySelector('#links'), {
            animation: 150,
            onEnd: async (e) => {
                let id = e.item.getAttribute('data-id');

                let link = `{{url('admin/linkorder/${id}/${e.newIndex}')}}`;

                await fetch(link);

                let iframe = document.querySelector('iframe');

                iframe.src = iframe.src;
            }
        });
    </script>
@endsection
