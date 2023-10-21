document.addEventListener("DOMContentLoaded", function () {
  // Sélection des boutons "Modifier"
  const editButtons = document.querySelectorAll(".edit-button");

  // Boucle pour ajouter un événement click à chaque bouton
  editButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      // Récupération de l'identifiant de la catégorie
      const categoryId = event.target.getAttribute("data-category");
      //console.log(`categoryId: ${categoryId}`);

      // Sélection de la div correspondante
      const categoryDiv = event.currentTarget.closest(`div#${categoryId}`);
      console.log(categoryDiv);

      // Sélection du formulaire correspondant
      const editForm = categoryDiv.querySelector(".edit-form");

      // Vérifiez si le formulaire est actuellement affiché
      if (editForm.style.display === "block") {
        // Cachez le formulaire
        editForm.style.display = "none";
      } else {
        // Affichez le formulaire
        editForm.style.display = "block";
      }

      // Sélection du champ "category" et affectation de la valeur
      const categoryField = editForm.querySelector('input[name="category"]');
      if (categoryField) {
        categoryField.value = categoryId;
      }

      // Récupération des champs du formulaire
      const formFields = editForm.querySelectorAll("input, select, textarea");

      // Pré-remplissage des champs avec les valeurs actuelles (sans le champ "category")
      formFields.forEach((field) => {
        const fieldName = field.getAttribute("name");

        // Ignore le champ "category" et le champ "record_id"
        if (fieldName !== "category" && fieldName !== "record_id") {
          const fieldValue = categoryDiv.querySelector(
            `[data-field="${fieldName}"]`
          );

          // console.log(`fieldValue pour ${fieldName}:`, fieldValue);

          if (fieldValue) {
            // Check if fieldValue exists
            if (fieldName === "poids_kg") {
              // Affecter la valeur numérique directement
              field.value = parseFloat(fieldValue.textContent);
            } else if (
              fieldName === "distance_elliptique" ||
              fieldName === "calories_elliptique" ||
              fieldName === "distance_marche"
            ) {
              // Retirer les unités et affecter la valeur numérique
              field.value = parseFloat(fieldValue.textContent);
            } else {
              field.value = fieldValue.textContent;
            }
          }

          console.log(`Valeur affectée pour ${fieldName}:`, field.value);
        }
      });
    });
  });

  // Ecoute du bouton supprimer et envoi vers traitement pour suppression
  const deleteButtons = document.querySelectorAll(".delete-button");
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      // Empêche le comportement par défaut du bouton
      event.preventDefault();

      // Affiche la boîte de dialogue de confirmation
      const confirmation = confirm(
        "Êtes-vous sûr de vouloir supprimer cet enregistrement ?"
      );

      // Si l'utilisateur confirme la suppression
      if (confirmation) {
        // Soumettre le formulaire de suppression
        const deleteForm = document.getElementById("delete-form");
        deleteForm.submit();
      }
    });
  });
});
