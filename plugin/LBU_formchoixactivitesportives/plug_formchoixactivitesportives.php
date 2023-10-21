<?php
/*
Plugin Name: 0_LBU_plug_sportform
Description: Affiche un formulaire pour sélectionner des activités sportives.
Version: 1.0
Author: LeBonUnivers
*/

function sports_form_shortcode()
{
    ob_start();
?>
<form method="post" id="sports-form" action="" class="sportlist-form">
    <div class="sportlist-field">
        <input type="checkbox" id="velo_elliptique" name="velo_elliptique" value="1" class="sportlist-checkbox">
        <label for="velo_elliptique" class="sportlist-label">Vélo elliptique</label>
    </div>
    <div class="sportlist-field">
        <input type="checkbox" id="pompes" name="pompes" value="1" class="sportlist-checkbox">
        <label for="pompes" class="sportlist-label">Pompes</label>
    </div>

    <div class="sportlist-field">
        <input type="checkbox" id="abdominaux" name="abdominaux" value="1" class="sportlist-checkbox">
        <label for="abdominaux" class="sportlist-label">Abdominaux</label>
    </div>
    <div class="sportlist-field">
        <input type="checkbox" id="squat" name="squat" value="1" class="sportlist-checkbox">
        <label for="squat" class="sportlist-label">Squat</label>
    </div>
    <div class="sportlist-field">
        <input type="checkbox" id="gainage" name="gainage" value="1" class="sportlist-checkbox">
        <label for="gainage" class="sportlist-label">Gainage</label>
    </div>
    <div class="sportlist-field">
        <input type="checkbox" id="traction" name="traction" value="1" class="sportlist-checkbox">
        <label for="traction" class="sportlist-label">Traction</label>
    </div>
    <div class="sportlist-field">
        <input type="checkbox" id="musculation" name="musculation" value="1" class="sportlist-checkbox">
        <label for="musculation" class="sportlist-label">Musculation</label>
    </div>
    <div class="sportlist-field">
        <input type="checkbox" id="boxe" name="boxe" value="1" class="sportlist-checkbox">
        <label for="boxe" class="sportlist-label">Boxe</label>
    </div>
    <div class="sportlist-field">
        <input type="checkbox" id="marche" name="marche" value="1" class="sportlist-checkbox">
        <label for="marche" class="sportlist-label">Marche</label>
    </div>
    <div class="sportlist-field">
        <input type="submit" name="submit" value="Enregistrer mes choix" class="sportlist-submit">
    </div>
</form>
<?php
    return ob_get_clean();
}

// Création du shortcode [sports_form]
add_shortcode('sports_form', 'sports_form_shortcode');