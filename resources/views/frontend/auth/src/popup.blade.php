@if (!Auth::check())
    <a href="javascript:void(0)" @click="openLoginPopup" class="item__become-supplier loginBtn">
        <span><b>Login</b> or <b> SignUp </b></span>
    </a>
    <div class="login-wrapper" ref="loginPopup">
        <div class="loginSignup-popup">
            <a href="javascript:void(0)" @click="closeLoginPopup" class="loginSignup-popup__close" title="close"><i
                    class='bx bx-x'></i></a>
            <div v-if="challenge === 'check_email'">
                <h3>Log in or sign up</h3>
                <p>Check out more easily and access your tickets on any device with your GetYourGuide account.</p>
                <div class="loginSignup-popup__buttons">
                    <a href="{{ route('auth.socialite', ['social' => 'google']) }}" class="loginSignup-popup__icons">
                        <div class="loginSignup-popup__img">
                            <img src="{{ asset('frontend/assets/images/google-removebg-preview.webp') }}" alt='image'
                                class='imgFluid' loading='lazy' width="27" height="27">
                        </div>
                    </a>
                    <a href="{{ route('auth.socialite', ['social' => 'facebook']) }}" class="loginSignup-popup__icons">
                        <div class="loginSignup-popup__img">
                            <img src="{{ asset('frontend/assets/images/scale_1200-removebg-preview.webp') }}"
                                alt='image' class='imgFluid' loading='lazy' width="27" height="27">
                        </div>
                    </a>
                </div>
                <form @submit.prevent="checkEmail">
                    <div class="loginSignup-popup__email">
                        <input v-model="email" type="email" placeholder="Email address" class="check-fields" required
                            name="email" autocomplete="off">
                    </div>
                    <button type="submit" class="loginSignup-popup__btn" :class="{ 'disabled': !isEmailValid }"
                        :disabled="loading">
                        <div class="spinner-border spinner-border-sm" v-if="loading"></div>
                        <span v-else>Continue with email</span>
                    </button>
                </form>
            </div>

            <form @submit.prevent="performAuth" v-if="challenge === 'sign_up'">
                <h3>Create an account</h3>
                <div class="prev-data">
                    <input class="prev-data__email" :value="email" readonly>
                    <button @click="challenge = 'check_email'" type="button" class="changeEmailButton">Change</button>
                </div>
                <div class="loginSignup-popup__email">
                    <input type="text" placeholder="Full name" class="check-fields" name="full_name" required
                        v-model="formData.full_name">
                </div>
                <div class="loginSignup-popup__email">
                    <div class="password-field">
                        <input :type="showPassword ? 'text' : 'password'" placeholder="Password" class="check-fields"
                            name="password" required v-model="formData.password" />
                        <button @click="togglePasswordVisibility" type="button" class="password-field__show">
                            <i :class="showPassword ? 'bx bxs-show' : 'bx bxs-hide'"></i>
                        </button>
                    </div>
                </div>
                <button class="loginSignup-popup__btn" :class="{ 'disabled': !isSignUpValid }" :disabled="loading">
                    <div class="spinner-border spinner-border-sm" v-if="loading"></div>
                    <span v-else>Create an account</span>
                </button>
                <div class="info">By creating an account, you agree to our <a
                        href="{{ route('privacy_policy') }}">Privacy
                        Policy</a>.
                    See our <a href="{{ route('terms_conditions') }}">Terms and Conditions</a>.</div>
            </form>

            <form @submit.prevent="performAuth" v-if="challenge === 'login'">
                <h3>Welcome back!</h3>
                <div class="prev-data">
                    <input class="prev-data__email" :value="email" readonly>
                    <button @click="challenge = 'check_email'" type="button" class="changeEmailButton">Change</button>
                </div>
                <div class="loginSignup-popup__email">
                    <div class="password-field">
                        <input :type="showPassword ? 'text' : 'password'" placeholder="Password" class="check-fields"
                            name="password" required v-model="formData.password" />
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

                <button class="loginSignup-popup__btn" :class="{ 'disabled': !isLoginValid }" :disabled="loading">
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
                    <input type="email" placeholder="Email address" class="check-fields" name="email" required
                        v-model="email" autocomplete="off">
                </div>
                <button class="loginSignup-popup__btn" :class="{ 'disabled': !isEmailValid }" :disabled="loading">
                    <div class="spinner-border spinner-border-sm" v-if="loading"></div>
                    <span v-else> Send Reset Link </span>
                </button>
            </form>
        </div>
    </div>
@endif
