<div class="box box-solid">
    <div class=" box-header with-border">
        <h3 class="box-title">Old Versions</h3>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Version</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Reviewed</th>
                    <th>Approved</th>
                    <th>Released</th>
                </tr>
            </thead>
            <tbody>
                @foreach($old_versions as $old_version)

                <tr>
                    <td>{{ $old_version->id }}</td>
                    <td>{{ $old_version->version }}</td>

                    <td>{!! $old_version->creator->name !!}</td>
                    <td>{!! $old_version->created_at !!}</td>
                    <td>
                        @if($old_version->reviewed==true)
                        <span>REVIEWED</span>
                        @endif
                    </td>

                    <td>
                        @if($old_version->approved==true)
                        <span>APPROVED</span>
                        @endif
                    </td>

                    <td>
                        @if($old_version->released==true)
                        <span>RELEASED</span>
                        @endif
                    </td>
                </tr>




                @endforeach
            </tbody>
        </table>

    </div>
</div>
