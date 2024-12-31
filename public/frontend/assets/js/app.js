$(".banner-slider").slick({
    dots: false,
    arrows: false,
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    fade: true,
    autoplay: true,
    autoplaySpeed: 2000,
});

function setupRepeaterSlickSlider(selector, slidesToShow, responsiveSettings) {
    $(selector).slick({
        dots: false,
        arrows: true,
        infinite: false,
        slidesToShow: slidesToShow,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        responsive: responsiveSettings,
    });
}

setupRepeaterSlickSlider(".five-items-slider", 5, [
    { breakpoint: 1024, settings: { slidesToShow: 3 } },
    { breakpoint: 600, settings: { slidesToShow: 2 } },
    { breakpoint: 400, settings: { slidesToShow: 1 } },
]);

setupRepeaterSlickSlider(".four-items-slider", 4, [
    { breakpoint: 900, settings: { slidesToShow: 2 } },
    { breakpoint: 400, settings: { slidesToShow: 1 } },
]);

setupRepeaterSlickSlider(".three-items-slider", 3, [
    { breakpoint: 900, settings: { slidesToShow: 2 } },
    { breakpoint: 400, settings: { slidesToShow: 1 } },
]);



$(".destinations-slider").slick({
    dots: false,
    arrows: true,
    infinite: false,
    slidesToShow: 5,
    slidesToScroll: 1,
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 4,
            },
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 3,
            },
        },
        {
            breakpoint: 300,
            settings: {
                slidesToShow: 2,
            },
        },
    ],
});

$(".promotions-slider").slick({
    dots: false,
    infinite: false,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 1,
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
            },
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
            },
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            },
        },
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
    ],
});
$(".trending-products-slider").slick({
    dots: false,
    infinite: false,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
            },
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
            },
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            },
        },
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
    ],
});
$(".comment-slider").slick({
    autoplay: true,
    dots: false,
    infinite: false,
    speed: 300,
    slidesToShow: 1,
    slidesToScroll: 1,
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            },
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            },
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            },
        },
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
    ],
});

// accordian
const accordians = document.querySelectorAll(".accordian");
accordians.forEach((accordian) => {
    let accordianHeader = accordian.querySelector(".accordian-header");
    accordianHeader.addEventListener("click", () => {
        const isOpen = accordian.classList.contains("active");

        accordians.forEach((acc) => {
            acc.classList.remove("active");
        });

        if (!isOpen) {
            accordian.classList.add("active");
        }
    });
});
const accordians2 = document.querySelectorAll(".accordian-2");
accordians2.forEach((accordian) => {
    let accordianHeader = accordian.querySelector(".accordian-2-header");
    accordianHeader.addEventListener("click", () => {
        const isOpen = accordian.classList.contains("active");

        accordians2.forEach((acc) => {
            acc.classList.remove("active");
        });

        if (!isOpen) {
            accordian.classList.add("active");
        }
    });
});

$(".tour-details_banner2-slider").slick({
    dots: true,
    infinite: false,
    speed: 300,
    slidesToShow: 1,
    slidesToScroll: 1,
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: true,
            },
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
            },
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            },
        },
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
    ],
});

// Password Show Hide
document.querySelectorAll(".password-field__show").forEach((button) => {
    button.addEventListener("click", function () {
        const passwordField = this.previousElementSibling;
        if (passwordField.type === "password") {
            passwordField.type = "text";
            this.classList.add("open");
        } else {
            passwordField.type = "password";
            this.classList.remove("open");
        }
    });
});

// SideBar

function openSideBar() {
    document.getElementById("sideBar").classList.add("show");
}

function closeSideBar() {
    document.getElementById("sideBar").classList.remove("show");
}

const dropdownToggle = document.querySelector(".drop-down--toggle");
const sidebarNav = document.querySelector(".sideBar__nav");

dropdownToggle.addEventListener("click", () => {
    const toggleWrapper = dropdownToggle.querySelector(".toggle-wrapper");
    if (toggleWrapper) {
        toggleWrapper.classList.toggle("open");
    }
});

document.addEventListener("DOMContentLoaded", function () {
    let images = document.querySelectorAll("img.lazy");
    let observer = new IntersectionObserver(
        (entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    let img = entry.target;
                    img.src = img.dataset.src;
                    observer.unobserve(img);
                }
            });
        },
        {
            rootMargin: "200px 0px",
            threshold: 0.1,
        }
    );
    images.forEach((image) => {
        observer.observe(image);
    });
});

// sign up form
const loginBtns = document.querySelectorAll(".loginBtn");
const loginPopup = document.getElementById("loginPopup");
const closePopups = document.querySelectorAll(".closePopup");
const forgotPasswordLink = document.getElementById("forgotPassword");
forgotPasswordLink.addEventListener("click", () => {
    document.querySelectorAll(".signUpStep").forEach((signUpStep) => {
        signUpStep.classList.remove("show");
    });
    let forgotPasswordStep = document.querySelector(".for-forgot-password");
    forgotPasswordStep.classList.add("show");
    forgotPasswordStep
        .querySelector(".check-fields")
        .addEventListener("input", () => validateForm(forgotPasswordStep));
});

loginBtns.forEach((loginBtn) => {
    loginBtn.addEventListener("click", () => {
        loginPopup.classList.add("open");
    });
});
closePopups.forEach((closePopup) => {
    closePopup.addEventListener("click", () => {
        loginPopup.classList.remove("open");
    });
});
window.addEventListener("click", (event) => {
    if (event.target === loginPopup) {
        loginPopup.classList.remove("open");
    }
});

const signUpStep = document.querySelector(".signUpStep.show");
const checkEmailForm = document.getElementById("checkEmailForm");
const checkEmailStep = document.querySelector(".for-check-email");
const correctEmailFormat = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const baseUrl = document.getElementById("web_base_url").value;

document.addEventListener("DOMContentLoaded", () => {
    startSignForm(signUpStep);

    checkEmailForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        let loaderBtn = e.target.querySelector(".loginSignup-popup__btn");
        const emailField = checkEmailForm.querySelector('input[name="email"]');
        await checkUser(emailField.value, loaderBtn);
    });
});

const getCsrfToken = () => {
    return document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
};

const validateForm = (signUpStep) => {
    const fields = signUpStep.querySelectorAll(".check-fields");
    const submitButton = signUpStep.querySelector(".loginSignup-popup__btn");
    let allFieldsValid = true;

    fields.forEach((field) => {
        if (field.type === "email") {
            if (!correctEmailFormat.test(field.value)) {
                allFieldsValid = false;
            }
        } else if (field.type === "password") {
            if (field.value.length <= 7) {
                allFieldsValid = false;
            }
        } else {
            if (field.value.trim() === "") {
                allFieldsValid = false;
            }
        }
    });

    if (allFieldsValid) {
        submitButton.classList.remove("disabled");
    } else {
        submitButton.classList.add("disabled");
    }
};

const checkUser = async (email, loaderElement) => {
    loaderElement.innerHTML = `<div class="spinner-border spinner-border-sm"></div>`;
    try {
        const response = await fetch(
            `${baseUrl}/check-account?email=${email}`,
            {
                method: "POST",
                headers: {
                    // 'X-Requested-With': 'XMLHttpRequest',
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": getCsrfToken(),
                },
                credentials: "same-origin",
            }
        );

        const data = await response.json();

        if (data.status === "200") {
            document.querySelector(".for-login").classList.add("show");
            document.querySelector(".for-check-email").classList.remove("show");
            startSignForm(document.querySelector(".for-login"));
        } else if (data.status === "404") {
            document.querySelector(".for-check-email").classList.remove("show");
            document.querySelector(".for-sign-up").classList.add("show");
            startSignForm(document.querySelector(".for-sign-up"));
        } else if (data.status === "error") {
            $.toast({
                heading: "Error!",
                position: "bottom-right",
                text: data.message,
                loaderBg: "#ff6849",
                icon: "error",
                hideAfter: 2000,
                stack: 6,
            });
        }
    } catch (error) {
        console.error("Error checking user:", error);
    } finally {
        loaderElement.innerHTML = `Continue with email`;

        const form = document.querySelector(".authForm.show");

        if (form) {
            form.querySelector(".prev-data__email").setAttribute(
                "value",
                email
            );

            form.addEventListener("submit", (e) => {
                e.preventDefault();
                submitAuthForm(form);
            });
        }
    }
};

const startSignForm = (signUpStep) => {
    const fields = signUpStep.querySelectorAll(".check-fields");
    fields.forEach((field) => {
        field.addEventListener("input", () => validateForm(signUpStep));
    });

    validateForm(signUpStep);
};

const submitAuthForm = (form) => {
    let clickedButton = form.querySelector(".loginSignup-popup__btn");
    const buttonPrevContent = clickedButton.innerHTML;

    clickedButton.innerHTML = `<div class="spinner-border spinner-border-sm"></div>`;

    if (!form) {
        console.error("Form element not found");
        return;
    }
    const currentUrl = window.location.href;
    const formData = new FormData(form);
    const dataObject = {};
    formData.forEach((value, key) => {
        dataObject[key] = value;
    });
    fetch(form.action, {
        method: "POST",
        body: formData,
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": getCsrfToken(),
        },
        body: new URLSearchParams(dataObject),
        credentials: "same-origin",
    })
        .then((response) => {
            if (response.status === 419) {
                throw new Error("CSRF token mismatch");
            }
            return response.json();
        })
        .then((data) => {
            if (data.status === "success") {
                $.toast({
                    heading: "Success!",
                    position: "bottom-right",
                    text: data.message,
                    loaderBg: "#ff6849",
                    icon: "success",
                    hideAfter: 1500,
                    stack: 6,
                });
                setTimeout(() => {
                    window.location.href =
                        data.redirect_url === "current"
                            ? currentUrl
                            : data.redirect_url;
                }, 1200);
            } else {
                $.toast({
                    heading: "Error!",
                    position: "bottom-right",
                    text: data.message,
                    loaderBg: "#ff6849",
                    icon: "error",
                    hideAfter: 2000,
                    stack: 6,
                });
                if (data.old_input) {
                    Object.keys(data.old_input).forEach((key) => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) input.value = data.old_input[key];
                    });
                }
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            $.toast({
                heading: "Error!",
                position: "bottom-right",
                text: "An unexpected error occurred.",
                loaderBg: "#ff6849",
                icon: "error",
                hideAfter: 2000,
                stack: 6,
            });
        })
        .finally(() => {
            clickedButton.innerHTML = buttonPrevContent;
        });
};

// Custom quantity Counters
const quantityWrappers = document.querySelectorAll(".quantity-counter");
quantityWrappers.forEach((counter) => {
    const quantityField = counter.querySelector(
        ".quantity-counter__btn--quantity"
    );
    const minusBtn = counter.querySelector(".quantity-counter__btn--minus");
    const plusBtn = counter.querySelector(".quantity-counter__btn--plus");

    if (quantityField && minusBtn && plusBtn) {
        let quantity = quantityField.value;
        minusBtn.addEventListener("click", () => {
            if (quantity !== 0) {
                --quantity;
                quantityField.value = quantity;
            }
        });
        plusBtn.addEventListener("click", () => {
            ++quantity;
            quantityField.value = quantity;
        });
    } else {
        console.error("Maintain HTML Structure for quantity counter");
    }
});

// ToolTips
const showTooltips = () => {
    document
        .querySelectorAll('[data-tooltip="tooltip"]')
        .forEach(function (element) {
            new bootstrap.Tooltip(element, {
                html: true,
            });
        });
};

document.addEventListener("DOMContentLoaded", function () {
    showTooltips();
});
