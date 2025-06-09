@php
    $sectionContent = $pageSection ? json_decode($pageSection->content) : null;
@endphp
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="form-fields" id="editor-section-wrapper">
                    <label class="title">Content<span class="text-danger">*</span> :</label>
                    <textarea class="editor" name="content[content]" data-placeholder="content">
                            {{ $sectionContent->content ?? '' }}
                        </textarea>
                </div>
            </div>
        </div>
    </div>
</div>
