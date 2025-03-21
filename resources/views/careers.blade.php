<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers - AllCalls.io</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="https://656a16294c31011321a8129b--stupendous-trifle-525211.netlify.app/images/">
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="/careers_files/css2" rel="stylesheet">
    <link rel="stylesheet" href="/careers_files/style.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        async function validateForm(e) {
            e.preventDefault()
            clearErrors();
            changeButtonText('Please wait...')
            disableButton()
            var formData = new FormData(document.getElementById('joinUsForm'));
            console.log('formData', formData);
            var errors = validateFormData(formData);
            if (Object.keys(errors).length === 0) {
                try {
                    // Make API call
                    const apiResponse = await callYourApi(formData);

                    // Check if the API call was successful
                    if (apiResponse.success) {
                        changeButtonText('Apply Now')
                        enableButton()
                        // If successful, open Calendly
                        joinUsModal.classList.remove('exist');
                        calendlyModal.classList.add('exist');
                        document.querySelector('.calendly-inline-widget').scrollTo(0, 900);
                    } else {
                        // If the API call was not successful, handle errors
                        console.log('API Error:', apiResponse.errors);
                        changeButtonText('Apply Now')
                        enableButton()
                        displayErrors(apiResponse.errors);
                    }
                } catch (error) {
                    changeButtonText('Apply Now')
                    enableButton()
                    // Handle any unexpected errors during the API call
                    console.error('Unexpected Error:', error);
                }

                // joinUsModal.classList.remove('exist')
                // calendlyModal.classList.add('exist')
                // document.querySelector('.calendly-inline-widget').scrollTo(0, 900)
            } else {
                changeButtonText('Apply Now')
                enableButton()
                displayErrors(errors);
            }
        }

        function validateFormData(formData) {
            // Perform validation checks here
            var errors = {};

            if (formData.get('first_name') === '') {
                errors.first_name = 'First Name is required';
            }

            if (formData.get('last_name') === '') {
                errors.last_name = 'Last Name is required';
            }
            if (formData.get('phone') === '') {
                errors.phone = 'Phone is required';
            }
            if (formData.get('email') === '') {
                errors.email = 'Email is required';
            }
            if (formData.get('life_insurance') === null) {
                errors.life_insurance = 'Life Insurance is required';
            }

            // Add more validation checks as needed

            return errors;
        }


        async function callYourApi(formData) {
            try {
                // Use Axios for the API call
                const response = await axios.post('/web-api/careers', formData, {
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken(),
                    },
                });
                // Check if the response contains the expected data structure

                if (response.data.success) {
                    return response.data;
                    changeButtonText('Apply Now')
                    enableButton()
                } else {
                    throw new Error('Invalid API response structure');
                }
            } catch (error) {
                changeButtonText('Apply Now')
                enableButton()
                displayErrors(error.response.data.errors)
                return {
                    success: false,
                    errors: {
                        api: 'Failed to call the API',
                    },
                };
            }
        }

        function getCsrfToken() {
            return document.head.querySelector('meta[name="csrf-token"]').content;
        }

        function displayErrors(errors) {
            for (var fieldName in errors) {
                var errorMessage = errors[fieldName];
                var errorElement = document.getElementById('error_' + fieldName);

                if (errorElement) {
                    errorElement.textContent = errorMessage;
                }
            }
        }

        function clearErrors() {
            var errorElements = document.querySelectorAll('.carreer_input_error');
            errorElements.forEach(function(element) {
                element.textContent = '';
            });
        }

        function changeButtonText(newText) {
            // Change the text of the button by modifying the innerHTML of the span
            document.getElementById('joinUsModalSubmitBtn').innerHTML = '<span class="text">' + newText + '</span>';
        }

        function disableButton() {
            // Disable the button by setting the "disabled" attribute
            document.getElementById('joinUsModalSubmitBtn').setAttribute('disabled', true);
        }

        function enableButton() {
            // Enable the button by removing the "disabled" attribute
            document.getElementById('joinUsModalSubmitBtn').removeAttribute('disabled');
        }
    </script>
</head>
<style>
    .carreer_input_error {
        color: red;
    }
</style>

<body class="empower" style="" cz-shortcut-listen="true">
    <header class="glass-bg">
        <img class="logo pop-up loading-pop-up animat-popup" src="/careers_files/logo.svg">
        <nav>
            <a class="pop-up loading-pop-up d1 animat-popup" href="/">Home</a>
            <a class="pop-up loading-pop-up d2 animat-popup" href="/careers">Careers</a>
            <a class="pop-up loading-pop-up d3 animat-popup" href="/support">Support</a>
            <a class="pop-up loading-pop-up d4 animat-popup" href="#">Apply Now </a>
        </nav>
        <button class="join-us-btn lets-go-btn btn-1 btn pop-up loading-pop-up d6 animat-popup">
            <span class="text">LET’S GO</span>
            <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z"
                    fill="white"></path>
            </svg>
        </button>
        <div class="hamburger d1">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
    </header>



    <main>
        <div class="main-container">
            <div class="txt-block">
                <h1 class="pop-up loading-pop-up d7 animat-popup">Empower Your <br class="br-mobile"> <span
                        class="grand-gradient-phrase"><span class="gradient-phrase">Life Insurance </span><span
                            class="gradient-phrase">Career</span></span> With AllCalls.io</h1>
                <p class="pop-up loading-pop-up d8 animat-popup">Work Remotely, Earn Daily, Grow Exponentially</p>
                <button class="join-us-btn btn-2 btn pop-up loading-pop-up d9 animat-popup">
                    <span class="text">JOIN US NOW</span>
                    <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z"
                            fill="black"></path>
                    </svg>
                </button>
            </div>
            <div class="img-glass-block img-glass-block-sd pop-up loading-pop-up d10 animat-popup">
                <!-- <img class="main" src="images/carrer-main-img.jpg"> -->
                <video src="https://allcalls.io/video/file.mp4" autoplay="" muted="" playsinline=""
                    loop=""></video>
            </div>
        </div>
        <img class="main-bright-gradient  bright-gradient" src="/careers_files/empower-main-bright.svg">
    </main>
    <section class="why-join-us">
        <h2 class="pop-up scroll-pop-up animat-popup">why join us?</h2>
        <p class="our-Agents-p pop-up scroll-pop-up animat-popup">Our Agents are Partners, Not Employees!</p>
        <div class="box">
            <div class="content glass-bg">
                <div class="left side">
                    <div class="itm pop-up scroll-pop-up animat-popup">
                        <img class="check-box-icon" src="/careers_files/check-box-icon.svg">
                        <p>Work 100% Remotely</p>
                    </div>
                    <div class="itm pop-up scroll-pop-up animat-popup">
                        <img class="check-box-icon" src="/careers_files/check-box-icon.svg">
                        <p>On Demand Clients Brought <br> Right to You Through Our <br> Proprietary Call System</p>
                    </div>
                    <div class="itm pop-up scroll-pop-up animat-popup">
                        <img class="check-box-icon" src="/careers_files/check-box-icon.svg">
                        <p>Agent Lead Subsidy Program to Help Our Agents Grow</p>
                    </div>
                    <div class="itm pop-up scroll-pop-up animat-popup">
                        <img class="check-box-icon" src="/careers_files/check-box-icon.svg">
                        <p>Work When You Want, You Decide Your Schedule</p>
                    </div>
                </div>
                <div class="right side">
                    <div class="itm pop-up scroll-pop-up animat-popup">
                        <img class="check-box-icon" src="/careers_files/check-box-icon.svg">
                        <p>Servicing Our Clients With Over 20 A Rated Insurance Carriers</p>
                    </div>
                    <div class="itm pop-up scroll-pop-up animat-popup">
                        <img class="check-box-icon" src="/careers_files/check-box-icon.svg">
                        <p>Commissions Paid Daily</p>
                    </div>
                    <div class="itm pop-up scroll-pop-up animat-popup">
                        <img class="check-box-icon" src="/careers_files/check-box-icon.svg">
                        <p>Performance Base Promotion System</p>
                    </div>
                    <div class="itm pop-up scroll-pop-up animat-popup">
                        <img class="check-box-icon" src="/careers_files/check-box-icon.svg">
                        <p>Vested Renewals Day One</p>
                    </div>
                    <div class="itm pop-up scroll-pop-up animat-popup">
                        <img class="check-box-icon" src="/careers_files/check-box-icon.svg">
                        <p>Management/Agency Opportunities Available For the Right Individuals</p>
                    </div>
                </div>
            </div>
            <div class="gradient-on-layer"></div>
            <img class="left-bright-gradient bright-gradient " src="/careers_files/join-us-left-gradients.svg">
            <img class="middle-bright-gradients bright-gradient " src="/careers_files/join-us-middle-gradients.svg">
            <img class="right-bright-gradient bright-gradient " src="/careers_files/join-us-right-gradients.svg">
        </div>
        <button class="join-us-btn start-money-btn btn-2 btn pop-up scroll-pop-up animat-popup">
            <span class="text">START YOUR JOURNEY HERE</span>
            <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z"
                    fill="black"></path>
            </svg>
        </button>
    </section>

    <section class="Partnerships">
        <p class="a-rated-carrier-p pop-up scroll-pop-up animat-popup">20+ A-rated Carrier Partnerships and dozen of
            products</p>
        <div class="box">
            <img class="americo partner-logo pop-up scroll-pop-up animat-popup" src="/careers_files/americo.png">
            <img class="ameritas partner-logo pop-up scroll-pop-up d1 animat-popup" src="/careers_files/ameritas.png">
            <img class="mutual for-desktop partner-logo pop-up scroll-pop-up d2 animat-popup"
                src="/careers_files/mutual.png">
            <img class="mutual for-mobile partner-logo pop-up scroll-pop-up animat-popup"
                src="/careers_files/mutual-for-mobile.png">
            <img class="AIG partner-logo pop-up scroll-pop-up d3 animat-popup" src="/careers_files/AIG.png">
            <img class="americam-amicable partner-logo pop-up scroll-pop-up d4 animat-popup"
                src="/careers_files/americam-amicable.png">
            <img class="american-equity partner-logo pop-up scroll-pop-up d5 animat-popup"
                src="/careers_files/American.png">
            <img class="columbian-life-Insurance partner-logo pop-up scroll-pop-up d6 animat-popup"
                src="/careers_files/ColumbianLifeInsuranceCompany.png">
            <img class="athene partner-logo pop-up scroll-pop-up animat-popup" src="/careers_files/athene.png">
            <img class="colombus-life partner-logo pop-up scroll-pop-up d1 animat-popup"
                src="/careers_files/colombus-life.png">
            <img class="assurant partner-logo pop-up scroll-pop-up d2 animat-popup" src="/careers_files/assurant.png">
            <img class="north-american partner-logo pop-up scroll-pop-up d3 animat-popup"
                src="/careers_files/north-american.png">
            <img class="jh partner-logo pop-up scroll-pop-up d4 animat-popup"
                src="/careers_files/jh_white_logo_2x.png">
            <img class="foresters partner-logo pop-up scroll-pop-up d5 animat-popup"
                src="/careers_files/foresters-logo-en-white 1.png">
            <img class="transamerica partner-logo pop-up scroll-pop-up d6 animat-popup"
                src="/careers_files/transamerica.png">
            <img class="oxford partner-logo pop-up scroll-pop-up animat-popup" src="/careers_files/oxford.png">
            <img class="national-life partner-logo pop-up scroll-pop-up d1 animat-popup"
                src="/careers_files/national.png">
            <img class="occidental partner-logo pop-up scroll-pop-up d2 animat-popup"
                src="/careers_files/Occidental.png">
            <img class="CVS partner-logo pop-up scroll-pop-up d3 animat-popup" src="/careers_files/cvs.png">
            <img class="gerber-life-insurance partner-logo pop-up scroll-pop-up d4 animat-popup"
                src="/careers_files/Gerber-Life-Insurance-2.png">
            <img class="GPM partner-logo pop-up scroll-pop-up d5 animat-popup" src="/careers_files/GPM.png">
            <img class="etohs partner-logo pop-up scroll-pop-up d6 animat-popup" src="/careers_files/Ethos.png">
        </div>
        <img class="under-top-stars-gradients bright-gradient" src="/careers_files/under-top-stars-gradients.svg">
        <img class="top-stars bright-gradient " src="/careers_files/top-stars.svg">
        <img class="elipse-top" src="/careers_files/elipse-top.svg">
    </section>


    <section class="leads-marketing">
        <h2 class="pop-up scroll-pop-up animat-popup">Leads and Marketing</h2>
        <div class="img-glass-block pop-up scroll-pop-up animat-popup">
            <img class="img" src="/careers_files/leads-marketing-main-img.jpg">
        </div>
        <div class="boxes">
            <div class="box">
                <img class="icon pop-up scroll-pop-up animat-popup" src="/careers_files/Live-Transfer-Calls-icon.svg">
                <h4 class="pop-up scroll-pop-up animat-popup">Live Transfer Calls</h4>
                <p class="pop-up scroll-pop-up animat-popup">No outbound dialing!
                    Our agents connect to customers looking for life insurance through our live transfer call program
                </p>
            </div>
            <div class="box">
                <img class="icon pop-up scroll-pop-up d1 animat-popup" src="/careers_files/On-Demand-Access-icon.svg">
                <h4 class="pop-up scroll-pop-up d1 animat-popup">On-Demand Access</h4>
                <p class="pop-up scroll-pop-up d1 animat-popup">Agents are able to log into their web portal or mobile
                    app and make themselves available to take calls
                    on demand.</p>
            </div>
            <div class="box">
                <img class="icon pop-up scroll-pop-up d2 animat-popup" src="/careers_files/Lead-Subsidy-icon.svg">
                <h4 class="pop-up scroll-pop-up d2 animat-popup">Lead Subsidy</h4>
                <p class="pop-up scroll-pop-up d2 animat-popup">We offer a lead subsidy for all of our agents to help
                    them increase their ROI for their business.</p>
            </div>
        </div>
        <button class="join-us-btn click-upply-btn btn-2 btn pop-up scroll-pop-up animat-popup">
            <span class="text">CLICK TO APPLY</span>
            <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z"
                    fill="black"></path>
            </svg>
        </button>
        <img class="lead-marketing-gradients bright-gradient" src="/careers_files/lead-marketing-gradients.svg">
        <img class="lead-marketing-gradients-middle-mobile for-mobile bright-gradient"
            src="/careers_files/lead-marketing-gradients-middle-mobile.svg">
        <img class="lead-marketing-gradients-bottom-mobile for-mobile bright-gradient"
            src="/careers_files/lead-marketing-gradients-bottom-mobile.svg">
    </section>

    <section class="training-onboarding">
        <h2 class="pop-up scroll-pop-up animat-popup">Training and Onboarding</h2>
        <div class="boxes">
            <div class="box img-glass-block pop-up scroll-pop-up animat-popup">
                <img class="desc-img" src="/careers_files/Training-Onboarding-img-1.jpg">
                <p>Free membership to insurance toolkits quoting tool</p>
                <img class="training-onboarding-gradients-top-mobile bright-gradient bright-gradient-mobile"
                    src="/careers_files/training-onboarding-gradients-top-mobile.svg">
            </div>
            <div class="box img-glass-block pop-up scroll-pop-up d1 animat-popup">
                <img class="desc-img" src="/careers_files/Training-Onboarding-img-2.jpg">
                <p>All of our training and resources are free</p>
                <img class="training-onboarding-gradients-middle-mobile bright-gradient bright-gradient-mobile"
                    src="/careers_files/training-onboarding-gradients-middle-mobile.svg">
            </div>
            <div class="box img-glass-block pop-up scroll-pop-up d2 animat-popup">
                <img class="desc-img" src="/careers_files/Training-Onboarding-img-3.jpg">
                <p>After your onboarding, you'll be able to schedule some one-on-one time with our team to help you get
                    started</p>
                <img class="training-onboarding-gradients-bottom-mobile bright-gradient bright-gradient-mobile"
                    src="/careers_files/training-onboarding-gradients-bottom-mobile.svg">
            </div>
        </div>
        <button class="join-us-btn get-started-btn btn-2 btn pop-up scroll-pop-up animat-popup">
            <span class="text">GET STARTED NOW</span>
            <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z"
                    fill="black"></path>
            </svg>
        </button>
        <img class="training-onboarding-gradients-left bright-gradient bright-gradient-desktop"
            src="/careers_files/Training-Onboarding-gradients-left.svg">
        <img class="training-onboarding-gradients-middle bright-gradient bright-gradient-desktop"
            src="/careers_files/Training-Onboarding-gradients-middle.svg">
        <img class="training-onboarding-gradients-right bright-gradient bright-gradient-desktop"
            src="/careers_files/Training-Onboarding-gradients-right.svg">
    </section>


    <section class="ready-unlock">
        <img class="elipse-bottom" src="/careers_files/elipse-bottom.svg">
        <h2 class="pop-up scroll-pop-up animat-popup">Ready to unlock new opportunities?</h2>
        <p class="main-p pop-up scroll-pop-up animat-popup">Start your journey as a life insurance broker with us!</p>
        <div class="btns">
            <button class="join-us-btn btn-2 btn pop-up scroll-pop-up animat-popup">
                <span class="text">JOIN US NOW</span>
                <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z"
                        fill="black"></path>
                </svg>
            </button>
            <a href="https://www.financialservicecareers.com/unlicensedstart"
                class="get-licenced-btn btn-2 btn pop-up scroll-pop-up d1 animat-popup">
                <span class="text">GET LICENSED</span>
                <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z"
                        fill="black"></path>
                </svg>
            </a>
        </div>
        <img class="ready-unlock-gradients bright-gradient" src="/careers_files/ready-unlock-gradients.svg">
        <img class="ready-unlock-stars bright-gradient" src="/careers_files/ready-unlock-gradients-stars.svg">
    </section>

    <div class="join-us-modal modal">
        <h3>Apply to Be An AllCalls Insurance Agent</h3>
        <form onsubmit="return validateForm(event);" id="joinUsForm"
            action="https://656a16294c31011321a8129b--stupendous-trifle-525211.netlify.app/">
            <input class="type-input" name="first_name" type="text" placeholder="First Name">
            <span class="carreer_input_error" id="error_first_name"></span>

            <input class="type-input" name="last_name" type="text" placeholder="Last Name">
            <span class="carreer_input_error" id="error_last_name"></span>

            <input class="type-input" name="email" type="email" placeholder="Email">
            <span class="carreer_input_error" id="error_email"></span>

            <input class="type-input" name="phone" type="tel" placeholder="Phone Number">
            <span class="carreer_input_error" id="error_phone"></span>

            <label for="">Are You Licensed for Life Insurance?</label>
            <div class="input-radio-block">
                <input class="radio-input" type="radio" value="Yes" id="life_insurance" name="life_insurance"><span class="desc"><label for="life_insurance">Yes</label> </span>
            </div>
            <div class="input-radio-block">
                <input class="radio-input" type="radio" value="No" id="life_insurance2" name="life_insurance"><span class="desc"><label for="life_insurance2">No</label>  </span>
            </div>
            <span class="carreer_input_error" id="error_life_insurance"></span>
            {{-- <button id="joinUsModalSubmitBtn" class="join-us-btn submit-btn btn-2 btn">
                <span class="text">Apply Now</span>
            </button> --}}
            <button id="joinUsModalSubmitBtn" class="  btn-2 btn">
                <span class="text">Apply Now</span>
            </button>

        </form>
        <div class="close-btn">
            <span class="line"></span>
            <span class="line"></span>
        </div>
    </div>

    <div class="calendly-modal modal">
        <h3>Schedule a Call With Our Team!</h3>
        <!-- Calendly inline widget begin -->
        <div class="calendly-inline-widget"
            data-url="https://calendly.com/d/4c2-5rz-64m/life-insurance-telesales-position"
            style="min-width:320px;height:700px;"></div>
        <script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>
        <!-- Calendly inline widget end -->
    </div>


    <footer>
        <div class="content">
            <div class="top">
                <img class="footer-logo pop-up scroll-pop-up" src="/careers_files/footer-logo.svg">
                <div class="subscreption">
                    <button class="log-in pop-up btn scroll-pop-up d1">Log In</button>
                    <button class="sign-up-btn btn-1 btn pop-up scroll-pop-up d2">
                        <span class="text">Sign Up Now</span>
                        <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z"
                                fill="white"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="links">
                <a class="pop-up scroll-pop-up" href="/">Home</a>
                <a class="pop-up scroll-pop-up d1" href="/careers">Careers</a>
                <a class="pop-up scroll-pop-up d2" href="/support">Support</a>
                <a class="pop-up scroll-pop-up d3" href="#">Apply Now </a>
            </div>
            <span class="seperator"></span>
            <div class="bottom">
                <span class="copy-right pop-up scroll-pop-up">Copyright © 2023 AllCalls.io, Inc, All Rights
                    Reserved</span>
                <div class="further-links">
                    <a class="pop-up scroll-pop-up d1" href="/support">Support</a>
                    <a class="pop-up scroll-pop-up d2" href="/terms.php">Terms of Service</a>
                    <a class="pop-up scroll-pop-up d3" href="/privacy.php">Privacy Policy</a>
                    <a class="pop-up scroll-pop-up d3" href="/eula.php">EULA</a>
                </div>
                <div class="contact">
                    <div class="itm pop-up scroll-pop-up d5">
                        <img class="icon" src="/careers_files/sms-icon.svg">
                        <span class="txt">support@allcalls.io</span>
                    </div>
                    <div class="itm pop-up scroll-pop-up d5">
                        <img class="icon" src="/careers_files/location-icon.svg">
                        <span class="txt">1309 COFFEEN AVE STE 11246SHERIDAN, WY 82801, USA</span>
                    </div>
                    <div class="itm pop-up scroll-pop-up d5">
                        <img class="icon" src="/careers_files/call-icon.svg">
                        <span class="txt">(855) 815-0382</span>
                    </div>
                </div>
            </div>

        </div>
    </footer>
    <script src="/careers_files/main.js"></script>


    <div id="yt_article_summary_widget_wrapper" class="yt_article_summary_widget_wrapper" style="display: none;">
        <div id="yt_article_summary_widget" class="yt_article_summary_widget"><svg style="filter: brightness(0.8);"
                width="32" height="32" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <mask id="path-1-outside-1_3606_3145" maskUnits="userSpaceOnUse" x="1" y="1" width="22"
                    height="22" fill="black">
                    <rect fill="white" x="1" y="1" width="22" height="22"></rect>
                    <path
                        d="M20.6816 10.1843C20.9588 9.34066 21.0063 8.4399 20.8192 7.57245C20.6321 6.70499 20.217 5.90134 19.6157 5.24216C19.0143 4.58298 18.2478 4.09146 17.393 3.81692C16.5382 3.54238 15.6253 3.49449 14.7459 3.67805C14.1453 3.01747 13.379 2.52468 12.524 2.24931C11.669 1.97394 10.7555 1.92571 9.87559 2.10947C8.99568 2.29324 8.18039 2.70252 7.51181 3.29608C6.84323 3.88965 6.34499 4.64654 6.06725 5.49055C5.18642 5.67292 4.3699 6.08122 3.70003 6.67426C3.03017 7.26731 2.53064 8.02413 2.25182 8.86842C1.97299 9.71271 1.92474 10.6146 2.11192 11.4832C2.2991 12.3517 2.71509 13.1562 3.31795 13.8155C3.09309 14.4899 3.01633 15.2037 3.09278 15.9095C3.16924 16.6154 3.39716 17.2971 3.76139 17.9093C4.30169 18.8351 5.12567 19.568 6.11483 20.0027C7.104 20.4373 8.20738 20.5512 9.26631 20.328C9.74353 20.8568 10.3291 21.2796 10.9844 21.5684C11.6396 21.8571 12.3495 22.0053 13.0672 22.003C14.1516 22.003 15.2081 21.6635 16.0847 21.0334C16.9612 20.4034 17.6125 19.5152 17.9449 18.4968C18.649 18.3539 19.3141 18.0649 19.8962 17.6489C20.4784 17.233 20.9642 16.6997 21.3214 16.0843C21.8585 15.1598 22.0858 14.0915 21.9709 13.032C21.856 11.9724 21.4048 10.9758 20.6816 10.1843ZM13.0798 20.6968C12.191 20.6968 11.3302 20.3894 10.6473 19.828L10.7677 19.7593L14.8029 17.4593C14.9069 17.4047 14.9935 17.3225 15.0528 17.2221C15.1121 17.1216 15.1418 17.0068 15.1386 16.8905V11.2655L16.8427 12.2405C16.8517 12.2441 16.8594 12.2501 16.865 12.2579C16.8706 12.2656 16.8739 12.2748 16.8744 12.2843V16.9343C16.876 17.4289 16.7785 17.9189 16.5875 18.3761C16.3964 18.8333 16.1156 19.2488 15.7611 19.5985C15.4067 19.9482 14.9856 20.2253 14.5222 20.4138C14.0588 20.6023 13.5621 20.6984 13.0608 20.6968H13.0798ZM4.90165 17.2593C4.46164 16.5029 4.3026 15.6189 4.45188 14.7593L4.57224 14.828L8.60749 17.128C8.70379 17.1829 8.81303 17.2118 8.92423 17.2118C9.03543 17.2118 9.14467 17.1829 9.24097 17.128L14.1758 14.3218V16.253C14.1797 16.2608 14.1817 16.2694 14.1817 16.278C14.1817 16.2867 14.1797 16.2953 14.1758 16.303L10.0962 18.628C9.66403 18.8748 9.18685 19.0352 8.69188 19.0999C8.19692 19.1647 7.69387 19.1326 7.21148 19.0055C6.72909 18.8784 6.27681 18.6587 5.88048 18.3591C5.48415 18.0595 5.15154 17.6858 4.90165 17.2593ZM3.83741 8.5843C4.28764 7.82089 4.99655 7.23878 5.83919 6.94055V11.6718C5.83595 11.7857 5.86434 11.8983 5.92128 11.9975C5.97823 12.0966 6.06156 12.1785 6.16227 12.2343L11.0717 15.028L9.36766 16.003C9.34918 16.0092 9.32914 16.0092 9.31065 16.003L5.23106 13.678C4.36041 13.1812 3.72487 12.3642 3.46364 11.4059C3.20242 10.4476 3.33682 9.42624 3.83741 8.56555V8.5843ZM17.8563 11.7968L12.9278 8.9718L14.6319 8.00305C14.6403 7.99741 14.6502 7.99439 14.6604 7.99439C14.6705 7.99439 14.6805 7.99741 14.6889 8.00305L18.7685 10.328C19.3915 10.684 19.8992 11.2072 20.2325 11.8368C20.5659 12.4664 20.7111 13.1764 20.6514 13.8843C20.5916 14.5921 20.3294 15.2687 19.8951 15.8352C19.4608 16.4017 18.8724 16.8349 18.1983 17.0843V12.353C18.1946 12.2391 18.1612 12.1281 18.1013 12.0306C18.0414 11.9332 17.957 11.8527 17.8563 11.7968ZM19.554 9.2968L19.4336 9.2218L15.4047 6.9093C15.3047 6.84846 15.1896 6.81624 15.0721 6.81624C14.9547 6.81624 14.8395 6.84846 14.7396 6.9093L9.8111 9.71555V7.75305C9.8061 7.7445 9.80346 7.7348 9.80346 7.72492C9.80346 7.71505 9.8061 7.70535 9.8111 7.6968L13.897 5.37805C14.5222 5.02257 15.2371 4.85003 15.958 4.88059C16.6789 4.91115 17.3762 5.14356 17.9682 5.55064C18.5601 5.95772 19.0225 6.52265 19.301 7.17939C19.5796 7.83614 19.663 8.55755 19.5413 9.2593L19.554 9.2968ZM8.87989 12.7218L7.1695 11.753C7.15339 11.7405 7.1422 11.7228 7.13782 11.703V7.06555C7.13785 6.35289 7.34371 5.65499 7.73128 5.0536C8.11885 4.45222 8.67209 3.97224 9.32619 3.6699C9.98029 3.36756 10.7082 3.25537 11.4246 3.34647C12.141 3.43757 12.8162 3.7282 13.3712 4.1843L13.2636 4.25305L9.21563 6.55305C9.11158 6.60765 9.02504 6.68981 8.96573 6.79029C8.90642 6.89076 8.87669 7.00557 8.87989 7.1218V12.7218ZM9.80476 10.753L11.9966 9.50305L14.1948 10.753V13.253L11.9966 14.503L9.79843 13.253L9.80476 10.753Z">
                    </path>
                </mask>
                <path
                    d="M20.6816 10.1843C20.9588 9.34066 21.0063 8.4399 20.8192 7.57245C20.6321 6.70499 20.217 5.90134 19.6157 5.24216C19.0143 4.58298 18.2478 4.09146 17.393 3.81692C16.5382 3.54238 15.6253 3.49449 14.7459 3.67805C14.1453 3.01747 13.379 2.52468 12.524 2.24931C11.669 1.97394 10.7555 1.92571 9.87559 2.10947C8.99568 2.29324 8.18039 2.70252 7.51181 3.29608C6.84323 3.88965 6.34499 4.64654 6.06725 5.49055C5.18642 5.67292 4.3699 6.08122 3.70003 6.67426C3.03017 7.26731 2.53064 8.02413 2.25182 8.86842C1.97299 9.71271 1.92474 10.6146 2.11192 11.4832C2.2991 12.3517 2.71509 13.1562 3.31795 13.8155C3.09309 14.4899 3.01633 15.2037 3.09278 15.9095C3.16924 16.6154 3.39716 17.2971 3.76139 17.9093C4.30169 18.8351 5.12567 19.568 6.11483 20.0027C7.104 20.4373 8.20738 20.5512 9.26631 20.328C9.74353 20.8568 10.3291 21.2796 10.9844 21.5684C11.6396 21.8571 12.3495 22.0053 13.0672 22.003C14.1516 22.003 15.2081 21.6635 16.0847 21.0334C16.9612 20.4034 17.6125 19.5152 17.9449 18.4968C18.649 18.3539 19.3141 18.0649 19.8962 17.6489C20.4784 17.233 20.9642 16.6997 21.3214 16.0843C21.8585 15.1598 22.0858 14.0915 21.9709 13.032C21.856 11.9724 21.4048 10.9758 20.6816 10.1843ZM13.0798 20.6968C12.191 20.6968 11.3302 20.3894 10.6473 19.828L10.7677 19.7593L14.8029 17.4593C14.9069 17.4047 14.9935 17.3225 15.0528 17.2221C15.1121 17.1216 15.1418 17.0068 15.1386 16.8905V11.2655L16.8427 12.2405C16.8517 12.2441 16.8594 12.2501 16.865 12.2579C16.8706 12.2656 16.8739 12.2748 16.8744 12.2843V16.9343C16.876 17.4289 16.7785 17.9189 16.5875 18.3761C16.3964 18.8333 16.1156 19.2488 15.7611 19.5985C15.4067 19.9482 14.9856 20.2253 14.5222 20.4138C14.0588 20.6023 13.5621 20.6984 13.0608 20.6968H13.0798ZM4.90165 17.2593C4.46164 16.5029 4.3026 15.6189 4.45188 14.7593L4.57224 14.828L8.60749 17.128C8.70379 17.1829 8.81303 17.2118 8.92423 17.2118C9.03543 17.2118 9.14467 17.1829 9.24097 17.128L14.1758 14.3218V16.253C14.1797 16.2608 14.1817 16.2694 14.1817 16.278C14.1817 16.2867 14.1797 16.2953 14.1758 16.303L10.0962 18.628C9.66403 18.8748 9.18685 19.0352 8.69188 19.0999C8.19692 19.1647 7.69387 19.1326 7.21148 19.0055C6.72909 18.8784 6.27681 18.6587 5.88048 18.3591C5.48415 18.0595 5.15154 17.6858 4.90165 17.2593ZM3.83741 8.5843C4.28764 7.82089 4.99655 7.23878 5.83919 6.94055V11.6718C5.83595 11.7857 5.86434 11.8983 5.92128 11.9975C5.97823 12.0966 6.06156 12.1785 6.16227 12.2343L11.0717 15.028L9.36766 16.003C9.34918 16.0092 9.32914 16.0092 9.31065 16.003L5.23106 13.678C4.36041 13.1812 3.72487 12.3642 3.46364 11.4059C3.20242 10.4476 3.33682 9.42624 3.83741 8.56555V8.5843ZM17.8563 11.7968L12.9278 8.9718L14.6319 8.00305C14.6403 7.99741 14.6502 7.99439 14.6604 7.99439C14.6705 7.99439 14.6805 7.99741 14.6889 8.00305L18.7685 10.328C19.3915 10.684 19.8992 11.2072 20.2325 11.8368C20.5659 12.4664 20.7111 13.1764 20.6514 13.8843C20.5916 14.5921 20.3294 15.2687 19.8951 15.8352C19.4608 16.4017 18.8724 16.8349 18.1983 17.0843V12.353C18.1946 12.2391 18.1612 12.1281 18.1013 12.0306C18.0414 11.9332 17.957 11.8527 17.8563 11.7968ZM19.554 9.2968L19.4336 9.2218L15.4047 6.9093C15.3047 6.84846 15.1896 6.81624 15.0721 6.81624C14.9547 6.81624 14.8395 6.84846 14.7396 6.9093L9.8111 9.71555V7.75305C9.8061 7.7445 9.80346 7.7348 9.80346 7.72492C9.80346 7.71505 9.8061 7.70535 9.8111 7.6968L13.897 5.37805C14.5222 5.02257 15.2371 4.85003 15.958 4.88059C16.6789 4.91115 17.3762 5.14356 17.9682 5.55064C18.5601 5.95772 19.0225 6.52265 19.301 7.17939C19.5796 7.83614 19.663 8.55755 19.5413 9.2593L19.554 9.2968ZM8.87989 12.7218L7.1695 11.753C7.15339 11.7405 7.1422 11.7228 7.13782 11.703V7.06555C7.13785 6.35289 7.34371 5.65499 7.73128 5.0536C8.11885 4.45222 8.67209 3.97224 9.32619 3.6699C9.98029 3.36756 10.7082 3.25537 11.4246 3.34647C12.141 3.43757 12.8162 3.7282 13.3712 4.1843L13.2636 4.25305L9.21563 6.55305C9.11158 6.60765 9.02504 6.68981 8.96573 6.79029C8.90642 6.89076 8.87669 7.00557 8.87989 7.1218V12.7218ZM9.80476 10.753L11.9966 9.50305L14.1948 10.753V13.253L11.9966 14.503L9.79843 13.253L9.80476 10.753Z"
                    fill="#828282"></path>
                <path
                    d="M20.6816 10.1843C20.9588 9.34066 21.0063 8.4399 20.8192 7.57245C20.6321 6.70499 20.217 5.90134 19.6157 5.24216C19.0143 4.58298 18.2478 4.09146 17.393 3.81692C16.5382 3.54238 15.6253 3.49449 14.7459 3.67805C14.1453 3.01747 13.379 2.52468 12.524 2.24931C11.669 1.97394 10.7555 1.92571 9.87559 2.10947C8.99568 2.29324 8.18039 2.70252 7.51181 3.29608C6.84323 3.88965 6.34499 4.64654 6.06725 5.49055C5.18642 5.67292 4.3699 6.08122 3.70003 6.67426C3.03017 7.26731 2.53064 8.02413 2.25182 8.86842C1.97299 9.71271 1.92474 10.6146 2.11192 11.4832C2.2991 12.3517 2.71509 13.1562 3.31795 13.8155C3.09309 14.4899 3.01633 15.2037 3.09278 15.9095C3.16924 16.6154 3.39716 17.2971 3.76139 17.9093C4.30169 18.8351 5.12567 19.568 6.11483 20.0027C7.104 20.4373 8.20738 20.5512 9.26631 20.328C9.74353 20.8568 10.3291 21.2796 10.9844 21.5684C11.6396 21.8571 12.3495 22.0053 13.0672 22.003C14.1516 22.003 15.2081 21.6635 16.0847 21.0334C16.9612 20.4034 17.6125 19.5152 17.9449 18.4968C18.649 18.3539 19.3141 18.0649 19.8962 17.6489C20.4784 17.233 20.9642 16.6997 21.3214 16.0843C21.8585 15.1598 22.0858 14.0915 21.9709 13.032C21.856 11.9724 21.4048 10.9758 20.6816 10.1843ZM13.0798 20.6968C12.191 20.6968 11.3302 20.3894 10.6473 19.828L10.7677 19.7593L14.8029 17.4593C14.9069 17.4047 14.9935 17.3225 15.0528 17.2221C15.1121 17.1216 15.1418 17.0068 15.1386 16.8905V11.2655L16.8427 12.2405C16.8517 12.2441 16.8594 12.2501 16.865 12.2579C16.8706 12.2656 16.8739 12.2748 16.8744 12.2843V16.9343C16.876 17.4289 16.7785 17.9189 16.5875 18.3761C16.3964 18.8333 16.1156 19.2488 15.7611 19.5985C15.4067 19.9482 14.9856 20.2253 14.5222 20.4138C14.0588 20.6023 13.5621 20.6984 13.0608 20.6968H13.0798ZM4.90165 17.2593C4.46164 16.5029 4.3026 15.6189 4.45188 14.7593L4.57224 14.828L8.60749 17.128C8.70379 17.1829 8.81303 17.2118 8.92423 17.2118C9.03543 17.2118 9.14467 17.1829 9.24097 17.128L14.1758 14.3218V16.253C14.1797 16.2608 14.1817 16.2694 14.1817 16.278C14.1817 16.2867 14.1797 16.2953 14.1758 16.303L10.0962 18.628C9.66403 18.8748 9.18685 19.0352 8.69188 19.0999C8.19692 19.1647 7.69387 19.1326 7.21148 19.0055C6.72909 18.8784 6.27681 18.6587 5.88048 18.3591C5.48415 18.0595 5.15154 17.6858 4.90165 17.2593ZM3.83741 8.5843C4.28764 7.82089 4.99655 7.23878 5.83919 6.94055V11.6718C5.83595 11.7857 5.86434 11.8983 5.92128 11.9975C5.97823 12.0966 6.06156 12.1785 6.16227 12.2343L11.0717 15.028L9.36766 16.003C9.34918 16.0092 9.32914 16.0092 9.31065 16.003L5.23106 13.678C4.36041 13.1812 3.72487 12.3642 3.46364 11.4059C3.20242 10.4476 3.33682 9.42624 3.83741 8.56555V8.5843ZM17.8563 11.7968L12.9278 8.9718L14.6319 8.00305C14.6403 7.99741 14.6502 7.99439 14.6604 7.99439C14.6705 7.99439 14.6805 7.99741 14.6889 8.00305L18.7685 10.328C19.3915 10.684 19.8992 11.2072 20.2325 11.8368C20.5659 12.4664 20.7111 13.1764 20.6514 13.8843C20.5916 14.5921 20.3294 15.2687 19.8951 15.8352C19.4608 16.4017 18.8724 16.8349 18.1983 17.0843V12.353C18.1946 12.2391 18.1612 12.1281 18.1013 12.0306C18.0414 11.9332 17.957 11.8527 17.8563 11.7968ZM19.554 9.2968L19.4336 9.2218L15.4047 6.9093C15.3047 6.84846 15.1896 6.81624 15.0721 6.81624C14.9547 6.81624 14.8395 6.84846 14.7396 6.9093L9.8111 9.71555V7.75305C9.8061 7.7445 9.80346 7.7348 9.80346 7.72492C9.80346 7.71505 9.8061 7.70535 9.8111 7.6968L13.897 5.37805C14.5222 5.02257 15.2371 4.85003 15.958 4.88059C16.6789 4.91115 17.3762 5.14356 17.9682 5.55064C18.5601 5.95772 19.0225 6.52265 19.301 7.17939C19.5796 7.83614 19.663 8.55755 19.5413 9.2593L19.554 9.2968ZM8.87989 12.7218L7.1695 11.753C7.15339 11.7405 7.1422 11.7228 7.13782 11.703V7.06555C7.13785 6.35289 7.34371 5.65499 7.73128 5.0536C8.11885 4.45222 8.67209 3.97224 9.32619 3.6699C9.98029 3.36756 10.7082 3.25537 11.4246 3.34647C12.141 3.43757 12.8162 3.7282 13.3712 4.1843L13.2636 4.25305L9.21563 6.55305C9.11158 6.60765 9.02504 6.68981 8.96573 6.79029C8.90642 6.89076 8.87669 7.00557 8.87989 7.1218V12.7218ZM9.80476 10.753L11.9966 9.50305L14.1948 10.753V13.253L11.9966 14.503L9.79843 13.253L9.80476 10.753Z"
                    stroke="#828282" stroke-width="0.2" mask="url(#path-1-outside-1_3606_3145)"></path>
            </svg></div>
        <div id="yt_article_summary_close_button" class="yt_article_summary_close_button">×</div>
    </div>
</body>

</html>
