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
    </style>
</head>
<body>
<h3 class="text-center title text-white pt-2 pb-2">Registration Steps - Legal Questions</h3>


<div class="section-border">
    <p>
        <strong>1. &nbsp;</strong>
        Have you ever been charged or convicted of, or pled guilty or no contest to, any felony, misdemeanor,
        federal/state insurance and/or securities or investments regulations or statutes?
    </p>
    <p>
        <input type="radio"
               @if(isset($questions[0]) && $questions[0]['name'] == 'convicted_checkbox_1' && $questions[0]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[0]) && $questions[0]['name'] == 'convicted_checkbox_1' && $questions[0]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[0]) && $questions[0]['name'] == 'convicted_checkbox_1' && $questions[0]['value'] == 'YES')
        <p>
           {{$questions[0]['description']}}
        </p>
    @endif

</div>

<div class="section-border">
    <p>
        <strong>1A. &nbsp;</strong>
        Have you ever been convicted of, or pled guilty or no contest to, any felony?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[1]) && $questions[1]['name'] == 'convicted_checkbox_1a' && $questions[1]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[1]) && $questions[1]['name'] == 'convicted_checkbox_1a' && $questions[1]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[1]) && $questions[1]['name'] == 'convicted_checkbox_1a' && $questions[1]['value'] == 'YES')
        <p>
            {{$questions[1]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>1B. &nbsp;</strong>
        Have you ever been convicted of, or pled guilty or no contest to, any misdemeanor?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[2]) && $questions[2]['name'] == 'convicted_checkbox_1b' && $questions[2]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[2]) && $questions[2]['name'] == 'convicted_checkbox_1b' && $questions[2]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[2]) && $questions[2]['name'] == 'convicted_checkbox_1b' && $questions[2]['value'] == 'YES')
        <p>
            {{$questions[2]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>1C. &nbsp;</strong>
        Have you ever been convicted of, or pled guilty or no contest to, a violation of federal or state securities or
        investment-related regulations?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[3]) && $questions[3]['name'] == 'convicted_checkbox_1c' && $questions[3]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[3]) && $questions[3]['name'] == 'convicted_checkbox_1c' && $questions[3]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[3]) && $questions[3]['name'] == 'convicted_checkbox_1c' && $questions[3]['value'] == 'YES')
        <p>
            {{$questions[3]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>1D. &nbsp;</strong>
        Have you ever been convicted of, or pled guilty or no contest to, a violation of state insurance department
        regulation or statute?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[4]) && $questions[4]['name'] == 'convicted_checkbox_1d' && $questions[4]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[4]) && $questions[4]['name'] == 'convicted_checkbox_1d' && $questions[4]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[4]) && $questions[4]['name'] == 'convicted_checkbox_1d' && $questions[4]['value'] == 'YES')
        <p>
            {{$questions[4]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>1E. &nbsp;</strong>
        Has any foreign government, court, regulatory agency, or exchange ever entered an order against you related to
        income investments or fraud?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[5]) && $questions[5]['name'] == 'convicted_checkbox_1e' && $questions[5]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[5]) && $questions[5]['name'] == 'convicted_checkbox_1e' && $questions[5]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[5]) && $questions[5]['name'] == 'convicted_checkbox_1e' && $questions[5]['value'] == 'YES')
        <p>
            {{$questions[5]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>1F. &nbsp;</strong>
        Have you ever been charged with a felony?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[6]) && $questions[6]['name'] == 'convicted_checkbox_1f' && $questions[6]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[6]) && $questions[6]['name'] == 'convicted_checkbox_1f' && $questions[6]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[6]) && $questions[6]['name'] == 'convicted_checkbox_1f' && $questions[6]['value'] == 'YES')
        <p>
            {{$questions[6]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>1G. &nbsp;</strong>
        Have you ever been charged with a misdemeanor?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[7]) && $questions[7]['name'] == 'convicted_checkbox_1g' && $questions[7]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[7]) && $questions[7]['name'] == 'convicted_checkbox_1g' && $questions[7]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[7]) && $questions[7]['name'] == 'convicted_checkbox_1g' && $questions[7]['value'] == 'YES')
        <p>
            {{$questions[7]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>1H. &nbsp;</strong>
        Have you ever been on probation?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[8]) && $questions[8]['name'] == 'convicted_checkbox_1h' && $questions[8]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[8]) && $questions[8]['name'] == 'convicted_checkbox_1h' && $questions[8]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[8]) && $questions[8]['name'] == 'convicted_checkbox_1h' && $questions[8]['value'] == 'YES')
        <p>
            {{$questions[8]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>2. &nbsp;</strong>
        Have you ever been, or are you currently being investigated, have any pending indictments, lawsuits, or have you
        ever been in a lawsuit with an insurance company?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[9]) && $questions[9]['name'] == 'lawsuit_checkbox_2' && $questions[9]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[9]) && $questions[9]['name'] == 'lawsuit_checkbox_2' && $questions[9]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[9]) && $questions[9]['name'] == 'lawsuit_checkbox_2' && $questions[9]['value'] == 'YES')
        <p>
            {{$questions[9]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>2A. &nbsp;</strong>
        Are you currently under investigation by any legal or regulatory agency?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[10]) && $questions[10]['name'] == 'lawsuit_checkbox_2a' && $questions[10]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[10]) && $questions[10]['name'] == 'lawsuit_checkbox_2a' && $questions[10]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[10]) && $questions[10]['name'] == 'lawsuit_checkbox_2a' && $questions[10]['value'] == 'YES')
        <p>
            {{$questions[10]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>2B. &nbsp;</strong>
        Have you been under investigation by any insurance company?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[11]) && $questions[11]['name'] == 'lawsuit_checkbox_2b' && $questions[11]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[11]) && $questions[11]['name'] == 'lawsuit_checkbox_2b' && $questions[11]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[11]) && $questions[11]['name'] == 'lawsuit_checkbox_2b' && $questions[11]['value'] == 'YES')
        <p>
            {{$questions[11]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>2C. &nbsp;</strong>
        Have you ever been, or are you currently involved in any pending indictments, lawsuits, civil judgements or
        other legal proceedings (civil or criminal) (you may omit family court)?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[12]) && $questions[12]['name'] == 'lawsuit_checkbox_2c' && $questions[12]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[12]) && $questions[12]['name'] == 'lawsuit_checkbox_2c' && $questions[12]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[12]) && $questions[12]['name'] == 'lawsuit_checkbox_2c' && $questions[12]['value'] == 'YES')
        <p>
            {{$questions[12]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>2D. &nbsp;</strong>
        Have you ever been named as a defendant or co-defendant in a lawsuit, or have you ever sued or been sued by an
        insurance company?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[13]) && $questions[13]['name'] == 'lawsuit_checkbox_2d' && $questions[13]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[13]) && $questions[13]['name'] == 'lawsuit_checkbox_2d' && $questions[13]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[13]) && $questions[13]['name'] == 'lawsuit_checkbox_2d' && $questions[13]['value'] == 'YES')
        <p>
            {{$questions[13]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>3. &nbsp;</strong>
        Have you ever been alleged to have engaged in any fraud?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[14]) && $questions[14]['name'] == 'alleged_engaged_checkbox_3' && $questions[14]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[14]) && $questions[14]['name'] == 'alleged_engaged_checkbox_3' && $questions[14]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[14]) && $questions[14]['name'] == 'alleged_engaged_checkbox_3' && $questions[14]['value'] == 'YES')
        <p>
            {{$questions[14]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>4. &nbsp;</strong>
        Have you ever been found to have engaged in any fraud?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[15]) && $questions[15]['name'] == 'found_engaged_checkbox_4' && $questions[15]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[15]) && $questions[15]['name'] == 'found_engaged_checkbox_4' && $questions[15]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[15]) && $questions[15]['name'] == 'found_engaged_checkbox_4' && $questions[15]['value'] == 'YES')
        <p>
            {{$questions[15]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>5. &nbsp;</strong>
        Has any insurance or financial services company or broker-dealer terminated your contract or appointment or
        permitted you to resign for reason other than lack of sales?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[16]) && $questions[16]['name'] == 'terminate_contract_checkbox_5' && $questions[16]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[16]) && $questions[16]['name'] == 'terminate_contract_checkbox_5' && $questions[16]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[16]) && $questions[16]['name'] == 'terminate_contract_checkbox_5' && $questions[16]['value'] == 'YES')
        <p>
            {{$questions[16]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>5A. &nbsp;</strong>
        Were you fired because you were accused of violating insurance or investment-related statutes, regulations,
        rules, or industry standards of conduct?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[17]) && $questions[17]['name'] == 'terminate_contract_checkbox_5a' && $questions[17]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[17]) && $questions[17]['name'] == 'terminate_contract_checkbox_5a' && $questions[17]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[17]) && $questions[17]['name'] == 'terminate_contract_checkbox_5a' && $questions[17]['value'] == 'YES')
        <p>
            {{$questions[17]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>5B. &nbsp;</strong>
        Were you fired because you were accused of fraud or the wrongful taking of property?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[18]) && $questions[18]['name'] == 'terminate_contract_checkbox_5b' && $questions[18]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[18]) && $questions[18]['name'] == 'terminate_contract_checkbox_5b' && $questions[18]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[18]) && $questions[18]['name'] == 'terminate_contract_checkbox_5b' && $questions[18]['value'] == 'YES')
        <p>
            {{$questions[18]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>5C. &nbsp;</strong>
        Failure to supervise in connection with insurance or investment-related statutes, regulations, rules, or
        industry standards of conduct?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[19]) && $questions[19]['name'] == 'terminate_contract_checkbox_5c' && $questions[19]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[19]) && $questions[19]['name'] == 'terminate_contract_checkbox_5c' && $questions[19]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[19]) && $questions[19]['name'] == 'terminate_contract_checkbox_5c' && $questions[19]['value'] == 'YES')
        <p>
            {{$questions[19]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>6. &nbsp;</strong>
        Have you ever had an appointment with any insurance company denied or terminated for cause? (If you have been
        reported to Vector One, answer YES)
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[20]) && $questions[20]['name'] == 'cancel_insu_cause_checkbox_6' && $questions[20]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[20]) && $questions[20]['name'] == 'cancel_insu_cause_checkbox_6' && $questions[20]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[20]) && $questions[20]['name'] == 'cancel_insu_cause_checkbox_6' && $questions[20]['value'] == 'YES')
        <p>
            {{$questions[20]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>7. &nbsp;</strong>
        Does any insurer, insured, or other person claim any commission chargeback or other indebtedness from you as a
        result of any insurance transactions or business? (If you have been reported to Vector One, answer YES)
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[21]) && $questions[21]['name'] == 'insurer_checkbox_7' && $questions[21]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[21]) && $questions[21]['name'] == 'insurer_checkbox_7' && $questions[21]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[21]) && $questions[21]['name'] == 'insurer_checkbox_7' && $questions[21]['value'] == 'YES')
        <p>
            {{$questions[21]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>8. &nbsp;</strong>
        Has any lawsuit or claim ever been made against you, your surety company, or errors and omissions insurer,
        arising out of your sales or practices, or have you been refused surety bonding or E&O coverage?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[22]) && $questions[22]['name'] == 'lawsuit_checkbox_8' && $questions[22]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[22]) && $questions[22]['name'] == 'lawsuit_checkbox_8' && $questions[22]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[22]) && $questions[22]['name'] == 'lawsuit_checkbox_8' && $questions[22]['value'] == 'YES')
        <p>
            {{$questions[22]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>8A. &nbsp;</strong>
        Has a bonding or surety company ever denied, paid on, or revoked a bond for you? 8B. Has any Errors & Omissions
        company ever denied, paid claims on, or cancelled your coverage?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[23]) && $questions[23]['name'] == 'lawsuit_checkbox_8a' && $questions[23]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[23]) && $questions[23]['name'] == 'lawsuit_checkbox_8a' && $questions[23]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[23]) && $questions[23]['name'] == 'lawsuit_checkbox_8a' && $questions[23]['value'] == 'YES')
        <p>
            {{$questions[23]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>8B. &nbsp;</strong>
        Has any Errors & Ommisions company ever denied, paid claims on, or cancelled your coverage?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[24]) && $questions[24]['name'] == 'lawsuit_checkbox_8b' && $questions[24]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[24]) && $questions[24]['name'] == 'lawsuit_checkbox_8b' && $questions[24]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[24]) && $questions[24]['name'] == 'lawsuit_checkbox_8b' && $questions[24]['value'] == 'YES')
        <p>
            {{$questions[24]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>9. &nbsp;</strong>
        Have you ever had an insurance or securities license denied, suspended, cancelled, or revoked?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[25]) && $questions[25]['name'] == 'license_denied_checkbox_9' && $questions[25]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[25]) && $questions[25]['name'] == 'license_denied_checkbox_9' && $questions[25]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[25]) && $questions[25]['name'] == 'license_denied_checkbox_9' && $questions[25]['value'] == 'YES')
        <p>
            {{$questions[25]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>10. &nbsp;</strong>
        Has any state or federal regulatory body found you to have been a cause of an investment or insurance-related
        business having its authorization to do business denied, suspended, revoked, or restricted?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[26]) && $questions[26]['name'] == 'regulatory_checkbox_10' && $questions[26]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[26]) && $questions[26]['name'] == 'regulatory_checkbox_10' && $questions[26]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[26]) && $questions[26]['name'] == 'regulatory_checkbox_10' && $questions[26]['value'] == 'YES')
        <p>
            {{$questions[26]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>11. &nbsp;</strong>
        Has any state or federal regulatory agency revoked or suspended your license as an attorney, accountant, or
        federal contractor?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[27]) && $questions[27]['name'] == 'regulatory_revoked_checkbox_11' && $questions[27]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[27]) && $questions[27]['name'] == 'regulatory_revoked_checkbox_11' && $questions[27]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[27]) && $questions[27]['name'] == 'regulatory_revoked_checkbox_11' && $questions[27]['value'] == 'YES')
        <p>
            {{$questions[27]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>12. &nbsp;</strong>
        Has any state or federal regulatory agency found you to have made a false statement or omission or been
        dishonest, unfair, or unethical?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[28]) && $questions[28]['name'] == 'regulatory_found_checkbox_12' && $questions[28]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[28]) && $questions[28]['name'] == 'regulatory_found_checkbox_12' && $questions[28]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[28]) && $questions[28]['name'] == 'regulatory_found_checkbox_12' && $questions[28]['value'] == 'YES')
        <p>
            {{$questions[28]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>13. &nbsp;</strong>
        Have you ever had any interruptions in licensing?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[29]) && $questions[29]['name'] == 'interr_licensing_checkbox_13' && $questions[29]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[29]) && $questions[29]['name'] == 'interr_licensing_checkbox_13' && $questions[29]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[29]) && $questions[29]['name'] == 'interr_licensing_checkbox_13' && $questions[29]['value'] == 'YES')
        <p>
            {{$questions[29]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>14. &nbsp;</strong>
        Has any state, federal, or self-regulatory agency filed a complaint against you, fined, sanctioned, censured,
        penalized, or otherwise disciplined you for a violation of their regulations or state or federal statuses?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[30]) && $questions[30]['name'] == 'self_regularity_checkbox_14' && $questions[30]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[30]) && $questions[30]['name'] == 'self_regularity_checkbox_14' && $questions[30]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[30]) && $questions[30]['name'] == 'self_regularity_checkbox_14' && $questions[30]['value'] == 'YES')
        <p>
            {{$questions[30]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>14A. &nbsp;</strong>
        Has any regulatory body ever sanctioned, censured, penalized, or otherwise disciplined you?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[31]) && $questions[31]['name'] == 'self_regularity_checkbox_14a' && $questions[31]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[31]) && $questions[31]['name'] == 'self_regularity_checkbox_14a' && $questions[31]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[31]) && $questions[31]['name'] == 'self_regularity_checkbox_14a' && $questions[31]['value'] == 'YES')
        <p>
            {{$questions[31]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>14B. &nbsp;</strong>
        Has any state, federal, or self-regulatory agency filed a complaint against you, fined, or sanctioned you?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[32]) && $questions[32]['name'] == 'self_regularity_checkbox_14b' && $questions[32]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[32]) && $questions[32]['name'] == 'self_regularity_checkbox_14b' && $questions[32]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[32]) && $questions[32]['name'] == 'self_regularity_checkbox_14b' && $questions[32]['value'] == 'YES')
        <p>
            {{$questions[32]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>14C. &nbsp;</strong>
        Have you ever been the subject of a consumer-initiated complaint?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[33]) && $questions[33]['name'] == 'self_regularity_checkbox_14c' && $questions[33]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[33]) && $questions[33]['name'] == 'self_regularity_checkbox_14c' && $questions[33]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[33]) && $questions[33]['name'] == 'self_regularity_checkbox_14c' && $questions[33]['value'] == 'YES')
        <p>
            {{$questions[33]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>15. &nbsp;</strong>
        Have you personally, or any insurance or securities brokerage firm with whom you have been associated, filed a
        bankruptcy petition, or declared bankruptcy? 15A. Have you personally filed a bankruptcy petition or declared
        bankruptcy?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[34]) && $questions[34]['name'] == 'bankruptcy_checkbox_15' && $questions[34]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[34]) && $questions[34]['name'] == 'bankruptcy_checkbox_15' && $questions[34]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[34]) && $questions[34]['name'] == 'bankruptcy_checkbox_15' && $questions[34]['value'] == 'YES')
        <p>
            {{$questions[34]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>15A. &nbsp;</strong>
        Have you personally, or any insurance or securities brokerage firm with whom you have been associated, filed a
        bankruptcy petition, or declared bankruptcy? 15A. Have you personally filed a bankruptcy petition or declared
        bankruptcy?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[35]) && $questions[35]['name'] == 'bankruptcy_checkbox_15a' && $questions[35]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[35]) && $questions[35]['name'] == 'bankruptcy_checkbox_15a' && $questions[35]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[35]) && $questions[35]['name'] == 'bankruptcy_checkbox_15a' && $questions[35]['value'] == 'YES')
        <p>
            {{$questions[35]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>15B. &nbsp;</strong>
        Has any insurance or securities brokerage firm, with whom you have been associated, filed a bankruptcy petition,
        or been declared bankrupt, either during your association with them or within 5 years after termination of such
        an association?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[36]) && $questions[36]['name'] == 'bankruptcy_checkbox_15b' && $questions[36]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[36]) && $questions[36]['name'] == 'bankruptcy_checkbox_15b' && $questions[36]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[36]) && $questions[36]['name'] == 'bankruptcy_checkbox_15b' && $questions[36]['value'] == 'YES')
        <p>
            {{$questions[36]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>15C. &nbsp;</strong>
        Is the bankruptcy pending?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[37]) && $questions[37]['name'] == 'bankruptcy_checkbox_15c' && $questions[37]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[37]) && $questions[37]['name'] == 'bankruptcy_checkbox_15c' && $questions[37]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[37]) && $questions[37]['name'] == 'bankruptcy_checkbox_15c' && $questions[37]['value'] == 'YES')
        <p>
            {{$questions[37]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>16. &nbsp;</strong>
        Are there any unsatisfied judgements or liens against you?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[38]) && $questions[38]['name'] == 'liens_against_checkbox_16' && $questions[38]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[38]) && $questions[38]['name'] == 'liens_against_checkbox_16' && $questions[38]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[38]) && $questions[38]['name'] == 'liens_against_checkbox_16' && $questions[38]['value'] == 'YES')
        <p>
            {{$questions[38]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>17. &nbsp;</strong>
        Are you connected in any way with a bank, savings & loan association, or other lending or financial institution?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[39]) && $questions[39]['name'] == 'connected_checkbox_17' && $questions[39]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[39]) && $questions[39]['name'] == 'connected_checkbox_17' && $questions[39]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[39]) && $questions[39]['name'] == 'connected_checkbox_17' && $questions[39]['value'] == 'YES')
        <p>
            {{$questions[39]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>18. &nbsp;</strong>
        Have you ever used any other names or aliases?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[40]) && $questions[40]['name'] == 'aliases_checkbox_18' && $questions[40]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[40]) && $questions[40]['name'] == 'aliases_checkbox_18' && $questions[40]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[40]) && $questions[40]['name'] == 'aliases_checkbox_18' && $questions[40]['value'] == 'YES')
        <p>
            {{$questions[40]['description']}}
        </p>
    @endif
</div>

<div class="section-border">
    <p>
        <strong>19. &nbsp;</strong>
        Do you have any unresolved matters pending with the Internal Revenue Service, or other taxing authority?
    </p>

    <p>
        <input type="radio"
               @if(isset($questions[41]) && $questions[41]['name'] == 'unresolved_matter_checkbox_19' && $questions[41]['value'] == 'YES') checked
               @endif class="radio-option"> <span class="radio-values">YES</span>
        <input type="radio"
               @if(isset($questions[41]) && $questions[41]['name'] == 'unresolved_matter_checkbox_19' && $questions[41]['value'] == 'NO') checked
               @endif class="radio-option"> <span class="radio-values">NO</span>
    </p>

    @if(isset($questions[41]) && $questions[41]['name'] == 'unresolved_matter_checkbox_19' && $questions[41]['value'] == 'YES')
        <p>
            {{$questions[41]['description']}}
        </p>
    @endif
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
