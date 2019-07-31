


<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Newly Approved Documents</h3>
    </div>
    <div class="box-body table-responsive home-box-maxh">
        @component('home.components.table')
        @slot('documents',$approved_documents)
        @endcomponent
    </div>
</div>

