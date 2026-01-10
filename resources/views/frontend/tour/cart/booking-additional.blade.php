<template v-if="getBookingAdditional(tour.id) && getBookingAdditional(tour.id).enabled == 1">
    <div class="cart__booking-additional mt-3">
        <h5 class="mb-3">Additional Information</h5>


        <!-- Meeting Point -->
        <template v-if="getBookingAdditional(tour.id).additional_type === 'meeting_point'">
            @{{ (() => {
    // Initialize selection as object if not exists
    if (!cart.tours[tour.id].booking_additional_selections.selection || typeof cart.tours[tour.id].booking_additional_selections.selection !== 'object') {
        cart.tours[tour.id].booking_additional_selections.selection = {
            city_id: '',
            city_name: '',
            meeting_point: ''
        };
    }
    return '';
})() }}

            <div class="form-fields mb-3">
                <label class="title text-dark">Select City <span class="text-danger">*</span></label>
                <select v-model="cart.tours[tour.id].booking_additional_selections.selection.city_id"
                    @change="(e) => {
                        const cityId = e.target.value;
                        const cityData = (getBookingAdditional(tour.id).meeting_points?.cities || []).find(c => c.city_id == cityId);
                        cart.tours[tour.id].booking_additional_selections.selection.city_name = cityData?.city_name || '';
                        cart.tours[tour.id].booking_additional_selections.selection.meeting_point = '';
                        handleBookingAdditionalChange(tour.id);
                    }"
                    class="field" required>
                    <option value="">Select City</option>
                    <option v-for="(cityData, index) in getBookingAdditional(tour.id).meeting_points?.cities || []"
                        :key="index" :value="cityData.city_id">
                        @{{ cityData.city_name }}
                    </option>
                </select>
            </div>

            <div class="form-fields mb-3" v-if="cart.tours[tour.id].booking_additional_selections.selection.city_id">
                <label class="title text-dark">Meeting Point <span class="text-danger">*</span></label>
                <select v-model="cart.tours[tour.id].booking_additional_selections.selection.meeting_point"
                    @change="handleBookingAdditionalChange(tour.id)" class="field" required>
                    <option value="">Select Meeting Point</option>
                    <template v-for="(cityData, index) in getBookingAdditional(tour.id).meeting_points?.cities || []">
                        <option
                            v-if="cityData.city_id == cart.tours[tour.id].booking_additional_selections.selection.city_id"
                            v-for="(point, pIndex) in cityData.points || []" :key="`${index}-${pIndex}`"
                            :value="point">
                            @{{ point }}
                        </option>
                    </template>
                </select>
                <small v-if="getBookingAdditional(tour.id).meeting_points?.user_remarks"
                    class="text-muted d-block mt-3">
                    @{{ getBookingAdditional(tour.id).meeting_points.user_remarks }}
                </small>
            </div>
        </template>

        <!-- Timeslot -->
        <template v-if="getBookingAdditional(tour.id).additional_type === 'timeslot'">
            <div class="form-fields mb-3">
                <label class="title text-dark">Timeslot @{{ formatTime(getBookingAdditional(tour.id).timeslots?.options?.from) }} - @{{ formatTime(getBookingAdditional(tour.id).timeslots?.options?.to) }} <span
                        class="text-danger">*</span></label>
                <input @change="handleBookingAdditionalChange(tour.id)" type="text"
                    v-model="cart.tours[tour.id].booking_additional_selections.selection"
                    :data-min="getBookingAdditional(tour.id).timeslots?.options?.from"
                    :data-max="getBookingAdditional(tour.id).timeslots?.options?.to" class="time-picker field"
                    required />


                <small v-if="getBookingAdditional(tour.id).timeslots?.user_remarks" class="text-muted d-block mt-3">
                    @{{ getBookingAdditional(tour.id).timeslots.user_remarks }}
                </small>
            </div>
        </template>

        <!-- Meeting Time -->
        <template v-if="getBookingAdditional(tour.id).additional_type === 'meeting_time'">
            <div class="form-fields mb-3">
                <label class="title text-dark">
                    Meeting Time
                    @{{ formatTime(getBookingAdditional(tour.id).meeting_time?.options?.from) }} -
                    @{{ formatTime(getBookingAdditional(tour.id).meeting_time?.options?.to) }}
                    <span class="text-danger">*</span>
                </label>

                <input type="time" onclick="this.showPicker()"
                    v-model="cart.tours[tour.id].booking_additional_selections.selection"
                    :data-min="getBookingAdditional(tour.id).meeting_time?.options?.from"
                    :data-max="getBookingAdditional(tour.id).meeting_time?.options?.to" class="time-picker field"
                    @change="handleBookingAdditionalChange(tour.id)" required />



                <small v-if="getBookingAdditional(tour.id).meeting_time?.user_remarks" class="text-muted d-block mt-3">
                    @{{ getBookingAdditional(tour.id).meeting_time.user_remarks }}
                </small>
            </div>
        </template>

        <!-- Departure Time -->
        <template v-if="getBookingAdditional(tour.id).additional_type === 'departure_time'">
            <div class="form-fields mb-3">
                <label class="title text-dark">
                    Departure Time
                    @{{ formatTime(getBookingAdditional(tour.id).departure_time?.options?.from) }} -
                    @{{ formatTime(getBookingAdditional(tour.id).departure_time?.options?.to) }}
                    <span class="text-danger">*</span>
                </label>

                <input type="time" onclick="this.showPicker()"
                    v-model="cart.tours[tour.id].booking_additional_selections.selection"
                    :data-min="getBookingAdditional(tour.id).departure_time?.options?.from"
                    :data-max="getBookingAdditional(tour.id).departure_time?.options?.to" class="time-picker field"
                    @change="handleBookingAdditionalChange(tour.id)" required />



                <small v-if="getBookingAdditional(tour.id).departure_time?.user_remarks"
                    class="text-muted d-block mt-3">
                    @{{ getBookingAdditional(tour.id).departure_time.user_remarks }}
                </small>
            </div>
        </template>

        <!-- Departure Hotel Name -->
        <template v-if="getBookingAdditional(tour.id).additional_type === 'departure_hotel_name'">
            <div class="form-fields mb-3">
                <label class="title text-dark">Departure Hotel Name <span class="text-danger">*</span></label>
                <select v-model="cart.tours[tour.id].booking_additional_selections.selection"
                    @change="handleBookingAdditionalChange(tour.id)" class="field" required>
                    <option value="">Select Departure Hotel</option>
                    <option v-for="(option, index) in getBookingAdditional(tour.id).departure_hotel_name?.options || []"
                        :key="index" :value="option">
                        @{{ option }}
                    </option>
                </select>
                <small v-if="getBookingAdditional(tour.id).departure_hotel_name?.user_remarks"
                    class="text-muted d-block mt-3">
                    @{{ getBookingAdditional(tour.id).departure_hotel_name.user_remarks }}
                </small>
            </div>
        </template>

        <!-- Pickup Location -->
        <template v-if="getBookingAdditional(tour.id).additional_type === 'pickup_location'">

            <!-- Ensure selection object exists -->
            @{{ (() => {
    if (!cart.tours[tour.id].booking_additional_selections.selection ||
        typeof cart.tours[tour.id].booking_additional_selections.selection !== 'object') {
        cart.tours[tour.id].booking_additional_selections.selection = {
            location_type: '',
            address: '',
            room_no: '',
            hotel_no: ''
        };
    }
    return '';
})() }}

            <!-- Pickup Location Dropdown -->
            <div class="form-fields mb-3">
                <label class="title text-dark">Pickup Location <span class="text-danger">*</span></label>
                <select v-model="cart.tours[tour.id].booking_additional_selections.selection.location_type"
                    @change="handleBookingAdditionalChange(tour.id)" class="field" required>
                    <option value="">Select Pickup Location</option>
                    <option v-for="(option, index) in getBookingAdditional(tour.id).pickup_location?.options || []"
                        :key="index" :value="option">
                        @{{ option }}
                    </option>
                </select>
            </div>

            <!-- Conditional Address Input -->
            <div class="form-fields mb-3"
                v-if="cart.tours[tour.id].booking_additional_selections.selection.location_type">
                <label class="title text-dark">
                    Location
                </label>
                <input type="text" v-model="cart.tours[tour.id].booking_additional_selections.selection.address"
                    @input="handleBookingAdditionalChange(tour.id)" class="field" required placeholder="e.g. (Marina Gate 2, Dubai Marina)" />
            </div>

            <template v-if="cart.tours[tour.id].booking_additional_selections.selection.location_type === 'hotel'">
                <div class="form-fields mb-3">
                    <label class="title text-dark">
                        Room No
                    </label>

                    <input type="text" v-model="cart.tours[tour.id].booking_additional_selections.selection.room_no"
                        @input="handleBookingAdditionalChange(tour.id)" class="field" required
                        placeholder="e.g. (1001)" />
                </div>

                <div class="form-fields mb-3">
                    <label class="title text-dark">
                        Hotel Contact Number
                    </label>

                    <input type="text" v-model="cart.tours[tour.id].booking_additional_selections.selection.hotel_no"
                        @input="handleBookingAdditionalChange(tour.id)" class="field" required
                        placeholder="e.g. (+971-55-2301416)" />
                </div>
            </template>

            <!-- Optional User Remarks -->
            <small v-if="getBookingAdditional(tour.id).pickup_location?.user_remarks" class="text-muted d-block mt-3">
                @{{ getBookingAdditional(tour.id).pickup_location.user_remarks }}
            </small>
        </template>

        <!-- Activities -->
        <template v-if="getBookingAdditional(tour.id).additional_type === 'activities'">
            <!-- Single Selection -->
            <template v-if="getBookingAdditional(tour.id).activities?.selection_type === 'single_selection'">
                <div class="mb-3">
                    <div class="mb-2">
                        @{{ getBookingAdditional(tour.id).activities.single_selection?.activity || 'N/A' }}
                    </div>
                    <small v-if="getBookingAdditional(tour.id).activities.single_selection?.user_remarks"
                        class="d-block mt-2" style="color: #000; font-weight: bold;">
                        @{{ getBookingAdditional(tour.id).activities.single_selection.user_remarks }}
                    </small>
                </div>
            </template>

            <!-- Multiple Selection -->
            <template v-if="getBookingAdditional(tour.id).activities?.selection_type === 'multiple_selection'">
                <div class="mb-3">
                    @{{ (() => {
    // Initialize selection as object if not exists
    if (!cart.tours[tour.id].booking_additional_selections.selection || typeof cart.tours[tour.id].booking_additional_selections.selection !== 'object') {
        cart.tours[tour.id].booking_additional_selections.selection = {};
    }
    return '';
})() }}

                    <!-- Meeting Point Dropdown -->
                    <template
                        v-if="(getBookingAdditional(tour.id).activities?.multiple_selection?.activity || []).includes('meeting_point')">

                        <!-- City Dropdown -->
                        <div class="form-fields mb-3">
                            <label class="title text-dark">Select City <span class="text-danger">*</span></label>
                            <select v-model="cart.tours[tour.id].booking_additional_selections.selection.city_id"
                                @change="(e) => {
            const cityId = e.target.value;
            const cityData = (getBookingAdditional(tour.id).activities.multiple_selection.meeting_point?.cities || [])
                .find(c => c.city_id == cityId);
            cart.tours[tour.id].booking_additional_selections.selection.city_name = cityData?.city_name || '';
            cart.tours[tour.id].booking_additional_selections.selection.meeting_point = '';
            handleBookingAdditionalChange(tour.id);
        }"
                                class="field" required>
                                <option value="">Select City</option>
                                <option
                                    v-for="(cityData, index) in getBookingAdditional(tour.id).activities.multiple_selection.meeting_point?.cities || []"
                                    :key="index" :value="cityData.city_id">
                                    @{{ cityData.city_name }}
                                </option>
                            </select>
                        </div>

                        <!-- Meeting Points Dropdown -->
                        <div class="form-fields mb-3"
                            v-if="cart.tours[tour.id].booking_additional_selections.selection.city_id">
                            <label class="title text-dark">Meeting Point <span class="text-danger">*</span></label>
                            <select v-model="cart.tours[tour.id].booking_additional_selections.selection.meeting_point"
                                @change="handleBookingAdditionalChange(tour.id)" class="field" required>
                                <option value="">Select Meeting Point</option>
                                <template
                                    v-for="(cityData, index) in getBookingAdditional(tour.id).activities.multiple_selection.meeting_point?.cities || []">
                                    <option
                                        v-if="cityData.city_id == cart.tours[tour.id].booking_additional_selections.selection.city_id"
                                        v-for="(point, pIndex) in cityData.points || []" :key="`${index}-${pIndex}`"
                                        :value="point">
                                        @{{ point }}
                                    </option>
                                </template>
                            </select>
                        </div>
                    </template>

                    <!-- Timeslot Input -->
                    <template
                        v-if="(getBookingAdditional(tour.id).activities?.multiple_selection?.activity || []).includes('timeslot')">
                        <div class="form-fields mb-3">
                            <label class="title text-dark">
                                Timeslot
                                @{{ formatTime(getBookingAdditional(tour.id).activities.multiple_selection.timeslot?.from) }} -
                                @{{ formatTime(getBookingAdditional(tour.id).activities.multiple_selection.timeslot?.to) }}
                                <span class="text-danger">*</span>
                            </label>

                            <input type="time" onclick="this.showPicker()"
                                v-model="cart.tours[tour.id].booking_additional_selections.selection.timeslot"
                                :data-min="getBookingAdditional(tour.id).activities.multiple_selection.timeslot?.from"
                                :data-max="getBookingAdditional(tour.id).activities.multiple_selection.timeslot?.to"
                                class="time-picker field" @change="handleBookingAdditionalChange(tour.id)" required />



                            <small
                                v-if="getBookingAdditional(tour.id).activities.multiple_selection.timeslot?.user_remarks"
                                class="text-muted d-block mt-3">
                                @{{ getBookingAdditional(tour.id).activities.multiple_selection.timeslot.user_remarks }}
                            </small>
                        </div>
                    </template>

                    <!-- Pickup Location Dropdown -->
                    <template
                        v-if="(getBookingAdditional(tour.id).activities?.multiple_selection?.activity || []).includes('pickup_location')">
                        <div class="form-fields mb-3">
                            <label class="title text-dark">Pickup Location <span class="text-danger">*</span></label>
                            <select
                                v-model="cart.tours[tour.id].booking_additional_selections.selection.pickup_location"
                                @change="handleBookingAdditionalChange(tour.id)" class="field" required>
                                <option value="">Select Pickup Location</option>
                                <option
                                    v-for="(option, index) in getBookingAdditional(tour.id).activities.multiple_selection.pickup_location?.options || []"
                                    :key="index" :value="option">
                                    @{{ option }}
                                </option>
                            </select>
                        </div>
                    </template>

                    <!-- Multiple Selection User Remarks -->
                    <small v-if="getBookingAdditional(tour.id).activities?.multiple_selection?.user_remarks"
                        class="d-block mt-2" style="color: #000; font-weight: bold;">
                        @{{ getBookingAdditional(tour.id).activities.multiple_selection.user_remarks }}
                    </small>
                </div>
            </template>
        </template>
    </div>
</template>
