<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Revision Logs</h3>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-hover table-bordered table-striped">
            <thead>
                <tr>
                    <th class="fit">Revision Level</th>
                    <th class="fit">Effectivity Date</th>
                    <th>Description</th>
                    <th class="fit">Change Initiator</th>
                    <th>Reviewer(s)</th>
                    <th>Approver(s)</th>
                </tr>
            </thead>
            <tbody>

                @foreach($document->versions as $version_log)

                <tr>
                    <td>{{ $version_log->version }}</td>
                    <td class="fit">{{ $version_log->effective_date_formatted }}</td>
                    <td>{!! $version_log->castedDescription()->toString() !!}</td>
                    <td>{!! $version_log->creator->name !!}</td>
                    <td>
                        @foreach($version_log->reviewers as $version_log_reviewers)
                        {{ $version_log_reviewers->user->name }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach($version_log->approvers as $version_log_approver)
                        {{ $version_log_approver->user->name }}<br>
                        @endforeach
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    

    </div>
</div>