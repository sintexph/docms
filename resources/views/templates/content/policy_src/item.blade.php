@foreach($content->items as $item)


@switch($item->type)

@case('list')
{!! $item->data->htmlRepresentation() !!}
@break

@case('paragraph')
{!! $item->data->meta['html'] !!}
@break

@case('table')

@component('templates.content.policy_src.types.table')
@slot('header',$item->data->header)
@slot('rows',$item->data->rows)
@endcomponent
@break



@case('image')

@if($item->data->meta['position']=='center')
<center>
    <img class="{{ $item->data->meta['class'] }}" style="{{ $item->data->meta['style'] }}"
        src="{{ $item->data->link }}">
</center>
@else
<img class="{{ $item->data->meta['class'] }}" style="{{ $item->data->meta['style'] }}" src="{{ $item->data->link }}">
@endif
<div class="clearfix"></div>
@break


@endswitch


@endforeach
