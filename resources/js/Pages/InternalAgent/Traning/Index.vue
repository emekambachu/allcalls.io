<script setup>
import { ref, reactive, defineEmits, onMounted, watch, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BasicTraining from "@/Pages/InternalAgent/Traning/BasicTraining.vue";
import BasicTrainingModal from "@/Pages/InternalAgent/Traning/BasicTrainingModal.vue";
import NewAgentTrainingModal from "@/Pages/InternalAgent/Traning/NewAgentTrainingModal.vue";
import "vue3-carousel/dist/carousel.css";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";
import { basicTrainingSteps } from "@/constants.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head ,usePage } from "@inertiajs/vue3";

let page = usePage();

let basicTrainingModal = ref(false);
let basisTraning = () => {
  basicTrainingModal.value = true;
};
let closeBasicTraining = () => {
  basicTrainingModal.value = false;
};
let step = ref(1);

onMounted(() => {});
let currentSlide = ref(0);
let videoData = ref(null);
let videoDataModal = ref(false);
let slideTo = (index, val) => {
  currentSlide.value = index;
  videoData.value = val;
  videoDataModal.value = true;
};
const handlePlay = (index) => {
  // Pause any other videos when a new video starts playing
  if (playingVideoIndex.value !== null && playingVideoIndex.value !== index) {
    const prevVideo = document.getElementById(`video_${playingVideoIndex.value}`);
    if (prevVideo) {
      prevVideo.pause();
    }
  }

  // Set the currently playing video index
  playingVideoIndex.value = index;
};
let autoplayTime = ref("2000");
let onMouseEnter = () => {
  autoplayTime.value = false;
};
let onMouseLeave = () => {
  autoplayTime.value = "2000";
};
let settings = ref({
  itemsToShow: 3,
  snapAlign: "center",
});
let breakpoints = ref({
  300: {
    itemsToShow: 1,
  },
  // 1024 and up
  1024: {
    itemsToShow: 3,
  },
});
const filteredTraining = computed(() => {
  return basicTrainingSteps.filter((basicTraining) => basicTraining.title !== "");
});
console.log("filteredTraining", filteredTraining);
let newAgentTrainingModal = ref(false)
let newAgentTraining = () => {
  newAgentTrainingModal.value = true
}
if(!page.props.auth.user.new_agent_call_scheduled && page.props.auth.user.basic_training){
  newAgentTrainingModal.value = true
}
</script>

<template>
  <Head title="Training" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Training
      </h2>
    </template>

    <div class="pt-14">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="px-4 sm:px-8 sm:rounded-lg">
          <div class="flex justify-between">
            <div>
              <h1 class="text-4xl text-custom-sky font-bold mb-6">
                Training
              </h1>
            </div>
            <div>
            </div>
          </div>
          <hr class="mb-4" />
        </div>
      </div>
    </div>

    <!-- <div class="mx-auto sm:px-4 lg:px-12">
      <button class="font-medium mb-3">
        <span class="text-blue-600 ml-4 dark:text-blue-500 hover:underline"
          >Basic Training</span
        >
      </button>
      <Carousel
        v-bind="settings"
        :autoplay="autoplayTime"
        :wrapAround="true"
        :breakpoints="breakpoints"
      >
        <Slide
          @mouseenter="onMouseEnter"
          @mouseleave="onMouseLeave"
          v-for="(basicTraining, index) in filteredTraining"
          :key="basicTraining.id"
        >
          <div class="thumbnail cursor-pointer" @click="slideTo(index, basicTraining)">
            <img class="thumbnail-img" :src="basicTraining.thumbnail" alt="Thumbnail" />
            <div class="svg-container">
              <svg
                version="1.1"
                id="Layer_1"
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 0 528 528"
                xml:space="preserve"
                fill="#239dfa"
                stroke="#239dfa"
              >
                <g id="SVGRepo_bgCarrier" stroke-width="0" />

                <g
                  id="SVGRepo_tracerCarrier"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />

                <g id="SVGRepo_iconCarrier">
                  <path
                    style="fill: #ffff2D2D2D"
                    d="M264,511.624c136.752,0,248-111.088,248-247.624S400.752,16.376,264,16.376S16,127.464,16,264 S127.248,511.624,264,511.624z M192.496,154.928l195.624,104.44L192.496,363.8V154.928z"
                  />
                  <path
                    style="fill: #ffff8AD5DD"
                    d="M264,527.624c145.568,0,264-118.256,264-263.624S409.568,0.376,264,0.376S0,118.632,0,264 S118.432,527.624,264,527.624z M264,16.376c136.752,0,248,111.088,248,247.624S400.752,511.624,264,511.624S16,400.536,16,264 S127.248,16.376,264,16.376z"
                  />
                  <polygon
                    style="fill: #ffff"
                    points="192.496,154.928 192.496,363.8 388.12,259.368 "
                  />
                </g>
              </svg>
            </div>
            <div>
              <p
                style="font-weight: 100; max-width: 100%"
                class="mt-2 mx-2 text-blue-600 dark:text-blue-500"
              >
                {{ basicTraining.id }}) {{ basicTraining.title }}
              </p>
            </div>
            <br />
            <br />
          </div>
        </Slide>
        <template #addons>
          <navigation />
        </template>
      </Carousel>
    </div> -->
    <div class="mx-auto sm:px-4 lg:px-12 text-center">
      <div class="grid lg:grid-cols-3 mb-2 md:grid-cols-2 sm:grid-cols-1 gap-0">
        <div
          style="position: relative"
          v-for="(basicTraining, index) in filteredTraining"
          :key="basicTraining.id"
          class="thumbnail cursor-pointer"
          @click="slideTo(index, basicTraining)"
        >
          <img class="thumbnail-img" :src="basicTraining.thumbnail" alt="Thumbnail" />
          <div class="svg-container">
            <svg
              version="1.1"
              id="Layer_1"
              xmlns="http://www.w3.org/2000/svg"
              xmlns:xlink="http://www.w3.org/1999/xlink"
              viewBox="0 0 528 528"
              xml:space="preserve"
              fill="#239dfa"
              stroke="#239dfa"
            >
              <g id="SVGRepo_bgCarrier" stroke-width="0" />

              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
              />

              <g id="SVGRepo_iconCarrier">
                <path
                  style="fill: #ffff2D2D2D"
                  d="M264,511.624c136.752,0,248-111.088,248-247.624S400.752,16.376,264,16.376S16,127.464,16,264 S127.248,511.624,264,511.624z M192.496,154.928l195.624,104.44L192.496,363.8V154.928z"
                />
                <path
                  style="fill: #ffff8AD5DD"
                  d="M264,527.624c145.568,0,264-118.256,264-263.624S409.568,0.376,264,0.376S0,118.632,0,264 S118.432,527.624,264,527.624z M264,16.376c136.752,0,248,111.088,248,247.624S400.752,511.624,264,511.624S16,400.536,16,264 S127.248,16.376,264,16.376z"
                />
                <polygon
                  style="fill: #ffff"
                  points="192.496,154.928 192.496,363.8 388.12,259.368 "
                />
              </g>
            </svg>
          </div>
          <div>
            <p
              style="font-weight: 100; max-width: 100%"
              class="mt-2 mx-2 text-blue-600 text-start dark:text-blue-500"
            >
              {{ basicTraining.id }}) {{ basicTraining.title }}
            </p>
          </div>
          <br />
          <br />
        </div>
      </div>
    </div>

    <!-- <hr class="mb-4 mt-4" />
        <div class="px-12">
            <a href="#" class="font-medium"><span class="text-blue-600 dark:text-blue-500 hover:underline">Intermediate
                    Training</span></a>
        </div>
        <hr class="mb-4 mt-4" />
        <div class="px-12">
            <a href="#" class="font-medium"><span class="text-blue-600 dark:text-blue-500 hover:underline">Advanced
                    Training</span></a>
        </div> -->
    <BasicTraining
      v-if="basicTrainingModal"
      :basicTrainingSteps="basicTrainingSteps"
      :unlocked="'unlocked'"
      @close="closeBasicTraining"
      :basicTrainingModal="basicTrainingModal"
    />
    <BasicTrainingModal
      v-if="videoDataModal"
      @close="videoDataModal = false"
      :videoData="videoData"
      :videoDataModal="videoDataModal"
    />
    <NewAgentTrainingModal v-if="newAgentTrainingModal" :newAgentTrainingModal="newAgentTrainingModal" @close="newAgentTrainingModal = false" />
  </AuthenticatedLayout>
</template>
<style scoped>
.carousel__slide {
  padding: 10px;
}

.carousel__viewport {
  perspective: 2000px;
}

.carousel__track {
  transform-style: preserve-3d;
}

.carousel__slide--sliding {
  transition: 0.5s;
}

.carousel__slide {
  opacity: 0.9;
  transform: rotateY(-20deg) scale(0.9);
}

.carousel__slide--active ~ .carousel__slide {
  transform: rotateY(20deg) scale(0.9);
}

.carousel__slide--prev {
  opacity: 1;
  transform: rotateY(-10deg) scale(0.95);
}

.carousel__slide--next {
  opacity: 1;
  transform: rotateY(10deg) scale(0.95);
}

.carousel__slide--active {
  opacity: 1;
  transform: rotateY(0) scale(1.4);
}

.thumbnail {
  position: relative;
}

.image-container {
  position: relative;
  text-align: center;
}

.thumbnail-img {
  width: 97%;
  height: 190px;
  display: inline-block;
  vertical-align: middle;
  border-radius: 10px;
}

.svg-container {
  position: absolute;
  top: 33%;
  left: 50%;
  transform: translate(-50%, -50%);
}
.center-svg {
  width: 50px;
  /* Adjust the width of the SVG */
  height: 50px;
  /* Adjust the height of the SVG */
}

.svg-container svg {
  width: 56px;
  height: 56px;
}
/* @media (min-width: 351px) and (max-width: 500px) {
  .thumbnail-img {
    max-width: 200px;
  }

  .svg-container svg {
    width: 40px;
    height: 40px;
  }

  .svg-container {
    position: absolute;
    top: 30%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
}
@media (min-width: 300px) and (max-width: 350px) {
  .thumbnail-img {
    max-width: 150px;
  }

  .svg-container svg {
    width: 40px;
    height: 40px;
  }

  .svg-container {
    position: absolute;
    top: 30%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
} */

@media only screen and (min-width: 320px) and (max-width: 767px) {
  .thumbnail-img {
    width: 100%;
    height: 80%;
  }
  .svg-container {
    position: absolute;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
}
</style>
