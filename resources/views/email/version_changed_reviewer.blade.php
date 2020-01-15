@sintexemail
@slot('brand','Document Management System')
@slot('url','http://docms.sportscity.com.ph')
@slot('content')
<p>Hi {{ $receiver }},</p>

<p>This is to inform you that the document <strong>{{ $title }} | {{ $document_number }}</strong> was modified and you will be needing to review again. You will be receiving a notification from the system when it is ready to be reviewed.</p>
 <br>
 <br>
<p>To view the full document information and content please access the link below and also please update the status of the document once you have already reviewed it.</p>
<a href="{{ route('for_review.view',$document_reviewer_id) }}">Full Document Link</a>
 
@endslot
@endsintexemail
