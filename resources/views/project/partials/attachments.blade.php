@if (!$attachments->isEmpty())
    <div class="mt25">

        <div class="separator mt25 mb25"></div>

        <h2 class="mb10 fs14"><b>{{ trans('app.label_attached_files') }}</b></h2>

        <div class="clearfix fs12 discussion-attachments">
            @foreach ($attachments as $attachment)
                <div id="attachment-{{ $attachment->id }}" class="attachment-box">

                    <div class="attachment-box-preview">
                        <div class="delete-attachment-link">
                            <a href="javascript:void(0)" class="ajax-delete"
                               data-href="{{ route('ajax_delete_attachment', $attachment->id) }}"
                               data-confirm="{{ trans('app.confirm_delete_attachment') }}"
                               data-container="attachment-{{ $attachment->id }}">
                                <i class="glyphicon glyphicon-remove fs10 red"></i>
                            </a>
                        </div>

                        @if ($attachment->preview_url)
                            <a href="{{ $attachment->url }}" rel ="lightbox" data-title="{{ $attachment->name }}"
                               data-lightbox="preview">
                                <img src="{{ $attachment->preview_url }}" width="140" height="100">
                            </a>
                        @else
                            <a href="{{ route('download', [$attachment->id, $attachment->name]) }}" class="no-preview">
                                {{ $attachment->ext }}
                            </a>
                        @endif
                    </div>

                    <div class="attachment-box-controls">
                        {!! Html::linkRoute('download', $attachment->name, [$attachment->id, $attachment->name]) !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
