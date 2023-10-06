<div>
    <h3 class="title">Contracting - Basic Information</h3>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>First Name: &nbsp;</strong>
                {{$contractData->internalAgentContract->first_name}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Last Name: &nbsp;</strong>
                {{$contractData->internalAgentContract->last_name}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Middle Name: &nbsp;</strong>
                {{$contractData->internalAgentContract->middle_name}}
                </span>
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>SSN: &nbsp;</strong>
                {{$contractData->internalAgentContract->ssn}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Gender: &nbsp;</strong>
                {{ucfirst($contractData->internalAgentContract->gender)}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Date Of Birth: &nbsp;</strong>
                {{$contractData->internalAgentContract->dob}}
                </span>
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Cell Phone: &nbsp;</strong>
                {{$contractData->internalAgentContract->cell_phone}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Home Phone: &nbsp;</strong>
                {{$contractData->internalAgentContract->home_phone}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Fax: &nbsp;</strong>
                {{$contractData->internalAgentContract->fax}}
                </span>
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Email: &nbsp;</strong>
                {{$contractData->internalAgentContract->email}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Married Status: &nbsp;</strong>
                {{ucfirst($contractData->internalAgentContract->marital_status)}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Driver License#: &nbsp;</strong>
                {{$contractData->internalAgentContract->driver_license_no}}
                </span>
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Driver License State: &nbsp;</strong>
                {{getStateName($contractData->internalAgentContract->driver_license_state)}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Current Address(Resident): &nbsp;</strong>
                {{ucfirst($contractData->internalAgentContract->address)}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>City: &nbsp;</strong>
                {{$contractData->internalAgentContract->city}}
                </span>
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>State: &nbsp;</strong>
                {{getStateName($contractData->internalAgentContract->state)}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Zip Code: &nbsp;</strong>
                {{ucfirst($contractData->internalAgentContract->zip)}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Move-In Date: &nbsp;</strong>
                {{$contractData->internalAgentContract->move_in_date}}
                </span>
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Mailing Address (If Different From Residence): &nbsp;</strong>
                {{$contractData->internalAgentContract->move_in_address}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Move-In City: &nbsp;</strong>
                {{ucfirst($contractData->internalAgentContract->move_in_city)}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Move-In State: &nbsp;</strong>
                {{getStateName($contractData->internalAgentContract->move_in_state)}}
                </span>
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Move-In Zip: &nbsp;</strong>
                {{$contractData->internalAgentContract->move_in_zip}}
                </span>
            </td>

            <td style="width: 33.3%;"></td>
            <td style="width: 33.3%;"></td>
        </tr>
    </table>

    <p class="section-border mt-3 mb-30"></p>
    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Resident Insurance License #: &nbsp;</strong>
                {{$contractData->internalAgentContract->resident_insu_license_no}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Resident Insurance License State: &nbsp;</strong>
                {{getStateName($contractData->internalAgentContract->resident_insu_license_state)}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Doing Business As: &nbsp;</strong>
                {{$contractData->internalAgentContract->doing_business_as}}
                </span>
            </td>
        </tr>
    </table>
    <p class="section-border mt-3 mb-30"></p>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Business Name: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_name}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Tax ID: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_tax_id}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Principle Agent Name: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_agent_name}}
                </span>
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Principle Agent Title: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_agent_title}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Business Insurance Licence #: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_insu_license_no}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Cell Fax: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_office_fax}}
                </span>
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Office Phone: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_office_phone}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Email: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_email}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Website: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_website}}
                </span>
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Business Address: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_address}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>City: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_city}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>State: &nbsp;</strong>
                {{getStateName($contractData->internalAgentContract->business_state)}}
                </span>
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <span class="basic-info-element">
                <strong>Zip Code: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_zip}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                    <strong>Move-In Date: &nbsp;</strong>
                    {{$contractData->internalAgentContract->business_move_in_date}}
                </span>
            </td>

            <td style="width: 33.3%;">
                <span class="basic-info-element">
                        <strong>Company Type: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_company_type}}
                </span>
            </td>
        </tr>
    </table>
</div>



