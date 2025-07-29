$(".one-item-fade-slider").slick({
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
        infinite: true,
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

setupRepeaterSlickSlider(".one-items-slider", 1, [
    { breakpoint: 900, settings: { slidesToShow: 1 } },
    { breakpoint: 400, settings: { slidesToShow: 1 } },
]);

// Faqs Accordian
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
        },
    );
    images.forEach((image) => {
        observer.observe(image);
    });
});

// Custom quantity Counters
const quantityWrappers = document.querySelectorAll(".quantity-counter");
quantityWrappers.forEach((counter) => {
    const quantityField = counter.querySelector(
        ".quantity-counter__btn--quantity",
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

const todayDate = new Date().toISOString().split("T")[0];
document.getElementById("start_date")?.setAttribute("min", todayDate);
const emptyParas = Array.from(document.querySelectorAll("p")).filter(
    (p) => p.innerHTML.trim() === "&nbsp;",
);

if (emptyParas.length > 0) {
    emptyParas.forEach((p) => p.classList.add("empty"));
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[data-show-more]").forEach((container) => {
        const content = container.querySelector("[data-show-more-content]");
        const button = container.querySelector("[data-show-more-btn]");

        if (content && button) {
            const moreText = button.getAttribute("more-text") || "Show less";
            const lessText = button.getAttribute("less-text") || "Show more";

            button.addEventListener("click", function () {
                const isExpanded = content.style.display === "block";

                content.style.display = isExpanded ? "-webkit-box" : "block";
                button.textContent = isExpanded ? moreText : lessText;
            });
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[data-show-more]").forEach((container) => {
        const content = container.querySelector("[data-show-more-content]");
        const button = container.querySelector("[data-show-more-btn]");

        if (content && button) {
            const isTruncated = content.scrollHeight > content.clientHeight;

            if (isTruncated) {
                button.style.display = "inline-block"; //
            } else {
                button.style.display = "none";
            }
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const loginButtons = document.querySelectorAll("[open-vue-login-popup]");
    if (loginButtons.length > 0) {
        loginButtons.forEach((btn, index) => {
            btn.addEventListener("click", () => {
                document.querySelector('.sideBar')?.classList.remove('show')
                LoginPopupApp.openLoginPopup();
            });
        });
    }
});


document.addEventListener('click', function(e) {
    if (e.target === document.querySelector('.login-wrapper')) {
        document.querySelector('.login-wrapper')?.classList.remove('open');
    }
});


document.addEventListener('click', function(e) {
    if (e.target === document.querySelector('[data-send-popup]')) {
        document.querySelector('[data-send-popup]').classList.remove('open');
    }
});