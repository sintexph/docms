<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-search" aria-hidden="true"></i> Search Result</h3>
    </div>
    <div class="box-body home-box-maxh">





        @if(count($documents)==0)

        <center>
            <h3><i class="fa fa-search" aria-hidden="true"></i></h3>
            <h5>We did not find any search result for your. Sorry!</h5>
        </center>
        @else
            @component('home.components.table')
                @slot('documents',$documents)
            @endcomponent
        @endif

 

    </div>
    <div class="box-footer">

        <ol class="breadcrumb">
            @if(!empty($category_db))
            <li><a href="/?cat={{ $category_db->code }}">{{ $category_db->name }}</a></li>
            @endif

            @if(!empty($system_db))
            <li><a href="/?system={{ $system_db->code }}">{{ $system_db->name }}</a></li>
            @endif


            @if(!empty($section_db))
            <li><a href="/?section={{ $section_db->code }}">{{ $section_db->name }}</a></li>
            @endif

        </ol>
    </div>
</div>
