<script setup>
import { ref, reactive, watchEffect, onMounted, onUnmounted } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { toaster } from "@/helper.js";
import { Device } from "@twilio/voice-sdk";

let page = usePage();
let props = defineProps({
  callTypes: Array,
  onlineCallType: Object,
  onlineUsers: Array,
});

console.log('onlineUsers', props.onlineUsers);
console.log(props.callTypes);

let setupFlashMessages = () => {
  console.log(page.props.flash);
  if (page.props.flash.message) {
    toaster("success", page.props.flash.message);
  }

  if (page.props.errors.balance) {
    toaster("error", page.props.errors.balance);
  }
};
// let setOnlineCallType = () => {
//   callTypesWithToggles.value.map((type) => {
//     return { callType: type.callType, toggle: false };
//   });

//   for (let i = 0; i < callTypesWithToggles.value.length; i++) {
//     if (
//       props.onlineCallType &&
//       props.onlineCallType.call_type_id ===
//       callTypesWithToggles.value[i].callType.id
//     ) {
//       callTypesWithToggles.value[i].toggle = true;
//       return;
//     }
//   }
// };

let setOnlineCallType = () => {
  // This map operation still doesn't store the result, so it has no effect as before
  callTypesWithToggles.value.map((type) => {
    return { callType: type.callType, toggle: false };
  });

  // Iterate over each call type in callTypesWithToggles
  for (let i = 0; i < callTypesWithToggles.value.length; i++) {
    // Check each onlineUser to see if their call_type_id matches the current call type's id
    for (let user of props.onlineUsers) {
      if (user.call_type_id === callTypesWithToggles.value[i].callType.id) {
        // If a match is found, set the toggle to true and break out of the inner loop
        callTypesWithToggles.value[i].toggle = true;
        break; // Only breaks out of the inner loop, continues checking the next call type
      }
    }
  }
};


let callTypesWithToggles = ref(
  props.callTypes
    .filter((callType) => callType.selected)
    .map((callType) => {
      return { callType: callType, toggle: false, bidAmount: Number(callType.bidAmount), topBid: Number(callType.topBid), averageBid: Number(callType.averageBid) };
    })
);

let openedEditMenus = reactive([]);

let openEditMenu = (callTypeId) => {
  openedEditMenus.push(Number(callTypeId));
};

let closeEditMenu = (callTypeId) => {
  openedEditMenus.splice(openedEditMenus.indexOf(Number(callTypeId)), 1);
  router.visit('/web-api/bids/' + callTypeId, {
    method: "PATCH",
    data: {
      amount: callTypesWithToggles.value.find((callType) => callType.callType.id === callTypeId).bidAmount,
    },
  });
}

let cancelEditMenu = (callTypeId) => {
  openedEditMenus.splice(openedEditMenus.indexOf(Number(callTypeId)), 1);
}

let userCallTypesToggles = ref(
  props.callTypes
    .filter((callType) => callType.selected)
    .map((callType) => {
      return {};
    })
);

let toggled = (event, callType) => {
  console.log("toggled!");
  console.log(event.target.checked);

  if (event.target.checked) {
    // Add to the online users
    console.log("add");
    router.visit("/take-calls/online-users", {
      method: "POST",
      data: {
        call_type_id: callType.id,
      },
    });
  } else {
    console.log("remove");
    // Remove from the online users
    router.visit(`/take-calls/online-users/${callType.id}`, {
      method: "DELETE",
    });
  }
};
watchEffect(async () => {
  setOnlineCallType();
  setupFlashMessages();
});

/**  Outbound Call implementation starts **/
let showOutboundDialPad = ref(false);
let outboundTypedNumber = ref('');
let currentOutboundTone = ref(null);
let outboundDevice = ref(null);
let currentOutboundCall = ref(null);
let outboundCallStatus = ref(null);

// Twilio device setup for outbound
let setupOutboundTwilioDevice = () => {
  axios.get("/twilio-device-token").then((response) => {
    let token = response.data.token;
    // console.log("token is ", token);

    outboundDevice = new Device(token, {
      // Set Opus as our preferred codec. Opus generally performs better, requiring less bandwidth and
      // providing better audio quality in restrained network conditions. Opus will be default in 2.0.
      codecPreferences: ["opus", "pcmu"],
      // Use fake DTMF tones client-side. Real tones are still sent to the other end of the call,
      // but the client-side DTMF tones are fake. This prevents the local mic capturing the DTMF tone
      // a second time and sending the tone twice. This will be default in 2.0.
      // fakeLocalDTMF: true,
      // Use `enableRingingState` to enable the outboundDevice to emit the `ringing`
      // state. The TwiML backend also needs to have the attribute
      // `answerOnBridge` also set to true in the `Dial` verb. This option
      // changes the behavior of the SDK to consider a call `ringing` starting
      // from the connection to the TwiML backend to when the recipient of
      // the `Dial` verb answers.
      enableRingingState: true,
      // debug: true,
    });
    // console.log("outboundDeviceee", outboundDevice);

    outboundDevice.on("ready", function (outboundDevice) {
      console.log("Outbound Twilio.Device Ready!");
    });

    outboundDevice.on("registered", function () {
      console.log("Outbound REGISTERED!");
    });

    outboundDevice.on("error", function (error) {
      console.log("Outbound Twilio.Device Error: " + error.message);
    });

    // outboundDevice.on("connect", function (conn) {
    //   console.log("Outbound Successfully established call ! ");
    // });

    // outboundDevice.on("disconnect", function (conn) {
    //   console.log("Outbound Call should disconnect now.");
      // showRinging.value = false;
      // showOngoing.value = false;
      // hasSixtySecondsPassed.value = false;
      // showUpdateDispositionModal();
    // });

    // outboundDevice.on("cancel", function () {
    //   console.log("Incoming call was canceled");
    //   // Update the UI to hide the incoming call notification
    // });

    outboundDevice.register();
  });
};

let unregisterOutboundTwilioDevice = () => {
  if (outboundDevice) {
    outboundDevice.destroy(); // or device.unregister();
    console.log("Outbound Twilio.Device Unregistered!");
  }
};

// Method to play a tone
let playOutboundDialpadTone = async (digit) => {
  // Stop any currently playing tone first
  // stopOutboundDialpadTone();
  const fileName = dialPadToneMapping[digit] || digit;
  const encodedDigit = encodeURIComponent(fileName);
  const audioSrc = `/dialpad-tones/${encodedDigit}-sound.mp3`; // Adjust the path as needed
  currentOutboundTone.value = new Audio(audioSrc);

  // Check if there's an active connection
  // if (currentConnection.value) {
  //   currentConnection.value.sendDigits(digit);
  //   console.log("Active connection found, DTMF tones sent");
  // } else {
  //   console.error("No active connection to send DTMF.");
  // }

  try {
    await currentOutboundTone.value.play();
  } catch (error) {
    console.error("Audio play error:", error);
  }
};

const dialPadToneMapping = {
  "*": "star",
  "#": "hash",
  // Add any other special characters mappings here
};

let isAudioPlaying = (audio) => {
  return !audio.paused && !audio.ended && audio.currentTime > 0;
};

// Method to stop the tone
let stopOutboundDialpadTone = () => {
  if (currentOutboundTone.value && isAudioPlaying(currentOutboundTone.value)) {
    currentOutboundTone.value.pause();
    currentOutboundTone.value.currentTime = 0;
    currentOutboundTone.value = null;
  }
};

const appendOutboundNumber = (number) => {
  outboundTypedNumber.value += number;
};

const deleteOutboundNumber = () => {
  outboundTypedNumber.value = outboundTypedNumber.value.slice(0, -1);
};

const makeOutboundCall = async () => {
  try {
    console.log('Attempting to connect for an outbound call...');
    // Ensure phoneNumber is defined and correctly passed
    if (!outboundTypedNumber.value) {
      console.error('No phone number provided for the outbound call.');
      alert('No phone number provided');
      return; // Stop the function if no number is provided
    }
    const call = await outboundDevice.connect({
      params: {
        To: outboundTypedNumber.value
      }
    });
    currentOutboundCall.value = call;
    console.log('Dialing outbound number:', outboundTypedNumber.value);

    call.on('ringing', ()=> {
      outboundCallStatus.value = 'ringing';
      console.log('Outbound Ringing...'); 
    });

    call.on('connect', () => {
      outboundCallStatus.value = 'connected';
      console.log('Connection established with SID:', call.parameters.CallSid);
    });

    call.on('disconnect', () => {
      outboundCallStatus.value = '';
      console.log('Call disconnected for SID:', call.parameters.CallSid);
    });

    call.on('error', (error) => {
      console.error('Error during the call with SID:', call.parameters.CallSid, error.message);
    });

  } catch (error) {
    console.error('Failed to make an outbound call:', error);
  }
}

const hangupOutboundCall = () => {
  if (currentOutboundCall.value) {
    currentOutboundCall.disconnect();
    outboundCallStatus.value = '';
  }
}

/**  Outbound Call ends **/

onMounted(() => {
  setTimeout(() => {
    setupOutboundTwilioDevice();
  }, 5000);
});

onUnmounted(() => {
  unregisterOutboundTwilioDevice();
});
</script>

<template>
  <Head title="Take Calls" />
  <AuthenticatedLayout>

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Take Calls
      </h2>
    </template>

    <div class="pt-14 pb-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="text-4xl text-custom-sky font-bold mb-6">Take Calls</div>
          <hr class="mb-4" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2 px-4 sm:px-8">
            <div class="mt-5">
                  <h1 class="text-2xl font-bold mb-4 text-gray-700">Guidelines</h1>
                  <ul class="pl-2 max-w-md text-gray-500 list-disc list-inside">
                      <li>
                          Please keep in mind there is a 80-second qualifying timer before
                          you are charged for a call.
                      </li>
                      <li>
                          The client's name, state, and basic info is displayed on your call
                          immediately upon answering. Clients personal contact information
                          will appear after 80 seconds of being connected to the call.
                      </li>
                      <li>
                          If you turn your status to active and miss a call, you will be
                          billed a $5 missed call fee.
                      </li>
                      <li>Please make sure notifications are turned on for this app.</li>
                  </ul>

                  <!-- Outbound Call Starts -->
                  <button 
                    @click="showOutboundDialPad = !showOutboundDialPad" 
                    class="mt-8 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded
                  ">
                    Show Dialpad
                  </button>

                  <div v-if="showOutboundDialPad" class="fixed z-20 inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center p-4">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm">
                      <button
                        @click.prevent="hangupOutboundCall()"
                        v-if="outboundCallStatus !== '' && outboundCallStatus !== null"
                        class="bg-red-500 hover:bg-red-400 text-white rounded-full py-2 px-6 mx-auto"
                      >
                        Hang Up
                      </button>

                      <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Enter the number</h3>
                        <button @click="showOutboundDialPad = false" class="rounded p-1 hover:bg-gray-200">
                          <svg
                            class="h-6 w-6 text-gray-800"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"
                            />
                          </svg>
                        </button>
                      </div>
                      <div class="flex justify-center items-center mb-4">
                        <input
                          type="tel"
                          v-model="outboundTypedNumber"
                          class="form-control text-center text-xl border-b-2 border-gray-300 focus:outline-none focus:border-gray-500 w-full"
                          placeholder="+1 (555) 123-4567"
                        />
                      </div>
                      <div class="grid grid-cols-3 gap-4 mb-4">
                        <button
                          v-for="digit in [
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6',
                            '7',
                            '8',
                            '9',
                            '*',
                            '0',
                            '#',
                          ]"
                          :key="digit"
                          @click="appendOutboundNumber(digit)"
                          @mousedown="playOutboundDialpadTone(digit)"
                          @mouseup="stopOutboundDialpadTone"
                          @touchstart.prevent="playOutboundDialpadTone(digit)"
                          @touchend.prevent="stopOutboundDialpadTone"
                          class="flex justify-center items-center h-12 w-full bg-gray-200 rounded text-xl hover:bg-gray-300"
                        >
                          {{ digit }}
                        </button>
                      </div>
                      <div class="flex justify-between mt-4">
                        <button
                          @click="makeOutboundCall()"
                          class="flex justify-center items-center h-12 w-12 bg-green-500 rounded-full text-white hover:bg-green-600"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-6 h-6"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M20.25 3.75v4.5m0-4.5h-4.5m4.5 0-6 6m3 12c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 0 1 4.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 0 0-.38 1.21 12.035 12.035 0 0 0 7.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 0 1 1.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 0 1-2.25 2.25h-2.25Z"
                            />
                          </svg>
                        </button>
                        <button
                          @click="appendOutboundNumber('+')"
                          class="flex justify-center items-center h-12 w-12 bg-gray-300 rounded-full text-white hover:bg-gray-400"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-6 h-6"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 4.5v15m7.5-7.5h-15"
                            />
                          </svg>
                        </button>
                        <button
                          @click="deleteOutboundNumber"
                          class="flex justify-center items-center h-12 w-12 bg-red-300 rounded-full text-white hover:bg-red-600"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-6 h-6"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 9.75 14.25 12m0 0 2.25 2.25M14.25 12l2.25-2.25M14.25 12 12 14.25m-2.58 4.92-6.374-6.375a1.125 1.125 0 0 1 0-1.59L9.42 4.83c.21-.211.497-.33.795-.33H19.5a2.25 2.25 0 0 1 2.25 2.25v10.5a2.25 2.25 0 0 1-2.25 2.25h-9.284c-.298 0-.585-.119-.795-.33Z"
                            />
                          </svg>
                        </button>

                        <button
                          @click="hangupThirdPartyCall"
                          class="flex justify-center items-center h-12 w-12 bg-red-500 rounded-full text-white hover:bg-red-600"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-6 h-6"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M15.75 3.75 18 6m0 0 2.25 2.25M18 6l2.25-2.25M18 6l-2.25 2.25m1.5 13.5c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 0 1 4.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 0 0-.38 1.21 12.035 12.035 0 0 0 7.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 0 1 1.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 0 1-2.25 2.25h-2.25Z"
                            />
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                  <!-- Outbound Call Ends -->

                  <h1 v-if="page.props.auth.role === 'internal-agent'" class="text-2xl font-bold mb-4 text-gray-700 mt-6">Tools:</h1>
                  <div v-if="page.props.auth.role === 'internal-agent'">
                      <a href="https://insurancetoolkits.com/signup?paidAgency=lat4w7gAOHlVGSZJJONBUZKYLDQUAWKH2GB9fy4y"
                         target="_blank"
                         class="inline-flex items-center px-4 py-3 border border-transparent rounded-md font-semibold text-md uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 bg-custom-blue text-custom-green hover:bg-white hover:drop-shadow-2xl hover:text-custom-blue hover:ring-2 hover:ring-custom-sky focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 my-4">Underwriting
                          Toolkit</a>
                  </div>
              </div>

            <!-- List here -->
            <div class="mt-5">
                  <h1 class="text-2xl font-bold text-gray-700">Verticals</h1>
                  <!-- Call Types and Bids List -->
                  <ul>
                      <li v-for="callType in callTypesWithToggles" :key="callType.callType.id" class="py-3 sm:py-4">
                          <div class="flex justify-end items-center">
                              <div class="text-md text-gray-800">Turn calls on <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4 relative my-1" style="left: 35px">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-6 6m0 0l-6-6m6 6V9a6 6 0 0112 0v3" />
                              </svg>
                              </div>

                          </div>
                          <div class="flex items-center justify-between">
                              <!-- Call Type Title on the left -->
                              <p class="text-xl text-gray-900 font-medium" v-text="callType.callType.type"></p>

                              <!-- Toggle and Bids on the right -->
                              <div class="flex items-center space-x-4">
                                  <!-- Toggle Button -->
                                  <!-- <label
                                    class="relative inline-flex items-center cursor-pointer"
                                  >
                                    <input
                                      type="checkbox"
                                      v-model="callType.toggle"
                                      class="sr-only peer"
                                      @change="toggled($event, callType.callType)"
                                    />
                                    <div
                                      class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"
                                    ></div>
                                  </label> -->
                                  <label class="relative inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="callType.toggle" class="sr-only peer"
                                             @change="toggled($event, callType.callType)">
                                      <div
                                          class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                      </div>
                                  </label>

                              </div>
                          </div>

                          <div v-if="page.props.auth.role !== 'internal-agent'" class="flex items-center mt-2 mb-2">
                              <h4 class="text-gray-800 text-sm text-bold mr-1 font-medium">
                                  Your Bid:
                              </h4>
                              <div class="text-gray-600 hover:text-gray-800 cursor-pointer text-sm rounded-md flex">
                                  <div class="mr-2">${{ callType.bidAmount }}</div>

                                  <svg @click.prevent="openEditMenu(callType.callType.id)"
                                       v-if="!openedEditMenus.includes(callType.callType.id)" xmlns="http://www.w3.org/2000/svg" fill="none"
                                       viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                      <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                  </svg>
                              </div>
                          </div>

                          <div v-if="page.props.auth.role !== 'internal-agent' && openedEditMenus.includes(callType.callType.id)"
                               class="flex items-center mt-2 mb-2">
                              <input v-on:keyup.enter="closeEditMenu(callType.callType.id)" type="number"
                                     class="border-gray-400 rounded-lg font-xs mr-2 py-1 px-2 text-sm bg-sky" v-model="callType.bidAmount" />

                              <button class="bg-custom-sky hover:bg-custom-darksky text-white px-2 py-0.5 text-sm rounded"
                                      @click="closeEditMenu(callType.callType.id)">Save</button>
                              <button class="px-2 py-0.5 text-sm rounded" @click="cancelEditMenu(callType.callType.id)">Cancel</button>
                          </div>

                          <div v-if="page.props.auth.role !== 'internal-agent'" class="flex items-center mt-2 mb-2">
                              <h4 class="text-gray-800 text-sm text-bold mr-1 font-medium">
                                  Top Bid:
                              </h4>
                              <p class="text-gray-600 text-sm rounded-md">${{ callType.topBid }}</p>
                          </div>

                          <div v-if="page.props.auth.role !== 'internal-agent' && openedEditMenus.includes(callType.callType.id)"
                               class="flex items-center mt-2 mb-2">
                              <h4 class="text-gray-800 text-sm text-bold mr-1 font-medium">
                                  Average Bid:
                              </h4>
                              <p class="text-gray-600 text-sm rounded-md">${{ callType.averageBid }}</p>
                          </div>

                          <div v-if="page.props.auth.role !== 'internal-agent' && openedEditMenus.includes(callType.callType.id)"
                               class="flex items-center mt-2 mb-2">
                              <h4 class="text-gray-800 text-sm text-bold mr-1 font-medium">
                                  Minimum Bid:
                              </h4>
                              <p class="text-gray-600 text-sm rounded-md">$35</p>
                          </div>

                      </li>
                  </ul>
              </div>
        </div>

      </div>
    </div>

<!--    <section class="p-3">-->
<!--      -->
<!--    </section>-->

  </AuthenticatedLayout>
</template>
