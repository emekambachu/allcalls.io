<div class="mt-3 signature-authorization">
    <h3 class="title">Contracting - Signature Authorization</h3>
    <p style="font-weight: bold">
        PLEASE READ THIS AUTHORIZATION, SIGN IN THE BOX BELOW AND SUBMIT THIS FORM BY FOLLOWING THE INSTRUCTIONS
        PROVIDED ON THE COVER PAGE.
    </p>

    <p class="line-h30">
        I <span
            style="border-bottom: 2px solid black">{{$contractData->internalAgentContract->first_name}} {{$contractData->internalAgentContract->last_name}}</span>,
        hereby authorize AllCalls.io, LLC and its general agency customers (the "Authorized Parties") to affix
        or append a copy of my signature, as set forth below, to any and all required signature fields on forms
        and agreements of any insurance carrier (a "Carrier") designated by me through the SureLC software or
        through any other means, including without limitation, by e-mail or orally. The Authorized Parties shall
        be permitted to complete and submit all such forms and agreements on my behalf for the purpose of
        becoming authorized to sell Carrier insurance products. I hereby release, indemnify and hold harmless
        the Authorized Parties against any and all claims, demands, losses, damages, and causes of action,
        including expenses, costs and reasonable attorneys' fees which they may sustain or incur as a result of
        carrying out the authority granted hereunder.
    </p>

    <p>
        By my signature below, I certify that the information I have submitted to the Authorized Parties is
        correct to the best of my knowledge and acknowledge that I have read and reviewed the forms and
        agreements which the Authorized Parties have been authorized to affix my signature. I agree to indemnify
        and hold any third party harmless from and against any and all claims, demands, losses, damages, and
        causes of action, including expenses, costs and reasonable attorneys' fees which such third party may
        incur as a result of its reliance on any form or agreement bearing my signature pursuant to this
        authorization.
    </p>

    <table class="w-100" style="margin-bottom: 30px; margin-top:50px">
        <tr>
            <td style="width: 20%;" class="text-end">
                <strong>Signature: &nbsp;</strong>
                {{-- <div style="border-bottom:2px solid black"></div> --}}
            </td>

            <td style="width: 40%;" class="text-15">
            <div style="border-bottom:2px solid black; margin-top:5px !important">
                <a href="">[SIGNATURE_AUTHORIZATION]</a>
            </div>
            </td>

            <td style="width: 30%;" class="text-end">
                <p style="text-align: center">
                    <span>
                        <strong>
                            Date: &nbsp;
                        </strong>
                        <span style="border-bottom:2px solid black;">
                            {{\Carbon\Carbon::now()->format('m/d/Y')}}
                        </span>
                    </span>
                </p>
            </td>
            <td style="width: 10%;" class="text-end"></td>
        </tr>
    </table>
    {{-- <div style="width:35%; border-bottom:solid; height:120px; float: right;margin-top:30px;"> --}}
        {{-- <img  src="{{ asset($contractData->internalAgentContract->getContractSign->sign_url)}}" style="width:75%;margin-top:40px;margin-left:-8px;"> --}}
    {{-- </div> --}}
    {{-- <br>
    <div style="width:35%; height:100px; margin-right:-230px;  float: right; margin-top:130px;">
        <div><strong>Name</strong>:&nbsp; {{$contractData->internalAgentContract->first_name.' '.$contractData->internalAgentContract->last_name}}</div>
        <div><strong>Date</strong>:&nbsp; {{\Carbon\Carbon::now()->format('m/d/Y')}}  </div>
    </div> --}}
</div>
