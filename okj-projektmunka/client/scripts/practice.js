$('document').ready(() => {

    window.onbeforeunload = confirmExit;
    function confirmExit() {
        alert("You have attempted to leave this page. Are you sure?");
    }
    class Cards {
        constructor(cards) {
            this.savedStatus = false;
            this.cards = cards;
            this.cards.forEach(card => {
                card.state = false;
            });
            this.currentId = 0;
            this.good = 0;
            this.wrong = 0;
        }
        getSavedStatus() {
            return this.savedStatus;
        }
        save() {
            this.savedStatus = true;
        }
        changeStateOfCard(cardId) {
            this.cards[cardId]['state'] = true;
        }
        getCurrentId() {
            return this.currentId;
        }
        writeOut() {
            return this.cards;
        }
        parseBoxes() {
            let boxes = 0;
            this.cards.forEach(card => {
                card['box'] = parseInt(card['box']);
            });
        }
        sortCards() {
            let tmp;
            for (let i = 0; i < this.cards.length; i++) {
                for (let j = 0; j < this.cards.length; j++) {
                    if (this.cards[i]['box'] < this.cards[j]['box']) {
                        tmp = this.cards[i];
                        this.cards[i] = this.cards[j];
                        this.cards[j] = tmp;
                    }
                }
            }
        }
        getKnownCards() {
            let returnarray = [];
            this.cards.forEach(card => {
                if (card['state']) {
                    returnarray.push(card);
                }
            });
            return returnarray;
        }
        getUnknownCards() {
            let returnarray = [];
            this.cards.forEach(card => {
                if (!card['state']) {
                    returnarray.push(card);
                }
            });
            return returnarray;
        }
        getGoodAnswer() {
            return this.good;
        }
        getWrongAnswer() {
            return this.wrong;
        }
        increaseGoodAnswer() {
            this.good++;
        }
        increaseWrongAnswer() {
            this.wrong++;
        }
        setGoodWrongDefault() {
            this.good = 0;
            this.wrong = 0;
        }
        increaseCurrentId() {
            this.currentId++;
        }
        decreaseCurrentId() {
            this.currentId++;
        }
        getBox(cardId) {
            return this.cards[cardId]['box'];
        }
        increaseBox(cardId) {
            this.cards[cardId]['box']++;
        }

        decreaseBox(cardId) {
            this.cards[cardId]['box']--;
        }
        getCards() {
            return this.cards;
        }
        getCardsForPractice() {
            return this.cards.slice(0, 5);
        }

    }
    function checkAnswer(e, cards) {
        e.preventDefault();
        // console.table(cards.getCards()[cards.getCurrentId()]);
        //Jó válasz
        if ($("#answer").val().toLowerCase() == cards.getCards()[cards.getCurrentId()]['definition'].toLowerCase()) {
            addValid($("#answer"));
            cards.changeStateOfCard(cards.getCurrentId());
            // console.table(cards.getCards())
            cards.increaseBox(cards.getCurrentId());
            // $('#answer').prop("disabled", true);
            cards.increaseGoodAnswer();
            $('#forward').prop("disabled", false);
            //Rossz válasz
        } else {
            addInvalid($("#answer"));
            if (cards.getBox(cards.getCurrentId()) > 0) {
                cards.decreaseBox(cards.getCurrentId());
            }
            cards.increaseWrongAnswer();
            $('#forward').prop("disabled", false);

        }
        if (cards.getCards().length == cards.getCurrentId() + 1) {
            $('#statistics').prop("disabled", false);
        }
    }
    function manageChangesAfterAnswer(cards) {
        $('body').on('click', '#forward', function () {
            if (!($('#forward').disabled)) {
                cards.increaseCurrentId();
                if (cards.getCurrentId() < cards.getCards().length) {
                    $('#answer').val('');
                    if ($('#answer').hasClass('custom-valid')) {
                        $('#answer').removeClass('custom-valid')
                    } else if ($('#answer').hasClass('custom-invalid')) {
                        $('#answer').removeClass('custom-invalid')
                    }
                    $('#counter').text(`${cards.getCards().length}/${cards.getCurrentId() + 1}`);
                    $('#forward').prop("disabled", true);
                    $('#question').text(cards.writeOut()[cards.getCurrentId()]['statement']);
                } else {
                    // ajaxCall(toArray("getElement", "practiceSet"), function (response) {
                    //     if (response['state'] === "success") {
                    //         $(".container").append(response['element']);
                    //     }
                    // });
                }
            }
        });
    }

    function Practice(response) {
        //Ha ki akar lépni valaki mentetlen leckékkel
        $(window).on('onbeforeunload ', function (e) {
            e.preventDefault();
            alert("Where ya goin???");
        })

        if (response['state'] === "success") {
            let cardsToPractice = new Cards(response['cards']);
            cardsToPractice.parseBoxes();
            cardsToPractice.sortCards();
            let selectedCards = [];
            // for (let index = 0; index < 3; index++) {
            //     selectedCards.push(cardsToPractice.getCards()[index]);
            // }
            // cards = new Cards(selectedCards);
            let cards;
            if (cardsToPractice.getCards().length > 10) {
                for (let index = 0; index < 10; index++) {
                    selectedCards.push(cardsToPractice.getCards()[index]);
                }
                cards = new Cards(selectedCards);
            } else {
                cards = new Cards(response['cards']);
            }
            $('#counter').text(`${cards.getCards().length}/${cards.getCurrentId() + 1}`);
            $('#forward').prop("disabled", true);
            $('#statistics').prop("disabled", true);
            $('#question').text(cards.writeOut()[cards.getCurrentId()]['statement']);
            $("body").on("blur", "#answer", (e) => {
                if ($(e.target).val() != '') {
                    $('#check_answer').prop("disabled", false);
                } else {
                    $('#check_answer').prop("disabled", true);
                }
            });
            $(document).keypress(function (e) {
                if (e.which == 13) {
                    checkAnswer(e, cards);
                }
            });
            $('body').on('click', '#check_answer', function (e) {
                checkAnswer(e, cards);
            });
            manageChangesAfterAnswer(cards);
            $('body').on('click', '#statistics', function () {
                console.table(cards.getCards());
                ajaxCall(toArray("getElement", "createStatistics", cards.getKnownCards(), cards.getUnknownCards()), (response) => {
                    $(".container").empty();
                    $(".container").append(response['statistics']);
                });
            });
            $('body').on("click", "#save-cards", function (e) {
                ajaxCall(toArray("save_cards", cards.getCards()), (response) => {
                    ajaxCall(toArray("getElement", "msgModal", response['msg']), (response) => {
                        $("body").prepend(response['element']);
                        $('body').find('#msgModal').modal('show');
                    });
                });
            });
        }

    }
    $('body').on("click", "#restart", function (e) {
        e.preventDefault();
        location.reload();
    });
    // //Oldal változtatás
    // $("body").on("click", ".custom-nav-link", (e) => {
    //     e.preventDefault();
    //     ajaxCall(toArray("location", e.target.id), (response) => {
    //         if (response['state'] === "success") {
    //             location.reload();
    //         }
    //     })
    // });
    $('body').empty();
    ajaxCall(toArray("getElement", "primaryNavbar", "logged_in"), (response) => {
        $('<div />', { class: "sticky-top", id: 'nav_container' }).appendTo("body");
        $('<div />', { class: "container" }).appendTo("body");
        $("#nav_container").append(response['element']);
        ajaxCall(toArray("getElement", "practiceNavbar", "logged_in"), (response) => {
            $("#nav_container").append(response['element']);
            ajaxCall(toArray("getElement", "practiceSet"), function (response) {
                if (response['state'] === "success") {
                    $(".container").append(response['element']);
                    ajaxCall(toArray("get_cards"), Practice);
                }
            });
        });
    });


    //Vissza gomb
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
            // $('.container').empty();
            // $('<div />', { class: "row", id: 'sets_container' }).appendTo(".container");
            // ajaxCall(toArray("getElement", "getSets", ""), (response) => {
            //     response['elements'].forEach(element => {
            //         $("#sets_container").append(element);
            //     });
            // });
        });
    });
})