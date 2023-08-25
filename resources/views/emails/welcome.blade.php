{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome AllCall</title>

</head>

<body>



    <div class="" style="display: flex; justify-content: center;">

        <img src="{{ asset('img/new-logo-black.png') }}" width="20%" alt="">
    </div>
    <p>

        Dear {{ $user->first_name }},
    </p>
    <p>


        We're thrilled to have you onboard! Welcome to **AllCalls.io**.
    </p>
    <p>


        **Here's a quick rundown of what awaits you:**
    </p>
    <ol>
        <li>

            **Competitive Bidding**: Dive into a competitive yet rewarding bidding system where you get to decide how
            much a
            potential client's call is worth to you. Outshine your competitors and get connected instantly!
        </li>
        <li>

            **Tailored Preferences**: With AllCalls.io, you're in control. Choose the types of calls that align with
            your
            expertise and passion. No more sifting through irrelevant leads.
        </li>
        <li>

            **Seamless Availability Management**: With our user-friendly mobile app, toggle your availability with
            just a
            tap. Stay connected, no matter where you are.
        </li>
        <li>






            **Transparent Financial Management**: Top up your balance with ease and monitor your expenditures through
            our
            secure web app.

        </li>
    </ol>
    <p>

        **Getting Started:**
    </p>
    <ol>
        <li>


            Log into the web app and complete your profile.
        </li>
        <li>


            Add funds to your balance.

        </li>
        <li>


            Select the type of calls you’re interested in.
        </li>
        <li>


            Download our mobile app and manage your availability on-the-go.
        </li>
    </ol>
    <p>

        Our dedicated support team is always on hand to assist you, should you have any questions or need help along the
        way. Remember, at AllCalls.io, we're all about fostering connections that matter.
    </p>

    <p>

        Thank you for choosing us to be a part of your journey. Let's redefine insurance call experiences together!
    </p>
    <p>

        Warm regards,
    </p>
    <p>

        The AllCalls.io Team
    </p>
    <p>

        ---
    </p>

    <p>

        P.S. If you have any questions or need assistance, don't hesitate to reach out to our support team at
        [support@allcalls.io](mailto:support@allcalls.io) or visit our [Help Center](http://help.allcalls.io). We're
        here to
        help!
    </p>
</body>

</html> --}}

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>AllCalls.io</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>

<body
    style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: #718096; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;">

    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation"
        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
        <tr>
            <td align="center"
                style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation"
                    style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 0; padding: 0; width: 100%;">
                    <tr>
                        <td class="header"
                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; padding: 25px 0; text-align: center;">
                            <a href="{{ route('home') }}"
                                style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none; display: inline-block;">
                                <img src="{{ asset('img/new-logo-black.png') }}" width="20%" alt="">
                            </a>
                        </td>
                    </tr>

                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0"
                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%; border: hidden !important;">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
                                role="presentation"
                                style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 0 auto; padding: 0; width: 570px;">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell"
                                        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; max-width: 100vw; padding: 32px;">
                                        <p
                                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                            Dear {{ $user->first_name }},</p>
                                        <pre
                                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;"><code>We're thrilled to have you onboard! Welcome to **AllCalls.io**.

**Here's a quick rundown of what awaits you:**

1. **Competitive Bidding**: Dive into a competitive yet rewarding bidding system where you get to decide how much a
potential client's call is worth to you. Outshine your competitors and get connected instantly!

2. **Tailored Preferences**: With AllCalls.io, you're in control. Choose the types of calls that align with your
expertise and passion. No more sifting through irrelevant leads.

3. **Seamless Availability Management**: With our user-friendly mobile app, toggle your availability with just a
tap. Stay connected, no matter where you are.

4. **Transparent Financial Management**: Top up your balance with ease and monitor your expenditures through our
secure web app.

**Getting Started:**

1. Log into the web app and complete your profile.

2. Add funds to your balance.

3. Select the type of calls you’re interested in.

4. Download our mobile app and manage your availability on-the-go.

Our dedicated support team is always on hand to assist you, should you have any questions or need help along the
way. Remember, at AllCalls.io, we're all about fostering connections that matter.

Thank you for choosing us to be a part of your journey. Let's redefine insurance call experiences together!

Warm regards,

The AllCalls.io Team

---

P.S. If you have any questions or need assistance, don't hesitate to reach out to our support team at
[support@allcalls.io](mailto:support@allcalls.io) or visit our [Help Center](http://help.allcalls.io). We're here to
help!
</code></pre>



                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td
                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0"
                                role="presentation"
                                style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                                <tr>
                                    <td class="content-cell" align="center"
                                        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; max-width: 100vw; padding: 32px;">
                                        <p
                                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; line-height: 1.5em; margin-top: 0; color: #b0adc5; font-size: 12px; text-align: center;">
                                            © 2023 AllCalls.io. All rights reserved.</p>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
