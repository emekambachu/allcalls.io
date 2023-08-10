<?php

$settings = (object) [
  'companyName' => 'AllCalls.io',
  'address' => 'Example address',
  'pageTitle' => 'AllCalls.io',
  'email' => 'support@allcalls.io',
  'phone' => '(123) 456-7890',
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
  <p id="iqvz">Customer Service: Email Us 24/7 at <a href="mailto:<?php echo $settings->email; ?>"><?php echo $settings->email; ?></a>, we
respond with frequently asked questions immediately and live operator response within 8-12 business hours. </p>
  <p id="if9zj">Customer Service Phone: <?php echo $settings->phone; ?>. Support Hours: 24 / 7 Monday - Sunday. By using, accessing or ordering products from this Website, you hereby agree to all of
the following terms.</p>
  <h4>Acceptance of Agreement</h4>
  <p id="iliw7">Your access to and use of our website (the 'Site') is subject to the following terms and conditions which may
be updated by us from time to time without notice to you. You may review the most current version of the
terms and conditions at any time by visiting the following website link here: https://<?php echo $_SERVER['HTTP_HOST']; ?> 
as well as the Site's posted privacy policy found <a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/privacy.php" class="link">here</a>. By accessing and
browsing the Site, you accept and agree to, without limitation or qualification, the terms and conditions,
privacy policy, and acknowledge that any other agreements between you are superseded and of no force or
effect with respect to your access and use of the Site.</p>
  <h4>Shipping of Product(s)</h4>
  <p>Your order will be processed and shipping should begin with USPS within about 1 business day. Shipment via USPS typically takes 3-5 business days to arrive from when your order ships out. If you feel you are experiencing any delay in the
delivery of your product, you may contact our customer care department at <?php echo $settings->phone; ?>.</p>
  <h4>Shipping and Handling Fee</h4>
  <p>Shipping is $0.00.</p>
  <h4>Returns and Refunds</h4>
  <p id="ii5ma">We will credit one returned unopened product per customer if the received package is post marked within 30 days of the original order date and included with a RMA number obtained from customer service. Refunds will
be issued to customer credit card within 3 business days. No returns are credited after 30 days of the
original order date. Any merchandise must be returned at the customer's expense and must have an RMA number
marked on it. No returns are credited if they are not accompanied by an RMA number. No refunds will be
issued for any used product. You may request an RMA and instructions on how to submit a return by emailing <a draggable="true" href="mailto:%20<?php echo $settings->email; ?>"><?php echo $settings->email; ?></a> or
calling <a draggable="true" href="tel: <?php echo $settings->phone; ?>" class="link"><?php echo $settings->phone; ?></a>. <br draggable="true">
<br draggable="true" id="iru8g-3-2-2">
Please send all returns to: <br draggable="true">
12924 Pierce Street, Pacoima CA 91331, USA</p>
  <h4>Did Not Receive and/or Lost Package</h4>
  <p>All products are expected to arrive to the shipping address originally typed into the web form on the product
webpage you ordered from within 7 business days after it ships. Your order will be processed and shipping should begin with USPS within about 1 business day. Shipment via USPS typically takes 3-5 business days to arrive from when your order ships out. For orders placed on a weekend date, packages will ship out the following Monday, assuming it is not a major holiday. If for some reason you do not receive the product
after seven (7) business days of ordering it is recommended to contact customer support by email at <a href="mailto:%20<?php echo $settings->email; ?>"><?php echo $settings->email; ?></a>. All
orders are shipped with delivery confirmation, if the order is shown as delivered we are to believe it has
been delivered unless you call to state otherwise. There will be no refunds for product claimed to be
undelivered and the package is shown as delivered with delivery confirmation obtained via the US Postal
Service. There may be times when shipments are delayed a few extra days.</p>

<?php foreach($products as $product): ?>
  <h4 id="id6hb">Terms of <?php echo $product->title; ?> Sale</h4>
  <div id="idnwd">By placing your order of <?php echo $settings->pageTitle; ?> <?php echo $product->title; ?> you will be charged the total price of $<?php echo $product->price; ?> + (free shipping) today.  Your order will be processed and shipping should begin with USPS within about 1 business day. Shipment via USPS typically takes 3-5 business days to arrive from when your order ships out. This purchase will appear on your credit card statement as "<?php echo $settings->descriptor; ?>".</div>
<?php endforeach; ?>
  
  <h4 id="i7tqm">Modifications to Services</h4>
  <p>We reserve the right at any time and from time to time to modify or discontinue, temporarily or permanently,
the Services (or any part thereof) with or without notice. You agree that we shall not be liable to you or
to any third party for any modification, suspension or discontinuance of the Services.</p>
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
  <h4>Product Information</h4>
  <p>The products advertised on this site have not been approved or cleared by a government regulatory body. You
should not construe anything on the Site as a promotion or solicitation for any product or service or for
the use of any product or service that is not authorized by the laws and regulations of the country where
you are located, including the United States.</p>
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
  <h4>Links From Other Sites</h4>
  <p>We have not reviewed all of the sites linked to this Site and are not responsible for the content of any
off-site pages or any other sites linked to the Site. We do not take any responsibility for any information
or claims that were made by other websites and encourage you to alert us if a website is making false claims
and linking to our site or product at <a href="mailto:<?php echo $settings->email; ?>"><?php echo $settings->email; ?></a></p>
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