@php
    $footerLogo = $settings->get('footer_logo') ?? null;
    $footerLogoAltText = $settings->get('footer_logo_alt_text') ?? null;
    $footerCopyrightText = $settings->get('footer_copyright_text') ?? null;
    $footerConfig = $settings->get('footer_config') ? json_decode($settings->get('footer_config'), true) ?? [] : [];

@endphp
<footer class=footer>
    <div class=container>
        <div class="row">
            @foreach ($footerConfig['blocks'] ?? [] as $block)
                @if ($block['type'] === 'links')
                    <div class="col-md">
                        <div class="footer-content">
                            <div class="footer-details">{{ $block['heading'] }}</div>
                            <ul class="footer-link">
                                @foreach ($block['links'] ?? [] as $link)
                                    <li><a href="{{ sanitizedLink($link['url']) }}">{{ $link['label'] ?? '' }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @elseif($block['type'] === 'image' && !empty($block['image']))
                    <div class="col-md">
                        <div class="payment-section">
                            <label class="footer-details">{{ $block['heading'] }}</label>
                            <div class="payment-images">
                                <div>
                                    <img src="{{ $block['image'] }}" alt="{{ $block['alt_text'] ?? '' }}"
                                        class="imgFluid">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class=last-footer>
        <div class=container>
            <div class=last-footer__content>
                <div class=last-footer__title>
                    <span>{{ $footerCopyrightText }}</span>
                </div>
            </div>
        </div>
    </div>
</footer>
