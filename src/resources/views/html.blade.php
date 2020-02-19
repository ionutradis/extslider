@php
    use caconnect\extslider\Models\Extslider;
    $slider = new Extslider();
    if(isset($id)) {
        $slider = $slider->where('id', $id);
    }
    if(isset($slug)) {
        $slider = $slider->where('slug', $slug);
    }
@endphp

@if(count($slider->get()) !== 0 && $slider->first()->status == 1)
    @section('scripts')
        {!! $slider->first()->scripts_content ?? '' !!}
    @endsection
    @section('css')
        {!! $slider->first()->css_content ?? '' !!}
    @endsection
    @push('scripts')
        {!! $slider->first()->scripts_content ?? '' !!}
    @endpush
    @push('css')
        {!! $slider->first()->css_content ?? '' !!}
    @endpush

    {!! $slider->first()->html_content !!}
@endif
