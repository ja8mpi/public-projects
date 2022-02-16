$('document').ready(() => {
    localStorage.setItem("raw-card-num", 0);
    class Cards {
        constructor(cards) {
            this.cards = cards;
            this.idCounter = 0;
        }
        deleteCard(id) {

        }
        unsetCards() {
            this.cards = [];
        }
        getCards() {
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
        addCard(statement, definition) {

            this.cards.push(
                {
                    id: this.idCounter, statement: statement, definition: definition, box: 0
                }
            );
            this.idCounter++;
        }
        getCards() {
            return this.cards;
        }
        getIdCounter() {
            return this.idCounter;
        }
        updateAttr(attr, id, value) {
            this.cards.forEach(card => {
                if (card['id'] == id) {
                    switch (attr) {
                        case "statement":
                            card['statement'] = value;
                            break;
                        case "definition":
                            card['definition'] = value;
                            break;
                    }
                }
            });
        }
    }
    let cards = new Cards(new Array());
    let newCards = new Cards(new Array());
    let requestCards = () => {
        ajaxCall(toArray("getElement", "getCards", ""), (response) => {
            console.log(response)
            if (response['state'] === 'success') {

                ajaxCall(toArray("get_cards"), (response) => {
                    if (response['state'] === "success") {
                        cards = new Cards(response['cards']);
                        cards.parseBoxes();
                        // localStorage.setItem("cards", JSON.stringify(response['cards']));
                    }
                });
                $('#cards_container').empty();
                $('#cards_container').append(response['elements']).hide().fadeIn('slow', (e) => { });
            } else if (response['state'] === 'error') {
                ajaxCall(toArray("getElement", "msgModal", response['msg']), (response) => {
                    $("body").prepend(response['element']);
                    $('body').find('#msgModal').modal('show');
                });
            }
        });
    }
    $('body').empty();
    ajaxCall(toArray("getElement", "primaryNavbar", "logged_in"), (response) => {
        $('<div />', { class: "sticky-top", id: 'nav_container' }).appendTo("body");
        $("#nav_container").append(response['element']);
        ajaxCall(toArray("getElement", "cardsNavbar", "logged_in"), (response) => {
            $("#nav_container").append(response['element']);
            $('<div/>', { class: 'container mt-3' }).appendTo("body");
            $('<div/>', { class: "row", id: 'cards_container' }).appendTo(".container");
            requestCards();
        });
    });
    // Osztály példány létrehozása    

    // let cards = new Cards(JSON.parse(localStorage.getItem("cards")));
    // cards.parseBoxes();
    // console.table(cards.getCards());

    $("body").on("click", "#logout", (e) => {
        e.preventDefault();
        ajaxCall(toArray("logout", "logout"), (response) => {
            if (response['state'] === "success") {
                location.reload();
            }
        });
    });


    //Lecke gyakorlása
    $("body").on("click", "[id^='practice-set-']", function () {
        // var setId = $(this).attr("id").slice(13);
        ajaxCall(toArray("location", "practice"), function (response) {
            console.log(response);
            if (response['state'] === "success") {
                location.reload();
            }
        });
    });

    //Kártya hozzáadása
    $("body").on("click", "#add-card", function () {
        var setId = $(this).attr("id").slice(13);
        ajaxCall(toArray("add_card",), function (response) {
            $("#cards_container").prepend(response['element']).hide().fadeIn("slow");
            let rawcardnum = localStorage.getItem('raw-card-num')
            $('#cards_container').find('raw-delete-card-btn').attr('id', `raw-delete-card-btn-${rawcardnum}`);
            rawcardnum++;
            localStorage.setItem("raw-card-num", rawcardnum);
        });
    });

    //Nyers állítás kezelése
    $("body").on("blur", ".raw-definition", function () {
        let statementTag = $(this).parent().parent().children(':nth-child(1)').children(':nth-child(2)');
        let definitionTag = $(this);
        let statement = testInput($(statementTag).val());
        let definition = testInput($(definitionTag).val());
        if (statement != "" && definition != "") {
            let id = newCards.getIdCounter();
            newCards.addCard(statement, definition);
            $(statementTag).removeClass("raw-statement");
            $(definitionTag).removeClass("raw-definition");
            $(statementTag).attr("id", "new-statement-" + id);
            $(definitionTag).attr("id", "new-definition-" + id);
            localStorage.setItem('unsaved', true);
        }
    });
    //Nyers definíció kezelése

    $('body').on('click', '[id^=raw-delete-card-btn]', function (e) {
        e.preventDefault;
        if (e.target.tagName == 'BUTTON') {
            console.log($(e.target).attr('id'))
            $(e.target).parent().parent().parent().parent().parent().fadeOut("slow", function () { $(this).remove(); });

        } else if (e.target.tagName == 'I') {
            console.log($(e.target).parent().attr('id'))
            $(e.target).parent().parent().parent().parent().parent().parent().fadeOut("slow", function () { $(this).remove(); });

        }
    });

    //Új definíció hozzáadása
    $('body').on("blur", "[id^=new-definition-]", function (e) {
        let id = $(this).attr("id").slice(15);
        let value = $(this).val();
        newCards.updateAttr("definition", id, value);
    });

    //Új állítás hozzáadása
    $('body').on("blur", "[id^=new-statement-]", function (e) {
        let id = $(this).attr("id").slice(14);
        let value = $(this).val();
        newCards.updateAttr("statement", id, value);
    });

    //Régi lecke módosítása
    $('body').on("blur", "[id^=definition-]", function (e) {
        let id = $(this).attr("id").slice(11);
        let value = $(this).val();
        cards.updateAttr("definition", id, value);
        localStorage.setItem("unsaved", true);
    });

    $('body').on("blur", "[id^=statement-]", function (e) {
        let id = $(this).attr("id").slice(10);
        let value = $(this).val();
        cards.updateAttr("statement", id, value);
        localStorage.setItem("unsaved", true);
    });


    //Lecke mentése
    $("body").on("click", "[id^='save-set-']", function () {
        let selectedCards;
        let selectedNewCards;
        if (cards.getCards().length > 0) {
            selectedCards = cards.getCards();
        }
        if (newCards.getCards().length > 0) {
            selectedNewCards = newCards.getCards()
        }
        ajaxCall(toArray("update_cards", selectedCards, selectedNewCards), (response) => {
            console.log(response)
            if (response['state'] === 'success') {
                localStorage.removeItem('unsaved');
                cards.unsetCards();
                newCards.unsetCards();
                requestCards();
            }
        });
    });
    //Kártya törlése
    $('body').on("click", "button[id^='delete-card-btn-']", (e) => {
        let cardId = undefined;
        if (e.target.tagName == 'BUTTON') {
            cardId = e.target.id.slice(16);

        } else if (e.target.tagName == 'I') {
            cardId = $(e.target).parent().attr('id').slice(16);
        }
        ajaxCall(toArray("delete_card", cardId), function (response) {
            console.log(response);
            // $('#cards_container').empty();
            // requestCards();

            $('#delete-card-btn-' + cardId).parent().parent().parent().parent().parent()
                .fadeOut("slow", function () { $(this).remove(); });
            if (response['state'] === "success") {
                cards = new Cards(response['cards']);
                console.log("Response kártyák:");
                console.table(response['cards']);
                console.log("Client kártyák:");
                console.table(cards.getCards());
            } else if (response['state'] === 'error') {
                cards.unsetCards();
            }
        });

    });
});