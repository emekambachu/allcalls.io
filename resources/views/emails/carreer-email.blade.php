<x-mail::message>
Welcome to **AllCalls.io**.<br>

Dear Recruiting Team,

I hope this email finds you well. I am writing to inform you that a new candidate has submitted their application for the [AllCalls.io](https://allcalls.io).
Below are the details provided by the candidate:<br>

@component('mail::table')
|                    |                                |
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
