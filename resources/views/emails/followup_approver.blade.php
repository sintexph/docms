@sintexemail
@slot('brand','Document Management System')
@slot('url','http://docms.sportscity.com.ph')
@slot('content')
<p>Hi {{ $receiver }},</p>

<p>This is to inform you that there's still a document <strong>{{ $title }} | {{ $document_number }}</strong> that needs your approval.</p>
 <br>
<p>To view the full document information and content please access the link below and also please update the status of the document once you have already approved it.</p>
<a href="{{ route('for_approval.view',$document_approver_id) }}">Full Document Link</a>
 
@endslot
@endsintexemail
