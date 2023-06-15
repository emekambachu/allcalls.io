<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Footer from "@/Components/Footer.vue";

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
</script>

<template>
  <Head title="Welcome">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800;900&family=Nunito+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  </Head>

  <section id="getstarted">
    <div class="main">
      <div class="container">
        <header class="header">
          <div class="header__logo">
            <img src="/img/logo.png" alt="">
          </div>
          <nav class="header__nav">
            <ul>
              <li><a href="#getstarted">Get Started</a></li>
              <li><a href="#solutions">Solutions</a></li>
              <li><a href="#company">Company</a></li>
              <li><a href="#resources">Resources</a></li>
              <li><a href="#howItWorks">How it works</a></li>
            </ul>
          </nav>
          <a href="/login">
            <div class="header__button">Log In</div>
          </a>
        </header>
        <div class="main__getstarted max-w-4xl">
          <div class="main__title mb-5">Your Next Opportunity is Just a Call Away</div>
          <div class="main__subtitle mb-12">Instantly Connect with your prospects on demand.
            <span style="color: #3CFA7A;">Flexible, affordable, and tailored to your success.</span></div>
          <div class="main__button">
            <a href="#">
              <img src="/img/getstarted.png" alt="">
            </a>
          </div>
        </div>
      </div>
      <div class="main__layer">
        <img src="/img/main-layer.png" alt="">
      </div>
    </div>
  </section>


  <section id="solutions">
    <div class="container">
      <div class="secTwo">
        <div class="secTwo__title">We help businesses get connected with clients</div>
        <div class="secTwo__verticals">
          <div class="secTwo__verticals-i">
            <img src="/img/auto.png" alt="">
          </div>

          <div class="secTwo__verticals-i">
            <img src="/img/debt-relief.png" alt="">
          </div>

          <div class="secTwo__verticals-i">
            <img src="/img/health.png" alt="">
          </div>

          <div class="secTwo__verticals-i">
            <img src="/img/life.png" alt="">
          </div>

          <div class="secTwo__verticals-i">
            <img src="/img/medicare.png" alt="">
          </div>
        </div>

        <div class="secTwo__why">
          <div class="secTwo__why-l">
            <img src="/img/video-mask.png" alt="">
          </div>
          <div class="secTwo__why-r">
            <div class="secTwo__title">Why Choose AllCalls.io</div>
            <div class="secTwo__para">Leave prospecting behind and let us do the heavy lifting for you and your business. Experience the Power of On-Demand Calls and start talking with potential clients Today!</div>
            <div class="secTwo__button">
              <p>See it in action</p>
              <img src="/img/arrow.png" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <section class="wrap" id="company">
    <div class="container">
      <div class="secThree">
        <div class="secThree__subtitle">Why AllCalls.io</div>
        <div class="secThree__title">Simplify your Sales with AllCalls.io Mobile App</div>

        <div class="secThree__layer">
          <img src="/img/second-layer.png" alt="">
        </div>


      </div>
    </div>
  </section>

  
  <section>
      <div class="secFour" id="howItWorks">
        <div class="secFour__hold">
          <div class="secFour__grid wrap">
            <div class="secFour__img">
              <img src="/img/why-01.png" alt="">
            </div>
            <div class="secFour__texts wrap">
              <div class="secFour__subtitle">Stay Connected</div>
              <div class="secFour__title">Talk To New Client's On-Demand</div>
              <div class="secFour__para">With AllCalls.io, you decide when to receive calls based on your schedule. Turn on the mobile app when you're ready to connect, and turn it off when you're busy. We give you the flexibility to manage your calls on your terms, ensuring you never miss an opportunity.</div>
              <div class="secFour__button">Learn More</div>
            </div>
          </div>
        </div>

        <div class="secFour__hold">
          <div class="secFour__grid wrap">
            <div class="secFour__img">
              <img src="/img/why-02.png" alt="">
            </div>
            <div class="secFour__texts wrap">
              <div class="secFour__subtitle">Boost Efficiency</div>
              <div class="secFour__title">Seamless Integration</div>
              <div class="secFour__para">AllCalls.io fits smoothly into your existing workflow. Our web and mobile apps ensure you can manage your calls from anywhere, on any device. Plus, with our intuitive interface and straightforward setup, you'll be up and running in no time.</div>
              <div class="secFour__button">Learn More</div>
            </div>
          </div>
        </div>

        <div class="secFour__hold">
          <div class="secFour__grid wrap">
            <div class="secFour__img">
              <img src="/img/why-03.png" alt="">
            </div>
            <div class="secFour__texts wrap">
              <div class="secFour__subtitle">Increase Revenue</div>
              <div class="secFour__title">More Calls = More Sales</div>
              <div class="secFour__para">At AllCalls.io, we believe quality calls shouldn't break the bank. And if we can put you in front of more potential clients for your money, we know we can help increase your sales.  We offer competitive pricing to fit your companies needs and budget.  Whether you're a small startup or a large enterprise, we have a solution for you!</div>
              <div class="secFour__button">Learn More</div>
            </div>
          </div>
        </div>

      </div>
  </section>


  <section id="resources"> 
    <div class="secFive" >
      <div class="secFive__row">
        <div class="secFive__inner">
          <div class="secFive__texts">
            <div class="text-6xl font-semibold text-white mb-5">Streamlined Process for On-Demand Calls</div>
            <div class="text-lg font-semibold text-white">Getting started with AllCalls.io is as easy as 1-2-3. Create your account, download our app, and start managing your calls on your terms. With our user-friendly interface, you'll be up to speed in no time. Whether you're managing a busy schedule or want to ensure availability for crucial calls, AllCalls.io is designed with your needs in mind.</div>
          </div>
          <div class="secFive__img">
            <img class="mb-5" src="/img/artboard-123.png" alt="">
            <div class="font-semibold custom-white text-5xl text-center"><a href="#">Get Started Now</a></div>
          </div>
        </div>
      </div>

      <div class="secFive__grid">
        <div class="secFive__one">
          <div class="container">
            <div class="secFive__subtitle">Start Receiving Calls Fast</div>
            <div class="secFive__title">Our technology allows you start taking calls in seconds</div>
            <a href="#">
              <div class="secFive__button">
                <p class="custom-blue">Download Our Mobile App</p>
                <img src="/img/blue-arrow.png" alt="">
              </div>
            </a>
          </div>
        </div>

        <div class="secFive__two">
          <div class="container">
            <div class="secFive__subtitle custom-white">Access my Account</div>
            <div class="secFive__title text-white">Log In and Start Speaking with Prospects Now</div>
            <a href="/login">
              <div class="secFive__button">
                  <p class="custom-green">Log In</p>
                  <img src="/img/green-arrow.png" alt="">
              </div>
            </a>
          </div>
        </div>
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
