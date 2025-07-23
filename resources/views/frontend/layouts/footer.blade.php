@php
    $footerLogo = App\Models\Setting::where('key', 'footer_logo')->first()->value ?? null;
    $footerLogoAltText = App\Models\Setting::where('key', 'footer_logo_alt_text')->first()->value ?? null;
    $footerCopyrightText = App\Models\Setting::where('key', 'footer_copyright_text')->first()->value ?? null;
@endphp
<footer class=footer>
    <div class=container>
        <div class=row>
            <div class=col-md>
                <div class=footer-content>

                    <div class=footer-details>COMPANY</div>

                    <ul class=footer-link>
                        <li><a href=#>About Us</a></li>
                        <li><a href=#>News</a></li>
                        <li><a href=#>Career</a></li>
                    </ul>
                </div>
            </div>
            <div class=col-md>
                <div class=footer-content>
                    <div class=footer-details>SERVICES</div>
                    <ul class=footer-link>
                        <li><a href=#>Tours</a></li>
                        <li><a href=#>Restaurants</a></li>
                        <li><a href=#>Tattoos</a></li>
                        <li><a href=#>Bar</a></li>
                    </ul>
                </div>
            </div>
            <div class=col-md>
                <div class=footer-content>
                    <div class=footer-details>ACCOUNT</div>
                    <ul class=footer-link>
                        <li><a href=#>Log In</a></li>
                        <li><a href=#>Sign Up</a></li>
                        <li><a href=#>Forgot Password?</a></li>
                        <li><a href=#>Become a Supplier</a></li>
                    </ul>
                </div>
            </div>
            <div class=col-md>
                <div class=footer-content>
                    <div class=footer-details>SUPPORT</div>
                    <ul class=footer-link>
                        <li><a href=#>Help Center</a></li>
                        <li><a href={{ route('terms_conditions') }}>Term Of Use</a></li>
                        <li><a href={{ route('privacy_policy') }}>Privacy Policy</a></li>
                        <li><a href=#>Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class=col-md>
                <div class=payment-section id=payment-section>
                    <label class=footer-details>Ways You Can Pay</label>
                    <div class=payment-images>
                        <div><img alt=Paypal src={{ asset('frontend/assets/images/payments.png') }} class=imgFluid>
                        </div>
                    </div>
                </div>
            </div>
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
