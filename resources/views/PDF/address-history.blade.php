<div class="mt-3">
    <h3 class="title">Contract - Address History For The Past 7 Years</h3>
    @foreach($contractData->internalAgentContract->addresses as $key=>$address)
        <table class="w-100 line-h30">
            <tr>
                <strong style="border-bottom: 2px solid black">Address {{$key+1}}:</strong>
            </tr>
            <tr class="w-100">
                <td style="width: 33.3%;">
                    <strong>Home Address: &nbsp;</strong>
                    {{$contractData->internalAgentContract->address}}
                </td>

                <td style="width: 33.3%;">
                    <strong>City: &nbsp;</strong>
                    {{$contractData->internalAgentContract->city}}
                </td>

                <td style="width: 33.3%;">
                    <strong>State: &nbsp;</strong>
                    {{$contractData->internalAgentContract->state}}
                </td>
            </tr>

            <tr class="w-100">
                <td style="width: 33.3%;">
                    <strong>Zip Code: &nbsp;</strong>
                    {{$contractData->internalAgentContract->zip}}
                </td>

                <td style="width: 33.3%;">
                    <strong>Move-In Date: &nbsp;</strong>
                    {{$contractData->internalAgentContract->move_in_date}}
                </td>

                <td style="width: 33.3%;">
                    <strong>Move-Out Date: &nbsp;</strong>
                    {{$contractData->internalAgentContract->move_out_date}}
                </td>
            </tr>
        </table>
        @if(!$loop->last)
            <p class="section-border mt-3 mb-30"></p>
        @endif
    @endforeach
</div>
