<x-mail::message>
Welcome to **AllCalls.io**.<br>

You have been invited to join our company and begin your career as an Independent AllCalls Insurance Agent. <br>

@component('mail::table')
| Description                   |                               Value |
|:------------------------------|--------------------------------:    |
| First Name                    | {{ $applicantData['first_name'] }}  |
| Last Name                     | {{ $applicantData['last_name'] }}   |
| Email                         | {{ $applicantData['email'] }}       |
| Phone                         | {{ $applicantData['phone'] }}       |
| Life Insurance                |{{ $applicantData['life_insurance'] }}|
@endcomponent

Warm regards,<br>

The AllCalls.io Team<br>

---

P.S. If you have any questions or need assistance, don't hesitate to reach out to our support team at support@allcalls.io or visit our Help Center. We're here to help!
</x-mail::message>
