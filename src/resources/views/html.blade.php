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

@if(count($slider->get()) !== 0 && $slider->first()->status == 1 && $slider->first()->mode == 'production')
    @include('extslider::render')
@endif