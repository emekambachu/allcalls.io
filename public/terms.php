<?php

$settings = (object) [
  'companyName' => 'AllCalls.io',
  'address' => '1309 Coffeen Ave STE 11246, Sheridan, WY 82801, USA',
  'pageTitle' => 'AllCalls.io',
  'email' => 'support@allcalls.io',
  'phone' => '(855) 815-0382',
  'descriptor' => 'ALLCALLS.IO'
];

$products = [
  (object) [
      'title' => 'Product A',
      'price' => '29.99'
  ],
  (object) [
      'title' => 'Product B',
      'price' => '49.99'
  ],
  // You can add more product objects as needed...
];

?>

<!DOCTYPE html>



<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


<style>
.sf-submit-loader-container {
	display: none;
	position: fixed;
	width: 100vw;
	height: 100vh;
	top: 0;
	left: 0;
	background: rgba(0, 0, 0, 0.2);
	z-index: 1000
}
.sf-submit-loader-content {
	margin: auto;
	text-align: center;
}
.sf-submit-loader-text {
	font-size: 26px;
	margin-bottom: 6px;
	color: rgba(0,0,0,0.52);
	letter-spacing: 1.2px;
}
.lds-dual-ring {
	display: inline-block;
	width: 64px;
	height: 64px;
}
.lds-dual-ring:after {
	content: " ";
	display: block;
	width: 46px;
	height: 46px;
	margin: 1px;
	border-radius: 50%;
	border: 5px solid #fff;
	border-color: #fff transparent #fff transparent;
	animation: lds-dual-ring 1.2s linear infinite;
}
@keyframes lds-dual-ring {
0% {
transform: rotate(0deg);
}
100% {
transform: rotate(360deg);
}
}
.sq-input {
	height: 40px;
	box-sizing: border-box;
	border: 1px solid rgba(0,0,0,0.4);
	background-color: white;
	display: inline-block;
	-webkit-transition: border-color .1s ease-in-out;
	-moz-transition: border-color .1s ease-in-out;
	-ms-transition: border-color .1s ease-in-out;
	transition: border-color .1s ease-in-out;
}
.sq-input--focus {
	border: 1px solid rgb(57, 142, 231);
}
.sq-input--error {
	border: 1px solid #E02F2F;
}
*, ::after, ::before {
	box-sizing: border-box;
}
.content-container {
	max-width: 800px;
	padding: 20px;
	margin: 20px auto;
	font-size: 1rem;
	font-family: Arial, Helvetica, sans-serif;
	line-height: 1.5;
}
</style>

</head>
<body cz-shortcut-listen="true" style="">
    
<div class="content-container">
  <h2><?php echo $settings->companyName; ?></h2>
  <p id="isgk"><?php echo $settings->address; ?></p>
  <h3 id="iiec"><?php echo $settings->pageTitle; ?> - Terms &amp; Conditions</h3>
<p>Last updated: 8/22/2023</p>
  <p id="iqvz">Customer Service: Email Us 24/7 at <a href="mailto:<?php echo $settings->email; ?>"><?php echo $settings->email; ?></a>, we
respond with frequently asked questions immediately and live operator response within 8-12 business hours. </p>
  <p id="if9zj">Customer Service Phone: <?php echo $settings->phone; ?>. Support Hours: 24 / 7 Monday - Sunday. By using, accessing or ordering products from this Website, you hereby agree to all of
the following terms.</p>
  <h4>Acceptance of Agreement</h4>
  <p id="iliw7">Your access to and use of our website (the 'Site') is subject to the following terms and conditions which may
be updated by us from time to time without notice to you. You may review the most current version of the
terms and conditions at any time by visiting the following website link here: <a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/terms.php">https://<?php echo $_SERVER['HTTP_HOST']; ?>/terms.php</a> 
as well as the Site's posted privacy policy found <a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/privacy.php" class="link">here</a>. By accessing and
browsing the Site, you accept and agree to, without limitation or qualification, the terms and conditions,
privacy policy, and acknowledge that any other agreements between you are superseded and of no force or
effect with respect to your access and use of the Site.</p>
  <h4>User Accounts</h4>
  <p>a. Users must be at least 18 years old to create an account.</p>
  <p>b. During account creation, we collect your First Name, Last Name, Email Address, Phone Number, Password, and State Insurance License Information.</p>
  <p>c. AllCalls.io reserves the right to verify the accuracy of the information provided by users.</p>
  <h4>Content Ownership</h4>
  <p>All user-generated content, such as reviews and comments, is owned by AllCalls.io. Redistribution or reuse of this content by other users or third parties is prohibited.</p>
  <h4>Prohibited Conduct</h4>
  <p id="ii5ma">Users are prohibited from engaging in hate speech, harassment, and spamming. Intentionally terminating a call to avoid payment or any attempts to cheat the system is strictly forbidden.</p>
  <h4>Termination of Service</h4>
  <p>AllCalls.io reserves the right to terminate or suspend a user's account for any reason, including but not limited to violations of these ToS or engagement in fraudulent activities.</p>
  <h4 id="i7tqm">Modifications to Services</h4>
  <p>We reserve the right at any time and from time to time to modify or discontinue, temporarily or permanently,
the Services (or any part thereof) with or without notice. You agree that we shall not be liable to you or
to any third party for any modification, suspension or discontinuance of the Services.</p>
<h4>Pricing and Billing</h4>
	<p>
	<ul>
		<li>a. Calls reaching a duration of 60 seconds or more will be billed. Internally licensed agents are billed at a fixed rate of $35 per call. Other users are billed based on a bidding system, with a minimum bid of $25. Users will pay $1 more than the next highest bid.</li>
		<li>b. The minimum deposit is $100. There is an autopay feature available. A mandatory 3% processing fee applies to all deposits.</li>
		<li>c. Funds never expire, and unused funds can be refunded, which may take up to 10 business days.</li>
	</ul>
	</p>
  <h4>Agreement To Arbitrate; Class Action Waiver</h4>
  <p>Any dispute or claim relating in any way to this agreement shall be resolved by binding arbitration
administered by the American Arbitration Association in accord with its Commercial Arbitration Rules (visit
www.adr.org or call 800-778-7879). The arbitrator shall have exclusive authority to resolve any dispute
relating to the interpretation, applicability, enforceability, or formation of this agreement. The
arbitrator shall not conduct arbitration as a class or representative action. The customer acknowledge that
this agreement is a transaction in interstate commerce governed by the Federal Arbitration Act. The customer
agrees to waive any right to pursue any dispute relating to this agreement in any class, private attorney
general, or other representative action.</p>
  <h4>No Medical Advice</h4>
  <p>The Site does not provide medical advice and is not engaged in rendering medical or professional services or
advice, and the information provided on the Site is not intended to replace medical advice offered by a
health care provider. We strongly recommend that you promptly consult a physician or professional health
care provider prior to use of any product. Neither the product nor the ingredients in the product have been
approved or endorsed by the FDA or any regulatory agency for treatment of obesity or to cause weight loss. </p>
  <h4>Limitation of Liability & Disclaimer</h4>
  <p>AllCalls.io does not guarantee the website or app's error-free operation or the validity of any information provided. We also don't guarantee successful sales from our leads or genuine interest from prospects on calls.</p><p>In no event shall AllCalls.io, its directors, employees, or affiliates be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation loss of profits, data, use, goodwill, or other intangible losses resulting from:
<ul>
<li>Your access to or use of or inability to access or use the Service.</li>
<li>Any unauthorized access to or use of our servers and/or any personal information stored therein.</li>
<li>Any bugs, viruses, or similar that may be transmitted to or through our Service by any third party.</li>
<li>Any content posted on our Service.</li>
<li>Any other matter related to the Service.</li>
</ul></p>
  <h4>Exclusion of Warranty</h4>
  <p>While we use reasonable efforts to include accurate and up-to-date information in the Site, we no warranties
or representations as to its accuracy. We assume no liability or responsibility for any errors or omissions
in the content of the Site. Without limiting the foregoing, everything on the Site is provided to you 'AS
IS' WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE OR NON-INFRINGEMENT. Please note that some
jurisdictions may not allow the exclusion of implied warranties, so some of the above exclusions may not
apply to you. Check your local laws for any restrictions or limitations regarding the exclusion of implied
warranties.</p>
  <h4>User Submissions</h4>
  <p>Any 'personally identifiable information' in electronic communications to the Site is governed by the Site's
Privacy Policy which is posted on the site.</p>
  <h4>Third-Party Links</h4>
  <p>Our Service may contain links to third-party websites or services that are not owned or controlled by AllCalls.io. We have no control over and assume no responsibility for the content, privacy policies, or practices of any third-party websites or services. We do not take any responsibility for any information
or claims that were made by other websites and encourage you to alert us if a website is making false claims
and linking to our site or product at <a href="mailto:<?php echo $settings->email; ?>"><?php echo $settings->email; ?></a></p>
  <h4>Intellectual Property</h4>
	<p>The content, layout, design, data, databases, and graphics on this website are protected by U.S. intellectual property laws. Unless expressly stated otherwise, no content may be copied, reproduced, republished, uploaded, posted, publicly displayed, encoded, translated, transmitted, or distributed in any way to any other computer, server, website, or other medium for publication or distribution without our prior written consent.</p>
  <h4>No Waiver</h4>
	<p>No waiver by AllCalls.io of any term or condition set out in these Terms shall be deemed a further or continuing waiver of such term or condition or a waiver of any other term or condition, and any failure by us to assert a right or provision under these Terms shall not constitute a waiver of such right or provision.</p>
  <h4>If any provision of these Terms is held to be invalid or unenforceable by a court, the remaining provisions of these Terms will remain in effect.</h4>
  <h4>Governing Law</h4>
  <p id="i9hoo">This agreement and its performance shall be governed by the laws of the state of Wyoming, United States of
America, without regard to its conflict of laws provisions. You consent and submit to the exclusive
jurisdiction of the state and federal courts located in Wyoming, in all questions and controversies arising out of your use of the Site and this
Agreement. To the extent allowed by applicable law, any claim or cause of action arising from or relating to
your access or use of the Site must be brought within two (2) years from the date on which such claim or
action arose or accrued.</p>
  <p>This purchase will appear on your credit card statement as "<?php echo $settings->descriptor; ?>".</p>
  <p>Product sold by <?php echo $settings->companyName; ?>.</p>
</div>
<div class="sf-submit-loader-container" style="background: rgba(0, 0, 0, .5);">
  <div class="sf-submit-loader-content">
<div class="sf-submit-loader-wrapper">
      <div class="lds-dual-ring"></div>
    </div>
</div>
</div>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
  var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
  (function(){
      var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
      s1.async=true;
      s1.src='https://embed.tawk.to/63ff89ea31ebfa0fe7f0186c/1gqf35ep4';
      s1.charset='UTF-8';
      s1.setAttribute('crossorigin','*');
      s0.parentNode.insertBefore(s1,s0);
  })();
</script>
<!--End of Tawk.to Script-->

</body></html>