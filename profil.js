// Fonction pour afficher/masquer le mot de passe
$(document).ready(function() {
  $('#toggle-password').click(function() {
    var passwordInput = $('#password');
    var passwordFieldType = passwordInput.attr('type');

    if (passwordFieldType === 'password') {
      passwordInput.attr('type', 'text');
      $('#toggle-password').text('Masquer');
    } else {
      passwordInput.attr('type', 'password');
      $('#toggle-password').text('Afficher');
    }
  });
});

$(document).ready(function () {
    // Écouter l'événement de soumission du formulaire
    $('#profile-form').on('submit', function (e) {
        e.preventDefault(); // Empêcher la soumission du formulaire par défaut

        // Récupérer les valeurs des champs de formulaire
        var login = $('#login').val();
        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var password = $('#password').val();

        const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordPattern.test(password)) {
            alert("Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.");
            return
        }

        // Envoyer les données au serveur via AJAX
        $.ajax({
            url: 'profil.php', // L'URL du script de traitement
            method: 'POST',
            data: {login: login, firstname: firstname, lastname: lastname, password: password}, // Les données à envoyer
            success: function (response) {
                alert('Information modifié avec succés !');
            },
            error: function (xhr, status, error) {
                // Gérer les erreurs éventuelles
                console.error(error);
            }
        });
    });
});