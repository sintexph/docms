<tr>
    <td class="fit">
        <small class="document-access">
            <a href="#" title="{{ $document->access_type }}">{!! $document->access_icon !!}</a></small>
    </td>
      <td>
        <strong>
            @if($key==0)
                <a data-disable-interaction="1" data-step='6' data-position="bottom-middle-aligned" data-intro='E click ang kining nga numero para makita nimo ang sulod sa documento' href="{{ route('home.view_document',$document->active_version->id) }}">
            @else
                <a href="{{ route('home.view_document',$document->active_version->id) }}">
            @endif
            
            @if($document->obsolete==true)
                <strike>{{ $document->document_number }}</strike>
            @else
                {{ $document->document_number }}
            @endif
            </a>
        </strong>
            <div>{{ $document->title }}</div>
        </td>
    <td>{{ $document->active_version->version }}</td>
    <td class="fit">{{ $document->active_version->effective_date_formatted }}</td>
</tr>
