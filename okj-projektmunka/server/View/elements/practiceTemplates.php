<?php
function practiceMatch()
{
    $practice = '
    <div class="row mt-5">
        <div class="col-md-8 col-xs-12  mx-auto" id="practiceSet" ml-auto>
            <div>
                <h5 class="text-center text-white mt-3 mb-3">
                    Párosítsa az állítást a hozzá tartozó definícióval
                    <div id="counter"></div>
                </h5>
                <h4 class="text-center text-white" id="question">
                    
                </h4>
            </div>
            <form class="mb-2">
            <div class="form-group mr-2 ml-2">
                <input type="text" class="form-control custom-input" id="answer">
                <div class="custom-invalid-feedback"><small></small></div>
            </div>
            </form>
            <div class="form-inline justify-content-center mb-3">
                <div class="form-group">
                <button type="button" class="btn custom-button custom-button-primary ml-2" id="check_answer"><i class="fas fa-question fa-2x"></i></button>
                </div>
                <div class="form-group">
                <button type="button" class="btn custom-button custom-button-arrow ml-2" id="forward"><i class="fas fa-arrow-alt-circle-right fa-2x"></i></button>
                </div>
                <div class="form-group">
                <button type="button" class="btn custom-button custom-button-secondary ml-2" id="statistics"><i class="fas fa-chart-bar fa-2x"></i></i></button>
                </div>
            </div>
        </div>
    </div>';
    return $practice;
}
