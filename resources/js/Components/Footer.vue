<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { onMounted } from "vue";

onMounted(() => {
  setTimeout(() => {
    document.querySelector(".widget-visible iframe").style.bottom = "70px";
  }, 1000);

  setTimeout(() => {
    document.querySelector(".widget-visible iframe").style.bottom = "70px";
  }, 2000);
});
</script>

<template>
  <footer class="wrap">
    <div class="container">
      <div class="footer__wrap">
        <div class="footer__logo">
          <!-- <img src="/img/logo.png" alt=""> -->
          <ApplicationLogo class="text-center" />
        </div>

        <div class="text-white flex justify-center text-center lg:text-left mt-8 lg:mt-0">
          <div>
            <div class="mb-4 text-lg uppercase tracking-wider">AllCalls.io</div>
            <div class="mb-4 text-gray-200">
              1309 COFFEEN AVE STE 11246<br />SHERIDAN, WY 82801, USA
            </div>
            <div class="mb-4 text-gray-200">(855) 815-0382</div>
            <div class="mb-4 text-gray-200">support@allcalls.io</div>
          </div>
        </div>
        <!-- <div class="footer__list">
            <ul>
              <li class="footer__label">Platform</li>
              <li>Overview</li>
              <li>Why All Calls</li>
              <li>How it works</li>
            </ul>

            <ul>
              <li class="footer__label">Use Cases</li>
              <li>Customer Services</li>
              <li>Scheduling</li>
              <li>Billing and payment</li>
            </ul>

            <ul>
              <li class="footer__label">Solutions</li>
              <li>Insurance</li>
              <li>Financial Service</li>
              <li>Healthcare</li>
            </ul>

            <ul>
              <li class="footer__label">Company</li>
              <li>About</li>
              <li>Careers</li>
              <li>Contact</li>
            </ul>
          </div> -->
      </div>
    </div>

    <div class="footer__copyrights text-lg">
	<h2>Get mobile updates on new resources releases and important information</h2>
<p>Text INSURED to 800-677-8036 signup for recurring marketing alerts. Message and data rates may apply.</p>

<p>By subscribing above, you confirm you are authorized to add the telephone number and hereby give consent to receive SMS/text messages from you at the telephone number. You understand that your consent is not required as a condition to purchase products or services. When you subscribe to “AllCalls.io” program by AllCalls LLC. Message & data rates may apply. You also accept our <a href="/terms.php">Terms and Conditions</a> and our <a href="/privacy.php"> Privacy Policy</a>. Message frequency will be up to 1 message per day. You may revoke future alerts and messages via the online portal or by texting “STOP” to 800-677-8036 opt out. For help text “HELP” to 800-677-8036. T-Mobile is not liable for delayed or undelivered messages</p>
	
	
      <div class="footer__text">
        Copyright © 2023 AllCalls.io, Inc, All Rights Reserved
      </div>

      <div class="footer__links">
        <a href="/support">Support</a>
        |
        <a href="/terms.php">Terms of Service</a>
        |
        <a href="/privacy.php"> Privacy Policy</a>
        |
        <a href="/eula.php"> EULA</a>
      </div>
    </div>
  </footer>
</template>

<style>
@media (max-width: 1020px) {
  .footer__wrap {
    flex-direction: column;
    gap: 20px;
  }

  .footer__logo img {
    margin: auto;
  }

  .footer__list ul {
    margin: auto;
  }

  .footer__list {
    gap: 25px;
    flex-wrap: wrap;
    text-align: center;
  }

  .footer__list ul li {
    margin-bottom: 12px;
    font-size: 14px;
  }

  .footer__copyrights {
    font-size: 14px;
    padding: 8px 24px;
    gap: 12px;
  }
}
</style>
