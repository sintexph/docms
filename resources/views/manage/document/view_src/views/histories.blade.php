<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">Action Histories</h3>
    </div>
    <div class="box-body  home-box-maxh">
        <div class="table-responsive">
        <table class="table table-hover table-bordered table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($document_action_histories as $history)
                <tr>
                    <td class="fit">{!! $history->created_at !!}</td>
                    <td class="fit">{!! $history->created_by !!}</td>
                    <td>
                        {!! $history->content !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        
    </div>

    <div class="box-footer">
        {{ $document_action_histories->appends(request()->except('page'))->links() }} 
    </div>
</div>

