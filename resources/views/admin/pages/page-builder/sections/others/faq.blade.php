@php
    $sectionContent = $pageSection ? json_decode($pageSection->content) : null;
    $faqItems = [];
    if (!empty($sectionContent->faq->question) && !empty($sectionContent->faq->answer)) {
        foreach ($sectionContent->faq->question as $i => $q) {
            $faqItems[] = [
                'question' => $q,
                'answer' => $sectionContent->faq->answer[$i] ?? '',
            ];
        }
    }
    if (empty($faqItems)) {
        $faqItems = [['question' => '', 'answer' => '']];
    }
@endphp
<div class="row">
    <div class="col-lg-12 pt-3 pb-4">
        <div class="form-fields">
            <div class="d-flex align-items-center gap-3 mb-3">
                <label class="title title--sm mb-0">Title:</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-fields">
                    <label class="title">Heading Text <span class="text-danger">*</span> :</label>
                    <input type="text" name="content[heading]" class="field" placeholder="" data-error="Title"
                        value="{{ $sectionContent->heading ?? '' }}" maxlength="39">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-fields">
                    <div class="title d-flex align-items-center gap-2">
                        <div>
                            Text Color <span class="text-danger">*</span>:
                        </div>
                        <a class="p-0 nav-link" href="//html-color-codes.info" target="_blank">Get Color
                            Codes</a>
                    </div>
                    <div class="field color-picker" data-color-picker-container>
                        <label for="color-picker" data-color-picker></label>
                        <input id="color-picker" type="text" name="content[heading_text_color]"
                            data-color-picker-input value="{{ $sectionContent->heading_text_color ?? '#1c4d99' }}"
                            inputmode="text" />
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <hr>
    </div>
    <div class="col-md-12 mt-5">
        <div class="form-fields">
            <div class="repeater-table" x-data="{
                items: {{ json_encode($faqItems) }},
                addItem() { this.items.push({ question: '', answer: '' }) },
                remove(index) { this.items.splice(index, 1) },
                copy(text) { navigator.clipboard.writeText(text) }
            }">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">
                                Faq content
                                <span class="small text-muted ms-2 d-inline-flex align-items-center gap-2">
                                    <span>To add a link:</span>
                                    <code class="text-nowrap text-lowercase">&lt;a href=&quot;//google.com&quot;
                                        target=&quot;_blank&quot;&gt;Text&lt;/a&gt;</code>
                                    <button class="themeBtn copy-btn py-1 px-2" type="button"
                                        @click="copy('<a href=&quot;//google.com&quot; target=&quot;_blank&quot;>Text</a>')">
                                        Copy
                                    </button>
                                </span>
                            </th>
                            <th class="text-end" scope="col">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td>
                                    <div class="mb-3">
                                        <label class="title">Question <span class="ms-1"
                                                x-text="index + 1"></span>:</label>
                                        <textarea x-model="item.question" name="content[faq][question][]" class="field mb-1" rows="6"></textarea>
                                    </div>
                                    <div style="color: #000">
                                        <label class="title">Answer <span class="ms-1"
                                                x-text="index + 1"></span>:</label>
                                        <textarea x-model="item.answer" name="content[faq][answer][]" class="field editor" rows="6"
                                            x-init="initializeEditorsSingle($el, { initialValue: item.answer, onChange: val => item.answer = val })"></textarea>

                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="delete-btn ms-auto delete-btn--static"
                                        @click="remove(index)" :disabled="index === 0">
                                        <i class='bx bxs-trash-alt'></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <button type="button" class="themeBtn ms-auto" @click="addItem">
                    Add <i class="bx bx-plus"></i>
                </button>
            </div>
        </div>
    </div>
</div>
