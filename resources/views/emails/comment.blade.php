@sintexemail
@slot('brand','Document Management System')
@slot('url','http://docms.sportscity.com.ph')
@slot('content')

@foreach($document_version->comments()->limit(4)->orderBy('created_at','desc')->get() as $comment)
<div style="font-size:12px; font-weight: 700; color:#686868; margin-bottom:5px;">{{ $comment->creator->name }}</div>
<div style="margin-bottom:5px;">{!! $comment->comment_html !!}</div>
<span style="font-size:0.5em; color:#aaaaaa;"><small>{{ $comment->created_at }}</small></span>
<hr>
@endforeach


<p><strong>Document Information</strong></p>
<table style="font-size:13px; font-family:Arial;">
    <tbody>
        <tr>
            <td><strong>Document #</strong></td>
            <td style="padding-left:20px;"><small>{{ $document_version->document->document_number }}</small></td>
        </tr>
        <tr>
            <td><strong>Title</strong></td>
            <td style="padding-left:20px;"><strong>{{ $document_version->document->title }}</strong></td>
        </tr>
        <tr>
            <td><strong>System</strong></td>
            <td style="padding-left:20px;">
                @if($document_version->document->system==null)
                {{ $document_version->document->system_code }}
                @else
                {{ $document_version->document->system->name }}
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>Section</strong></td>
            <td style="padding-left:20px;">
                @if($document_version->document->section==null)
                {{ $document_version->document->section_code }}
                @else
                {{ $document_version->document->section->name }}
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>Category</strong></td>
            <td style="padding-left:20px;">
                @if($document_version->document->category==null)
                {{ $document_version->document->category_code }}
                @else
                {{ $document_version->document->category->name }}
                @endif
            </td>
        </tr>
        <tr>
            <td class="fit">Latest Version</strong></td>
            <td style="padding-left:20px;">{{ $document_version->version }}</td>
        </tr>
        <tr>
            <td><strong>Owner</strong></td>
            <td style="padding-left:20px;">{{ $document_version->creator->name }} - <a
                    href="mailto:{{ $document_version->creator->email }}" class="infos">
                    {{ $document_version->creator->email }}
                </a>
            </td>
        </tr>
        <tr>
            <td><strong>Created</strong></td>
            <td style="padding-left:20px;">{{ $document_version->created_at }}</td>
        </tr>
        @if(!empty($document_url))
         <tr>
            <td><strong>Link</strong></td>
            <td style="padding-left:20px;"><a href="{{ $document_url }}">{{ $document_url }}</a></td>
        </tr>
        @endif
    </tbody>
</table>


@endslot
@endsintexemail
