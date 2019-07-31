



<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Documents under <a href="#">{{ $system_db->name }}</a> and <a href="#">{{ $section_db->name }}</a> section</h3>
    </div>
    <div class="box-body table-responsive home-box-maxh">
        @component('home.components.table')
        @slot('documents',$documents)
        @endcomponent

    </div>
</div>
