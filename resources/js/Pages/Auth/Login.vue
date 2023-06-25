<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import NewGuestLayout from '@/Layouts/NewGuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import GuestTextInput from '@/Components/GuestTextInput.vue';
import Footer from '@/Components/Footer.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <!-- <GuestLayout> -->
    <NewGuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
        
        <form @submit.prevent="submit">
            <div>
                <div class="py-4 mb-4 text-custom-blue text-3xl font-bold">Log In</div>

                <GuestTextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Email"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-6">

                <GuestTextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="Password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                </label>
            </div>



            <div class="flex flex-col items-start mt-6">
                <PrimaryButton class="mb-6" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
                
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="font-semibold text-sm lg:text-md text-custom-sky hover:text-custom-green rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Forgot your password?
                </Link>
            </div>

            <div class="mt-2 mb-10">
                <p class="text-sm lg:text-lg text-custom-blue font-extrabold">New to AllCalls.io? <Link href="/register" class="text-custom-sky font-bold">Register Here</Link></p>
            </div>


        </form>

        <template v-slot:titles>
                <div class="text-4xl lg:text-5xl xl:text-8xl text-white mb-10 "><span class="text-custom-green">On-Demand</span> Calls</div>
                <div class="text-white text-2xl lg:text-2xl xl:text-5xl text-3xl">Bringing Clients right to your Fingertips.</div>
        </template>

        <template v-slot:subtitles>
            <div class="text-custom-blue text-2xl xl:text-5xl font-bold">
                Creating New 
                Opportunities for 
                Our Customers is 
                What We Do!
            </div>
        </template>



    <!-- </GuestLayout> -->
    </NewGuestLayout>
    
    <Footer />
</template>
