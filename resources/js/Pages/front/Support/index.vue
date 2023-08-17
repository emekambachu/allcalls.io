<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { onMounted, onBeforeUnmount, ref } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Footer from "@/Components/Footer.vue";
import ScrollAnimation from "@/Components/ScrollAnimation.vue";

defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  },
  laravelVersion: {
    type: String,
    required: true,
  },
  phpVersion: {
    type: String,
    required: true,
  },
});
let openLiveChat = () => {
  let iframe = document.querySelector(".widget-visible iframe");
  let iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
  let iframeButton = iframeDoc.querySelector("button");

  iframeButton.click();
};

// Define a reactive property for v-show
const showHamburger = ref(false);
</script>

<template>
  <Head title="Welcome">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800;900&family=Nunito+Sans:wght@300;400;500;600;700;800;900&display=swap"
      rel="stylesheet"
    />
  </Head>

  <section id="getstarted">
    <div class="main">
      <div class="container">
        <header class="header">
          <div class="header__logo">
            <!-- <img src="/img/logo.png" alt=""> -->
            <ApplicationLogo />
          </div>

          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-6 h-6 sm:w-10 sm:h-10 text-white xl:hidden cursor-pointer transition hover:text-custom-green hamburger"
            @click="showHamburger = !showHamburger"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
            />
          </svg>

          <nav class="header__nav hidden xl:flex items-end">
            <ul class="">
              <a href="/#getstarted" class="hover:text-custom-green"
                ><li>Live Transfers</li></a
              >
              <a href="/#solutions" class="hover:text-custom-green"
                ><li>Why AllCalls.io</li></a
              >
              <a href="/#resources" class="hover:text-custom-green"
                ><li>How It Works</li></a
              >
              <a
                href="https://www.financialservicecareers.com"
                class="hover:text-custom-green"
                ><li>Careers</li></a
              >
              <a href="/clients/support" class="hover:text-custom-green"
                ><li>Careers</li></a
              >
              <a href="/register" class="hover:text-custom-green"><li>Sign Up Now</li></a>
            </ul>
          </nav>
          <a href="/login" class="hidden xl:flex">
            <div class="header__button hover:text-custom-green">Log In</div>
          </a>

          <!-- hamburger -->
        </header>

        <ul
          class="text-white transition-all delay-700 duration-800 w-full relative z-20 text-center"
          v-show="showHamburger"
        >
          <a href="/#getstarted"
            ><li
              class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white mb-2"
            >
              Live Transfers
            </li></a
          >
          <a href="/#solutions"
            ><li
              class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white mb-2"
            >
              Why AllCalls.io
            </li></a
          >
          <a href="/#resources"
            ><li
              class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white mb-2"
            >
              How It Works
            </li></a
          >
          <a href="https://www.financialservicecareers.com"
            ><li
              class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white mb-2"
            >
              Careers
            </li></a
          >
          <a href="/clients/support"
            ><li
              class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white mb-2"
            >
              Support
            </li></a
          >
          <a href="/login"
            ><li
              class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white mb-2"
            >
              Log In
            </li></a
          >
          <a href="/register"
            ><li
              class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white"
            >
              Register
            </li></a
          >
        </ul>

        <!-- Support Start -->
        <div class="bg-white pt-14 rounded">
          <div class="pt-14">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
              <div class="px-4 sm:px-8 sm:rounded-lg">
                <div class="text-4xl text-custom-sky font-bold mb-6">Support</div>
                <hr class="mb-4" />
              </div>
            </div>
          </div>

          <div
            class="px-16 mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8"
          >
            <div class="flex flex-col bg-custom-darksky rounded p-4 text-center">
              <div class="mb-6">
                <h2 class="text-xl text-white font-extrabold">Search our Help Center</h2>
                <p class="text-gray-300">
                  Find helpful how-to's and commonly asked questions.
                </p>
              </div>
              <div class="mt-auto">
                <PrimaryButton>Visit Help Center</PrimaryButton>
              </div>
            </div>

            <div class="flex flex-col bg-custom-darksky rounded p-4 text-center">
              <div class="mb-6">
                <h2 class="text-xl text-white font-extrabold">Live Calls User Guide</h2>
                <p class="text-gray-300">A detailed guide of the Live Calls page.</p>
              </div>
              <div class="mt-auto">
                <PrimaryButton>Download</PrimaryButton>
              </div>
            </div>

            <div class="flex flex-col bg-custom-darksky rounded p-4 text-center">
              <div class="mb-6">
                <h2 class="text-xl text-white font-extrabold">Live-chat support</h2>
                <p class="text-gray-300">
                  You can chat with our support staff if you have any issues or questions.
                </p>
              </div>
              <div class="mt-auto">
                <PrimaryButton @click="openLiveChat">Open Live Chat</PrimaryButton>
              </div>
            </div>
          </div>

          <div class="pt-14">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
              <div class="px-4 sm:px-8 sm:rounded-lg">
                <div class="text-4xl text-custom-sky font-bold mb-6">
                  Contact Information
                </div>
                <hr class="mb-4" />
              </div>
            </div>
          </div>

          <div class="px-16 text-gray-700 mb-8 pb-14">
            <div class="font-bold text-gray-700 mb-2 text-lg">AllCalls LLC</div>
            <div>500 4th St NW Suite 102 NUM 1981, Albuquerque, NM 87102 USA</div>
            <div>
              <strong class="text-gray-600">Toll Free Customer Service Phone:</strong>
              +1 (888) 482-9195
            </div>
            <div>
              <strong class="text-gray-600">Support Hours:</strong> 24/7 Monday - Sunday
            </div>

            <div class="font-bold text-gray-700 mt-4 text-lg mb-2">Returns</div>
            <p class="mb-3">
              If you need to return your order please contact customer service to get an
              RMA number that you will need to include in the package you send back to us.
              All returns should be sent to this address:
            </p>

            <p class="mb-1">12924 Pierce Street, Pacoima CA 91331, USA</p>
            <p><strong class="text-gray-500">Email:</strong> support@allcalls.io</p>
          </div>
        </div>
        <!-- Support End -->
      </div>
      <!-- <div class="main__layer">
        <img src="/img/main-layer.png" alt="" />
      </div> -->
    </div>
  </section>

  <Footer />
</template>

<style>
.bg-dots-darker {
  background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
  .dark\:bg-dots-lighter {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
  }
}
</style>
