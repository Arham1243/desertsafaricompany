$(function () {
    if ($(".date-picker").length) {
        $(".date-picker")
            .daterangepicker({
                singleDatePicker: true,
                showCalendar: false,
                autoUpdateInput: false,
                sameDate: true,
                autoApply: true,
                disabledPast: true,
                enableLoading: true,
                showEventTooltip: true,
                classNotAvailable: ["disabled", "off"],
                disableHightLight: true,
                locale: { format: "YYYY-MM-DD" },
            })
            .on("apply.daterangepicker", function (event, picker) {
                $(this).val(picker.startDate.format("YYYY-MM-DD"));
            });
    }
});

// Function to update the label text based on the switch state
function updateLabel(container) {
    const checkbox = container.querySelector("input[data-toggle-switch]");
    const label = container.querySelector("label");

    const enabledText =
        container.getAttribute("data-enabled-text") || "Enabled";
    const disabledText =
        container.getAttribute("data-disabled-text") || "Disabled";

    if (checkbox.checked) {
        label.textContent = enabledText;
    } else {
        label.textContent = disabledText;
    }
}
const initializeToggleSwitchLabels = (switchElement) => {
    const container = switchElement.closest(
        "[data-enabled-text], [data-disabled-text]",
    );

    if (container) {
        updateLabel(container);

        switchElement.addEventListener("change", () => updateLabel(container));
    }
};

document.addEventListener("DOMContentLoaded", function () {
    document
        .querySelectorAll("input[data-toggle-switch]")
        .forEach(initializeToggleSwitchLabels);
});

document
    .querySelector(".permalink-input")
    ?.addEventListener("click", function () {
        if (this.type === "button") {
            this.type = "text";
            this.focus();
        }
    });

document
    .querySelector(".permalink-input")
    ?.addEventListener("blur", function () {
        const hiddenField = document.getElementById(this.dataset.fieldId);
        if (this.type === "text") {
            if (this.value.trim() === "") {
                this.value = "#";
                hiddenField.value = "";
            } else {
                hiddenField.value = this.value.trim();
            }
            this.type = "button";
        }
    });

document
    .querySelector(".permalink-input")
    ?.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            this.blur();
        }
    });

function showIcon(iconField) {
    iconField.parentElement
        .querySelector("[data-preview-icon]")
        .setAttribute("class", `${iconField.value} bx-md`);
}

window.addEventListener("load", function () {
    const loader = document.getElementById("loader");
    loader.style.display = "none";
});

// Single File Upload
function showImage(input, previewImgId, filenamePreviewId) {
    var file = input.files[0];
    var allowedTypes = [
        "image/jpeg",
        "image/png",
        "image/gif",
        "image/webp",
        "image/svg+xml",
        "image/bmp",
        "image/tiff",
    ];

    if (file && allowedTypes.includes(file.type)) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $("#" + previewImgId).attr("src", e.target.result);
            $("#" + filenamePreviewId).text(file.name);
        };
        reader.readAsDataURL(file);
    } else if (file) {
        alert(
            "Please select a valid image file. Supported formats: JPEG, PNG, GIF, WEBP, SVG, BMP, TIFF.",
        );
        input.value = "";
    }
}

let table = new DataTable(".data-table", {
    columnDefs: [
        { orderable: false, targets: 0 }, // first column unsortable
    ],
    ordering: false, // disable all sorting
});

// SideBar Dropdown
document.addEventListener("DOMContentLoaded", function () {
    // Get all dropdowns
    const dropdowns = document.querySelectorAll(".custom-dropdown__active");

    // Add click event listener to each dropdown trigger
    dropdowns.forEach(function (dropdown) {
        dropdown.addEventListener("click", function (e) {
            // Prevent default action for anchor tags
            e.preventDefault();

            // Toggle the 'open' class on the current dropdown
            const parentDropdown = this.parentElement;
            parentDropdown.classList.toggle("open");

            // If it has sub-dropdowns, toggle its children as well
            const subDropdown = parentDropdown.querySelector(
                ".custom-dropdown__values",
            );
            if (subDropdown) {
                subDropdown.classList.toggle("open");
            }
        });
    });
});

// Choices Select
const initializeChoiceSelects = () => {
    const choiceSelects = document.querySelectorAll(".choice-select");
    choiceSelects?.forEach((select) => {
        const maxItems = select.hasAttribute("data-max-items")
            ? parseInt(select.getAttribute("data-max-items"))
            : -1;
        const shouldSort = select.hasAttribute("should-sort")
            ? select.getAttribute("should-sort") === "true"
            : true;
        new Choices(select, {
            searchEnabled: true,
            itemSelectText: "",
            placeholder: true,
            placeholderValue: select.getAttribute("placeholder"),
            addItems: true,
            delimiter: ", ",
            maxItemCount: maxItems,
            removeItemButton: true,
            shouldSort: shouldSort,
            duplicateItemsAllowed: false,
        });
    });
};
document.addEventListener("DOMContentLoaded", function () {
    initializeChoiceSelects();
});

// Configuration function for Select2
const getSelect2Config = (select, maxItems, shouldSort) => {
    return {
        placeholder: select.getAttribute("placeholder") || "Select",
        maximumSelectionLength: maxItems > 0 ? maxItems : undefined,
        allowClear: true,
        sorter: shouldSort
            ? function (data) {
                  return data.sort(function (a, b) {
                      return a.text.localeCompare(b.text);
                  });
              }
            : undefined,
        language: {
            noResults: function () {
                return "No results found";
            },
        },
    };
};

const initializeSelect2 = () => {
    const selectElements = document.querySelectorAll(".select2-select");
    selectElements.forEach((select) => {
        // Destroy any existing Select2 instance
        if ($(select).data("select2")) {
            $(select).select2("destroy");
        }

        const maxItems = select.hasAttribute("data-max-items")
            ? parseInt(select.getAttribute("data-max-items"))
            : -1;

        // Disable sorting for categories dropdown (you can adjust this condition as needed)
        const shouldSort = select.classList.contains("category-select")
            ? false // Disable sorting for category select
            : select.hasAttribute("should-sort")
              ? select.getAttribute("should-sort") === "true"
              : true;

        const config = getSelect2Config(select, maxItems, shouldSort);
        // Initialize Select2
        $(select).select2(config);
    });
};

document.addEventListener("DOMContentLoaded", function () {
    initializeSelect2();
});

// Multple File Upload
document.addEventListener("DOMContentLoaded", () => {
    const uploadComponents = document.querySelectorAll(
        "[data-upload-multiple]",
    );

    uploadComponents.forEach((uploadComponent) => {
        const fileInput = uploadComponent.querySelector(
            "[data-upload-multiple-input]",
        );
        const imageContainer = uploadComponent.querySelector(
            "[data-upload-multiple-images]",
        );
        const errorMessage = uploadComponent.querySelector(
            "[data-upload-multiple-error]",
        );

        fileInput.addEventListener("change", (event) => {
            const fileList = event.target.files;

            imageContainer.innerHTML = "";
            errorMessage.classList.add("d-none");

            let hasInvalidFiles = false;

            // Check for invalid files
            Array.from(fileList).forEach((file) => {
                if (!file.type.startsWith("image/")) {
                    hasInvalidFiles = true;
                }
            });

            if (hasInvalidFiles) {
                errorMessage.textContent = "Please upload a valid image file";
                errorMessage.classList.remove("d-none");
                return;
            }

            // Process valid image files
            Array.from(fileList).forEach((file) => {
                const reader = new FileReader();

                reader.onload = (e) => {
                    // Create a list item for each image
                    const li = document.createElement("li");
                    li.className = "single-image";

                    li.innerHTML = `
                    <div class="delete-btn">
                        <i class='bx bxs-trash-alt'></i>
                    </div>
                    <a class="mask" href="${e.target.result}" data-fancybox="gallery"><img src="${e.target.result}" class="imgFluid" /></a>
                     <input type="text" name="gallery_alt_texts[]" value="" class="field" placeholder="Enter Name" required>
                `;

                    // Append the list item to the image container
                    imageContainer.appendChild(li);

                    // Add delete functionality
                    li.querySelector(".delete-btn").addEventListener(
                        "click",
                        () => {
                            imageContainer.removeChild(li);
                        },
                    );
                };

                reader.readAsDataURL(file);
            });
        });
    });
});

// Editor
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("validation-form");
    if (form) {
        const editors = initializeEditors(form);

        form.addEventListener("submit", function (event) {
            const requiredEditors = editors.filter((editor) => {
                return editor.sourceElement.dataset.required !== undefined;
            });
            const isValid = validateForm(form, requiredEditors);

            if (!isValid) {
                event.preventDefault();
            }
        });
    }
});

// Tiny Editor
// function initializeEditors(form) {
//     const editors = [];
//     const editorElements = form.querySelectorAll(".editor");

//     editorElements.forEach((editorElement) => {
//         tinymce.init({
//             target: editorElement,
//             plugins:
//                 "advlist autolink link image lists charmap print preview anchor \
//                       searchreplace visualblocks code fullscreen insertdatetime media table \
//                       paste code wordcount emoticons hr pagebreak save directionality \
//                       template toc textpattern imagetools visualchars nonbreaking codesample",
//             toolbar:
//                 "undo redo | formatselect | bold italic underline strikethrough forecolor backcolor | \
//                       alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | \
//                       link image media | preview fullscreen | emoticons codesample blockquote hr pagebreak | \
//                       removeformat",
//             height: 300, // Adjust the height for the editor
//             menubar: true, // Show the menubar
//             branding: false, // Remove TinyMCE branding
//             image_advtab: true, // Advanced image options
//             media_live_embeds: true, // Auto-embed media
//             paste_data_images: true, // Allow pasting images
//             automatic_uploads: true, // Auto-upload images while editing
//             file_picker_types: "image media", // Enable file picker for image/media
//             content_style:
//                 "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }",
//             setup: function (editor) {
//                 editor.on("init", function () {
//                     editors.push(editor);
//                 });
//             },
//         });
//     });

//     return editors;
// }

// Ck Editor test
function initializeEditors(form) {
    const editors = [];
    const editorElements = form.querySelectorAll(".editor");

    editorElements.forEach((editorElement) => {
        ClassicEditor.create(editorElement, {
            toolbar: [
                "undo",
                "redo",
                "|",
                "heading",
                "|",
                "bold",
                "italic",
                "underline",
                "strikethrough",
                "fontColor",
                "highlight",
                "|",
                "alignment",
                "|",
                "bulletedList",
                "numberedList",
                "outdent",
                "indent",
                "|",
                "link",
                "imageUpload",
                "blockQuote",
                "insertTable",
                "|",
                "mediaEmbed",
                "horizontalLine",
                "pageBreak",
                "|",
                "removeFormat",
                "codeBlock",
                "|",
                "specialCharacters",
                "|",
                "fullScreen",
                "|",
                "preview",
            ],
            heading: {
                options: [
                    {
                        model: "paragraph",
                        title: "Paragraph",
                        class: "ck-heading_paragraph",
                    },
                    {
                        model: "heading1",
                        view: { name: "h1", classes: "" },
                        title: "Heading 1",
                        class: "ck-heading_heading1",
                    },
                    {
                        model: "heading2",
                        view: { name: "h2", classes: "" },
                        title: "Heading 2",
                        class: "ck-heading_heading2",
                    },
                    {
                        model: "heading3",
                        view: { name: "h3", classes: "" },
                        title: "Heading 3",
                        class: "ck-heading_heading3",
                    },
                    {
                        model: "heading4",
                        view: { name: "h4", classes: "" },
                        title: "Heading 4",
                        class: "ck-heading_heading4",
                    },
                    {
                        model: "heading5",
                        view: { name: "h5", classes: "" },
                        title: "Heading 5",
                        class: "ck-heading_heading5",
                    },
                    {
                        model: "heading6",
                        view: { name: "h6", classes: "" },
                        title: "Heading 6",
                        class: "ck-heading_heading6",
                    },
                ],
            },
            // You can set additional configurations here
            height: "300px",
            // Add custom styles here if needed
            // Note: CKEditor 5 does not support `content_style` like TinyMCE
        })
            .then((editor) => {
                editors.push(editor);
            })
            .catch((error) => {
                console.error(
                    "There was a problem initializing the editor:",
                    error,
                );
            });
    });

    return editors;
}

// Function to validate the form
function validateForm(form, editors) {
    let isValid = true;
    const requiredFields = form.querySelectorAll("[data-required]");

    requiredFields.forEach((field) => {
        if (field.classList.contains("editor")) {
            return; // Skip editor fields
        }
        isValid = validateField(field) && isValid;
    });

    editors.forEach((editorInstance) => {
        isValid = validateEditor(editorInstance) && isValid;
    });

    return isValid;
}

// Function to validate standard fields
function validateField(field) {
    if (
        (!field.value.trim() &&
            !(field.type === "file" && field.classList.contains("d-none"))) ||
        (field.type === "file" && field.files.length === 0)
    ) {
        showErrorToast(`${field.dataset.error || field.name} is Required!`);
        return false;
    }
    return true;
}

// Function to validate TinyMCE editor fields
function validateEditor(editorInstance) {
    const editorData = tinymce
        .get(editorInstance.id)
        .getContent({ format: "text" });
    const editorElement = editorInstance.targetElm;

    if (!editorData.trim()) {
        showErrorToast(
            `${editorElement.dataset.error || editorElement.name} is Required!`,
        );
        return false;
    }
    return true;
}

// Ck editor
function validateEditor(editorInstance) {
    const editorData = editorInstance.getData();
    const editorElement = editorInstance.sourceElement;

    if (!editorData.trim()) {
        showErrorToast(
            `${editorElement.dataset.error || editorElement.name} is Required!`,
        );
        return false;
    }
    return true;
}

// Function to show toast messages
function showErrorToast(message) {
    $.toast({
        heading: "Error!",
        position: "bottom-right",
        text: message,
        loaderBg: "#ff6849",
        icon: "error",
        hideAfter: 2000,
        stack: 6,
    });
}

// Bulk Action
document.addEventListener("DOMContentLoaded", function () {
    const selectAllCheckbox = document.getElementById("select-all");

    function initializeBulkActionCheckboxes() {
        const itemCheckboxes = document.querySelectorAll(".bulk-item");

        if (itemCheckboxes && selectAllCheckbox) {
            function toggleSelectAll() {
                const isChecked = selectAllCheckbox.checked;
                itemCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = isChecked;
                });
            }

            function handleItemCheckboxChange() {
                const allChecked = Array.from(itemCheckboxes).every(
                    (checkbox) => checkbox.checked,
                );
                selectAllCheckbox.checked = allChecked;
            }

            // Attach event listeners to checkboxes
            selectAllCheckbox.addEventListener("change", toggleSelectAll);
            itemCheckboxes.forEach(function (checkbox) {
                checkbox.addEventListener("change", handleItemCheckboxChange);
            });
        }
    }

    // Initialize the checkboxes for the first time
    initializeBulkActionCheckboxes();

    // If you're using DataTables, listen for the draw event and reinitialize the checkboxes
    $(".data-table").on("draw.dt", function () {
        initializeBulkActionCheckboxes();
    });
});

function confirmBulkAction(event) {
    const selectedAction = document.getElementById("bulkActions").value;

    if (selectedAction === "delete") {
        const confirmation = confirm(
            "Are you sure you want to delete the selected items?",
        );
        if (!confirmation) {
            event.preventDefault();
        }
    }

    if (selectedAction === "permanent_delete") {
        const message =
            "This action will permanently delete the selected items and all related fields. Do you want to proceed?";
        const confirmation = confirm(message);
        if (!confirmation) {
            event.preventDefault();
        }
    }
}

function initializeUploadComponent(uploadComponent) {
    const fileInput = uploadComponent.querySelector("[data-file-input]");
    const uploadBox = uploadComponent.querySelector("[data-upload-box]");
    const uploadImgBox = uploadComponent.querySelector("[data-upload-img]");
    const uploadPreview = uploadComponent.querySelector(
        "[data-upload-preview]",
    );
    const deleteBtn = uploadComponent.querySelector("[data-delete-btn]");
    const errorMessage = uploadComponent.querySelector("[data-error-message]");

    const fileNameDiv = document.createElement("div");
    fileNameDiv.className = "filename";
    uploadImgBox.appendChild(fileNameDiv, uploadPreview);

    fileInput.addEventListener("change", function (event) {
        const file = event.target.files[0];

        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();

            reader.onload = function (e) {
                uploadPreview.src = e.target.result;
                uploadImgBox.querySelector(".mask").href = e.target.result;
                uploadImgBox.querySelector(".filename").textContent = file.name;
            };

            reader.readAsDataURL(file);

            uploadBox.classList.remove("show");
            uploadImgBox.classList.add("show");
            errorMessage.classList.add("d-none");
            if (uploadImgBox.querySelector("input")) {
                uploadImgBox.querySelector("input").focus();
            }
        } else {
            errorMessage.classList.remove("d-none");
            fileInput.value = "";
        }
    });

    deleteBtn?.addEventListener("click", function () {
        fileInput.value = "";
        uploadBox.classList.add("show");
        uploadImgBox.classList.remove("show");
        fileNameDiv.textContent = "";
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const uploadComponents = document.querySelectorAll("[data-upload]");
    uploadComponents?.forEach((uploadComponent) => {
        initializeUploadComponent(uploadComponent);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document
        .querySelectorAll("[data-color-picker-container]")
        ?.forEach((element) => {
            InitializeColorPickers(element);
        });
});

const InitializeColorPickers = (pickerContainer) => {
    const colorPicker = pickerContainer.querySelector("[data-color-picker]");
    const colorPickerInput = pickerContainer.querySelector(
        "[data-color-picker-input]",
    );

    const initialColor =
        colorPickerInput.value &&
        /^#([0-9A-F]{3}){1,2}$/i.test(colorPickerInput.value)
            ? colorPickerInput.value
            : colorPickerInput.getAttribute("placeholder");
    if (colorPicker && colorPickerInput) {
        const pickr = Pickr.create({
            el: colorPicker,
            theme: "classic",
            default: initialColor,
            components: {
                preview: true,
                hue: true,
                interaction: {
                    hex: true,
                    input: true,
                },
            },
        });

        pickr.on("init", () => {
            const colorPickerThumb = pickerContainer.querySelector(".pickr");

            colorPickerThumb.style.backgroundColor = initialColor;

            if (colorPickerThumb) {
                pickr.on("change", (color) => {
                    const hexColor = color.toHEXA().toString();
                    colorPickerInput.value = hexColor;
                    colorPickerThumb.style.backgroundColor = hexColor;
                });
            }

            colorPickerInput.addEventListener("input", () => {
                const newColor = colorPickerInput.value;
                if (/^#([0-9A-F]{3}){1,2}$/i.test(newColor)) {
                    pickr.setColor(newColor);
                }
            });
        });
    }
};

function initializeFlagInputs() {
    $("[data-flag-input-wrapper]").each(function () {
        var $wrapper = $(this);
        var input = $wrapper.find("[data-flag-input]");

        if (input.length > 0) {
            input.intlTelInput({
                initialCountry: "ae",
                separateDialCode: true,
            });

            function updateCountryCode() {
                var countryData = input.intlTelInput("getSelectedCountryData");
                if (countryData && countryData.dialCode) {
                    $wrapper
                        .find("[data-flag-input-country-code]")
                        .val(countryData.iso2);
                    $wrapper
                        .find("[data-flag-input-dial-code]")
                        .val(countryData.dialCode);
                }
            }

            input.on("countrychange", function (e) {
                updateCountryCode();
            });

            var countryCode = $wrapper
                .find("[data-flag-input-country-code]")
                .val();
            if (countryCode) {
                input.intlTelInput("setCountry", countryCode);
            }
        }
    });
}
$(document).ready(function () {
    initializeFlagInputs();
});

// Custom Accordian
document.querySelectorAll("[custom-accordion]")?.forEach((accordion) => {
    accordion
        .querySelector("[custom-accordion-header]")
        ?.addEventListener("click", () => {
            accordion.classList.toggle("open");
        });
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

function initializeFeatures() {
    showTooltips();
    initializeSelect2();
    document
        .querySelectorAll("[data-upload]")
        .forEach(initializeUploadComponent);
    document
        .querySelectorAll("[data-color-picker-container]")
        ?.forEach((element) => {
            InitializeColorPickers(element);
        });
    document
        .querySelectorAll("input[data-toggle-switch]")
        .forEach(initializeToggleSwitchLabels);
    document
        .querySelectorAll("[data-editable-field]")
        .forEach(function (field) {
            syncToEditable(field);
        });
}

const showMessage = (message, type) => {
    $.toast({
        heading: type === "success" ? "Success!" : "Error!",
        position: "bottom-right",
        text: message,
        loaderBg: type === "success" ? "#ff6849" : "#ff6849",
        icon: type === "success" ? "success" : "error",
        hideAfter: 3000,
        stack: 6,
    });
};
document.getElementById("sidebarToggle")?.addEventListener("click", () => {
    document
        .getElementById("main-dashboard-wrapper")
        ?.classList.toggle("close-sidebar");
});
