<x-mail::message>
    Dear {{ $user->first_name }},

    Notify you about your recent deposit fund.

    <table>
        <tr>
            <td>Sub Total:</td>
            <td>${{$subTotal}}</td>
        </tr>

        <tr>
            <td>Credit Card Processing Fee:</td>
            <td>${{$processingFee}}</td>
        </tr>
        ---
        <tr>
            <td>Total</td>
            <td>${{$total}}</td>
        </tr>
    </table>

    ---

    P.S. If you have any questions or need assistance, don't hesitate to reach out to our support team at
    [support@allcalls.io](mailto:support@allcalls.io) or visit our [Help Center](http://help.allcalls.io). We're here to
    help!
</x-mail::message>
