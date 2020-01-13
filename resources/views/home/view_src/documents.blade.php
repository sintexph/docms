<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Documents under <a href="/?system={{ $system_db->id }}">{{ $system_db->name }}</a>
            @if(!empty($section_db))
            and <a href="/?system={{ $system_db->id }}&sec={{ $section_db->id }}">{{ $section_db->name }}</a> section
            @endif
        </h3>
    </div>
    <div class="box-body table-responsive home-box-maxh">
        @component('home.components.table')
        @slot('documents',$documents)
        @endcomponent
    </div>
</div>
