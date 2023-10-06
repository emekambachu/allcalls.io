<div class="mt-3">
    <h3 class="title">Contracting - Legal Question Explanation</h3>
    <p style="font-weight: bold">
        For contracting and appointment requests, please answer the following questions. If you answer YES to any
        question, you must provide documentation including a full, detailed explanation and specific dates. Please
        answer every question including sub questions for clarity.
    </p>
    @foreach($contractData->internalAgentContract->legalQuestion as $question)
        @if($question->name == 'convicted_checkbox_1')
            <div class="section-border">
                <p>
                    <strong>1. &nbsp;</strong>
                    Have you ever been charged or convicted of, or pled guilty or no contest to, any felony,
                    misdemeanor,
                    federal/state insurance and/or securities or investments regulations or statutes?
                </p>
                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'convicted_checkbox_1a')
            <div class="section-border">
                <p>
                    <strong>1A. &nbsp;</strong>
                    Have you ever been convicted of, or pled guilty or no contest to, any felony?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'convicted_checkbox_1b')
            <div class="section-border">
                <p>
                    <strong>1B. &nbsp;</strong>
                    Have you ever been convicted of, or pled guilty or no contest to, any misdemeanor?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'convicted_checkbox_1c')
            <div class="section-border">
                <p>
                    <strong>1C. &nbsp;</strong>
                    Have you ever been convicted of, or pled guilty or no contest to, a violation of federal or state
                    securities
                    or
                    investment-related regulations?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'convicted_checkbox_1d')
            <div class="section-border">
                <p>
                    <strong>1D. &nbsp;</strong>
                    Have you ever been convicted of, or pled guilty or no contest to, a violation of state insurance
                    department
                    regulation or statute?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'convicted_checkbox_1e')
            <div class="section-border">
                <p>
                    <strong>1E. &nbsp;</strong>
                    Has any foreign government, court, regulatory agency, or exchange ever entered an order against you
                    related
                    to
                    income investments or fraud?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'convicted_checkbox_1f')
            <div class="section-border">
                <p>
                    <strong>1F. &nbsp;</strong>
                    Have you ever been charged with a felony?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'convicted_checkbox_1g')
            <div class="section-border">
                <p>
                    <strong>1G. &nbsp;</strong>
                    Have you ever been charged with a misdemeanor?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'convicted_checkbox_1h')
            <div class="section-border">
                <p>
                    <strong>1H. &nbsp;</strong>
                    Have you ever been on probation?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'lawsuit_checkbox_2')
            <div class="section-border">
                <p>
                    <strong>2. &nbsp;</strong>
                    Have you ever been, or are you currently being investigated, have any pending indictments, lawsuits,
                    or have
                    you
                    ever been in a lawsuit with an insurance company?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'lawsuit_checkbox_2a')
            <div class="section-border">
                <p>
                    <strong>2A. &nbsp;</strong>
                    Are you currently under investigation by any legal or regulatory agency?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'lawsuit_checkbox_2b')
            <div class="section-border">
                <p>
                    <strong>2B. &nbsp;</strong>
                    Have you been under investigation by any insurance company?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'lawsuit_checkbox_2c')
            <div class="section-border">
                <p>
                    <strong>2C. &nbsp;</strong>
                    Have you ever been, or are you currently involved in any pending indictments, lawsuits, civil
                    judgements or
                    other legal proceedings (civil or criminal) (you may omit family court)?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'lawsuit_checkbox_2d')
            <div class="section-border">
                <p>
                    <strong>2D. &nbsp;</strong>
                    Have you ever been named as a defendant or co-defendant in a lawsuit, or have you ever sued or been
                    sued by
                    an
                    insurance company?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'alleged_engaged_checkbox_3')
            <div class="section-border">
                <p>
                    <strong>3. &nbsp;</strong>
                    Have you ever been alleged to have engaged in any fraud?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'found_engaged_checkbox_4')
            <div class="section-border">
                <p>
                    <strong>4. &nbsp;</strong>
                    Have you ever been found to have engaged in any fraud?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'terminate_contract_checkbox_5')
            <div class="section-border">
                <p>
                    <strong>5. &nbsp;</strong>
                    Has any insurance or financial services company or broker-dealer terminated your contract or
                    appointment or
                    permitted you to resign for reason other than lack of sales?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'terminate_contract_checkbox_5a')
            <div class="section-border">
                <p>
                    <strong>5A. &nbsp;</strong>
                    Were you fired because you were accused of violating insurance or investment-related statutes,
                    regulations,
                    rules, or industry standards of conduct?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'terminate_contract_checkbox_5b')
            <div class="section-border">
                <p>
                    <strong>5B. &nbsp;</strong>
                    Were you fired because you were accused of fraud or the wrongful taking of property?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'terminate_contract_checkbox_5c')
            <div class="section-border">
                <p>
                    <strong>5C. &nbsp;</strong>
                    Failure to supervise in connection with insurance or investment-related statutes, regulations,
                    rules, or
                    industry standards of conduct?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'cancel_insu_cause_checkbox_6')
            <div class="section-border">
                <p>
                    <strong>6. &nbsp;</strong>
                    Have you ever had an appointment with any insurance company denied or terminated for cause? (If you
                    have
                    been
                    reported to Vector One, answer YES)
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'insurer_checkbox_7')
            <div class="section-border">
                <p>
                    <strong>7. &nbsp;</strong>
                    Does any insurer, insured, or other person claim any commission chargeback or other indebtedness
                    from you as
                    a
                    result of any insurance transactions or business? (If you have been reported to Vector One, answer
                    YES)
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'lawsuit_checkbox_8')
            <div class="section-border">
                <p>
                    <strong>8. &nbsp;</strong>
                    Has any lawsuit or claim ever been made against you, your surety company, or errors and omissions
                    insurer,
                    arising out of your sales or practices, or have you been refused surety bonding or E&O coverage?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'lawsuit_checkbox_8a')
            <div class="section-border">
                <p>
                    <strong>8A. &nbsp;</strong>
                    Has a bonding or surety company ever denied, paid on, or revoked a bond for you? 8B. Has any Errors
                    &
                    Omissions
                    company ever denied, paid claims on, or cancelled your coverage?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'lawsuit_checkbox_8b')
            <div class="section-border">
                <p>
                    <strong>8B. &nbsp;</strong>
                    Has any Errors & Ommisions company ever denied, paid claims on, or cancelled your coverage?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'license_denied_checkbox_9')
            <div class="section-border">
                <p>
                    <strong>9. &nbsp;</strong>
                    Have you ever had an insurance or securities license denied, suspended, cancelled, or revoked?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'regulatory_checkbox_10')
            <div class="section-border">
                <p>
                    <strong>10. &nbsp;</strong>
                    Has any state or federal regulatory body found you to have been a cause of an investment or
                    insurance-related
                    business having its authorization to do business denied, suspended, revoked, or restricted?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'regulatory_revoked_checkbox_11')
            <div class="section-border">
                <p>
                    <strong>11. &nbsp;</strong>
                    Has any state or federal regulatory agency revoked or suspended your license as an attorney,
                    accountant, or
                    federal contractor?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'regulatory_found_checkbox_12')
            <div class="section-border">
                <p>
                    <strong>12. &nbsp;</strong>
                    Has any state or federal regulatory agency found you to have made a false statement or omission or
                    been
                    dishonest, unfair, or unethical?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'interr_licensing_checkbox_13')
            <div class="section-border">
                <p>
                    <strong>13. &nbsp;</strong>
                    Have you ever had any interruptions in licensing?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'self_regularity_checkbox_14')
            <div class="section-border">
                <p>
                    <strong>14. &nbsp;</strong>
                    Has any state, federal, or self-regulatory agency filed a complaint against you, fined, sanctioned,
                    censured,
                    penalized, or otherwise disciplined you for a violation of their regulations or state or federal
                    statuses?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'self_regularity_checkbox_14a')
            <div class="section-border">
                <p>
                    <strong>14A. &nbsp;</strong>
                    Has any regulatory body ever sanctioned, censured, penalized, or otherwise disciplined you?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'self_regularity_checkbox_14b')
            <div class="section-border">
                <p>
                    <strong>14B. &nbsp;</strong>
                    Has any state, federal, or self-regulatory agency filed a complaint against you, fined, or
                    sanctioned you?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'self_regularity_checkbox_14c')
            <div class="section-border">
                <p>
                    <strong>14C. &nbsp;</strong>
                    Have you ever been the subject of a consumer-initiated complaint?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'bankruptcy_checkbox_15')
            <div class="section-border">
                <p>
                    <strong>15. &nbsp;</strong>
                    Have you personally, or any insurance or securities brokerage firm with whom you have been
                    associated, filed
                    a
                    bankruptcy petition, or declared bankruptcy? 15A. Have you personally filed a bankruptcy petition or
                    declared
                    bankruptcy?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'bankruptcy_checkbox_15a')
            <div class="section-border">
                <p>
                    <strong>15A. &nbsp;</strong>
                    Have you personally, or any insurance or securities brokerage firm with whom you have been
                    associated, filed
                    a
                    bankruptcy petition, or declared bankruptcy? 15A. Have you personally filed a bankruptcy petition or
                    declared
                    bankruptcy?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'bankruptcy_checkbox_15b')
            <div class="section-border">
                <p>
                    <strong>15B. &nbsp;</strong>
                    Has any insurance or securities brokerage firm, with whom you have been associated, filed a
                    bankruptcy
                    petition,
                    or been declared bankrupt, either during your association with them or within 5 years after
                    termination of
                    such
                    an association?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'bankruptcy_checkbox_15c')
            <div class="section-border">
                <p>
                    <strong>15C. &nbsp;</strong>
                    Is the bankruptcy pending?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'liens_against_checkbox_16')
            <div class="section-border">
                <p>
                    <strong>16. &nbsp;</strong>
                    Are there any unsatisfied judgements or liens against you?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'connected_checkbox_17')
            <div class="section-border">
                <p>
                    <strong>17. &nbsp;</strong>
                    Are you connected in any way with a bank, savings & loan association, or other lending or financial
                    institution?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'aliases_checkbox_18')
            <div class="section-border">
                <p>
                    <strong>18. &nbsp;</strong>
                    Have you ever used any other names or aliases?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif

        @if($question->name == 'unresolved_matter_checkbox_19')
            <div class="section-border">
                <p>
                    <strong>19. &nbsp;</strong>
                    Do you have any unresolved matters pending with the Internal Revenue Service, or other taxing
                    authority?
                </p>

                <p>
                    <input type="radio" @if($question->value == 'YES') checked @endif class="radio-option">
                    <span class="radio-values">YES</span>
                    <input type="radio" @if($question->value == 'NO') checked @endif class="radio-option">
                    <span class="radio-values">NO</span>
                </p>

                @if($question->value == 'YES')
                    <p>
                        <strong>Explanation: &nbsp;</strong> <br> {{$question->description}}
                    </p>
                @endif
            </div>
        @endif
    @endforeach
    <p style="font-weight: bold">
        I attest that the information I have provided is true to the best of my knowledge. I acknowledge that if any
        information changes, I will notify my agency office within 5 days of such change. Further, I understand that my
        agency may contact me when I need to answer carrier-specific questions.
    </p>
</div>
