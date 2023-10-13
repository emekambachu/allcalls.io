<script setup>
  import { Head, Link } from "@inertiajs/vue3";
  import  { onMounted, onBeforeUnmount, ref } from "vue";
  import PrimaryButton from "@/Components/PrimaryButton.vue";
  import ApplicationLogo from "@/Components/ApplicationLogo.vue";
  import Footer from "@/Components/Footer.vue";
  import ScrollAnimation from "@/Components/ScrollAnimation.vue";
  import DownloadAppModal from "@/Components/DownloadAppModal.vue";
  
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
  let appDownloadModal = ref(false)
  // Define a reactive property for v-show
  const showHamburger = ref(false);
  
  onMounted(() => {
  
    var video = document.querySelector("#demo");
    var fvideo = document.querySelector("#playButton");
    var vid = document.querySelector("#video");
    // var pvid = document.querySelector("#circle-play-b");
    var finalPlay = document.querySelector("#finalPaly");
  
  
  
    video.addEventListener("mousemove", mouseOver);
    video.addEventListener("mouseleave", mouseOut);
    vid.addEventListener("ended", function(){
      document.querySelector('#pauseButton').classList.add("displayNoneClass");
      document.querySelector('#pauseButton').classList.remove("displayFlexClass");
      document.querySelector('#finalPlay').classList.add("displayFlexClass");
      document.querySelector('#finalPlay').classList.remove("displayNoneClass");
    });
    vid.addEventListener("pause", function(){
        document.querySelector('#pauseButton').classList.add("displayNoneClass");
        document.querySelector('#pauseButton').classList.remove("displayFlexClass");
        document.querySelector('#finalPlay').classList.remove("displayNoneClass");
        document.querySelector('#finalPlay').classList.add("displayFlexClass");
    });
    vid.addEventListener("play", function(){
        document.querySelector('#finalPlay').classList.add("displayNoneClass");
        document.querySelector('#pauseButton').classList.remove("displayNoneClass");
        document.querySelector('#pauseButton').classList.add("displayFlexClass");
        document.querySelector('#finalPlay').classList.remove("displayFlexClass");
    });

    // pvid.addEventListener("click", playVid);
  
    fvideo.addEventListener("click", function() {
      
      setTimeout(() => {
        fvideo.classList.add("hided");
      }, 3000);
      if (vid.paused == true) {
        // Play the video
        vid.play();
        document.querySelector('#finalPlay').classList.add("displayNoneClass");
        document.querySelector('#pauseButton').classList.remove("displayNoneClass");
        document.querySelector('#pauseButton').classList.add("displayFlexClass");
        document.querySelector('#finalPlay').classList.remove("displayFlexClass");


      } else {
        // Pause the video
        vid.pause();
        document.querySelector('#pauseButton').classList.add("displayNoneClass");
        document.querySelector('#pauseButton').classList.remove("displayFlexClass");
        document.querySelector('#finalPlay').classList.remove("displayNoneClass");
        document.querySelector('#finalPlay').classList.add("displayFlexClass");

      }
    });
    
  var aaa
    function mouseOver() {
    //  let aaa = '';
     if(aaa){
      clearTimeout(aaa);
     } 
     aaa =  setTimeout(() => {
        fvideo.classList.add("hided");
      }, 3000);
      fvideo.classList.remove("hided");
      
    }
  
    function mouseOut() {
      fvideo.classList.add("hided");
    }
    function playVid() { 
      fvideo.classList.add("hided");
      vid.play(); 
    }
    // video.addEventListener("click", mouseOver);



    vid.addEventListener("touchend", function() {
      fvideo.classList.remove("hided");
    });
  
  });



  
  
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
                <a href="#getstarted" class="hover:text-custom-green"
                  ><li>Live Transfers</li></a
                >
                <a href="#solutions" class="hover:text-custom-green"
                  ><li>Why AllCalls.io</li></a
                >
                <a href="#resources" class="hover:text-custom-green"
                  ><li>How It Works</li></a
                >
                <a
                  href="https://www.financialservicecareers.com"
                  class="hover:text-custom-green"
                  ><li>Careers</li></a
                >
                <a href="/support" class="hover:text-custom-green"
                  ><li>Support</li></a
                >
                <a href="/register" class="hover:text-custom-green"
                  ><li>Sign Up Now</li></a
                >
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
            <a href="#getstarted"
              ><li
                class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white mb-2"
              >
                Live Transfers
              </li></a
            >
            <a href="#solutions"
              ><li
                class="py-1 rounded text-custom-blue border-radius bg-custom-blue hover:bg-custom-green hover: hover:cursor-pointer text-white mb-2"
              >
                Why AllCalls.io
              </li></a
            >
            <a href="#resources"
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
            <a href="/support"
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
  
          <div class="main__getstarted max-w-4xl">
            <div class="main__title mb-5">Your Next Sale is Just a Call Away</div>
            <div class="main__subtitle mb-12">
              Instantly Connect with new clients on demand.
              <span style="color: #3cfa7a"
                >Flexible, affordable, and tailored to your success.</span
              >
            </div>
            <div class="main__button">
              <a href="/register" class="w-max flex flex-col items-center">
                <div
                  class="inline-flex items-center px-4 py-3 bg-gray-100 border border-transparent rounded-md font-semibold text-md text-custom-green uppercase tracking-widest hover:bg-custom-blue hover:drop-shadow-2xl focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                >
                  <div
                    class="lg:text-3xl xl:text-4xl bg-clip-text text-md font-extrabold text-transparent bg-gradient-to-r from-blue-400 to-green-500"
                  >
                    BUY CALLS NOW
                  </div>
                  <img class="w-10 lg:w-20" src="/img/green-arrow.png" alt="" />
                </div>
                <div
                  class="text-sm lg:text-lg text-gray-400 drop-shadow-wxl mt-2"
                >
                  Starting as Low as $25 / Call
                </div>
              </a>
            </div>
          </div>
        </div>
        <div class="main__layer">
          <img src="/img/main-layer.png" alt="" />
        </div>
      </div>
    </section>
  
    <section id="solutions">
      <div class="container">
        <div class="secTwo">
          <div class="secTwo__title">
            We help businesses get connected with clients
          </div>
          <div class="secTwo__verticals">
            <div class="secTwo__verticals-i flex flex-col items-center gap-2">
              <img src="/img/auto.png" alt="" />
              <div class="text-sm font-bold text-gray-600 text-center">
                Auto Insurance
              </div>
            </div>
  
            <div class="secTwo__verticals-i flex flex-col items-center gap-2">
              <img src="/img/debt-relief.png" alt="" />
              <div class="text-sm font-bold text-gray-600 text-center">
                Life Insurance
              </div>
            </div>
  
            <div class="secTwo__verticals-i flex flex-col items-center gap-2">
              <img src="/img/health.png" alt="" />
              <div class="text-sm font-bold text-gray-600 text-center">
                Medicare
              </div>
            </div>
  
            <div class="secTwo__verticals-i flex flex-col items-center gap-2">
              <img src="/img/life.png" alt="" />
              <div class="text-sm font-bold text-gray-600 text-center">
                ACA Health
              </div>
            </div>
  
            <div
              class="secTwo__verticals-i last-i flex flex-col items-center gap-2"
            >
              <img src="/img/medicare.png" alt="" />
              <div class="text-sm font-bold text-gray-600 text-center">
                U65 Health
              </div>
            </div>
          </div>
  
          <div class="secTwo__why">
            <div class="secTwo__why-l">
              <div class="video-wrapper" id="demo">
                <div class="video-container" id="video-container">
                  <video @mouseover="mouseOver" controls id="video" preload="metadata">
                    <source src="/video/file.mp4" type="video/mp4">
                    <!-- <source src="//cdn.jsdelivr.net/npm/big-buck-bunny-1080p@0.0.6/video.mp4" type="video/mp4"> -->

                    
                  </video>
              
                  <div class="play-button-wrapper hided" id="playButton">
                    <div title="Play video" class="play-gif" id="circle-play-b">
                      <!-- SVG Play Button -->
                      <svg id="finalPlay" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80">
                        <path d="M40 0a40 40 0 1040 40A40 4000 0 0040 0zM26 61.56V18.44L64 40z" />
                      </svg>
                      <img id="pauseButton" class="svgClass displayNoneClass" src="/img/pause.png" alt="svg">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="secTwo__why-r">
              <div class="secTwo__title">Why Choose AllCalls.io</div>
              <div class="secTwo__para">
                Leave prospecting behind and let us do the heavy lifting for you
                and your business. Experience the Power of On-Demand Calls and
                start talking with potential clients Today!
              </div>
              <a href="/register">
                <PrimaryButton class="my-4">
                  <div class="text-custom-orange">See it in action</div>
                  <img class="w-10" src="/img/orange-arrow.png" alt="" />
                </PrimaryButton>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  
    <section class="wrap" id="company">
      <div class="container">
        <div class="secThree">
          <div class="secThree__subtitle">The AllCalls.io Lead Solution</div>
          <div class="secThree__title">
            Simplify your Sales with AllCalls.io Mobile App
          </div>
  
          <div class="secThree__layer">
            <img src="/img/second-layer.png" alt="" />
          </div>
        </div>
      </div>
    </section>
  
    <section>
      <div class="secFour" id="howItWorks">
        <div class="secFour__hold">
          <div class="secFour__grid wrap">
            <div class="secFour__img">
              <img src="/img/why-01.png" alt="" />
            </div>
            <div class="secFour__texts wrap">
              <div class="secFour__subtitle">Stay Connected</div>
              <div class="secFour__title">Talk To New Client's On-Demand</div>
              <div class="secFour__para">
                With AllCalls.io, you decide when to receive calls based on your
                schedule. Turn on the mobile app when you're ready to connect, and
                turn it off when you're busy. We give you the flexibility to
                manage your calls on your terms, ensuring you never miss an
                opportunity.
              </div>
              <a href="/register">
                <PrimaryButton class="my-4">
                  <div class="text-custom-orange">Try AllCalls.io Now</div>
                  <img class="w-10" src="/img/orange-arrow.png" alt="" />
                </PrimaryButton>
              </a>
            </div>
          </div>
        </div>
  
        <div class="secFour__hold">
          <div class="secFour__grid wrap">
            <div class="secFour__img">
              <img src="/img/why-02.png" alt="" />
            </div>
            <div class="secFour__texts wrap">
              <div class="secFour__subtitle">Boost Efficiency</div>
              <div class="secFour__title">Seamless Integration</div>
              <div class="secFour__para">
                AllCalls.io fits smoothly into your existing workflow. Our web and
                mobile apps ensure you can manage your calls from anywhere, on any
                device. Plus, with our intuitive interface and straightforward
                setup, you'll be up and running in no time.
              </div>
              <a href="/register">
                <PrimaryButton class="my-4">
                  <div class="text-custom-orange">Buy Live Transfers Today</div>
                  <img class="w-10" src="/img/orange-arrow.png" alt="" />
                </PrimaryButton>
              </a>
            </div>
          </div>
        </div>
  
        <div class="secFour__hold">
          <div class="secFour__grid wrap">
            <div class="secFour__img">
              <img src="/img/why-03.png" alt="" />
            </div>
            <div class="secFour__texts wrap">
              <div class="secFour__subtitle">Increase Revenue</div>
              <div class="secFour__title">More Calls = More Sales</div>
              <div class="secFour__para">
                At AllCalls.io, we believe quality calls shouldn't break the bank.
                And if we can put you in front of more potential clients for your
                money, we know we can help increase your sales. We offer
                competitive pricing to fit your companies needs and budget.
                Whether you're a small startup or a large enterprise, we have a
                solution for you!
              </div>
              <a href="/register">
                <PrimaryButton class="my-4">
                  <div class="text-custom-orange">Learn How to Get Started</div>
                  <img class="w-10" src="/img/orange-arrow.png" alt="" />
                </PrimaryButton>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  
    <section class="">
      <div class="w-full h-128 flex items-center sec__reg">
        <div class="w-[44%] reg__one">
          <img class="w-[80%] m-auto" src="/img/regulation.png" alt="" />
        </div>
        <div class="w-[56%] reg__two px-4 xl:px-14">
          <div class="reg__title drop-shadow-xl">TCPA and DNC Compliance</div>
          <div
            class="reg__subtitle text-3xl text-gray-500 font-bold drop-shadow-lg max-w-3xl"
          >
            At AllCalls.io we take Compliance very seriously. All of our calls
            have opted in to be called with Jornaya and Trusted Form verification
            to keep the highest TCPA compliance. We also scrub all of our callers
            against the national DNC list.
          </div>
        </div>
      </div>
    </section>
  
    <section id="resources">
      <div class="secFive">
        <div class="secFive__outer">
          <div class="secFive__row">
            <div class="secFive__inner gap-8">
              <div class="secFive__texts">
                <div
                  class="text-6xl font-semibold text-white mb-5 secFive__texts-t"
                >
                  Streamlined Process for On-Demand Calls
                </div>
                <ul
                  class="text-sm font-semibold text-custom-sky space-y-2 lg:text-lg"
                >
                  <li
                    class="w-full bg-white py-1 px-2 rounded drop-shadow-xl lg:bg-transparent lg:text-white"
                  >
                    1. Join & Sign Up for AllCalls.io
                  </li>
                  <li
                    class="w-full bg-white py-1 px-2 rounded drop-shadow-xl lg:bg-transparent lg:text-white"
                  >
                    2. Setup Schedule and Payment Info
                  </li>
                  <li
                    class="w-full bg-white py-1 px-2 rounded drop-shadow-xl lg:bg-transparent lg:text-white"
                  >
                    3. Download Mobile App and Start Taking Calls!
                  </li>
                </ul>
              </div>
              <div class="secFive__img flex flex-col items-center">
                <img
                  class="mb-5 drop-shadow-2xl xl:mr-10"
                  src="/img/streamlined.png"
                  alt=""
                />
                <a href="/register">
                  <PrimaryButton class="bg-gray-100">
                    <div class="font-semibold 2xl:text-4xl text-center">
                      Get Started Now
                    </div>
                    <img
                      src="/img/green-arrow.png"
                      class="w-10 2xl:w-20"
                      alt=""
                    />
                  </PrimaryButton>
                </a>
              </div>
            </div>
          </div>
        </div>
  
        <div class="secFive__grid">
          <div class="secFive__one">
            <div
              class="container"
              style="
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
              "
            >
              <div class="secFive__one-hold">
                <div class="secFive__subtitle">Start Receiving Calls Fast</div>
                <div class="secFive__title">
                  Our technology allows you to start taking calls in seconds
                </div>
                <div  @click="appDownloadModal = true">
                  <PrimaryButton class="my-4">
                    <div class="text-custom-sky">Download Our Mobile App</div>
                    <img class="w-10" src="/img/blue-arrow.png" alt="" />
                  </PrimaryButton>
                </div>
              </div>
            </div>
          </div>
          <DownloadAppModal :appDownloadModal="appDownloadModal" @close="appDownloadModal = false" />
          <!-- <div class="secFive__two">
            <div
              class="container"
              style="
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
              "
            >
              <div class="secFive__subtitle custom-white">Access my Account</div>
              <div class="secFive__title text-white">
                Log In and Start Speaking with Prospects Now
              </div>
              <a href="/login">
                <PrimaryButton class="my-4">
                  <div class="text-custom-green">Log In</div>
                  <img class="w-10" src="/img/green-arrow.png" alt="" />
                </PrimaryButton>
              </a>
            </div>
          </div> -->
        </div>
      </div>
    </section>
  
    <Footer />
  </template>
  
  <style>
  .bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
  }
  .video-container {
     max-width: 640px;
     border-radius: 4px;
     margin: 0 auto;
     position: relative;
     display: flex;
     flex-direction: column;
     justify-content: center;
     box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.4);
  }
   .video-container .video-wrapper {
     width: 100%;
     height: 100%;
     display: flex;
     justify-content: center;
     align-items: center;
  }
   .video-container video {
     width: 100%;
     height: 100%;
     border-radius: 4px;
  }
   .play-button-wrapper {
     position: absolute;
     top: 0;
     left: 0;
     right: 0;
     bottom: 0;
     display: flex;
     align-items: center;
     justify-content: center;
     width: 100%;
     height: auto;
     pointer-events: none;
  }
   .play-button-wrapper #circle-play-b {
     cursor: pointer;
     pointer-events: auto;
  }
   .play-button-wrapper #circle-play-b svg {
     width: 100px;
     height: 100px;
     fill: #fff;
     stroke: #fff;
     cursor: pointer;
     background-color: rgba(0, 0, 0, 0.2);
     border-radius: 50%;
     opacity: 0.9;
  }
  .hided{
    display: none;
  }
  .svgClass{
    max-width: 100px;
    margin-bottom: 90px;
  }
  .displayNoneClass{
    display: none !important;
  }
  .displayFlexClass{
    display: flex !important;
  }
  @media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
      background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
  }

   @media (max-width: 480px) {
      .displayFlexClass{
        max-width: 25px;
        max-height: 25px;
        margin-top:-25px;
      }
   }

   @media (min-width: 481px) and (max-width: 767px) {
      .displayFlexClass{
        max-width: 25px;
        max-height: 25px;
        margin-top:-25px;
      }
    }

   @media (min-width: 768px) and (max-width: 1024px){

    .displayFlexClass{
        max-width: 25px;
        max-height: 25px;
        margin-top:-25px;
      }
   }
  </style>
  