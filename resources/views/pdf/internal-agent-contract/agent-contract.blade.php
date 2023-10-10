<!DOCTYPE html>
<html>
<head>
    <title>Contracting</title>
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
         width: 25% !important;
        }

        .mt-2 {
            margin-top: 20px;
        }

        .mb-2 {
            margin-bottom: 20px;
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

        .line-h40 {
            line-height: 40px !important;
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

        .basic-info-element
        {
            border: 1px solid lightgray;
            padding:0px 2px;
            border-radius: 5px;
            display: flex;
            width: 95%;
            margin-bottom: 10px;
            margin-top: 10px
        }

        .signature-authorization {
            page-break-before: always;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>

<embed src="{{ $contractData->internalAgentContract->amlCourse->url }}" width="100%" height="600px" type="application/pdf">

{{--Start Inforamtion--}}
@if($contractData->internalAgentContract)
    @include('pdf.internal-agent-contract.basic-information')
@endif
{{--End Inforamtion--}}


{{--Start Legal Question Section--}}
@if(isset($contractData->internalAgentContract) && count($contractData->internalAgentContract->legalQuestion))
    @include('pdf.internal-agent-contract.legal-questions')
@endif
{{--End Legal Question Section--}}

{{--Start Address History--}}
@if(isset($contractData->internalAgentContract) && count($contractData->internalAgentContract->addresses))
    @include('pdf.internal-agent-contract.address-history')
@endif
{{--End Address History--}}

{{--Start Additional Info--}}
@if(isset($contractData->internalAgentContract) && isset($contractData->internalAgentContract->additionalInfo))
    @include('pdf.internal-agent-contract.additional-info')
@endif
{{--End Additional Info--}}

{{--Start Signature--}}
@if(isset($contractData->internalAgentContract) && $contractData->internalAgentContract->getQuestionSign)
    @include('pdf.internal-agent-contract.accompanying-sign')
@endif
{{--End Signature--}}

{{--Start Signature Authorization--}}
@if(isset($contractData->internalAgentContract) && $contractData->internalAgentContract->getContractSign)
    @include('pdf.internal-agent-contract.signature')
@endif
{{--End Signature Authorization--}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
