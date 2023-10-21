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

      // Affichage du formulaire
      editForm.style.display = "block";

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

          if (fieldValue === null) {
            console.log(
              `Erreur : champ de formulaire avec l'attribut 'name' "${fieldName}" n'a pas été trouvé.`
            );
            // return;
          }

          if (
            fieldName === "distance_elliptique" ||
            fieldName === "calories_elliptique" ||
            fieldName === "distance_marche"
          ) {
            // Retirer les unités et affecter la valeur numérique
            field.value = parseFloat(fieldValue.textContent);
          } else {
            field.value = fieldValue.textContent;
          }

          console.log(`Valeur affectée pour ${fieldName}:`, field.value);
        }
      });
    });
  });
});
