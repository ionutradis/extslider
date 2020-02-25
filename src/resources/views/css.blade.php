
@section('css')
    {!! $slider->first()->css_content ?? '' !!}
@endsection
@push('css')
    {!! $slider->first()->css_content ?? '' !!}
@endpush