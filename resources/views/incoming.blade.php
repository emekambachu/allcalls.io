<!DOCTYPE html>
<html>

<head>
    <title>In browser calls</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="/twilio/style.css">
</head>

<body>

    <div class="modal" id="modal-dial">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="text-align: center;">Enter the number</h4>
                    <button type="button" class="btn btn-danger close" id="btnCloseDialModal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body mx-auto">
                    <div class="btn-group-vertical  my-5" role="group" aria-label="Basic example">
                        <input id="phoneNumber" type="tel"></input>
                        <div class="btn-group btn-group-lg ">
                            <button type="button"
                                class="btn btn-outline-secondary border-bottom-0 rounded-0 btnNumber">1</button>
                            <button type="button"
                                class="btn btn-outline-secondary border-bottom-0 btnNumber">2</button>
                            <button type="button"
                                class="btn btn-outline-secondary border-bottom-0 rounded-0 btnNumber">3</button>
                        </div>
                        <div class="btn-group btn-group-lg">
                            <button type="button"
                                class="btn btn-outline-secondary border-bottom-0 rounded-0 btnNumber">4</button>
                            <button type="button"
                                class="btn btn-outline-secondary border-bottom-0 btnNumber">5</button>
                            <button type="button"
                                class="btn btn-outline-secondary border-bottom-0 rounded-0 btnNumber">6</button>
                        </div>
                        <div class="btn-group btn-group-lg">
                            <button type="button" class="btn btn-outline-secondary rounded-0 btnNumber">7</button>
                            <button type="button" class="btn btn-outline-secondary btnNumber">8</button>
                            <button type="button" class="btn btn-outline-secondary rounded-0 btnNumber">9</button>
                        </div>
                        <div class="btn-group btn-group-lg">
                            <button type="button" class="btn btn-outline-secondary rounded-0 btnNumber">*</button>
                            <button type="button" class="btn btn-outline-secondary btnNumber">0</button>
                            <button type="button" class="btn btn-outline-secondary rounded-0 btnNumber"><span
                                    class="small">#</span></button>
                        </div>
                        <div class="btn-group btn-group-lg">
                            <button id="btnDial" type="button" class="btn btn-outline-secondary rounded-0">
                                <i class="fa fa-phone-square fa-flip-horizontal  fa-2x" style="color: green;"
                                    aria-hidden="true"></i> </button>
                            <button type="button" class="btn btn-outline-secondary btnNumber">+</button>
                            <button id="btnDelete" type="button" class="btn btn-outline-secondary rounded-0">
                                <i class="fa fa-backspace fa-1x" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- log output -->
        <div class="card text-center log-container">
            <h3>Device log</h3>
            <div id="log"></div>
            <div class="btn-container">
                <button type="button" id="btnOpenNumberPad" class="btn btn-default btn-circle btn-lg">
                    <i class="fa fa-phone fa-flip-horizontal " aria-hidden="true" style="color: green;"></i>
                </button>
            </div>
        </div>

        <div class="modal" id="modal-call-in-progress" style="width: 300px; height: 600px;margin-left: 560px;">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <div id="callDuration"></div>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body" id="modal-call-in-progress-body" style="height: 300px;">
                        <div class="mx-auto">
                            <h4 id="txtPhoneNumber" style="text-align: center;"></h4>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer mx-auto">
                        <button type="button" class="btn btn btn-default btn-circle btnHangUp">
                            <i class="fa fa-phone fa-1x fa-rotate-icon" aria-hidden="true" style="color: red;"></i>
                        </button>
                    </div>
                    <div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="modal-incomming-call" style="width: 300px; height: 600px;margin-left: 560px;">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header mx-auto">
                        <h4 id="callerNumber">+1230090003030</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body" id="modal-incomming-call-body" style="height: 300px;">
                    </div>


                    <!-- Modal Footer -->
                    <div class="modal-footer mx-auto">
                        <button class="btnAcceptCall btn btn-default btn-circle" type="button">
                            <i class="fa fa-phone fa-flip-horizontal  fa-1x " aria-hidden="true"
                                style="color: green;"></i>
                        </button>
                        <button type="button" class="btn btn-default btn-circle btnReject">
                            <i class="fa fa-phone fa-1x fa-rotate-icon" aria-hidden="true" style="color: red;"></i>
                        </button>
                    </div>

                    <div>
                    </div>
                </div>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous">
        </script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://media.twiliocdn.com/sdk/js/client/v1.8/twilio.min.js"></script>
        <script type="text/javascript" src="/twilio/main.js"></script>
        <script type="text/javascript" src="/twilio/modals.js" defer></script>
</body>

</html>
