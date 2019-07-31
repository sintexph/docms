<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-search" aria-hidden="true"></i> Search Result</h3>
    </div>
    <div class="box-body home-box-maxh">

        <div class="gsc-results gsc-webResult" style="display: block;">
            <div class="gsc-webResult gsc-result">
                @foreach($search_result as $result)
                <div class="gs-webResult gs-result">


                    <table class="gsc-table-result">
                        <tbody>
                            <tr>
                                <td class="gsc-table-cell-thumbnail gsc-thumbnail" style="display:none"></td>
                                <td class="gsc-table-cell-snippet-close">
                                    <div class="gs-title gsc-table-cell-thumbnail gsc-thumbnail-left">
                                        <a class="gs-title" href="{{ route('home.view_document',$result->id) }}" target="_blank"><b>{{
                                                $result->document_number }}</b> {{ $result->title }} </a>
                                    </div>
                                    <div><span></span></div>
                                    <div class="gs-bidi-start-align gs-snippet" dir="ltr">{{
                                        $result->current_version->created_at }} <b>...</b>
                                        {{ $result->current_version->ellipsis_content }}
                                        <br>
                                        <strong>State:</strong>
                                        @if($result->obsolete==true)
                                        <span class="text-yellow">OBSOLETE</span>
                                        @else
                                        <span>ACTIVE</span>
                                        @endif

                                        <br>
                                        <strong>Access:</strong>
                                        {{ $result->access_type }}



                                    </div>
                                    <div class="gsc-url-bottom">
                                        <strong>Link:</strong>
                                        <a href="{{ route('home.view_document',$result->id) }}" target="_blank">
                                            {{ route('home.view_document',$result->id) }}
                                        </a>
                                    </div>
                                    <div class="gs-richsnippet-box" style="display: none;"></div>
                                    <div class="gs-per-result-labels" url="{{ route('home.view_document',$result->id) }}"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                @endforeach
                




            </div>



        </div>

        @if(count($search_result)==0)
        <center>
            <h3><i class="fa fa-search" aria-hidden="true"></i></h3>
            <h5>We did not find any result for "{{ app('request')->input('search') }}". Sorry!</h5>
        </center>
        @endif

    </div>
</div>
