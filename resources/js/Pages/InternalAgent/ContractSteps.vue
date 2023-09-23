<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ContractModal from "@/Pages/InternalAgent/ContractModal.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Steps" />
    <AuthenticatedLayout>
        <ContractModal  >
        </ContractModal>
    </AuthenticatedLayout>
</template>

