<div class="row">
    <div class="col-sm-8">
        @include('manage.document.view_src.left-area.index')
    </div>
    <div class="col-sm-4">
        <div class="box box-solid">
            <div class=" box-header with-border">
                <h3 class="box-title">Document Information</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-hover table-bordered ">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <td>{{ $document->id }}</td>
                        </tr>
                        <tr>
                            <th>Document #</th>
                            <td><small>{{ $document->document_number }}</small></td>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td><strong>{{ $document->title }}</strong></td>
                        </tr>
                        <tr>
                            <th>System</th>
                            @if($document->system!=null)
                            <td>{{ $document->system->name }}</td>
                            @else
                            <td><code>{{ $document->system_code }}</code></td>
                            @endif
                        </tr>
                        <tr>
                            <th>Section</th>
                            @if($document->section!=null)
                            <td>{{ $document->section->name }}</td>
                            @else
                            <td><code>{{ $document->section_code }}</code></td>
                            @endif
                        </tr>
                        <tr>
                            <th>Category</th>
                            @if($document->category!=null)
                            <td>{{ $document->category->name }}</td>
                            @else
                            <td><code>{{ $document->category_code }}</code></td>
                            @endif
                        </tr>
                        <tr>
                            <th class="fit">Latest Version</th>
                            <td>{{ $document->version }}</td>
                        </tr>
                        <tr>
                            <th>Owner</th>
                            <td>
                                <a class="infos" href="mailto:{{ $document->creator->email }}">
                                    {{ $document->creator->name }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td>{{ $document->created_at }}</td>
                        </tr>
                        <tr>
                            <th>Comment</th>
                            <td>{!! nl2br($document->comment) !!}</td>
                        </tr>
                        <tr>
                            <th>Access</th>
                            <td><strong><a href="#" title="Access Type">{!! $document->access_icon !!} {{
                                        $document->access_type }}</a></strong></td>
                        </tr>
                        <tr>
                            <th>Keywords</th>
                            <td>
                                @foreach($document->keywords as $keyword)
                                <a href="#" title="{{ $keyword }}"><small>{{ $keyword }}</small>&nbsp;&nbsp;</a>
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>


        @if($left_area_tab!='ev')
            <document-version-action :document="{{ json_encode($document) }}" :selected_version="{{ json_encode($selected_version) }}"></document-version-action>
        @endif
 

    </div>
</div>
