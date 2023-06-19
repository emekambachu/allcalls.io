<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import NewGuestLayout from '@/Layouts/NewGuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Footer from '@/Components/Footer.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <!-- <GuestLayout> -->
    <NewGuestLayout>

        <Head title="Forgot Password" />

        <!-- <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset
            link that will allow you to choose a new one.
        </div> -->

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>

        <template v-slot:titles>
                <div class="text-4xl lg:text-5xl xl:text-8xl text-white mb-10 "><span class="text-custom-green">Forgot your password?</span> No problem. </div>
        </template>

        
        <template v-slot:subtitles>            
            <div class="text-custom-blue text-sm md:text-lg lg:text-2xl mt-6 font-bold">
                Just let us know your email address and we will email you a password reset
                link that will allow you to choose a new one.
            </div>
        </template>

    </NewGuestLayout>
    <!-- </GuestLayout> -->
    <Footer />
</template>
