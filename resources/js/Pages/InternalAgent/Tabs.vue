<script setup>

const props = defineProps({
    step: String,
});
</script>
<style scoped>
.active-tab {
    color: #5f30e2;
    border-bottom: 0px solid #5f30e2;
}

.active-icon {
    color: #5f30e2;
}

.shadow-tab {
    box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
    border-radius: 5px;
}
</style>
<template>
    <div class="mb-4">
        <ul class="flex flex-wrap justify-center text-sm font-medium text-center text-gray-500 dark:text-gray-400">
            <li :class="{ 'shadow-tab': step === 1, }" class="mr-2  px-3 pb-2  my-2">
                <a href="#" :class="{ 'active-tab': step === 1, }"
                    class="inline-flex items-center justify-center py-2   border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg :class="{ 'active-icon w-6 h-6': step == 1, }" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                    </svg>
                    <div v-show="step === 1" class="flex flex-col items-start ml-2">
                        Step 1/6 <br> <span class="font-black">Contract Details</span>
                    </div>
                </a>
            </li>
            <li :class="{ 'shadow-tab': step === 2 }" class="mr-2  px-3 pb-2  my-2 ">
                <a href="#" :class="{ 'active-tab': step === 2 }"
                    class="inline-flex items-center justify-center py-2   border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg class="w-10 h-10" :class="{ 'active-icon w-6 h-6': step === 2, }"
                        xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision"
                        text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd"
                        clip-rule="evenodd" viewBox="0 0 512 348.04">
                        <path
                            d="M431.64 199.57c40.99-.32 74.49 32.64 74.82 73.64.32 41-32.64 74.5-73.64 74.83-41 .32-74.49-32.65-74.82-73.64-.33-41 32.64-74.5 73.64-74.83zM54.43 42.22h403.14c3.51 0 6.38 2.88 6.38 6.39v34.15c0 3.51-2.87 6.38-6.38 6.38H54.43c-3.51 0-6.38-2.87-6.38-6.38V48.61c0-3.52 2.87-6.39 6.38-6.39zM27.19 0h457.62c7.48 0 14.29 3.06 19.21 7.98 4.92 4.92 7.98 11.74 7.98 19.21v201.43a91.763 91.763 0 0 0-15.47-20.16l-.39-.37V27.19c0-3.1-1.28-5.94-3.34-7.99a11.273 11.273 0 0 0-7.99-3.34H27.19c-3.12 0-5.96 1.27-7.99 3.3l-.04.04c-2.03 2.03-3.3 4.87-3.3 7.99v262.66c0 3.11 1.28 5.94 3.34 8 2.05 2.05 4.89 3.33 7.99 3.33h317.54a90.949 90.949 0 0 0 6.65 15.86H27.19c-7.47 0-14.29-3.06-19.21-7.98C3.06 304.14 0 297.33 0 289.85V27.19c0-7.45 3.06-14.24 7.98-19.17l.04-.04C12.97 3.05 19.75 0 27.19 0zm115.49 204.93c8.07 6.96 31.12 4.88 39.74 9.68 2.74 1.53 5.23 3.49 7.22 6.12 4.94 6.52 5.32 8.91 7.21 16.88-.44 4.69-3.1 7.4-8.34 7.8H52.83c-5.24-.4-7.9-3.11-8.34-7.8 1.89-7.97 2.27-10.36 7.21-16.88 1.99-2.64 4.47-4.59 7.22-6.12 8.48-4.73 30.93-2.67 39.33-9.34 1.27-2.75 2.56-6.65 3.37-9.11.04-.13-.07.2.34-1.02.94-2.8-4.61-8.28-6.46-11.22l-6.88-10.95c-2.52-3.75-3.82-7.18-3.9-10-.04-1.32.18-2.52.67-3.58a6.66 6.66 0 0 1 2.37-2.75 8.62 8.62 0 0 1 1.66-.85c-.44-5.93-.61-10.44-.32-16.7.15-1.49.43-2.97.85-4.46 2.52-9 10.25-15.48 19.09-18.54 4.29-1.48 2.63-5.01 6.97-4.77 10.27.56 26.12 7.18 32.21 14.2 8.53 9.83 6.33 18.98 6.04 30.98 1.91.59 3.13 1.8 3.63 3.75.56 2.17-.04 5.23-1.88 9.41l-.01-.01c-.03.08-.07.15-.11.23l-7.84 12.91c-3.03 4.98-6.1 9.97-10.19 13.8-.2.19-.4.37-.61.55.6.83 1.29 1.85 2.03 2.93 1.07 1.57 2.24 3.29 3.4 4.86zm75.79-69.59c-3.65 0-6.61-3.95-6.61-8.81 0-4.87 2.96-8.82 6.61-8.82H349.4c3.65 0 6.61 3.95 6.61 8.82 0 4.86-2.96 8.81-6.61 8.81H218.47zm0 100.12c-3.65 0-6.61-3.94-6.61-8.81 0-4.87 2.96-8.81 6.61-8.81h141.14a91.256 91.256 0 0 0-10.66 17.62H218.47zm0-50.06c-3.65 0-6.61-3.95-6.61-8.81 0-4.87 2.96-8.82 6.61-8.82h197.17c3.65 0 6.61 3.95 6.61 8.82 0 4.86-2.96 8.81-6.61 8.81H218.47zM431.9 274a3.85 3.85 0 0 1 3.88 3.81c.01 2.13-1.69 3.86-3.81 3.87-2.12.02-3.86-1.69-3.87-3.8a3.835 3.835 0 0 1 3.8-3.88zm-.04-10.05c7.65-.07 13.91 6.09 13.97 13.74.06 7.66-6.09 13.91-13.74 13.98-7.66.05-13.91-6.1-13.97-13.75-.07-7.66 6.09-13.91 13.74-13.97zm-56.17-10.07c5.26-14.92 16.13-27.56 29.82-34.08 18.82-8.96 41.73-9.7 61.28 4.52 11.51 8.38 15.75 15.29 21.56 27.56 4.42 10.88.89 14.45-13.06 8.44-28.86-13.99-57.91-13.13-87.13.69-10.77 7.16-16.89 1.3-12.47-7.13zm74.47 77.31c20.02-6.48 31.64-19.36 38.7-35.72 4.58-12.64-10.12-16.79-15.19-8.45-3.04 6.14-6.66 11.41-11.43 15.51-7.43 6.43-18.28 7.67-19.87 19.69-.83 6.25.64 11.83 7.79 8.97zm-34.61.27c-20.12-6.16-31.95-18.85-39.27-35.09-4.77-12.56 9.85-16.95 15.06-8.7 3.13 6.09 6.84 11.3 11.67 15.34 7.54 6.3 18.4 7.38 20.19 19.36.92 6.24-.45 11.85-7.65 9.09z" />
                    </svg>
                    <div v-show="step === 2" class="flex flex-col items-start ml-2">
                        Step 2/6 <br> <span class="font-black"> Driver Licence</span>
                    </div>
                </a>
            </li>
            <li :class="{ 'shadow-tab': step === 3 }" class="mr-2  px-3 pb-2  my-2 ">
                <a href="#" :class="{ 'active-tab': step === 3 }"
                    class="inline-flex items-center justify-center py-2   border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg :class="{ 'active-icon w-6 h-6': step === 3, }" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <div v-show="step === 3" class="flex flex-col items-start ml-2">
                        Step 3/6 <br> <span class="font-black">AML Course</span>
                    </div>
                </a>
            </li>
            <li :class="{ 'shadow-tab': step === 4 }" class="mr-2  px-3 pb-2  my-2 ">
                <a href="#" :class="{ 'active-tab ': step === 4 }"
                    class="inline-flex items-center justify-center py-2   border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg :class="{ 'active-icon w-6 h-6': step === 4, }" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <div v-show="step === 4" :class="{ 'active-icon': step === 3, }" class="flex flex-col items-start ml-2">
                        Step 4/6 <br> <span class="font-black">Errors and Omissions Insurance</span>
                    </div>
                </a>
            </li>
            <li :class="{ 'shadow-tab': step === 5 }" class="mr-2  px-3 pb-2  my-2 ">
                <a href="#" :class="{ 'active-tab': step === 5 }"
                    class="inline-flex items-center justify-center py-2   border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg :class="{ 'active-icon w-6 h-6': step === 5, }" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 7.5h-.75A2.25 2.25 0 004.5 9.75v7.5a2.25 2.25 0 002.25 2.25h7.5a2.25 2.25 0 002.25-2.25v-7.5a2.25 2.25 0 00-2.25-2.25h-.75m0-3l-3-3m0 0l-3 3m3-3v11.25m6-2.25h.75a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25h-7.5a2.25 2.25 0 01-2.25-2.25v-.75" />
                    </svg>
                    <div v-show="step === 5" class="flex flex-col items-start ml-2">
                        Step 5/6 <br> <span class="font-black">Upload Resident License PDF</span>
                    </div>
                </a>
            </li>
            <li :class="{ 'shadow-tab': step === 6 }" class="mr-2  px-3 pb-2  my-2 ">
                <a href="#" :class="{ 'active-tab': step === 6 }"
                    class="inline-flex items-center justify-center py-2   border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg :class="{ 'active-icon w-6 h-6': step === 6, }" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>
                    <div v-show="step === 6" class="flex flex-col items-start ml-2">
                        Step 6/6 <br> <span class="font-black">Banking Information</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</template>