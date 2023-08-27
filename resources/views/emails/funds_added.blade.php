<x-mail::message>
    Dear {{ $user->first_name }},

    Notify you about your recent deposit fund.

    | Sub Total: | ${{$subTotal}} |
    | Credit Card Processing Fee: | ${{$processingFee}} |
    ---
    | Total | ${{$total}} |

    ---

    P.S. If you have any questions or need assistance, don't hesitate to reach out to our support team at
    [support@allcalls.io](mailto:support@allcalls.io) or visit our [Help Center](http://help.allcalls.io). We're here to
    help!
</x-mail::message>
