<script setup>
let { form } = defineProps({
    form: Object,
    heading:String,
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
    <h1 style="font-size:30px;" class="text-gray-800 text-center text-xxl font-bold" v-text="heading"></h1>
    <br>
    <div style="width: 100%;">
        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                <div class=""><strong>Agent Full Name:</strong></div>
                <div class="ml-2">{{ form.agent_full_name }} </div>
            </div>
            <div class="flex">
                <div ><strong>Agent Email:</strong></div>
                <div class="ml-2">{{ form.agent_email }} </div>
            </div>
            <div class="flex">
                <!-- <div >EF Number :</div>
                <div class="ml-2"><strong>{{ form.ef_number }} </strong></div> -->
            </div>
        </div>
        <hr >

        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex ">
                <div class="flex"><strong>Insurance Company:</strong></div>
                <div class="ml-2">{{ form.insurance_company }} </div>
            </div>
            <div class="flex">
                <div ><strong>Product Name:</strong></div>
                <div class="ml-2">{{ form.product_name === 'Select' ? '' : form.product_name }} </div>
            </div>
            <div class="flex">
                <div ><strong>Application Date :</strong></div>
                <div class="ml-2">{{ formatDate(form.application_date) }} </div>
            </div>
        </div>
        <hr >

        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                <div class="flex"><strong>Coverage Amount:</strong></div>
                <div class="ml-2">{{ form.coverage_amount }} </div>
            </div>
            <div class="flex">
                <div ><strong>Coverage Length:</strong></div>
                <div class="ml-2">{{ form.coverage_length === 'Select' ? '' : form.coverage_length }} </div>
            </div>
            <div class="flex">
                <div ><strong>Premium Frequency</strong></div>
                <div class="ml-2">{{ form.premium_frequency === 'Select' ? '' : form.premium_frequency }} </div>
            </div>
        </div>
        <hr >

        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                <div class="flex"><strong>{{ form.premium_frequency != 'Select' ? form.premium_frequency : ''  }} Premium Amount:</strong></div>
                <div class="ml-2">{{ form.premium_amount }} </div>
            </div>
            <div class="flex">
                <div ><strong>Annual Premium Volume:</strong></div>
                <div class="ml-2">{{ form.premium_volumn }} </div>
            </div>
            <div class="flex">
                <div ><strong>Equis writing number:</strong></div>
                <div class="ml-2">{{ form.equis_writing_number_carrier === 'Select' ? '' : form.equis_writing_number_carrier }} </div>
            </div>
        </div>
        <hr >

        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                <div class="flex"><strong>Carrier Writing Number:</strong></div>
                <div class="ml-2">{{ form.carrier_writing_number }} </div>
            </div>
            <div class="flex">
                <div ><strong>App from a lead:</strong></div>
                <div class="ml-2">{{ form.this_app_from_lead === 'Select' ? '' : form.this_app_from_lead }} </div>
            </div>
            <div class="flex">
                <div ><strong>Source of the lead:</strong></div>
                <div class="ml-2">{{ form.source_of_lead }} </div>
            </div>
        </div>
        <hr >

        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                 <div >EF Number:</div>
                <div class="ml-2"><strong>{{ form.ef_number }} </strong></div>
            </div>
            <div class="flex">
                <div ><strong>Upline Manager:</strong></div>
                <div class="ml-2">{{ form.upline_manager === 'Select' ? '' : form.upline_manager }} </div>
            </div>
            <div class="flex">
                <div ><strong>split sale:</strong></div>
                <div class="ml-2">{{ form.split_sale === 'Select' ? '' : form.split_sale }} </div>
            </div>
        </div>
        <hr >

        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                 <div >split sale Type:</div>
                <div class="ml-2"><strong>{{ form.split_sale_type === 'Select' ? '' : form.split_sale_type }} </strong></div>
            </div>
            <div class="flex">
                <div ><strong>split Agent Email:</strong></div>
                <div class="ml-2">{{ form.split_agent_email  }} </div>
            </div>
            <div class="flex">
                <div ><strong>Appointment Type:</strong></div>
                <div class="ml-2">{{ form.appointment_type === 'Select' ? '' : form.appointment_type }} </div>
            </div>
        </div>
        <hr >

        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                 <div >Policy Draft Date:</div>
                <div class="ml-2"><strong>{{ formatDate(form.policy_draft_date) }} </strong></div>
            </div>
            <div class="flex">
                <div ><strong>First Name:</strong></div>
                <div class="ml-2">{{ form.first_name  }} </div>
            </div>
            <div class="flex">
                <div ><strong>Last Name:</strong></div>
                <div class="ml-2">{{ form.last_name  }} </div>
            </div>
            
        </div>
        <hr >

        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                <div ><strong>MI:</strong></div>
                <div class="ml-2">{{ form.mi  }} </div>
            </div>
            <div class="flex">
                 <div >Annual Target Premium:</div>
                <div class="ml-2"><strong>{{ form.annual_target_premium}} </strong></div>
            </div>
            <div class="flex">
                <div ><strong>Annual Planned Premium:</strong></div>
                <div class="ml-2">{{ form.annual_planned_premium  }} </div>
            </div>
            
        </div>
        <hr >

        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                <div ><strong>Annual Excess Premium:</strong></div>
                <div class="ml-2">{{ form.annual_excess_premium  }} </div>
            </div>
            <div class="flex">
                 <div >Initial Investment Amount:</div>
                <div class="ml-2"><strong>{{ form.intial_investment_amount}} </strong></div>
            </div>
            <div class="flex">
                <div ><strong>Did another agent refer this application to you?:</strong></div>
                <div class="ml-2">{{ form.refer_another_agent  }} </div>
            </div>
            
        </div>
        <hr >

        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                <div ><strong>Is this AN SDIC?:</strong></div>
                <div class="ml-2">{{ form.this_an_sdic  }} </div>
            </div>
            <div class="flex">
                 <div >Will there be a recurring premium?:</div>
                <div class="ml-2"><strong>{{ form.recurring_premium}} </strong></div>
            </div>
            <div class="flex">
                <div ><strong>Date of Birth:</strong></div>
                <div class="ml-2">{{ formatDate(form.dob)  }} </div>
            </div>
        </div>
        <hr >

        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                <div ><strong>Gender:</strong></div>
                <div class="ml-2">{{ form.gender === 'Select' ? '' : form.gender  }} </div>
            </div>
            <div class="flex">
                
            </div>
            <div class="flex">
               
            </div>
        </div>
        <hr >


        <p class="text-center my-3">Client Information</p>
        <hr>

        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                <div ><strong>Street Address 1:</strong></div>
                <div class="ml-2">{{ form.client_street_address_1  }} </div>
            </div>
            <div class="flex">
                 <div >Street Address 2:</div>
                <div class="ml-2"><strong>{{ form.client_street_address_2}} </strong></div>
            </div>
            <div class="flex">
                <div ><strong>City:</strong></div>
                <div class="ml-2">{{ form.client_city  }} </div>
            </div>
        </div>
        <hr >


        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                <div ><strong>State:</strong></div>
                <div class="ml-2">{{ form.client_state === 'Select' ? '' : form.client_state  }} </div>
            </div>
            <div class="flex">
                 <div >Zip-code:</div>
                <div class="ml-2"><strong>{{ form.client_zipcode}} </strong></div>
            </div>
            <div class="flex">
                <div ><strong>Client Phone Number:</strong></div>
                <div class="ml-2">{{ form.client_phone_no  }} </div>
            </div>
        </div>
        <hr >

        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                <div ><strong>Client Email:</strong></div>
                <div class="ml-2">{{ form.client_email   }} </div>
            </div>
            <div class="flex">
                 
            </div>
            <div class="flex">
                
            </div>
        </div>
        <hr >



</div></template>