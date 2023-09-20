<x-mail::message>
# Order Confirmation

**Dear {{ $user->first_name }},**

We are pleased to confirm that the funds added to your account have been successfully processed. Below are the details of your transaction:

## Date of Transaction: {{ now()->format('F j, Y, g:i a') }}

## Account Information
**Account #:** {{ $user->id }}

**Card Last 4:** {{ $lastFour }}

**Name:** {{ $user->first_name }} {{ $user->last_name }}

**Email:** {{ $user->email }}

**Contact Number:** {{ $user->phone }}

## Transaction Details

@component('mail::table')
| Description                   | Amount         |
|:------------------------------|---------------:|
| Funds Added                   | ${{ number_format($subTotal, 2) }}  |
| Processing Fee                | ${{ number_format($processingFee, 2) }} |
| ----------------------------- | ---------------|
| **Total Amount Charged**    | **${{ number_format($total, 2) }}** |
@endcomponent

@if(isset($bonus) && $bonus != 0)Your bonus money is **${{ number_format($bonus, 2) }}**.@endif

Your account balance is now **${{ number_format($user->balance, 2) }}**.

## Billing Address
**Address:** {{ $card->address }}  
**City:** {{ $card->city }}  
**State:** {{ $card->state }}  
**ZIP Code:** {{ $card->zip }}  
**Country:** US

If you have any questions or concerns, feel free to reach out to our support team.

**Best regards,**
AllCalls.io Support Team
</x-mail::message>
