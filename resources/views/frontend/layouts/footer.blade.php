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
                        <li><a href=#>Restourants</a></li>
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
                        <li><a href=#>Sing Up</a></li>
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
                        <div><img alt=Paypal src={{ asset('frontend/assets/images/paypal_border.webp') }}
                                class=imgFluid></div>
                        <div><img alt=Mastercard src={{ asset('frontend/assets/images/mastercard.webp') }}
                                class=imgFluid></div>
                        <div><img alt=Visa src={{ asset('frontend/assets/images/visa.webp') }} class=imgFluid></div>
                        <div><img alt=Maestro src={{ asset('frontend/assets/images/maestro.webp') }} class=imgFluid>
                        </div>
                        <div><img alt="American Express" src={{ asset('frontend/assets/images/amex.webp') }}></div>
                        <div><img alt=Jcb src={{ asset('frontend/assets/images/jcb.webp') }} class=imgFluid></div>
                        <div><img alt=Discover src={{ asset('frontend/assets/images/discover.webp') }} class=imgFluid>
                        </div>
                        <div><img alt=Sofort src={{ asset('frontend/assets/images/sofort.webp') }} class=imgFluid></div>
                        <div><img alt=Klarna src={{ asset('frontend/assets/images/klarna.webp') }} class=imgFluid>
                        </div>
                        <div><img alt="Google Pay" src={{ asset('frontend/assets/images/googlepay.webp') }}
                                class=imgFluid></div>
                        <div><img alt="Apple Pay" src={{ asset('frontend/assets/images/applepay.webp') }}
                                class=imgFluid></div>
                        <div><img alt=Bancontact src={{ asset('frontend/assets/images/bancontact.webp') }}
                                class=imgFluid></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class=footer-2>
        <div class=container>
            <div class=footer-2__content>
                <div class=footer-logo>
                    <a href=index.php> <img src="{{ asset($logo->img_path ?? 'assets/images/logo (1).webp') }}"
                            alt=image class=imgFluid width=112.03 height=33.69></a>
                </div>
                <!--<div class=header-btns>-->
                <!--    <div class=currencys>-->
                <!--        <div class="themeBtn themeBtn-white footer-2__btn">EN</div>-->
                <!--        <div class="themeBtn themeBtn-white footer-2__btn">EUR</div>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
    <div class=last-footer>
        <div class=container>
            <div class=last-footer__content>
                <div class=last-footer__title>
                    <span>© <?php echo date('Y'); ?> {{ env('APP_NAME') }} All Rights Reserved.</span>
                </div>
                <!--<div class=Agency-name>Öz Alanya Tour and Travel Agency</div>-->
            </div>
        </div>
    </div>
</footer>
