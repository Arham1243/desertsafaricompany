@if ($settings->get('is_registration_enabled') && (int) $settings->get('is_registration_enabled') === 1)
    @if (!Auth::check())
        @php
            $is_enabled_login_button =
                $settings->get('is_enabled_login_button') && (int) $settings->get('is_enabled_login_button') === 1;
            $login_button_text_color = $settings->get('login_button_text_color')
                ? $settings->get('login_button_text_color')
                : null;
            $login_button_text = $settings->get('login_button_text') ? $settings->get('login_button_text') : null;
            $login_button_bg_color = $settings->get('login_button_bg_color')
                ? $settings->get('login_button_bg_color')
                : null;
        @endphp
        @if ($is_enabled_login_button)
            <a href="javascript:void(0)" @click="openLoginPopup" class="item__become-supplier loginBtn login-anchor"
                @if ($login_button_text_color || $login_button_bg_color) style="{{ $login_button_text_color ? "color: {$login_button_text_color};" : '' }} {{ $login_button_bg_color ? "background-color: {$login_button_bg_color};" : '' }}" @endif>
                <span><b>{{ $login_button_text }}</b></span>
            </a>
        @endif
        <div class="login-wrapper" ref="loginPopup">
            <div class="loginSignup-popup">
                <a href="javascript:void(0)" @click="closeLoginPopup" class="loginSignup-popup__close popup-close-icon"
                    title="close"><i class='bx bx-x'></i></a>
                <div v-if="challenge === 'check_email'">
                    <h3>Log in or sign up</h3>
                    <p>Check out faster and access your tickets anytime on any device with your account.</p>
                    <div class="loginSignup-popup__buttons">
                        @if ($settings->get('is_google_login_enabled') && (int) $settings->get('is_google_login_enabled') === 1)
                            <a href="{{ route('auth.socialite', ['social' => 'google']) }}"
                                class="loginSignup-popup__icons">
                                <div class="loginSignup-popup__img">
                                    <img src="{{ asset('frontend/assets/images/google-removebg-preview.webp') }}"
                                        alt='image' class='imgFluid' loading='lazy' width="27" height="27">
                                </div>
                            </a>
                        @endif
                        @if ($settings->get('is_facebook_login_enabled') && (int) $settings->get('is_facebook_login_enabled') === 1)
                            <a href="{{ route('auth.socialite', ['social' => 'facebook']) }}"
                                class="loginSignup-popup__icons">
                                <div class="loginSignup-popup__img">
                                    <img src="{{ asset('frontend/assets/images/scale_1200-removebg-preview.webp') }}"
                                        alt='image' class='imgFluid' loading='lazy' width="27" height="27">
                                </div>
                            </a>
                        @endif
                    </div>
                    <form @submit.prevent="checkEmail">

                        <div class="loginSignup-popup__email">
                            <input v-model="email" type="email" placeholder="Email address" class="check-fields"
                                required name="email">
                        </div>
                        <button type="submit" class="loginSignup-popup__btn" :class="{ 'disabled': !isEmailValid }"
                            :disabled="loading">
                            <div class="spinner-border spinner-border-sm" v-if="loading"></div>
                            <span v-else>Continue with email</span>
                        </button>
                        @if ($settings->get('is_google_recaptcha_enabled') && (int) $settings->get('is_google_recaptcha_enabled') === 1)
                            <div class="g-recaptcha" data-sitekey="{{ env('RE_CAPTCHA_SITE_KEY') }}"> </div>
                        @endif
                    </form>
                </div>

                <form @submit.prevent="performAuth" v-if="challenge === 'sign_up'">

                    <h3>Create an account</h3>
                    <div class="prev-data">
                        <input class="prev-data__email" :value="email" readonly>
                        <button @click="challenge = 'check_email'" type="button"
                            class="changeEmailButton">Change</button>
                    </div>
                    <div class="loginSignup-popup__email">
                        <input type="text" placeholder="Full name" class="check-fields" name="full_name" required
                            v-model="formData.full_name">
                    </div>
                    <div class="loginSignup-popup__email">
                        <div class="password-field">
                            <input :type="showPassword ? 'text' : 'password'" placeholder="Password"
                                class="check-fields" name="password" required v-model="formData.password" />
                            <button @click="togglePasswordVisibility" type="button" class="password-field__show">
                                <i :class="showPassword ? 'bx bxs-show' : 'bx bxs-hide'"></i>
                            </button>
                        </div>
                    </div>
                    <button class="loginSignup-popup__btn" :class="{ 'disabled': !isSignUpValid }"
                        :disabled="loading">
                        <div class="spinner-border spinner-border-sm" v-if="loading"></div>
                        <span v-else>Create an account</span>
                    </button>
                    <div class="info">By creating an account, you agree to our Privacy
                        Policy.
                        See our Terms and Conditions.</div>
                </form>

                <form @submit.prevent="performAuth" v-if="challenge === 'login'">
                    <h3>Welcome back!</h3>
                    <div class="prev-data">
                        <input class="prev-data__email" :value="email" readonly>
                        <button @click="challenge = 'check_email'" type="button"
                            class="changeEmailButton">Change</button>
                    </div>
                    <div class="loginSignup-popup__email">
                        <div class="password-field">
                            <input :type="showPassword ? 'text' : 'password'" placeholder="Password"
                                class="check-fields" name="password" required v-model="formData.password" />
                            <button @click="togglePasswordVisibility" type="button" class="password-field__show">
                                <i :class="showPassword ? 'bx bxs-show' : 'bx bxs-hide'"></i>
                            </button>

                        </div>
                    </div>
                    <div class="info d-flex align-items-center justify-content-between">
                        <div class="remember-me">
                            <input type="checkbox" name="remember" id="remember" value="1"
                                v-model="formData.remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="javascript:void(0)" @click="challenge = 'forgot_password'">Forgot password?</a>
                    </div>

                    <button class="loginSignup-popup__btn" :class="{ 'disabled': !isLoginValid }"
                        :disabled="loading">
                        <div class="spinner-border spinner-border-sm" v-if="loading"></div>
                        <span v-else>Log in</span>
                    </button>

                </form>

                <form @submit.prevent="resetPasswordRequest" method="POST" v-if="challenge === 'forgot_password'">

                    @csrf
                    <h3>Reset Password
                    </h3>
                    <p>Enter the email address associated with your account and we'll send you a link to reset your
                        password.

                    </p>
                    <div class="loginSignup-popup__email">
                        <input type="email" placeholder="Email address" class="check-fields" name="email"
                            required v-model="email" autocomplete="off">
                    </div>
                    <button class="loginSignup-popup__btn" :class="{ 'disabled': !isEmailValid }"
                        :disabled="loading">
                        <div class="spinner-border spinner-border-sm" v-if="loading"></div>
                        <span v-else> Send Reset Link </span>
                    </button>
                </form>
            </div>
        </div>
    @endif
@endif
