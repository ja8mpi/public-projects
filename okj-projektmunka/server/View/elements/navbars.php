<?php
function createPrimaryNavbar($state)
{
    $navbar = '<nav id="primary_navbar" class="navbar navbar-expand-lg navbar-dark bg-primary custom-navbar custom-navbar-dark custom-bg-primary">
        <a class="navbar-brand custom-navbar-brand" href="#">Tanulgató</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#primarynav" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
            </span>
        </button>
        <div class="collapse navbar-collapse text-right" id="primarynav">';
    if ($state == "logged_in") {
        $navbar .= '
        <form class="form-inline mt-2  ml-auto">
            <div class="form-group  mr-2">
                <button class="btn custom-nav-link custom-button custom-button-primary" type="button" id="homepage">Főoldal</button>
            </div>
            <div class="form-group mr-2">
                <button class="btn custom-nav-link custom-button custom-button-primary"  type="button" id="profile">Profil</button>
            </div>
            <div class="form-group mr-2">
                <button class="btn custom-nav-link custom-button custom-button-primary"  type="button" id="sets">Leckéim</button>
            </div>
            <div class="form-group mr-2">
                <button class="btn custom-nav-link custom-button custom-button-danger"  type="button" id="logout">Kilépés</button>
            </div>
    </div></form>';
    } else {
        $navbar .= '<form action="" class="form-inline my-2 my-lg-0 ml-auto mb-4">
                <div class="form-group  mr-3 mb-2">
                    <input type="text" class="form-control custom-input" id="loginname" placeholder="Felhasználónév">
                </div>
                <div class="form-group  mr-3 mb-2">
                    <input type="password" class="form-control custom-input" id="loginpwd" placeholder="Jelszó">
                </div>
                <div class="form-group mb-2">
                    <button class="btn custom-button custom-button-primary mr-3" id="loginbutton" type="button">Belépés</button>
                    <button class="btn custom-button custom-button-secondary" id="regopen" data-toggle="modal" type="button" data-target="#registrationmodal">Regisztráció</button>
                </div>
            </form></div>';
    }

    $navbar .= '</nav>';
    return $navbar;
}
function createSecondaryNavbar()
{
    $nav = '<nav class="navbar navbar-dark navbar-expand-lg custom-navbar custom-navbar bg-primary custom-navbar-light custom-bg-secondary">
    <a class="navbar-brand text-light">Leckék</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#searcher" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="searcher">
        <form class="form-inline my-2 my-lg-0 ml-auto" method="POST">
            <div class="form-group">
                <select id="search_topics" class="form-control custom-select-primary mr-2" id="topics">
                <option value="">Téma</option selected>';
    foreach ($_SESSION['topics']->getTopics() as $topic) {
        $nav .= '<option value="' . $topic['id'] . '">' . $topic['topic'] . '</option>';
    }
    $nav .= '           
    <option value="">Egyéb</option selected>
    </select>
            </div>
            <div class="form-group mr-2">
                <input class="form-control" id="search_text" type="search" placeholder="Keresés" aria-label="Search">
            </div>
            <div class="form-group mr-2">
                <button class="btn custom-button-light custom-button" id="search-btn" type="button">Keresés</button>
            </div>
            <div class="form-group mr-2">
                <button class="btn custom-button-primary custom-button" type="button" data-toggle="modal" data-target="#new_set">Új lecke</button>
            </div>
        </form>
    </div>
</nav>';
    return $nav;
}
function createCardsNav($set)
{
    $nav = ' <nav class="navbar navbar-dark navbar-expand-lg custom-navbar custom-navbar bg-primary custom-navbar-light custom-bg-secondary">
            <button class="btn custom-nav-link custom-button custom-button-dark" type="button" id="back-to-sets">Vissza</button>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#searcher" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
                <div class="collapse navbar-collapse" id="searcher">
                    <form class="form-inline my-2 my-lg-0 ml-auto">
                        <div class="form-group">
                            <button class="btn  mr-2 custom-button-primary custom-button custom-icon" id="save-set-' . $set['id'] . '" type="button"><i class="fas fa-lg fa-save"></i></button>
                        </div>
                        <div class="form-group">
                            <button class="btn  mr-2 custom-button-dark custom-button custom-icon" id="practice-set-' . $set['id'] . '" type="button"><i class="fas fa-lg fa-gamepad"></i></button>
                        </div>
                        <div class="form-group">
                            <button class="btn  mr-2 custom-button-light custom-dark create-card custom-icon" id="add-card" type="button"><i class="fas fa-lg fa-plus"></i></button>
                        </div>
                        <div class="form-group">
                            <button class="btn  custom-button-danger custom-button custom-icon" type="button" id="delete-set-' . $set['id'] . '" data-toggle="modal"><i class="fas fa-lg fa-trash"></i></button>
                        </div>
                    </form>
                </div>
            </nav>
';
    return $nav;
}
function createPracticeNavbar($set)
{
    $nav = ' <nav class="navbar navbar-dark navbar-expand-lg custom-navbar custom-navbar bg-primary custom-navbar-light custom-bg-secondary">
            <button class="btn custom-button custom-nav-link custom-button-dark" type="button" id="back-to-cards">Vissza</button>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#searcher" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
                <div class="collapse navbar-collapse" id="searcher">
                    <form class="form-inline my-2 my-lg-0 ml-auto">
                        <div class="form-group">
                            <button class="btn  mr-2 custom-button-dark custom-button custom-icon" id="restart" type="button"><i class="fas fa-lg fa-redo"></i></button>
                        </div>
                        <div class="form-group">
                            <button class="btn  mr-2 custom-button-primary custom-button custom-icon" id="save-cards" type="button"><i class="fas fa-lg fa-save"></i></button>
                        </div>
                    </form>
                </div>
            </nav>
';
    return $nav;
}
