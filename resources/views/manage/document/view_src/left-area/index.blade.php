@if($selected_version->active==false)
<div class="clearfix alert-custom alert-custom-warning">You are <strong>viewing</strong> an old version of this document. <span class="pull-right">Click <a href="{{ route('manage.documents.view',$document->id) }}">here</a> to get to the latest version</span></div>
@endif 
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="{{ empty($left_area_tab)?'active':'' }}"><a href="?view={{ $current_view }}&ver={{ app('request')->input('ver') }}">Current Version</a></li>
        <li class="{{ $left_area_tab=='at'?'active':'' }}"><a href="?view={{ $current_view }}&latab=at&ver={{ app('request')->input('ver') }}">Attachments ({{ count($attachments) }})</a></li>
        @if(count($old_versions)!=0)
        <li class="{{ $left_area_tab=='ov'?'active':'' }}"><a href="?view={{ $current_view }}&latab=ov">Old Versions</a></li>
        @endif
        <li class="{{ $left_area_tab=='rl'?'active':'' }}"><a href="?view={{ $current_view }}&latab=rl">Revision Logs</a></li>
        <li class="{{ $left_area_tab=='rd'?'active':'' }}"><a href="?view={{ $current_view }}&latab=rd">References ({{ count($reference_documents) }})</a></li>
    </ul>
</div>
@switch($left_area_tab)
    @case('ov')
        @include('manage.document.view_src.left-area.tabs.old_versions')
        @break

    @case('rl')
        @include('manage.document.view_src.left-area.tabs.revision_logs')
        @break

    @case('at')
        @include('manage.document.view_src.left-area.tabs.attachments')
        @break
        
    @case('rd')
        @include('manage.document.view_src.left-area.tabs.reference_documents')
        @break

    @case('ev')
        @include('manage.document.view_src.left-area.tabs.edit_version')
        @break
        
    @default
        @include('manage.document.view_src.left-area.tabs.current_version')
@endswitch

