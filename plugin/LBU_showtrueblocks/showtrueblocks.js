jQuery(document).ready(function ($) {
  // Vérifier si l'élément unique à la page tableau-de-bord est présent
  if ($("#tableau-de-bord").length) {
    // Parcourir les éléments de la variable sportlist_champs
    $.each(sportlist_champs, function (nom_champ, valeur_champ) {
      // Si la valeur du champ est 0 (false), cacher l'élément du DOM correspondant
      if (valeur_champ == 0) {
        $("#" + nom_champ).hide();
      } else {
        // Si la valeur du champ est 1 (true), afficher l'élément du DOM correspondant
        $("#" + nom_champ).show();
      }
    });
  }
});
