<script>
    @if (!Auth::check())
        const LoginPopup = createApp({
            setup() {
                const loginPopup = ref(null);
                const showPassword = ref(null);
                const email = ref('');
                const recaptchaToken = ref(null)
                const formData = ref({
                    full_name: '',
                    password: '',
                    remember: false,
                });
                const loading = ref(false);
                const challenge = ref('check_email');

                const togglePasswordVisibility = () => {
                    showPassword.value = !showPassword.value;
                }

                const openLoginPopup = () => {
                    loginPopup.value.classList.add('open');
                };

                const closeLoginPopup = () => {
                    loginPopup.value.classList.remove('open');

                    setTimeout(() => {
                        resetForm()
                    }, 400);
                };

                const resetForm = () => {
                    email.value = ''
                    formData.value.full_name = ''
                    formData.value.password = ''
                    formData.value.remember = false
                    challenge.value = 'check_email'
                    if (window.isGoogleRecaptchaEnabled) {
                        grecaptcha.reset();
                        recaptchaToken.value = null;
                    }
                }

                const isEmailValid = computed(() => {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return emailRegex.test(email.value);
                });

                const isSignUpValid = computed(() => {
                    return formData.value.full_name.trim() !== '' && formData.value.password.trim() !==
                        '' &&
                        formData.value.password.length > 7;
                });

                const isLoginValid = computed(() => {
                    return isEmailValid && formData.value.password.trim() !== '' &&
                        formData.value.password.length > 7;
                });

                const checkEmail = async () => {
                    if (window.isGoogleRecaptchaEnabled) {
                        const token = grecaptcha.getResponse();
                        if (!token) {
                            showToast('error', 'Please verify you are human!');
                            return;
                        }
                        recaptchaToken.value = token;
                    }

                    try {
                        loading.value = true;
                        let route =
                            `{{ route('auth.check-email') }}?email=${email.value}&recaptcha=${recaptchaToken.value}`;
                        const response = await axios.post(route);
                        if (response.data.challenge == 'sign_up') {
                            challenge.value = 'sign_up';
                        } else if (response.data.challenge == 'login') {
                            challenge.value = 'login';
                        } else if (response.data.challenge == 'forgot_password') {
                            challenge.value = 'forgot_password';
                        }
                    } catch (error) {
                        showToast('error', error.response?.data?.message || 'Something went wrong');
                    } finally {
                        loading.value = false;
                    }
                };

                const performAuth = async () => {
                    if (formData.value.password.length < 8) {
                        showToast('error', 'Password must be at least 8 characters long')
                        return
                    }
                    try {
                        loading.value = true;
                        let route = `{{ route('auth.perform-auth') }}`;

                        const payload = {
                            auth_type: challenge.value === 'login' ? 'login' : 'sign_up',
                            email: email.value,
                            ...formData.value,
                        };

                        const response = await axios.post(route, payload);

                        if (response.data.status === 'success') {
                            showToast('success', response.data.message);
                            if (response.data.redirect_url) {
                                window.location.href = response.data.redirect_url;
                            }
                        } else {
                            showToast('error', response.data.message);
                        }
                    } catch (error) {
                        if (error.response && error.response.data.message) {
                            showToast('error', error.response.data.message);
                        } else {
                            showToast('error', 'Something went wrong. Please try again.');
                        }
                    } finally {
                        loading.value = false;
                    }
                };

                const resetPasswordRequest = async () => {
                    try {
                        loading.value = true;
                        let route = `{{ route('password.email') }}`;

                        const payload = {
                            email: email.value,
                        };

                        const response = await axios.post(route, payload);

                        if (response.data.status === 'success') {
                            showToast('success', response.data.message);
                            if (response.data.redirect_url) {
                                window.location.href = response.data.redirect_url;
                            }
                        } else {
                            showToast('error', response.data.message);
                        }
                    } catch (error) {
                        if (error.response && error.response.data.message) {
                            showToast('error', error.response.data.message);
                        } else {
                            showToast('error', 'Something went wrong. Please try again.');
                        }
                    } finally {
                        loading.value = false;
                    }
                };


                return {
                    loginPopup,
                    email,
                    formData,
                    loading,
                    challenge,
                    showPassword,

                    isEmailValid,
                    isSignUpValid,
                    isLoginValid,

                    openLoginPopup,
                    closeLoginPopup,
                    checkEmail,
                    performAuth,
                    resetPasswordRequest,
                    showToast,
                    togglePasswordVisibility
                };
            }
        });
    @else
        const LoginPopup = createApp({
            setup() {}
        });
    @endif
    const LoginPopupApp = LoginPopup.mount('#login-popup');
</script>
