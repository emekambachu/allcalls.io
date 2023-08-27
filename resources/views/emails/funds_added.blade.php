<x-mail::message>
    # Funds Deposit Confirmation

    **Dear {{ $user->first_name }},**

    We are pleased to confirm that your recent deposit has been successfully processed. Below are the details of your transaction:

    ## Date of Transaction: {{ now()->format('F j, Y, g:i a') }}
    
    ## Account Information
    **Name:** {{ $user->first_name }} {{ $user->last_name }}  
    **Email:** {{ $user->email }}  
    **Contact Number:** {{ $user->contact_number }}

    ## Deposit Details

    @component('mail::table')
    | Description                   | Amount         |
    |:------------------------------|---------------:|
    | Funds Added                   | ${{ number_format($subTotal, 2) }}  |
    | Processing Fee                | ${{ number_format($processingFee, 2) }} |
    | ----------------------------- | ---------------|
    | **Total Amount Deposited**    | **${{ number_format($total, 2) }}** |
    @endcomponent

    Your account balance is now **${{ number_format($user->balance, 2) }}**.

    If you have any questions or concerns, feel free to reach out to our support team.

    **Best regards,**  
    AllCalls.io

</x-mail::message>
