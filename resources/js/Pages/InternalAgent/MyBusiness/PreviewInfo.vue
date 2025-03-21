<script setup>
import { Head, router, usePage } from "@inertiajs/vue3";

let { form } = defineProps({
  form: Object,
  heading: String,
});
let page = usePage();

let formatDate = (date) => {
  if (!date) {
    return "";
  }

  const dateObj = new Date(date);

  const formattedDate = dateObj.toLocaleDateString("en-US", {
    day: "numeric",
    month: "short",
    year: "numeric",
  });

  return formattedDate;
};
</script>
<template>
  <div
    v-if="heading"
    class="flex items-center rounded-lg bg-blue-50 p-4 text-sm text-blue-800"
    role="alert"
  >
    <svg
      class="me-3 inline h-4 w-4 flex-shrink-0"
      aria-hidden="true"
      xmlns="http://www.w3.org/2000/svg"
      fill="currentColor"
      viewBox="0 0 20 20"
    >
      <path
        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"
      />
    </svg>
    <span class="sr-only">Info</span>
    <div v-text="heading"></div>
  </div>

  <br />
  <div style="width: 100%">
    <div v-if="$page.props.auth.role == 'admin'">
      <h1
        style="background-color: #134576"
        class="my-5 text-center rounded-md py-2 text-white"
      >
        Agent Information
      </h1>
      <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
        <div class="flex">
          <div><strong>Agent Name:</strong></div>
          <div class="ml-2">{{ form.agent_full_name }}</div>
        </div>
        <div class="flex">
          <div><strong>Agent Email:</strong></div>
          <div class="ml-2">{{ form.agent_email }}</div>
        </div>
      </div>
      <hr />
    </div>

    <h1
      style="background-color: #134576"
      class="my-5 text-center rounded-md py-2 text-white"
    >
      Client Information
    </h1>

    <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
      <div class="flex">
        <div><strong>First Name:</strong></div>
        <div class="ml-2">{{ form.first_name }}</div>
      </div>
      <div class="flex">
        <div><strong>Last Name:</strong></div>
        <div class="ml-2">{{ form.last_name }}</div>
      </div>
      <div class="flex">
        <div><strong>MI:</strong></div>
        <div class="ml-2">{{ form.mi }}</div>
      </div>
    </div>
    <hr />

    <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
      <div class="flex">
        <div style="min-width: 135px"><strong>Beneficiary Name:</strong></div>
        <div class="ml-2">{{ form.beneficiary_name }}</div>
      </div>
      <div class="flex">
        <div style="min-width: 181px"><strong>Beneficiary Relationship:</strong></div>
        <div class="ml-2">{{ form.beneficiary_relationship }}</div>
      </div>
    </div>
    <hr />

    <div class="grid my-3 xl:grid-cols-2 lg:grid-cols-2 sm:grid-cols-1">
      <div class="flex">
        <div style="min-width: 125px"><strong>Street Address 1:</strong></div>
        <div class="ml-2">{{ form.client_street_address_1 }}</div>
      </div>
      <div class="flex">
        <div style="min-width: 125px"><strong>Street Address 2:</strong></div>
        <div class="ml-2">{{ form.client_street_address_2 }}</div>
      </div>
    </div>
    <hr />

    <div class="grid my-3 xl:grid-cols-4 lg:grid-cols-2 sm:grid-cols-1">
      <div class="flex">
        <div><strong>City:</strong></div>
        <div class="ml-2">{{ form.client_city }}</div>
      </div>
      <div class="flex">
        <div><strong>State:</strong></div>
        <div class="ml-2">{{ form.client_state_name }}</div>
      </div>
      <div class="flex">
        <div><strong>Zip-code:</strong></div>
        <div class="ml-2">{{ form.client_zipcode }}</div>
      </div>
      <div class="flex">
        <div><strong>Date of Birth:</strong></div>
        <div class="ml-2">{{ formatDate(form.dob) }}</div>
      </div>
    </div>
    <hr />

    <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
      <div class="flex">
        <div><strong>Client Phone Number:</strong></div>
        <div class="ml-2">{{ form.client_phone_no }}</div>
      </div>
      <div class="flex">
        <div><strong>Client Email:</strong></div>
        <div class="ml-2">{{ form.client_email }}</div>
      </div>
      <div class="flex">
        <div><strong>Gender:</strong></div>
        <div class="ml-2">{{ form.gender === "Select" ? "" : form.gender }}</div>
      </div>
    </div>
    <hr />
    <div class="grid my-3 xl:grid-cols-1 lg:grid-cols-1 sm:grid-cols-1">
      <div class="flex">
        <div><strong>Notes:</strong></div>
        <div class="ml-2">{{ form.notes }}</div>
      </div>
    </div>
    <hr />

    <h1
      style="background-color: #134576"
      class="my-5 text-center rounded-md py-2 text-white"
    >
      Policy Information
    </h1>
    <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
      <div class="flex">
        <div class="flex"><strong>Status:</strong></div>
        <div class="ml-2">{{ form.status }}</div>
      </div>
      <div class="flex">
        <div class="flex"><strong>Insurance Company:</strong></div>
        <div class="ml-2">{{ form.insurance_company }}</div>
      </div>
      <div class="flex">
        <div><strong>Product Name:</strong></div>
        <div class="ml-2">
          {{ form.product_name === "Select" ? "" : form.product_name }}
        </div>
      </div>
    </div>
    <hr />

    <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
      <div class="flex">
        <div><strong>Application Date :</strong></div>
        <div class="ml-2">{{ formatDate(form.application_date) }}</div>
      </div>
      <div class="flex">
        <div class="flex"><strong>Coverage Amount:</strong></div>
        <div class="ml-2">{{ form.coverage_amount }}</div>
      </div>
      <div class="flex">
        <div><strong>Coverage Length:</strong></div>
        <div class="ml-2">
          {{ form.coverage_length === "Select" ? "" : form.coverage_length }}
        </div>
      </div>
    </div>
    <hr />

    <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
      <div class="flex">
        <div><strong>Premium Frequency</strong></div>
        <div class="ml-2">
          {{ form.premium_frequency === "Select" ? "" : form.premium_frequency }}
        </div>
      </div>
      <div class="flex">
        <div class="flex">
          <strong
            >{{
              form.premium_frequency != "Select" ? form.premium_frequency : ""
            }}
            Premium Amount:</strong
          >
        </div>
        <div class="ml-2">{{ form.premium_amount }}</div>
      </div>
      <div class="flex">
        <div><strong>Annual Premium Volume:</strong></div>
        <div class="ml-2">{{ form.premium_volumn }}</div>
      </div>
    </div>
    <hr />

    <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
      <div class="flex">
        <div class="flex"><strong>Carrier Writing Number:</strong></div>
        <div class="ml-2">{{ form.carrier_writing_number }}</div>
      </div>
      <div class="flex">
        <div><strong>App from a lead:</strong></div>
        <div
          v-if="form.this_app_from_lead == 0 || form.this_app_from_lead == 1"
          class="ml-2"
        >
          {{ form.this_app_from_lead == "0" ? "NO" : "YES" }}
        </div>
        <div v-else class="ml-2">{{ form.this_app_from_lead }}</div>
      </div>
      <div class="flex">
        <div><strong>Source of the lead:</strong></div>
        <div class="ml-2">
          {{ form.this_app_from_lead === "YES" ? form.source_of_lead : "" }}
        </div>
      </div>
    </div>
    <hr />
    <div class="grid my-3 xl:grid-cols-3 lg:grid-cols-2 sm:grid-cols-1">
      <div class="flex">
        <div><strong>Policy Draft Date:</strong></div>
        <div class="ml-2">{{ formatDate(form.policy_draft_date) }}</div>
      </div>
    </div>
    <hr />
    <br />
  </div>
</template>
