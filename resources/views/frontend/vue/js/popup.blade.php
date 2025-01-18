<script>
    @if (!Auth::check())
        const LoginPopup = createApp({
            setup() {
                const loginPopup = ref(null);
                const showPassword = ref(null);
                const email = ref('');
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

                const showToast = (type, message) => {
                    if (type === 'error') {
                        $.toast({
                            heading: 'Error!',
                            position: 'bottom-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 5000,
                            text: message,
                            stack: 6
                        });
                    } else {
                        $.toast({
                            text: message,
                            heading: 'Success!',
                            position: 'bottom-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 2000,
                            stack: 6
                        });
                    }
                }

                const checkEmail = async () => {
                    try {
                        loading.value = true;
                        let route = `{{ route('auth.check-email') }}?email=${email.value}`
                        const response = await axios.post(route)
                        if (response.data.challenge == 'sign_up') {
                            challenge.value = 'sign_up';
                        } else if (response.data.challenge == 'login') {
                            challenge.value = 'login';
                        } else if (response.data.challenge == 'forgot_password') {
                            challenge.value = 'forgot_password';
                        }
                    } catch (error) {
                        showToast('error', error.response.data.message)
                    } finally {
                        loading.value = false;
                    }
                };

                const performAuth = async () => {
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
    LoginPopup.mount('#login-popup');
</script>
