<div>
    <h3 class="title">Contract - Basic Information</h3>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <strong>First Name: &nbsp;</strong>
                {{$contractData->internalAgentContract->first_name}}
            </td>

            <td style="width: 33.3%;">
                <strong>Last Name: &nbsp;</strong>
                {{$contractData->internalAgentContract->last_name}}
            </td>

            <td style="width: 33.3%;">
                <strong>Middle Name: &nbsp;</strong>
                {{$contractData->internalAgentContract->middle_name}}
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <strong>SSN: &nbsp;</strong>
                {{$contractData->internalAgentContract->ssn}}
            </td>

            <td style="width: 33.3%;">
                <strong>Gender: &nbsp;</strong>
                {{ucfirst($contractData->internalAgentContract->gender)}}
            </td>

            <td style="width: 33.3%;">
                <strong>Date Of Birth: &nbsp;</strong>
                {{$contractData->internalAgentContract->dob}}
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <strong>Cell Phone: &nbsp;</strong>
                {{$contractData->internalAgentContract->cell_phone}}
            </td>

            <td style="width: 33.3%;">
                <strong>Home Phone: &nbsp;</strong>
                {{$contractData->internalAgentContract->home_phone}}
            </td>

            <td style="width: 33.3%;">
                <strong>Fax: &nbsp;</strong>
                {{$contractData->internalAgentContract->fax}}
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <strong>Email: &nbsp;</strong>
                {{$contractData->internalAgentContract->email}}
            </td>

            <td style="width: 33.3%;">
                <strong>Married Status: &nbsp;</strong>
                {{ucfirst($contractData->internalAgentContract->marital_status)}}
            </td>

            <td style="width: 33.3%;">
                <strong>Driver License#: &nbsp;</strong>
                {{$contractData->internalAgentContract->driver_license_no}}
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <strong>Driver License State: &nbsp;</strong>
                {{$contractData->internalAgentContract->driver_license_state}}
            </td>

            <td style="width: 33.3%;">
                <strong>Current Address(Resident): &nbsp;</strong>
                {{ucfirst($contractData->internalAgentContract->address)}}
            </td>

            <td style="width: 33.3%;">
                <strong>City: &nbsp;</strong>
                {{$contractData->internalAgentContract->city}}
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <strong>State: &nbsp;</strong>
                {{$contractData->internalAgentContract->state}}
            </td>

            <td style="width: 33.3%;">
                <strong>Zip Code: &nbsp;</strong>
                {{ucfirst($contractData->internalAgentContract->zip)}}
            </td>

            <td style="width: 33.3%;">
                <strong>Move-In Date: &nbsp;</strong>
                {{$contractData->internalAgentContract->move_in_date}}
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <strong>Mailing Address (If Different From Residence): &nbsp;</strong>
                {{$contractData->internalAgentContract->move_in_address}}
            </td>

            <td style="width: 33.3%;">
                <strong>Move-In City: &nbsp;</strong>
                {{ucfirst($contractData->internalAgentContract->move_in_city)}}
            </td>

            <td style="width: 33.3%;">
                <strong>Move-In State: &nbsp;</strong>
                {{$contractData->internalAgentContract->move_in_state}}
            </td>
        </tr>
    </table>

    <table class="w-100 line-h30">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <strong>Move-In Zip: &nbsp;</strong>
                {{$contractData->internalAgentContract->move_in_zip}}
            </td>
        </tr>
    </table>

    <p class="section-border mt-3 mb-30"></p>
    <table class="w-100">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <strong>Resident Insurance License #: &nbsp;</strong>
                {{$contractData->internalAgentContract->resident_insu_license_no}}
            </td>

            <td style="width: 33.3%;">
                <strong>Resident Insurance License State: &nbsp;</strong>
                {{$contractData->internalAgentContract->resident_insu_license_state}}
            </td>

            <td style="width: 33.3%;" class="text-end">
                <strong>Doing Business As: &nbsp;</strong>
                {{$contractData->internalAgentContract->doing_business_as}}
            </td>
        </tr>
    </table>
    <p class="section-border mt-3 mb-30"></p>

    <table class="w-100">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <strong>Business Name: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_name}}
            </td>

            <td style="width: 33.3%;">
                <strong>Tax ID: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_tax_id}}
            </td>

            <td style="width: 33.3%;">
                <strong>Principle Agent Name: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_agent_name}}
            </td>
        </tr>
    </table>

    <table class="w-100">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <strong>Principle Agent Title: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_agent_title}}
            </td>

            <td style="width: 33.3%;">
                <strong>Business Insurance Licence #: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_insu_license_no}}
            </td>

            <td style="width: 33.3%;">
                <strong>Cell Fax: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_office_fax}}
            </td>
        </tr>
    </table>

    <table class="w-100">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <strong>Office Phone: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_office_phone}}
            </td>

            <td style="width: 33.3%;">
                <strong>Email: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_email}}
            </td>

            <td style="width: 33.3%;">
                <strong>Website: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_website}}
            </td>
        </tr>
    </table>

    <table class="w-100">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <strong>Business Address: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_address}}
            </td>

            <td style="width: 33.3%;">
                <strong>City: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_city}}
            </td>

            <td style="width: 33.3%;">
                <strong>State: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_state}}
            </td>
        </tr>
    </table>

    <table class="w-100">
        <tr class="w-100">
            <td style="width: 33.3%;">
                <strong>Zip Code: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_zip}}
            </td>

            <td style="width: 33.3%;">
                <strong>Move-In Date: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_move_in_date}}
            </td>

            <td style="width: 33.3%;">
                <strong>Company Type: &nbsp;</strong>
                {{$contractData->internalAgentContract->business_company_type}}
            </td>
        </tr>
    </table>
</div>




