<!DOCTYPE html>
<html>
<head>
    <title>Legal Questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        .title {
            background-color: rgb(19, 69, 118);
            border-radius: 10px;
            color: white;
            padding: 7px 0px;
            text-align: center;
        }

        .section-border {
            border-bottom: 2px solid dimgray;
        }

        .radio-option {
            margin-left: 10px;
            margin-top: 10px
        }

        .radio-values {
            margin-left: 10px
        }

        p {
            line-height: 25px;
        }

        .w-25 {
         ;
        }

        .mt-3 {
            margin-top: 30px;
        }

        .mb-3 {
            margin-top: 30px;
        }

        .w-100 {
            width: 100% !important;
        }

        .line-h30 {
            line-height: 30px !important;
        }

        .line-h35 {
            line-height: 35px !important;
        }

        .d-flex {
            display: flex !important;
            flex-direction: row;
            justify-content: space-between;
        }

        .d-flex > div {
            width: 25%;
        }

        .text-end {
            text-align: end;
        }

    </style>
</head>
<body>

{{--Start Inforamtion--}}
@if($contractData->internalAgentContract)
    @include('PDF.basic-information')
@endif
{{--End Inforamtion--}}


{{--Start Legal Question Section--}}
@if(isset($contractData->internalAgentContract) && count($contractData->internalAgentContract->legalQuestion))
    @include('PDF.legal-questions')
@endif
{{--End Legal Question Section--}}

{{--Start Address History--}}
@if(isset($contractData->internalAgentContract) && count($contractData->internalAgentContract->addresses))
    @include('PDF.address-history')
@endif
{{--End Address History--}}

{{--Start Additional Info--}}
@if(isset($contractData->internalAgentContract) && isset($contractData->internalAgentContract->additionalInfo))
    @include('PDF.additional-info')
@endif
{{--End Additional Info--}}

{{--Start Signature--}}
{{--@if(isset($contractData->internalAgentContract) && isset($contractData->internalAgentContract->additionalInfo))--}}
    @include('PDF.signature')
{{--@endif--}}
{{--End Signature--}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
