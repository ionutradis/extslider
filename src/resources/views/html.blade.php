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

@if(count($slider->get()) !== 0)
    @switch([$slider->first()->mode, $slider->first()->status])
        @case(['production', 1])
            @include('extslider::render')
        @break
        @case(['testing', 1])
            @php
                $explodeIps = explode(',', $slider->first()->ips);
            @endphp
            @if(in_array(\request()->ip(), $explodeIps) !== false)
                @include('extslider::render')
            @endif
        @break
    @endswitch
@endif