<x-mail::message>
    Dear {{ $user->first_name }},
    <x-mail::table>
        | Description | Amount |
        | --- | --- |
        | Sub Total | ${{ $subTotal }} |
        | Card Processing Fee | ${{ $processingFee }} |
        | Total | ${{ $total }} |
    </x-mail::table>
    Notify you about your recent deposit fund.
</x-mail::message>
