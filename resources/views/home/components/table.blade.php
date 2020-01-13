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

        <tr>
            <td class="fit">
                <small class="document-access">
                <a href="#" title="{{ $document->access_type }}">{!! $document->access_icon !!}</a></small>
            </td>
            <td>
                <strong>
                    @if($document->access=="3")

                        @if($key==0)
                            <a data-disable-interaction="1" data-step='5' data-position="bottom-middle-aligned" data-intro='E click ang kining nga numero para makita nimo ang sulod sa documento' href="{{ route('home.view_document',$document->active_version->id) }}">
                        @else
                             <a href="{{ route('home.view_document',$document->active_version->id) }}">
                        @endif

                        @if($document->obsolete==true)
                        <strike>{{ $document->document_number }}</strike>
                        @else
                        {{ $document->document_number }}
                        @endif
                        </a>
                    @else

                    <span>
                        @if($document->obsolete==true)
                        <strike>{{ $document->document_number }}</strike>
                        @else
                        {{ $document->document_number }}
                        @endif
                    </span>

                    @endif
                </strong>
                <br>
                <span>{{ $document->title }}</span><br>

            </td>
            <td>{{ $document->active_version->version }}</td>
            <td class="fit">{{ $document->active_version->effective_date_formatted }}</td>

        </tr>

        @endforeach
    </tbody>
</table>


@if(count($documents)!=0)
{{ $documents->appends(request()->except('page'))->links() }}
@endif
