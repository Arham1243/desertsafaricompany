<div class=form-book_content>
    @if ($tour->pricing_box_heading_enabled && (int) $tour->pricing_box_heading_enabled === 1)
        <h3 class="tour-pricing-heading"
            @if ($settings->get('pricing_box_heading_color')) style="color: {{ $settings->get('pricing_box_heading_color') }}" @endif>
            {{ $tour->pricing_box_heading ?? '' }}
        </h3>
    @endif
    @php
        $pricingTagline = isset($tour['pricing_tagline']) ? json_decode($tour['pricing_tagline'], true) : [];
    @endphp
    @if ((int) ($pricingTagline['enabled'] ?? 0) === 1)
        <div class="tour-views tour-views--tagline">
            <div class="tour-views__icon"><i
                    @if ($settings->get('pricing_tagline_icon_color')) style="color: {{ $settings->get('pricing_tagline_icon_color') }}" @endif
                    class="{{ $pricingTagline['icon_class'] ?? '' }}"></i></div>
            <div class="tour-views__count"
                @if ($settings->get('pricing_tagline_text_color') || (int) $settings->get('pricing_tagline_bold') === 1) style="
                        @if ($settings->get('pricing_tagline_text_color'))
                            color: {{ $settings->get('pricing_tagline_text_color') }}; @endif
                @if ((int) $settings->get('pricing_tagline_bold') === 1) font-weight: bold; @endif "
                                             @endif>
                {{ $pricingTagline['text'] ?? '' }}
            </div>
        </div>
    @endif
    <div class="tour-content__pra form-book__pra px-0">
        Start Date
    </div>
    <div class="tour-content__title form-book__title position-relative">
        <input type="date" class="form-book__date" name="start_date" required id="start_date" ref="startDateInput"
            @change="handleDateChange" placeholder="mm/dd/yyyy" />
        <div class="cal-icon"><i class='bx bxs-calendar'></i></div>
        <input type="hidden" name="price_type" value="{{ $tour->price_type ?? 'simple' }}">
    </div>
</div>

@if (is_null($tour->price_type) && (int) $tour->is_person_type_enabled === 0)
    @include('frontend.tour.pricing.types.simple')
@else
    @switch($tour->price_type)
        @case('normal')
            @include('frontend.tour.pricing.types.normal')
        @break

        @case('water')
            @include('frontend.tour.pricing.types.water')
        @break

        @case('promo')
            @include('frontend.tour.pricing.types.promo')
        @break

        @case('private')
            @include('frontend.tour.pricing.types.private')
        @break
    @endswitch
@endif

@include('frontend.tour.pricing.components.extra_price')
@include('frontend.tour.pricing.components.service_fee')
@include('frontend.tour.pricing.components.total_price')

<div class="tour-views">
    <div class="tour-views__icon"><i class="bx bx-show"></i></div>
    <div class="tour-views__count">
        {{ $todayViews }} {{ Str::plural('view', $todayViews) }} today, so act now!
    </div>
</div>

@if (isset($settings['tamara_enabled']) && (int) $settings['tamara_enabled'] === 1)
    <div class="payment-widget mt-4 mb-3">
        <tamara-widget id="tamara-widget-custom" type="tamara-summary" amount="{{ $tour->tour_lowest_price }}"
            config='{"badgePosition":"right","showExtraContent":""}' inline-type="2">
        </tamara-widget>
    </div>
@endif

@if (isset($settings['tabby_enabled']) && (int) $settings['tabby_enabled'] === 1)
    <div class="payment-widget" id="tabby-promo-widget" class="my-3"></div>
@endif

<div class="form-guest__btn mt-4">
    @if (Auth::check())
        @if (isset($isTourInCart) && !$isTourInCart)
            @if (!$tour->availability_status['available'])
                <button class="primary-btn w-100" disabled data-tooltip="tooltip"
                    title="{{ $tour->availability_status['user_message'] }}">
                    Book Now
                </button>
            @else
                <button class="primary-btn w-100" :disabled="!isSubmitEnabled"
                    @if (!$isDataValid) disabled @endif
                    @if (!$tour->availability_status['available']) data-tooltip="tooltip" title="{{ $tour->availability_status['user_message'] }}" @endif>
                    Book Now
                </button>
                <div v-if="!startDateInput?.value" class="text-danger text-center mt-2">
                    Please select a start date
                </div>
            @endif
        @else
            <a href="{{ route('cart.index') }}" class="primary-btn w-100">Already in Cart</a>
        @endif
    @else
        <button type="button" class="primary-btn w-100" open-vue-login-popup
            @if (!$tour->availability_status['available']) disabled
                data-tooltip="tooltip" title="{{ $tour->availability_status['user_message'] }}" @endif>
            Login to Continue
        </button>
    @endif
</div>

@if ((int) $settings->get('is_enabled_detail_popup_trigger_box') === 1)
    @php
        $detail_popup_trigger_box_icon = $settings->get('detail_popup_trigger_box_icon');
        $detail_popup_trigger_box_icon_color = $settings->get('detail_popup_trigger_box_icon_color');
        $detail_popup_trigger_box_background_color = $settings->get('detail_popup_trigger_box_background_color');
        $detail_popup_trigger_box_text_color = $settings->get('detail_popup_trigger_box_text_color');

        $detail_popup_box_style = [];

        if ($detail_popup_trigger_box_icon_color) {
            $detail_popup_box_style[] = "--detail-popup-trigger-box-icon-color: {$detail_popup_trigger_box_icon_color}";
        }
        if ($detail_popup_trigger_box_background_color) {
            $detail_popup_box_style[] = "--detail-popup-trigger-box-background-color: {$detail_popup_trigger_box_background_color}";
        }
        if ($detail_popup_trigger_box_text_color) {
            $detail_popup_box_style[] = "--detail-popup-trigger-box-text-color: {$detail_popup_trigger_box_text_color}";
        }

        $detail_popup_box_style_attribute = empty($detail_popup_box_style)
            ? ''
            : 'style="' . implode('; ', $detail_popup_box_style) . '"';

        $selectedDetailPopupsIds = $settings->get('detail_popup_ids')
            ? json_decode($settings->get('detail_popup_ids'))
            : [];
        $selectedDetailPopups = $detailPopups->whereIn('id', $selectedDetailPopupsIds);
    @endphp
    @if ($selectedDetailPopups->isNotEmpty())
        <div class="detail-popups" {!! $detail_popup_box_style_attribute !!}>
            @foreach ($selectedDetailPopups as $selectedDetailPopup)
                <div class="detail-popups-item">
                    <div class="detail-popups-item__icon">
                        <i class='{{ $detail_popup_trigger_box_icon ?? '' }}'></i>
                    </div>
                    <div class="detail-popups-item__info">
                        <span class="trigger-text" detail-popup-trigger
                            detail-popup-id="popup-{{ $selectedDetailPopup->id }}">
                            {{ $selectedDetailPopup->popup_trigger_text ?? '' }}</span>
                        <span class="user-label">{{ $selectedDetailPopup->user_showing_text ?? '' }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        @foreach ($selectedDetailPopups as $selectedDetailPopupsModal)
            <div class="global-popup-wrapper detail-popup" id="popup-{{ $selectedDetailPopupsModal->id }}">
                <div class="global-popup">
                    <div class="global-popup__header">
                        <div class="title">{{ $selectedDetailPopupsModal->main_heading ?? '' }}</div>
                        <div class="close-icon popup-close-icon" detail-popup-close>
                            <i class="bx bx-x"></i>
                        </div>
                    </div>

                    @php
                        $content = $selectedDetailPopupsModal->content
                            ? json_decode($selectedDetailPopupsModal->content)
                            : null;
                    @endphp

                    <div class="global-popup__content editor-content">
                        @if (!empty($content->sub_heading))
                            <h4>{{ $content->sub_heading }}</h4>
                        @endif

                        @if (!empty($content->condition_1) || !empty($content->condition_2))
                            <div class="refund-policy">
                                @if (!empty($content->condition_1) || !empty($content->outcome_1))
                                    <div class="refund-policy-item refund-policy-item--green">
                                        <div class="refund-policy-item__time">{{ $content->condition_1 ?? '' }}
                                        </div>
                                        <div class="refund-policy-item__result">{{ $content->outcome_1 ?? '' }}
                                        </div>
                                    </div>
                                @endif

                                @if (!empty($content->condition_2) || !empty($content->outcome_2))
                                    <div class="refund-policy-item refund-policy-item--red">
                                        <div class="refund-policy-item__time">{{ $content->condition_2 ?? '' }}
                                        </div>
                                        <div class="refund-policy-item__result">{{ $content->outcome_2 ?? '' }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {!! $content->editor_content ?? '' !!}
                    </div>
                </div>
            </div>
        @endforeach
        @push('js')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const triggers = document.querySelectorAll('[detail-popup-trigger]');
                    const popups = document.querySelectorAll('.global-popup-wrapper');

                    triggers.forEach(trigger => {
                        trigger.addEventListener('click', () => {
                            const id = trigger.getAttribute('detail-popup-id');
                            popups.forEach(popup => {
                                popup.classList.remove('open');
                                if (popup.id === id) popup.classList.add('open');
                            });
                        });
                    });
                    popups.forEach(popup => {
                        popup.addEventListener('click', function(e) {
                            if (e.target === popup) {
                                popup.classList.remove('open');
                            }
                        });
                    });

                    document.querySelectorAll('[detail-popup-close]').forEach(close => {
                        close.addEventListener('click', () => {
                            close.closest('.global-popup-wrapper').classList.remove('open');
                        });
                    });
                });
            </script>
        @endpush
    @endif
@endif
