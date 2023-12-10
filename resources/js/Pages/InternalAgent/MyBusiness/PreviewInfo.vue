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
</script>
<template>
    <h1 style="font-size:30px;" class="text-gray-800 text-center text-xxl font-bold" v-text="heading"></h1>
    <br>
    <div style="width: 100%;">
       

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
                <!-- <div ><strong>Equis writing number:</strong></div>
                <div class="ml-2">{{ form.equis_writing_number_carrier === 'Select' ? '' : form.equis_writing_number_carrier }} </div> -->
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
                <div class="ml-2">{{ form.this_app_from_lead == '0' ? 'NO' : 'YES' }} </div>
            </div>
            <div class="flex">
                <div ><strong>Source of the lead:</strong></div>
                <div class="ml-2">{{ form.this_app_from_lead === 'YES' ? form.source_of_lead:"" }} </div>
            </div>
        </div>
        <hr >
        <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
            <div class="flex">
                 <div><strong>Policy Draft Date:</strong></div>
                <div class="ml-2"><strong>{{ formatDate(form.policy_draft_date) }} </strong></div>
            </div>
            <div class="flex">
                <div><strong>First Name:</strong></div>
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
                 <div><strong>Will there be a recurring premium?:</strong></div>
                 <div v-if="form.recurring_premium == 'YES'|| form.recurring_premium == 'NO'" class="ml-2">{{ form.recurring_premium  }} </div>
                <div v-if="form.recurring_premium == 0|| form.recurring_premium == 1" class="ml-2">{{ form.recurring_premium == 1 ? 'YES' : 'NO' }} </div>
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
                 <div><strong>Street Address 2:</strong></div>
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
                <div class="ml-2">{{ form.client_state_name   }} </div>
            </div>
            <div class="flex">
                 <div><strong>Zip-code:</strong></div>
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
        <hr>
</div></template>
