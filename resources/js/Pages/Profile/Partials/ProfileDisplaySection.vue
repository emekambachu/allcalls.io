<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { ref, reactive } from "vue";


let user = usePage().props.auth.user;

let form = useForm({
  first_name: user.first_name,
  last_name: user.last_name,
  email: user.email,
  phone: user.phone,
  states_info: user.states_info,
});
</script>

<template>
    
</template>