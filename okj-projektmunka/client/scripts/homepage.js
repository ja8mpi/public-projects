$('document').ready(() => {
    $('body').empty();
    ajaxCall(toArray("getElement", "primaryNavbar", "logged_in"), (response) => {
        $("body").prepend(response['element']);
    });
    // $("#set").on("click", (e) => {
    //     e.preventDefault();
    //     ajaxCall(toArray("getElement", "sets"), (response) => {
    //         location.reload();
    //     });
    // })
    // $("body").on("click", "#logout", (e) => {
    //     e.preventDefault();
    //     ajaxCall(toArray("logout", "logout"), (response) => {
    //         if (response['state'] === "success") {
    //             location.reload();
    //         }
    //     });
    // });

    // //Az oldal változtatása
    // $("body").on("click", ".custom-nav-link", (e) => {
    //     e.preventDefault();
    //     ajaxCall(toArray("location", e.target.id), (response) => {
    //         if (response['state'] === "success") {
    //             location.reload();
    //         }
    //     })
    // });
});