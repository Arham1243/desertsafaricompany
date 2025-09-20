@if (!$content)
    <div class="faqs faqs-category my-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="section-content">
                        <h2 class="subHeading block-heading">
                            FAQS
                        </h2>
                    </div>
                </div>
            </div>

            <div class="faqs-single accordian">
                <div class="faqs-single__header accordian-header">
                    <div class="faq-icon"><i class="bx bx-plus"></i></div>
                    <div class="tour-content__title">What Types Of Tours Are Available In This Category?</div>
                </div>
                <div class="faqs-single__content accordian-content">
                    <div class="hidden-wrapper tour-content__pra">
                        This category includes city tours, adventure trips, and cultural experiences.
                    </div>
                </div>
            </div>
            <div class="faqs-single accordian">
                <div class="faqs-single__header accordian-header">
                    <div class="faq-icon"><i class="bx bx-plus"></i></div>
                    <div class="tour-content__title">How Do I Choose The Right Tour For Me?</div>
                </div>
                <div class="faqs-single__content accordian-content">
                    <div class="hidden-wrapper tour-content__pra">
                        How Do I Choose The Right Tour For Me?
                    </div>
                </div>
            </div>
            <div class="faqs-single accordian">
                <div class="faqs-single__header accordian-header">
                    <div class="faq-icon"><i class="bx bx-plus"></i></div>
                    <div class="tour-content__title">Are Discounts Offered For Group Bookings?
                    </div>
                </div>
                <div class="faqs-single__content accordian-content">
                    <div class="hidden-wrapper tour-content__pra">
                        Are Discounts Offered For Group Bookings?
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    @php
        $faqItems = [];
        if (!empty($content->faq->question) && !empty($content->faq->answer)) {
            foreach ($content->faq->question as $i => $q) {
                $faqItems[] = [
                    'question' => $q,
                    'answer' => $content->faq->answer[$i] ?? '',
                ];
            }
        }
        if (empty($faqItems)) {
            $faqItems = [['question' => '', 'answer' => '']];
        }
    @endphp
    <div class="faqs faqs-category my-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="section-content">
                        <h2 class="subHeading block-heading"
                            @if ($content->heading_text_color) style="color: {{ $content->heading_text_color }};" @endif>
                            {{ $content->heading ?? '' }}
                        </h2>
                    </div>
                </div>
            </div>

            @foreach ($faqItems as $item)
                <div class="faqs-single accordian">
                    <div class="faqs-single__header accordian-header">
                        <div class="faq-icon"><i class="bx bx-plus"></i></div>
                        <div class="tour-content__title">{{ $item['question'] }}</div>
                    </div>
                    <div class="faqs-single__content accordian-content">
                        <div class="hidden-wrapper tour-content__pra">
                            {!! $item['answer'] !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
