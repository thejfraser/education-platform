<section class="pagination bg-light p-3 text-center">
    <nav aria-label="Page Navigation">
        <ul class="pagination">
            @if ($currentPage > 1)
                <li class="page-item"><a class="page-link" href="{{$currentPage - 1}}">Previous</a></li>
                <li class="page-item"><a class="page-link" href="{{$currentPage - 1}}">{{$currentPage - 1}}</a></li>
            @endif
            <li class="page-item"><a class="page-link" href="#">{{$currentPage}}</a></li>
            @if ($currentPage < $maxPage)
                <li class="page-item"><a class="page-link" href="{{$currentPage + 1}}">{{$currentPage + 1}}</a></li>
                <li class="page-item"><a class="page-link" href="{{$currentPage + 1}}">Next</a></li>
            @endif
        </ul>
    </nav>
</section>
