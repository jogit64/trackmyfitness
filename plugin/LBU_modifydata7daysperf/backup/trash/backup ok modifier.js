document.addEventListener("DOMContentLoaded", function () {
  // Sélection des boutons "Modifier"
  const editButtons = document.querySelectorAll(".edit-button");

  // Boucle pour ajouter un événement click à chaque bouton
  editButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      // Récupération de l'identifiant de la catégorie
      alert("coucou");
      const categoryId = event.target.getAttribute("data-category");
      // Sélection de la div correspondante
      const categoryDiv = document.querySelector(`#${categoryId}`);
      // Sélection du formulaire correspondant
      const editForm = categoryDiv.querySelector(".edit-form");
      // Affichage du formulaire
      editForm.style.display = "block";

      // Récupération des champs du formulaire
      const formFields = editForm.querySelectorAll("input, select, textarea");

      // Pré-remplissage des champs avec les valeurs actuelles
      formFields.forEach((field) => {
        const fieldName = field.getAttribute("name");
        const fieldValue = categoryDiv.querySelector(
          `[data-field="${fieldName}"]`
        ).textContent;
        field.value = fieldValue;
      });
    });
  });
});
