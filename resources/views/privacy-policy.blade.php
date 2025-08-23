@extends('frontend.layouts.main')
@section('content')
    @php
        $seo = (object) [
            'seo_title' => 'Privacy Policy',
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
            <h3 class="subHeading">Privacy Policy</h3>

            <p>This Privacy Policy is for the site <a href="http://desertsafaricompany.com/"
                    target="_blank">www.desertsafaricompany.com</a> and is accepted by Desert Safari Company L.L.C. It
                signifies the protection of clients who use this site.</p>

            <p>The policy outlines customer protection, the obligations of users, and how the website manages and secures
                user data. All credit/debit card information and personally identifiable information will NOT be stored,
                sold, shared, rented, or leased to any third parties.</p>

            <p>Desert Safari Company takes appropriate steps to ensure data privacy and security, including hardware and
                software measures. However, absolute security of online information cannot be guaranteed.</p>

            <h4>Payment Confirmation</h4>
            <p>Once payment is made, a confirmation notice will be sent via email within 24 hours.</p>

            <h4>Refund Policy</h4>
            <p>Refunds will be processed through the original payment method. Allow up to 40 days for completion.</p>

            <h4>The Website</h4>
            <p>The site and its owners ensure proper protection of user data throughout their experience. The website
                complies with UAE laws regarding customer protection.</p>

            <h4>Use of Cookies</h4>
            <p>The site uses cookies to improve user experience. On first visit, users may authorize or prevent cookies.
                Cookies store information about user interactions, allowing a customized experience.</p>
            <p>Users can disable cookies via their browser settings. Google Analytics may track user actions without storing
                personal information. Some external vendors may also store cookies for referral tracking; no personal data
                is stored.</p>

            <h4>Contact & Communication</h4>
            <p>Clients share personal information at their own risk. Personal data is kept private and secure until no
                longer needed. The website may use data to provide information about products/services and respond to
                queries. Email newsletters are sent only with user consent.</p>

            <h4>Email Newsletter</h4>
            <p>Subscribers may receive emails about products/services. All subscriptions comply with UAE Spam Laws, and
                personal data is kept secure. Subscriber actions (open, clicks, etc.) may be tracked to improve future
                campaigns. Unsubscription is always possible through an automated system.</p>

            <h4>External Content & Links</h4>
            <p>The website may contain content or links from third parties. Desert Safari Company does not guarantee
                accuracy and is not responsible for external sites. Clicking external links is at the user’s own risk.</p>

            <h4>Adverts and Sponsored Links</h4>
            <p>Sponsored links may use cookies to track referrals. Users click on sponsored content at their own risk.
                Desert Safari Company is not responsible for any consequences of visiting external advertiser sites.</p>

            <h4>Social Media Platforms</h4>
            <p>Interactions on social media platforms are subject to their terms and policies. Users are responsible for
                their personal information. Social sharing buttons allow users to share content; users must be aware these
                may track activity independently through their social accounts.</p>

            <h4>Shortened Links in Social Media</h4>
            <p>Desert Safari Company may share shortened URLs on social media. Users should exercise caution before
                clicking. The company is not responsible for damages caused by visiting shortened links.</p>
        </div>
    </div>
@endsection
