<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="{{ $current_view==false?'active':'' }}"><a href="{{ route('manage.documents.view',$document->id) }}"><i
                    class="fa fa-file" aria-hidden="true"></i> Document</a></li>


        @if(auth()->user()->can('initiate_action',$document))

                @if($selected_version->reviewed==true && $selected_version->released==true && $selected_version->approved==true)
                <li class="{{ $current_view=='new'?'active':'' }}"><a href="?view=new"><i class="fa fa-code-fork" aria-hidden="true"></i>
                        New Version</a></li>
                @endif
        
                @if($document->current_version->approved==false)
                <li class="{{ $current_view=='edit'?'active':'' }}">
                    <a href="?view=edit">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        Edit Document
                    </a>
                </li>
                @endif
                
        <li class="{{ $current_view=='edit_access'?'active':'' }}"><a href="?view=edit_access"><i class="fa fa-folder-open"
                    aria-hidden="true"></i> Edit Access</a></li>
        @endif

        <li class="{{ $current_view=='histories'?'active':'' }}"><a href="?view=histories"><i class="fa fa-history"
                    aria-hidden="true"></i> Histories</a></li>

        @if(auth()->user()->can('lock',$document))
        <li class="{{ $current_view=='lock'?'active':'' }}"><a href="?view=lock">
                @if($document->locked==true)
                <i class="fa fa-unlock-alt" aria-hidden="true"></i> Unlock</a>
            @else
            <i class="fa fa-lock" aria-hidden="true"></i> Lock</a>
            @endif
        </li>
        @endif

        @if(auth()->user()->can('status',$document))
        <li class="{{ $current_view=='change_status'?'active':'' }}"><a href="?view=change_status">
                <i class="fa fa-pencil" aria-hidden="true"></i>
                Change Status</a>
        </li>
        @endif

        @if(auth()->user()->can('archive',$document))
        <li class="{{ $current_view=='archive'?'active':'' }}"><a href="?view=archive">
                <i class="fa fa-archive" aria-hidden="true"></i>
                Archive</a>
        </li>
        @endif
    </ul>

</div>
