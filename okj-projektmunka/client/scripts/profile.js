$('document').ready(() => {
    $('body').empty();
    let name_error_state = true;
    let email_error_state = true;
    let pwd_error_state = true;
    let userData = {
    };
    ajaxCall(toArray("getElement", "primaryNavbar", "logged_in"), (response) => {
        $("body").prepend(response['element']);
        $('<div />', { class: 'container mt-3' }).appendTo("body");
        ajaxCall(toArray("getElement", "getProfileData", "logged_in"), (response) => {
            $('.container').append(response['element']);
            userData.name = response['userData'][0];
            userData.email = response['userData'][1];
        }, (response) => {
            console.log(response['responseText']);
        });

    });
    $('body').on('click', '[id^=edit-]', (e) => {
        let id;
        if (e.target.tagName == 'BUTTON') {
            id = $(e.target).attr('id').slice(5);
            $(e.target).prop('disabled', true);
        } else if (e.target.tagName == 'I') {
            id = $(e.target).parent().attr('id').slice(5);
            $(e.target).parent().prop('disabled', true);
        }
        switch (id) {
            case "name":
                $('#name').fadeOut('slow', () => {
                    $('#name').remove();
                    $('<input />', { type: "text", class: "form-control custom-input mt-4", id: 'username', placeholder: "Felhasználónév" }).appendTo("#name-container");
                    $('<small />', { class: "custom-invalid-feedback" }).appendTo("#name-container");
                });
                break;
            case "email":
                $('#email').fadeOut('slow', () => {
                    $('#email').remove();
                    $('<input />', { type: "email", class: "form-control custom-input mt-4", id: 'email', placeholder: "Email cím" }).appendTo("#email-container");
                    $('<small />', { class: "custom-invalid-feedback" }).appendTo("#email-container");
                });
                break;
            case "pwd":
                $('#edit-pwd').fadeOut('slow', () => {
                    $('#pwd').remove();
                    $('<input />', { type: "password", class: "form-control custom-input", id: 'pwd', placeholder: "Jelszó" }).appendTo("#pwd-container");
                    $('<small />', { class: "custom-invalid-feedback" }).appendTo("#pwd-container");
                });
                break;
        }
    })

    //Felhasználónév ellenőrzése
    $("body").on("blur", "#username", function () {
        console.table(userData);
        let name = testInput($("#username").val());
        if (name != "") {
            if (name != userData.name) {
                if (checkChars(name, "name")) {
                    ajaxCall(toArray("register", "name", name, "", ""), function (response) {
                        if (response['state'] === "error") {
                            name_error_state = true;
                            addInvalid("#username");
                            $("#username").parent().find("small").html(response['msg']);
                        }
                        else if (response['state'] === "success") {
                            name_error_state = false;
                            addValid("#username");
                            $("#username").parent().find("small").html("");
                        }
                    });
                }
                else {
                    name_error_state = true;
                    addInvalid("#username");
                    $("#username").parent().find("small").html("Hibás formátum!");
                }
            } else {
                addInvalid("#username");

                $("#username").parent().find("small").html("A megadott felhasználónév megegyezik az előzővel!");
            }

        } else {
            name_error_state = true;
            addInvalid("#username");
            $("#username").parent().find("small").html("Kérem töltse ki a beviteli mezőt!");
        }
    });
    //Felhasználónév mentése
    $('body').on("click", '#save-name', (e) => {
        e.preventDefault();
        let name = $('#username').val();
        if (!name_error_state) {
            ajaxCall(toArray('updateUserName', name), (response) => {
                console.log(response)
                if (response['state'] === 'success') {
                    $('.container').empty();
                    ajaxCall(toArray("getElement", "getProfileData", "logged_in"), (response) => {
                        console.log(response)
                        $('.container').append(response['element']).hide().fadeIn('slow', () => { });
                        userData.name = response['userData'][0];
                        userData.email = response['userData'][1];
                    }, (response) => {
                        console.log(response['responseText']);
                    });
                }
            });
        }
    })

    //EMAIl CÍM ELLENŐRZÉSE
    $("body").on("blur", "#email", function () {
        if ($(this).val() != "") {
            if (checkChars($(this).val(), "email")) {
                ajaxCall(toArray("register", "email", "", $(this).val(), ""), function (response) {
                    if (response['state'] === "error") {
                        addInvalid("#email");
                        $("#email").parent().find("small").html(response['msg']);
                        email_error_state = true;
                    }
                    else if (response['state'] === "success") {
                        addValid("#email");
                        $("#email").parent().find("small").html("");
                        email_error_state = false;
                    }
                });
            }
            else {
                addInvalid("#email");
                $("#email").parent().find("small").html("Hibás formátum!");
                email_error_state = true;
            }
        } else {
            addInvalid($(this));
            $(this).parent().find("small").html("Kérem töltse ki a beviteli mezőt");
            email_error_state = true;
        }
    });
    //Email cím ellenőrzése
    //Felhasználónév mentése
    $('body').on("click", '#save-email', (e) => {
        e.preventDefault();
        let email = $('#email').val();
        if (!email_error_state) {
            ajaxCall(toArray('updateEmail', email), (response) => {
                if (response['state'] === 'success') {
                    $('.container').empty();
                    ajaxCall(toArray("getElement", "getProfileData", "logged_in"), (response) => {
                        $('.container').append(response['element']).hide().fadeIn('slow', () => { });
                        userData.name = response['userData'][0];
                        userData.email = response['userData'][1];
                    }, (response) => {
                        console.log(response['responseText']);
                    });
                }
            });
        }
    })

    //Jelszó ellenőrzése
    $("body").on("blur", "#pwd", (e) => {
        password = $(this).attr('id');
        passwordVerifyer(e.target);
    });

    //Jelszó mentése
    //Felhasználónév mentése
    $('body').on("click", '#save-pwd', (e) => {
        e.preventDefault();
        let pwd = $('#pwd').val();
        console.log(pwd)
        if (!pwd_error_state) {
            ajaxCall(toArray('updatePassword', pwd), (response) => {
                if (response['state'] === 'success') {
                    $('.container').empty();
                    ajaxCall(toArray("getElement", "getProfileData", "logged_in"), (response) => {
                        $('.container').append(response['element']).hide().fadeIn('slow', () => { });
                    }, (response) => {
                        console.log(response['responseText']);
                    });
                }
            });
        }
    })


    const passwordVerifyer = (target) => {
        if (target.value != undefined) {
            if (checkChars(target.value, "pwd")) {
                addValid($(target));
                $(target).parent().find("small").html("");
                pwd_error_state = false;
            } else {
                addInvalid($(target));
                $(target).parent().find("small").html("A jelszónak legalább 8 karakter hosszúnak kell lennie és tartalmaznia kell egy kis, egy nagy betűt, valamint egy számot és egy speciális karaktert!");
                pwd_error_state = true;
            }
        } else {
            addInvalid(target);
            $(target).parent().find("small").html("Kérem töltse ki a beviteli mezőt");
            pwd_error_state = true;
        }
    }
    const Matches = (stringA, stringB) => {
        if (stringA === stringB) {
            return true;
        } else {
            return false;
        }
    }
    // let passwords = {
    // }
    // //JELSZÓ ELLENŐRZÉSE


    // $("body").on("blur", "#password-verify", (e) => {
    //     localStorage.setItem('passwordB', $(this).attr('id'));
    //     passwordVerifyer(e.target);

    // });
    // $('body').on('click', "#update-profile", (e) => {
    //     let passwordA = $('#password').val();
    //     let passwordB = $('#password-verify').val();
    //     let email = $('#email').val()
    //     let name = $('#username').val();
    //     if (passwordA != passwordB) {
    //         addInvalid('#password');
    //         addInvalid('#password-verify');
    //         $("#password-verify").parent().find("div").find("small").html("Nem egyezik a két jelszó!");
    //         pwd_error_state = true;
    //     } else if (passwordA == passwordB) {
    //         addValid($('#password'));
    //         addValid($('#password-verify'));
    //         $("password").parent().find("div").find("small").html("");
    //         $("password-verify").parent().find("div").find("small").html("");
    //         pwd_error_state = false;
    //         if (!name_error_state && !email_error_state && !pwd_error_state) {
    //             let updatedUserData = {
    //                 password: passwordA,
    //                 email: email,
    //                 name: name
    //             }
    //             ajaxCall(toArray("updateUserData", updatedUserData), (response) => {
    //                 console.log(response);
    //             });
    //         }
    //     }
    //     e.preventDefault();
    // });
});