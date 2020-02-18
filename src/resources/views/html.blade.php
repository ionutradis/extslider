@php
    use caconnect\extslider\Models\Extslider;
    $slider = new Extslider();
    if(isset($id)) {
        $slider = $slider->where('id', $id);
    }
    if(isset($slug)) {
        $slider = $slider->where('slug', $slug);
    }
    $tag = 'section';
@endphp
@if(count($slider->get()) !== 0)
{{--    @if(config('settings') == 'section')--}}
{{--@endif--}}
    @if($tag == 'yield')
        @section('scripts')
            {{ $slider->first()->scripts_content ?? '' }}
        @endsection
        @section('css')
            {{ $slider->first()->css_content ?? '' }}
        @endsection
        @section('html')
            {{ $slider->first()->html_content ?? '' }}
        @endsection
    @elseif($tag == 'stack')
        @push('scripts')
            {{ $slider->first()->scripts_content ?? '' }}
        @endpush
        @push('css')
            {{ $slider->first()->css_content ?? '' }}
        @endpush
        @push('html')
            {{ $slider->first()->html_content ?? '' }}
        @endpush
    @endif
@endif