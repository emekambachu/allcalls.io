<div class="mt-3">
    <h3 class="title">Contract - Additional Information</h3>
    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 30%;">
                <strong>Resident Country: &nbsp;</strong>
                {{$contractData->internalAgentContract->additionalInfo->resident_country}}
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
                <strong>City Of Birth: &nbsp;</strong>
                {{$contractData->internalAgentContract->additionalInfo->resident_city}}
            </td>

            <td style="width: 33.3%;">
                <strong>State Of Birth: &nbsp;</strong>
                {{$contractData->internalAgentContract->additionalInfo->resident_state}}
            </td>

            <td style="width: 33.3%;">
                <strong>Maiden Name: &nbsp;</strong>
                {{$contractData->internalAgentContract->additionalInfo->resident_maiden_name}}
            </td>
        </tr>
    </table>
</div>
