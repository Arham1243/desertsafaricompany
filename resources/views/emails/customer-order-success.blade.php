<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0;" />
    <meta name="format-detection" content="telephone=no" />
    <style>
        @font-face {
            font-family: "UAEDirham";
            src: url("{{ asset('admin/assets/fonts/UAE-dirham/aed-Regular.otf') }}");
        }

        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            height: 100% !important;
            background-color: #f0f0f0;
            color: #000;
        }

        body :is(.dirham.dirham) {
            font-family: "UAEDirham" !important;
            font-weight: 400 !important;
        }

        body,
        table,
        td,
        div,
        p,
        a {
            -webkit-font-smoothing: antialiased;
            text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            line-height: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            border-collapse: collapse !important;
        }

        img {
            border: 0;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            object-fit: contain;
        }

        #outlook a {
            padding: 0;
        }

        .ReadMsgBody,
        .ExternalClass {
            width: 100%;
        }

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }

        @media all and (min-width:560px) {
            body {
                margin-top: 30px;
            }

            .container {
                border-radius: 8px;
                -webkit-border-radius: 8px;
                -moz-border-radius: 8px;
            }
        }

        a,
        a:hover {
            color: #127db3;
        }

        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.info-table th,
        table.info-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table.info-table th {
            background-color: #f2f2f2;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 4px;
            background-color: #1c4d99;
            text-decoration: none;
            color: #fff !important;
            font-family: sans-serif;
            font-size: 17px;
            line-height: 120%;
            display: inline-block;
        }
    </style>
    <title>Your Order is Confirmed</title>
</head>

<body style="background-color:#F0F0F0; color:#000;">
    <table width="100%" cellpadding="0" cellspacing="0" align="center">
        <tr>
            <td align="center">
                <table width="600" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" class="container"
                    style="max-width:600px; font-family:sans-serif; border-radius:8px;">

                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding-top:25px; padding-left:6.25%; padding-right:6.25%;">
                            <img src="{{ $data['logo'] }}" width="200" height="58" alt="{{ env('APP_NAME') }}"
                                title="{{ env('APP_NAME') }}" />
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td align="center" style="padding-top:25px; padding-left:6.25%; padding-right:6.25%;">
                            <hr color="#E0E0E0" width="100%" size="1" noshade />
                        </td>
                    </tr>

                    <!-- Greeting & Message -->
                    <tr>
                        <td
                            style="padding-top:25px; padding-left:6.25%; padding-right:6.25%; font-size:17px; line-height:160%; color:#000;">
                            {!! replaceTemplateVariables($settings->get('customer_order_confirmed_greeting'), [
                                'name' => $data['customer_name'] ?? 'User',
                            ]) !!},<br>

                            Your order <strong>#{{ $data['order_id'] }}</strong> has been
                            <strong>confirmed</strong>.<br>

                            @if ($data['advance_amount'] > 0)
                                <strong>{{ formatPrice($data['advance_amount']) }}</strong> has been paid in advance.
                                The remaining amount will be collected on-site or at pickup.
                            @else
                                The order has been <strong>fully paid</strong>.
                            @endif
                        </td>
                    </tr>

                    <!-- Customer Info Table -->
                    <tr>
                        <td style="padding-top:20px; padding-left:6.25%; padding-right:6.25%;">
                            <table class="info-table">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Customer Email</th>
                                        <th>Customer Phone</th>
                                        <th>Payment Type</th>
                                        @if ($data['advance_amount'] > 0)
                                            <th>Advance Paid</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $data['customer_name'] }}</td>
                                        <td>{{ $data['customer_email'] }}</td>
                                        <td>+{{ $data['customer_phone'] }}</td>
                                        <td>{{ ucfirst($data['payment_type']) }}</td>
                                        @if ($data['advance_amount'] > 0)
                                            <td>{{ formatPrice($data['advance_amount']) }}</td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>


                    <!-- Tours Table -->
                    <tr>
                        <td style="padding-top:25px; padding-left:6.25%; padding-right:6.25%;">
                            <table class="info-table">
                                <thead>
                                    <tr>
                                        <th>Tour</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['tours'] as $tourId => $tour)
                                        <tr>
                                            <td>{{ getTourByID($tourId)->title ?? '' }}</td>
                                            <td>{{ $tour['total_price'] ? formatPrice($tour['total_price']) : '' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td style="font-weight:bold;">Grand Total</td>
                                        <td style="font-weight:bold;">
                                            {{ $data['total'] ? formatPrice($data['total']) : '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td align="center" style="padding-top:25px; padding-bottom:25px;">
                            <a href="{{ $data['order_link'] }}" target="_blank" class="btn">View Your Order</a>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center"
                            style="padding-left:6.25%; padding-right:6.25%; padding-bottom:25px; font-size:14px; color:#000;">
                            {!! replaceTemplateVariables($settings->get('customer_order_confirmed_footer'), [
                                'year' => now()->year,
                            ]) !!}
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
