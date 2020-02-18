@php
    use caconnect\extslider\Models\Extslider;
    $slider = new Extslider();
    if(isset($id)) {
        $slider->where('id', 212);
    }
    if(isset($group)) {
        $slider->where('group', $group);
    }
    //dd($slider->count);
@endphp
@if($slider)
    da
@endif