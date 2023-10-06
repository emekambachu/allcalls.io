<div class="mt-3">
    <h3 class="title">Contract - Accompanying Documents</h3>

    <table class="w-100" style="margin-bottom: 15px">
        <tr>
            <td style="width: 10%;" class="text-end"></td>
            <td style="width: 80%;" class="text-end">
                <p style="text-align: center">
                    By signing below, l hereby authorize the Company to initiate credit entries and, if necessary,
                    adjustments for credit entries in error to the checking and/or savings account indicated on this
                    form. This authority is to remain in full effect until the Company has received written notice from
                    me for its termination. I understand that this authorization is subject to the terms of any agent or
                    representative contract, commission agreement, or loan agreement that I may have now, or in the
                    future, with the Company.
                </p>
            </td>
            <td style="width: 10%;" class="text-end"></td>
        </tr>
    </table>

    <table class="w-100" style="margin-bottom: 30px">
        <tr>
            <td style="width: 60%;" class="text-15">
                <p style="text-align: center">

                    <span>
                <strong>Signature: &nbsp;</strong>
                    <img src="{{asset(asset($contractData->internalAgentContract->getQuestionSign->sign_url))}}" alt="" width="250" height="100">
                </span>
                </p>
            </td>
            <td style="width: 30%;" class="text-end">
                <p style="text-align: center">
                    <span>
                        <strong>
                            Date: &nbsp;
                        </strong>
                        {{\Carbon\Carbon::parse($contractData->internalAgentContract->getContractSign->created_at)->format('m/d/Y')}}
                    </span>
                </p>
            </td>
            <td style="width: 10%;" class="text-end"></td>
        </tr>
    </table>
</div>
