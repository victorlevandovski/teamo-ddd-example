@if (!$attachments->isEmpty())
    <div class="mt25">

        <div class="separator mt25 mb25"></div>

        <h2 class="mb10 fs14"><b>{{ trans('app.label_attached_files') }}</b></h2>

        <div class="clearfix fs12 discussion-attachments">
            @foreach ($attachments as $attachment)
                <div id="attachment-{{ $attachment->attachmentId()->id() }}" class="attachment-box">

                    <div class="attachment-box-preview">
                        <div class="delete-attachment-link">
                            <a href="javascript:void(0)" class="ajax-delete"
                               data-href="{{ route("project.{$controller}.ajax_delete_attachment", [$selectedProjectId, $entityId, $attachment->attachmentId()->id()]) }}"
                               data-confirm="{{ trans('app.confirm_delete_attachment') }}"
                               data-container="attachment-{{ $attachment->attachmentId()->id() }}">
                                <i class="glyphicon glyphicon-remove fs10 red"></i>
                            </a>
                        </div>

                        @if ($attachment->type()->isImage())
                            <a href="{{ route('project.attachment', [$attachment->attachmentId()->id(), $attachment->name()]) }}" rel ="lightbox" data-title="{{ $attachment->name() }}"
                               data-lightbox="preview">
                                <img src="/thumbs/{{ thumb_url($attachment->attachmentId()->id()) }}" width="140" height="100">
                            </a>
                        @else
                            <a href="{{ route('project.attachment.download', [$attachment->attachmentId()->id(), $attachment->name()]) }}" class="no-preview">
                                {{ pathinfo($attachment->name(), PATHINFO_EXTENSION) }}
                            </a>
                        @endif
                    </div>

                    <div class="attachment-box-controls">
                        {!! Html::linkRoute('project.attachment.download', $attachment->name(), [$attachment->attachmentId()->id(), $attachment->name()]) !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
