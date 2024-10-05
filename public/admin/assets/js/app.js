function populateTimeDropdown() {
    const dropdown = document.getElementById("time-dropdown");
    if (dropdown) {
        dropdown.innerHTML = "";

        const startTime = 15; // Start from 15 minutes
        const endTime = 240; // 4 hours in minutes
        const interval = 15; // Gap of 15 minutes

        for (let minutes = startTime; minutes <= endTime; minutes += interval) {
            const time = moment()
                .startOf("day")
                .add(minutes, "minutes")
                .format("HH:mm");
            const option = document.createElement("option");
            option.value = minutes;
            option.textContent = `${time} (${minutes} mins)`;
            dropdown.appendChild(option);
        }
    }
}

populateTimeDropdown();

const sortableTableBody = document.querySelector("[data-sortable-body]");
if (sortableTableBody) {
    const sortable = new Sortable(sortableTableBody, {
        animation: 300,
        handle: ".order-menu",
        onEnd: function (/**Event*/ evt) {
            console.log(
                "Dragged item index: ",
                evt.oldIndex,
                " -> ",
                evt.newIndex,
            );
            updateOrderFields();
        },
    });
}
function toggleAddStopButton() {
    const stops = itineraryTableBody.querySelectorAll(
        'tr[data-item-type="stop"]',
    );
    const addStopBtn = document.getElementById("add-stop-btn");
    const hasValue = Array.from(
        itineraryTableBody.querySelectorAll(
            'input[name="itinerary[stops][title][]"]',
        ),
    ).some((input) => input.value.trim() !== "");

    if (stops.length > 0 && hasValue) {
        addStopBtn.classList.remove("d-none");
    } else {
        addStopBtn.classList.add("d-none");
    }
}

function updateOrderFields() {
    // Update order for all rows (vehicles and stops) in unified order
    const rows = document.querySelectorAll("#itinerary-table-body tr");
    rows.forEach((row, index) => {
        const orderField = row.querySelector(
            "input[name='itinerary[order][]']",
        );
        if (orderField) {
            orderField.value = index + 1; // Update the order number
        }
    });
    toggleAddStopButton();
}

const itineraryTableBody = document.getElementById("itinerary-table-body");
const subStopsSection = document.getElementById(
    "itinerary_experience_sub_stops",
);
const subStopsCheckbox = document.getElementById(
    "itinerary_experience_enabled_sub_stops",
);

if (itineraryTableBody && subStopsSection && subStopsCheckbox) {
    // Toggle Sub Stops visibility on checkbox change
    subStopsCheckbox.addEventListener("change", function () {
        if (this.checked) {
            subStopsSection.classList.remove("d-none");
            populateMainStopDropdown(); // Repopulate when checkbox is checked
        } else {
            subStopsSection.classList.add("d-none");
        }
    });

    function createRow(type) {
        let row = null;
        const order = itineraryTableBody.children.length + 1; // Calculate current order
        if (type === "vehicle") {
            row = `
        <tr data-item-type="vehicle" draggable="true">
            <td><div class="order-menu"><i class='bx-sm bx bx-menu'></i></div>
            <input type="hidden" name="itinerary[order][]["type]" value="vehicle">
            <input type="hidden" name="itinerary[order][][index]" value="${order}"></td>
            <td><div class="d-flex align-items-center gap-1"><i class='bx bxs-car'></i>Vehicle</div></td>
            <td><input name="itinerary[vehicles][name][]" type="text" class="field" placeholder="Name"></td>
            <td><input name="itinerary[vehicles][time][]" type="number" class="field" placeholder="Time (mins)"></td>
            <td><button type="button" class="delete-btn ms-auto delete-btn--static"><i class='bx bxs-trash-alt'></i></button></td>
        </tr>`;
        } else if (type === "stop") {
            row = `
        <tr data-item-type="stop" draggable="true">
            <td><div class="order-menu"><i class='bx-sm bx bx-menu'></i></div>
            <input type="hidden" name="itinerary[order][][type]" value="stop">
            <input type="hidden" name="itinerary[order][][index]" value="${order}"></td>
            <td><div class="d-flex align-items-center gap-1"><i class="bx bx-star"></i>Stop</div></td>
            <td><input name="itinerary[stops][title][]" type="text" class="field" placeholder="Title"></td>
            <td><input name="itinerary[stops][activities][]" type="text" class="field" placeholder="Activities"></td>
            <td><button type="button" class="delete-btn ms-auto delete-btn--static"><i class='bx bxs-trash-alt'></i></button></td>
        </tr>`;
        }
        return row;
    }

    document.querySelectorAll("[data-itinerary-action]").forEach((item) => {
        item.addEventListener("click", function () {
            const action = this.getAttribute("data-itinerary-action");
            if (action === "add-vehicle") {
                itineraryTableBody.insertAdjacentHTML(
                    "beforeend",
                    createRow("vehicle"),
                );
            } else if (action === "add-stop") {
                itineraryTableBody.insertAdjacentHTML(
                    "beforeend",
                    createRow("stop"),
                );
            }
            updateOrderFields(); // Ensure order is updated after adding rows
            closeSubStopsSection(); // Close sub stops section after adding new rows
        });
    });

    // Update the order whenever a row is removed
    itineraryTableBody.addEventListener("click", function (e) {
        if (e.target.closest(".delete-btn")) {
            const row = e.target.closest("tr");
            row.remove();
            updateOrderFields(); // Update order after row removal
            populateMainStopDropdown(); // Repopulate dropdown when stops are removed
            closeSubStopsSection(); // Close sub stops section after row removal
        }
    });

    // Close sub stops section
    function closeSubStopsSection() {
        subStopsCheckbox.checked = false;
        subStopsSection.classList.add("d-none");
    }
    // Handle input field changes for dynamic updates
    itineraryTableBody.addEventListener("input", function (e) {
        if (e.target.name === "itinerary[stops][title][]") {
            closeSubStopsSection(); // Close sub stops section if stop name changes
            toggleAddStopButton();
        }
    });

    // Populate the main stop dropdown with the latest stop names
    function populateMainStopDropdown() {
        const stopNames = document.querySelectorAll(
            "input[name='itinerary[stops][title][]']",
        );
        const mainStopDropdowns = document.querySelectorAll(
            "select[name='itinerary[stops][sub_stops][main_stop][]']",
        );

        mainStopDropdowns.forEach((dropdown) => {
            dropdown.innerHTML =
                '<option value="" selected disabled>Select</option>';
        });

        stopNames.forEach((stopInput) => {
            const stopTitle = stopInput.value.trim();
            if (stopTitle) {
                mainStopDropdowns.forEach((dropdown) => {
                    const option = document.createElement("option");
                    option.value = stopTitle;
                    option.textContent = stopTitle;
                    dropdown.appendChild(option);
                });
            }
        });
    }

    // Initialize default order on page load
    updateOrderFields();
}

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

// DataTable
let table = new DataTable(".data-table", {
    columnDefs: [
        { orderable: false, targets: 0 }, // Disable sorting for the first column (index 0)
    ],
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
document.addEventListener("DOMContentLoaded", function () {
    const choiceSelects = document.querySelectorAll(".choice-select");
    choiceSelects.forEach((select) => {
        const maxItems = select.hasAttribute("data-max-items")
            ? parseInt(select.getAttribute("data-max-items"))
            : -1;

        new Choices(select, {
            searchEnabled: true,
            itemSelectText: "",
            placeholder: true,
            placeholderValue: select.getAttribute("placeholder"),
            addItems: true,
            delimiter: ", ",
            maxItemCount: maxItems,
            removeItemButton: true,
            duplicateItemsAllowed: false,
        });
    });
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
                     <input type="text" name="gallery_alt_texts[]" value="gallery" class="field" placeholder="Enter alt text" required>
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
            const isValid = validateForm(form, editors);

            if (!isValid) {
                event.preventDefault();
            }
        });
    }
});

// Tiny Editor
function initializeEditors(form) {
    const editors = [];
    const editorElements = form.querySelectorAll(".editor");

    editorElements.forEach((editorElement) => {
        tinymce.init({
            target: editorElement,
            plugins:
                "advlist autolink link image lists charmap print preview anchor \
                      searchreplace visualblocks code fullscreen insertdatetime media table \
                      paste code wordcount emoticons hr pagebreak save directionality \
                      template toc textpattern imagetools visualchars nonbreaking codesample",
            toolbar:
                "undo redo | formatselect | bold italic underline strikethrough forecolor backcolor | \
                      alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | \
                      link image media | preview fullscreen | emoticons codesample blockquote hr pagebreak | \
                      removeformat",
            height: 300, // Adjust the height for the editor
            menubar: true, // Show the menubar
            branding: false, // Remove TinyMCE branding
            image_advtab: true, // Advanced image options
            media_live_embeds: true, // Auto-embed media
            paste_data_images: true, // Allow pasting images
            automatic_uploads: true, // Auto-upload images while editing
            file_picker_types: "image media", // Enable file picker for image/media
            content_style:
                "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }",
            setup: function (editor) {
                editor.on("init", function () {
                    editors.push(editor);
                });
            },
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

function previewImage(selectElement, imgElementId) {
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    var imgUrl = selectedOption.getAttribute("data-image");

    // Update image src and href
    var imgElement = document.getElementById(imgElementId);
    imgElement.src = imgUrl;
    imgElement.parentElement.href = imgUrl;
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

    fileInput.addEventListener("change", function (event) {
        const file = event.target.files[0];

        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();

            reader.onload = function (e) {
                uploadPreview.src = e.target.result;
                uploadImgBox.querySelector(".mask").href = e.target.result;
            };

            reader.readAsDataURL(file);

            uploadBox.classList.remove("show");
            uploadImgBox.classList.add("show");
            errorMessage.classList.add("d-none");
        } else {
            errorMessage.classList.remove("d-none");
            fileInput.value = "";
        }
    });

    deleteBtn?.addEventListener("click", function () {
        fileInput.value = "";
        uploadBox.classList.add("show");
        uploadImgBox.classList.remove("show");
    });
}

document.addEventListener("DOMContentLoaded", function () {
    let itemCount = 0;

    function updateDeleteButtonState(container) {
        const items = container.querySelectorAll("[data-repeater-item]");
        items.forEach((item, index) => {
            const deleteBtn = item.querySelector("[data-repeater-remove]");
            if (index === 0) {
                deleteBtn.disabled = true;
            } else {
                deleteBtn.disabled = false;
            }
        });
    }

    function updateUploadBox(newItem) {
        const uploads = newItem.querySelectorAll("[data-upload]");

        uploads.forEach((upload) => {
            itemCount++;
            const uniqueId = `upload-${itemCount}`;
            const uploadImgBox = upload.querySelector("[data-upload-img]");
            const uploadBox = upload.querySelector("[data-upload-box]");
            const uploadMask = upload.querySelector(".mask");
            const imagePreview = upload.querySelector("[data-upload-preview]");
            const fileInput = upload.querySelector("[data-file-input]");
            let prevId = fileInput.id;
            const label = newItem.querySelector(`label[for="${prevId}"]`);

            fileInput.id = uniqueId;
            fileInput.value = "";
            uploadMask.href = "javascript:void(0)";
            imagePreview.src = imagePreview.dataset.placeholder;
            uploadBox.classList.add("show");
            uploadImgBox.classList.remove("show");
            if (label) {
                label.setAttribute("for", uniqueId);
            }
            initializeUploadComponent(upload);
        });
    }

    function addItem(container) {
        const list = container.querySelector("[data-repeater-list]");
        const firstItem = list.querySelector("[data-repeater-item]");
        const newItem = firstItem.cloneNode(true);

        const inputs = newItem.querySelectorAll("input, textarea");
        inputs.forEach((input) => {
            input.value = "";
        });

        updateUploadBox(newItem);
        list.appendChild(newItem);
        updateDeleteButtonState(container);
    }

    function removeItem(button) {
        const container = button.closest("[data-repeater]");
        const item = button.closest("[data-repeater-item]");
        item.remove();
        updateDeleteButtonState(container);
    }

    document.querySelectorAll("[data-repeater]").forEach((container) => {
        const addBtn = container.querySelector("[data-repeater-create]");
        addBtn.addEventListener("click", function () {
            addItem(container);
        });

        container.addEventListener("click", function (e) {
            if (e.target.closest("[data-repeater-remove]")) {
                removeItem(e.target.closest("[data-repeater-remove]"));
            }
        });

        updateDeleteButtonState(container);
        const initialUploadComponents =
            container.querySelectorAll("[data-upload]");
        initialUploadComponents.forEach((uploadComponent) => {
            initializeUploadComponent(uploadComponent);
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const uploadComponents = document.querySelectorAll("[data-upload]");

    uploadComponents.forEach((uploadComponent) => {
        initializeUploadComponent(uploadComponent);
    });
});
