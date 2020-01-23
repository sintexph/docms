<div class="box box-solid" data-disable-interaction="1" data-step='5' data-position="bottom-middle-aligned" data-intro='Kini nga dapita mu gawas tanang mga documento na imong ge pangita'>
    <div class="box-header with-border bg-green">
        <h3 class="box-title">Newly Approved Documents</h3>
    </div>
    <div class="box-body table-responsive home-box-maxh">
        @component('home.components.table')
        @slot('documents',$approved_documents)
        @endcomponent
    </div>
</div>