<x-mail::message>
    Dear {{ $user->first_name }},
    notify you about your recent deposit fund.
    @component('mail::table', ['table-header-align' => 'center', 'table-align' => 'right'])

        | Description | Amount |
        | --- | --- |
        | Sub Total | ${{ $subTotal }} |
        | Credit Card Processing Fee | ${{ $processingFee }} |
        | --- | --- |
        | Total | ${{ $total }} |

    @endcomponent
</x-mail::message>
