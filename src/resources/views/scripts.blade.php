@section('scripts')
    {!! $slider->first()->scripts_content ?? '' !!}
@endsection
@push('scripts')
    {!! $slider->first()->scripts_content ?? '' !!}
@endpush