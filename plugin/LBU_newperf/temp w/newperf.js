document.addEventListener("DOMContentLoaded", function (event) {
  // Sélectionner le bouton "Enregistrer" par son identifiant
  const enregistrerButton = document.querySelector("#recordoneday-btn");

  // Vérifier si l'élément existe avant d'ajouter l'événement "click"
  if (enregistrerButton) {
    // Ajouter un gestionnaire d'événements "click" sur le bouton "Enregistrer"
    enregistrerButton.addEventListener("click", function (event) {
      // Empêcher le comportement par défaut du bouton
      event.preventDefault();
      alert("coucou le js !");
      // Sélectionner le formulaire correspondant par son identifiant ou sa classe
      const form = document.querySelector(".oneday-form"); // remplacer "#my-form" par l'identifiant ou la classe de votre formulaire

      // Soumettre le formulaire
      form.submit();
    });
  }
});
