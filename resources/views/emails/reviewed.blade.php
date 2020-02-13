@sintexemail
@slot('brand','Document Management System')
@slot('url','http://docms.sportscity.com.ph')
@slot('content')
<p>Hi {{ $receiver }},</p>

<p>The document that you have created (<i>{{ $document_number }} - {{ $title }}</i>) has been reviewed by {{ $document_reviewer->user->name }}.<br>
To view the full document information and content please access the link below.</p>
<a href="{{ route('manage.documents.view',$document_id) }}">Full Document Link</a>
 
@endslot
@endsintexemail
