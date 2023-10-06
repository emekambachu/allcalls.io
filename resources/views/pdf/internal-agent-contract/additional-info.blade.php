<div class="mt-3">
    <h3 class="title">Contract - Additional Information</h3>
    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 30%;">
                 <span class="basic-info-element">
                <strong>Resident Country: &nbsp;</strong>
                {{$contractData->internalAgentContract->additionalInfo->resident_country}}
                 </span>
            </td>

            <td style="width: 25%;">
                Do you own your home?
            </td>

            <td style="width: 25%;">
                <input type="radio"
                       @if($contractData->internalAgentContract->additionalInfo->resident_your_home == 'YES') checked
                       @endif class="radio-option">
                <span class="radio-values">YES</span>
                <input type="radio"
                       @if($contractData->internalAgentContract->additionalInfo->resident_your_home == 'NO') checked
                       @endif class="radio-option">
                <span class="radio-values">NO</span>
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                 <span class="basic-info-element">
                <strong>City Of Birth: &nbsp;</strong>
                {{$contractData->internalAgentContract->additionalInfo->resident_city}}
                 </span>
            </td>

            <td style="width: 33.3%;">
                 <span class="basic-info-element">
                <strong>State Of Birth: &nbsp;</strong>
                {{getStateName($contractData->internalAgentContract->additionalInfo->resident_state)}}
                 </span>
            </td>

            <td style="width: 33.3%;">
                 <span class="basic-info-element">
                <strong>Maiden Name: &nbsp;</strong>
                {{$contractData->internalAgentContract->additionalInfo->resident_maiden_name}}
                 </span>
            </td>
        </tr>
    </table>
</div>
