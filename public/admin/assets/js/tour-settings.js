const sortableTableBody = document.querySelector("[data-sortable-body]");
if (sortableTableBody) {
    const sortable = new Sortable(sortableTableBody, {
        animation: 300,
        handle: ".order-menu",
        onEnd: function (evt) {
            // console.log(
            //     "Dragged item index: ",
            //     evt.oldIndex,
            //     " -> ",
            //     evt.newIndex,
            // );
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
            'textarea[name^="itinerary_experience[stops]"][name$="[title]"]',
        ),
    ).some((input) => input.value.trim() !== "");
    if (stops.length > 0 && hasValue) {
        addStopBtn.classList.remove("d-none");
    } else {
        addStopBtn.classList.add("d-none");
    }
}

function updateOrderFields() {
    const rows = document.querySelectorAll("#itinerary-table-body tr");
    rows.forEach((row, index) => {
        const orderField = row.querySelector("input[name$='[order]']");
        if (orderField) {
            orderField.value = index + 1; // Update the order number
        }
    });
    toggleAddStopButton();
}

const itineraryTableBody = document.getElementById("itinerary-table-body");
const subStopsSection = document.getElementById("itinerary_experience_sub_stops");
const subStopsCheckbox = document.getElementById("itinerary_experience_enabled_sub_stops");

if (itineraryTableBody && subStopsSection && subStopsCheckbox) {
    subStopsCheckbox.addEventListener("change", function () {
        if (this.checked) {
            subStopsSection.classList.remove("d-none");
            populateMainStopDropdown();
        } else {
            subStopsSection.classList.add("d-none");
        }
    });

    function createRow(type) {
        let row = null;
        const order = itineraryTableBody.children.length; // Calculate current order
        if (type === "vehicle") {
            row = `
            <tr data-item-type="vehicle" draggable="true">
                <td><div class="order-menu"><i class='bx-sm bx bx-menu'></i></div>
                <input type="hidden" name="itinerary_experience[vehicles][${order}][order]" value="${order}">
                <input type="hidden" name="itinerary_experience[vehicles][${order}][type]" value="vehicle"></td>
                <td><div class="d-flex align-items-center gap-1"><i class='bx bxs-car'></i>Vehicle</div></td>
                <td><label class="title">Icon class: <a class="p-0 ps-2 nav-link" href="https://v2.boxicons.com/" target="_blank">Boxicons</a></label><input name="itinerary_experience[vehicles][${order}][icon_class]" type="text" class="field" value="bx bxs-car"></td>
                <td><label class="title">Name:</label><input name="itinerary_experience[vehicles][${order}][name]" type="text" class="field"></td>
                <td><label class="title">Time:</label><input name="itinerary_experience[vehicles][${order}][time]" type="text" class="field"></td>
                <td><button type="button" class="delete-btn ms-auto delete-btn--static"><i class='bx bxs-trash-alt'></i></button></td>
            </tr>`;
        } else if (type === "stop") {
            row = `
            <tr data-item-type="stop" draggable="true">
                <td><div class="order-menu"><i class='bx-sm bx bx-menu'></i></div>
                <input type="hidden" name="itinerary_experience[stops][${order}][order]" value="${order}">
                <input type="hidden" name="itinerary_experience[stops][${order}][type]" value="stop"></td>
                <td><div class="d-flex align-items-center gap-1"><i class="bx bx-star"></i>Stop</div></td>
                <td><label class="title">Icon class: <a class="p-0 ps-2 nav-link" href="https://v2.boxicons.com/" target="_blank">Boxicons</a></label><input name="itinerary_experience[stops][${order}][icon_class]" type="text" class="field" value="bx bx-star"></td>
                <td> <label class="title">Title:</label> <textarea name="itinerary_experience[stops][${order}][title]" rows="4" class="field"></textarea> </td>
                <td> <label class="title">Activities:</label> <textarea name="itinerary_experience[stops][${order}][activities]" rows="4" class="field"></textarea> </td>
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
            updateOrderFields();
            closeSubStopsSection();
        });
    });

    itineraryTableBody.addEventListener("click", function (e) {
        if (e.target.closest(".delete-btn")) {
            const row = e.target.closest("tr");
            row.remove();
            updateOrderFields();
            populateMainStopDropdown();
            closeSubStopsSection();
        }
    });

    function closeSubStopsSection() {
        subStopsCheckbox.checked = false;
        subStopsSection.classList.add("d-none");
    }

    itineraryTableBody.addEventListener("input", function (e) {
        if (e.target.name.includes("itinerary_experience[stops][") && e.target.name.endsWith("[title]")) {
            closeSubStopsSection();
            toggleAddStopButton();
        }
    });

    function populateMainStopDropdown() {
        const stopNames = document.querySelectorAll("textarea[name^='itinerary_experience[stops]'][name$='[title]']");
        const mainStopDropdowns = document.querySelectorAll(".main-stop-dropdown");

        // Store currently selected values for each dropdown
        const selectedValues = Array.from(mainStopDropdowns).map(dropdown => dropdown.value);

        // Add options from stop title inputs
        stopNames.forEach(stopInput => {
            const stopTitle = stopInput.value.trim();
            if (stopTitle) {
                mainStopDropdowns.forEach(dropdown => {
                    // Check if the option already exists
                    if (![...dropdown.options].some(option => option.value === stopTitle)) {
                        const option = document.createElement("option");
                        option.value = stopTitle;
                        option.textContent = stopTitle;
                        dropdown.appendChild(option);
                    }
                });
            }
        });

        // Restore previously selected values in the dropdowns
        mainStopDropdowns.forEach(dropdown => {
            const currentValue = selectedValues.find(value => value === dropdown.value);
            if (currentValue) {
                dropdown.value = currentValue;
            }
        });
    }


    updateOrderFields();
}



document.addEventListener("DOMContentLoaded", function () {
    let itemCount = 0;

    function updateDeleteButtonState(container) {
        const items = container.querySelectorAll("[data-repeater-item]");
        items.forEach((item, index) => {
            const deleteBtn = item.querySelector("[data-repeater-remove]");
            if (deleteBtn) {
                deleteBtn.disabled = index === 0;
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
            const prevId = fileInput.id;
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

    function updateCheckboxes(newItem) {
        const checkboxes = newItem.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach((checkbox) => {
            itemCount++;
            const uniqueId = `checkbox-${itemCount}`;
            const prevId = checkbox.id;
            const label = newItem.querySelector(`label[for="${prevId}"]`);

            checkbox.id = uniqueId;
            if (label) {
                label.setAttribute("for", uniqueId);
            }
        });
    }

    function addSubItem(container) {
        const list = container.querySelector("[data-sub-repeater-list]");
        const firstItem = list.querySelector("[data-sub-repeater-item]");
        const newItem = firstItem.cloneNode(true);

        const inputs = newItem.querySelectorAll("input, textarea");
        inputs.forEach((input) => {
            input.value = "";
        });

        updateUploadBox(newItem);
        updateCheckboxes(newItem);
        list.appendChild(newItem);
        updateSubDeleteButtonState(container);
        updateIndices(container);
    }

    function updateIndices(container) {
        const items = container.querySelectorAll("[data-repeater-item]");
        items.forEach((item, index) => {
            const pricingFields = ["name", "price"];

            pricingFields.forEach((field) => {
                const input = item.querySelector(
                    `input[name*='[${field}]'], select[name*='[${field}]']`
                );
                if (input) {
                    input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                }
            });

            const itemInputs = item.querySelectorAll(
                "input[name*='[items]']"
            );
            itemInputs.forEach((input) => {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            });
            const urlsInputs = item.querySelectorAll("input[name*='[urls]']");
            urlsInputs.forEach((input) => {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            });
        });
    }

    function updateSubDeleteButtonState(container) {
        const items = container.querySelectorAll("[data-sub-repeater-item]");
        items.forEach((item, index) => {
            const deleteBtn = item.querySelector("[data-sub-repeater-remove]");
            deleteBtn.disabled = index === 0;
        });
    }

    function addItem(container, afterElement = null) {
        const list = container.querySelector("[data-repeater-list]");
        const firstItem = list.querySelector("[data-repeater-item]");
        const newItem = firstItem.cloneNode(true);

        // Reset inputs
        const inputs = newItem.querySelectorAll("input, textarea");
        inputs.forEach((input) => {
            if (input.type !== "checkbox") {
                input.value = "";
            } else {
                input.checked = false;
            }
        });
        const selects = newItem.querySelectorAll("select");
        selects.forEach((select) => (select.selectedIndex = 0));

        updateUploadBox(newItem);
        updateCheckboxes(newItem);

        // ✅ If afterElement is provided → insert after it
        if (afterElement) {
            afterElement.insertAdjacentElement("afterend", newItem);
        } else {
            // Otherwise → append at the end
            list.appendChild(newItem);
        }

        updateDeleteButtonState(container);
        updateIndices(container);
        initializeSubRepeater(newItem);
        calculatePromoPrice();
    }


    function removeItem(button) {
        const container = button.closest("[data-repeater]");
        const item = button.closest("[data-repeater-item]");
        item.remove();
        updateDeleteButtonState(container);
        updateIndices(container);
    }

    function initializeSubRepeater(parentItem) {
        const subContainer = parentItem.querySelector("[data-sub-repeater]");
        if (subContainer) {
            const addBtn = subContainer.querySelector(
                "[data-sub-repeater-create]"
            );
            if(addBtn){
                addBtn?.addEventListener("click", function () {
                    addSubItem(subContainer);
                });

                subContainer.addEventListener("click", function (e) {
                    if (e.target.closest("[data-sub-repeater-remove]")) {
                        removeSubItem(
                            e.target.closest("[data-sub-repeater-remove]")
                        );
                    }
                });

                updateSubDeleteButtonState(subContainer);
            }

        }
    }

    function removeSubItem(button) {
        const container = button.closest("[data-sub-repeater]");
        const item = button.closest("[data-sub-repeater-item]");
        item.remove();
        updateSubDeleteButtonState(container);
    }
    let repeaters = document.querySelectorAll("[data-repeater]");
    if (repeaters.length > 0) {
        repeaters.forEach((container) => {
            const addBtn = container.querySelector("[data-repeater-create]");
            addBtn?.addEventListener("click", function () {
                addItem(container);
            });

            container.addEventListener("click", function (e) {
                if (e.target.closest("[data-repeater-remove]")) {
                    removeItem(e.target.closest("[data-repeater-remove]"));
                }

                 if (e.target.closest("[data-repeater-insert]")) {
                    const currentItem = e.target.closest("[data-repeater-item]");
                    addItem(container, currentItem); // insert after current item
                }
            });

            updateDeleteButtonState(container);
            const initialUploadComponents =
                container.querySelectorAll("[data-upload]");
            initialUploadComponents.forEach((uploadComponent) => {
                initializeUploadComponent(uploadComponent);
            });

            container
                .querySelectorAll("[data-repeater-item]")
                .forEach((item) => {
                    initializeSubRepeater(item);
                });
        });
    }
});
