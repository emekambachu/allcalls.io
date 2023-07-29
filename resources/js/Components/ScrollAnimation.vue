<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { onMounted, onBeforeUnmount, ref } from 'vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";

const sections = [
  {
    image: '/img/why-01.png',
    subtitle: 'Stay Connected',
    title: 'Talk To New Client\'s On-Demand',
    paragraph: 'With AllCalls.io, you decide when to receive calls based on your schedule. Turn on the mobile app when you\'re ready to connect, and turn it off when you\'re busy. We give you the flexibility to manage your calls on your terms, ensuring you never miss an opportunity.',
    buttonText: 'Try AllCalls.io Now',
  },
  {
    image: '/img/why-02.png',
    subtitle: 'Boost Efficiency',
    title: 'Seamless Integration',
    paragraph: 'AllCalls.io fits smoothly into your existing workflow. Our web and mobile apps ensure you can manage your calls from anywhere, on any device. Plus, with our intuitive interface and straightforward setup, you\'ll be up and running in no time.',
    buttonText: 'Buy Live Transfers Today',
  },
  {
    image: '/img/why-03.png',
    subtitle: 'Increase Revenue',
    title: 'More Calls = More Sales',
    paragraph: 'At AllCalls.io, we believe quality calls shouldn\'t break the bank. And if we can put you in front of more potential clients for your money, we know we can help increase your sales. We offer competitive pricing to fit your companies needs and budget. Whether you\'re a small startup or a large enterprise, we have a solution for you!',
    buttonText: 'Learn How to Get Started',
  },
];

const showSections = ref(Array(sections.length).fill(false));
const activeSection = ref(0);

const handleScroll = () => {
  const scrollPosition = window.scrollY;

  sections.forEach((_, index) => {
    const element = document.querySelector(`.section-${index + 1}`);
    if (element) {
      const rect = element.getBoundingClientRect();
      const isVisible = rect.top <= window.innerHeight / 2 && rect.bottom >= window.innerHeight / 2;
      showSections.value[index] = isVisible;
    }
  });

  activeSection.value = showSections.value.indexOf(true);
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onBeforeUnmount(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
  <div>
    <div v-for="(section, index) in sections" :key="index" :class="['secFour__hold', `section-${index + 1}`, { 'active': activeSection === index, 'animate': showSections[index] }]">      <div class="secFour__grid wrap">
        <div class="secFour__img">
          <img :src="section.image" alt="">
        </div>
        <div class="secFour__texts wrap">
          <div class="secFour__subtitle">{{ section.subtitle }}</div>
          <div class="secFour__title">{{ section.title }}</div>
          <div class="secFour__para">{{ section.paragraph }}</div>
          <a href="/register">
            <PrimaryButton class="my-4">
              <div class="text-custom-orange">{{ section.buttonText }}</div>
              <img class="w-10" src="/img/orange-arrow.png" alt="">
            </PrimaryButton>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
.animate {
  transition: transform 3s ease-in-out;
  transform: translateY(0);
}

.section-1.animate {
  transform: translateY(100%);
}

.section-2.animate {
  transform: translateY(100%);
}

.section-3.animate {
  transform: translateY(100%);
}
</style>