@extends(
    config('laravolt.comma.view.layout'),
    [
        '__page' => [
            'title' => __('comma::post.header.create'),
            'actions' => [
                [
                    'label' => __('Kembali'),
                    'class' => '',
                    'icon' => 'icon angle left',
                    'url' => route('comma::posts.index')
                ],
            ]
        ],
    ]
)

@section('content')
    {!! form()->open()->route('comma::posts.store') !!}
    {!! form()->text('title')->label(trans('comma::post.attributes.title'))->required() !!}
    {!! form()->textarea('content')->label(trans('comma::post.attributes.content'))->required() !!}
    {!! form()->selectMultiple('tags[]', $tags)->placeholder('')->label(trans('comma::post.attributes.tags')) !!}
    {!! form()->action(
        form()->submit(trans('comma::post.action.save'))->addClass('primary'),
        form()->link(trans('comma::post.action.cancel'), route('comma::posts.index'))
    ) !!}
    {!! form()->close() !!}
@endsection

@push('body')
<script src="{{ asset('lib/redactor/redactor.js') }}"></script>
<script>
    $(function () {
        $('#postContent').redactor({
            minHeight: 500,
            toolbarFixedTopOffset: 60,
            imageUpload: '{{ route('comma::media.store') }}',
            imageResizable: true,
            imagePosition: true,
            imageUploadFields: {
                '_token': '{{ csrf_token() }}',
            }
        });
    });
</script>

@endpush
