
function addInvalid(input) {
    if ($(input).hasClass("custom-valid")) {
        $(input).removeClass("custom-valid");
    }
    $(input).addClass("custom-invalid");
}
function addValid(input) {
    if ($(input).hasClass("custom-invalid")) {
        $(input).removeClass("custom-invalid");
    }
    $(input).addClass("custom-valid");
}

//Ajax call jQuery
function ajaxCall(params, successCallback, errorCallback) {
    let request = JSON.stringify(params);
    $.ajax({
        type: "POST",
        url: '../server/Control/process.php',
        data: { request: request },
        dataType: "json",
        success: successCallback,
        error: errorCallback
    });
}

function toArray(type, content1, content2, content3, content4) {
    var inputs = [];
    inputs[0] = type;
    inputs[1] = content1;
    if (typeof content2 !== "undefined") { inputs[2] = content2 }
    if (typeof content3 !== "undefined") { inputs[3] = content3 }
    if (typeof content4 !== "undefined") { inputs[4] = content4 }
    return inputs;
}

function testInput(input) {
    return input
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;")
}

function checkChars(input, type) {
    const nameexp = /^[a-zA-Z0-9áéíóöőúüűÁÉÍÓÖŐÜŰ]+$/;
    const emailexp = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    const pwdexp = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/;
    switch (type) {
        case "name":
            return input.match(nameexp);
        case "email":
            return input.match(emailexp);
        case "pwd":
            return input.match(pwdexp);
    }
}
$('document').ready(function () {

    $("body").on("click", "#logout", (e) => {
        e.preventDefault();
        ajaxCall(toArray("logout", "logout"), (response) => {
            if (response['state'] === "success") {
                location.reload();
            }
        });
    });
    //Oldal változtatás
    $("body").on("click", ".custom-nav-link", (e) => {
        e.preventDefault();
        let selectedLocation = $(e.target).attr('id');
        if (selectedLocation.includes('back-to-')) {
            selectedLocation = selectedLocation.slice(8);
        }
        if (selectedLocation === 'logout') {
            localStorage.clear()
        }
        if (localStorage.getItem("unsaved") === null) {
            ajaxCall(toArray("location", selectedLocation), (response) => {
                if (response['state'] === "success") {
                    $("body").empty();
                    $('#pageJs').remove();
                    location.reload();
                    // $.getScript(response['file']);
                }
            })
        } else if (localStorage.getItem("unsaved")) {
            ajaxCall(toArray("getElement", "confirmModal", "Elhagyás", "Nem mentette az adatait!\nBiztos elhagyja az oldalt?"), (response) => {
                $("body").prepend(response['element']);
                $('body').find('#confirmModal').modal('show');
                $('body').on('click', '#confirm', function (e) {
                    localStorage.removeItem('unsaved');
                    ajaxCall(toArray("location", targetid), (response) => {
                        if (response['state'] === "success") {
                            $("body").empty();
                            $('#pageJs').remove();
                            location.reload();
                            // $.getScript(response['file']);
                        }
                    });
                });
            });
        }


    });
    // $("body").on("click", "button", (e) => {
    //     e.preventDefault();
    //     console.log($(this).attr('id'))
    // });
});
