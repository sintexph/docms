@sintexemail
@slot('brand','Document Management System')
@slot('url','http://docms.sportscity.com.ph')
@slot('content')
<p>Hi {{ $receiver }},</p>

<p>You have been set as <strong>Approver</strong> of the document that was been created by {{$creator}} and below are the details.</p>
<span><strong>Document No.:</strong> {{ $document_number }}</span><br>
<span><strong>Version:</strong> {{ $version_number }}</span><br>
<span><strong>Title:</strong> {{ $title }}</span><br>
<span><strong>System:</strong> {{ $system }}</span><br>
<span><strong>Section:</strong> {{ $section }}</span><br>
<span><strong>Category:</strong> {{ $category }}</span><br>
 <br>
 <br>
<p>To view the full document information and content please access the link below and also please update the status of the document once you have already reviewed it.</p>
<a href="{{ route('for_approval.view',$document_approving_id) }}">Full Document Link</a>
 
@endslot
@endsintexemail
