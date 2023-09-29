<script setup>

const props = defineProps({
    step: String,
});
</script>
<style scoped>
.active-tab {
   color: #5f30e2;
   /* border-bottom: 2px solid #5f30e2; */
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
            <li :class="{ 'shadow-tab': step === 1,  }" class="mr-2  px-3 pb-2  my-2">
                <a href="#" :class="{ 'active-tab': step === 1,  }"
                    class="inline-flex items-center justify-center py-2   border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg :class="{ 'active-icon w-6 h-6': step == 1, }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                    </svg>

                    <div v-show="step === 1" class="flex flex-col items-start ml-2">
                        Step 1/6 <br> <span class="font-black">Contract Details</span>
                    </div>

                </a>
            </li>
            <li :class="{ 'shadow-tab': step === 2 }" class="mr-2  px-3 pb-2  my-2 ">
                <a href="#" :class="{ 'active-tab': step === 2 }"
                    class="inline-flex items-center justify-center py-2   border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg :class="{ 'active-icon w-6 h-6': step === 2, }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>

                    <div v-show="step === 2" class="flex flex-col items-start ml-2">
                        Step 2/6 <br> <span class="font-black">AML Course</span>
                    </div>

                </a>
            </li>
            <li :class="{ 'shadow-tab': step === 3 }" class="mr-2  px-3 pb-2  my-2 ">
                <a href="#" :class="{ 'active-tab ': step === 3 }"
                    class="inline-flex items-center justify-center py-2   border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg :class="{ 'active-icon w-6 h-6': step === 3, }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>

                    <div v-show="step === 3" :class="{ 'active-icon': step === 3, }" class="flex flex-col items-start ml-2">
                        Step 3/6 <br> <span class="font-black">Errors and Omissions Insurance</span>
                    </div>

                </a>
            </li>
            <li :class="{ 'shadow-tab': step === 4 }" class="mr-2  px-3 pb-2  my-2 ">
                <a href="#" :class="{ 'active-tab': step === 4 }"
                    class="inline-flex items-center justify-center py-2   border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg :class="{ 'active-icon w-6 h-6': step === 4, }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5h-.75A2.25 2.25 0 004.5 9.75v7.5a2.25 2.25 0 002.25 2.25h7.5a2.25 2.25 0 002.25-2.25v-7.5a2.25 2.25 0 00-2.25-2.25h-.75m0-3l-3-3m0 0l-3 3m3-3v11.25m6-2.25h.75a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25h-7.5a2.25 2.25 0 01-2.25-2.25v-.75" />
                    </svg>

                    <div v-show="step === 4" class="flex flex-col items-start ml-2">
                        Step 4/6 <br> <span class="font-black">Upload Resident License PDF</span>
                    </div>

                </a>
            </li>
            <li :class="{ 'shadow-tab': step === 5 }" class="mr-2  px-3 pb-2  my-2 ">
                <a href="#" :class="{ 'active-tab': step === 5 }"
                    class="inline-flex items-center justify-center py-2   border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg :class="{ 'active-icon w-6 h-6': step === 5, }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>

                    <div v-show="step === 5" class="flex flex-col items-start ml-2">
                        Step 5/6 <br> <span class="font-black">Banking Information</span>
                    </div>

                </a>
            </li>
            <!-- <li class="mr-2 shadow-tab px-3 pb-2  my-2 ">
                <a href="#" :class="{ 'active-tab': step == 6 }"
                    class="inline-flex items-center justify-center py-2  border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg :class="{ 'active-icon': step == 6, }"
                        class="w-8 h-8 mr-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                    </svg>
                    <div class="flex flex-col items-start ml-2">
                        Step 6/6 <br> <span class="font-black">Contact Detail</span>
                    </div>

                </a>
            </li> -->

        </ul>
    </div>
</template>