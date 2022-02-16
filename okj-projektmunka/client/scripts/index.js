$('document').ready(() => {
    $('body').empty();
    let name_error_state = true;
    let email_error_state = true;
    let pwd_error_state = true;
    //Elsődleges navbar létrehozása
    ajaxCall(toArray("getElement", "primaryNavbar", ""), (response) => {
        $("body").prepend(response['element']);
    });
    //Regisztrációs form létrehozása
    ajaxCall(toArray("getElement", "registerModal"), (response) => {
        $("body").prepend(response['element']);
    });

    $("body").on("click", "#regopen", (e) => {
        e.preventDefault();
    });
    $("body").on("click", "input", function () {
        if ($(this).hasClass("custom-valid")) {
            $(this).removeClass("custom-valid");
            $(this).parent().find("div").find("small").html("");
        }
        if ($(this).hasClass("custom-invalid")) {
            $(this).removeClass("custom-invalid");
            $(this).parent().find("div").find("small").html("");
        }
    });

    //Felhasználónév ellenőrzése
    $("body").on("blur mouseleave", "#regname", function () {
        let name = testInput($("#regname").val());
        if (name != "") {
            if (checkChars(name, "name")) {
                ajaxCall(toArray("register", "name", name, "", ""), function (response) {
                    if (response['state'] === "error") {
                        name_error_state = true;
                        addInvalid("#regname");
                        $("#regname").parent().find("div").find("small").html(response['msg']);
                    }
                    else if (response['state'] === "success") {
                        name_error_state = false;
                        addValid("#regname");
                        $("#regname").parent().find("div").find("small").html("");
                    }
                });
            }
            else {
                name_error_state = true;
                addInvalid("#regname");
                $("#regname").parent().find("div").find("small").html("Hibás formátum!");
            }
        } else {
            name_error_state = true;
            addInvalid("#regname");
            $("#regname").parent().find("div").find("small").html("Kérem töltse ki a beviteli mezőt!");
        }
    });

    //EMAIl CÍM ELLENŐRZÉSE
    $("body").on("blur mouseleave", "#regemail", function () {
        if ($(this).val() != "") {
            if (checkChars($(this).val(), "email")) {
                ajaxCall(toArray("register", "email", "", $(this).val(), ""), function (response) {
                    if (response['state'] === "error") {
                        addInvalid("#regemail");
                        $("#regemail").parent().find("div").find("small").html(response['msg']);
                        email_error_state = true;
                    }
                    else if (response['state'] === "success") {
                        addValid("#regemail");
                        $("#regemail").parent().find("div").find("small").html("");
                        email_error_state = false;
                    }
                });
            }
            else {
                addInvalid("#regemail");
                $("#regemail").parent().find("div").find("small").html("Hibás formátum!");
                email_error_state = true;
            }
        } else {
            addInvalid($(this));
            $(this).parent().find("div").find("small").html("Kérem töltse ki a beviteli mezőt");
            email_error_state = true;
        }
    });
    //JELSZÓ ELLENŐRZÉSE
    $("body").on("blur mouseleave", "#regpwd", function () {
        let pwd = testInput($("#regpwd").val());
        if (pwd != "") {
            if (checkChars(pwd, "pwd")) {
                addValid($(this));
                $("#regpwd").parent().find("div").find("small").html("");
                pwd_error_state = false;
            } else {
                addInvalid($(this));
                $(this).parent().find("div").find("small").html("A jelszónak legalább 8 karakter hosszúnak kell lennie és tartalmaznia kell egy kis, egy nagy betűt, valamint egy számot és egy speciális karaktert!");
                pwd_error_state = true;
            }
        } else {
            addInvalid($(this));
            $(this).parent().find("div").find("small").html("Kérem töltse ki a beviteli mezőt");
            pwd_error_state = true;
        }
    });

    //REGISZTRÁCIÓ VÉGLEGESÍTÉSE
    $("body").on("click", "#regbutton", (e) => {
        e.preventDefault;
        if (!name_error_state && !email_error_state && !pwd_error_state) {
            ajaxCall(toArray("register", "final", $("#regname").val(), $("#regemail").val(), $("#regpwd").val()), (response) => {
                console.table(response);
                if (response['state'] === "success") {
                    $('#registrationmodal').modal('toggle');
                    ajaxCall(toArray("getElement", "msgModal", response['msg']), (response) => {
                        $("body").prepend(response['element']);
                        $('body').find('#msgModal').modal('show');
                    })
                    $("#regname").val() = "";
                    $("#regemail").val() = "";
                    $("#regpwd").val() = "";
                } else {
                    $('#registrationmodal').modal('toggle');
                    ajaxCall(toArray("getElement", "msgModal", response['msg']), (response) => {
                        $("body").prepend(response['element']);
                    });
                    $('#msgModal').modal('toggle');
                }
            });
        }
    });

    //Belépés
    $("body").on("click", "#loginbutton", (e) => {
        let name = $("#loginname").val();
        let pwd = $("#loginpwd").val();
        if (name != "" && pwd != "") {
            ajaxCall(toArray("login", name, pwd), (response) => {
                if (response['state'] === "error") {
                    addInvalid("#loginname");
                    addInvalid("#loginpwd");
                    ajaxCall(toArray("getElement", "msgModal", response['msg']), (response) => {
                        $("body").prepend(response['element']);
                        $('#msgModal').modal('show');
                        $("#closeMsgModal").on("click", (e) => {
                            e.preventDefault;
                            $("#msgModal").fadeOut(300, function () {
                                $(this).remove();
                            });
                            $(".modal-backdrop").remove();
                        });
                    });
                }
                else if (response['state'] === "success") {
                    location.reload();
                }
            });
        } else {
            addInvalid("#loginname");
            addInvalid("#loginpwd");
            ajaxCall(toArray("getElement", "msgModal", "Kérem töltse ki a beviteli mezőket!"), (response) => {
                $("body").prepend(response['element']);
                $('#msgModal').modal('show');
                $("#closeMsgModal").on("click", (e) => {
                    e.preventDefault;
                    $("#msgModal").fadeOut(300, function () {
                        $(this).remove();
                    });
                    $(".modal-backdrop").remove();
                });
            });
        }
    });
});