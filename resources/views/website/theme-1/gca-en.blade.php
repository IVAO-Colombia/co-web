@extends('website.theme-1.layout.theme-1')
@section('content')
    <!-- Inspiro Slider -->
    <div id="slider" class="inspiro-slider dots-creative" data-height-xs="200">

        <!-- Slide 2 -->
        <div class="slide kenburns" style="background-image:url('{{ asset('img/0091.jpg') }}');">
            <div class="bg-overlay" data-style="10"></div>
            <div class="container">
                <div class="slide-captions text-center text-light">
                    <!-- Captions -->

                    <h1>{{ __('GCA') }}</h1>
                    <!-- end: Captions -->
                </div>
            </div>
        </div>
        <!-- end: Slide 2 -->

    </div>
    <!--end: Inspiro Slider -->


    <section>
        <div class="container">


            <div class="row justify-center flex">
                <div class="content col-md-10 my-2 text-left ">

                    <div class="tag-box tag-box-v1 margin-bottom-40 heading-text heading-light font-light">
                        <p>The training department informs the interested parties about the modification in the division's
                            regulations regarding the
                            Guest Controller Approval or GCA for its acronym. IVAO Colombia has been under the program since
                            2018 under regulations T.2, T.2.1, T2.2.2 and T.2.3.

                        </p>



                        <h2>Definitions</h2>



                        <p>
                            <b>GCA:</b> Acronym for Guest Controller Approval, it is an approval that allows members of
                            other divisions to control in Colombian airspace.
                            <br>

                            <b>FRA:</b> Facility Rating Assignments, establishes the minimum requirements to control a
                            specific ATC facility.
                        </p>

                        <h2 class=" ">Requirements</h2>

                        <p>
                            The Colombian division expects GCA applicants to have a rank that is equivalent to the position
                            they wish to control.
                            For this reason, only users with an ADC (Aerodrome Controller) rank or higher will be
                            considered. The following table shows the positions that can be covered according to the rank:
                        </p>

                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <p>
                                                <strong>Rating</strong>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <strong>ATC Facility</strong>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                Aerodrome Controller (ADC)</p>
                                        </td>
                                        <td>
                                            <p>
                                                DEL/GND/TWR/<strong>*APP</strong></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                Approach Controller (APC)</p>
                                        </td>
                                        <td>
                                            <p>
                                                DEL/GND/TWR/APP/<strong>*CTR</strong></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                Center Controller (ACC) or higher</p>
                                        </td>
                                        <td>
                                            <p>
                                                DEL/GND/TWR/APP/CTR</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <small>Table 1. Minimun ATC Ranks</small>
                        </div>

                        <br>
                        <p>
                            <b>Note:</b> Due to the structure of the FRA, some positions allow ADC-ranked controllers in
                            approach dependencies and APC-ranked controllers in Center dependencies. To opt for these
                            positions, depending on the rank, the user must perform a check in addition to the GCA in the
                            requested position and this can be requested only after one calendar month of having obtained
                            the GCA.
                        </p>
                        <ul class="list-disc ml-4 pl-4">
                            <li>Speak, write, and read both English and Spanish.</li>
                            <li>Know the local procedures and letters of 
                                agreement applicable in the division (available in the IVAO Colombia ATC forum).</li>
                        </ul>

                        <br>
                        <h2 class=" ">Process for obtaining the GCA</h2>
                        <p>
                            The following is a description of the steps to follow for the application process, training, 
                            checks and subsequent approval of the GCA in the Colombian division.
                        </p>

                        <br>

                        <h4 class=" ">Request:</h4>
                        <p>
                            To apply for Guest Controller Approval the user must send an email to <a
                                href="mailto:co-tc@ivao.aero" class="text-blue-800 font-semibold">co-tc@ivao.aero</a> and
                            <a href="mailto:co-tac@ivao.aero" class="text-blue-800 font-semibold">co-tac@ivao.aero</a>
                            with subject “GCA Request VID XXXXXX” and include in the message:
                        </p>

                        <ul class="list-disc ml-4 pl-4">

                            <li>Name and Last name</li>
                            <li>IVAO VID</li>
                            <li>Short explanation including why you are applying for the GCA in Colombia.</li>
                        </ul>

                        <br>

                        <h4 class=" ">Training:</h4>
                        <p>
                            Once the request has been sent, the training department will assign a trainer to clarify doubts
                            about local
                            regulations and procedures through a theoretical session. Subsequently, practical training will
                            be carried out in the unit where the control will be carried out.
                            <br><br>
                            Each of these sections will be carried out as follows:
                        </p>

                        <br>
                        <ul class="list-disc ml-4 pl-4">

                            <li>
                                <p>
                                    <b>Theoretical session: </b> It comprises a talk with the trainer completely focused on local regulations; 
                                    the trainer will introduce the main points of the Colombian regulations and will resolve any concerns that may arise during the reading process of the GCA applicant.  
                                    The session should not last more than 60 minutes.
                                    <br><br>
                                    <b> Note: </b>
                                    Since the applicant already has the rank in the network, 
                                    the training should under no circumstances be directed to ICAO fundamentals.

                                </p>
                            </li>
                            <li>
                                <b>Practical session: </b>
                                It will have a maximum duration of 90 minutes, 
                                including control time and time for corrections and/or suggestions from the trainer.
                            </li>

                        </ul>
                        <br>
                        <p>
                            It is important to highlight that the training sessions are only a 
                            tool proposed by the division, they are not mandatory during the process.
                            <br><br>
                            <b> Note: </b>
                            Training should only be omitted at the applicant's request; sessions will be scheduled directly with the trainer through official channels, or any other channel as long as agreed by both parties.

                        </p>


                        <h4 class=" ">Final check:</h4>
                        <p>
                        As in the training, the final check-up will be carried out in two parts, one theoretical and one practical, which will be performed as follows:
                        </p>
                        <br>
                        <ul class="list-disc ml-4 pl-4">

                            <li>
                                <p>
                                    <b>Theoretical session:</b> This will be composed of a theoretical evaluation via direct verbal communication, which will be mainly focused on local regulations, however, if the trainer considers it convenient, he/she may ask questions about control procedures in general based on the applicant's rank. It should not last more than 45 minutes, it should be done before the practical part, but it is not mandatory that both sessions are held on the same day.

                                </p>
                            </li>
                            <li>
                                <b>Practical session:</b>
                                <p>
                                This session will take place at Torre El Dorado (SKBO_TWR) in case the GCA applicant aspires to control in the different towers of the country. It will take place in Bogotá Approach (SKBO_APP) if the applicant is APC rank or higher, seeks to control in the approach facilities of the country or has his GCA for a period not less than 30 calendar days. Finally, it will take place in Bogotá Control (SKED_CTR) if the applicant has ACC rank or higher. The practical part shall not last more than 120 minutes, including the control session and the final comments by the evaluator.
                                    <br> <br>
                                    <b> Note:</b>
                                    APC-ranked members holding the GCA cannot apply for the area facility control check.
                                </p>

                            </li>

                        </ul>
                        <p>
                        It is important to clarify that it is not an exam of the applicant's rank, it is a check of control techniques and knowledge of local regulations, so there is no score, the result is the evaluator's assessment.
                        </p>
                        <h4 class=" "> Result</h4>
                        <p>
                        At the end of the check-up, the evaluator will inform the applicant whether the result was satisfactory or not. If the result was satisfactory, the examiner will notify the training department coordinators of the result so that the GCA can become effective within a maximum of 15 calendar days from the day after the check-up.
                            <br><br>
                            If the applicant does not pass the check-up, he/she must wait 15 calendar days from the day after the check-up to request a new evaluation. In this case, the training part is omitted, and it will be up to the trainer to decide whether or not to provide it after a unsatisfactory check-up.
                            <br><br>
                            <b>Note:</b> The applicant must wait for the GCA confirmation mail from the training department coordination in order to be able to connect in the country's dependencies, if he/she fails to comply with this regulation, the member will not obtain his/her GCA and must wait 90 calendar days to resubmit his/her application.    
                        </p>
                        
                        <h4 class=" ">Rules for maintaining the GCA</h4>

                        <ul class="list-disc ml-4 pl-4">
                                <li>
                                    <p>
                                        The GCA user must complete a minimum of 6 hours of monitoring within 30 calendar days, for a total of 18 hours per quarter. In case of not complying with the minimum number of quarterly hours, the GCA will be removed, and the user must wait at least 90 calendar days to make a new application.  
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        If the user fails to comply with any of IVAO's R&R the GCA will be removed and the time to submit a new application shall not be less than twice the penalty established by the network.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        If the user does not present adequate knowledge of the local procedures and control standards the GCA will be removed, the waiting time for a new application will be determined by the coordination of the training department of the Colombia division and its HQ.
                                    </p>    
                                </li>
                            </ul>
                        <h4 class=" ">Final considerations</h4>
                        <p>
                        The GCA is a tool provided by IVAO to increase member participation and encourage community involvement, however, it is at the discretion of the training department and divisional HQ to freely remove the rating.
                            <br><br>
                            For former members of the division, the training department, together with the divisional HQ, may make the decision to grant the GCA without the need for a check, as long as the rank with which he/she aspires to control has been obtained in the Colombian division.  
                            <br><br>
                            Furthermore, the training department and the divisional HQ have the power to add and remove ratings to other facilities based on their evaluation criteria.  
                            <br><br>
                            For APC-ranked members, holders, or applicants to the GCA who are interested in controlling superior control facilities must have obtained the rank in the Colombia division.
                            <br><br>
                            It is not necessary for the applicant to perform the initial check in the highest unit that his/her rank allows, for example, a member with ACC rank can apply for the GCA by applying for a tower check, however, he/she must complete the entire process for the other dependencies.
                        </p>
                    </div>
                </div>



            </div>
        </div>
    </section>
@endsection
