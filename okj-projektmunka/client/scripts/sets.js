$("document").ready(() => {
    class Sets {
        constructor(sets) {
            this.sets = sets;
            this.idCounter = 0;
        }
        unsetSets() {
            this.sets = [];
        }
        getSets() {
            return this.sets;
        }
        getSetById(setId) {
            let returnset;
            for (let i = 0; i < this.sets.length; i++) {
                if (this.sets[i]['id'] === setId) {
                    returnset = this.sets[i];
                }
            }
            return returnset;
        }
    }
    let sets;
    let set_error_state = true;
    $('body').empty();
    function getSets() {
        $('#sets_container').empty();
        ajaxCall(toArray("getElement", "getSets", ""), (response) => {
            if (response['state'] === "success") {
                $('#sets_container').empty();
                $("#sets_container").append(response['elements']).hide().fadeIn('slow', () => { });

                // response['elements'].forEach(element => {
                //     $("#sets_container").append(element);
                // });
            } else if (response['state'] === 'error') {
                ajaxCall(toArray("getElement", "msgModal", "Jelenleg nincsenek leckéi!"), (response) => {
                    $("body").prepend(response['element']);
                    $('body').find('#msgModal').modal('show');
                    $('body').on('click', '.delete-modal', function (e) {
                        e.preventDefault();
                        $('#msgModal').fadeOut("slow", function () { $(this).remove(); });
                        $('body').removeClass();
                    });
                });
            }
        });
    }

    ajaxCall(toArray("getElement", "primaryNavbar", "logged_in"), (response) => {
        $('<div />', { class: "sticky-top", id: 'nav_container' }).appendTo("body");
        $("#nav_container").append(response['element']);
        ajaxCall(toArray("getElement", "secondaryNavbar", "logged_in"), (response) => {
            $("#nav_container").append(response['element']);
            ajaxCall(toArray("getElement", "createSetModal", ""), (response) => {
                $("body").append(response['element']);
                $('<div />', { class: 'container mt-3' }).appendTo("body");
                $('<div />', { class: "row", id: 'sets_container' }).appendTo(".container");
                getSets();
                ajaxCall(toArray('getSets'), (response) => {
                    if (response['state'] === "success") {
                        sets = new Sets(response['sets']);
                    } else if (response['state'] === 'error') {

                    }
                });
            });
        });
    });
    // new Sets(JSON.parse(localStorage.getItem("sets")))

    $("body").on("blur", ".set-input-field", function () {
        let title = testInput($(".set-input-field").val());
        if (title != "") {
            ajaxCall(toArray("check_test", "title", title), function (response) {
                if (response['state'] === "success") {
                    set_error_state = false;
                    addValid(".set-input-field");
                    $(".set-input-field").parent().find("div").find("small").html("");
                }
                else if (response['state'] === 'error') {
                    if ($('.set-input-field').attr('id') === 'create_set_title') {
                        set_error_state = true;
                        addInvalid(".set-input-field");
                        $("#create_set_title").parent().find("div").find("small").html(response['msg']);
                    } else if ($('.set-input-field').attr('id') === 'modify-set-title') {
                        set_error_state = false;
                    }
                }
            }, function (response) {

            });

        } else {
            set_error_state = true;
            addInvalid(".set-input-field");
            $(".set-input-field").parent().find("div").find("small").html("Kérem töltse ki a beviteli mezőt!");
        }
    });

    //LECKE létrehozása
    $("body").on("click", "#create_set", function (e) {
        e.preventDefault();
        let topic = $('#create_set_topics option:selected').val();
        let title = $('#create_set_title').val();
        if (!set_error_state) {
            ajaxCall(toArray("create_set", title, topic), (response) => {
                sets = new Set
                console.log(response);
                if (response['state'] === 'success') {
                    getSets();
                    $('#create_set_title').val('');
                    $('#new_set').modal('toggle');
                } else if (response['state'] === 'error') {
                    ajaxCall(toArray("getElement", "msgModal", "Jelenleg nincsenek leckéi!"), (response) => {
                        $("body").prepend(response['element']);
                        $('body').find('#msgModal').modal('show');
                        $('body').on('click', '.delete-modal', function (e) {
                            e.preventDefault();
                            $('#msgModal').fadeOut("slow", function () { $(this).remove(); });
                            $('body').removeClass();
                        });
                    });
                }
            });
        }
    });

    //Lecke megnyitása
    $("body").on("click", "[id^='open-set-btn-']", function () {
        var setId = $(this).attr("id").slice(13);

        ajaxCall(toArray("location", "cards", setId), function (response) {
            if (response['state'] === "success") {
                $("body").empty();
                location.reload();
            } else if (response['state'] === 'error') {
                $("body").empty();
                location.reload();

            }
            $('.container').empty();
            $('<div />', { class: "row", id: 'sets_container' }).appendTo(".container");
            ajaxCall(toArray("getElement", "getSets", ""), (response) => {
                response['elements'].forEach(element => {
                    $("#sets_container").append(element);
                });
            });
        });
    });
    //Lecke módosítása
    $('body').on('click', "[id^='open-modify-btn-']", function (e) {
        let setId = $(this).attr('id').slice(16);
        let selectedSet = sets.getSetById(setId);
        localStorage.setItem("selectedSet", JSON.stringify(selectedSet));
        e.preventDefault();
        ajaxCall(toArray("getElement", "modifySet", selectedSet), (response) => {
            $("body").prepend(response['element']);
            $('body').find('#modify_set').modal('show');
        });
    });
    $('body').on('click', "#modify-set", function (e) {
        e.preventDefault();
        let set = JSON.parse(localStorage.getItem('selectedSet'));
        if ($('#modify_set_title').val() != undefined && $('#modify_set_title').val() != "") {
            set['title'] = $('#modify_set_title').val();
        }
        if ($('#modify_set_topics option:selected').val() != undefined && $('#modify_set_topics option:selected').val() != "") {
            set['topicId'] = $('#modify_set_topics option:selected').val();
        }
        ajaxCall(toArray('update_set', set), (response) => {
            sets = new Sets(response['sets']);
            $('body').find('#modify_set').modal('hide');
            getSets();
        },
            (response) => {
            });

    })
    //Keresés a leckék között

    $('body').on('click', '#search-btn', function (e) {
        e.preventDefault();
        let text = $('#search_text').val();
        let topic = $('#search_topics option:selected').val();
        ajaxCall(toArray('search_sets', topic, text), (response) => {
            if (response['state'] == 'success') {
                getSets();
            }
        }
            , (response) => {
                getSets();
            });
    })

    //Lecke törlése
    $("body").on("click", "[id^='delete-set-btn-']", function () {
        let setId = $(this).attr('id').slice(15);
        let selectedId = $(this).attr('id');
        console.log(sets)
        let selectedSet = sets.getSetById(setId);
        localStorage.setItem("selectedSet", JSON.stringify(selectedSet));
        localStorage.setItem('selectedId', selectedId);
        ajaxCall(toArray("getElement", "confirmModal", "Törlés", "Biztos törli a leckét?"), (response) => {
            $("body").prepend(response['element']);
            $('body').find('#confirmModal').modal('show');
            $('body').on('click', '.delete-modal', function (e) {
                e.preventDefault();
                $('#confirmModal').fadeOut("slow", function () { $(this).remove(); });
                $('body').removeClass();
            });
        });
    });
    $('body').on('click', '#confirm', function (e) {
        e.preventDefault();
        let set = JSON.parse(localStorage.getItem('selectedSet'));
        let selected = localStorage.getItem('selectedId').slice(15);
        ajaxCall(toArray('deleteSet', selected), (response) => {

            if (response['state'] === 'success') {
                $(`#${localStorage.getItem('selectedId')}`).parent().parent().parent().parent().parent().parent().fadeOut("slow", function () { $(this).remove(); });
            } else if (response['state'] === 'error') {

            }
            // }, (response) => {
            //     ajaxCall(toArray("getElement", "msgModal", 'Sikertelen törlés!\nHa a lecke tartalmaz kártyákat, előbb azokat kell törölnie!'), (response) => {
            //         $("body").prepend(response['element']);
            //     });
        });
    })
});