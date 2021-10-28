<div class="menuArea row mx-0 mb-2">
    <div class="container">
        <nav>
            <ul class="nav nav-pills" id="navigationTab" role="tablist">
                @php $c = 0; @endphp
                @foreach($categories as $category)
                @if($category->is_pos_available)
                <li class="nav-item mr-2 mr-md-3 mb-1">
                    <a class="nav-link{{ $c == 0 ? ' active' : '' }} px-2 py-1" id="navigationCategory{{$category->id}}Tab" href="#category{{$category->id}}Tab" role="tab" data-toggle="tab" aria-controls="category{{$category->id}}Tab" aria-selected="true">{{ $category->name }}</a>
                </li>
                @php $c++; @endphp
                @endif
                @endforeach
            </ul>
        </nav>
    </div>
</div>