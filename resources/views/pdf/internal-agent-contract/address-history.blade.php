<div class="mt-3">
    <h3 class="title">Contracting - Address History For The Past 7 Years</h3>
    @foreach($contractData->internalAgentContract->addresses as $key=>$address)
        <table class="w-100 line-h30">
            <tr>
                <strong style="border-bottom: 2px solid black">Address {{$key+1}}:</strong>
            </tr>
            <tr class="w-100">
                <td style="width: 33.3%;">
                   <span class="basic-info-element">
                        <strong>Home Address: &nbsp;</strong>
                    {{$contractData->internalAgentContract->address}}
                   </span>
                </td>

                <td style="width: 33.3%;">
                     <span class="basic-info-element">
                        <strong>City: &nbsp;</strong>
                        {{$contractData->internalAgentContract->city}}
                     </span>
                </td>

                <td style="width: 33.3%;">
                     <span class="basic-info-element">
                        <strong>State: &nbsp;</strong>
                        {{getStateName($contractData->internalAgentContract->state)}}
                     </span>
                </td>
            </tr>

            <tr class="w-100">
                <td style="width: 33.3%;">
                 <span class="basic-info-element">
                    <strong>Zip Code: &nbsp;</strong>
                    {{$contractData->internalAgentContract->zip}}
                 </span>
                </td>

                <td style="width: 33.3%;">
                 <span class="basic-info-element">
                    <strong>Move-In Date: &nbsp;</strong>
                    {{$contractData->internalAgentContract->move_in_date}}
                 </span>
                </td>

                <td style="width: 33.3%;">
                 <span class="basic-info-element">
                    <strong>Move-Out Date: &nbsp;</strong>
                    {{$contractData->internalAgentContract->move_out_date}}
                 </span>
                </td>
            </tr>
        </table>
        @if(!$loop->last)
            <p class="section-border mt-3 mb-30"></p>
        @endif
    @endforeach
</div>
