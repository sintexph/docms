
@if(count($errors)>0)
<div class="alert alert-danger">
    <strong>Validation Failed!</strong>
    <br>
     @foreach ($errors->all() as $error)
    <div>{{ $error }}</div>
    @endforeach
</div>
@endif

@if(session('warning'))
<div class="alert alert-warning">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Warning!</strong> {{session('warning')}}
</div>
@endif 

@if(session('success'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Success!</strong> {{session('success')}}
</div>
@endif 


@if(session('error'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Failed!</strong> {{session('error')}}
</div>
@endif 
