@extends('frontend.layouts.main')
@section('content')
    @php
        $seo = (object) [
            'seo_title' => 'Terms & Conditions',
            'is_seo_index' => true,
            'seo_description' => null,
            'canonical' => null,
            'fb_title' => null,
            'fb_description' => null,
            'fb_featured_image' => null,
            'tw_title' => null,
            'tw_description' => null,
            'tw_featured_image' => null,
            'schema' => null,
        ];
    @endphp
    <div class="text-document my-5">
        <div class="container">
            <h3 class="subHeading">Terms and Conditions</h3>
            <p><strong>Desert Safari Company L.L.C</strong> maintains <a href="{{ url('/') }}"
                    target="_blank">www.desertsafaricompany.com</a>. United Arab Emirates is our country of domicile, and all
                governing laws are the local laws. All disputes arising in connection shall be heard only by a court of
                competent jurisdiction in U.A.E.</p>

            <p>Visa or MasterCard debit and credit cards in AED will be accepted for payment. We do not trade with or
                provide services to OFAC and sanctioned countries. Users under the age of 18 shall not register or transact
                on this website. Cardholders must retain a copy of transaction records and policies. Users are responsible
                for maintaining the confidentiality of their account.</p>

            <p>By booking a tour through our site, you agree to these terms. Please read carefully to ensure you understand
                the conditions of your selected tour.</p>

            <h4>1. Pricing</h4>
            <p>Prices are quoted per person and do not include tips for guides or drivers unless specified. Rates may change
                without notice due to unexpected events, such as increases in petrol, hotel rates, or transport costs.</p>

            <h4>2. Methods of Payment</h4>
            <ul>
                <li>Cash payment on pick-up.</li>
                <li>Bank transfer or deposit.</li>
            </ul>

            <h4>3. Confirmation</h4>
            <p>After booking, our tour facilitators will send a confirmation email. Ensure all information provided during
                booking is accurate.</p>

            <h4>4. Cancellation & No Show Policy</h4>
            <h5>4.1 Cancellation</h5>
            <ul>
                <li>Cancelled/revised 48 hrs before tour: no charges.</li>
                <li>Cancelled/amended within 24–48 hrs: 50% charge.</li>
                <li>Cancelled/amended within 24 hrs: 100% charge.</li>
            </ul>

            <h5>4.2 No Show</h5>
            <p>No refunds will be given for no-shows, unused safari/tours/dhow trips, or rescheduled confirmed tours.</p>

            <h4>5. Cancellation Procedures</h4>
            <p>Notify Desert Safari Company of cancellations via email or phone. Confirmation of cancellation and charges
                will be communicated. We are not responsible for cancellations not received or confirmed by us.</p>

            <h4>6. Itinerary Amendments</h4>
            <p>Routings and services are subject to change due to local/weather conditions or logistics. Alternatives of
                similar quality may be offered. Minor revisions can be executed at any time without refund. No compensation
                for major natural events like floods or earthquakes.</p>

            <h4>7. Travel Insurance</h4>
            <p>Desert Safari Company is not responsible for any injuries, illnesses, damage, or loss of personal items.
                Travelers are recommended to have travel insurance.</p>

            <h4>8. Travel Documents</h4>
            <p>Visitors must carry valid identification or travel documents. No refunds for missing or lost documents. Check
                your country’s entry requirements and visa/health regulations.</p>

            <h4>9. Website Usage Restrictions</h4>
            <p>All content on this site, including logos, images, and tour information, is the property of Desert Safari
                Company. You agree not to use the content for non-personal, commercial, or illegal purposes.</p>
        </div>
    </div>
@endsection
