<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit New App</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="app">
        <form @submit.prevent="" class="question-card-list">
            <div v-show="!showConfirmationWindow" class="question-card animate__animated"  style="position: relative;">
                <div v-if="loading" style="position: absolute; height: 100%; width: 100%; background-color: #000000c7; z-index: 999; top: 0; left: 0; display: flex; justify-content: center; align-items: center; flex-direction: column; color: white; text-transform: uppercase; font-weight: 300; letter-spacing: 2px;">
                    <span class="loader"></span>
                    <div>Processing Information</div>
                </div>

                <div class="question-card__question">
                    <div class="question-card__body">
                        <div>
                            <div v-if="currentQuestion">
                                <h1 v-if="!currentQuestion.textMethod" :class="{ 'question-card__title--required': currentQuestion.required }" class="question-card__title" v-text="currentQuestion.text"></h1>
                                <h1 v-if="currentQuestion.textMethod" :class="{ 'question-card__title--required': currentQuestion.required }" class="question-card__title" v-text="this[currentQuestion.textMethod]()"></h1>


                                <h2 class="question-card__heading" v-text="currentQuestion.heading"></h2>


                                <div v-if="currentQuestion.type === 'text'">
                                    <input @keyup.enter="Next" type="text" v-model="this.form[currentQuestion.propertyName]" class="question-card__input">
                                    <p class="question-card__error" v-if="errors[currentQuestion.propertyName]" v-text="errors[currentQuestion.propertyName]"></p>
                                </div>
                                <div v-if="currentQuestion.type === 'number'">
                                    <input @keyup.enter="Next" type="number" v-model="this.form[currentQuestion.propertyName]" class="question-card__input">
                                    <p class="question-card__error" v-if="errors[currentQuestion.propertyName]" v-text="errors[currentQuestion.propertyName]"></p>
                                </div>
                                <div v-if="currentQuestion.type === 'date'">
                                    <input type="date" v-model="this.form[currentQuestion.propertyName]" class="question-card__input">
                                    <p class="question-card__error" v-if="errors[currentQuestion.propertyName]" v-text="errors[currentQuestion.propertyName]"></p>
                                </div>
                                <div v-if="currentQuestion && currentQuestion.type === 'currency'">
                                    <my-currency-input :input="form[currentQuestion.propertyName]" :name="currentQuestion.propertyName" v-on:update="updatedCurrencyValue"></my-currency-input>
                                    <p class="question-card__error" v-if="errors[currentQuestion.propertyName]" v-text="errors[currentQuestion.propertyName]"></p>
                                </div>
                                <div v-if="currentQuestion.type === 'select'">
                                    <select v-model="this.form[currentQuestion.propertyName]" class="question-card__input">
                                        <option value="" selected>Select</option>
                                        <option v-for="option in currentQuestion.options" v-text="option" :value="option"></option>
                                    </select>
                                    <p class="question-card__error" v-if="errors[currentQuestion.propertyName]" v-text="errors[currentQuestion.propertyName]"></p>
                                </div>
                                <div v-if="currentQuestion.type === 'dynamicSelect'">
                                    <select v-model="form[currentQuestion.propertyName]" :data-name="currentQuestion.propertyName" @change="onDynamicSelectChange" class="question-card__input">
                                        <option value="" selected>Select</option>
                                        <option v-for="option in this[currentQuestion.dynamicOptionsMethod]()" v-text="option"></option>
                                    </select>
                                    <p class="question-card__error" v-if="errors[currentQuestion.propertyName]" v-text="errors[currentQuestion.propertyName]"></p>
                                </div>

                                <div v-if="currentQuestion.type === 'collection'">
                                    <div style="margin-bottom: 20px;" v-for="question in currentQuestion.questions" :key="question">
                                        <div v-if="question.type === 'text'">
                                            <label class="question-card__label" v-text="question.text"></label>
                                            <input type="text" v-model="this.form[question.propertyName]" class="question-card__input">
                                            <p class="question-card__error" v-if="errors[question.propertyName]" v-text="errors[question.propertyName]"></p>
                                        </div>
                                        <div v-if="question.type === 'number'">
                                        <label class="question-card__label" v-text="question.text"></label>
                                            <input type="number" v-model="this.form[question.propertyName]" class="question-card__input">
                                            <p class="question-card__error" v-if="errors[question.propertyName]" v-text="errors[question.propertyName]"></p>
                                        </div>
                                        <div v-if="question.type === 'date'">
                                            <label class="question-card__label" v-text="question.text"></label>
                                            <input type="date" v-model="this.form[question.propertyName]" class="question-card__input">
                                            <p class="question-card__error" v-if="errors[question.propertyName]" v-text="errors[question.propertyName]"></p>
                                        </div>
                                        <div v-if="question && question.type === 'currency'">
                                            <label class="question-card__label" v-text="question.text"></label>
                                            <my-currency-input :input="form[question.propertyName]" :name="question.propertyName" v-on:update="updatedCurrencyValue"></my-currency-input>
                                            <p class="question-card__error" v-if="errors[question.propertyName]" v-text="errors[question.propertyName]"></p>
                                        </div>
                                        <div v-if="question.type === 'select'">
                                            <label class="question-card__label" v-text="question.text"></label>
                                            <select v-model="this.form[question.propertyName]" class="question-card__input">
                                                <option value="" selected>Select</option>
                                                <option v-for="option in question.options" v-text="option" :value="option"></option>
                                            </select>
                                            <p class="question-card__error" v-if="errors[question.propertyName]" v-text="errors[question.propertyName]"></p>
                                        </div>
                                        <div v-if="question.type === 'dynamicSelect'">
                                        <label class="question-card__label" v-text="question.text"></label>
                                            <select v-model="form[question.propertyName]" :data-name="question.propertyName" @change="onDynamicSelectChange" class="question-card__input">
                                                <option value="" selected>Select</option>
                                                <option v-for="option in this[question.dynamicOptionsMethod]()" v-text="option"></option>
                                            </select>
                                            <p class="question-card__error" v-if="errors[question.propertyName]" v-text="errors[question.propertyName]"></p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="question-actions">
                    <a @click.prevent="Previous()" class="question-actions__btn question-actions__btn--previous" href="#">
                        <svg style="height: 15px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                        </svg>
                        Previous
                    </a>
                    <a @click.prevent="Next()" class="question-actions__btn question-actions__btn--next" href="#">
                        Next
                        <svg style="height: 15px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                    </a>
                </div>

            </div>
        </form>

        <div class="confirm" v-show="showConfirmationWindow">
            <div class="confirm__container" style="position: relative; min-height: 400px;">
                <div v-show="!loading" class="confirm__card">
                    <div class="confirm__heading">Please confirm all your information is correct:</div>

                    <div class="confirm__info">
                        <div class="confirm__item" v-for="(value, key) in information" :key="key">
                            <div class="confirm__key" v-text="key"></div>
                            <div class="confirm__value" v-text="value"></div>
                        </div>
                    </div>

                    <div class="confirm_buttons">
                        <a href="#" class="confirm__button" @click.prevent="this.showConfirmationWindow = false">Change Entries</a>
                        <a href="#" class="confirm__button confirm__button--next" @click.prevent="confirm">Confirm</a>
                    </div>
                </div>
                <div v-if="loading" style="position: absolute; height: 100%; width: 100%; background-color: #000000c7; z-index: 999; top: 0; left: 0; display: flex; justify-content: center; align-items: center; flex-direction: column; color: white; text-transform: uppercase; font-weight: 300; letter-spacing: 2px;">
                    <span class="loader"></span>
                    <div>Processing Information</div>
                </div>
            </div>
        </div>
    </div>

<script src="//code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="//unpkg.com/axios/dist/axios.min.js"></script>
<script src="//unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
const { createApp } = Vue

let app = createApp({
    components: {
        myCurrencyInput: {
    props: ["input", "name"],
    template: `
        <div>
            <input class="question-card__input" type="text" v-model="displayValue" @blur="isInputActive = false" @focus="isInputActive = true"/>
        </div>`,
    data: function() {
        return {
            isInputActive: false
        }
    },
    computed: {
        displayValue: {
            get: function() {
                if (this.isInputActive) {
                    // Cursor is inside the input field. unformat display value for user
                    return this.input.toString()
                } else {
                    // User is not modifying now. Format display value for user interface
                    return "$ " + this.input.toFixed(2).replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,")
                }
            },
            set: function(modifiedValue) {
                // Recalculate value after ignoring "$" and "," in user input
                let newValue = parseFloat(modifiedValue.replace(/[^\d\.]/g, ""))

                // Ensure that it is not NaN
                if (isNaN(newValue)) {
                    newValue = 0
                }
                // Note: we cannot set this.value as it is a "prop". It needs to be passed to parent component
                // $emit the event so that parent component gets it
                this.$emit('update', newValue, this.name)
            }
        }
    }
}
    },
    data() {
        

        return {
            loading: false,
            showConfirmationWindow: false,
            index: 0,
            
            

           

            

            "errors": {},

            insuranceCompanyChangeEventAdded: false,
        };
    },

    methods: {

        f() {
            return Object.keys(this.companies);
        },
        
        getProductNameOptions() {
            return Object.keys(this.companies[this.form.insuranceCompany]);
        },

        getAnnualPremiumVolumeText() {
            return this.form.premiumFrequency + ' Premium Amount';
        },

        updatedCurrencyValue(value, name) {
            this.form[name] = value;
        },

        getAnnualPremiumVolume() {
            return 'Something annual premium volume';
        },

        onDynamicSelectChange(event) {
            if (event.target.getAttribute('data-name') === 'insuranceCompany') {
                this.insuranceCompanyChanged();
            }
        },

        Next() {
            let questionsForTheCurrentProduct = this.companies[this.form.insuranceCompany][this.form.productName].questions.map(questionIndex => {
                return this.questions[questionIndex];
            });

            // Check if the current question is a collection
            if (this.currentQuestion.type === 'collection') {
                // First check if each individual question in the collection is valid
                for (let i = 0; i < this.currentQuestion.questions.length; i++) {
                    if(! this[this.currentQuestion.questions[i].validationMethod].call(this)) {
                        return;
                    }
                }

                this.index++;
                this.currentQuestion = questionsForTheCurrentProduct[this.index];
                return;
            }

            if (this[this.currentQuestion.validationMethod]()) {
                this.index++;

                if (! questionsForTheCurrentProduct[this.index]) {
                    this.index--;
                    this.showConfirmationWindow = true;
                    return;
                }
                this.currentQuestion = questionsForTheCurrentProduct[this.index];

                // If it's certain questions, fields can be skipped depending on various conditions.
                if (this.currentQuestion.propertyName === 'choose') {
                    if (this.form.wasThisASplitSale === 'No') {
                        // Skip this question and the next one as well, so we can get straight to the insuranceCompany field.
                        this.index = this.index + 2;
                        this.currentQuestion = questionsForTheCurrentProduct[this.index];
                        return;
                    }
                }

                if (this.currentQuestion.propertyName === 'splitAgentEmail') {
                    if (this.form.choose === 'Transfer Program') {
                        // Skip this question
                        this.index++;
                        this.currentQuestion = questionsForTheCurrentProduct[this.index];
                        return; 
                    }
                }

                if (this.currentQuestion.propertyName === 'sourceOfTheLead') {
                    if (this.form.wasThisAppFromALead === 'No') {
                        // Skip this question
                        this.index++;
                        this.currentQuestion = questionsForTheCurrentProduct[this.index];
                        return; 
                    }
                }

                if (this.currentQuestion.propertyName === 'carrierWritingNumber') {
                    if (this.form.doYouHaveAnEquisWritingNumberWithThisCarrier === 'No') {
                        // Skip this question
                        this.index++;
                        this.currentQuestion = questionsForTheCurrentProduct[this.index];
                        return; 
                    }
                }

                return;
            }

            return;
        },

        Previous() {
            let questionsForTheCurrentProduct = this.companies[this.form.insuranceCompany][this.form.productName].questions.map(questionIndex => {
                return this.questions[questionIndex];
            });

            this.index--;

            if (this.index < 0) {
                this.index++;
                return;
            }
            this.currentQuestion = questionsForTheCurrentProduct[this.index];

            // Check for skipped questions
            if (this.currentQuestion.propertyName === 'choose') {
                if (this.form.wasThisASplitSale === 'No') {
                    // Skip this question and the next one as well, so we can get straight to the insuranceCompany field.
                    this.index = this.index - 2;
                    this.currentQuestion = questionsForTheCurrentProduct[this.index];
                    return;
                }
            }

            if (this.currentQuestion.propertyName === 'splitAgentEmail') {
                if (this.form.choose === 'Transfer Program' || this.form.choose === '') {
                    // Skip this question
                    this.index = this.index - 2;
                    this.currentQuestion = questionsForTheCurrentProduct[this.index];
                    return; 
                }
            }

            if (this.currentQuestion.propertyName === 'sourceOfTheLead') {
                if (this.form.wasThisAppFromALead === 'No') {
                    // Skip this question
                    this.index--;
                    this.currentQuestion = questionsForTheCurrentProduct[this.index];
                    return; 
                }
            }

            if (this.currentQuestion.propertyName === 'carrierWritingNumber') {
                if (this.form.doYouHaveAnEquisWritingNumberWithThisCarrier === 'No') {
                    // Skip this question
                    this.index--;
                    this.currentQuestion = questionsForTheCurrentProduct[this.index];
                    return; 
                }
            }

            return;
        },

        validateOptionalField() {
            return true;
        },

        validateRequiredField() {
            if (this.form[this.currentQuestion.propertyName].length) {
                this.errors[this.currentQuestion.propertyName] = '';
                return true;
            }

            console.log('Required validation failed.');
            this.errors[this.currentQuestion.propertyName] = 'This field is required.';
            return false;
        },

        validatePositiveNumberField() {
            if (this.form[this.currentQuestion.propertyName] && Number(this.form[this.currentQuestion.propertyName]) > 0) {
                this.errors[this.currentQuestion.propertyName] = '';
                return true;
            }

            console.log('Number validation failed.');
            this.errors[this.currentQuestion.propertyName] = 'Please enter a positive integer.';
            return false;
        },

        validateStreetAddress() {
            if (! this.form.streetLine1.length) { 
                this.errors['streetLine1'] = 'Please enter a street address.';
                return false;
            }

            this.errors['streetLine1'] = '';
            return true;
        },

        validateCity() {
            if (! this.form.city.length) {
                this.errors['city'] = 'Please enter your city name.';
                return false;
            }

            this.errors['city'] = '';
            return true;
        },

        validateState() {
            if (! this.form.state.length) {
                this.errors['state'] = 'Please select your state name.';
                return false;
            }

            this.errors['state'] = '';
            return true;
        },

        validateZipCodeField() {
            // Check if it has any value
            if (/(^\d{5}$)|(^\d{5}-\d{4}$)/.test(this.form.zipcode)) {
                this.errors[this.currentQuestion.propertyName] = '';
                return true;
            }

            console.log('Zip-code validation failed.');
            this.errors['zipcode'] = 'Please enter a valid US zip-code.';
            return false;
        },

        validatePhoneNumberField() {
            // Check if it has any value
            if (/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im.test(this.form[this.currentQuestion.propertyName])) {
                this.errors[this.currentQuestion.propertyName] = '';
                return true; 
            }

            console.log('Phone validation failed.');
            this.errors[this.currentQuestion.propertyName] = 'Please enter a valid phone number.';
            return false;
        },

        validateEmailField() {
            // Check if it has any value
            if (/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()\.,;\s@\"]+\.{0,1})+([^<>()\.,;:\s@\"]{2,}|[\d\.]+))$/.test(this.form[this.currentQuestion.propertyName])) {
                this.errors[this.currentQuestion.propertyName] = '';
                return true; 
            }

            console.log('Email validation failed.');
            this.errors[this.currentQuestion.propertyName] = 'Please enter a valid email address.';
            return false;
        },

        insuranceCompanyChanged() {
            console.log(this.companies[this.form.insuranceCompany]);
            this.form.productName = Object.keys(this.companies[this.form.insuranceCompany])[0];
        },

        productNameChanged() {
        },

        shakeQuestionCard() {
            $('.question-card').addClass('animate__shakeX')
            setTimeout(function() {
                $('.question-card').removeClass('animate__shakeX');
            }, 500);
        },

        sendRequest() {
            this.loading = true;
            axios.get('submit.php?' + new URLSearchParams(this.form).toString()).then(response => {
                // console.log('Redirect to the thanks page now.');
                window.location.href = 'thanks.php';
            }).catch(error => {
                this.loading = false;
            });
        },

        confirm() {
            this.sendRequest();
        },
    },

    computed: {
        insuranceCompaniesList() {
            return Object.keys(this.companies);
        },

        productNamesList() {
            return Object.keys(this.companies[this.form.insuranceCompany]);
        },

        information() {
            let info = {};
            let question;

            for (let prop in this.form) {
                // Find the question with prop as the propertyName
                    // Loop through all questions and filter by prop
                currentQuestion = this.questions.filter(question => question.propertyName === prop)[0];
                // Then, add a new element to the info object with question's text as the key and this.form[prop] as the value
                if (this.form[prop] !== '' && this.form[prop] !== 0) {
                    info[currentQuestion.text] = this.form[prop];
                }
            }

            return info;
        },
    },

});
app.mount('#app');
</script>


</body>
</html>
