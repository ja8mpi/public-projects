<?php function registerModal()
{
    return '
        <div class="modal fade" id="registrationmodal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content  custom-modal">
                    <div class="modal-header">
                        <h5 class="modal-title">Regisztráció</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="registerform">
                            <div class="form-group">
                                <input type="text" class="form-control custom-input" id="regname" placeholder="Felhasználónév:" required>
                                <div class="custom-invalid-feedback"><small></small></div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control custom-input" id="regemail" placeholder="Email cím" required>
                                <div class="custom-invalid-feedback"><small></small></div>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control custom-input" id="regpwd" placeholder="Jelszó" required>
                                <div class="custom-invalid-feedback"><small></small></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn custom-button-primary mr-auto" name="regbutton" id="regbutton" form="reg" value="Regisztráció">
                    </div>
                </div>
            </div>
        </div>';
}
function createSetModal($conn)
{
    $modal = '<div class="modal fade" id="new_set" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content custom-modal">
            <div class="modal-body">
                <form>
                    <label for="set_name">Lecke címe: </label>
                    <div class="form-group">
                        <input type="text" class="form-control custom-input set-input-field" id="create_set_title" placeholder="Lecke címe">
                        <div class="custom-invalid-feedback"><small></small></div>
                        </div>
                    <div class="form-group mr-2">
                        <label for="create_set_topics">Téma: </label>
                        <select class="form-control custom-select-secondary mb-xs-1" name="create_topics" id="create_set_topics">
                            <option value="">Téma</option>
                            ';
    foreach ($_SESSION['topics']->getTopics() as $topic) {
        $modal .= '<option value="' . $topic['id'] . '">' . $topic['topic'] . '</option>';
    }
    $modal .= '
    <option value="">Egyéb</option selected>
                </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn custom-button custom-button-primary" id="create_set">Létrehozás</button>
                <button type="button" class="btn custom-button custom-button-secondary" data-dismiss="modal">Mégse</button>
            </div>
        </div>
    </div>
</div>';
    return $modal;
}

//Üzenet ablak
function msgModal($msg)
{
    return '<div class="modal fade bd-example-modal-sm" id="msgModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
      <div class="modal-content custom-modal">
      <div class="modal-header">
      <h5 class="modal-title">' . $msg . '</h5>
        <button type="button" class="close delete-modal" id="closeMsgModal" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
      </div>
    </div>
  </div>';
}
function confirmModal($type, $msg)
{
    return '<div class="modal fade bd-example-modal-sm" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
      <div class="modal-content custom-modal">
      <div class="modal-header">
      <h5 class="modal-title">' . $msg . '</h5>
        <button type="button" class="close delete-modal" id="closeMsgModal" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div
        <div>
        <form class="form-inline custom-form mb-2">
        <div class="formGroup">
        <button type="button" class="btn custom-button custom-button-danger delete-modal" id="confirm"  data-dismiss="modal">
        ' . $type . '
        </button>
        </div>
        <div class="formGroup">
            <button type="button" class="btn custom-button custom-button-light delete-modal" id="cancel" data-dismiss="modal">
                Mégse
            </button>
        </form>
        </div>
      </div>
    </div>
  </div>';
}
function modifyModal($set)
{
    $modal = '<div class="modal fade" tabindex="-1" id="modify_set" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content custom-modal">
            <div class="modal-body">
                <form>
                    <label for="set_name">Lecke címe: </label>
                    <div class="form-group">
                        <input type="text" class="form-control custom-input set-input-field" id="modify_set_title" value="' . $set['title'] . '">
                        <div class="custom-invalid-feedback"><small></small></div>
                        </div>
                    <div class="form-group mr-2">
                        <label for="create_set_topics">Téma: </label>
                        <select class="form-control custom-select-secondary mb-xs-1" name="create_topics" id="modify_set_topics">
                            <option value="">Téma</option>
                            ';
    foreach ($_SESSION['topics']->getTopics() as $topic) {
        $modal .= '<option value="' . $topic['id'] . '">' . $topic['topic'] . '</option>';
    }
    $modal .= '
                </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn custom-button custom-button-primary" id="modify-set">Módosítás</button>
                <button type="button" class="btn custom-button custom-button-secondary" id="remove_modal" data-dismiss="modal">Mégse</button>
            </div>
        </div>
    </div>
</div>';
    return $modal;
}
