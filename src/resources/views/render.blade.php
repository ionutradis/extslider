@php
    use ionutradis\extslider\Models\Extslider;
    $slider = new Extslider();
    if(isset($id)) {
        $slider = $slider->where('id', $id);
    }
    if(isset($slug)) {
        $slider = $slider->where('slug', $slug);
    }
@endphp

@include('extslider::css')
@include('extslider::scripts')
{!! $slider->first()->html_content !!}
