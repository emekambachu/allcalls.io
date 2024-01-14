<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>allcalls.io</title>
    <link rel="icon" href="images/" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@900&family=Inter:wght@300;400;600;900&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="css/style.css" /> -->

    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        @font-face {
            font-family: 'SF Pro Text';
            src: url(../fonts/SF-Pro-Text-Thin.woff);
        }

        @font-face {
            font-family: 'SF Pro Text Bold';
            src: url(../fonts/SF-Pro-Text-Bold.woff);
        }

        @font-face {
            font-family: 'SF Pro Display';
            src: url(../fonts/SF-Pro-Display-Heavy.woff);
        }

        :root {
            --black: #000;
            --white: #fff;
            --gray-1: #CECDCD;
            --inter-font: 'Inter', sans-serif;
            --figtree-font: 'Figtree', sans-serif;
            --sf-pro-text: "SF Pro Text", sans-serif;
            --sf-pro-text-bold: "SF Pro Text Bold", sans-serif;
            --sf-pro-display: 'SF Pro Display', sans-serif;
        }

        body {
            width: 100vw;
            overflow-x: hidden;
            font-family: var(--sf-pro);
            color: var(--white);
            background-color: var(--black);
        }

        .main-container {
            margin-left: 15.6vw;
        }

        h1 {
            font-family: var(--figtree-font);
            font-size: 3.32vw;
            font-weight: 900;
            text-transform: uppercase;
        }

        h1 .br-mobile {
            display: none;
        }

        h1 .gradient-phrase {
            background: linear-gradient(84deg, #00F5F0 -20.63%, #06F 113.37%, #0080FC 113.37%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: rgba(255, 255, 255, 0);
            display: inline-block;
        }

        h2 {
            font-family: var(--sf-pro-display);
            font-size: 2.5vw;
            font-weight: 900;
            letter-spacing: 2.4px;
            text-transform: uppercase;
            text-align: center;
        }

        p {
            font-family: var(--sf-pro-text);
            font-size: 1.24vw;
        }

        .btn {
            font-family: var(--inter-font);
            border: 0;
            outline: 0;
            display: flex;
            background: 0;
            align-items: center;
            cursor: pointer;
        }

        button .arrow {
            width: 0.93vw;
        }

        .btn-1 {
            font-size: 0.83vw;
            font-weight: 600;
            color: var(--white);
            background-color: rgba(255, 255, 255, 0.26);
            border-radius: 51px;
            padding: 0.62vw 1.14vw;
            gap: 0.52vw;
        }

        .btn-2 {
            font-size: 1.04vw;
            font-weight: 900;
            color: var(--black);
            background-color: var(--white);
            border-radius: 51px;
            padding: 1.14vw 2.28vw;
            gap: 0.52vw;
        }

        a {
            text-decoration: none;
        }

        .glass-bg {
            background: linear-gradient(90deg, rgba(232, 232, 232, 0.01) -24.96%, rgba(217, 217, 217, 0.02) 63.41%);
            box-shadow: 0px 4px 62px 0px rgba(255, 255, 255, 0.04) inset;
            backdrop-filter: blur(14px);
        }

        .img-glass-block {
            background: rgba(255, 255, 255, 0.05);
        }

        .img-glass-block-sd {
            border-radius: 17.457px;
            padding: 1.3vw;
        }

        .img-glass-block-sd img,
        .img-glass-block-sd video {
            width: 32.42vw;
            border-radius: 11.6px;
        }

        .img-glass-block-sd video {
            height: 22.41vw;
            object-fit: cover;
        }

        .bright-gradient {
            position: absolute;
            pointer-events: none;
        }

        main {
            padding-top: 20.5vw;
            margin-top: -10vw;
            position: relative;
            overflow: hidden;
            padding-bottom: 300px;
        }

        main .main-container {
            display: flex;
            align-items: flex-start;
        }

        main .txt-block {
            max-width: 30.26vw;
            margin-top: 1.5vw;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5vw;
        }

        main .main-bright-gradient {
            width: 70vw;
            top: 0;
            right: 0;
            z-index: -1;
        }

        .pop-up {
            transform: translateY(50px);
            opacity: 0;
            transition: 0.6s;
        }

        .animat-popup {
            transform: translate(0);
            opacity: 1;
        }

        .d1 {
            transition-delay: 0.05s;
        }

        .d2 {
            transition-delay: 0.1s;
        }

        .d3 {
            transition-delay: 0.15s;
        }

        .d4 {
            transition-delay: 0.2s;
        }

        .d5 {
            transition-delay: 0.25s;
        }

        .d6 {
            transition-delay: 0.3s;
        }

        .d7 {
            transition-delay: 0.35s;
        }

        .d8 {
            transition-delay: 0.4s;
        }

        .d9 {
            transition-delay: 0.45s;
        }

        .d10 {
            transition-delay: 0.5s;
        }


        .modal {
            width: 100%;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1001;
            background: linear-gradient(90deg, rgba(232, 232, 232, 0.01) -24.96%, rgba(217, 217, 217, 0.02) 63.41%);
            box-shadow: 0px 4px 62px 0px rgba(255, 255, 255, 0.04) inset;
            backdrop-filter: blur(40px);
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .modal.exist {
            display: flex;
        }

        .modal h3 {
            font-family: var(--sf-pro-display);
            font-size: 2vw;
            text-align: center;
        }

        .modal .close-btn {
            width: 26px;
            height: 26px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            cursor: pointer;
            position: absolute;
            top: 40px;
            right: 40px;
        }

        .modal .close-btn .line {
            width: 100%;
            height: 1px;
            background-color: var(--white);
        }

        .modal .close-btn .line:nth-of-type(1) {
            transform: rotate(45deg);
            transform-origin: -0% 500%;
        }

        .modal .close-btn .line:nth-of-type(2) {
            transform: rotate(-45deg);
            transform-origin: 70% 200%;
        }

        .join-us-modal h3 {
            max-width: 25vw;
        }

        .join-us-modal form {
            font-family: var(--sf-pro-text);
            display: flex;
            flex-direction: column;
            margin-top: 2.5vw;
            gap: 1vw;
        }

        .join-us-modal form .type-input {
            width: 25vw;
            font-family: var(--sf-pro-text);
            font-size: 0.83vw;
            font-weight: 300;
            color: var(--white);
            background: 0;
            outline: 0;
            border: 1px solid var(--white);
            border-radius: 5px;
            padding: 1vw 0.5vw;
        }

        .join-us-modal form .type-input::placeholder {
            color: var(--white);
        }

        .join-us-modal form label {
            font-size: 1vw;
            font-weight: 300;
            margin-top: 0.5vw;
        }

        .join-us-modal form .input-radio-block {
            display: flex;
            align-items: center;
            gap: 0.3vw;
        }

        .join-us-modal form .input-radio-block .radio-input {
            width: 1.25vw;
            height: 1.25vw;
            cursor: pointer;
        }

        .join-us-modal form .input-radio-block .desc {
            font-size: 0.83vw;
        }

        .join-us-modal form .submit-btn {
            justify-content: center;
        }

        .calendly-modal {
            gap: 1vw;
        }

        .calendly-modal .calendly-inline-widget {
            min-width: 540px !important;
            height: 70vh !important;
        }

        .calendly-modal h3 {
            max-width: 20vw;
        }


        header {
            width: 59.85vw;
            margin-top: 2.28vw;
            margin-left: auto;
            margin-right: auto;
            border-radius: 2vw;
            display: flex;
            align-items: center;
            padding: 1.14vw 0.72vw 1.04vw 1.35vw;
            position: relative;
            z-index: 2;
        }

        header .logo {
            width: 9.72vw
        }

        header nav {
            margin-left: 4.94vw;
            display: flex;
            gap: 1.5vw;
        }

        header nav a {
            font-family: var(--inter-font);
            font-weight: 300;
            font-size: 0.83vw;
            color: var(--white);
        }

        header nav a.active {
            font-weight: 800;
            background: linear-gradient(53deg, #00F5F0 38.1%, #06F 88.39%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: rgba(255, 255, 255, 0);
        }

        header .lets-go-btn {
            margin-left: auto;
        }

        .empower section {
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: hidden;
        }

        .empower main .main-container {
            gap: 5.25vw;
        }

        .why-join-us {
            padding-top: 15.44vw;
            padding-bottom: 12.48vw;
            margin-top: -300px;
        }

        .why-join-us .our-Agents-p {
            margin-top: 1.14vw;
        }

        .why-join-us .box {
            width: 60.16vw;
            margin-top: 2.75vw;
            margin-left: auto;
            margin-right: auto;
            display: grid;
            border-radius: 24px;
            position: relative;
        }

        .why-join-us .box .gradient-on-layer {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background-color: var(--black);
            border-radius: 24px;
            z-index: 0;
        }

        .why-join-us .box::before,
        .why-join-us .box::after {
            content: '';
            width: 19vw;
            height: 100%;
            position: absolute;
            border-radius: 24px;
            z-index: -1;
        }

        .why-join-us .box::before {
            top: -2px;
            left: -2px;
            background: linear-gradient(126deg, #00F5F0 -0%, transparent 30%);
        }

        .why-join-us .box::after {
            bottom: -2px;
            right: -2px;
            background: linear-gradient(314deg, #327EEF -0%, transparent 30%);
        }

        .why-join-us .box .content {
            display: grid;
            grid-template-columns: 23vw 26vw;
            justify-content: space-between;
            padding: 4.26vw;
            border-radius: 24px;
            z-index: 1;
        }

        .why-join-us .box .content .side {
            display: flex;
            flex-direction: column;
        }

        .why-join-us .box .content .left {
            gap: 3.8vw;
        }

        .why-join-us .box .content .right {
            gap: 3.17vw;
        }

        .why-join-us .box .itm {
            display: flex;
            align-items: flex-start;
            gap: 1.3vw;
        }

        .why-join-us .box .itm .check-box-icon {
            width: 1.04vw;
            margin-top: 0.36vw;
        }

        .why-join-us .box .left-bright-gradient {
            top: -18vw;
            left: -18vw;
            width: 45vw;
            z-index: 2;
        }

        .why-join-us .box .middle-bright-gradients {
            width: 60vw;
            height: 100%;
        }

        .why-join-us .box .right-bright-gradient {
            bottom: -18vw;
            right: -18vw;
            width: 45vw;
            z-index: 2;
        }

        .why-join-us .start-money-btn {
            margin-top: 4.47vw;
        }

        .Partnerships {
            padding-bottom: 23vw;
            position: relative;
        }

        .Partnerships .box {
            margin-top: 4vw;
            display: grid;
            grid-template-columns: repeat(7, auto);
            align-items: center;
            gap: 2.34vw;
        }

        .Partnerships .box .partner-logo {
            margin: auto;
        }

        .Partnerships .box .americo {
            width: 7.38vw;
        }

        .Partnerships .box .ameritas {
            width: 7.33vw;
        }

        .Partnerships .box .mutual {
            width: 5.3vw;
            translate: -1.5vw;
        }

        .Partnerships .box .mutual.for-mobile {
            display: none;
        }

        .Partnerships .box .AIG {
            width: 6.34vw;
        }

        .Partnerships .box .americam-amicable {
            width: 10.4vw;
        }

        .Partnerships .box .american-equity {
            width: 4.1vw;
        }

        .Partnerships .box .columbian-life-Insurance {
            width: 5.51vw;
        }

        .Partnerships .box .athene {
            width: 6.24vw;
        }

        .Partnerships .box .colombus-life {
            width: 10.6vw;
        }

        .Partnerships .box .assurant {
            width: 6.1vw;
        }

        .Partnerships .box .north-american {
            width: 6.4vw;
        }

        .Partnerships .box .jh {
            width: 7.8vw;
        }

        .Partnerships .box .foresters {
            width: 6.08vw;
        }

        .Partnerships .box .transamerica {
            width: 6.44vw;
        }

        .Partnerships .box .oxford {
            width: 7.17vw;
        }

        .Partnerships .box .national-life {
            width: 7.43vw;
        }

        .Partnerships .box .occidental {
            width: 6.08vw;
        }

        .Partnerships .box .CVS {
            width: 8.37vw;
        }

        .Partnerships .box .gerber-life-insurance {
            width: 7.48vw;
        }

        .Partnerships .box .GPM {
            width: 4.83vw;
        }

        .Partnerships .box .etohs {
            width: 5.92vw;
        }

        .Partnerships .under-top-stars-gradients {
            width: 100%;
            bottom: -35vw;
            left: 50%;
            transform: translate(-50%);
        }

        .Partnerships .top-stars {
            width: 77vw;
            bottom: -35vw;
            left: 6.25vw
        }

        .Partnerships .elipse-top {
            width: 100%;
            bottom: 0;
            position: absolute;
            z-index: 1;
        }


        .leads-marketing {
            padding-top: 7.5vw;
            margin-top: -2vw;
            position: relative;
            z-index: 1;
        }

        .leads-marketing .img-glass-block {
            padding: 1.14vw;
            border-radius: 16.203px;
            margin-top: 2.8vw;
        }

        .leads-marketing .img-glass-block img {
            width: 29.9vw;
            border-radius: 10.8px;
        }

        .leads-marketing .boxes {
            display: flex;
            margin-top: 3.95vw;
        }

        .leads-marketing .boxes .box {
            width: 19.13vw;
            text-align: center;
            display: flex;
            align-items: center;
            flex-direction: column;
            gap: 1.24vw;
        }

        .leads-marketing .boxes .box:first-of-type {
            border-right: #3B3B3B solid 1px;
            padding-right: 2.34vw;
        }

        .leads-marketing .boxes .box:nth-of-type(2) {
            border-right: #3B3B3B solid 1px;
            padding-left: 2.34vw;
            padding-right: 2.34vw;
        }

        .leads-marketing .boxes .box:last-of-type {
            padding-left: 2.34vw;
        }

        .leads-marketing .boxes .box .icon {
            width: 8vw;
        }

        .leads-marketing .boxes .box h4 {
            font-family: var(--sf-pro-text-bold);
            font-size: 1.4vw;
            font-weight: 600;
        }

        .leads-marketing .boxes .box p {
            color: var(--gray-1);
        }

        .leads-marketing .click-upply-btn {
            margin-top: 4vw;
        }

        .leads-marketing .lead-marketing-gradients {
            width: 70vw;
            z-index: -1;
            transform: translateY(-10vw);
        }

        .leads-marketing .for-mobile {
            display: none;
        }

        .training-onboarding {
            padding-top: 13vw;
            padding-bottom: 10vw;
            position: relative;
        }

        .training-onboarding .boxes {
            margin-top: 2.95vw;
            display: flex;
            gap: 2.91vw;
            z-index: 1;
        }

        .training-onboarding .boxes .box {
            width: 23.92vw;
            padding: 1.24vw 1.35vw 4vw;
            border-radius: 22px 22px 9px 9px;
        }

        .training-onboarding .boxes .box .desc-img {
            width: 100%;
            border-radius: 20.72px 20.72px 5.53px 5.53px;
        }

        .training-onboarding .boxes .box p {
            text-align: center;
            margin-top: 0.98vw;
        }

        .training-onboarding .boxes .box .bright-gradient-mobile {
            display: none;
        }

        .training-onboarding .get-started-btn {
            margin-top: 4vw;
        }

        .training-onboarding .bright-gradient {
            width: 50vw;
            z-index: 0;
        }

        .training-onboarding .training-onboarding-gradients-left {
            left: 0;
        }

        .training-onboarding .training-onboarding-gradients-right {
            right: 0;
        }

        .ready-unlock {
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            padding-top: 29vw;
            padding-bottom: 45vw;
        }

        .ready-unlock .elipse-bottom {
            width: 100%;
            top: 0.5vw;
            position: absolute;
            z-index: 1;
            margin-top: -0.5vw;
        }

        .ready-unlock .bright-gradient {
            z-index: -1;
        }

        .ready-unlock .main-p {
            margin-top: 0.46vw;
        }

        .ready-unlock .btns {
            margin-top: 1.04vw;
            display: flex;
            gap: 0.72vw;
        }

        .ready-unlock .ready-unlock-gradients {
            width: 100%;
            top: -80vw;
        }

        .ready-unlock .ready-unlock-stars {
            width: 100%;
            top: -19.5vw;
        }

        footer {
            position: relative;
            width: 83.72vw;
            font-family: var(--inter-font);
            margin: auto;
            overflow: hidden;
            margin-bottom: 66px;
        }

        footer .content {
            border-radius: 43px;
            background: rgba(217, 217, 217, 0.03);
            backdrop-filter: blur(18px);
            padding: 3.48vw 3.27vw 1.82vw 3.27vw;
            position: relative;
            z-index: 1;
            margin: 1px;
        }

        footer::before {
            content: '';
            width: 45vw;
            height: 150%;
            position: absolute;
            border-radius: 43px;
            z-index: -1;
        }

        footer::before {
            background: linear-gradient(126deg, #FFFFFF4A 0%, transparent 30%);
        }

        footer::after {
            content: '';
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background-color: var(--black);
            border-radius: 43px;
            margin: 1px;
            z-index: -1;
        }

        footer .top {
            display: flex;
            justify-content: space-between;
        }

        footer .top .footer-logo {
            width: 15.34vw;
        }

        footer .top .subscreption {
            display: flex;
            align-items: center;
            gap: 1.45vw;
        }

        footer .top .subscreption .log-in {
            font-size: 0.83vw;
            color: var(--white);
        }

        footer .links {
            margin-top: 5.2vw;
            display: flex;
            justify-content: center;
            gap: 1.85vw;
        }

        footer .links a {
            font-size: 1.04vw;
            color: var(--white);
        }

        footer .seperator {
            width: 83.72vw;
            height: 1px;
            display: block;
            margin-top: 10.81vw;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.33) -11400.8%, rgba(255, 255, 255, 0.00) 11948.69%);
            transform: translate(-3.27vw);
        }

        footer .bottom {
            margin-top: 1.61vw;
            display: flex;
            align-items: center;
        }

        footer .bottom .copy-right {
            font-size: 0.83vw;
            font-weight: 300;
        }

        footer .bottom .further-links {
            margin-left: 2.75vw;
            display: flex;
            align-items: center;
            gap: 1.75vw;
        }

        footer .bottom .further-links a {
            font-size: 0.72vw;
            font-weight: 200;
            color: var(--white);
        }

        footer .bottom .further-links a.EULA {
            border-radius: 41px;
            border: 0.2px solid var(--white);
            padding: 0.2vw 0.52vw;
        }

        footer .bottom .contact {
            width: 16.74vw;
            margin-left: auto;
            display: flex;
            flex-direction: column;
            gap: 1.04vw;
        }

        footer .bottom .contact .itm {
            display: flex;
            align-items: center;
            gap: 0.2vw;
        }

        footer .bottom .contact .itm .icon {
            width: 1.24vw;
        }

        footer .bottom .contact .itm .txt {
            font-size: 0.72vw;
            color: rgba(255, 255, 255, 0.54);
        }

        @media screen and (max-width: 1000px) {
            .main-container {
                margin-left: -0;
            }

            h1 {
                max-width: 600px;
                font-size: 42px;
            }

            h1 .br-mobile {
                display: block;
            }

            h1 .gradient-phrase {
                background: 0;
                display: inline;
            }

            h1 .grand-gradient-phrase {
                background: linear-gradient(69deg, #00F5F0 20.29%, #06F 87.01%);
                background-clip: text;
                -webkit-background-clip: text;
                -webkit-text-fill-color: rgba(255, 255, 255, 0);
            }

            h1 .gradient-phrase {
                background: linear-gradient(84deg, #00F5F0 -20.63%, #06F 113.37%, #0080FC 113.37%);
                background-clip: text;
                -webkit-background-clip: text;
                -webkit-text-fill-color: rgba(255, 255, 255, 0);
                display: inline-block;
            }

            h2 {
                font-size: 32px;
                letter-spacing: 1.6px;
            }

            p {
                font-size: 16px;
            }

            button .arrow {
                width: 12px;
            }

            .btn-2 {
                font-size: 14px;
                gap: 8px;
                border-radius: 187.304px;
                padding: 14.76px 29.53px;
            }

            .img-glass-block-sd {
                border-radius: 13px;
                padding: 18px;
            }

            .img-glass-block-sd img,
            .img-glass-block-sd video {
                width: 472px;
                border-radius: 9px;
            }

            .img-glass-block-sd video {
                height: 329px;
            }

            .modal h3 {
                font-family: var(--sf-pro-display);
                font-size: 30px;
                text-align: center;
            }

            .join-us-modal h3 {
                max-width: 400px;
            }

            .join-us-modal form {
                margin-top: 30px;
                gap: 10px;
            }

            .join-us-modal form .type-input {
                width: 500px;
                font-size: 14px;
                padding: 10px 5px;
            }

            .join-us-modal form label {
                font-size: 20px;
                margin-top: 10px;
            }

            .join-us-modal form .input-radio-block {
                gap: 5px;
            }

            .join-us-modal form .input-radio-block .radio-input {
                width: 20px;
                height: 20px;
            }

            .join-us-modal form .input-radio-block .desc {
                font-size: 14px;
            }

            .calendly-modal {
                gap: 10px;
            }

            .calendly-modal h3 {
                max-width: 300px;
            }


            header {
                width: 92vw;
                margin-top: 33px;
                padding: 14px 22px 14px 17px;
                border-radius: 61.528px;
            }

            header .logo {
                width: 125px
            }

            header nav {
                margin-left: 4vw;
                gap: 14.7px;
            }

            header nav a {
                font-size: 12px;
            }

            header .lets-go-btn {
                font-size: 12px;
                gap: 6.7px;
                padding: 8px 14px;
            }

            main .main-container {
                flex-direction: column;
                align-items: center;
            }

            main .txt-block {
                max-width: 573px;
                text-align: center;
                margin-top: 0;
                align-items: center;
                gap: 15px;
            }

            main {
                padding-top: 370px;
                margin-top: -200px;
            }

            main .main-bright-gradient {
                width: 100%;
            }

            .empower main .main-container {
                gap: 24px
            }

            .why-join-us {
                padding-top: 215px;
                padding-bottom: 200px;
            }

            .why-join-us .our-Agents-p {
                margin-top: 16px;
            }

            .why-join-us .box {
                width: 75vw;
                margin-top: 40px;
                border-radius: 24px;
            }

            .why-join-us .box::before,
            .why-join-us .box::after {
                height: 150%;
            }

            .why-join-us .box .content {
                grid-template-columns: 1fr;
                padding: 38px;
                gap: 26px;
            }

            .why-join-us .box .content .left,
            .why-join-us .box .content .right {
                gap: 26px;
            }

            .why-join-us .box .itm {
                gap: 22.5px;
            }

            .why-join-us .box .itm .check-box-icon {
                width: 18.5px;
                margin-top: 0;
            }

            .why-join-us .box .itm p br {
                display: none;
            }

            .why-join-us .box .left-bright-gradient {
                width: 100vw;
                top: -35vw;
                left: -40vw;
            }

            .why-join-us .box .middle-bright-gradients {
                width: 80vw;
            }

            .why-join-us .box .right-bright-gradient {
                width: 100vw;
                bottom: -35vw;
                right: -40vw
            }

            .why-join-us .start-money-btn {
                margin-top: 39px;
            }

            .Partnerships {
                padding-bottom: 230px;
            }

            .Partnerships .box {
                margin-top: 50px;
                grid-template-columns: repeat(4, auto);
                column-gap: 27px;
                row-gap: 35px;
            }

            .Partnerships .box .GPM {
                display: none;
            }

            .Partnerships .box .americo {
                width: 112px;
                order: 1;
            }

            .Partnerships .box .ameritas {
                width: 112px;
                order: 2;
            }

            .Partnerships .box .mutual {
                width: 80px;
                order: 3;
                transform: translate(4px);
            }

            .Partnerships .box .mutual.for-desktop {
                display: none;
            }

            .Partnerships .box .mutual.for-mobile {
                display: block;
            }

            .Partnerships .box .AIG {
                width: 97px;
                order: 3;
            }

            .Partnerships .box .athene {
                width: 97px;
                order: 4;
            }

            .Partnerships .box .colombus-life {
                width: 161px;
                order: 5;
            }

            .Partnerships .box .assurant {
                width: 93px;
                order: 6;
            }

            .Partnerships .box .north-american {
                width: 97px;
                order: 7;
            }

            .Partnerships .box .oxford {
                width: 107px;
                order: 9;
            }

            .Partnerships .box .national-life {
                width: 113px;
                order: 10;
            }

            .Partnerships .box .occidental {
                width: 92.5px;
                order: 11;
            }

            .Partnerships .box .CVS {
                width: 127px;
                order: 12;
            }

            .Partnerships .box .americam-amicable {
                width: 135px;
                order: 13;
            }

            .Partnerships .box .american-equity {
                width: 62px;
                order: 14;
            }

            .Partnerships .box .columbian-life-Insurance {
                width: 84px;
                order: 15;
            }

            .Partnerships .box .jh {
                width: 118px;
                order: 16;
            }

            .Partnerships .box .foresters {
                width: 92px;
                order: 17;
            }

            .Partnerships .box .transamerica {
                width: 97px;
                order: 18;
            }

            .Partnerships .box .gerber-life-insurance {
                width: 114px;
                order: 19;
            }

            .Partnerships .box .etohs {
                width: 90px;
                order: 20;
            }

            .Partnerships .under-top-stars-gradients {
                width: 150%;
                bottom: -45vw;

            }

            .Partnerships .top-stars {
                width: 120%;
                bottom: -60vw;
                left: -20vw;
            }

            .leads-marketing {
                padding-top: 117px;
                margin-top: 5px;
            }

            .leads-marketing .img-glass-block {
                padding: 18px;
                border-radius: 14px;
                margin-top: 50px;
            }

            .leads-marketing .img-glass-block img {
                width: 472px;
                border-radius: 9px;
            }

            .leads-marketing .boxes {
                margin-top: 50px
            }

            .leads-marketing .boxes .box {
                width: 27vw;
                gap: 15.5px;
            }

            .leads-marketing .boxes .box:first-of-type {
                padding-right: 11px;
            }

            .leads-marketing .boxes .box:nth-of-type(2) {
                padding-left: 11px;
                padding-right: 11px;
            }

            .leads-marketing .boxes .box:last-of-type {
                padding-left: 11px;
            }

            .leads-marketing .boxes .box .icon {
                width: 100px;
            }

            .leads-marketing .boxes .box h4 {
                font-size: 18px;
            }

            .leads-marketing .click-upply-btn {
                margin-top: 43px;
            }

            .leads-marketing .lead-marketing-gradients {
                width: 130%;
                transform: translateY(-15vw);
            }

            .training-onboarding {
                padding-top: 199px;
                padding-bottom: 184px;
            }

            .training-onboarding .boxes {
                margin-top: 50px;
                gap: 56px;
                flex-direction: column;
            }

            .training-onboarding .boxes .box {
                width: 460px;
                padding: 24px 28px 28px;
                border-radius: 22px 22px 9px 9px;
                position: relative;
            }

            .training-onboarding .boxes .box p {
                margin-top: 20px;
            }

            .training-onboarding .boxes .box .bright-gradient-mobile {
                width: 100vw;
                top: -25vw;
                left: 50%;
                transform: translate(-50%);
                display: block;
                z-index: -1;
            }

            .training-onboarding .get-started-btn {
                margin-top: 53px;
            }

            .training-onboarding .bright-gradient-desktop {
                display: none;
            }

            .ready-unlock {
                padding-top: 45vw;
            }

            .ready-unlock h2 {
                max-width: 636px;
                text-align: center;
            }

            .ready-unlock .main-p {
                margin-top: 8px;
            }

            .ready-unlock .btns {
                margin-top: 17px;
                gap: 11px;
            }

            .ready-unlock .ready-unlock-gradients {
                width: 120%;
                top: -90vw;
            }

            .ready-unlock .ready-unlock-stars {
                width: 130%;
                top: -50vw;
            }

            footer {
                width: 92%;
            }

            footer .content {
                border-radius: 19px;
                padding: 30px 35px 15px 35px;
            }

            footer::before {
                content: '';
                width: 45vw;
                height: 140%;
                position: absolute;
                z-index: -1;
                border-radius: 19px;
            }

            footer::after {
                border-radius: 19px;
            }

            footer .top .footer-logo {
                width: 132px;
            }

            footer .top .subscreption {
                gap: 12.5px;
            }

            footer .top .subscreption .log-in,
            footer .bottom .copy-right,
            footer .bottom .further-links a,
            footer .bottom .contact .itm .txt {
                font-size: 9px;
            }

            footer .top .subscreption .sign-up-btn {
                font-size: 9px;
                padding: 5px 9.821px;
                border-radius: 22.766px;
                gap: 4.464px;
            }

            footer .top .subscreption .sign-up-btn .arrow {
                width: 8px;
            }

            footer .links {
                margin-top: 37.5px;
                gap: 12px;
            }

            footer .links a {
                font-size: 12px;
            }

            footer .seperator {
                width: 92vw;
                margin-top: 92px;
                transform: translate(-53px);
            }

            footer .bottom {
                margin-top: 13.5px;
            }

            footer .bottom .further-links {
                margin-left: 23.5px;
                gap: 11.5px;
            }

            footer .bottom .contact {
                width: 131px;
                gap: 9px;
            }

            footer .bottom .contact .itm {
                gap: 1.8px;
            }

            footer .bottom .contact .itm .icon {
                width: 10.7px;
            }
        }

        @media screen and (max-width: 750px) {
            .modal .close-btn {
                top: 2vh;
                right: 2vh;
            }

            .calendly-modal .calendly-inline-widget {
                min-width: 90vw !important;
                height: 78vh !important;
            }

            .hamburger-Clicked {
                overflow: hidden;
            }

            .hamburger-Clicked header {
                backdrop-filter: none;
            }

            header {
                width: 100%;
                padding: 31px 22px;
                border-radius: 0;
                margin-top: 0;
            }

            header .logo {
                width: 143px;
                z-index: 1000;
            }

            header nav {
                width: 100vw;
                height: 100vh;
                margin-left: 0;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 999;
                background: linear-gradient(90deg, rgba(232, 232, 232, 0.01) -24.96%, rgba(217, 217, 217, 0.02) 63.41%);
                box-shadow: 0px 4px 62px 0px rgba(255, 255, 255, 0.04) inset;
                backdrop-filter: blur(40px);
                flex-direction: column;
                align-items: center;
                gap: 22px;
                padding-top: 20vh;
                clip-path: circle(5% at 90% 7%);
                opacity: 0;
                transition: clip-path 1s;
                pointer-events: none;
            }

            .hamburger-Clicked header nav {
                overflow: hidden;
                clip-path: circle(100%);
                opacity: 1;
                pointer-events: all;
            }

            header nav a {
                font-size: 18px;
            }

            header nav a.active {
                font-weight: 400;
                color: var(--white);
                background: 0;
                -webkit-text-fill-color: unset;
            }

            header .lets-go-btn {
                display: none;
            }

            header .hamburger {
                display: flex;
                flex-direction: column;
                gap: 6px;
                margin-left: auto;
                cursor: pointer;
                z-index: 1000;
            }

            header .hamburger .line {
                width: 25px;
                height: 1px;
                background-color: var(--white);
                transition: 0.5s;
                transform-origin: 30% 0%;
            }

            .hamburger-Clicked header .hamburger .line:nth-of-type(1),
            .hamburger-Clicked header .hamburger .line:nth-of-type(4) {
                opacity: 0;
            }

            .hamburger-Clicked header .hamburger .line:nth-of-type(2) {
                transform: rotate(40deg);
            }

            .hamburger-Clicked header .hamburger .line:nth-of-type(3) {
                transform: rotate(-40deg);
            }

            .Partnerships .box {
                column-gap: 10px;
                row-gap: 30px;
            }

            footer .content {
                padding: 82px 0 68px;
                margin: 0;
                border-radius: 43px;

            }

            footer::before,
            footer::after {
                display: none;
            }

            footer .top {
                justify-content: center;
            }

            footer .top .footer-logo {
                width: 220px;
            }

            footer .top .subscreption {
                display: none
            }

            footer .links {
                margin-top: 43px;
                padding-top: 43px;
                flex-direction: column;
                align-items: center;
                gap: 22px;
                border-top: 1px solid #2f2f2fff;
            }

            footer .links a {
                width: 150px;
                font-size: 20px;
            }

            footer .seperator {
                display: none;
            }

            footer .bottom {
                flex-direction: column;
                margin-top: 74px;
            }

            footer .bottom .contact {
                width: auto;
                align-items: center;
                gap: 20px;
                order: 1;
                margin-left: 0;
            }

            footer .bottom .contact .itm {
                width: 294px;
                gap: 4px;
            }

            footer .bottom .contact .itm .icon {
                width: 24px;
            }

            footer .bottom .contact .itm .txt {
                font-size: 16px
            }

            footer .bottom .further-links {
                order: 2;
            }

            footer .bottom .further-links {
                margin-top: 65px;
                margin-left: 0;
                flex-direction: column;
                gap: 14px;
            }

            footer .bottom .further-links a {
                width: 131px;
                font-size: 14px;
                padding-left: 10px;
            }

            footer .bottom .further-links a.EULA {
                width: auto;
                align-self: flex-start;
                padding: 3px 10px;
            }

            footer .bottom .copy-right {
                width: 100%;
                font-size: 16px;
                text-align: center;
                padding-top: 65px;
                margin-top: 40px;
                padding-left: 16vw;
                padding-right: 16vw;
                border-top: 1px solid #2f2f2fff;
                order: 3;
            }

        }

        @media screen and (max-width: 600px) {
            h1 {
                max-width: 379px;
            }

            h1 .grand-gradient-phrase {
                background: linear-gradient(74deg, #00F5F0 13.27%, #06F 116.09%);
                background-clip: text;
                -webkit-background-clip: text;
                -webkit-text-fill-color: rgba(255, 255, 255, 0);
            }

            h2 {
                font-size: 32px;
            }

            p {
                font-size: 16px;
            }

            .btn-2 {
                font-size: 13px;
                gap: 8px;
                padding: 19px 40px;
            }

            .img-glass-block-sd {
                border-radius: 8.5px;
                padding: 12px;
            }

            .img-glass-block-sd img,
            .img-glass-block-sd video {
                max-width: 380px;
                width: 100%;
                border-radius: 5.6px;
            }

            .img-glass-block-sd video {
                height: 210px;
            }

            main {
                padding-top: 302px;
                padding-bottom: 200px;
                margin-top: -200px;
                padding-left: 5%;
                padding-right: 5%;
            }

            main .main-bright-gradient {
                width: 220%;
                top: 0%;
                right: -30vw;
            }

            .modal h3 {
                font-size: 26px;
            }

            .join-us-modal {
                padding-left: 5%;
                padding-right: 5%;
            }

            .join-us-modal form {
                width: 100%
            }

            .join-us-modal form .type-input {
                width: 100%
            }

            .join-us-modal form .submit-btn {
                font-size: 14px;
                gap: 8px;
                border-radius: 187.304px;
                padding: 14.76px
            }

            .empower main .main-container {
                gap: 15px
            }

            .why-join-us {
                padding-bottom: 300px;
                padding-top: 300px;
                margin-top: -380px;
            }

            .why-join-us .our-Agents-p {
                margin-top: 4px;
            }

            .why-join-us .box {
                max-width: 404px;
                width: 92%;
                margin-top: 33px;
            }

            .why-join-us .box .content {
                padding: 28px 13px 28px 28px;
                gap: 29px;
            }

            .why-join-us .box .content .left,
            .why-join-us .box .content .right {
                gap: 29px;
            }

            .why-join-us .box .itm {
                gap: 17px;
            }

            .why-join-us .box .itm .check-box-icon {
                width: 12px;
            }

            .why-join-us .box .left-bright-gradient {
                width: 200%;
                top: -75vw;
                left: -65vw;
            }

            .why-join-us .box .middle-bright-gradients {
                width: 180%;
                left: 50%;
                transform: translate(-50%);
            }

            .why-join-us .box .right-bright-gradient {
                width: 200%;
                bottom: -75vw;
                right: -65vw
            }

            .why-join-us .start-money-btn {
                margin-top: 30px;
            }

            .Partnerships {
                padding-left: 5%;
                padding-right: 5%;
                padding-bottom: 130px;
                margin-top: -200px;
            }

            .Partnerships .a-rated-carrier-p {
                max-width: 270px;
                text-align: center;
            }

            .Partnerships .box {
                margin-top: 30px;
                column-gap: 14px;
                row-gap: 20px;
            }

            .Partnerships .box .americo,
            .Partnerships .box .ameritas {
                width: 64px;
            }

            .Partnerships .box .mutual {
                width: 46px;
            }

            .Partnerships .box .AIG,
            .Partnerships .box .north-american,
            .Partnerships .box .transamerica {
                width: 56px;
            }

            .Partnerships .box .athene {
                width: 54px;
            }

            .Partnerships .box .colombus-life {
                width: 93px;
            }

            .Partnerships .box .assurant,
            .Partnerships .box .occidental,
            .Partnerships .box .foresters,
            .Partnerships .box .gerber-life-insurance {
                width: 53px;
            }

            .Partnerships .box .oxford {
                width: 61px;
            }

            .Partnerships .box .national-life {
                width: 65px;
            }

            .Partnerships .box .CVS {
                width: 73px;
            }

            .Partnerships .box .americam-amicable {
                width: 78px;
            }

            .Partnerships .box .american-equity {
                width: 36px;
            }

            .Partnerships .box .columbian-life-Insurance {
                width: 48px;
            }

            .Partnerships .box .jh {
                width: 67px;
            }

            .Partnerships .box .etohs {
                width: 52px;
            }

            .leads-marketing {
                padding-top: 75px;
                padding-left: 5%;
                padding-right: 5%;
                padding-bottom: 220px;
            }

            .leads-marketing .img-glass-block {
                padding: 11.5px;
                border-radius: 9px;
                margin-top: 30px;
            }

            .leads-marketing .img-glass-block img {
                max-width: 380px;
                width: 100%;
                border-radius: 6px;
            }

            .leads-marketing .boxes {
                margin-top: 26px;
                flex-direction: column;
                gap: 23px;
            }

            .leads-marketing .boxes .box {
                max-width: 240px;
                width: 100%;
                gap: 9px;
                border-right: 0;
                padding: 0;
            }

            .leads-marketing .boxes .box:first-of-type,
            .leads-marketing .boxes .box:nth-of-type(2),
            .leads-marketing .boxes .box:last-of-type {
                border: 0;
                padding: 0;
            }

            .leads-marketing .boxes .box h4 {
                font-size: 22px;
            }

            .leads-marketing .click-upply-btn {
                margin-top: 35px;
            }

            .leads-marketing .lead-marketing-gradients {
                width: 160%;
                transform: translateY(-15vw);
            }

            .leads-marketing .for-mobile {
                width: 40%;
                display: block;
            }

            .leads-marketing .lead-marketing-gradients-middle-mobile {
                top: 370px;
                left: -10%;
            }

            .leads-marketing .lead-marketing-gradients-bottom-mobile {
                top: 500px;
                right: 0%;
            }

            .training-onboarding {
                padding-left: 5%;
                padding-right: 5%;
                padding-top: 200px;
                padding-bottom: 130px;
                margin-top: -300px;
            }

            .training-onboarding .boxes {
                margin-top: 30px;
                gap: 40px;
            }

            .training-onboarding .boxes .box {
                max-width: 404px;
                width: 100%;
                padding: 19px;
                border-radius: 18px 18px 8px 8px;
            }

            .training-onboarding .boxes .box .desc-img {
                border-radius: 14.684px 14.684px 3.919px 3.919px;
            }

            .training-onboarding .boxes .box p {
                margin-top: 20px;
            }

            .training-onboarding .boxes .box .bright-gradient-mobile {
                width: 150%;
                top: auto;
                bottom: -20vw;
            }

            .ready-unlock {
                padding-top: 64vw;
                padding-left: 5%;
                padding-right: 5%;
            }

            .ready-unlock h2 {
                max-width: 317px;
            }

            .ready-unlock .main-p {
                max-width: 250px;
                text-align: center;
                margin-top: 6px;
            }

            .ready-unlock .btns {
                margin-top: 13px;
                flex-direction: column;
                gap: 10px;
            }

            .ready-unlock .ready-unlock-gradients {
                top: -60vw;
            }

            .ready-unlock .ready-unlock-stars {
                width: 160%;
                top: -0vw;
            }


        }

        @media screen and (max-width: 390px) {
            h1 {
                font-size: 11vw;
            }
        }

        @media screen and (max-width: 350px) {
            .modal .close-btn {
                top: 1vh;
                right: 1vh;
            }

            .Partnerships .box {
                column-gap: 5px;
                row-gap: 17px;
            }

            footer .bottom .contact .itm {
                width: 250px;
            }

            footer .bottom .copy-right {
                padding-left: 12vw;
                padding-right: 12vw;
            }
        }
    </style>
</head>

<body class="empower">
    <header class="glass-bg">
        <img class="logo pop-up loading-pop-up" src="images/logo.svg">
        <nav>
            <a class="pop-up loading-pop-up d1" href="#">Live Transfers</a>
            <a class="pop-up loading-pop-up d2" href="#">Why AllCalls.io</a>
            <a class="pop-up loading-pop-up d3" href="#">How It Works</a>
            <a class="pop-up loading-pop-up d4" class="active" href="#">Careers</a>
            <a class="pop-up loading-pop-up d5" href="#">Support</a>
        </nav>
        <button class="lets-go-btn btn-1 btn pop-up loading-pop-up d6">
            <span class="text">LET’S GO</span>
            <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z" fill="white" />
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
                <h1 class="pop-up loading-pop-up d7">Empower Your <br class="br-mobile"> <span class="grand-gradient-phrase"><span class="gradient-phrase">Life Insurance </span><span class="gradient-phrase">Career</span></span> With AllCalls.io</h1>
                <p class="pop-up loading-pop-up d8">Work Remotely, Earn Daily, Grow Exponentially</p>
                <button class="join-us-btn btn-2 btn pop-up loading-pop-up d9">
                    <span class="text">JOIN US NOW</span>
                    <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z" fill="black" />
                    </svg>
                </button>
            </div>
            <div class="img-glass-block img-glass-block-sd pop-up loading-pop-up d10">
                <!-- <img class="main" src="images/carrer-main-img.jpg"> -->
                <video src="https://allcalls.io/video/file.mp4" autoplay="" muted="" playsinline="" loop=""></video>
            </div>
        </div>
        <img class="main-bright-gradient  bright-gradient" src="images/empower-main-bright.svg">
    </main>
    <section class="why-join-us">
        <h2 class="pop-up scroll-pop-up">why join us?</h2>
        <p class="our-Agents-p pop-up scroll-pop-up">Our Agents are Partners, Not Employees!</p>
        <div class="box">
            <div class="content glass-bg">
                <div class="left side">
                    <div class="itm pop-up scroll-pop-up">
                        <img class="check-box-icon" src="images/check-box-icon.svg">
                        <p>Work 100% Remotely</p>
                    </div>
                    <div class="itm pop-up scroll-pop-up">
                        <img class="check-box-icon" src="images/check-box-icon.svg">
                        <p>On Demand Clients Brought <br> Right to You Through Our <br> Proprietary Call System</p>
                    </div>
                    <div class="itm pop-up scroll-pop-up">
                        <img class="check-box-icon" src="images/check-box-icon.svg">
                        <p>Agent Lead Subsidy Program to Help Our Agents Grow</p>
                    </div>
                    <div class="itm pop-up scroll-pop-up">
                        <img class="check-box-icon" src="images/check-box-icon.svg">
                        <p>Work When You Want, You Decide Your Schedule</p>
                    </div>
                </div>
                <div class="right side">
                    <div class="itm pop-up scroll-pop-up">
                        <img class="check-box-icon" src="images/check-box-icon.svg">
                        <p>Servicing Our Clients With Over 20 A Rated Insurance Carriers</p>
                    </div>
                    <div class="itm pop-up scroll-pop-up">
                        <img class="check-box-icon" src="images/check-box-icon.svg">
                        <p>Commissions Paid Daily</p>
                    </div>
                    <div class="itm pop-up scroll-pop-up">
                        <img class="check-box-icon" src="images/check-box-icon.svg">
                        <p>Performance Base Promotion System</p>
                    </div>
                    <div class="itm pop-up scroll-pop-up">
                        <img class="check-box-icon" src="images/check-box-icon.svg">
                        <p>Vested Renewals Day One</p>
                    </div>
                    <div class="itm pop-up scroll-pop-up">
                        <img class="check-box-icon" src="images/check-box-icon.svg">
                        <p>Management/Agency Opportunities Available For the Right Individuals</p>
                    </div>
                </div>
            </div>
            <div class="gradient-on-layer"></div>
            <img class="left-bright-gradient bright-gradient " src="images/join-us-left-gradients.svg">
            <img class="middle-bright-gradients bright-gradient " src="images/join-us-middle-gradients.svg">
            <img class="right-bright-gradient bright-gradient " src="images/join-us-right-gradients.svg">
        </div>
        <button class="start-money-btn btn-2 btn pop-up scroll-pop-up">
            <span class="text">START YOUR JOURNEY HERE</span>
            <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z" fill="black" />
            </svg>
        </button>
    </section>

    <section class="Partnerships">
        <p class="a-rated-carrier-p pop-up scroll-pop-up">20+ A-rated Carrier Partnerships and dozen of products</p>
        <div class="box">
            <img class="americo partner-logo pop-up scroll-pop-up" src="images/partners/americo.png">
            <img class="ameritas partner-logo pop-up scroll-pop-up d1" src="images/partners/ameritas.png">
            <img class="mutual for-desktop partner-logo pop-up scroll-pop-up d2" src="images/partners/mutual.png">
            <img class="mutual for-mobile partner-logo pop-up scroll-pop-up" src="images/partners/mutual-for-mobile.png">
            <img class="AIG partner-logo pop-up scroll-pop-up d3" src="images/partners/AIG.png">
            <img class="americam-amicable partner-logo pop-up scroll-pop-up d4" src="images/partners/americam-amicable.png">
            <img class="american-equity  partner-logo pop-up scroll-pop-up d5" src="images/partners/American.png ">
            <img class="columbian-life-Insurance partner-logo pop-up scroll-pop-up d6" src="images/partners/ColumbianLifeInsuranceCompany.png ">
            <img class="athene partner-logo pop-up scroll-pop-up" src="images/partners/athene.png">
            <img class="colombus-life partner-logo pop-up scroll-pop-up d1" src="images/partners/colombus-life.png ">
            <img class="assurant partner-logo pop-up scroll-pop-up d2" src="images/partners/assurant.png">
            <img class="north-american partner-logo pop-up scroll-pop-up d3" src="images/partners/north-american.png">
            <img class="jh partner-logo pop-up scroll-pop-up d4" src="images/partners/jh_white_logo_2x.png">
            <img class="foresters partner-logo pop-up scroll-pop-up d5" src="images/partners/foresters-logo-en-white 1.png">
            <img class="transamerica partner-logo pop-up scroll-pop-up d6" src="images/partners/transamerica.png ">
            <img class="oxford partner-logo pop-up scroll-pop-up" src="images/partners/oxford.png">
            <img class="national-life partner-logo pop-up scroll-pop-up d1" src="images/partners/national.png">
            <img class="occidental partner-logo pop-up scroll-pop-up d2" src="images/partners/Occidental.png">
            <img class="CVS partner-logo pop-up scroll-pop-up d3" src="images/partners/cvs.png ">
            <img class="gerber-life-insurance partner-logo pop-up scroll-pop-up d4" src="images/partners/Gerber-Life-Insurance-2.png ">
            <img class="GPM partner-logo pop-up scroll-pop-up d5" src="images/partners/GPM.png">
            <img class="etohs partner-logo pop-up scroll-pop-up d6" src="images/partners/Ethos.png ">
        </div>
        <img class="under-top-stars-gradients bright-gradient" src="images/under-top-stars-gradients.svg">
        <img class="top-stars bright-gradient " src="images/top-stars.svg">
        <img class="elipse-top" src="images/elipse-top.svg">
    </section>


    <section class="leads-marketing">
        <h2 class="pop-up scroll-pop-up">Leads and Marketing</h2>
        <div class="img-glass-block pop-up scroll-pop-up">
            <img class="img" src="images/leads-marketing-main-img.jpg">
        </div>
        <div class="boxes">
            <div class="box">
                <img class="icon pop-up scroll-pop-up" src="images/Live-Transfer-Calls-icon.svg">
                <h4 class="pop-up scroll-pop-up">Live Transfer Calls</h4>
                <p class="pop-up scroll-pop-up">No outbound dialing!
                    Our agents connect to customers looking for life insurance through our live transfer call program</p>
            </div>
            <div class="box">
                <img class="icon pop-up scroll-pop-up d1" src="images/On-Demand-Access-icon.svg">
                <h4 class="pop-up scroll-pop-up d1">On-Demand Access</h4>
                <p class="pop-up scroll-pop-up d1">Agents are able to log into their web portal or mobile app and make themselves available to take calls
                    on demand.</p>
            </div>
            <div class="box">
                <img class="icon pop-up scroll-pop-up d2" src="images/Lead-Subsidy-icon.svg">
                <h4 class="pop-up scroll-pop-up d2">Lead Subsidy</h4>
                <p class="pop-up scroll-pop-up d2">We offer a lead subsidy for all of our agents to help them increase their ROI for their business.</p>
            </div>
        </div>
        <button class="click-upply-btn btn-2 btn pop-up scroll-pop-up">
            <span class="text">CLICK TO APPLY</span>
            <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z" fill="black" />
            </svg>
        </button>
        <img class="lead-marketing-gradients bright-gradient" src="images/lead-marketing-gradients.svg">
        <img class="lead-marketing-gradients-middle-mobile for-mobile bright-gradient" src="images/lead-marketing-gradients-middle-mobile.svg">
        <img class="lead-marketing-gradients-bottom-mobile for-mobile bright-gradient" src="images/lead-marketing-gradients-bottom-mobile.svg">
    </section>

    <section class="training-onboarding">
        <h2 class="pop-up scroll-pop-up">Training and Onboarding</h2>
        <div class="boxes">
            <div class="box img-glass-block pop-up scroll-pop-up">
                <img class="desc-img" src="images/Training-Onboarding-img-1.jpg">
                <p>Free membership to insurance toolkits quoting tool</p>
                <img class="training-onboarding-gradients-top-mobile bright-gradient bright-gradient-mobile" src="images/training-onboarding-gradients-top-mobile.svg">
            </div>
            <div class="box img-glass-block pop-up scroll-pop-up d1">
                <img class="desc-img" src="images/Training-Onboarding-img-2.jpg">
                <p>All of our training and resources are free</p>
                <img class="training-onboarding-gradients-middle-mobile bright-gradient bright-gradient-mobile" src="images/training-onboarding-gradients-middle-mobile.svg">
            </div>
            <div class="box img-glass-block pop-up scroll-pop-up d2">
                <img class="desc-img" src="images/Training-Onboarding-img-3.jpg">
                <p>After your onboarding, you'll be able to schedule some one-on-one time with our team to help you get started</p>
                <img class="training-onboarding-gradients-bottom-mobile bright-gradient bright-gradient-mobile" src="images/training-onboarding-gradients-bottom-mobile.svg">
            </div>
        </div>
        <button class="get-started-btn btn-2 btn pop-up scroll-pop-up">
            <span class="text">GET STARTED NOW</span>
            <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z" fill="black" />
            </svg>
        </button>
        <img class="training-onboarding-gradients-left bright-gradient bright-gradient-desktop" src="images/Training-Onboarding-gradients-left.svg">
        <img class="training-onboarding-gradients-middle bright-gradient bright-gradient-desktop" src="images/Training-Onboarding-gradients-middle.svg">
        <img class="training-onboarding-gradients-right bright-gradient bright-gradient-desktop" src="images/Training-Onboarding-gradients-right.svg">
    </section>


    <section class="ready-unlock">
        <img class="elipse-bottom" src="images/elipse-bottom.svg">
        <h2 class="pop-up scroll-pop-up">Ready to unlock new opportunities?</h2>
        <p class="main-p pop-up scroll-pop-up">Start your journey as a life insurance broker with us!</p>
        <div class="btns">
            <button class="join-us-btn btn-2 btn pop-up scroll-pop-up">
                <span class="text">JOIN US NOW</span>
                <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z" fill="black" />
                </svg>
            </button>
            <button class="get-licenced-btn btn-2 btn pop-up scroll-pop-up d1">
                <span class="text">GET LICENSED</span>
                <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z" fill="black" />
                </svg>
            </button>
        </div>
        <img class="ready-unlock-gradients bright-gradient" src="images/ready-unlock-gradients.svg">
        <img class="ready-unlock-stars bright-gradient" src="images/ready-unlock-gradients-stars.svg">
    </section>

    <div class="join-us-modal modal">
        <h3>Apply to Be An AllCalls Insurance Agent</h3>
        <form action="">
            <input class="type-input" type="text" placeholder="First Name">
            <input class="type-input" type="text" placeholder="Last Name">
            <input class="type-input" type="email" placeholder="Email">
            <input class="type-input" type="tel" placeholder="Phone Number">
            <label for="">Are You Licensed for Life Insurance?</label>
            <div class="input-radio-block">
                <input class="radio-input" type="radio" name="licensed"><span class="desc">Yes</span>
            </div>
            <div class="input-radio-block">
                <input class="radio-input" type="radio" name="licensed"><span class="desc">No</span>
            </div>
            <button class="submit-btn btn-2 btn">
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
        <div class="calendly-inline-widget" data-url="https://calendly.com/d/4c2-5rz-64m/life-insurance-telesales-position" style="min-width:320px;height:700px;"></div>
        <script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>
        <div class="close-btn">
            <span class="line"></span>
            <span class="line"></span>
        </div>
    </div>


    <footer>
        <div class="content">
            <div class="top">
                <img class="footer-logo pop-up scroll-pop-up" src="images/footer-logo.svg">
                <div class="subscreption">
                    <button class="log-in pop-up btn scroll-pop-up d1">Log In</button>
                    <button class="sign-up-btn btn-1 btn pop-up scroll-pop-up d2">
                        <span class="text">Sign Up Now</span>
                        <svg class="arrow" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.6827 15.5025C6.5402 15.5025 6.3977 15.45 6.2852 15.3375C6.0677 15.12 6.0677 14.76 6.2852 14.5425L11.1752 9.65251C11.5352 9.29251 11.5352 8.70751 11.1752 8.34751L6.2852 3.45751C6.0677 3.24001 6.0677 2.88001 6.2852 2.66251C6.5027 2.44501 6.86269 2.44501 7.08019 2.66251L11.9702 7.55251C12.3527 7.93501 12.5702 8.45252 12.5702 9.00002C12.5702 9.54751 12.3602 10.065 11.9702 10.4475L7.08019 15.3375C6.96769 15.4425 6.8252 15.5025 6.6827 15.5025Z" fill="white" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="links">
                <a class="pop-up scroll-pop-up" href="#">Live Transfers</a>
                <a class="pop-up scroll-pop-up d1" href="#">Why AllCalls.io</a>
                <a class="pop-up scroll-pop-up d2" href="#">How It Works</a>
                <a class="pop-up scroll-pop-up d3" href="#">Careers</a>
                <a class="pop-up scroll-pop-up d4" href="#">Support</a>
            </div>
            <span class="seperator"></span>
            <div class="bottom">
                <span class="copy-right pop-up scroll-pop-up">Copyright © 2023 AllCalls.io, Inc, All Rights Reserved</span>
                <div class="further-links">
                    <a class="pop-up scroll-pop-up d1" href="#">Support</a>
                    <a class="pop-up scroll-pop-up d2" href="#">Terms of Service</a>
                    <a class="pop-up scroll-pop-up d3" href="#"> Privacy Policy</a>
                    <a class="EULA pop-up scroll-pop-up d4" href="#">EULA</a>
                </div>
                <div class="contact">
                    <div class="itm pop-up scroll-pop-up d5">
                        <img class="icon" src="images/sms-icon.svg">
                        <span class="txt">support@allcalls.io</span>
                    </div>
                    <div class="itm pop-up scroll-pop-up d5">
                        <img class="icon" src="images/location-icon.svg">
                        <span class="txt">1309 COFFEEN AVE STE 11246SHERIDAN, WY 82801, USA</span>
                    </div>
                    <div class="itm pop-up scroll-pop-up d5">
                        <img class="icon" src="images/call-icon.svg">
                        <span class="txt">(855) 815-0382</span>
                    </div>
                </div>
            </div>

        </div>
    </footer>
    <!-- <script src="js/main.js"></script> -->


    <script>
        let hamburger = document.querySelector('.hamburger');
        let loadingPopups = document.querySelectorAll('.loading-pop-up');
        let scrollPopups = document.querySelectorAll('.scroll-pop-up');
        let joinUsBtns = document.querySelectorAll('.join-us-btn');
        let joinUsModal = document.querySelector('.join-us-modal');
        let joinUsModalForm = document.querySelector('.join-us-modal form');
        let joinUsModalSubmitBtn = document.querySelector('.join-us-modal form .submit-btn');
        let cloaseJoinUsModalBtn = document.querySelector('.join-us-modal .close-btn');
        let calendlyModal = document.querySelector('.calendly-modal');
        let cloasecalendlyModalBtn = document.querySelector('.calendly-modal .close-btn');

        window.addEventListener('scroll', scrolled)

        loadingPopups.forEach(popup => popup.classList.add('animat-popup'))

        scrollPopups.forEach(popup => {

            let triggerPopUpTop = popup.getBoundingClientRect().top - window.innerHeight - 100

            if (triggerPopUpTop < 0) {
                popup.classList.add('animat-popup')

            }

        })

        function scrolled() {


            scrollPopups.forEach(popup => {

                let triggerPopUpTop = popup.getBoundingClientRect().top - window.innerHeight / 1.1

                if (triggerPopUpTop < 0) {
                    popup.classList.add('animat-popup')
                    // console.log(popup);
                }

            })
        }

        hamburger.addEventListener('click', hamburgerClicked)

        function hamburgerClicked() {
            document.body.classList.toggle('hamburger-Clicked')
        }


        joinUsBtns.forEach(btn => btn.addEventListener('click', () => {
            joinUsModal.classList.add('exist')
        }))
        cloaseJoinUsModalBtn.addEventListener('click', () => {
            joinUsModal.classList.remove('exist')
        })
        joinUsModalSubmitBtn.addEventListener('click', () => {
            joinUsModal.classList.remove('exist')
            calendlyModal.classList.add('exist')
            document.querySelector('.calendly-inline-widget').scrollTo(0, 900)
        })
        joinUsModalForm.addEventListener("submit", function(e) {
            e.preventDefault();

        })

        cloasecalendlyModalBtn.addEventListener('click', () => {
            calendlyModal.classList.remove('exist')
        })
    </script>
</body>

</html>