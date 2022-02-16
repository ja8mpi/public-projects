<?php

function createSets($set)
{
    $set = '
    <div class="col-lg-4 col-md-6 col-sm-12 text-center card_col mt-2">
        <div class="card">
            <div class="card-header custom-card text-light">
                <h5 class="set_title card-title card-text">' . $set["title"] . '</h5>
            </div>
            <div class="card-body custom-card text-light">Téma: ' . $set["topic"] . '
            </div>
            <div class="card-footer custom-card text-muted">
                <div>
                <form class="form-inline custom-form">
                <div class="formGroup">
                    <button type="button" class="btn custom-button custom-button-primary" id="open-set-btn-' . $set['id'] . '">
                        <i class="fas fa-folder-open"></i>
                    </button>
                </div>
                <div class="formGroup">
                    <button type="button" class="btn custom-button custom-button-light" id="open-modify-btn-' . $set['id'] . '">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                <div class="formGroup">
                    <button type="button" class="btn custom-button custom-button-danger" id="delete-set-btn-' . $set['id'] . '">
                        <i class="fas fa-trash"></i>
                </button>
            </div>
        </form>
                </div>
            </div>
        </div>
    </div>';
    return $set;
}
function createCards($card)
{
    $card = '<div class="col-lg-4 col-md-6 col-sm-12 text-center card_col mt-2">
        <div class="card custom-card">
            <div class="card-header custom-card text-light">
            <form class="form-inline custom-form ml-auto">
                    <div class="formGroup mr-0">
                        <button type="button" class="btn custom-button custom-button-danger" id="delete-card-btn-' . $card['id'] . '"><i class="fas fa-trash"></i></button>
                </div>
            </form>
            </div>
            <div class="card-body custom-card text-light">
            <form>
            <div class="form-group">
                <label>Állítás</label>
                <input type="text" class="form-control custom-input" id="statement-' . $card['id'] . '" value="' . $card['statement'] . '">
            </div>
            <div class="form-group">
                    <label>Definíció</label>
                    <input type="text" class="form-control custom-input" id="definition-' . $card['id'] . '" value="' . $card['definition'] . '">
            </div>
            </form>
            </div>
            <div class="card-footer custom-card text-muted">
            </div>
            </div>
        </div>
    </div>';
    return $card;
}
function createRawCard()
{
    $card = '<div class="col-lg-4 col-md-6 col-sm-12 text-center card_col mt-2">
        <div class="card custom-card">
            <div class="card-header custom-card text-light">
            <form class="form-inline custom-form ml-auto">
                    <div class="formGroup mr-0">
                        <button type="button" class="btn custom-button custom-button-danger" id="raw-delete-card-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                </div>
            </form>
            </div>
            <div class="card-body custom-card text-light">
            <form>
            <div class="form-group">
                <label>Állítás</label>
                <input type="text" class="form-control custom-input raw-statement" value="">
            </div>
            <div class="form-group">
                    <label>Definíció</label>
                    <input type="text" class="form-control custom-input raw-definition" value="">
            </div>
            </form>
            </div>
            <div class="card-footer custom-card text-muted">
            </div>
            </div>
        </div>
    </div>';
    return $card;
}
