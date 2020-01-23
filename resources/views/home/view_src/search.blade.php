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
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Document</th>
                    <th>Version</th>
                    <th class="fit">Effective Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)

                <tr>
                    <td class="fit">
                        <small class="document-access"><a href="#" title="{{ $document->access_type }}">{!!
                                $document->access_icon !!}</a></small>
                    </td>
                    <td>
                        <strong>
                            @if($document->access=="3")
                            <a href="{{ route('home.view_document',$document->active_version->id) }}">
                                @if($document->obsolete==true)
                                <strike>{{ $document->document_number }}</strike>
                                @else
                                {{ $document->document_number }}
                                @endif
                            </a>

                            @else

                            <span>
                                @if($document->obsolete==true)
                                <strike>{{ $document->document_number }}</strike>
                                @else
                                {{ $document->document_number }}
                                @endif
                            </span>

                            @endif
                        </strong>
                        <br>
                        <span>{{ $document->title }}</span><br>

                    </td>
                    <td>{{ $document->active_version->version }}</td>
                    <td class="fit">{{ $document->active_version->effective_date_formatted }}</td>

                </tr>

                @endforeach
            </tbody>
        </table>
        @endif

        @if(count($documents)!=0)
        {{ $documents->appends(request()->except('page'))->links() }}
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
