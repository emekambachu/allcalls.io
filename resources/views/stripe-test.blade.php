<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <input id="card-holder-name" type="text">

    <!-- Stripe Elements Placeholder -->
    <div id="card-element"></div>

    <button id="card-button">
        Process Payment
    </button>


    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const stripe = Stripe('pk_test_51JUMhZF43egAbbxbdvc4FIRiALFxHyYECIknypspzMqjYBQ47Kvt8TBY3g44gfhIQHJLPQT4GMwcqlqN1KwKPsbc00UfQoy1mu');

        const elements = stripe.elements();
        const cardElement = elements.create('card');

        cardElement.mount('#card-element');

        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('card-button');

        cardButton.addEventListener('click', async (e) => {
            const {
                paymentMethod,
                error
            } = await stripe.createPaymentMethod(
                'card', cardElement, {
                    billing_details: {
                        name: cardHolderName.value
                    }
                }
            );

            if (error) {
                // Display "error.message" to the user...
                console.log(error);
            } else {
                // The card has been verified successfully...
                console.log('card has been verified successfully')
            }
        });
    </script>
</body>

</html>
