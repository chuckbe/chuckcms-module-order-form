<div class="menuArea row">
    <div class="container">
        <nav>
            <ul class="nav nav-pills" id="navigationTab" role="tablist">
                @php $c = 0; @endphp
                @foreach($categories as $category)
                @if($category->is_displayed)
                <li class="nav-item mr-3">
                    <a class="nav-link{{ $c == 0 ? ' active' : '' }}" id="navigationCategory{{$category->id}}Tab" href="#category{{$category->id}}Tab" role="tab" data-toggle="tab" aria-controls="category{{$category->id}}Tab" aria-selected="true">{{ $category->name }}</a>
                </li>
                @php $c++; @endphp
                @endif
                @endforeach
            </ul>
        </nav>
    </div>
</div>