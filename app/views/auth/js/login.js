// Fonction pour récupérer la valeur d'un cookie
function getCookie(name) {
  let value = "; " + document.cookie;
  let parts = value.split("; " + name + "=");
  if (parts.length === 2) return parts.pop().split(";").shift();
}

// Récupérer le token CSRF depuis le cookie
const csrf_token = getCookie("csrf_token");

document.getElementById("login-form").addEventListener("submit", async (event) => {
  event.preventDefault();

  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;

  const errorMessage = document.getElementById("error-message");

  try {
    // let response = await fetch("/auth/login", {
    //   method: "POST",
    //   headers: { "Content-Type": "application/json" },
    //   body: JSON.stringify({ username, password, csrf_token }) // Ajout du token si nécessaire
    // });
    let response = await fetch("/auth/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-Token": csrf_token // Envoyer le token via un header
      },
      body: JSON.stringify({ username, password }),
      credentials: "include" // Important pour que les cookies soient envoyés
    });

    let data;
    try {
      data = await response.json(); // S'assure que la réponse est bien du JSON
    } catch (e) {
      throw new Error("Réponse invalide du serveur");
    }

    if (!response.ok) {
      errorMessage.textContent = data.error || "Connexion échouée.";
      errorMessage.classList.remove("hidden");
      return;
    }

    // Stocke l'utilisateur si nécessaire
    localStorage.setItem("user", JSON.stringify(data.user));

    // Redirige après connexion
    window.location.href = "/";
  } catch (error) {
    errorMessage.textContent = "Erreur réseau, réessayez.";
    errorMessage.classList.remove("hidden");
    console.error("Erreur de connexion :", error);
  }
});
