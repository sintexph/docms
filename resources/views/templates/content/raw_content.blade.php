@extends('templates.content.app')

@section('content')

{!! $document_version->castedContent()->toString() !!}

@endsection
