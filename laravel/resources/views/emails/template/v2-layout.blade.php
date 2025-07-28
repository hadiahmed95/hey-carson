<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ !empty($pageTitle) ? $pageTitle : 'Carson Dash' }}</title>

    <style media="screen">

        body {
            font-family: Arial, 'helvetica light', helvetica, sans-serif;
            margin: 0px;
            font-size: 14px;
            color: #000;
            background: #eee;
            padding-top: 20px;
            width: 100% !important;
            line-height: 1.3;
        }

        table {
            border-spacing: 0;
        }

        table td {
            border-collapse: collapse;
        }

        table#mail-header {
            margin-bottom: -10px;
        }

        .header-td {
            text-align: center;
            background-color: #FFF;
            padding-top: 55px;
            padding-bottom: 30px;
            padding-right: 30px;
            padding-left: 30px;
        }

        .header-anchor {
            display: block;
        }

        .header-image {
            width: auto;
            height: 44px;
        }

        .footer-table {
            position: relative;
            padding: 10px 55px;
        }

        .content-table {
            background: #fff;
            padding-top: 0;
            padding-bottom: 30px;
            padding-right: 55px;
            padding-left: 55px;
        }

        .content-td {
            text-align: left;
        }

        .footer-p {
            font-size: 11px;
            color: #9e9e9e;
        }

        .p-emphasis {
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 17px;
        }

        .p-emphasis-inline {
            font-size: 15px;
            font-weight: normal;
            color: #a2a2a2;
        }

        .p-padded-left-10 {
            padding-left: 10px;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .custom-button {
            text-decoration: none;
            background: #1f1f1f;
            border: 1px solid #1f1f1f;
            font-size: 18px;
            line-height: 21px;
            text-align: center;
            color: white !important;
            padding: 12px 17px;
            width: 50%;
            border-radius: 7px;
            margin-top: 31px;
            margin-bottom: 65px;
            cursor: pointer;
            word-wrap: break-word;
        }

        .custom-button:hover {
            background-color: #5223f5;
        }

        .p-disclaimer-1 {
            font-size: 13px;
            color: grey;
            font-style: italics;
            padding-top: 15px;
            text-align: justify;
        }

        .p-disclaimer-2 {
            font-size: 12px;
            font-weight: 700;
            font-style: italics;
            padding-top: 15px;
            text-align: center;
        }

        div.partner-message {

        }
        div.partner-message p {
            margin: 0;
            line-height: 20px;
        }

        .email-link {
            font-weight: bold;
            color: #55a8fd;
        }

        .text-justify {

        }

        .text-center {
            text-align: center;
        }

        .text-large {
            font-size: 120%;
        }

        .text-small {
            font-size: 80%;
        }

        .custom-button {
            background-color: #1f1f1f; /* Dark gray background */
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-family: Arial, sans-serif;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .custom-button:hover {
            background-color: #333333; /* Slightly lighter dark gray for hover effect */
        }

        @media screen and (max-width: 599px) {
            body[yahoo] table {
                width: 100% !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            body[yahoo] table#mail-header {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            body[yahoo] table img {
                width: 50%;
                height: auto;
            }

            body {
              padding-top: 0px !important;
            }

            .padding-xs-0 {
                padding-top: 0px !important;
            }

            #mail-header {
                width: 100% !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .custom-button {
                font-size: 9px;
            }
        }
    </style>

</head>

<body yahoo="fix" class="padding-xs-0">
    <table width="580" cellpadding="0" cellspacing="0" border="0" align="center" id="mail-header" class="heyCarsonEmailDiv">
        <tr>
            <td class="header-td">
                <a href="#" target="_blank">
                    <img style='width: auto; height: unset' src="{{ url('api/assets/email-resources/logos/shopexperts_banner.jpg') }}" alt="" class="header-image" />
                </a>
            </td>
        </tr>
    </table>
    <table width="580" cellpadding="0" cellspacing="0" border="0" align="center" class="content-table heyCarsonEmailDiv">
        <tr class="content-tr">
            <td class="content-td">
                @yield('content')
                @if (empty($modifiedSignature))
                    <p>
                        Warm Regards, <br>
                        The Shopexperts Team
                    </p>
                    <br/>
                    <hr style="border-top: 1px solid #bbb;">
                @endif
            </td>
        </tr>
        <tr>
            <td>
                @yield('disclaimer')
            </td>
        <tr>
    </table>
    <table width="580" cellpadding="0" cellspacing="0" border="0" align="center" class="footer-table heyCarsonEmailDiv">
        <tr>
            <td>
                <p class="footer-p">&copy;{{ date('Y') }} shopexperts. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>

</html>
