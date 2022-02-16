<?php
//Eltaltált kártyák statisztikája
function createStatistics($knownCards, $unknownCards)
{
    $returnText = '
    <div class="custom-statistics mt-3 table-responsive">
    <table class="table table-bordered custom-table-dark">
    <thead>
        <tr>
            <th>
            <h5>Eltalált szavak</h5>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row"></th>
            <td>
                <h5>Állítás</h5>
            </td>
            <td>
                <h5>Definíció</h5>
            </td>
        </tr>';
    for ($i = 0; $i < count($knownCards); $i++) {
        $card = (array)$knownCards[$i];
        $returnText .= '
        <tr>
            <th scope="row">' . $i + 1 . '</th>
            <td>' . $card['statement'] . '</td>
            <td>' . $card['definition'] . '</td>
        </tr>';
    }
    $returnText .= '</tbody>
</table></div>';
    //Hibás szavak
    $returnText .= '<div class="custom-statistics mt-3 table-responsive">
    <table class="table table-bordered custom-table-dark">
    <thead>
        <tr>
            <th>
            <h5>Elhibázott szavak</h5>
            </th>
        </tr>
    </thead>
<tbody>
    <tr>
        <th scope="row"></th>
        <td>
            <h5>Állítás</h5>
        </td>
        <td>
            <h5>Definíció</h5>
        </td>
    </tr>';
    for ($i = 0; $i < count($unknownCards); $i++) {
        $card = (array)$unknownCards[$i];
        $returnText .= '
        <tr>
            <th scope="row">' . $i + 1 . '</th>
            <td>' . $card['statement'] . '</td>
            <td>' . $card['definition'] . '</td>
        </tr>';
    }
    $returnText .= '</tbody>
</table></div>';
    return $returnText;
}
