@sintexemail
@slot('brand','Document Management System')
@slot('url','http://docms.sportscity.com.ph')
@slot('content')
<p>Hi {{ $receiver }},</p>

<p>This is to inform you that the document <strong>{{ $title }} | {{ $document_number }}</strong> was modified and you will be needing to approved again. You will be receiving a notification from the system when it is ready to be approved.</p>
 <br>
<p>To view the full document information and content please access the link below and also please update the status of the document once you have already approved it.</p>
<a href="{{ route('for_approval.view',$document_approver_id) }}">Full Document Link</a>
 
@endslot
@endsintexemail
