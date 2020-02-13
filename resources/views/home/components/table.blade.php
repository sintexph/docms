<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th></th>
            <th>Document</th>
            <th>Version</th>
            <th  class="fit">Effective Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($documents as $key=>$document)
            @if(auth()->user()!=null)
                @if(auth()->user()->can('view',$document))
                    @include('home.components.data')
                @endif
            @elseif($document->access==DocumentAccess::_PUBLIC)
                @include('home.components.data')
            @endif
        @endforeach
    </tbody>
</table>


@if(count($documents)!=0)
{{ $documents->appends(request()->except('page'))->links() }}
@endif
