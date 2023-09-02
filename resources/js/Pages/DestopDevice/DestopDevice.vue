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
      rel="stylesheet" />
  </Head>
  <section id="getstarted">
    <div class="main">
      <div class="container">
        <header class="header">
          <div class="header__logo">
            <ApplicationLogo />
          </div>
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6 sm:w-10 sm:h-10 text-white xl:hidden cursor-pointer transition hover:text-custom-green hamburger"
            @click="showHamburger = !showHamburger">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>

          <nav class="header__nav hidden xl:flex items-end">
            <ul class="">
              <a href="/#getstarted" class="hover:text-custom-green">
                <li>Live Transfers</li>
              </a>
              <a href="/#solutions" class="hover:text-custom-green">
                <li>Why AllCalls.io</li>
              </a>
              <a href="/#resources" class="hover:text-custom-green">
                <li>How It Works</li>
              </a>
              <a href="https://www.financialservicecareers.com" class="hover:text-custom-green">
                <li>Careers</li>
              </a>
              <a href="/support" class="hover:text-custom-green">
                <li>Support</li>
              </a>
              <a href="/register" class="hover:text-custom-green">
                <li>Sign Up Now</li>
              </a>
            </ul>
          </nav>
          <a href="/login" class="hidden xl:flex">
            <div class="header__button hover:text-custom-green">Log In</div>
          </a>

          <!-- hamburger -->
        </header>

        <ul class="text-white transition-all delay-700 duration-800 w-full relative z-20 text-center"
          v-show="showHamburger">
          <a href="/#getstarted">
            <li
              class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white mb-2">
              Live Transfers
            </li>
          </a>
          <a href="/#solutions">
            <li
              class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white mb-2">
              Why AllCalls.io
            </li>
          </a>
          <a href="/#resources">
            <li
              class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white mb-2">
              How It Works
            </li>
          </a>
          <a href="https://www.financialservicecareers.com">
            <li
              class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white mb-2">
              Careers
            </li>
          </a>
          <a href="/support">
            <li
              class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white mb-2">
              Support
            </li>
          </a>
          <a href="/login">
            <li
              class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white mb-2">
              Log In
            </li>
          </a>
          <a href="/register">
            <li
              class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white">
              Register
            </li>
          </a>
        </ul>

        <!-- Detop Device Start -->
        <div class="bg-white rounded">
          <div class="pt-14">
            <div class="max-w-7xl px-8 space-y-6">
              <div class="px-4 sm:px-8 sm:rounded-lg">
                <div class="text-4xl text-custom-sky font-bold mb-6">Destop Device</div>
                <hr class="mb-4" />
              </div>
            </div>
          </div>

          <ol class="border-l border-neutral-300 px-16 dark:border-neutral-500">
            <!--First item-->
            <li>
              <div class="flex-start flex items-center pt-3">
                <div class="-ml-[5px] mr-3 h-[9px] w-[9px] rounded-full bg-neutral-300 dark:bg-neutral-500"></div>
                <p class="text-sm text-neutral-500 dark:text-neutral-300">
                  01.07.2021
                </p>
              </div>
              <div class="mb-6 ml-4 mt-2">
                <h4 class="mb-1.5 text-xl font-semibold">Title of section 1</h4>
                <p class="mb-3 text-neutral-500 dark:text-neutral-300">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque
                  scelerisque diam non nisi semper, et elementum lorem ornare.
                  Maecenas placerat facilisis mollis. Duis sagittis ligula in
                  sodales vehicula.
                </p>
              </div>
            </li>

            <!--Second item-->
            <li>
              <div class="flex-start flex items-center pt-2">
                <div class="-ml-[5px] mr-3 h-[9px] w-[9px] rounded-full bg-neutral-300 dark:bg-neutral-500"></div>
                <p class="text-sm text-neutral-500 dark:text-neutral-300">
                  13.09.2021
                </p>
              </div>
              <div class="mb-6 ml-4 mt-2">
                <h4 class="mb-1.5 text-xl font-semibold">Title of section 2</h4>
                <p class="mb-3 text-neutral-500 dark:text-neutral-300">
                  Libero expedita explicabo eius fugiat quia aspernatur autem
                  laudantium error architecto recusandae natus sapiente sit nam
                  eaque, consectetur porro molestiae ipsam an deleniti.
                </p>
              </div>
            </li>

            <!--Third item-->
            <li>
              <div class="flex-start flex items-center pt-2">
                <div class="-ml-[5px] mr-3 h-[9px] w-[9px] rounded-full bg-neutral-300 dark:bg-neutral-500"></div>
                <p class="text-sm text-neutral-500 dark:text-neutral-300">
                  25.11.2021
                </p>
              </div>
              <div class="ml-4 mt-2 pb-5">
                <h4 class="mb-1.5 text-xl font-semibold">Title of section 3</h4>
                <p class="mb-3 text-neutral-500 dark:text-neutral-300">
                  Voluptatibus temporibus esse illum eum aspernatur, fugiat suscipit
                  natus! Eum corporis illum nihil officiis tempore. Excepturi illo
                  natus libero sit doloremque, laborum molestias rerum pariatur quam
                  ipsam necessitatibus incidunt, explicabo.
                </p>
              </div>
            </li>
          </ol>



          <ol class="border-l border-neutral-300 px-16 dark:border-neutral-500">
            <li>
              <div class="flex-start flex items-center pt-2">
                <div class="-ml-[5px] mr-3 h-[9px] w-[9px] rounded-full bg-neutral-300 dark:bg-neutral-500"></div>
                <p class="text-sm text-neutral-500 dark:text-neutral-300">
                  25.11.2021
                </p>
              </div>
              <div class="ml-4 mt-2 pb-5">
                <h4 class="mb-1.5 text-xl font-semibold">Title of section 4</h4>
                <p class="mb-3 text-neutral-500 dark:text-neutral-300">
                  Voluptatibus temporibus esse illum eum aspernatur, fugiat suscipit
                  natus! Eum corporis illum nihil officiis tempore. Excepturi illo
                  natus libero sit doloremque, laborum molestias rerum pariatur quam
                  ipsam necessitatibus incidunt, explicabo.
                </p>
              </div>
            </li>
          </ol>

          <ol class="border-l border-neutral-300 px-16 dark:border-neutral-500">
            <li>
              <div class="flex-start flex items-center pt-2">
                <div class="-ml-[5px] mr-3 h-[9px] w-[9px] rounded-full bg-neutral-300 dark:bg-neutral-500"></div>
                <p class="text-sm text-neutral-500 dark:text-neutral-300">
                  25.11.2021
                </p>
              </div>
              <div class="ml-4 mt-2 pb-5">
                <h4 class="mb-1.5 text-xl font-semibold">Title of section 5</h4>
                <p class="mb-3 text-neutral-500 dark:text-neutral-300">
                  Voluptatibus temporibus esse illum eum aspernatur, fugiat suscipit
                  natus! Eum corporis illum nihil officiis tempore. Excepturi illo
                  natus libero sit doloremque, laborum molestias rerum pariatur quam
                  ipsam necessitatibus incidunt, explicabo.
                </p>
              </div>
            </li>
          </ol>

          <ol class="border-l border-neutral-300 px-16 dark:border-neutral-500">
            <li>
              <div class="flex-start flex items-center pt-2">
                <div class="-ml-[5px] mr-3 h-[9px] w-[9px] rounded-full bg-neutral-300 dark:bg-neutral-500"></div>
                <p class="text-sm text-neutral-500 dark:text-neutral-300">
                  25.11.2021
                </p>
              </div>
              <div class="ml-4 mt-2 pb-5">
                <h4 class="mb-1.5 text-xl font-semibold">Title of section 6</h4>
                <p class="mb-3 text-neutral-500 dark:text-neutral-300">
                  Voluptatibus temporibus esse illum eum aspernatur, fugiat suscipit
                  natus! Eum corporis illum nihil officiis tempore. Excepturi illo
                  natus libero sit doloremque, laborum molestias rerum pariatur quam
                  ipsam necessitatibus incidunt, explicabo.
                </p>
              </div>
            </li>
          </ol>

          <ol class="border-l border-neutral-300 px-16 dark:border-neutral-500">
            <li>
              <div class="flex-start flex items-center pt-2">
                <div class="-ml-[5px] mr-3 h-[9px] w-[9px] rounded-full bg-neutral-300 dark:bg-neutral-500"></div>
                <p class="text-sm text-neutral-500 dark:text-neutral-300">
                  25.11.2021
                </p>
              </div>
              <div class="ml-4 mt-2 pb-5">
                <h4 class="mb-1.5 text-xl font-semibold">Title of section 7</h4>
                <p class="mb-3 text-neutral-500 dark:text-neutral-300">
                  Voluptatibus temporibus esse illum eum aspernatur, fugiat suscipit
                  natus! Eum corporis illum nihil officiis tempore. Excepturi illo
                  natus libero sit doloremque, laborum molestias rerum pariatur quam
                  ipsam necessitatibus incidunt, explicabo.
                </p>
              </div>
            </li>
          </ol>



          <ol class="border-l border-neutral-300 px-16 dark:border-neutral-500">
            <li>
              <div class="flex-start flex items-center pt-2">
                <div class="-ml-[5px] mr-3 h-[9px] w-[9px] rounded-full bg-neutral-300 dark:bg-neutral-500"></div>
                <p class="text-sm text-neutral-500 dark:text-neutral-300">
                  25.11.2021
                </p>
              </div>
              <div class="ml-4 mt-2 pb-5">
                <h4 class="mb-1.5 text-xl font-semibold">Title of section 8</h4>
                <p class="mb-3 text-neutral-500 dark:text-neutral-300">
                  Voluptatibus temporibus esse illum eum aspernatur, fugiat suscipit
                  natus! Eum corporis illum nihil officiis tempore. Excepturi illo
                  natus libero sit doloremque, laborum molestias rerum pariatur quam
                  ipsam necessitatibus incidunt, explicabo.
                </p>
              </div>
            </li>
          </ol>



          <ol class="border-l border-neutral-300 px-16 dark:border-neutral-500">
            <li>
              <div class="flex-start flex items-center pt-2">
                <div class="-ml-[5px] mr-3 h-[9px] w-[9px] rounded-full bg-neutral-300 dark:bg-neutral-500"></div>
                <p class="text-sm text-neutral-500 dark:text-neutral-300">
                  25.11.2021
                </p>
              </div>
              <div class="ml-4 mt-2 pb-5">
                <h4 class="mb-1.5 text-xl font-semibold">Title of section 9</h4>
                <p class="mb-3 text-neutral-500 dark:text-neutral-300">
                  Voluptatibus temporibus esse illum eum aspernatur, fugiat suscipit
                  natus! Eum corporis illum nihil officiis tempore. Excepturi illo
                  natus libero sit doloremque, laborum molestias rerum pariatur quam
                  ipsam necessitatibus incidunt, explicabo.
                </p>
              </div>
            </li>
          </ol>


          <ol class="border-l border-neutral-300 px-16 dark:border-neutral-500">
            <li>
              <div class="flex-start flex items-center pt-2">
                <div class="-ml-[5px] mr-3 h-[9px] w-[9px] rounded-full bg-neutral-300 dark:bg-neutral-500"></div>
                <p class="text-sm text-neutral-500 dark:text-neutral-300">
                  25.11.2021
                </p>
              </div>
              <div class="ml-4 mt-2 pb-5">
                <h4 class="mb-1.5 text-xl font-semibold">Title of section 10</h4>
                <p class="mb-3 text-neutral-500 dark:text-neutral-300">
                  Voluptatibus temporibus esse illum eum aspernatur, fugiat suscipit
                  natus! Eum corporis illum nihil officiis tempore. Excepturi illo
                  natus libero sit doloremque, laborum molestias rerum pariatur quam
                  ipsam necessitatibus incidunt, explicabo.
                </p>
              </div>
            </li>
          </ol>


          <ol class="border-l border-neutral-300 px-16 dark:border-neutral-500">
            <li>
              <div class="flex-start flex items-center pt-2">
                <div class="-ml-[5px] mr-3 h-[9px] w-[9px] rounded-full bg-neutral-300 dark:bg-neutral-500"></div>
                <p class="text-sm text-neutral-500 dark:text-neutral-300">
                  25.11.2021
                </p>
              </div>
              <div class="ml-4 mt-2 pb-5">
                <h4 class="mb-1.5 text-xl font-semibold">Title of section 11</h4>
                <p class="mb-3 text-neutral-500 dark:text-neutral-300">
                  Voluptatibus temporibus esse illum eum aspernatur, fugiat suscipit
                  natus! Eum corporis illum nihil officiis tempore. Excepturi illo
                  natus libero sit doloremque, laborum molestias rerum pariatur quam
                  ipsam necessitatibus incidunt, explicabo.
                </p>
              </div>
            </li>
          </ol>
          <br><br>
          
        </div>
        <!-- Detop Device End -->
      </div>
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
