<script setup>
let { form } = defineProps({
    form: Object,
})
let formatDate = date => {
    if (!date) {
        return '';
    }

    const dateObj = new Date(date);

    const formattedDate = dateObj.toLocaleDateString("en-US", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });

    return formattedDate;
}
console.log('form', form);
</script>
<template>
    <h1 class="text-gray-800 text-xl font-bold">Please confirm all your information is correct:</h1>
    <br>
    <div style="width: 100%;">

        <div class="flex my-3">
            <div style="width: 50%;">Agent Full Name</div>
            <div style="width: 50%;"><strong>{{ form.agent_full_name }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Agent Email</div>
            <div style="width: 50%;"><strong>{{ form.agent_email }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">EF Number</div>
            <div style="width: 50%;"><strong>{{ form.ef_number }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Upline Manager</div>
            <div style="width: 50%;"><strong>{{ form.upline_manager === 'Select' ? '' : form.upline_manager  }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">split sale</div>
            <div style="width: 50%;"><strong>{{ form.split_sale === 'Select' ? '' : form.split_sale  }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">split sale Type </div>
            <div style="width: 50%;"><strong>{{ form.split_sale_type === 'Select' ? '' : form.split_sale_type   }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">split Agent Email</div>
            <div style="width: 50%;"><strong>{{ form.split_agent_email }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Insurance Company</div>
            <div style="width: 50%;"><strong>{{ form.insurance_company === 'Select' ? '' : form.insurance_company    }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Product Name</div>
            <div style="width: 50%;"><strong>{{ form.product_name === 'Select' ? '' : form.product_name }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Application Date</div>
            <div style="width: 50%;"><strong>{{ formatDate(form.application_date)   }} </strong></div>
        </div>
        <hr>

  <div class="flex my-3">
            <div style="width: 50%;">Coverage Amount</div>
            <div style="width: 50%;"><strong>{{ form.coverage_amount }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Coverage Length</div>
            <div style="width: 50%;"><strong>{{ form.coverage_length === 'Select' ? '' : form.coverage_length }} </strong></div>
        </div>
        <hr>

      

        <div class="flex my-3">
            <div style="width: 50%;">Premium Frequency</div>
            <div style="width: 50%;"><strong>{{ form.premium_frequency === 'Select' ? '' : form.premium_frequency   }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">{{ form.premium_frequency }} Premium Amount</div>
            <div style="width: 50%;"><strong>{{ form.quarterly_premium_amount }} </strong></div>
        </div>
        <hr>
        
        <div class="flex my-3">
            <div style="width: 50%;">Annual Premium Volume</div>
            <div style="width: 50%;"><strong>{{ form.annually_premium_amount }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Equis writing number </div>
            <div style="width: 50%;"><strong>{{ form.equis_writing_number_carrier === 'Select' ? '' : form.equis_writing_number_carrier    }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Carrier Writing Number</div>
            <div style="width: 50%;"><strong>{{ form.carrier_writing_number }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">App from a lead</div>
            <div style="width: 50%;"><strong>{{ form.this_app_from_lead === 'Select' ? '' : form.this_app_from_lead  }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Source of the lead</div>
            <div style="width: 50%;"><strong>{{ form.source_of_lead }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Appointment Type</div>
            <div style="width: 50%;"><strong>{{ form.appointment_type === 'Select' ? '' : form.appointment_type   }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Policy Draft Date</div>
            <div style="width: 50%;"><strong>{{ formatDate(form.policy_draft_date) }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">First Name</div>
            <div style="width: 50%;"><strong>{{ form.first_name }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3"> 
            <div style="width: 50%;">MI</div>
            <div style="width: 50%;"><strong>{{ form.mi }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Annual Target Premium</div>
            <div style="width: 50%;"><strong>{{ form.annual_target_premium }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Annual Planned Premium</div>
            <div style="width: 50%;"><strong>{{ form.annual_planned_premium }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Annual Excess Premium</div>
            <div style="width: 50%;"><strong>{{ form.annual_excess_premium }} </strong></div>
        </div>
        <hr>


        <div class="flex my-3">
            <div style="width: 50%;">Initial Investment Amount</div>
            <div style="width: 50%;"><strong>{{ form.intial_investment_amount }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Did another agent refer this application to you?</div>
            <div style="width: 50%;"><strong>{{ form.refer_another_agent }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Is this AN SDIC?</div>
            <div style="width: 50%;"><strong>{{ form.this_an_sdic }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Will there be a recurring premium?</div>
            <div style="width: 50%;"><strong>{{ form.recurring_premium }} </strong></div>
        </div>
        <hr>

     

        <div class="flex my-3">
            <div style="width: 50%;">Last Name</div>
            <div style="width: 50%;"><strong>{{ form.last_name }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Date of Birth</div>
            <div style="width: 50%;"><strong>{{ formatDate(form.dob)  }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Gender</div>
            <div style="width: 50%;"><strong>{{ form.gender === 'Select' ? '' : form.gender }} </strong></div>
        </div>
        <hr>
        
        <p class="text-center my-3">Client Information</p>
        <hr>
        <div class="flex my-3">
            <div style="width: 50%;">Street Address 1</div>
            <div style="width: 50%;"><strong>{{ form.client_street_address_1 }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Street Address 2</div>
            <div style="width: 50%;"><strong>{{ form.client_street_address_2 }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">City</div>
            <div style="width: 50%;"><strong>{{ form.client_city }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">State</div>
            <div style="width: 50%;"><strong>{{ form.client_state === 'Select' ? '' : form.client_state }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Zip-code</div>
            <div style="width: 50%;"><strong>{{ form.client_zipcode }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Client Phone Number</div>
            <div style="width: 50%;"><strong>{{ form.client_phone_no }} </strong></div>
        </div>
        <hr>

        <div class="flex my-3">
            <div style="width: 50%;">Client Email</div>
            <div style="width: 50%;"><strong>{{ form.client_email }} </strong></div>
        </div>
        <hr>

       

    </div>


    
</template>