<?php
function createProfile($user)
{
    $returndata = '
    <div class="row">
    <div class="col-8 ml-auto mr-auto" id="user-data">
    <div class="row">
    <div class="col-12 text-center">
        <img src="images/profile_default.png" alt="Profilkép" class="mr-auto mt-4 img-fluid img-thumbnail">
        <div class="form-group mt-2 mr-auto mr-auto">
            <button type="file" class="btn custom-button custom-button-secondary" id="add-profile-picture">
                Profilkép feltöltése
            </button>
        </div>
    </div>
    </div>
    <div class="row">
    <div class="col text-center">
    <form class="mt-4 mr-2 mb-4">
    <div class="form-group">
        <div class="row">
            <div class="col-8 text-white" id="name-container">
                <h5 class="mt-4 profile-data" id="name">Felhasználónév: <br>' . $user[1] . '</h5>   
                </div>
            <div class="col-4 mt-4">
                <div class="form-inline custom-form">
                    <div class="form-group">
                        <button type="button" id="edit-name" class="btn custom-button custom-button-secondary from-control"><i class="fas fa-edit"></i></button>
                    </div>
                      <div class="form-group ml-2">
                      <button type="button" id="save-name" class="btn custom-button custom-button-primary from-control"><i class="fas fa-save"></i></button>
                    </div>    
                </div>    
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-8 text-white" id="email-container">
            <h5 class="mt-4 profile-data" id="email">Email cím: <br>' . $user[2] . '</h5>   
            </div>
            <div class="col-4 mt-4 custom-form">
                <div class="form-inline">
                    <div class="form-group">
                        <button type="button" id="edit-email" class="btn custom-button custom-button-secondary from-control"><i class="fas fa-edit"></i></button>
                    </div>
                      <div class="form-group ml-2">
                      <button type="button" id="save-email" class="btn custom-button custom-button-primary from-control"><i class="fas fa-save"></i></button>
                    </div>    
                </div>    
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-8 mt-4">
            <div class="form-group" id="pwd-container">
                        <button type="button" class="btn custom-button custom-button custom-button-light from-control" id="edit-pwd">Jelszó módostása</button>
                    </div>
                </div>
            <div class="col-4 mt-4">
                <div class="form-inline">
                      <div class="form-group ml-2">
                      <button type="button" class="btn custom-button custom-button-primary from-control" id="save-pwd"><i class="fas fa-save"></i></button>
                    </div>    
                </div>    
            </div>
        </div>
    </div>
</form>
    </div>
    <div
</div>
</div>';
    return $returndata;
}
// <label for="#username" class="text-white">Felhasználónév</label>
//                 <input type="text" class="form-control custom-input " id="username" placeholder="' . $user[1] . '" required>
//                 <div class="custom-invalid-feedback"><small></small></div>
